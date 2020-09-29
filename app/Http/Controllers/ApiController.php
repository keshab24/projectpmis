<?php

namespace PMIS\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PMIS\ActivityLog;
use PMIS\ActivityLogFiles;
use PMIS\AuthorizedPerson;
use PMIS\BudgetTopic;
use PMIS\Contractor;
use PMIS\CurrentProgress;
use PMIS\DateCon;
use PMIS\District;
use PMIS\Document;
use PMIS\Fiscalyear;
use PMIS\Http\Controllers\Admin\PushNotification;
use PMIS\Http\Controllers\Admin\SelectUserTrait;
use PMIS\Http\Resources\ProjectCollection;
use PMIS\Http\Resources\ProjectDetailCollection;
use PMIS\ImplementingOffice;
use PMIS\NotificationType;
use PMIS\ProcurementDate;
use PMIS\Project;
use PMIS\ProjectGroup;
use PMIS\projectListerTrait;
use PMIS\User;

class ApiController extends Controller
{
    use SelectUserTrait;

    use projectListerTrait;
    private $project;
    private $user;
    private $userToken;
    private $pro_data;
    private $pro_fiscal_year;

    public function __construct(Project $project, User $user, Request $request)
    {
        date_default_timezone_set("Asia/Kathmandu");
        parent::__construct();
        if (!$request->has('token')) {
            abort(404);
        } else {
            $this->userToken = $request->get('token');
            $this->user = $user->whereToken($this->userToken)->first();
            if (!$this->user) {
                abort(403);
            }
            $model = $this->user->typeFlag->type;
            $functionListener = 'project' . $model;
            $project = $project->join('pro_project_settings', 'pro_project_settings.project_id', '=', 'pro_projects.id')->select('pro_projects.*', 'pro_project_settings.id as setting_id', 'pro_project_settings.fy', 'pro_project_settings.budget_id', 'pro_project_settings.expenditure_id', 'pro_project_settings.implementing_id', 'pro_project_settings.monitoring_id', 'pro_project_settings.code');
            $project = $this->$functionListener($project);
            if ($this->user->typeFlag->type != "District") {
                $this->project = $project->whereNotIn('monitoring_id', androidUnAuthorized());
            } else {
                $this->project = $project->whereNotIn('district_id', androidUnAuthorized());
            }
            if ($request->has('fy_id')) {
                $this->pro_fiscal_year = Fiscalyear::find($request->get('fy_id'));
            } elseif ($request->has('fiscal_year')) {
                $this->pro_fiscal_year = Fiscalyear::find($request->get('fiscal_year'));
            } else {
                $this->pro_fiscal_year = Fiscalyear::where('fy', explode('/', getFiscalyearFromDate(dateBS(date('Y-m-d'))))[0] . '-' . explode('/', getFiscalyearFromDate(dateBS(date('Y-m-d'))))[1])->first();
            }
            $project = $project->where('fy', $this->pro_fiscal_year->id);
            $this->project = $project;
        }
        auth()->loginUsingId($this->user->id);
    }


    public function getProjectDetail($project_code)
    {
        $project = $this->project->find($project_code);
        if (!$project) {
            return json_encode(404);
        }
        $project_detail = $this->detail($project);

        return response()->json($project_detail, 200);
    }

    public function detail($project)
    {
        if (isset(nature_of_project()['eng'][$project->monitoring_office_id][$project->nature_of_project_id])) {
            $data['construction_type'] = $project->nature_of_project_id ? nature_of_project()['eng'][$project->monitoring_office_id][$project->nature_of_project_id] : "N/A";
        } else {
            $data['construction_type'] = "N/a";
        }

        $data['authorized_person'] = $project->authorizedPersons()->select('nep_name', 'name', 'phone', 'mobile', 'email')->get();
        if ($project->procurement->Contractor != null) {
            $data['contractor'] = $project->procurement->Contractor->name ? $project->procurement->Contractor->name : $project->procurement->Contractor->nep_name;
            $data['contractor_address'] = $project->procurement->Contractor->address;
        }
        if ($project->procurement->JointVenture != null) {
            $data['contractor'] = $project->procurement->JointVenture->name;
            $data['contractor_address'] = $project->procurement->JointVenture->address;
        }

        $progressCollection = collect([]);
        for ($i = $project->start_fy_id; $i <= $this->pro_fiscal_year->id; $i++) {
            $progresses_ = $project->progresses()->where('fy_id', $i)->orderBy('fy_id', 'desc')->orderBy('month_id', 'desc')->with('progressTrack')->get();
            if ($progresses_->count() != 0) {
                if ($progresses_->first()->month_id == 12) {
                    $progressCollection->push($progresses_->first());
                } else {
                    $progresses_->each(function ($item, $key) use ($progressCollection) {
                        $progressCollection->push($item);
                    });
                }
            }
        }
        $yearlyBudget = 0;
        $IdOffFiscalYearNow = $this->pro_fiscal_year->id;

        $thisYearAllocation = $project->allocation()->where('fy_id', $IdOffFiscalYearNow)->orderBy('amendment', 'desc')->first();

        if ($thisYearAllocation) {
            $yearlyBudget = floatval($thisYearAllocation->total_budget);
        }
        $data['yearly_budget'] = $yearlyBudget;
        $totalExpenditure = 0;
        $old_progresses = $project->progresses()->where('month_id', 12)->where('fy_id', '<>', $IdOffFiscalYearNow)->get();
        $latestProgress = $project->progresses()->where('fy_id', $IdOffFiscalYearNow)->get();

        foreach ($old_progresses as $old_progress) {
            $totalExpenditure += $old_progress->bill_exp;
        }
        if ($latestProgress->count() != 0) {
            if ($latestProgress->last()->month_id == 12) {
                $totalExpenditure += $latestProgress->last()->bill_exp;
            } else {
                foreach ($latestProgress as $lastProgress) {
                    $totalExpenditure += $lastProgress->bill_exp;
                }
            }
        }
        $projectCost = $project->projectCost();
        $data['monetary_progress'] = 0;
        try {
            $data['monetary_progress'] = $totalExpenditure * 1000 / $projectCost * 100;
        } catch (\Exception $e) {
        }

        $data['physical_progress'] = 'N/A';
        $data['yearly_physical_progress'] = 0;
        $data['yearly_monetary_progress'] = 0;
        $contingency = 1 + $project->procurement->contingency / 100;
        $data['contract_with_vat'] = number_format(round(($project->procurement->contract_amount * $contingency) * 1.13 / 1000, 2), 3);


        $headquarter = $project->headquarter ? insideHeadquarter()[$project->headquarter] : insideHeadquarter()[0];
        if (isset(nature_of_project()['eng'][$project->monitoring_office_id][$project->nature_of_project_id])) {
            $construction_type = $project->nature_of_project_id ? nature_of_project()['eng'][$project->monitoring_office_id][$project->nature_of_project_id] : "N/A";
        } else {
            $construction_type = "N/a";
        }
        if ($project->procurement->Contractor != null) {
            $contractor = $project->procurement->Contractor->name ? $project->procurement->Contractor->name : $project->procurement->Contractor->nep_name;
            $contractor_address = $project->procurement->Contractor->address;
        }
        if ($project->procurement->JointVenture != null) {
            $contractor = $project->procurement->JointVenture->name;
            $contractor_address = $project->procurement->JointVenture->address;
        }

        //activity log hune field haru
        $activity_logs_files = [];
        foreach (fieldsThatCanHaveActivityLogs() as $activity_type => $field_name) {
            $some = $project->activityLogs()->where('type', $activity_type)->get();
            if (count($some)) {
                foreach ($some as $activity) {
                    foreach ($activity->ActivityLogFiles as $file) {
                        $activity_logs_files[$field_name][] = [
                            'name' => $file->file_path,
                            'title' => $activity->title,
                            'type' => $activity->type,
                            'desc' => $activity->description,
                        ];
                    }
                }
            } else {
                $activity_logs_files[$field_name] = [];
            }
        }
        $project_detail['details'] = [
            'name' => $project->name,
            'name_eng' => $project->name_eng,
            'project_code' => $project->project_code,
            'address' => $project->district->name . ', ' . $project->district->zone->name . ', ' . $project->district->State->name,
            'project_current_status' => '',//running, completed, handover
            'project_current_status_date' => '',//running-expires-at, completed-completed-at, handover-date
            'division' => $project->implementing_office->name,
            'notifications_count' => count($project->app_notifications),
            'chat_notifications_count' => count($project->chat_notifications),
            /*'notifications' => $project->app_notifications,
            'chat_notifications' => $project->chat_notifications,*/
            'time_extension_count' => count($project->timeExtension),
            'lat' => $project->coordinates ? explode(',', $project->coordinates)[0] : null,
            'long' => $project->coordinates ? (array_key_exists(1, explode(',', $project->coordinates)) ? explode(',', $project->coordinates)[1] : null) : null,
        ];
        $project_detail['project_details'] = [
            ['text' => 'आयोजना कोड', 'value' => $project->project_code, 'eye' => false, 'documents' => []],
            ['text' => 'कार्यालय', 'value' => $project->implementing_office->name, 'eye' => false, 'documents' => []],
            ['text' => 'भौगोलिक क्षेत्र', 'value' => getLand()[$project->district->geo_id], 'eye' => false, 'documents' => []],
            ['text' => 'सदरमुकाम भित्र', 'value' => $headquarter, 'eye' => false, 'documents' => []],
            ['text' => 'आधार वर्ष', 'value' => $project->fiscal_year->fy, 'eye' => false, 'documents' => []],
            ['text' => 'भवनको तल्ला', 'value' => $project->story_area_unite ? getStoreyArea()[$project->story_area_unite] : "N/A", 'eye' => false, 'documents' => []],
            ['text' => 'भवनको क्षेत्रफल', 'value' => $project->bstype ? bsType()[$project->bstype] : "N/A", 'eye' => false, 'documents' => []],
            ['text' => 'प्रकार', 'value' => $construction_type, 'eye' => false, 'documents' => []],
            ['text' => 'ग्रुप', 'value' => $project->group ? $project->group->parent->name : 'N/A', 'eye' => false, 'documents' => []],
            ['text' => 'सव ग्रुप', 'value' => $project->group ? $project->group->name : 'N/A', 'eye' => false, 'documents' => []],
            ['text' => 'निर्माण स्थल', 'value' => $project->consturctionLocatedArea ? $project->consturctionLocatedArea->located_area : 'N/A', 'eye' => false, 'documents' => []],
            ['text' => 'निर्माणको स्थिति', 'value' => $project->show_on_running == 1 ? "Running" : "Completed", 'eye' => false, 'documents' => []],
            ['text' => 'सम्पन्न हुनु पर्ने मिति', 'value' => $project->completed_date, 'eye' => false, 'documents' => []],
            ['text' => 'हस्तान्तरण मिति', 'value' => $project->ho_date, 'eye' => count($activity_logs_files['ho_date']) ? true : false, 'documents' => $activity_logs_files['ho_date']],
            ['text' => 'जग्गाको स्वामित्व', 'value' => $project->swamittwo ? swamittwo()[$project->swamittwo] : "N/A", 'eye' => count($activity_logs_files['swamittwo']) ? true : false, 'documents' => $activity_logs_files['swamittwo'],],
            ['text' => 'कित्ता नं', 'value' => $project->kittanumber, 'eye' => false, 'documents' => []],
            ['text' => 'सीट नं', 'value' => $project->shitnumber, 'eye' => false, 'documents' => []],
            ['text' => 'माटो परिक्षण भए/नभएको', 'value' => $project->soiltest ? soilTest()[$project->soiltest] : 'N/A', 'eye' => count($activity_logs_files['soiltest']) ? true : false, 'documents' => $activity_logs_files['soiltest'],],
            ['text' => 'भार बहन क्षमता', 'value' => $project->baringcapacity . 'KN/m²', 'eye' => false, 'documents' => []],
            ['text' => 'संरचनाको प्रकृति', 'value' => $project->bstype ? bsType()[$project->bstype] : "N/A", 'eye' => false, 'documents' => []],
            ['text' => 'छतको प्रकृति', 'value' => $project->rooftype ? rooftype()[$project->rooftype] : "N/A", 'eye' => false, 'documents' => []],
            ['text' => 'झ्याल-ढोकाको प्रकृति', 'value' => $project->doorwindow ? doorWindow()[$project->doorwindow] : "N/A", 'eye' => false, 'documents' => []],
            ['text' => 'गारोको प्रकृति', 'value' => $project->wall_type ? wallType()[$project->wall_type] : "N/A", 'eye' => false, 'documents' => []],
            ['text' => 'डिजाइन प्रकार', 'value' => $project->design_type ? designType()[$project->design_type] : "N/A", 'eye' => false, 'documents' => []],
            ['text' => 'कैफियत', 'value' => $project->description, 'eye' => false, 'documents' => []],
        ];

        $project_detail['contractor'] = [
            ['text' => 'निर्माण ब्यबसायी', 'value' => isset($contractor) ? $contractor : 'N/A', 'eye' => false, 'documents' => []],
            ['text' => 'ठेगाना', 'value' => isset($contractor_address) ? $contractor_address : 'N/A', 'eye' => false, 'documents' => []],
        ];

        $authorized_persons[] = ['s_no' => 'क्र.स.', 'name' => 'नाम', 'phone' => 'फोन', 'mobile' => 'मोबाइल', 'email' => 'इमेल'];
        foreach ($data['authorized_person'] as $index => $person) {
            $authorized_persons[] = ['s_no' => ++$index, 'name' => $person->name_default, 'phone' => $person->phone, 'mobile' => $person->mobile, 'email' => $person->email];
        }
        $project_detail['authorized_persons'] = $authorized_persons;
        $project_detail['procurement'] = [
            ['text' => 'बजेट शिर्षक: ', 'value' => $project->budget_topic->budget_head, 'eye' => false, 'documents' => []],
            ['text' => 'ब.उ.शी.नं: ', 'value' => $project->budget_topic->budget_topic_num, 'eye' => false, 'documents' => []],
            ['text' => 'प्राथमिकता: ', 'value' => $project->budget_topic->priority, 'eye' => false, 'documents' => []],
//            ['text' => 'लागत', 'value' => formatcurrency($project->procurement->estimated_amount), 'eye' => false, 'documents' => []],
            ['text' => 'कुल दायित्व', 'value' => $project->procurement->total_liability ?? 'N/A', 'eye' => false, 'documents' => []],
            ['text' => 'स्वीकृत ल.ई.(भ्याट तथा कन्टिन्जेन्सी बाहेक): ', 'value' => formatcurrency($project->procurement->con_est_amt_net), 'eye' => false, 'documents' => []],
            ['text' => 'ल.ई स्वीकृत मिति: ', 'value' => $project->procurement->est_approved_date, 'eye' => count($project->activityLogs->where('type', 16)) ? true : false, 'documents' => count($project->activityLogs->where('type', 16))],
            ['text' => 'सम्झौता रकम (भ्याट तथा कन्टिन्जेन्सी बाहेक): ', 'value' => formatcurrency($project->procurement->contract_amount), 'eye' => false, 'documents' => []],
            ['text' => 'कार्यक्रम लागत: ', 'value' => formatcurrency($project->projectCost()), 'eye' => false, 'documents' => []],
            ['text' => 'सम्झौता बिधि: ', 'value' => $project->procurement->method, 'eye' => false, 'documents' => []],
            ['text' => 'डिजाइन स्वीकृत मिति: ', 'value' => $project->procurement->design_est_swikrit_miti, 'eye' => count($activity_logs_files['design_est_swikrit_miti']) ? true : false, 'documents' => $activity_logs_files['design_est_swikrit_miti']],
            ['text' => 'बोलपत्र स्वीकृत मिति: ', 'value' => $project->procurement->bolapatraswikriti, 'eye' => count($activity_logs_files['bolapatraswikriti']) ? true : false, 'documents' => $activity_logs_files['bolapatraswikriti']],
            ['text' => 'बोलपत्र तयारी मिति: ', 'value' => $project->procurement->bid_does_ready_act, 'eye' => false, 'documents' => []],
            ['text' => 'No Objection act1: ', 'value' => $project->procurement->no_obj_act1, 'eye' => count($activity_logs_files['no_obj_act1']) ? true : false, 'documents' => $activity_logs_files['no_obj_act1']],
            ['text' => 'बोलपत्र आव्हान मिति: ', 'value' => $project->procurement->call_for_bid_act, 'eye' => count($activity_logs_files['call_for_bid_act']) ? true : false, 'documents' => $activity_logs_files['call_for_bid_act']],
            ['text' => 'बोलपत्र खुलेको मिति: ', 'value' => $project->procurement->bid_open_act, 'eye' => count($activity_logs_files['bid_open_act']) ? true : false, 'documents' => $activity_logs_files['bid_open_act']],
            ['text' => 'बोलपत्र मुल्यांकन सम्पन्न मिति: ', 'value' => $project->procurement->bid_eval_act, 'eye' => count($activity_logs_files['bid_eval_act']) ? true : false, 'documents' => $activity_logs_files['bid_eval_act']],
            ['text' => 'No Objection act2: ', 'value' => $project->procurement->no_obj_act2, 'eye' => count($activity_logs_files['no_obj_act2']) ? true : false, 'documents' => $activity_logs_files['no_obj_act2']],
            ['text' => 'सम्झौता मिति: ', 'value' => $project->procurement->contract_date, 'eye' => count($activity_logs_files['contract_date']) ? true : false, 'documents' => $activity_logs_files['contract_date']],
            ['text' => 'सम्पन्न हुनु पर्ने मिति: ', 'value' => $project->procurement->completion_date, 'eye' => false, 'documents' => []],
            ['text' => 'कार्यादेश दिईएको मिति: ', 'value' => $project->procurement->wo_date, 'eye' => count($activity_logs_files['wo_date']) ? true : false, 'documents' => $activity_logs_files['wo_date']],
            ['text' => 'ठेक्का नं: ', 'value' => $project->procurement->con_id_div, 'eye' => false, 'documents' => []],
            ['text' => 'वार्षिक बजेट (रू हजारमा): ', 'value' => formatcurrency($yearlyBudget), 'eye' => false, 'documents' => []],
            ['text' => 'वार्षिक बित्तिय प्रगति: ', 'value' => formatcurrency($data['yearly_monetary_progress']), 'eye' => false, 'documents' => []],
            ['text' => 'वार्षिक भौतिक प्रगति (%): ', 'value' => $data['yearly_physical_progress'], 'eye' => false, 'documents' => []],
            ['text' => 'समग्र बित्तिय प्रगति: ', 'value' => formatcurrency($data['monetary_progress']), 'eye' => false, 'documents' => []],
            ['text' => 'समग्र भौतिक प्रगति (%): ', 'value' => $data['physical_progress'], 'eye' => false, 'documents' => []],
            ['text' => 'कैफियत: ', 'value' => $project->procurement->remarks, 'eye' => false, 'documents' => []],
        ];
        $procurements[] = ['s_no' => 'क्र.स.', 'company_name' => 'बैंक/कम्पनीको नाम', 'company_branch' => 'शाखा/ठेगाना', 'amount' => 'रकम(रू. हजारमा)', 'start_date' => 'शुरु मिति', 'end_date' => 'आन्तिम मिति', 'type' => 'किसिम', 'view' => 'हेर्नुहोस'];
        foreach ($project->ProcurementDates as $index => $procurementDate) {
            $procurements[] = ['s_no' => ++$index, 'company_name' => $procurementDate->company_name, 'company_branch' => $procurementDate->company_branch, 'amount' => $procurementDate->amount, 'start_date' => $procurementDate->start_date, 'end_date' => $procurementDate->end_date, 'type' => procurementDates()[$procurementDate->type], 'view' => $procurementDate->file ? [['name' => $procurementDate->file,
                'title' => '',
                'type' => '',
                'desc' => '',]] : []];
        }
        $project_detail['pg_apg_insurance'] = $procurements;

        $time_extensions[] = ['s_no' => 'क्र.स.', 'start_date' => 'देखि', 'end_date' => 'सम्म', 'extended_on' => 'निर्णय मिति', 'verified_from' => 'कहाँबाट भएको', 'liquidated_damage' => 'हर्जाना लागेको', 'remarks' => 'कैफियत', 'view' => 'हेर्नुहोस'];
        $time_extensions_of_project = $project->timeExtension()->orderBy('end_date', 'asc')->get();
        foreach ($time_extensions_of_project as $index => $timeExt) {
            $time_extensions[] = ['s_no' => ++$index, 'start_date' => $timeExt->start_date, 'end_date' => $timeExt->end_date, 'extended_on' => $timeExt->extended_on, 'verified_from' => verifiedFrom()[$timeExt->verified_from], 'liquidated_damage' => ($timeExt->liquidated_damage) ? 'लागेको' : 'नलागेको', 'remarks' => $timeExt->remarks, 'view' => $timeExt->file ? [[
                'name' => $timeExt->file,
                'title' => '',
                'type' => '',
                'desc' => '',
            ]] : []];
        }
        $project_detail['time_extension'] = $time_extensions;


        $total_expenses = 0;
        $progress_datas[] = ['s_no' => 'क्र.स.', 'fy' => 'आ.व', 'total' => 'खर्च (रू. हजारमा)', 'monetary_progress' => 'बित्तिय प्रगति Cumulative (%)', 'physical_progress' => 'भौतिक प्रगति (%)', 'current_status' => 'हालको स्थिति'];
        foreach ($progressCollection as $index => $progress) {
            if($project->projectCost() !== 0) {
                $monetary_progress = formatcurrency(($progress->bill_exp / $project->projectCost()) * 100);
            }else{
                $monetary_progress = 0;
            }
            $progress_datas[] = ['s_no' => ++$index, 'fy' => $progress->fy->fy, 'total' => formatcurrency($progress->bill_exp), 'monetary_progress' => $monetary_progress , 'physical_progresss' => $progress->current_physical_progress, 'current_status' => ($progress->progress_track) ? $progress->progress_track->progress : $progress->project_remarks];
            $total_expenses += $progress->bill_exp;
        }
        $progress_datas[] = ['s_no' => '', 'fy' => 'कुल खर्च (रू. हजारमा)', 'total' => number_format($total_expenses, 3), 'monetary_progress' => '', 'physical_progress' => '', 'current_status' => ''];
        $project_detail['expenses_progress'] = $progress_datas;
        $images = [];
        $project_images = $project->activityLogs()->where('type', 2)->get();
        if (count($project_images)) {
            foreach ($project_images as $activity) {
                foreach ($activity->ActivityLogFiles as $activityLogFile) {
                    array_push($images, ['name' => $activityLogFile->file_path, 'link' => '', 'title' => $activity->title, 'desc' => $activity->description]);
                }
            }
        }
        $project_detail['images'] = $images;

        $activityLogs = [];
        $project_activities = $project->activityLogs;
        if (count($project_activities)) {
            foreach ($project_activities as $activity) {
                foreach ($activity->ActivityLogFiles as $activityLogFile) {
                    array_push($activityLogs, [
                        'name' => $activityLogFile->file_path,
                        'title' => $activity->title,
                        'type' => $activity->type,
                        'desc' => $activity->description,
                    ]);
                }
            }
        }
        $project_detail['files'] = $activityLogs;

//        Added Variation Table
        $variations []= [
            's_no' => 'क्र.स',
            'decision_date' => 'निर्णय मिति',
            'amount' => 'रकम',
            'type' => 'प्रकार',
            'percentage' => 'प्रतिशत',
            'verified_from' => 'कहाँबाट भएको',
            'remarks' => 'कैफियत'
        ];
        $variations_choose=variations_choose();
        foreach($project->variation->sortByDesc('id') as $index=>$variation){
            $variations[] = [
                's_no' => $index + 1,
                'decision_date' => $variation->vope_date,
                'amount' => $variation->amount,
                'type' => $variations_choose[$variation->status],
                'percentage' => number_format(($variation->amount / $project->procurement->contract_amount) * 100, 3) . ' %',
                'verified_from' => verifiedFrom()[$variation->verified_from],
                'remarks' => $variation->remarks
            ];
        }
        $project_detail['variations'] = $variations;

//        Project Overview table

        $contract_date = $project->procurement->contract_date=='0000-00-00'? "N/A" : $project->procurement->contract_date;
        $completion_date_first_contract = $project->procurement->completion_date=='0000-00-00'? "N/A" : $project->procurement->completion_date;
        $completion_date_time_extension = $project->TimeExtension()->orderBy('end_date','desc')->first()->end_date ?? '';
        $contract_amount = number_format(round($project->procurement->contract_amount,2 ),2);
        $total_liability_amount = $project->procurement->total_liability !== null && $project->procurement->total_liability !== ""  ? $project->procurement->total_liability : 'N/A';
        $progresses_current_fy=$project->progresses()->where('fy_id','>=',$project->start_fy_id)->where('month_id',12)->where('fy_id','<',$this->pro_fiscal_year->id)->get();

        //FY DETAILS
        $current_fy = $this->pro_fiscal_year->id;

        $fiscal_years= [];
        $fiscal_years_title= [];
        $total_allocation = [];
        $financial_progress = [];
        $physical_progress = [];
        $end_fy = $current_fy;
        $fy_id = null;
        try{
            if($completion_date_first_contract !== ""){
                $fy_id = getFyId($completion_date_first_contract);
            }
            if($completion_date_time_extension !== "" && $completion_date_time_extension !== null){
                $fy_id = getFyId($completion_date_time_extension);
            }

            if($fy_id > $current_fy){
                $end_fy = $fy_id;
            }
        }catch (Exception $exception){
        }

        $fiscal_years = getFyFromTo($current_fy,$end_fy);
        //Financial Progress AND PHYSICAL PROGRESS Current Fy:

        $total_exp_this_fy = 0;
        foreach ($progresses_current_fy as $progress){
            $total_exp_this_fy += $progress->bill_exp;
            $total_exp_this_fy += $progress->cont_exp;
        }
        $total_expenditure = number_format($total_exp_this_fy,3) ?? 0;

        $total_liability_left = number_format(($project->projectCost() - $total_exp_this_fy),3);
        $financial_progress[] = $total_expenditure;
        $latest_progress =$project->progresses()->orderBy('id','desc')->first();
        if($latest_progress !== null)
            try{
                $remarks = ($latest_progress->progressTrack->progress !== null ? "(".$latest_progress->progressTrack->progress.")" : "(".$latest_progress->progressTrack->progress_eng.")") ?? '';
                $physical_progress[] = $latest_progress->progressTrack->physical_percentage.' %'.$remarks ?? 'N/A' ;
            }catch(\Exception $exception){
                $physical_progress[] = "N/A";
            }
        $fy_count = 1;
        foreach ($fiscal_years as $fy_id => $fiscal_year){
            if($fy_count <=3){
                $fiscal_years_title[] = $fiscal_year;
                $total_allocation[] = $project->allocation()->where('fy_id', $fy_id)->orderBy('amendment','desc')->first()->total_budget ?? '-';
                if($fy_id !== $current_fy){
                    $progress = $project->progresses()->where('fy_id','>=',$fy_id)->where('month_id',12)->first();
                    $financial_progress[] = $progress !== null ? ($progress->bill_exp + $progress->cont_exp) : '-';
                    $remarks = $progress !== null ? "(".$progress->progressTrack->progress.")" : '-';
                    $physical_progress[] = $progress !== null ? $progress->progressTrack->physical_percentage.' %'.$remarks ?? 'N/A' : '-';
                }
                $fy_count++;
            }
        }
        $total_allocation_this_fy = $project->allocation()->where('fy_id', $this->pro_fiscal_year->id)->orderBy('amendment','desc')->first()->total_budget ?? 0;



        //FY DETAILS

        $total_cost = $total_expenditure;
        $total_cost_percentage = $project->procurement->total_liability !== null && $project->procurement->total_liability !== "" && floatval($project->procurement->total_liability) > 0? (($total_exp_this_fy/floatval($project->procurement->total_liability))*100)." %" : '-';
        $total_liability_left_budget = $project->procurement->total_liability !== null && $project->procurement->total_liability !== "" && floatval($project->procurement->total_liability) > 0? number_format($project->procurement->total_liability -(floatval($total_expenditure) + $total_allocation_this_fy),3) ?? 0 : "N/A";
        $total_liability_left_expenditure = $project->procurement->total_liability !== null && $project->procurement->total_liability !== "" && floatval($project->procurement->total_liability) > 0? number_format($project->procurement->total_liability - floatval($total_expenditure),3) ?? 0 : "N/A";

        // Project Overview
        $start_fy_id = $project->start_fy_id;
        $end_fy_id = null;
//        if($contract_date) $start_fy_id= getFyId($contract_date);

        if($completion_date_time_extension){
            $end_fy_id=getFyId($completion_date_time_extension);
        }else{
            $end_fy_id=getFyId($completion_date_first_contract);
        }

        if ($start_fy_id && $end_fy_id){
//            $fys[] = getFyFromTo($start_fy_id, $end_fy_id);
            $i=1;
            foreach(getFyFromTo($start_fy_id, $end_fy_id) as $fy){
                $fys['field'.($i++)] = $fy;
            }
        }


        //filter fys before contract dates with not expenditure
        $fys_before_contract = getFyFromTo($start_fy_id, getFyId($contract_date)-1)->toArray();
        foreach ($fys_before_contract as $fy){
            $progress = $project->progresses()->where('fy_id',getFyId($fy))->where('month_id',12)->first();
            $bill_exp = $progress ? $progress->bill_exp : null;
            $cont_exp = $progress ?$progress->cont_exp : null;
            $total_expenses = $bill_exp + $cont_exp;
            if($total_expenses == 0){
                unset($fys[array_search($fy, $fys)]);
            }
        }

        /*विनियोजित बजेट*/
        $total_budget['field0'] = 'विनियोजित बजेट';
        if ($fys){
            $index=1;
            foreach ($fys as $fy){
                $allocation = $project->allocation()->where('fy_id', getFyId($fy))->orderBy('amendment','desc')->first();
                $total_budget['field'.($index++)] = $allocation ? number_format($allocation->total_budget, 2) : '-';
            }
        }

        /*वित्तीय प्रगति समग्रमा*/
        $total_exp['field0'] = 'वित्तीय प्रगति समग्रमा';
        if ($fys){
            $index=1;
            foreach ($fys as $fy){
                $progress = $project->progresses()->where('fy_id',getFyId($fy))->where('month_id',12)->first();
                $bill_exp = $progress ? $progress->bill_exp : 0;
                $cont_exp = $progress ?$progress->cont_exp : 0;
                $total_exp['field'.($index++)] = number_format($bill_exp + $cont_exp,2) == 0 ? '-' : ($bill_exp + $cont_exp);
            }

        }

        /*भौतिक प्रगति*/
        $physical_percentage['field0'] = 'भौतिक प्रगति';
        if ($fys){
            $index=1;
            foreach ($fys as $fy){
                $progress = $project->progresses()->where('fy_id',getFyId($fy))->where('month_id',12)->first();
                if($progress){
                    if($progress->id == $project->progresses()->get()->last()->id){
                        $physical_percentage['field'.($index++)] = $progress->progressTrack ? $progress->progressTrack->physical_percentage : '-'; //भौतिक प्रगति
                    }else{
                        $physical_percentage['field'.($index++)] = '-';
                    }
                }else{
                    $physical_percentage['field'.($index++)] = '-';
                }
            }
        }
        $fys['field0'] = 'आयोजनालाई विभिन्न आ.व. हरुमा हेर्दा';
        ksort($fys); //sort by key

        /*----- Project Overview Table ------*/
        $row1 = array(
            'field0' => '',
            'field1' => 'सम्झौता मिति : ' . $contract_date,
            'field2' => 'सुरुको सम्झौता अनुसार सम्पन्न गर्न पर्ने मिति :' . $completion_date_first_contract,
            'field3' => 'म्याद थप पश्चात सम्पन गर्नुपर्ने मिति : ' . $completion_date_time_extension,
        );
        for($i=4; $i < count($fys);$i++){$row1['field'.$i] = '';}//to compensate for the extra columns in आयोजनालाई विभिन्न आ.व. हरुमा हेर्दा section

        $row2 = array(
            'field0' => '',
            'field1' => '',
            'field2' => 'सम्झौता रकम (भ्याट बाहेक) :' . $contract_amount,
            'field3' => 'आयोजनाको कुल दायित्व : ' . $total_liability_amount,
        );
        for($i=4; $i < count($fys);$i++){$row2['field'.$i] = '';}

        $row7= array(
            'field0' => '',
            'field1' => 'हालसम्म को समग्रमा प्रगति',
            'field2' => 'Total Cost : '.$total_cost,
            'field3' => 'भौतिक प्रगति : '. count($physical_progress) > 0 ? $physical_progress[0] ?? '' : '',
        );
        for($i=4; $i < count($fys);$i++){$row7['field'.$i] = '';}


        $row8 = array(
            'field0' =>'आयोजनाको बाकि दायित्व (बजेट अनुसार)',
            'field1' => $total_liability_left_budget,
            'field2' => 'आयोजनाको बाकि दायित्व (खर्च अनुसार)',
            'field3' => $total_liability_left_expenditure,
        );
        for($i=4; $i < count($fys);$i++){$row8['field'.$i] = '';}

        $project_overview = [
            $row1,
            $row2,
            $fys, $total_budget, $total_exp, $physical_percentage,
            $row7,
            $row8
        ];

        $project_detail['project_overview'] = $project_overview;

        return $project_detail;
    }

}


