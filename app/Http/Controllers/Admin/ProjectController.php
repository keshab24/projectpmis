<?php

namespace PMIS\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use PMIS\ActivityLog;
use PMIS\ActivityLogFiles;
use PMIS\BudgetTopic;
use PMIS\ConstructionType;
use PMIS\Contractor;
use PMIS\District;
use PMIS\Engineer;
use PMIS\Fiscalyear;
use PMIS\Http\Controllers\AdminBaseController;
use PMIS\Http\Requests\CreateProjectRequest;
use PMIS\Http\Requests\CreateTimeExtensionRequest;
use PMIS\Http\Requests\CreateVariationRequest;
use PMIS\ImplementingOffice;
use PMIS\Jobs\JobInitiator;
use PMIS\Liquidation;
use PMIS\Manpower;
use PMIS\OldProjectCode;
use PMIS\Procurement;
use PMIS\ProcurementDate;
use PMIS\Project;
use PMIS\ProjectBlocks;
use PMIS\ProjectGroup;
use PMIS\ProjectSetting;
use PMIS\Sector;
use PMIS\State;
use PMIS\Traits\DateConverterTrait;


class ProjectController extends AdminBaseController
{
    protected $pro_data;
    protected $project;
    protected $not_running_projects;
    use SelectUserTrait;
    use DateConverterTrait;

    public function __construct(Project $project, ImplementingOffice $implementingOffice, BudgetTopic $budgettopic, ProjectBlocks $projectBlocks)
    {
        parent::__construct();
        $this->middleware(function ($request, $next) use ($budgettopic, $project,$projectBlocks) {
            $this->project = $this->user_info->visibleProjects();

            $this->projectBlocks = $projectBlocks;
            $this->not_running_projects = $this->user_info->visibleProjects(true);
            $this->pro_data['implementing_offices'] = add_my_array(Auth::user()->visibleImplementingOffices()->get()->pluck('title', 'id'), "Any");
            $this->pro_data['implementing_offices_new_update'] = Auth::user()->visibleImplementingOffices()->with('child')->get();

            $budget_topic = $budgettopic->where('monitoring_office_id', Auth::user()->implementing_office_id);
            if ($budget_topic->count() == 0) {
                $monitoringOffices = Auth::user()->implementingOffice->implementingSeesMonitor->pluck('id');
            }
            $this->pro_data['budgettopics'] = add_my_array($budget_topic->whereStatus(1)->pluck('budget_topic_num', 'id'));
            $this->pro_data['states'] = add_my_array(State::whereStatus(1)->pluck('name', 'id'));


            $this->pro_data['orderBy'] = 'district_id';
            $this->pro_data['order'] = 'asc';
            $this->pro_data['other_data'] = '';
            $this->pro_data['default_search'] = '';

            $this->pro_data['projects'] = $this->project;
            $this->pro_data['trashes_no'] = $project->onlyTrashed()->count();
            return $next($request);
        });
    }

    public function index()
    {
        $show_on_running = 1;
        $implementing_office = isset($_GET['implementing_office']) ? $_GET['implementing_office'] : 0;
        if ($implementing_office != 0) {
            $this->pro_data['projects'] = $this->pro_data['projects']->whereImplementing_office_id($implementing_office);
            $this->pro_data['implementing_office'] = $_GET['implementing_office'];
        }

        if (isset($_GET['trashes'])) {
            $show_on_running = 0;
            $this->pro_data['projects'] = $this->pro_data['projects']->onlyTrashed();
        }

        if (isset($_GET['terminated'])) {
            //
            $show_on_running = 0;
            $this->pro_data['projects'] = $this->pro_data['projects']->where('cancelled', 1)->whereIn('cancelled_reason', [1, 2]);
        }

        if (isset($_GET['cancelled'])) {
            //dropped project
            $show_on_running = 0;
            $this->pro_data['projects'] = $this->pro_data['projects']->where('cancelled', 1)->where('cancelled_reason', 3);
        }

        if (isset($_GET['orderBy']) && isset($_GET['order'])) {
            $this->pro_data['orderBy'] = $_GET['orderBy'];
            $this->pro_data['order'] = (strtolower($_GET['order']) == 'asc') ? 'desc' : 'asc';
        }

        if ($show_on_running == 1) {
            $this->pro_data['projects'] = $this->pro_data['projects']->where('show_on_running', '1');
        }

        $this->pro_data['projects'] = $this->pro_data['projects']->orderBy('implementing_id', 'asc')->orderBy($this->pro_data['orderBy'], $this->pro_data['order']);
        $this->pro_data['projects'] = $this->paginateCollection($this->pro_data['projects']->get(), $this->pro_data['limit'] ?? 50);
        $this->pro_data['count'] = count($this->pro_data['projects']);
        $this->pro_data['fiscal_years'] = add_my_array(Fiscalyear::orderBy('id', 'asc')->pluck('fy', 'id'));
        return view('admin.project.index', $this->pro_data);
    }

    public function show(Project $project, Engineer $engineer, ProjectBlocks $projectBlocks, Manpower $manpower)
    {
       
        restrictEngineers($this->user_info->type_flag);
        seeDetail(clone ($this->project)->get()->merge(clone ($this->not_running_projects)->get()), $project->id);
        $this->pro_data['project'] = $project;
        $this->pro_data['project_blocks'] = $projectBlocks->whereProjectId($project->id)->get();
        $this->pro_data['manpower_types'] = $manpower->whereStatus(1)->whereCountable(0)->orderBy('order')->get()->groupBy('type');

        $this->pro_data['dailyProgressUsers'] = $engineer->where('implementing_office', $project->implementing_office_id)->pluck('name', 'id');
        return view('admin.project.show', $this->pro_data);
    }

    public function manageEngineers(Project $project, Request $request)
    {
        
        foreach ($request->all() as $key=>$value){
            $ids[] = $value;
        }
        array_shift($ids); //remove csrf token
        $syncManpowers = [];
        foreach ($ids as $id) $syncManpowers = array_merge($syncManpowers, $id);

        $project->Engineers()->sync($syncManpowers);
        session()->flash('update_success_info', '" Project which named ' . $project->name . ' \'s Project Cordinaors list"');
        
        return redirect()->back();
    }

    public function manageDailyProgressUser(Project $project, Request $request)
    {
        $user = array();

        if ($request->has('users')) {
            $user = $request->get('users');
        }
        $syncDailyProgressUser = array_merge($user);

        $project->DailyProgressUsers()->sync($syncDailyProgressUser);
        session()->flash('update_success_info', '" Project named ' . $project->name . ' \'s Daily Progress Users"');
        return redirect()->back();
    }


    public function search()
    {
        $this->pro_data['default_search'] = isset($_GET['search']) ? $_GET['search'] : null;
        $this->pro_data['other_data'] = '&search=' . $this->pro_data['default_search'];
        $implementing_office = isset($_GET['implementing_office']) ? $_GET['implementing_office'] : 0;
        $budgettopic = isset($_GET['budgettopic']) ? $_GET['budgettopic'] : 0;
        $fiscal_year = isset($_GET['fiscal_year']) ? $_GET['fiscal_year'] : 0;
        $this->pro_data['variation_options'] = Variation::variationOptions();
        $expendituretopic = isset($_GET['expendituretopic']) ? $_GET['expendituretopic'] : 0;

        if (isset($_GET['variation_type']) && $_GET['variation_type'] != 'none') {
            $variation_type = $_GET['variation_type'];
            if(isset($_GET['completed'])){
                $this->pro_data['projects'] = $this->not_running_projects->whereHas('variation', function ($q) use ($variation_type) {
                    $q->where('status', $variation_type);
                });
            }else {
                $this->pro_data['projects'] = $this->project->whereHas('variation', function ($q) use ($variation_type) {
                    $q->where('status', $variation_type);
                });
            }
        }

        if (isset($_GET['time_extended'])) {
            $this->pro_data['projects'] = $this->project->has('timeExtension');
        }

        // if (isset($_GET['completed'])) {
        //     $this->pro_data['projects'] = $this->not_running_projects;
        // }

        if (isset($_GET['state']) && $_GET['state'] != 0) {
            $state = $_GET['state'];
            $this->pro_data['state'] = $state;
            $this->pro_data['projects'] = $this->pro_data['projects']->whereHas('district', function ($district) use ($state) {
                $district->where('state_id', $state);
            });
        }

        if ($expendituretopic != 0) {
            //first filtered by fy then related field (on pro_project_settings table)
//            $this->pro_data['projects'] = $this->pro_data['projects']->whereExpenditureTopicId($expendituretopic);
            $this->pro_data['projects'] = $this->pro_data['projects']->where('expenditure_id', $expendituretopic);
            $this->pro_data['expendituretopic'] = $_GET['expendituretopic'];
        }

        if ($budgettopic != 0) {
            //first filtered by fy then related field (on pro_project_settings table)
//            $this->pro_data['projects'] = $this->pro_data['projects']->whereBudgetTopicId($budgettopic);
            $this->pro_data['projects'] = $this->pro_data['projects']->where('budget_id', $budgettopic);
            $this->pro_data['budgettopic'] = $_GET['budgettopic'];
        }
        if ($implementing_office != 0) {
//            $this->pro_data['projects'] = $this->pro_data['projects']->whereImplementing_office_id($implementing_office);
            $this->pro_data['projects'] = $this->pro_data['projects']->where('implementing_id', $implementing_office);
            $this->pro_data['implementing_office'] = $_GET['implementing_office'];
        }
        if ($fiscal_year != 0) {
            $this->pro_data['projects'] = $this->pro_data['projects']->where('start_fy_id', $fiscal_year);
            $this->pro_data['fiscal_year'] = $_GET['fiscal_year'];
        }

        if (isset($_GET['completed'])) {
            $this->pro_data['projects'] = $this->pro_data['projects']->where('show_on_running', '0')
                ->where('completed_fy', '<=', session()->get('pro_fiscal_year'));
        } else {
            $this->pro_data['projects'] = $this->pro_data['projects']->where('show_on_running', '1');
        }

        if (isset($_GET['cancelled'])) {
            // cancelled or terminated
            $this->pro_data['projects'] = $this->pro_data['projects']->withTrashed()->orWhere('cancelled', 1);
        } else {
            $this->pro_data['projects'] = $this->pro_data['projects']->where('cancelled', 0);
        }

        if ($this->pro_data['default_search'] != '' || $this->pro_data['default_search'] != null) {
            $this->pro_data['projects'] = $projects = $this->pro_data['projects']->search($this->pro_data['default_search']);
        }
        $this->pro_data['projects'] = $this->pro_data['projects']->orderBy('implementing_id', 'asc');
        if (request()->has('orderBy')) {
            $this->pro_data['projects'] = $this->pro_data['projects']->orderBy($this->pro_data['orderBy'], $this->pro_data['order']);
        } else {
            $this->pro_data['projects'] = $this->pro_data['projects']->orderBy('project_code');
        }

        $this->pro_data['projects'] = $this->pro_data['projects']->groupBy('project_id');
        $this->pro_data['projects'] = $this->paginateCollection($this->pro_data['projects']->get(), $this->pro_data['limit'] ?? 50);
//        $this->pro_data['projects'] = $this->pro_data['projects']->simplePaginate(50);

        $this->pro_data['count'] = count($this->pro_data['projects']);
        $this->pro_data['fiscal_years'] = add_my_array(Fiscalyear::orderBy('id', 'asc')->pluck('fy', 'id'));
        return view('admin.project.index', $this->pro_data);
    }

    public function create(ImplementingOffice $implementingoffice, ConstructionType $constructiontype, Sector $sector, District $district, Fiscalyear $fiscalyear, ProjectGroup $projectGroup)
    {
        restrictEngineers($this->user_info->type_flag);
        //cancelled and reason Contract break (Marked as Terminated Projects) afnai implementing office ko ..
        $this->pro_data['cancelled_projects'] = Project::where('cancelled', 1)->where('cancelled_reason', 1)->where('monitoring_office_id', Auth::user()->implementing_office_id)->whereDoesntHave('brokeProjectContinue')->select('name', 'id', 'name_eng', 'monitoring_office_id', 'project_code')->with('monitoringOffice')->get();
        restrictToImplementingOffice('abort');
        $this->pro_data['project_groups'] = $projectGroup->whereStatus(1)->where('level', '>', '1')
            ->whereHas('creator', function ($creator) {
                $creator->where('implementing_office_id', Auth::user()->implementing_office_id);
            });
//        if (($this->pro_data['project_groups']->count()) == 0) {
//            $this->pro_data['project_groups'] = $projectGroup->whereStatus(1)->where('level', '>', '1')
//                ->whereHas('creator', function ($creator) {
//                    $creator->whereIn('implementing_office_id', Auth::user()->implementingOffice->implementingSeesMonitor->pluck('id'));
//                });
//        }
        $this->pro_data['project_groups'] = add_my_array($this->pro_data['project_groups']->pluck('name', 'id'));
        $this->pro_data['construction_located_area'] = add_my_array($this->user_info->ConstructionLocatedArea()->pluck('located_area', 'id'));


        $this->pro_data['constructiontypes'] = add_my_array($this->user_info->ConstructionType()->whereStatus(1)->pluck('name', 'id'));
        $this->pro_data['fiscal_years'] = $fiscalyear->orderBy('id', 'desc')->pluck('fy', 'id');
        $this->pro_data['districts'] = add_my_array($district->whereStatus(1)->pluck('name_eng', 'id'));

        $this->pro_data['monitoring_offices'] = add_my_array($implementingoffice->whereStatus(1)->where('level', '>', 0)->where('IsMonitoring', '1')->pluck('name_eng', 'id'), 'Choose Office');
        if (Auth::user()->implementing_office_id != 1 && Auth::user()->implementingOffice->isMonitoring != 1) {
            $this->pro_data['districts'] = add_my_array($district->whereStatus(1)->whereHas('implementingOffice', function ($implementingOffice) {
                $implementingOffice->where('pro_implementing_offices.id', $this->user_info->implementing_office_id);
            })->pluck('name_eng', 'id'));
        }
        return view('admin.project.create', $this->pro_data);
    }

    public function store(CreateProjectRequest $request)
    {
        restrictEngineers($this->user_info->type_flag);
        $implementingOfficeId = $request->get('implementing_office_id');
        $monitoringOfficeId = $request->get('monitoring_office_id');
        $monitorin_office = ImplementingOffice::find($monitoringOfficeId);
        if ($monitorin_office->isAyojanaType) {
            $projectCode = $request->get('monitoring_office_id') . '-' . $request->get('project_code');
        } else {
            $projectCode = $request->get('monitoring_office_id') . ':' . $request->get('project_code');
        }
        if ($this->user_info->access != 'Root Level') {
            if ($this->user_info->implementingOffice->isMonitoring == 0) {
                $implementingOfficeId = $this->user_info->implementingOffice->id;
            } else {
                $monitoringOfficeId = $this->user_info->implementingOffice->id;
            }
        }

        $cancelled_project_id = null;
        if ($request->has('cancelled_project') && $request->get('cancelled_project') == 'on') {
            $cancelled_project_id = $request->get('cancelled_project_id');
        }
        $status = $request->get('status') == 'on' ? 1 : 0;
        $liability = session()->get('pro_fiscal_year') > $request->get('start_fy_id') ? 1 : 0;
        $coordinates = $request->manual_coordinates ? $request->manual_coordinates : trim($request->get('coordinates'), '()');
        $projectInfo = Project::create([
            'design_type' => $request->get('design_type'),
            'project_status' => 0,
            'project_code' => $projectCode,
            'cancelled_project_id' => $cancelled_project_id,
            'name' => $request->get('name'),
            'status' => $status,
            'name_eng' => $request->get('name_eng'),
            'expenditure_topic_id' => $request->get('expenditure_topic_id'),
            'budget_topic_id' => $request->get('budget_topic_id'),
            'construction_type_id' => $request->get('construction_type_id'),
            'fy_id' => $request->session()->get('pro_fiscal_year'),
            'start_fy_id' => $request->get('start_fy_id') ?: $request->session()->get('pro_fiscal_year'),
            'implementing_office_id' => $implementingOfficeId,
            'monitoring_office_id' => $monitoringOfficeId,
            'district_id' => $request->get('district_id') == 0 ? null : $request->get('district_id'),
            'address' => $request->get('address'),
            'coordinates' => $coordinates,
            'approved_date' => $request->get('approved_date'),
            'description' => $request->get('description'),
            'unit' => $request->get('unit'),
            'quantity' => $request->get('quantity'),
            'project_group_id' => $request->get('project_group_id'),
            'nature_of_project_id' => $request->get('nature_of_project_id'),
            'construction_located_area_id' => $request->get('construction_located_area_id'),
            'liability' => $liability,
            'trimester_id' => 0,
            'amendment_id' => 0,
            'story_area_unite' => 0,
            'land_ownership' => 0,
            'kittanumber' => '-',
            'shitnumber' => '-',
            'soiltest' => 0,
            'baringcapacity' => '-',
            'doorwindow' => 0,
        ]);
        //added project to settings table
        $project_settings = ProjectSetting::insert([
            'code'=> $projectCode,
            'project_id'=> $projectInfo->id,
            'fy'=> $projectInfo->fy_id,
            'budget_id'=> $projectInfo->budget_topic_id,
            'expenditure_id'=> $projectInfo->expenditure_topic_id,
            'implementing_id'=> $projectInfo->implementing_office_id,
            'monitoring_id'=> $projectInfo->monitoring_office_id,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);

        Procurement::create([
            'estimated_amount' => $request->get('estimated_amount'),
            'project_code' => $projectInfo->id,
            'status' => 1,
            'contingency' => $request->get('contingency'),
        ]);

        $description = logDescriptionCreate($request->all());
        storeLog($projectInfo->id, $description, 0, 'Project');

        session()->flash('store_success_info', '" project named ' . $request->get('name') . '"');
        session()->flash('next_project_code', getProjectCode($implementingOfficeId, $monitoringOfficeId, true));

        return redirect()->back()->withInput(Input::Except('name', 'name_eng', 'project_code'));
        //return redirect()->route('project.create');
    }

    public function edit(Project $project, ImplementingOffice $implementingoffice, Sector $sector, District $district, BudgetTopic $budgettopic, ProjectGroup $projectGroup)
    {
        restrictEngineers($this->user_info->type_flag);

        if ($project->cancelled != 0) abort(403);
        //yo fy ko running or last fy ko not running haru.
        seeDetail(clone ($this->project)->get()->merge(clone ($this->not_running_projects)->get()), $project->id);
        $this->pro_data['project_groups'] = $projectGroup->whereStatus(1)->where('level', '>', '1')
            ->whereHas('creator', function ($creator) {
                $creator->where('implementing_office_id', Auth::user()->implementing_office_id);
            });

        $this->pro_data['construction_located_area'] = add_my_array($this->user_info->ConstructionLocatedArea()->pluck('located_area', 'id'));

        $this->pro_data['project_groups'] = add_my_array($this->pro_data['project_groups']->pluck('name', 'id'));
        $this->pro_data['project'] = $project;
        $this->pro_data['divisions'] = add_my_array($division->whereStatus(1)->pluck('name_eng', 'id'));
        $this->pro_data['constructiontypes'] = add_my_array($this->user_info->ConstructionType()->whereStatus(1)->pluck('name', 'id'));

        $this->pro_data['sectors'] = add_my_array($sector->whereStatus(1)->pluck('name_eng', 'id'));
        $this->pro_data['districts'] = add_my_array($district->whereStatus(1)->pluck('name_eng', 'id'));

        $this->pro_data['trimesters'] = add_my_array(Trimester::pluck('name', 'id'));
        $this->pro_data['trimesters'] = add_my_array($this->pro_data['trimesters'], 'बार्षिक कार्यक्रम');
        $this->pro_data['fiscal_years'] = Fiscalyear::orderBy('id', 'desc')->pluck('fy', 'id');

        $this->pro_data['districts'] = add_my_array($district->whereStatus(1)->pluck('name_eng', 'id'));
        $this->pro_data['monitoring_offices'] = add_my_array($implementingoffice->whereStatus(1)->where('level', '>', 0)->where('IsMonitoring', '1')->pluck('name_eng', 'id'), 'Choose Office');
        if (Auth::user()->implementing_office_id != 1 && Auth::user()->implementingOffice->isMonitoring != 1) {
            $this->pro_data['districts'] = add_my_array($district->whereStatus(1)->whereHas('implementingOffice', function ($implementingOffice) {
                $implementingOffice->where('pro_implementing_offices.id', $this->user_info->implementing_office_id);
            })->pluck('name_eng', 'id'));
        }
        return view('admin.project.edit', $this->pro_data);
    }

    public function update(CreateProjectRequest $request, Project $project)
    {
        seeDetail($this->project, $project->id);

        $oldProject = $project->toArray();
        if ($this->user_info->implementingOffice->isMonitoring == 0) {
            $implementingOfficeId = $this->user_info->implementingOffice->id;
            $monitoringOfficeId = $request->get('monitoring_office_id');
        } else {
            $monitoringOfficeId = $this->user_info->implementingOffice->id;
            $implementingOfficeId = $request->get('implementing_office_id');
        }
        if ($this->user_info->access == 'Root Level') {
            $implementingOfficeId = $request->get('implementing_office_id');
            $monitoringOfficeId = $request->get('monitoring_office_id');
        }


        $status = $request->get('status') == 'on' ? 1 : 0;
        $liability = session()->get('pro_fiscal_year') > $request->get('start_fy_id') ? 1 : 0;
        $whose = $project->whose;
        if ($request->get('swamittwo') == 1) {
            $whose = $request->get('whose');
        }
        $shitnumber = $project->shitnumber;
        $kittanmber = $project->kittanumber;

        if ($request->get('swamittwo') == 2) {
            $shitnumber = $request->get('shitnumber');
            $kittanmber = $request->get('kittanumber');
        }
        $coordinates = $request->manual_coordinates ? $request->manual_coordinates : trim($request->get('coordinates'), '()');
        $construction_type_id = $request->get('construction_type_id') != 0 ? $request->get('construction_type_id') : $project->construction_type_id;
        $budget_topic_id = $request->get('budget_topic_id') != 0 ? $request->get('budget_topic_id') : $project->budget_topic_id;
        $project_group_id = $request->get('project_group_id') != 0 ? $request->get('project_group_id') : $project->project_group_id;
        $construction_located_area_id = $request->get('construction_located_area_id') != 0 ? $request->get('construction_located_area_id') : $project->construction_located_area_id;

        $data = [
            'old_project_code' => $monitoringOfficeId .':'. $project->project_code,
            'project_id' => $project->id,
            'fy_id' => $project->fy_id,
            'updated_by' => Auth()->id(),
        ];
        OldProjectCode::create($data);

        $project->fill([
            'name' => $request->get('name'),
            'name_eng' => $request->get('name_eng'),
//            'budget_topic_id' => $budget_topic_id,
//            'expenditure_topic_id' => $request->get('expenditure_topic_id'),
            'construction_type_id' => $construction_type_id,
            'address' => $request->get('address'),
            'coordinates' => $coordinates,
            'headquarter' => $request->get('headquarter'),
            'land_ownership' => 0,
            'swamittwo' => $request->get('swamittwo'),
            'soiltest' => $request->get('soiltest'),
            'braingcapacity' => $request->get('braingcapacity'),
            'bstype' => $request->get('bstype'),
            'rooftype' => $request->get('rooftype'),
            'doorwindow' => $request->get('doorwindow'),
            'wall_type' => $request->get('wall_type'),
//            'implementing_office_id' => $implementingOfficeId,
//            'monitoring_office_id' => $monitoringOfficeId,
            'project_group_id' => $project_group_id,
            'nature_of_project_id' => $request->get('nature_of_project_id'),
            'construction_located_area_id' => $construction_located_area_id,
            'unit' => $request->get('unit'),
            'quantity' => $request->get('quantity'),
            'design_type' => $request->get('design_type'),
//            'project_code' => $projectCode,
            'status' => $status,
            'fy_id' => $request->session()->get('pro_fiscal_year'),
            'district_id' => $request->get('district_id') == 0 ? null : $request->get('district_id'),
            'approved_date' => $request->get('approved_date'),
            'description' => $request->get('description'),
            'start_fy_id' => $request->get('start_fy_id'),
            'liability' => $liability,
            'pr_code' => $request->get('pr_code'),
            'story_area_unite' => $request->get('story_area_unite'),
            'whose' => $whose,
            'kittanumber' => $kittanmber,
            'baringcapacity' => $request->get('baringcapacity') ? $request->get('baringcapacity') : '-',
            'shitnumber' => $shitnumber,
        ])->save();
        $this->updateSetting($project);

        $change = logDescriptionUpdate($project, $oldProject);
        if ($change != false) {
            storeLog($project->id, $change, 1, 'Project');
        }

        if ($project->procurement) {
            $oldProcurement = $project->procurement->toArray();
            $project->procurement->fill([
                'estimated_amount' => $request->get('estimated_amount'),
                'con_est_amt_net' => $request->get('con_est_amt_net'),
                'design_est_swikrit_miti' => $request->get('design_est_swikrit_miti'),
                'est_approved_date' => $request->get('est_approved_date'),
                'contract_amount' => $request->get('contract_amount'),
                'contract_date' => $request->get('contract_date'),
                'completion_date' => $request->get('completion_date'),
                'contingency' => $request->get('contingency'),
            ])->save();
            $procurement = $project->procurement;

            $change = logDescriptionUpdate($procurement, $oldProcurement);
        }

        if ($change != false) {
            storeLog($project->id, $change, 1, 'Procurement');
        }
        session()->flash('update_success_info', '" project named ' . $request->get('name') . '"');
        $setting = $project->projectSettings()->where('fy', session()->get('pro_fiscal_year'))->first();
        if (!$setting) {
            $setting = $project;
        }
        return redirect()->route('searchProject', 'search=' . $setting->project_code);
    }

    public function uploadLog(Request $request, $block_id = null)
    {
        $implementing_office_id = null;
        $project_id = null;
        if ($request->has('implementing_office_id')) {
            $implementing_office_id = $request->get('implementing_office_id');
        } else {
            $project_id = $request->get('project_id');
        }

        $submitted_date = dateAD($request->get('date'));
        $activitylog = ActivityLog::create([
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'submitted_date' => $submitted_date,
            'implementing_office_id' => $implementing_office_id,
            'type' => $request->get('type'),
            'project_id' => $project_id,
            'created_by' => $this->user_info->id,
            'updated_by' => $this->user_info->id,
            'status' => 1,
            'block_id' => $block_id,
        ]);

        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $file) {
                $fileName = '';
                $fileName = getFileName($file);
                $mime = $file->getMimeType();
                if ($mime == 'image/jpeg' || $mime == 'image/jpeg' || $mime == 'image/gif' || $mime == 'image/gif' || $mime == 'image/png' || $mime == 'application/pdf' || $mime == "application/msword" || $mime == "image/vnd.dwg" || $mime == "image/vnd.dxf" || $mime =="text/plain" ) {
                    $file->move('public/activityFiles', $fileName);
                }

                ActivityLogFiles::create([
                    'activity_log' => $activitylog->id,
                    'file_path' => $fileName,
                ]);
            }

        }
        $description = logDescriptionCreate($request->all());
        storeLog($project_id ? $project_id : null, $description, 0, 'ActivityLog');
        session()->flash('store_success_info', '" Project Activity log."');
        return redirect()->back();
    }

    public function deleteActivityLog($id)
    {
        restrictToImplementingOffice('abort');
        $activityLog = ActivityLog::withTrashed()->find($id);
        foreach ($activityLog->ActivityLogFiles as $file) {
            if ($file->file_path) {
                if (file_exists('public/activityFiles/' . $file->file_path)) {
                    unlink('public/activityFiles/' . $file->file_path);
                }
            }
        }
        $activityLog->forceDelete();
        session()->flash('delete_success_info', '" Activity Log"');
        return redirect()->back();
    }

    public function deleteActivityLogFile($id)
    {
        restrictToImplementingOffice('abort');
        $file = ActivityLogFiles::find($id);
        if ($file) {
            if (file_exists('public/activityFiles/' . $file->file_path)) {
                unlink('public/activityFiles/' . $file->file_path);
            }
            $file->forceDelete();
        }
        return redirect()->back();
    }

    public function handover(Request $request)
    {
        $project_id = $request->project_id;
        $hod = $request->hod;
        $project = Project::findOrFail($project_id);
        $project->project_status = 2;
//        $project->ho_date = dateAD($hod);
        $project->ho_date = $hod;
        $project->completed_date = $request->wcd;
        $ho_fy = null;
        if (Fiscalyear::where('fy', getFy($hod))->first()) {
            $ho_fy = Fiscalyear::where('fy', getFy($hod))->first()->id;
        }
        if ($project->completed_date == null) {
            $project->completed_date = dateAD($hod);
            $project->completed_fy = $ho_fy;
        }
        $project->ho_fy = $ho_fy;
        $project->save();
        return json_encode([
            'status' => 'success'
        ], 200);

    }

    public function extendTime(CreateTimeExtensionRequest $request, Project $project, PushNotification $pushNotification, MessageService $messageService)
    {
        if (strtotime(dateAD($request->get('start_date'))) > strtotime(dateAD($request->get('end_date')))) {
            session()->flash('fail_info', '" Start Date cannot be greater than End Date"');
            return redirect()->back()->withInput();
        }
        $fileName = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = getFileName($file);
            $mime = $file->getMimeType();
            if ($mime == 'image/jpeg' || $mime == 'image/pjpeg' || $mime == 'image/gif' || $mime == 'image/gif' || $mime == 'image/png' || $mime == 'application/pdf') {
                $file->move('public/activityFiles', $fileName);
            }
        }

        TimeExtension::create([
            'start_date' => $request->get('start_date'),
            'verified_from' => $request->get('verified_from'),
            'end_date' => $request->get('end_date'),
            'remarks' => $request->get('remarks'),
            'extended_on' => $request->get('extended_on'),
            'liquidated_damage' => $request->get('liquidated_damage'),
            'project_code' => $project->id,
            'file' => $fileName,
            'status' => 1,
        ])->save();


        /*        $startDate = new DateTime(date('Y-m-d'));
                $endDate = new DateTime(dateAD($request->input('end_date')));
                $duration = $startDate->diff($endDate)->d;*/

        $notice = Notice::firstOrCreate([
            'name' => 'Deadline',
            'description' => "Project Time period of " . $project->name . " is extended till " . $request->input('end_date'),
            'created_by' => 4,
            'updated_by' => 4,
        ]);

        $notification = NotificationType::whereType('timeExtension')->first();
        $jobInitiator = new JobInitiator($notification, "TimeExtension");
        $jobInitiator->putOnQueue($notice, $project);

        $description = logDescriptionCreate($request->all());
        storeLog($project->id, $description, 0, 'Time Extension');
        session()->flash('update_success_info', '" project named ' . $project->name . ' Extended"');
        return redirect()->back();
    }

    public function addVariation(CreateVariationRequest $request, Project $project)
    {
        if ($project->procurement->contract_amount == 0) {
            session()->flash('fail_info', '" Variation cannot be added on NULL contract amount"');
            return redirect()->back()->withInput();
        }

        if ($request->get('status') == 1 && $request->get('amount') < 0) {
            session()->flash('fail_info', '" Price Escalation cannot have negative amount"');
            return redirect()->back()->withInput();
        }
        $fileName = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = getFileName($file);
            $mime = $file->getMimeType();
            if ($mime == 'image/jpeg' || $mime == 'image/pjpeg' || $mime == 'image/gif' || $mime == 'image/gif' || $mime == 'image/png' || $mime == 'application/pdf') {
                $file->move('public/activityFiles', $fileName);
            }
        }

        Variation::create([
            'amount' => $request->get('amount'),
            'verified_from' => $request->get('verified_from'),
            'vope_date' => $request->get('vope_date'),
            'remarks' => $request->get('remarks'),
            'status' => $request->get('status'),
            'project_code' => $project->id,
            'file' => $fileName,
        ])->save();


        $description = logDescriptionCreate($request->all());
        storeLog($project->id, $description, 0, 'Variation');

        session()->flash('update_success_info', '" project named ' . $project->name . ' added a variation"');
        return redirect()->back();
    }

    public function addLiquidation(Request $request, Project $project)
    {
        restrictToImplementingOffice('abort');
        $fileName = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = getFileName($file);
            $mime = $file->getMimeType();
            if ($mime == 'image/jpeg' || $mime == 'image/pjpeg' || $mime == 'image/gif' || $mime == 'image/gif' || $mime == 'image/png' || $mime == 'application/pdf') {
                $file->move('public/activityFiles', $fileName);
            }
        }

        Liquidation::create([
            'amount' => $request->get('amount'),
            'collected_date' => $request->get('collected_date'),
            'remarks' => $request->get('remarks'),
            'status' => 1,
            'project_code' => $project->id,
            'file' => $fileName,
        ])->save();

        $description = logDescriptionCreate($request->all());
        storeLog($project->id, $description, 0, 'Liquidation');

        session()->flash('update_success_info', '" project named ' . $project->name . ' added a Liquidation"');
        return redirect()->back();
    }

    public function changeProjectStatus($project)
    {
        $oldProject = $project->toArray();
        if ($project->project_status == 0) {
            $project->project_status = 1;
        } elseif ($project->project_status == 1) {
            $project->project_status = 2;
        } else {
            $project->project_status = 0;
        }
        $project->fill([
            'project_status' => $project->project_status,
        ])->save();

        $change = logDescriptionUpdate($project, $oldProject);
        if ($change != false) {
            storeLog($project->id, $change, 1, 'Project');
        }

        session()->flash('update_success_info', '" project named ' . $project->name . ' status changed"');
        return redirect()->back();
    }

    public function destroy(Project $project)
    {

        $name = $project->name;
        if (Input::get('hardDelete')) {
            $project->forceDelete();
        } else {
            $project->delete();
        }
        storeLog($project->id, $project->name, 2, 'Project');
        session()->flash('delete_success_info', '" project named ' . $name . '"');
        return redirect()->route('project.index');
    }


    public function updateActivityLog(Request $request)
    {
        $activityLog = ActivityLog::find($request->paper_id);
        if (!allowEdit($activityLog)) {
            abort(403);
        }
        $activityLog->fill([
            'title' => $request->get('title'),
            'description' => $request->get('paperremarks'),
            'submitted_date' => dateAD($request->get('dateUpdate')),
            'type' => $request->get('type'),
        ])->save();
        session()->flash('update_success_info', '" Project Activity log."');
        return redirect()->back();
    }




//    Daily progress user



    public function updateSetting($project)
    {
        //project's dependencies may change each fiscal year (budget topic, expenditure, implementing, monitoring and project code)
        $logged_in_fy = session()->get('pro_fiscal_year');
        $project_setting = $project->projectSettings()->where('fy', $logged_in_fy)->first();
        $implementingOfficeId = request()->get('implementing_office_id');
        $monitoringOfficeId = request()->get('monitoring_office_id');
        $monitorin_office = ImplementingOffice::find($monitoringOfficeId);

        if ($monitorin_office->isAyojanaType) {
            $projectCode = request()->get('monitoring_office_id') . '-' . request()->get('project_code');
        } else {
            $projectCode = request()->get('monitoring_office_id') . ':' . request()->get('project_code');
        }

        $data = [
            'fy' => $logged_in_fy,
            'budget_id' => request()->get('budget_topic_id'),
            'expenditure_id' => request()->get('expenditure_topic_id'),
            'monitoring_id' => request()->get('monitoring_office_id'),
            'implementing_id' => request()->get('implementing_office_id'),
            'code' => $projectCode,
            'updated_by' => request()->user()->id,
        ];
        if (!$project_setting) {
            $data['created_by'] = request()->user()->id;
            $project->projectSettings()->create($data);
        } else
            $project_setting->fill($data)->save();
    }


    public function blockDetail( $project_id, $block_id){
        $this->pro_data['project'] = Project::findorfail($project_id);
        $this->pro_data['block'] = $this->projectBlocks->findOrFail($block_id);
        return view('admin.project.block-detail', $this->pro_data);
    }

    public function blockUpdate(Request $request, $block_id){
        $block_name = $request->block_name;
        $block = ProjectBlocks::findOrfail($block_id);
        $block->fill([
           'block_name' => $block_name,
           'updated_by' => $this->user_info->id,
           'updated_at' => now()
        ])->save();
        session()->flash('update_success_info', 'Block Detail');
        return redirect()->back();
    }

    public function blockDelete($block_id){
        $block = ProjectBlocks::findOrfail($block_id)->delete();
        session()->flash('delete_success_info', 'Block');
        return redirect()->back();
    }

    public function blockDetailStore(Request $request, $project_id, $block_id){
        $block = $this->projectBlocks->findOrFail($block_id);
        $block->fill($request->all());
        $block->fill([
            'updated_by' => $this->user_info->id,
            'updated_at' => now()
        ])->save();
        session()->flash('store_success_info', 'Block Detail');
        return redirect()->back();
    }

    public function storeBlock(Request $request, $project_id){
        $block = ProjectBlocks::create([
            'project_id' => $project_id,
            'block_name' => $request->block_name,
            'created_by' => $this->user_info->id,
            'updated_by' => $this->user_info->id,
        ]);
        session()->flash('store_success_info', '""');
        return redirect()->back();
    }

}