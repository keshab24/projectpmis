<?php

namespace PMIS\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use PMIS\BudgetTopic;
use PMIS\Division;
use PMIS\ExpenditureTopic;
use PMIS\Exports\Export\AnnualProgressReportExport;
use PMIS\Exports\Export\SeekProjectExport;
use PMIS\Fiscalyear;
use PMIS\Http\Controllers\AdminBaseController;
use PMIS\ImplementingOffice;
use PMIS\LumpSumBudget;
use PMIS\Month;
use PMIS\Procurement;
use PMIS\Progress;
use PMIS\Project;
use PMIS\ProjectGroup;
use PMIS\ProjectSetting;
use PMIS\State;
use PMIS\TimeExtension;
use PMIS\Trimester;
use PMIS\Variation;

class ReportController extends AdminBaseController
{
    protected $pro_data;
    protected $all_projects;
    protected $progressLastFiscalYear;
    protected $excelFormat = 'xls';


    public function __construct(Project $project, ImplementingOffice $implementingOffice, BudgetTopic $budgettopic)
    {

        parent::__construct();
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '-1');
        $this->middleware(function ($request, $next) {
            restrictEngineers($this->user_info->type_flag);
            $this->project = $this->user_info->visibleProjects()->orderBy('project_code', 'asc');
            $this->all_projects = $this->user_info->visibleProjects(true)->orderBy('project_code', 'asc');
            $this->pro_data['budgettopics'] = $this->user_info->visiblebudgetTopic()->whereStatus(1)->pluck('budget_topic_num', 'id');
            $this->pro_data['implementingoffices'] = add_my_array(
                $this->user_info->visibleImplementingOffices()->get()->pluck('title', 'id')
                , "Choose office");
            $this->pro_data['implementing_offices_after_update'] = $this->user_info->visibleImplementingOffices()->with('child')->get();
            return $next($request);
        });

    }

    public function seekProject(Fiscalyear $fy)
    {
        $this->pro_data['fiscalYear'] = $fy->whereStatus(1)->pluck('fy', 'id');
        return view('admin.progressreport.seekreport', $this->pro_data);
    }

    public function seekProjectPost(Request $request)
    {
        $fiscalYearFrom = $request->get('fiscalYear_from');
        $fiscalYearTo = $request->get('fiscalYear_to');
        $this->pro_data['projects'] = $this->user_info->visibleProjects(null, true)->where('show_on_running', '1')->orderBy('project_code', 'asc');
        $budgetTopicId = $request->get('budget_topic_id');
        $implementing_office = $request->get('implementing_office_id');
        if ($implementing_office != 0) {
            $this->pro_data['projects'] = $this->pro_data['projects']->where('implementing_id', $implementing_office);
        }
        if ($budgetTopicId) {
            $this->pro_data['projects'] = $this->pro_data['projects']->where('budget_id',$budgetTopicId);
        }

        $this->pro_data['projects'] = $this->pro_data['projects']->whereBetween('start_fy_id', [intval($fiscalYearFrom), intval($fiscalYearTo)]);
        $this->pro_data['projects'] = $this->pro_data['projects']->with('lastTimeExtension','procurement','projectSettings.implementing_office','projectSettings.monitoringOffice','implementing_office','fiscal_year', 'lastProgressWithoutLimit')->withCount('timeExtension')->groupBy('pro_projects.id');
        if($request->has('time_ext_count') && $request->get('time_ext_count')){
            if($request->get('time_ext_count') == 'not_extended')
                $this->pro_data['projects'] = $this->pro_data['projects']->doesntHave('timeExtension');    
            $project_time_ext_count = $request->get('time_ext_count');
            $this->pro_data['project_time_ext_count'] = $project_time_ext_count;
        }
        if($request->has('project_period') && $request->get('project_period')){
            $project_period = $request->get('project_period');
            $this->pro_data['project_period_filter'] = $project_period;
        }

        $this->pro_data['projects'] = $this->pro_data['projects']->get();
        if ($request->get('visibility') == 1) {
            return view('admin.progressreport.seekreportPost', $this->pro_data);
        } else {
            $seekProjectExcel = new SeekProjectExport($this->pro_data['projects']);
            $seekProjectExcel->download();
        }

    }

    public function healthyProjects(Fiscalyear $fy)
    {
        $this->pro_data['fiscalYear'] = $fy->whereStatus(1)->pluck('fy', 'id');
        return view('admin.progressreport.healthyProjects', $this->pro_data);
    }

    public function healthyProjectsPost(Request $request)
    {
        $projects = $this->project->doesntHave('timeExtension')->where('project_status', '!=', 0);
        $this->pro_data['projects'] = $projects = $projects->where('start_fy_id', '>=', $request->get('fiscalYear_from'))->where('start_fy_id', '<=', $request->get('fiscalYear'))->get();

        if ($request->get('visibility') == 1) {
            return view('admin.progressreport.healthyprojectPost', $this->pro_data);
        }

        $heading[0] = "क्र.सं.";
        $heading[1] = "Division Name";
        $heading[2] = "Project Code";
        $heading[3] = "Project Name";
        $heading[4] = "Base Year";
        $heading[5] = "Remarks";
        $heading[6] = "Completed Fiscal Year";
        $exportTable[] = $heading;
        foreach ($projects as $index => $project) {
            $exportField[0] = $index + 1;
            $exportField[1] = $project->implementing_office->name;
            $exportField[2] = $project->project_code;
            $exportField[3] = $project->name;
            $exportField[4] = $project->fiscal_year->fy;
            $exportField[5] = "N/A";
            if ($project->lastProgress) {
                $exportField[5] = $project->lastProgress->project_remarks;
            }
            $completedFy = "N/A";
            if ($project->completedFiscalYear) {
                $completedFy = $project->completedFiscalYear->fy;
            }
            $exportField[6] = $completedFy;
            $exportTable[] = $exportField;
        }
        Excel::create('healthyProjects', function ($excel) use ($exportTable) {
            $excel->sheet('sheet1', function ($sheet) use ($exportTable) {
                $sheet->fromArray($exportTable, null, 'A1', false, false);
            });
        })->download($this->excelFormat);
    }

    public function hbmsReportForm(Month $month, Fiscalyear $fiscalyear)
    {

        $this->pro_data['fiscal_year'] = add_my_array($fiscalyear->whereStatus(1)->pluck('fy', 'id'), "Any");
        $this->pro_data['months'] = $month->whereStatus(1)->pluck('name', 'id');
        $this->pro_data['statuses'] = getProjectStatus();
        $this->pro_data['statuses'][4] = "Any";
        return view('admin.progressreport.hbmsform', $this->pro_data);
    }

    public function hbmsReport(Request $request)
    {
        $this->pro_data['projects'] = $this->project;
        $budgetTopicId = $request->get('budget_topic_id');
        $project_status = $request->get('status');
        $implementing_office = $request->get('implementing_office_id');
        $fiscal_year = $request->get('fiscal_year');

        if ($request->get('project_id') != "") {
            $projectId = $request->get('project_id');
//            $this->pro_data['projects'] = $this->pro_data['projects']->where('project_code', 'like', '%' . $projectCode.'%');
//            $this->pro_data['projects'] = $this->user_info->visibleProjects(true)->where('project_code', 'like', '%' . $projectCode.'%')->groupBy('pro_projects.id');
            $this->pro_data['projects'] = $this->user_info->visibleProjects(true)->where('pro_projects.id', $projectId)->groupBy('pro_projects.id');
        } else {

            if ($project_status != 4) {// if not any
                if ($project_status == 0) {
                    $this->pro_data['projects'] = $this->pro_data['projects']->where('show_on_running', '1');
                } else {
                    $this->pro_data['projects'] = $this->pro_data['projects']->where('project_status', $project_status);
                }
            }

            if ($implementing_office != 0) {
//                $this->pro_data['projects'] = $this->pro_data['projects']->whereImplementing_office_id($implementing_office);
                $this->pro_data['projects'] = $this->pro_data['projects']->where('implementing_id', $implementing_office);
                $this->pro_data['implementing_office'] = $_GET['implementing_office_id'];
            }
            if ($fiscal_year != 0) {
                $this->pro_data['projects'] = $this->pro_data['projects']->whereStart_fy_id($fiscal_year);
                $this->pro_data['implementing_office'] = $_GET['implementing_office_id'];
            }

//            $this->pro_data['projects'] = $this->pro_data['projects']->whereBudget_topic_id($budgetTopicId);
            $this->pro_data['projects'] = $this->pro_data['projects']->where('budget_id',$budgetTopicId);
            $this->pro_data['paginate'] = true;
        }
        $this->pro_data['count'] = $this->pro_data['projects']->count();
        $this->pro_data['projects'] = $this->pro_data['projects']->paginate(100);
        return view('admin.progressreport.hbms', $this->pro_data);
    }

    public function progress_report()
    {
        return view('admin.progressreport.progress_report', $this->pro_data);
    }

    public function progress_reportPost(Request $request)
    {
        $project = $this->project->where('project_code', 'like', '%' . $request->project_code)->first();
        if ($project) {
            $this->pro_data['project'] = $project;
            return view('admin.progressreport.progress_reportPost', $this->pro_data);
        }
        session()->flash('fail_info', '" No Project Found"');
        return redirect()->back();
    }

    public function allocationReport(ExpenditureTopic $expendituretopic, Division $division, Month $month)
    {
        $this->pro_data['expendituretopics'] = $expendituretopic->whereStatus(1)->pluck('expenditure_topic_num', 'id');
        $this->pro_data['divisionoffices'] = add_my_array($division->whereStatus(1)->pluck('name', 'id'), 'सबै डी का');
        $this->pro_data['months'] = $month->whereStatus(1)->pluck('name', 'id');
        $this->pro_data['trimesters'] = Trimester::pluck('name', 'id');
        return view('admin.allocation_report.index', $this->pro_data);
    }

    public function allocationReportPost(Request $request)
    {
        $this->pro_data['budget_topic_id'] = $request->get('budget_topic_id') != '' ? $request->get('budget_topic_id') : '';
        $this->pro_data['expenditure_topic_id'] = $request->get('expenditure_topic_id') != '' ? $request->get('expenditure_topic_id') : '';
        $this->pro_data['implementing_office_id'] = $request->get('implementing_office_id') != '' ? $request->get('implementing_office_id') : '';

        /*$this->pro_data['projects'] = $this->project->whereStatus(1)->whereBudget_topic_id($this->pro_data['budget_topic_id'])->whereExpenditure_topic_id($this->pro_data['expenditure_topic_id'])->where('show_on_running', 1);*/
        $this->pro_data['projects'] = $this->project->whereStatus(1)->where('budget_id',$this->pro_data['budget_topic_id'])->where('expenditure_id',$this->pro_data['expenditure_topic_id'])->where('show_on_running', 1);
        $implementing_office_id = $request->get('implementing_office_id');
        if ($request->get('division_code')) {
            $division_code = $request->get('division_code');
        }
        if ($implementing_office_id != 0) {
//            $this->pro_data['projects'] = $this->pro_data['projects']->whereImplementing_office_id($this->pro_data['implementing_office_id']);
            $this->pro_data['projects'] = $this->pro_data['projects']->where('implementing_id', $this->pro_data['implementing_office_id']);
        }
        if (isset($division_code)) {
            if ($division_code != 0) {
                $this->pro_data['projects'] = $this->pro_data['projects']->whereDivision_code($division_code);
            }
        }
//        $this->pro_data['projects'] = $this->pro_data['projects']->has('allocation')->orderBy('implementing_office_id', 'asc')->get();
        $this->pro_data['projects'] = $this->pro_data['projects']->has('allocation')->orderBy('implementing_id', 'asc')->get();
        return view('admin.allocation_report.views', $this->pro_data);
    }

    public function project_cost()
    {
        return view('admin.progressreport.project_cost', $this->pro_data);
    }

    public function project_costPost(Request $request)
    {
        $this->pro_data['projects'] = $this->project;
        $budgetTopicId = $request->get('budget_topic_id');

        $implementing_office = $request->get('implementing_office_id');
        $this->pro_data['projects'] = $this->pro_data['projects']->where('show_on_running', '1');
        if ($implementing_office != 0) {
//            $this->pro_data['projects'] = $this->pro_data['projects']->whereImplementing_office_id($implementing_office);
            $this->pro_data['projects'] = $this->pro_data['projects']->where('implementing_id', $implementing_office);
        }
//        $this->pro_data['projects'] = $this->pro_data['projects']->whereBudget_topic_id($budgetTopicId)->get();
        $this->pro_data['projects'] = $this->pro_data['projects']->where('budget_id',$budgetTopicId)->get();
        $heading = [];
        $heading[0] = "क्र.सं.";
        $heading[1] = "प्रोजेक्ट कोड";
        $heading[2] = "आयोजनाको नाम";
        $heading[3] = "कार्यक्रम लागत";
        $exportTable[] = $heading;
        foreach ($this->pro_data['projects'] as $index => $project) {
            $exportField[0] = $index + 1;
            $exportField[1] = $project->project_code;
            $exportField[2] = $project->name;
            $exportField[3] = $project->projectCost();
            $exportTable[] = $exportField;
        }

        Excel::create('project_cost', function ($excel) use ($exportTable) {
            $excel->sheet('building-report', function ($sheet) use ($exportTable) {
                $sheet->fromArray($exportTable, null, 'A1', false, false);
            });
        })->download($this->excelFormat);
    }

    public function multiYearProcurementPlan()
    {
        return view('admin.progressreport.multiyear_procurement', $this->pro_data);
    }

    public function multiYearProcurementPlanPost(Request $request, ProjectSetting $projectSetting)
    {
        $budget_topic_id = $request->budget_topic_id;
        $count = 0;
//        $projects = $this->project->where('show_on_running', '1')->where('budget_id', $budget_topic_id)->orderBy('id', 'asc')->get();
        foreach ($projectSetting->where('budget_id', $budget_topic_id)->orderBy('code', 'asc')->get() as $projectSetting)
            if ($projectSetting->project->show_on_running)
                $settings[] = $projectSetting;

        $heading = [];
        $heading[0] = "Sub-Group";
        $heading[1] = "Division Code";
        $heading[2] = "डि.का.";
        $heading[3] = "आयोजनाको आधार वर्ष";
        $heading[4] = "आई.डी.";
        $heading[5] = "जिल्ला";
        $heading[6] = "कार्यक्रमको विवरण";
        $heading[7] = "खरिदको प्रकार(सेवा, निर्माण,अन्य माल समान)";
        $heading[8] = "पारिमाण";
        $heading[9] = "प्रस्तावित लागत/सम्झौता रकम";
        $heading[10] = "हालसम्मको खर्च (" . Fiscalyear::find(session()->get('pro_fiscal_year') - 1)->fy . ") सम्म"; // -1
        $heading[11] = "खरिदको पिधि(टेण्डर, कोटेशन, सोझै, आर.एफ.पि)";
        $heading[12] = "खरिदलाइ टुक्र्याउने प्याकेज";
        $heading[13] = "सम्झौताको मोटामोटी संख्या र मुख्य मुख्य काम";
        $heading[14] = "खरिदको मोटामोटी समय तालिका र रकम (" . Fiscalyear::find(session()->get('pro_fiscal_year'))->fy . ")";
        $heading[15] = Fiscalyear::find(session()->get('pro_fiscal_year') + 1)->fy;
        $heading[16] = Fiscalyear::find(session()->get('pro_fiscal_year') + 2)->fy;
        $heading[17] = Fiscalyear::find(session()->get('pro_fiscal_year') + 3) ? Fiscalyear::find(session()->get('pro_fiscal_year') + 3)->fy : '';
        $heading[18] = "हालसम्मको प्रगति";
        $heading[19] = "कैफियत";
        $heading[20] = "सम्पन्न हुने मिति";
        $heading[21] = "सम्पन्न हुने आ.व.";
        $heading[22] = "Progress In Number";
        $exportTable[] = $heading;
        $exportTable[1] = array();
        foreach ($settings as $setting) {
            $exportField[0] = $setting->project->group->name;
            $exportField[1] = intval($setting->implementing_office->office_code);
            $implementing_office_name = $setting->implementing_office->name;
            $exportField[2] = strpos($implementing_office_name, ', ') ? explode(", ", $implementing_office_name)[1] : $implementing_office_name;
            $exportField[3] = $setting->project->fiscal_year->fy;
            $exportField[4] = $setting->code;
            $exportField[5] = $setting->project->district->name;
            $exportField[6] = $setting->project->name;
            $exportField[7] = "निर्माण";
            $exportField[8] = "१ वटा";
            $exportField[9] = $setting->project->projectCost();
            $totalExpenditure = 0;
            foreach ($setting->project->progresses()->where('fy_id', '>=', $setting->project->start_fy_id)->where('month_id', 12)->where('fy_id', '<', session()->get('pro_fiscal_year'))->get() as $index => $progress) {
                $totalExpenditure += $progress->bill_exp + $progress->cont_exp;
            }
            $exportField[10] = floatval($totalExpenditure);
            $exportField[11] = "टेण्डर";
            $exportField[12] = "एक प्याकेज";
            $exportField[13] = "भवन निर्माण";
            $value = "N/A";
            $allocation = $setting->project->allocation()->where('fy_id', intval(session()->get('pro_fiscal_year')))->orderBy('id', 'desc')->first();
            if ($allocation) {
                $value = $allocation->total_budget;
            }
            $exportField[14] = floatval($value);
            $exportField[15] = '';
            $exportField[16] = '';
            $exportField[17] = '';
            $exportField[18] = $setting->project->lastProgress ? $setting->project->lastProgress->project_remarks : 'N/A';
            $exportField[19] = "";

            $exportField[20] = $setting->project->procurement->completion_date;
            if ($setting->project->timeExtension->count() > 0) {
                $exportField[20] = $setting->project->timeExtension()->orderBy('end_date', 'desc')->first()->end_date;
            }
            $exportField[21] = $exportField[20] ? getFiscalyearFromDate($exportField[20]) : '';
            $exportField[22] = 0;
            if ($setting->project->lastProgress) {
                if ($setting->project->lastProgress->progressTrack) {
                    $exportField[22] = intval($setting->project->lastProgress->progressTrack->physical_percentage);
                }
            }
            $exportTable[] = $exportField;
        }
        Excel::create('procurement plan', function ($excel) use ($exportTable) {
            $excel->sheet('sheet1', function ($sheet) use ($exportTable) {
                $sheet->fromArray($exportTable, null, 'A1', false, false);
            });
        })->download($this->excelFormat);
    }

    public function toBeTimeExtended()
    {
        return view('admin.progressreport.tobeextended', $this->pro_data);
    }

    public function toBeExtendedPost(Request $request)
    {
        $this->pro_data['projects'] = $this->project->where('show_on_running', '1');
//        $this->pro_data['projects'] = $this->pro_data['projects']->whereBudget_topic_id($request->get('budget_topic_id'));
        $this->pro_data['projects'] = $this->pro_data['projects']->where('budget_id',$request->get('budget_topic_id'));
        $implementing_office_id = $request->get('implementing_office_id');
        if ($implementing_office_id != 0) {
//            $this->pro_data['projects'] = $this->pro_data['projects']->whereImplementing_office_id($implementing_office_id);
            $this->pro_data['projects'] = $this->pro_data['projects']->where('implementing_id', $implementing_office_id);
            $this->pro_data['implementing_office_name'] = ImplementingOffice::find($implementing_office_id)->name;
        }
        $this->pro_data['extended'] = '';
        $today = strtotime(date('Y-m-d'));
        foreach ($this->pro_data['projects']->get() as $project) {
            $extension = false;
            if (count($project->timeExtension) > 0) {
                if ($today > strtotime(dateAD($project->timeExtension->last()->end_date))) {
                    $extension = true;
                }
            } else {
                if (dateAD($project->procurement->completion_date) != FALSE && $today > strtotime(dateAD($project->procurement->completion_date)) && $project->procurement->completion_date != '0000-00-00' && $project->procurement->completion_date != '') {
                    $extension = true;
                }
            }
            if ($extension == true) {
                $this->pro_data['extended'][] = $project;
            }
        }


        return view('admin.progressreport.tobeextendedreport', $this->pro_data);
    }

    public function hoThisFY(Fiscalyear $fiscalyear)
    {
        $this->pro_data['fiscal_years'] = $fiscalyear->whereStatus(1)->orderBy('id', 'desc')->pluck('fy', 'id');
        return view('admin.progressreport.hothisyear', $this->pro_data);
    }

    public function hoThisFyPost(Request $request)
    {
        $this->pro_data['projects'] = $this->project;
        $budgetTopicId = $request->get('budget_topic_id');
        $fiscalYear = $request->get('fiscal_year');
        $implementing_office = $request->get('implementing_office_id');

        if ($implementing_office != 0) {
//            $this->pro_data['projects'] = $this->pro_data['projects']->whereImplementing_office_id($implementing_office);
            $this->pro_data['projects'] = $this->pro_data['projects']->where('implementing_id', $implementing_office);
        }
//        $this->pro_data['projects'] = $this->pro_data['projects']->whereBudget_topic_id($budgetTopicId);
        $this->pro_data['projects'] = $this->pro_data['projects']->where('budget_id',$budgetTopicId);
        $this->pro_data['projects'] = $this->pro_data['projects']->where('completed_fy', $fiscalYear)->get();
        $this->pro_data['fiscalYear'] = Fiscalyear::find($fiscalYear)->fy;

        $this->pro_data['count'] = $this->pro_data['projects']->count();

        return view('admin.progressreport.hoThisFy', $this->pro_data);

    }

    public function buildingReportForm(Month $month)
    {
        $this->pro_data['months'] = $month->whereStatus(1)->pluck('name', 'id');
        $this->pro_data['statuses'] = getProjectStatus();
        $this->pro_data['statuses'][4] = "Any";
        return view('admin.progressreport.buildingform', $this->pro_data);
    }

    public function buildingReport(Request $request)
    {
        $budgetTopicId = $request->get('budget_topic_id');
        $this->pro_data['storey'] = $request->get('storey_area');
        $project_status = $request->get('status');
        $this->pro_data['projects'] = $this->project;
        $implementing_office_id = $request->get('implementing_office_id');
        //project_status 4 == any but implementing_office_id == 0 means any HAD SITUATION !!
        if ($implementing_office_id != 0 && $project_status == 4) {
//            $this->pro_data['projects'] = $this->pro_data['projects']->whereImplementing_office_id($implementing_office_id);
            $this->pro_data['projects'] = $this->pro_data['projects']->where('implementing_id', $implementing_office_id);
        } elseif ($implementing_office_id == 0 && $project_status != 4) {
            $this->pro_data['projects'] = $this->pro_data['projects']->whereProject_status($project_status);
        } elseif ($implementing_office_id != 0 && $project_status != 4) {
//            $this->pro_data['projects'] = $this->pro_data['projects']->whereProject_status($project_status)->whereImplementing_office_id($implementing_office_id);
            $this->pro_data['projects'] = $this->pro_data['projects']->whereProject_status($project_status)->where('implementing_id', $implementing_office_id);
        }
//        $this->pro_data['projects'] = $this->pro_data['projects']->whereBudget_topic_id($budgetTopicId)->whereStory_area_unite($this->pro_data['storey']);
        $this->pro_data['projects'] = $this->pro_data['projects']->where('budget_id',$budgetTopicId)->whereStory_area_unite($this->pro_data['storey']);
        if ($request->get('excel')) {
            if (($this->pro_data['projects']->count() > 0)) {
                $heading = [];
                $heading[0] = "क्र.सं.";
                $heading[1] = "प्रोजेक्ट कोड";
                $heading[2] = "आयोजनाको नाम";
                $heading[3] = "भवनको तल्ला";
                $exportTable[] = $heading;
                foreach ($this->pro_data['projects']->get() as $index => $project) {
                    $exportField[0] = $index + 1;
                    $exportField[1] = $project->project_code;
                    $exportField[2] = $project->name;
                    $exportField[3] = ($project->story_area_unite == 0) ? '' : getStoreyArea()[$project->story_area_unite];
                    $exportTable[] = $exportField;
                }
                $implementingOffice = 'All';
                if ($request->get('implementing_office_id') != 0) {
                    $implementingOffice = ImplementingOffice::whereId($request->get('implementing_office_id'))->first()->name;
                }
                Excel::create($implementingOffice . ' Building Report', function ($excel) use ($exportTable) {
                    $excel->sheet('building-report', function ($sheet) use ($exportTable) {
                        $sheet->fromArray($exportTable, null, 'A1', false, false);
                    });

                })->download($this->excelFormat);
            }
        }
        $this->pro_data['projects'] = $this->pro_data['projects']->simplePaginate(20);
        return view('admin.progressreport.buildingreport', $this->pro_data);
    }

    public function progressReport(ExpenditureTopic $expendituretopic, Division $division, Month $month)
    {
        $this->pro_data['expendituretopics'] = add_my_array($expendituretopic->whereStatus(1)->pluck('expenditure_topic_num', 'id'), "सबै खर्च शिर्षक");
        $this->pro_data['divisionoffices'] = add_my_array($division->whereStatus(1)->pluck('name', 'id'), 'सबै डी का');
        $this->pro_data['months'] = $month->whereStatus(1)->pluck('name', 'id');
        $this->pro_data['trimesters'] = Trimester::where('name_eng', '<>', 'Third Trimester')->pluck('name', 'id');
        return view('admin.progressreport.index', $this->pro_data);
    }

    public function progressReportPost(Request $request)
    {
        $this->pro_data['month'] = '';
        $this->pro_data['trimester'] = '';
        if ($request->get('month')) {
            $this->pro_data['month'] = Month::whereId($request->get('month'));
            $month_id = intval($request->get('month'));
            $this->pro_data['month_id'] = $month_id;
            $this->pro_data['total_months'] = Month::where('id', '<', $request->get('month'))->pluck('id')->toArray();
        } else {
            $trim = $request->get('trimester') == "4" ? "3" : $request->get('trimester');
            $this->pro_data['trimester'] = Trimester::whereId($trim)->first();
//            $months = Trimester::whereId($trim)->first()->months()->pluck('id')->sortByDesc('id')->first();
            $months = Trimester::whereId($trim)->first()->months()->orderBy('id', 'desc')->pluck('id')->first();
            $this->pro_data['month_id'] = $months;
            $this->pro_data['total_months'] = Month::where('trim_id', '<=', $trim)->pluck('id')->toArray();
        }
        $this->pro_data['budget_topic_id'] = $request->get('budget_topic_id') != '' ? $request->get('budget_topic_id') : '';
        $expenditureTopicId = $this->pro_data['expenditure_topic_id'] = $request->get('expenditure_topic_id') != '' ? $request->get('expenditure_topic_id') : '';
        $this->pro_data['implementing_office_id'] = $request->get('implementing_office_id') != '' ? $request->get('implementing_office_id') : '';
        //$this->pro_data['division_code'] = $request->get('division_code')!=''?$request->get('division_code'):'';


//        $this->pro_data['projects'] = $this->project->whereStatus(1)->whereBudget_topic_id($this->pro_data['budget_topic_id'])->where('show_on_running', '1');
        $this->pro_data['projects'] = $this->project->whereStatus(1)->where('budget_id',$this->pro_data['budget_topic_id'])->where('show_on_running', '1');

        $implementing_office_id = $request->get('implementing_office_id');
        if ($request->get('division_code')) {
            $division_code = $request->get('division_code');
        }
        if ($implementing_office_id != 0) {
//            $this->pro_data['projects'] = $this->pro_data['projects']->whereImplementing_office_id($this->pro_data['implementing_office_id']);
            $this->pro_data['projects'] = $this->pro_data['projects']->where('implementing_id', $this->pro_data['implementing_office_id']);
        }
        if ($expenditureTopicId != 0) {
//            $this->pro_data['projects'] = $this->pro_data['projects']->whereExpenditure_topic_id($this->pro_data['expenditure_topic_id']);
            $this->pro_data['projects'] = $this->pro_data['projects']->where('expenditure_id',$this->pro_data['expenditure_topic_id']);
        }
        if (isset($division_code)) {
            if ($division_code != 0) {
                $this->pro_data['projects'] = $this->pro_data['projects']->whereDivision_code($division_code);
            }
        }
        $project_ids = Progress::whereMonth_id($this->pro_data['month_id'])->pluck('project_code')->toArray();
        $this->pro_data['projects'] = $this->pro_data['projects']->has('progresses')->whereIn('pro_projects.id', $project_ids)->orderBy('project_code', 'asc')->get();

        //$this->pro_data['projects'] = $project->whereStatus(1)->whereBudget_topic_id($this->pro_data['budget_topic_id'])->whereExpenditure_topic_id($this->pro_data['expenditure_topic_id'])->whereImplementing_office_id($this->pro_data['implementing_office_id'])->whereDivision_code($this->pro_data['division_code'])->whereIn('id',$project_ids)->get();
        if (LumpSumBudget::where('budget_topic_id', $this->pro_data['budget_topic_id'])->get()->count() == 0) return redirect()->back()->with('lump_sum_budget_issue', BudgetTopic::whereId($this->pro_data['budget_topic_id'])->first()->budget_topic_num);
        if ($request->get('trimester') == "4") {
            return view('admin.progressreport.yearly_progress', $this->pro_data);
        } else {
            return view('admin.progressreport.views', $this->pro_data);
        }
    }

    public function excelExport(Month $month, Fiscalyear $fy, State $state)
    {
        $this->pro_data['states'] = add_my_array(add_my_array($state->whereStatus(1)->pluck('name', 'id'), 'All', 'all'), 'प्रदेश', null);
        $this->pro_data['months'] = $month->whereStatus(1)->pluck('name', 'id');
        $this->pro_data['fy'] = add_my_array($fy->whereStatus(1)->pluck('fy', 'id'), 'Any');
        $this->pro_data['statuses'] = getProjectStatus();
        $this->pro_data['statuses'][4] = "Any";
        return view('admin.excelexport.hbmsform', $this->pro_data);
    }

    public function downloadExcel(Request $request)
    {
        $project_status = $request->get('project_status');
        if(!$request->get('fiscal_year')){
            $this->pro_data['projects'] = $this->user_info->visibleProjects(null, true)->orderBy('project_code', 'asc');;
        }else{
            $this->pro_data['projects'] = $this->project;
        };
        if ($project_status == 0) {
            //only running this year.
            $this->pro_data['projects'] = $this->pro_data['projects']->where('show_on_running', '1');
        } elseif ($project_status == 6) {
            //not running projects
            $this->pro_data['projects'] = $this->pro_data['projects']->where('show_on_running', '0');
        } elseif ($project_status == 4) {
            //any
            $this->pro_data['projects'] = $this->all_projects->where('cancelled', 0);
        } elseif ($project_status == 3) {
            //terminated
            $this->pro_data['projects'] = $this->all_projects->where('cancelled', 1)->whereIn('cancelled_reason', [1, 2]);
        } elseif ($project_status ==5) {
            //cancelled projects
            $this->pro_data['projects'] = $this->all_projects->where('cancelled', 1)->where('cancelled_reason', 3);
        } else {
            $this->pro_data['projects'] = $this->all_projects->where('cancelled', 0)->where('project_status', $project_status);
            //TODO work complete and hand seems inconsistent. Discuss more.
            //work complete should show all the projects that are completed.
            //where hand over should show the projects that are completed and handed over.
  /*          if($project_status == 1){
                //work completed
                $this->pro_data['projects'] = $this->pro_data['projects']->where('completed_fy', '<=', session()->get('pro_fiscal_year'));
            }elseif($project_status == 1){
                //project hand over
                $this->pro_data['projects'] = $this->pro_data['projects']->where('ho_fy', '<=', session()->get('pro_fiscal_year'));
            }*/
        }
        if (in_array("project_status", $request->get('displayField'))) {
            $this->pro_data['projects'] = $this->project->withTrashed();
        }

        if ($request->fiscal_year != 0) {
            $this->pro_data['projects'] = $this->pro_data['projects']->where('start_fy_id', $request->fiscal_year);
        }

        if ($request->implementing_office_id != 0) {
//            $this->pro_data['projects'] = $this->pro_data['projects']->whereImplementing_office_id($request->implementing_office_id);
            $this->pro_data['projects'] = $this->pro_data['projects']->where('implementing_id',$request->implementing_office_id);
        }

        if ($request->budget_topic_id != 0) {
//            $this->pro_data['projects'] = $this->pro_data['projects']->whereBudget_topic_id($request->get('budget_topic_id'));
            $this->pro_data['projects'] = $this->pro_data['projects']->where('budget_id',$request->get('budget_topic_id'));
        }

        if ($request->state_id != '' && $request->state_id != 'all') {
            $this->pro_data['projects'] = $this->pro_data['projects']->whereHas('district', function ($district) use ($request) {
                $district->where('state_id', $request->state_id);
            });
        }

        //Filter with default params

        $this->pro_data['projects'] = $this->pro_data['projects']->groupBy('code');
        $displayFields = $request->get('displayField');
        foreach ($this->pro_data['projects']->get() as $index => $project) {
            $setting = $project->projectSettings()->where('fy',session()->get('pro_fiscal_year'))->first();
            if(!$setting){
                $setting = $project;
            }
            $row = [];
            $heading = [];
            foreach ($displayFields as $field) {
                $value = 'N/A';
                $fieldMap = exportFields()[$field];
                if ($fieldMap[4] == 1) { //if has many records

                    switch ($field) {
                        case 'completion_date':
                            $value = $project->procurement->completion_date;
                            break;

                        case 'timeExtension':
                            if ($project->timeExtension()->count() > 0) {
                                $value = $project->timeExtension()->orderBy('end_date', 'desc')->first()->end_date;
                            } else {
                                $value = '';
                            }
                            break;

                        case 'progress_track':
                            if ($project->lastProgress) {
                                if ($project->progresses->last()->progressTrack) {
                                    $field2 = $fieldMap[2];
                                    $value = $project->progresses->last()->progressTrack->$field2;
                                }
                            }
                            break;
                        default :
                            $relation = $fieldMap[1];
                            if ($project->$relation->last()) {
                                $field = $fieldMap[2];
                                $value = $project->$relation->last()->$field;
                            }
                            break;
                    }
                } else {
                    switch ($field) {
                        case 'contingency':
                            $value = $project->procurement->contingency;
                            break;
                        case 'completion_date':
                            $value = $project->procurement->completion_date;
                            break;
                        case 'project_code':
                            $value = $setting->project_code;
                            break;
                        case 'project_code_old':
                            //2074-75 bhanda pahile ko data system ma chaina
                            //tyo data old pmis ma manage gariyeko cha.
                            //project code matra rakhiyeko le static call garnu pareko.
                            $value = ($project->project_code_old ? '(2074-075) '.$project->project_code_old.', ' :'').implode(', ',$project->projectSettings->where('fy','<',session()->get('pro_fiscal_year'))->pluck('project_code_with_fy')->toArray());
                            break;
                        case 'implementing_office':
                            $value=$setting->implementing_office->title;
                            break;

                        case 'nature_of_project_id':
                            $value = $project->nature_of_project_id ? nature_of_project()['nep'][$project->monitoring_office_id][$project->nature_of_project_id] : 'N/A';
                            break;
                        case 'contractor':
                            $value = 'N/A';
                            if ($project->procurement->Contractor) {
                                $value = $project->procurement->Contractor->name;
                            } elseif ($project->procurement->JointVenture) {
                                $value = $project->procurement->JointVenture->name;
                            }
                            break;

                        case 'contractor_address':
                            $value = $project->procurement ? ($project->procurement->Contractor ? $project->procurement->Contractor->address : 'N/A') : 'N/A';
                            break;

                        case 'authorized_person':
                            $value = $project->procurement ? ($project->procurement->Contractor ? ($project->procurement->Contractor->authorizedPerson->count() > 0 ? $project->procurement->Contractor->authorizedPerson->first()->name : 'N/A') : 'N/A') : 'N/A';
                            break;

                        case 'authorized_person_email':
                            $value = $project->procurement ? ($project->procurement->Contractor ? ($project->procurement->Contractor->authorizedPerson->count() > 0 ? $project->procurement->Contractor->authorizedPerson->first()->email : 'N/A') : 'N/A') : 'N/A';
                            break;

                        case 'authorized_person_phone':
                            $value = $project->procurement ? ($project->procurement->Contractor ? ($project->procurement->Contractor->authorizedPerson->count() > 0 ? $project->procurement->Contractor->authorizedPerson->first()->mobile : 'N/A') : 'N/A') : 'N/A';
                            break;

                        case 'project_status':
                            $value = $project->project_status ? getProjectStatus()[$project->project_status] : 'N/A';
                            if ($project->deleted_at) {
                                $value = 'PC';
                            }
                            break;

                        case 'zone_name':
                            $value = $project->district->zone->name;
                            break;

                        case 'state':
                            $value = $project->district->state->name;
                            break;

                        case 'geo_location':
                            $value = $project->district->geo_id ? getLand()[$project->district->geo_id] : 'N/A';
                            break;

                        case 'payment_status':
                            $value = $project->payment_status ? getPaymentStatus()[$project->payment_status] : 'N/A';
                            break;

                        case 'construction_located_area':
                            $value = $project->consturctionLocatedArea ? $project->consturctionLocatedArea->located_area_nep : 'N/A';
                            break;

                        case 'land_ownership':
                            $value = $project->swamittwo ? swamittwo()[$project->swamittwo] : 'N/A';
                            break;

                        case 'soil_test':
                            $value = $project->soiltest ? soilTest()[$project->soiltest] : 'N/A';
                            break;

                        case 'roof_type':
                            $value = $project->rooftype ? rooftype()[$project->rooftype] : "N/A";
                            break;

                        case 'door_window':
                            $value = $project->doorwindow ? doorWindow()[$project->doorwindow] : 'N/A';
                            break;

                        case 'wall_type':
                            $value = $project->wall_type ? wallType()[$project->wall_type] : 'N/A';
                            break;

                        case 'design_type':
                            $value = $project->design_type ? designType()[$project->design_type] : 'N/A';
                            break;

                        case 'monetary_progress':
                            $projectCost = $project->projectCost();
                            if ($projectCost == 0) {
                                $value = 0;
                            } else {
                                $totalExpenditure = 0;
                                $IdOffFiscalYearNow = Fiscalyear::where('fy', explode('/', getFiscalyearFromDate(dateBS(date('Y-m-d'))))[0] . '-' . explode('/', getFiscalyearFromDate(dateBS(date('Y-m-d'))))[1])->first()->id;
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

                                $value = floatval($totalExpenditure * 1000 / $projectCost * 100);
                            }
                            break;

                        case 'yearly_budget':
                            $value = 'N/A';
                            $IdOffFiscalYearNow = Fiscalyear::where('fy', explode('/', getFiscalyearFromDate(dateBS(date('Y-m-d'))))[0] . '-' . explode('/', getFiscalyearFromDate(dateBS(date('Y-m-d'))))[1])->first()->id;
                            $thisYearAllocation = $project->allocation()->where('fy_id', $IdOffFiscalYearNow)->orderBy('amendment', 'desc')->first();
                            if ($thisYearAllocation) {
                                $value = number_format($thisYearAllocation->total_budget);
                            }
                            break;
                        case 'expenditure_till_last_fy':
                            $value = 0;
                            $old_Progress = $project->progresses()->where('fy_id', '>=', $project->start_fy_id)->where('month_id', 12)->where('fy_id', '<', session()->get('pro_fiscal_year'))->get();
                            foreach ($old_Progress as $progress) {
                                $value += $progress->bill_exp + $progress->cont_exp;
                            }

                            break;
                        case 'total_expenditure':
                            $value = 0;
                            $old_Progress = $project->progresses()->where('fy_id', '>=', $project->start_fy_id)->where('month_id', 12)->where('fy_id', '<', session()->get('pro_fiscal_year'))->get();
                            foreach ($old_Progress as $progress) {
                                $value += $progress->bill_exp + $progress->cont_exp;
                            }


                            $progressesThisYear = $project->progresses()->where('fy_id', '>=', $project->start_fy_id)->where('fy_id', session()->get('pro_fiscal_year'))->orderBy('month_id', 'desc')->get();

                            $last = 0;
                            foreach ($progressesThisYear as $progressThisFy) {
                                if ($last == 1) {
                                    break;
                                }
                                if ($progressThisFy->month_id == 12) {
                                    $last = 1;
                                }
                                $value += $progressThisFy->bill_exp + $progressThisFy->cont_exp;
                            }
                            break;
                        case 'project_cost':
                            $value = $project->projectCost();
                            break;

                        case 'current_physical_progress':
                            $value = 'N/A';
                            if ($project->lastProgress) {
                                if ($project->lastProgress->progressTrack) {
                                    $value = $project->lastProgress->progressTrack->physical_percentage;
                                }
                            }
                            break;

                        case 'allocations':
                            $value = "N/A";
                            $allocation = $project->allocation()->where('fy_id', intval(session()->get('pro_fiscal_year')))->orderBy('id', 'desc')->first();
                            if ($allocation) {
                                $value = $allocation->total_budget;
                            }

                            break;
                        case 'district':
                            $value = $project->district->name;
                            break;

                        case 'district_eng':
                            $value = $project->district->name_eng;
                            break;

                        case 'implementing_office_eng':
                            $value = $setting->implementing_office->title_eng;
                            break;

                        case 'contract_amount':
                            $value = $project->procurement->contract_amount;
                            break;

                        case 'fy_id':
                            $value = $project->fiscal_year->fy;
                            break;

                        case 'contract_date':
                            $value = $project->procurement->contract_date;
                            break;

                        case 'budget_topic_id':
                            $setting = $project->projectSettings()->where('fy',session()->get('pro_fiscal_year'))->first();
                            if(!$setting){
                                $setting = $project;
                            }
                            $value = $setting->budget_topic->budget_topic_num;
                            break;
                        case 'expenditure_topic_id':
                            $setting = $project->projectSettings()->where('fy',session()->get('pro_fiscal_year'))->first();
                            if(!$setting){
                                $setting = $project;
                            }
                            $value = $setting->expenditure_topic->expenditure_head;
                            break;

                        default:
                            $field1 = $fieldMap[1];
                            $field2 = $fieldMap[2];
                            $value = !$field1 == '' ? $project->$field1->$field2 : $project->$field;
                            break;
                    }
                }
                $row[] = is_numeric($value) ? floatval($value) : $value;
                $heading[] = $fieldMap[0];
            }
            if ($index == 0) $exportTable[] = $heading;
            $exportTable[] = $row;
        }
        
        $exportTable = array_map("unserialize", array_unique(array_map("serialize",$exportTable)));
        if ($this->pro_data['projects']->get()->count() > 0) {
            Excel::create('hbms-report', function ($excel) use ($exportTable) {
                $excel->sheet('Sheetname1', function ($sheet) use ($exportTable) {
                    $sheet->fromArray($exportTable, null, 'A1', false, false);
                });

            })->download($this->excelFormat);
        } else {
            echo "<h1>No Data to export</h1>";
        }
    }

    public function handOverDate()
    {
        return view('admin.progressreport.handoverdate', $this->pro_data);
    }

    public function handOverPost(Request $request)
    {

        $this->pro_data['projects'] = $this->project->where('project_status', 2)->where('ho_date', null)->orWHere('ho_date', '0000-00-00');
        $implementing_office_id = $request->get('implementing_office_id');
        //project_status 4 == any but implementing_office_id == 0 means any HAD SITUATION !!
        if ($implementing_office_id != 0) {
            $this->pro_data['implementing_office_name'] = ImplementingOffice::find($implementing_office_id)->name;
//            $this->pro_data['projects'] = $this->pro_data['projects']->whereImplementing_office_id($implementing_office_id);
            $this->pro_data['projects'] = $this->pro_data['projects']->where('implementing_id', $implementing_office_id);
        }
//        $this->pro_data['projects'] = $this->pro_data['projects']->whereBudget_topic_id($request->get('budget_topic_id'))->whereCompletedFy($request->fy_id)->get();
        $this->pro_data['projects'] = $this->pro_data['projects']->where('budget_id',$request->get('budget_topic_id'))->whereCompletedFy($request->fy_id)->get();
        return view('admin.progressreport.handoverdatepost', $this->pro_data);
    }

    public function annualProgressReport(Project $project)
    {
        $formANew = $project->where('show_on_running', '1')->where('start_fy_id', session()->get('pro_fiscal_year'))->get();
        $formAOnGoing = $project->where('show_on_running', '1')->where('start_fy_id', '!=', session()->get('pro_fiscal_year'))->get();

        $heading = [];
        $heading[0] = "S.No";
        $heading[1] = "ID";
        $heading[2] = "DUDBC Division Office";
        $heading[3] = "Description of Works ";
        $heading[4] = "District";
        $heading[5] = "Budget Head";
        $heading[6] = "Exp Head";
        $heading[7] = "Contract ID";
        $heading[8] = "Bid Ready Estimate";
        $heading[9] = "Bid Ready Actual";
        $heading[10] = "No Objection Estimate 1";
        $heading[11] = "No Objection Actual 1";
        $heading[12] = "Call For Bids Estimate";
        $heading[13] = "Call For Bids Actual";
        $heading[14] = "Bid Open Estimate";
        $heading[15] = "Bid Open Actual";
        $heading[16] = "Bid Evaluation Date Estimate";
        $heading[17] = "Bid Evaluation Date Actual";
        $heading[18] = "No Objection Estimate 2";
        $heading[19] = "No Objection Actual 2";
        $heading[20] = "Contract Sign Date Estimate";
        $heading[21] = "Contract Sign Date Actual";
        $heading[22] = "Contract End date Estimate";
        $heading[23] = "Contract End date Actual";
        $heading[24] = "Supplier/ Contractor";
        $heading[25] = "Nationality";
        $heading[26] = "Amount invoice to date";
        $heading[27] = "Amount Paid to date";
        $heading[28] = "Status";
        $heading[29] = "Physical Progress";
        $formANewExport[] = $heading;
        $formAOngoingExport[] = $heading;

        foreach ($formANew as $index => $project) {
            $exportField[0] = $index + 1;
            $exportField[1] = $project->project_code;
            $exportField[2] = $project->implementing_office->name;
            $exportField[3] = $project->name;
            $exportField[4] = $project->district->name;
            $exportField[5] = $project->budget_topic->budget_head;
            $exportField[6] = $project->expenditure_topic->expenditure_head;
            $exportField[7] = $project->procurement->con_id_div;
            $exportField[8] = $project->procurement->bid_does_ready_est;
            $exportField[9] = $project->procurement->bid_does_ready_act;
            $exportField[10] = $project->procurement->no_obj_est1;
            $exportField[11] = $project->procurement->no_obj_act1;
            $exportField[12] = $project->procurement->call_for_bid_est;
            $exportField[13] = $project->procurement->call_for_bid_act;
            $exportField[14] = $project->procurement->bid_open_est;
            $exportField[15] = $project->procurement->bid_open_act;
            $exportField[16] = $project->procurement->bid_eval_est;
            $exportField[17] = $project->procurement->bid_eval_act;
            $exportField[18] = $project->procurement->no_obj_est2;
            $exportField[19] = $project->procurement->no_obj_act2;
            $exportField[20] = $project->procurement->con_sign_est;
            $exportField[21] = $project->procurement->contract_date;
            $exportField[22] = $project->procurement->con_end_est;
            $exportField[23] = $project->procurement->completion_date;
            $contractor = '-';
            if ($project->procurement->Contractor != null) {
                $contractor = $project->procurement->Contractor->name;
            }
            if ($project->procurement->JointVenture != null) {
                $contractor = $project->procurement->JointVenture->name;
            }
            $exportField[24] = $contractor;
            $exportField[25] = "Nepal";
            $exportField[26] = "-";
            $totalExpenditure = 0;
            $status = '-';
            $physicalProgress = '-';
            foreach ($project->progresses()->where('fy_id', '>=', $project->start_fy_id)->get() as $index => $progress) {
                $totalExpenditure += $progress->bill_exp;
                if ($progress->id == $project->progresses()->get()->last()->id) {
                    $status = $progress->project_remarks;
                    if ($progress->progressTrack) {
                        $physicalProgress = $progress->progressTrack->physical_percentage;
                    }
                }


            }
            $exportField[27] = $totalExpenditure;
            $exportField[28] = $status;
            $exportField[29] = $physicalProgress;
            $formANewExport[] = $exportField;
        }

        foreach ($formAOnGoing as $index => $project) {
            $exportField[0] = $index + 1;
            $exportField[1] = $project->project_code;
            $exportField[2] = $project->implementing_office->name;
            $exportField[3] = $project->name;
            $exportField[4] = $project->district->name;
            $exportField[5] = $project->budget_topic->budget_head;
            $exportField[6] = $project->expenditure_topic->expenditure_head;
            $exportField[7] = $project->procurement ? $project->procurement->con_id_div : 'N/A';
            $exportField[8] = $project->procurement ? $project->procurement->bid_does_ready_est : 'N/A';
            $exportField[9] = $project->procurement ? $project->procurement->bid_does_ready_act : 'N/A';
            $exportField[10] = $project->procurement ? $project->procurement->no_obj_est1 : 'N/A';
            $exportField[11] = $project->procurement ? $project->procurement->no_obj_act1 : 'N/A';
            $exportField[12] = $project->procurement ? $project->procurement->call_for_bid_est : 'N/A';
            $exportField[13] = $project->procurement ? $project->procurement->call_for_bid_act : 'N/A';
            $exportField[14] = $project->procurement ? $project->procurement->bid_open_est : 'N/A';
            $exportField[15] = $project->procurement ? $project->procurement->bid_open_act : 'N/A';
            $exportField[16] = $project->procurement ? $project->procurement->bid_eval_est : 'N/A';
            $exportField[17] = $project->procurement ? $project->procurement->bid_eval_act : 'N/A';
            $exportField[18] = $project->procurement ? $project->procurement->no_obj_est2 : 'N/A';
            $exportField[19] = $project->procurement ? $project->procurement->no_obj_act2 : 'N/A';
            $exportField[20] = $project->procurement ? $project->procurement->con_sign_est : 'N/A';
            $exportField[21] = $project->procurement ? $project->procurement->contract_date : 'N/A';
            $exportField[22] = $project->procurement ? $project->procurement->con_end_est : 'N/A';
            $exportField[23] = $project->procurement ? $project->procurement->completion_date : 'N/A';
            $contractor = '-';
            if ($project->procurement) {
                if ($project->procurement->Contractor != null) {
                    $contractor = $project->procurement->Contractor->name;
                }
                if ($project->procurement->JointVenture != null) {
                    $contractor = $project->procurement->JointVenture->name;
                }
            }
            $exportField[24] = $contractor;
            $exportField[25] = "Nepal";
            $exportField[26] = "-";
            $totalExpenditure = 0;
            $status = '-';
            $physicalProgress = '-';
            foreach ($project->progresses()->where('fy_id', '>=', $project->start_fy_id)->get() as $index => $progress) {
                $totalExpenditure += $progress->bill_exp;
                if ($progress->id == $project->progresses()->get()->last()->id) {
                    $status = $progress->project_remarks;
                    if ($progress->progressTrack) {
                        $physicalProgress = $progress->progressTrack->physical_percentage;
                    }
                }


            }
            $exportField[27] = $totalExpenditure;
            $exportField[28] = $status;
            $exportField[29] = $physicalProgress;
            $formAOngoingExport[] = $exportField;
        }

        $headingB = '';
        $headingB[0] = "S.No";
        $headingB[1] = "ID";
        $headingB[2] = "DUDBC Division Office";
        $headingB[3] = "Description of Works ";
        $headingB[4] = "District";
        $headingB[5] = "Budget Head";
        $headingB[6] = "Exp Head";
        $headingB[7] = "Contract ID";
        $headingB[8] = "Budget Allocation";
        $headingB[9] = "Authorized Budget";
        $headingB[10] = "Release Amount";
        $headingB[11] = "Expenditure";
        $headingB[12] = "Exp %";
        $headingB[13] = "Supplier/ Contractor";
        $headingB[14] = "Nationality";
        $headingB[15] = "Contract Currency";
        $headingB[16] = "Contract Value";
        $headingB[17] = "Amount invoice to date";
        $headingB[18] = "Amount Paid to date";
        $headingB[19] = "Status";
        $headingB[20] = "Physical Progress";
        $formBNewExport[] = $headingB;
        $formBOnGoingExport[] = $headingB;

        foreach ($formANew as $index => $project) {
            $exportFieldB[0] = $index + 1;
            $exportFieldB[1] = $project->project_code;
            $exportFieldB[2] = $project->implementing_office->name;
            $exportFieldB[3] = $project->name;
            $exportFieldB[4] = $project->district->name;
            $exportFieldB[5] = $project->budget_topic->budget_head;
            $exportFieldB[6] = $project->expenditure_topic->expenditure_head;
            $exportFieldB[7] = $project->procurement->con_id_div;
            $totalAllocation = 0;
            foreach ($project->allocation as $allocation) {
                $totalAllocation += $allocation->total_budget;
            }
            $exportFieldB[8] = $totalAllocation;
            $exportFieldB[9] = $totalAllocation;
            $exportFieldB[10] = 0.00;
            $totalExpenditure = 0;
            foreach ($project->progresses()->where('fy_id', '>=', $project->start_fy_id)->get() as $index => $progress) {
                $totalExpenditure += $progress->bill_exp;
            }
            $exportFieldB[11] = $totalExpenditure;
            $expenditurePercentage = 0;
            if ($totalExpenditure != 0) {
                $expenditurePercentage = ($totalExpenditure / $totalAllocation) * 100;
            }
            $exportFieldB[12] = $expenditurePercentage;
            $contractor = '-';
            if ($project->procurement->Contractor != null) {
                $contractor = $project->procurement->Contractor->name;
            }
            if ($project->procurement->JointVenture != null) {
                $contractor = $project->procurement->JointVenture->name;
            }
            $exportFieldB[13] = $contractor;
            $exportFieldB[14] = "Nepali";
            $exportFieldB[15] = "NPR";
            $exportFieldB[16] = $project->procurement->contract_amount;
            $exportFieldB[17] = '-';
            $exportFieldB[18] = $totalExpenditure;
            $status = '-';
            $physicalProgress = '-';
            foreach ($project->progresses()->where('fy_id', '>=', $project->start_fy_id)->get() as $index => $progress) {
                $totalExpenditure += $progress->bill_exp;
                if ($progress->id == $project->progresses()->get()->last()->id) {
                    $status = $progress->project_remarks;
                    if ($progress->progressTrack) {
                        $physicalProgress = $progress->progressTrack->physical_percentage;
                    }
                }
            }
            $exportFieldB[19] = $status;
            $exportFieldB[20] = $physicalProgress;
            $formBNewExport[] = $exportFieldB;
        }
        foreach ($formAOnGoing as $index => $project) {
            $exportFieldB[0] = $index + 1;
            $exportFieldB[1] = $project->project_code;
            $exportFieldB[2] = $project->implementing_office->name;
            $exportFieldB[3] = $project->name;
            $exportFieldB[4] = $project->district->name;
            $exportFieldB[5] = $project->budget_topic->budget_head;
            $exportFieldB[6] = $project->expenditure_topic->expenditure_head;
            $exportFieldB[7] = $project->procurement->con_id_div;
            $totalAllocation = 0;
            foreach ($project->allocation as $allocation) {
                $totalAllocation += $allocation->total_budget;
            }
            $exportFieldB[8] = $totalAllocation;
            $exportFieldB[9] = $totalAllocation;
            $exportFieldB[10] = 0.00;
            $totalExpenditure = 0;
            foreach ($project->progresses()->where('fy_id', '>=', $project->start_fy_id)->get() as $index => $progress) {
                $totalExpenditure += $progress->bill_exp;
            }
            $exportFieldB[11] = $totalExpenditure;
            $expenditurePercentage = 0;
            if ($totalExpenditure != 0) {
                $expenditurePercentage = ($totalExpenditure / $totalAllocation) * 100;
            }

            $exportFieldB[12] = $expenditurePercentage;
            $contractor = '-';
            if ($project->procurement->Contractor != null) {
                $contractor = $project->procurement->Contractor->name;
            }
            if ($project->procurement->JointVenture != null) {
                $contractor = $project->procurement->JointVenture->name;
            }
            $exportFieldB[13] = $contractor;
            $exportFieldB[14] = "Nepali";
            $exportFieldB[15] = "NPR";
            $exportFieldB[16] = $project->procurement->contract_amount;
            $exportFieldB[17] = '-';
            $exportFieldB[18] = $totalExpenditure;
            $status = '-';
            $physicalProgress = '-';
            foreach ($project->progresses()->where('fy_id', '>=', $project->start_fy_id)->get() as $index => $progress) {
                $totalExpenditure += $progress->bill_exp;
                if ($progress->id == $project->progresses()->get()->last()->id) {
                    $status = $progress->project_remarks;
                    if ($progress->progressTrack) {
                        $physicalProgress = $progress->progressTrack->physical_percentage;
                    }
                }
            }
            $exportFieldB[19] = $status;
            $exportFieldB[20] = $physicalProgress;
            $formBOnGoingExport[] = $exportFieldB;
        }

        Excel::create('World Bank Report', function ($excel) use ($formANewExport, $formAOngoingExport, $formBNewExport, $formBOnGoingExport) {
            $excel->sheet('FormA New', function ($sheet) use ($formANewExport) {
                $sheet->fromArray($formANewExport, null, 'A1', false, false);
            });
            $excel->sheet('FormA OnGoing', function ($sheet) use ($formAOngoingExport) {
                $sheet->fromArray($formAOngoingExport, null, 'A1', false, false);
            });
            $excel->sheet('FormB New', function ($sheet) use ($formBNewExport) {
                $sheet->fromArray($formBNewExport, null, 'A1', false, false);
            });
            $excel->sheet('FormB OnGoing', function ($sheet) use ($formBOnGoingExport) {
                $sheet->fromArray($formBOnGoingExport, null, 'A1', false, false);
            });
        })->download($this->excelFormat);
    }

    public function vopesReport(Variation $variation, TimeExtension $timeExtensions)
    {
        $timeExtensions = $timeExtensions->where('verified_from', 1)->orderBy('extended_on', 'desc');
        $variations = $variation->where('verified_from', 1)->orderBy('vope_date', 'desc');

        $timeExtensions = $timeExtensions->whereHas('project', function ($project) {
            $project->where('show_on_running', '1');
        })->get();

        $variations = $variations->whereHas('project', function ($project) {
            $project->where('show_on_running', '1');
        })->get();


        $heading = [];
        $heading[0] = "क्र.सं.";
        $heading[1] = "प्रोजेक्ट कोड";
        $heading[2] = "आयोजनाको नाम";
        $heading[3] = "निर्णय मिति";
        $heading[4] = "सुरु मिति";
        $heading[5] = "अन्तिम मिति";
        $exportTable[] = $heading;
        foreach ($timeExtensions as $index => $timeExtention) {
            $exportField[0] = $index + 1;
            $exportField[1] = ($timeExtention->project->project_code);
            $exportField[2] = $timeExtention->project->name;
            $exportField[3] = $timeExtention->extended_on == '0000-00-00' ? '' : $timeExtention->extended_on;
            $exportField[4] = $timeExtention->start_date == '0000-00-00' ? '' : $timeExtention->start_date;
            $exportField[5] = $timeExtention->end_date == '0000-00-00' ? '' : $timeExtention->end_date;
            $exportTable[] = $exportField;
        }
        $heading = [];
        $heading[0] = "क्र.सं.";
        $heading[1] = "प्रोजेक्ट कोड";
        $heading[2] = "आयोजनाको नाम";
        $heading[3] = "Amount";
        $heading[4] = "Vope Date";
        $heading[5] = "Type";
        $exportTable1[] = $heading;
        foreach ($variations as $index => $variation) {
            $exportField[0] = $index + 1;
            $exportField[1] = ($variation->project->project_code);
            $exportField[2] = $variation->project->name;
            $exportField[3] = $variation->amount;
            $exportField[4] = $variation->vope_date == '0000-00-00' ? '' : $variation->vope_date;
            $exportField[5] = variations_choose()[$variation->status];
            $exportTable1[] = $exportField;
        }
        Excel::create('from-division', function ($excel) use ($exportTable1, $exportTable) {
            $excel->sheet('variations', function ($sheet) use ($exportTable1) {
                $sheet->fromArray($exportTable1, null, 'A1', false, false);
            });
            $excel->sheet('timeExtensions', function ($sheet) use ($exportTable) {
                $sheet->fromArray($exportTable, null, 'A1', false, false);
            });

        })->download($this->excelFormat);
    }

    public function amendmentReport(ExpenditureTopic $expendituretopic, Division $division, Month $month)
    {
        $this->pro_data['expendituretopics'] = $expendituretopic->whereStatus(1)->pluck('expenditure_topic_num', 'id');
        $this->pro_data['divisionoffices'] = add_my_array($division->whereStatus(1)->pluck('name', 'id'), 'सबै डी का');
        $this->pro_data['months'] = $month->whereStatus(1)->pluck('name', 'id');
        $this->pro_data['trimesters'] = Trimester::pluck('name', 'id');
        return view('admin.amendment_report.index', $this->pro_data);
    }

    public function amendmentReportPost(Request $request)
    {
        $this->pro_data['budget_topic_id'] = $request->get('budget_topic_id') != '' ? $request->get('budget_topic_id') : '';
        $this->pro_data['expenditure_topic_id'] = $request->get('expenditure_topic_id') != '' ? $request->get('expenditure_topic_id') : '';
        $this->pro_data['implementing_office_id'] = $request->get('implementing_office_id') != '' ? $request->get('implementing_office_id') : '';
        $this->pro_data['amendment'] = $request->get('amendment') != '' ? $request->get('amendment') : '';
//        $this->pro_data['projects'] = $this->project->whereStatus(1)->whereBudget_topic_id($this->pro_data['budget_topic_id'])->whereExpenditure_topic_id($this->pro_data['expenditure_topic_id']);
        $this->pro_data['projects'] = $this->project->whereStatus(1)->where('budget_id',$this->pro_data['budget_topic_id'])->where('expenditure_id',$this->pro_data['expenditure_topic_id']);
        $implementing_office_id = $request->get('implementing_office_id');
        if ($request->get('division_code')) {
            $this->pro_data['division_code'] = $division_code = $request->get('division_code');
        }
        if ($implementing_office_id != 0) {
//            $this->pro_data['projects'] = $this->pro_data['projects']->whereImplementing_office_id($this->pro_data['implementing_office_id']);
            $this->pro_data['projects'] = $this->pro_data['projects']->where('implementing_id', $this->pro_data['implementing_office_id']);
        }
        if (isset($division_code)) {
            if ($division_code != 0) {
                $this->pro_data['projects'] = $this->pro_data['projects']->whereDivision_code($division_code);
            }
        }
//        $this->pro_data['projects'] = $this->pro_data['projects']->has('allocation')->orderBy('implementing_office_id', 'asc')->orderBy('division_code', 'asc')->get();
        $this->pro_data['projects'] = $this->pro_data['projects']->has('allocation')->orderBy('implementing_id', 'asc')->orderBy('division_code', 'asc')->get();

        return view('admin.amendment_report.views', $this->pro_data);
    }

    public function amendmentForm(ExpenditureTopic $expendituretopic, Division $division, Month $month)
    {
        $this->pro_data['expendituretopics'] = $expendituretopic->whereStatus(1)->pluck('expenditure_topic_num', 'id');
        $this->pro_data['divisionoffices'] = add_my_array($division->whereStatus(1)->pluck('name', 'id'), 'सबै डी का');
        $this->pro_data['months'] = $month->whereStatus(1)->pluck('name', 'id');
        $this->pro_data['trimesters'] = Trimester::pluck('name', 'id');
        return view('admin.amendment_report.formOptions', $this->pro_data);
    }

    public function amendmentFormPost(Request $request)
    {
        $this->pro_data['budget_topic_id'] = $request->get('budget_topic_id') != '' ? $request->get('budget_topic_id') : '';
        $this->pro_data['expenditure_topic_id'] = $request->get('expenditure_topic_id') != '' ? $request->get('expenditure_topic_id') : '';
        $this->pro_data['implementing_office_id'] = $request->get('implementing_office_id') != '' ? $request->get('implementing_office_id') : '';
        $this->pro_data['amendment'] = $request->get('amendment') != '' ? $request->get('amendment') : '';
//        $this->pro_data['projects'] = $this->project->whereStatus(1)->whereBudget_topic_id($this->pro_data['budget_topic_id'])->whereExpenditure_topic_id($this->pro_data['expenditure_topic_id']);
        $this->pro_data['projects'] = $this->project->whereStatus(1)->where('budget_id',$this->pro_data['budget_topic_id'])->where('expenditure_id',$this->pro_data['expenditure_topic_id']);
        $implementing_office_id = $request->get('implementing_office_id');
        if ($request->get('division_code')) {
            $division_code = $request->get('division_code');
        }
        if ($implementing_office_id != 0) {
//            $this->pro_data['projects'] = $this->pro_data['projects']->whereImplementing_office_id($this->pro_data['implementing_office_id']);
            $this->pro_data['projects'] = $this->pro_data['projects']->where('implementing_id', $this->pro_data['implementing_office_id']);
        }
        if (isset($division_code)) {
            if ($division_code != 0) {
                $this->pro_data['projects'] = $this->pro_data['projects']->whereDivision_code($division_code);
            }
        }
//        $this->pro_data['projects'] = $this->pro_data['projects']->has('allocation')->orderBy('implementing_office_id', 'asc')->orderBy('division_code', 'asc')->get();
        $this->pro_data['projects'] = $this->pro_data['projects']->has('allocation')->orderBy('implementing_id', 'asc')->orderBy('division_code', 'asc')->get();

        return view('admin.amendment_report.amendmentForm', $this->pro_data);
    }

    public function withAttributes(Fiscalyear $fiscalyear)
    {
        $this->pro_data['fiscal_year'] = add_my_array($fiscalyear->whereStatus(1)->pluck('fy', 'id'), 'Any');
        $this->pro_data['statuses'] = array('Any', 'Running', 'Completed');
        return view('admin.progressreport.withattr', $this->pro_data);
    }

    public function withAttributesPost(Request $request)
    {
        $attribute = $request->attr;
        $fiscal_year = $request->fiscal_year;
        $project_status = $request->status;

        $this->pro_data['projects'] = $this->project;

        $budgetTopicId = $request->get('budget_topic_id');
        $implementing_office = $request->get('implementingOffice');

//        $this->pro_data['projects'] = $this->pro_data['projects']->whereBudget_topic_id($budgetTopicId);
        $this->pro_data['projects'] = $this->pro_data['projects']->where('budget_id',$budgetTopicId);
        if ($implementing_office != 0) {
            $this->pro_data['projects'] = $this->pro_data['projects']->where('implementing_office_id', $implementing_office);
        }

        if ($attribute == 4) {
            $this->pro_data['projects'] = $this->project->whereHas('timeExtension', function ($timeExtension) use ($fiscal_year) {
                if ($fiscal_year != 0) {
                    $year_range = getYearRange(Fiscalyear::find($fiscal_year));
                    $timeExtension->whereBetween('end_date', $year_range);
                }
            });
        } else {
            $this->pro_data['projects'] = $this->project->whereHas('variation', function ($variation) use ($fiscal_year, $attribute) {
                $variation->where('status', $attribute);
                if ($fiscal_year != 0) {
                    $year_range = getYearRange(Fiscalyear::find($fiscal_year));
                    $variation->whereBetween('vope_date', $year_range);
                }
            });

        }

        if ($project_status == 1) {
            $this->pro_data['projects'] = $this->pro_data['projects']->where('show_on_running', '1');
        } elseif ($project_status == 2) {
            $this->pro_data['projects'] = $this->pro_data['projects']->where('show_on_running', '!=', 1);
        } else {
            //TOOD Nothing
        }

        $this->pro_data['projects'] = $this->pro_data['projects']->get();
        $this->pro_data['attribute'] = $attribute;
        return view('admin.progressreport.withattrpost', $this->pro_data);

    }

    public function greaterExpenses()
    {
        $this->pro_data['statuses'] = getProjectStatus();
        $this->pro_data['statuses'][4] = "Any";
        return view('admin.progressreport.greaterexpenses', $this->pro_data);
    }

    public function greaterExpensesPost(Request $request)
    {
        $this->pro_data['projects'] = $this->project;
        $budgetTopicId = $request->get('budget_topic_id');
        $project_status = $request->get('status');
        $implementing_office = $request->get('implementing_office_id');
        if ($project_status == 0) {
            $this->pro_data['projects'] = $this->pro_data['projects']->where('show_on_running', '1');
        } elseif ($project_status == 4) {
            $this->pro_data['projects'] = $this->pro_data['projects'];
        } else {
            $this->pro_data['projects'] = $this->pro_data['projects']->where('show_on_running', '0');
        }
        if ($implementing_office != 0) {
//            $this->pro_data['projects'] = $this->pro_data['projects']->whereImplementing_office_id($implementing_office);
            $this->pro_data['projects'] = $this->pro_data['projects']->where('implementing_id', $implementing_office);
            $this->pro_data['implementing_office_id'] = $implementing_office;
        }
//        $this->pro_data['projects'] = $this->pro_data['projects']->whereBudget_topic_id($budgetTopicId);
        $this->pro_data['projects'] = $this->pro_data['projects']->where('budget_id',$budgetTopicId);
        $this->pro_data['projects'] = $this->pro_data['projects']->get();
        return view('admin.progressreport.greaterexpensesPost', $this->pro_data);
    }

    public function procurementMissing()
    {
        $this->pro_data['statuses'] = getProjectStatus();
        $this->pro_data['statuses'][4] = "Any";
        return view('admin.progressreport.procurement_missing', $this->pro_data);
    }

    public function procurementMissingPost(Request $request)
    {
        $budgetTopicId = $request->get('budget_topic_id');
//        $this->pro_data['projects'] = $this->project->where('budget_topic_id', $budgetTopicId);
        $this->pro_data['projects'] = $this->project->where('budget_id', $budgetTopicId);
        $project_status = $request->get('status');
        $implementing_office = $request->get('implementing_office_id');

        if ($project_status == 0) {
            $this->pro_data['projects'] = $this->pro_data['projects']->where('show_on_running', '1');
        } elseif ($project_status == 4) {
            $this->pro_data['projects'] = $this->pro_data['projects'];
        } else {
            $this->pro_data['projects'] = $this->pro_data['projects']->where('project_status', $project_status);
        }
        if ($implementing_office != 0) {
//            $this->pro_data['projects'] = $this->pro_data['projects']->whereImplementing_office_id($implementing_office);
            $this->pro_data['projects'] = $this->pro_data['projects']->where('implementing_id', $implementing_office);
            $this->pro_data['implementing_office_id'] = $implementing_office;
        }

        $heading = [];
        $heading[0] = "Id";
        $heading[1] = "Project Code";
        $heading[2] = "Name";
        $heading[3] = "Name English";
        $heading[4] = "Land Ownership";
        $heading[5] = "Expenditure Topic";
        $heading[6] = "Budget Topic";
        $heading[7] = "Start Fiscal Year";
        $heading[8] = "Implementing Office";
        $heading[9] = "Approved Date";
        $heading[10] = "Unit";
        $heading[11] = "Quantity";
        $heading[12] = "District";
        $heading[13] = "Address";
        $heading[14] = "Coordinates";
        $heading[15] = "Liability";
        $heading[16] = "Description";
        $heading[17] = "Project Sub Group";
        $heading[18] = "Nature of project";
        $heading[19] = "Project Status";
        $heading[20] = "Payment Status";
        $heading[21] = "Completed Date";
        $heading[22] = "Completed FY";
        $heading[23] = "Hand Over Date";
        $heading[24] = "Hand Over FY";
        $heading[25] = "In This FY?";
        $exportTable[] = $heading;

        $projects = $this->pro_data['projects'];

        foreach ($projects->get() as $index => $project) {
            $exportField[0] = $project->id;
            $exportField[1] = $project->project_code;
            $exportField[2] = $project->name;
            $exportField[3] = $project->name_eng;
            $exportField[4] = $project->swamitto ? swamittwo($project->swamitto) : 'N/A';

            $exportField[5] = $project->expenditure_topic ? $project->expenditure_topic->expenditure_head : 'N/A';
            $exportField[6] = $project->budget_topic ? $project->budget_topic->budget_head : 'N/A';
            $exportField[7] = $project->fiscal_year ? $project->fiscal_year->fy : 'N/A';
            $exportField[8] = $project->implementing_office ? $project->implementing_office->name : 'N/A';
            $exportField[9] = $project->approved_date;
            $exportField[10] = $project->unit;
            $exportField[11] = $project->quantity;
            $exportField[12] = $project->district ? $project->district->name : 'N/A';
            $exportField[13] = $project->address;
            $exportField[14] = $project->coordinates;

            $exportField[15] = $project->liability;
            $exportField[16] = $project->description;
            $exportField[17] = $project->group ? $project->group->name : 'N/A';
            $exportField[18] = nature_of_project()['eng'][$project->monitoring_office_id][$project->nature_of_project_id];
            $exportField[19] = getProjectStatus()[$project->project_status];

            $exportField[20] = $project->payment_status ? getPaymentStatus()[$project->payment_status] : 'N/A';
            $exportField[21] = $project->completed_date;
            $exportField[22] = $project->completedFiscalYear ? $project->completedFiscalYear->fy : 'N/A';
            $exportField[23] = $project->ho_date;
            $exportField[24] = $project->ho_fiscalYear ? $project->ho_fiscalYear->fy : 'N/A';
            $exportField[25] = $project->show_on_running == 1 ? 'Yes' : 'No';
            $exportTable[] = $exportField;
        }

        $this->pro_data['excel'] = Excel::create('projects', function ($excel) use ($exportTable) {
            $excel->sheet('projects', function ($sheet) use ($exportTable) {
                $sheet->fromArray($exportTable, null, 'A1', false, false);
            });
        });

        $this->pro_data['projects'] = $this->pro_data['projects']->whereHas('procurement', function ($procurement) {
            foreach (Procurement::first()->toArray() as $key => $attribute) {
                $procurement->orWhere($key, null);
            }
        });

        $this->pro_data['projects'] = $this->pro_data['projects']->get();
        return view('admin.progressreport.procurement_missingPost', $this->pro_data);
    }

    public function progressPercentage()
    {
        if (Auth::user()->implementing_office_id != 1) {
            if (Auth::user()->implementingOffice->isMonitoring == 1) {
                $this->pro_data['implementingOffices'] = add_my_array(Auth::user()->implementingOffice->MonitorSeesImplementing);
            } else {
                $this->pro_data['implementingOffices'] = Auth::user()->implementingOffice()->get();
            }
        }
        return view('admin.progressreport.progressPercentage', $this->pro_data);
    }

    public function completed_projects(BudgetTopic $budgetTopic, Fiscalyear $fiscalyear)
    {
        $this->pro_data['budgettopics'] = $budgetTopic->whereStatus(1)->pluck('budget_topic_num', 'id');
        $this->pro_data['fiscalYear'] = $fiscalyear->whereStatus(1)->pluck('fy', 'id');
        return view('admin.progressreport.completed_projects', $this->pro_data);
    }

    public function completed_projectsPost(Request $request, ImplementingOffice $implementingOffice, Project $project)
    {
        $this->pro_data['project'] = $project;
        $fiscalYear = intval($request->fy_id);
        $this->pro_data['fiscalyear'] = $fiscalYear;

        $this->pro_data['budget_topic_id'] = $request->budget_topic_id;
        $this->pro_data['project_status'] = $request->project_status;

        /*$this->pro_data['implementingoffices'] = $implementingOffice->whereStatus(1)->where('isMonitoring', 0)->whereHas('projects', function ($project) {
            $project->where('monitoring_office_id', Auth::user()->implementingOffice->id);
        })->get();*/
        $this->pro_data['implementingoffices'] = Auth::user()->visibleImplementingOffices()->with('child')->get();
        if ($request->has('detailed_report')) {
            return view('admin.progressreport.completed_projectsDetailPost', $this->pro_data);
        } else {

            return view('admin.progressreport.completed_projectsPost', $this->pro_data);
        }


    }

    public function group_report(Fiscalyear $fiscalyear)
    {
        $this->pro_data['fiscalYear'] = $fiscalyear->whereStatus(1)->pluck('fy', 'id');
        return view('admin.progressreport.group_report', $this->pro_data);

    }

    public function group_reportPost(Request $request)
    {
        // 2075 -76 bata budget topic change bahaye pachi
        // purano ra naya dautai ko group haru aune gari
        // naya bata purano aune gareko cha .. purano bata naya aune gareko chaina ..
        // TODO to check all places .. where budget topic effects .. .
        $budget_topic_ids = [];
        $budget_topic = BudgetTopic::find($request->budget_topic_id);
        array_push($budget_topic_ids, $request->budget_topic_id);
        if ($budget_topic->getOriginal()['budget_topic_num'] != $budget_topic->budget_topic_num_old) {
            $old_budget_topic = BudgetTopic::where('budget_topic_num', $budget_topic->budget_topic_num_old)->first();
            if ($old_budget_topic) {
                array_push($budget_topic_ids, $old_budget_topic->id);
            }
        }
//        $this->pro_data['project_groups'] = BudgetTopic::find($request->budget_topic_id)->projectGroup()->where('group_category_id', '=', 1);
//        $this->pro_data['project_groups'] = ProjectGroup::whereIn('budget_topic', $budget_topic_ids)->where('group_category_id', '=', 1);
        $this->pro_data['project_groups'] = ProjectGroup::where('group_category_id', '=', 1)->whereHas('child',function($child) use ($budget_topic_ids){
            $child->whereHas('projects', function($projects) use ($budget_topic_ids){
                $projects->whereHas('projectSettings', function($setting) use ($budget_topic_ids){
                    $setting->whereIn('budget_id', $budget_topic_ids)->where('fy', session()->get('pro_fiscal_year'));
                });
            });
        });
        if ($request->has('till_now')) {
            $this->pro_data['till_now'] = 1;
        }
        $this->pro_data['budget_topic'] = $request->budget_topic_id;
        $this->pro_data['implementingOffice'] = $request->implementingOffice;
        $this->pro_data['show_on_running'] = $request->show_on_running;
        $this->pro_data['fy_id'] = $request->fy_id;
        $this->pro_data['project_groups'] = $this->pro_data['project_groups']->get();
        return view('admin.progressreport.group_reportPost', $this->pro_data);
    }

    public function groupReport()
    {
        $this->pro_data['fiscalYear'] = Fiscalyear::whereStatus(1)->pluck('fy', 'id');
        $this->pro_data['project_group_id'] = ProjectGroup::whereHas('parent', function ($parent) {
            $parent->where('id', '>', 1);
        })->pluck('name', 'id');
        $this->pro_data['nature_of_project_id'] = visibleNature_of_project();
        return view('admin.progressreport.group_report_for_seeing_project', $this->pro_data);
    }

    public function groupReportPost(Request $request)
    {
        $fy_id = $request->get('fy_id');
        $this->pro_data['projects'] = $this->project;

        $this->pro_data['projects'] = $this->pro_data['projects']->where('project_group_id', $request->get('project_group_id'));
        if ($request->get('nature_of_project_id') != 0) {
            $this->pro_data['projects'] = $this->pro_data['projects']->where('nature_of_project_id', $request->get('nature_of_project_id'));
        }
        $progresses = $request->get('progresses');
        if ($request->has('till_now')) {
            $this->pro_data['projects'] = $this->pro_data['projects']->where('start_fy_id', '<=', $fy_id);
        } else {
            $this->pro_data['projects'] = $this->pro_data['projects']->where('start_fy_id', $fy_id);
        }
        //check $project->lastProgress->progressTrack
        if ($progresses == 1) { // 5 percent
            $this->pro_data['projects'] = $this->pro_data['projects']->whereHas('progresses', function ($progresses) {
                $progresses->where('id', function ($progresses) {
                    $progresses->from('pro_progresses as progress')
                        ->selectRaw('max(id)')
                        ->whereRaw('progress.project_code = pro_projects.id');
                })->whereHas('progressTrack', function ($progressTrack) {
                    $progressTrack->where('physical_percentage', 5);
                });
            });
        }
        if ($progresses == 2) { // 10 percent
            $this->pro_data['projects'] = $this->pro_data['projects']->whereHas('progresses', function ($progresses) {
                $progresses->where('id', function ($progresses) {
                    $progresses->from('pro_progresses as progress')
                        ->selectRaw('max(current_physical_progress)')
                        ->whereRaw('progress.project_code = pro_projects.id');
                })->whereHas('progressTrack', function ($progressTrack) {
                    $progressTrack->where('physical_percentage', 10);
                });
            });
        }
        if ($progresses == 3) { // 10 to 90 percent

        }
        if ($progresses == 4) { // 90 plus not hundred


        }
        if ($progresses == 5) { // hundred
            $this->pro_data['projects'] = $this->pro_data['projects']->whereHas('progresses', function ($progresses) {
                $progresses->where('id', function ($progresses) {
                    $progresses->from('pro_progresses as progress')
                        ->selectRaw('max(id)')
                        ->whereRaw('progress.project_code = pro_projects.id');
                })->whereHas('progressTrack', function ($progressTrack) {
                    $progressTrack->whereBetween('physical_percentage', [10, 90]);
                });
            });
        }

        $this->pro_data['projects'] = $this->pro_data['projects']->get();
        return view('admin.progressreport.hbms', $this->pro_data);

    }

    public function contractInfo()
    {
        $this->pro_data['fiscal_year'] = add_my_array(Fiscalyear::whereStatus(1)->pluck('fy', 'id'), 'Choose Fiscal Year', 0);
        $this->pro_data['project_group_id'] = ProjectGroup::whereHas('parent', function ($parent) {
            $parent->where('id', '>', 1);
        })->pluck('name', 'id');
        return view('admin.contractinfo.form', $this->pro_data);
    }

    public function contractInfoPost(Request $request)
    {

        $implementingOffice = $request->implementing_office_id;
        $budget_topic_id = $request->budget_topic_id;
        $fy_id = $request->fiscal_year_id;
//        $projects = $this->project->where('budget_topic_id', $budget_topic_id)->where('show_on_running', '1');
        $projects = $this->project->where('budget_id', $budget_topic_id)->where('show_on_running', '1');
        if ($implementingOffice != 0) {
//            $projects = $projects->where('implementing_office_id', $implementingOffice);
            $projects = $projects->where('implementing_id', $implementingOffice);
        }
        if ($fy_id != 0) {
            $projects = $projects->where('start_fy_id', '=', $fy_id);
        }

        $heading[0] = "क्र.सं.";
        $heading[1] = "डिभिजन कोड";
        $heading[2] = "डिभिजन कार्यालय";
        $heading[3] = "आइ. डी";
        $heading[4] = "क्रियाकलाप बिवरण";
        $heading[5] = "ल.इ स्वीकृत रकम (भ्याट र कन्टिनजेसी बाहेक) ";
        $heading[6] = "Contract ID";
        $heading[7] = "बोलपत्र तयारी मिति";
        $heading[8] = "बोलपत्र आह्वान मिति";
        $heading[9] = "बोलपत्र खोलेको मिति";
        $heading[10] = "बोलपत्र मुल्यांकन मिति";
        $heading[11] = "सम्झौता रकम भ्याट तथा कन्टिन्जेन्सी बाहेक";
        $heading[12] = "सम्झौता मिति";
        $heading[13] = "सम्पन्न हुनपर्ने मिति";
        $heading[14] = "सम्झौता बिधि";
        $heading[15] = "निर्माण ब्यबसायीको नाम,ठेगाना";
        $heading[16] = "निर्माण ब्यबसायीको देश";
        $heading[17] = "निर्माण ब्यबसायीको मोबाइल";
        $heading[18] = "निर्माण ब्यबसायीको Email";
        $heading[19] = "अख्तियारी प्राप्त व्यक्ति को नाम";
        $heading[20] = "अख्तियारी प्राप्त व्यक्ति को मोबाइल";
        $heading[21] = "Site Engineerको नाम";
        $heading[22] = "Site Engineerको मोबाइल";
        $exportTable[] = $heading;
        foreach ($projects->get() as $index => $project) {

            $setting = $project->projectSettings()->where('fy',session()->get('pro_fiscal_year'))->first();
            if(!$setting){
                $setting = $project;
            }

            $exportField[0] = $index + 1;
            $exportField[1] = $setting->implementing_office->office_code;
            $exportField[2] = $setting->implementing_office->name;
            $exportField[3] = $setting->project_code;
            $exportField[4] = $project->name;
            $exportField[5] = $project->procurement->con_est_amt_net;
            $exportField[6] = $project->procurement->con_id_div;
            $exportField[7] = $project->procurement->bid_does_ready_act;
            $exportField[8] = $project->procurement->call_for_bid_act;
            $exportField[9] = $project->procurement->bid_open_act;
            $exportField[10] = $project->procurement->bid_eval_act;
            $exportField[11] = $project->procurement->contract_amount;
            $exportField[12] = $project->procurement->contract_date;
            $exportField[13] = $project->procurement->completion_date;
            $exportField[14] = $project->procurement ? $project->procurement->method : "N/A";
            $contractor = "N/A";
            $contractorMobile = "N/A";
            $contractorEmail = "N/A";
            if ($project->procurement->contractor) {
                $contractor = $project->procurement->Contractor->name . ' ,' . $project->procurement->Contractor->address;
                $contractorMobile = $project->procurement->Contractor->myUser->phone;
                $contractorEmail = $project->procurement->Contractor->myUser->email;
            } elseif ($project->procurement->JointVenture) {
                $contractor = $project->procurement->JointVenture->name . ' ,' . $project->procurement->JointVenture->address;
                $contractorMobile = '';
                $contractorEmail = '';
                foreach ($project->procurement->JointVenture->contractors as $contractor) {
                    $contractorMobile .= $contractor->myUser->phone ? $contractor->myUser->phone . ', ' : "";
                    $contractorEmail = $contractor->myUser->email ? $contractor->myUser->email . ', ' : "";
                }

            }
            $exportField[15] = $contractor;
            $exportField[16] = $contractor == "N/A" ? "N/A" : "Nepal";
            $exportField[17] = $contractorMobile;
            $exportField[18] = $contractorEmail;

            $authorizedPersonName = "";
            $authorizedPersonMobile = "";

            foreach ($project->authorizedPersons as $authorizedPerson) {
//                $authorizedPersonName .= $authorizedPerson->myUser->name ? $authorizedPerson->myUser->name . ', ' : '';
                $authorizedPersonName .= $authorizedPerson->name ? $authorizedPerson->name . ', ' : '';
                $authorizedPersonMobile .= $authorizedPerson->mobile ? $authorizedPerson->mobile . ', ' : "";
            }

            $exportField[19] = rtrim($authorizedPersonName, ', ');
            $exportField[20] = rtrim($authorizedPersonMobile, ', ');


            $siteEngineerName = "";
            $siteEngineerMobile = "";

            foreach ($project->Engineers as $siteEngineer) {
                $siteEngineerName .= $siteEngineer->myUser ? $siteEngineer->myUser->name . ', ' : "";
                $siteEngineerMobile .= $siteEngineer->mobile ? $siteEngineer->mobile . ', ' : "";
            }

            $exportField[21] = rtrim($siteEngineerName, ', ');
            $exportField[22] = rtrim($siteEngineerMobile, ', ');


            $exportTable[] = $exportField;
        }
        Excel::create('Contractor Related Information', function ($excel) use ($exportTable) {
            $excel->sheet('sheet1', function ($sheet) use ($exportTable) {
                $sheet->setColumnFormat(array(
                    'F' => '0.00',
                    'L' => '0.00',
                ));
                $sheet->fromArray($exportTable, null, 'A1', false, false);
            });
        })->download($this->excelFormat);
    }

    public function hoWithOutPapers()
    {
        return view('admin.progressreport.howithoutpapers', $this->pro_data);
    }


    /*------------On Going-----------*/

    /*------------On Going-----------*/


    /*------------NOT COMPLETED-----------*/

    /*------------NOT COMPLETED-----------*/

    public function hoWithOutPapersPost(Request $request)
    {
        $projects = $this->project->where('project_status', 2)->whereNotNull('ho_date');
        $projects = $projects->whereDoesntHave('activityLogs', function ($activityLogs) {
            $activityLogs->where('type', 2); //ho papers means two!!!
        });

        $implementing_office_id = $request->get('implementing_office_id');

        if ($implementing_office_id != 0) {
            $this->pro_data['implementing_office_name'] = ImplementingOffice::find($implementing_office_id)->name;
//            $projects = $projects->whereImplementing_office_id($implementing_office_id);
            $projects = $projects->where('implementing_id', $implementing_office_id);
        }

//        $this->pro_data['projects'] = $projects->whereBudget_topic_id($request->get('budget_topic_id'))->get();
        $this->pro_data['projects'] = $projects->where('budget_id',$request->get('budget_topic_id'))->get();
        return view('admin.progressreport.hoWithOutPapersPost', $this->pro_data);

    }

    public function barsikPragariFormat()
    {
        $report = new AnnualProgressReportExport($this->project->where('show_on_running', 1)->get());
        $report->download();
    }
}

