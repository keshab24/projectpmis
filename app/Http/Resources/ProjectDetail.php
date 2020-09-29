<?php

namespace PMIS\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use PMIS\Fiscalyear;

class ProjectDetail extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected $pro_fiscal_year;
    public function toArray($request)
    {
        $project = $this;
        if ($request->has('fy_id')) {
            $this->pro_fiscal_year = Fiscalyear::find($request->get('fy_id'));
        } elseif ($request->has('fiscal_year')) {
            $this->pro_fiscal_year = Fiscalyear::find($request->get('fiscal_year'));
        } else {
            $this->pro_fiscal_year = Fiscalyear::where('fy', explode('/', getFiscalyearFromDate(dateBS(date('Y-m-d'))))[0] . '-' . explode('/', getFiscalyearFromDate(dateBS(date('Y-m-d'))))[1])->first();
        }
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
            $data['monetary_progress'] = number_format($totalExpenditure * 1000 / $projectCost * 100, 2);
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
            'lat' => $project->coordinates ? explode(',',$project->coordinates)[0]:null,
            'long' => $project->coordinates ? (array_key_exists(1,explode(',',$project->coordinates))?explode(',',$project->coordinates)[1]:null):null,
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
            ['text' => 'हस्तान्तरण मिति', 'value' => $project->ho_date, 'eye' => true, 'documents' => $activity_logs_files['ho_date']],
            ['text' => 'जग्गाको स्वामित्व', 'value' => $project->swamittwo ? swamittwo()[$project->swamittwo] : "N/A", 'eye' => count($activity_logs_files['swamittwo'])?true:false, 'documents' => $activity_logs_files['swamittwo'],],
            ['text' => 'कित्ता नं', 'value' => $project->kittanumber, 'eye' => false, 'documents' => []],
            ['text' => 'सीट नं', 'value' => $project->shitnumber, 'eye' => false, 'documents' => []],
            ['text' => 'माटो परिक्षण भए/नभएको', 'value' => $project->soiltest ? soilTest()[$project->soiltest] : 'N/A', 'eye' => count($activity_logs_files['soiltest'])?true:false, 'documents' => $activity_logs_files['soiltest'],],
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
            $authorized_persons[] = ['s_no' => ++$index, 'name' => $person->name, 'phone' => $person->phone, 'mobile' => $person->mobile, 'email' => $person->email];
        }
        $project_detail['authorized_persons'] = $authorized_persons;
        $project_detail['procurement'] = [
            ['text' => 'बजेट शिर्षक: ', 'value' => $project->budget_topic->budget_head, 'eye' => false, 'documents' => []],
            ['text' => 'ब.उ.शी.नं: ', 'value' => $project->budget_topic->budget_topic_num, 'eye' => false, 'documents' => []],
            ['text' => 'प्राथमिकता: ', 'value' => $project->budget_topic->priority, 'eye' => false, 'documents' => []],
            ['text' => 'लागत', 'value' => $project->procurement->estimated_amount, 'eye' => false, 'documents' => []],
            ['text' => 'स्वीकृत ल.ई.(भ्याट तथा कन्तिन्जिंसी बाहेक): ', 'value' => $project->procurement->con_est_amt_net, 'eye' => false, 'documents' => []],
            ['text' => 'ल.ई स्वीकृत मिति: ', 'value' => $project->procurement->est_approved_date, 'eye' => count($project->activityLogs->where('type', 16)) ? true : false, 'documents' => count($project->activityLogs->where('type', 16))],
            ['text' => 'सम्झौता रकम (भ्याट तथा कन्तिन्जिंसी बाहेक): ', 'value' => $project->procurement->contract_amount, 'eye' => false, 'documents' => []],
            ['text' => 'कार्यक्रम लागत: ', 'value' => $project->projectCost(), 'eye' => false, 'documents' => []],
            ['text' => 'सम्झौता बिधि: ', 'value' => $project->procurement->method, 'eye' => false, 'documents' => []],
            ['text' => 'डिजाइन स्वीकृत मिति: ', 'value' => $project->procurement->design_est_swikrit_miti, 'eye' => count((array_key_exists('design_est_swikrit_miti',$activity_logs_files)?$activity_logs_files['design_est_swikrit_miti']:[]))?true:false, 'documents' => (array_key_exists('design_est_swikrit_miti',$activity_logs_files)?$activity_logs_files['design_est_swikrit_miti']:[])],
            ['text' => 'बोलपत्र स्वीकृत मिति: ', 'value' => $project->procurement->bolapatraswikriti, 'eye' => count($activity_logs_files['bolapatraswikriti'])?true:false, 'documents' => $activity_logs_files['bolapatraswikriti']],
            ['text' => 'बोलपत्र तयारी मिति: ', 'value' => $project->procurement->bid_does_ready_act, 'eye' => false, 'documents' => []],
            ['text' => 'No Objection act1: ', 'value' => $project->procurement->no_obj_act1, 'eye' => count($activity_logs_files['no_obj_act1'])?true:false, 'documents' => $activity_logs_files['no_obj_act1']],
            ['text' => 'बोलपत्र आव्हान मिति: ', 'value' => $project->procurement->call_for_bid_act, 'eye' => count($activity_logs_files['call_for_bid_act'])?true:false, 'documents' => $activity_logs_files['call_for_bid_act']],
            ['text' => 'बोलपत्र खुलेको मिति: ', 'value' => $project->procurement->bid_open_act, 'eye' => count($activity_logs_files['bid_open_act'])?true:false, 'documents' => $activity_logs_files['bid_open_act']],
            ['text' => 'बोलपत्र मुल्यांकन सम्पन्न मिति: ', 'value' => $project->procurement->bid_eval_act, 'eye' => count($activity_logs_files['bid_eval_act'])?true:false, 'documents' => $activity_logs_files['bid_eval_act']],
            ['text' => 'No Objection act2: ', 'value' => $project->procurement->no_obj_act2, 'eye' => count($activity_logs_files['no_obj_act2'])?true:false, 'documents' => $activity_logs_files['no_obj_act2']],
            ['text' => 'सम्झौता मिति: ', 'value' => $project->procurement->contract_date, 'eye' => count($activity_logs_files['contract_date'])?true:false, 'documents' => $activity_logs_files['contract_date']],
            ['text' => 'सम्पन्न हुनु पर्ने मिति: ', 'value' => $project->procurement->completion_date, 'eye' => false, 'documents' => []],
            ['text' => 'कार्यादेश दिईएको मिति: ', 'value' => $project->procurement->wo_date, 'eye' => count($activity_logs_files['wo_date'])?true:false, 'documents' => $activity_logs_files['wo_date']],
            ['text' => 'ठेक्का नं: ', 'value' => $project->procurement->con_id_div, 'eye' => false, 'documents' => []],
            ['text' => 'वार्षिक बजेट (रू हजारमा): ', 'value' => $yearlyBudget, 'eye' => false, 'documents' => []],
            ['text' => 'वार्षिक बित्तिय प्रगति (%): ', 'value' => $data['yearly_monetary_progress'], 'eye' => false, 'documents' => []],
            ['text' => 'वार्षिक भौतिक प्रगति (%): ', 'value' => $data['yearly_physical_progress'], 'eye' => false, 'documents' => []],
            ['text' => 'समग्र बित्तिय प्रगति (%): ', 'value' => $data['monetary_progress'], 'eye' => false, 'documents' => []],
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
            $time_extensions[] = ['s_no' => ++$index, 'start_date' => $timeExt->start_date, 'end_date' => $timeExt->end_date, 'extended_on' => $timeExt->extended_on, 'verified_from' => $timeExt->verified_from, 'liquidated_damage' => ($timeExt->liquidated_damage) ? 'लागेको' : 'नलागेको', 'remarks' => $timeExt->remarks, 'view' => $timeExt->file ? [[
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
            $progress_datas[] = ['s_no' => ++$index, 'fy' => $progress->fy->fy, 'total' => $progress->bill_exp, 'monetary_progress' => round(($progress->bill_exp / $project->projectCost()) * 100, 2), 'physical_progresss' => $progress->current_physical_progress, 'current_status' => ($progress->progress_track) ? $progress->progress_track->progress : $progress->project_remarks];
            $total_expenses += $progress->bill_exp;
        }
        $progress_datas[] = ['s_no' => '', 'fy' => 'कुल खर्च (रू. हजारमा)', 'total' => $total_expenses, 'monetary_progress' => '', 'physical_progress' => '', 'current_status' => ''];
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
        return $project_detail;
    }
}
