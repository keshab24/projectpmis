<?php
namespace PMIS\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\URL;
use PMIS\BudgetTopic;
use PMIS\Contractor;
use PMIS\District;
use PMIS\Division;
use PMIS\ExpenditureTopic;
use PMIS\Fiscalyear;
use PMIS\Http\Controllers\AdminBaseController;
use PMIS\ImplementingMode;
use PMIS\ImplementingOffice;
use PMIS\JointVenture;
use PMIS\Procurement;
use PMIS\Project;

class ProcurementController extends AdminBaseController {
    protected $pro_data;
    protected $procurement;
    protected $project;

    public function __construct(Procurement $procurement, Project $project,BudgetTopic $budgettopic, ExpenditureTopic $expendituretopic,ImplementingOffice $implementingOffice){
        parent::__construct();
        $this->middleware(function ($request, $next) use ($procurement,$implementingOffice,$project,$budgettopic,$expendituretopic ) {
            restrictEngineers($this->user_info->type_flag);
            $this->procurement = $procurement;
            $this->project = $project;
            $this->pro_data['limits']=array(1=>1,10=>10,15=>15,20=>20,30=>30,50=>50,100=>100,200=>200,250=>250,500=>500);
            $this->pro_data['implementing_offices'] = add_my_array($implementingOffice->whereStatus(1)->pluck('name_eng','id'));
            $this->pro_data['implementing_offices_new_update'] = $implementingOffice->whereStatus(1)->with('child')->get();
            if(Auth::user()->implementing_office_id!=1){
                if(Auth::user()->implementingOffice->isMonitoring==1){
                    $this->pro_data['implementing_offices']=add_my_array(Auth::user()->implementingOffice->MonitorSeesImplementing->pluck('name_eng','id'),'Choose Office');
                    $this->pro_data['implementing_offices_new_update'] = Auth::user()->visibleImplementingOffices()->with('child')->get();
                }else{
                    $this->pro_data['implementing_offices']=Auth::user()->implementingOffice()->pluck('name_eng','id');
                    $this->pro_data['implementing_offices_new_update'] = Auth::user()->implementingOffice()->with('child')->get();
                }

                $this->project = $project->where('monitoring_office_id',Auth::user()->implementing_office_id);

                $this->procurement = $procurement->whereHas('project',function($project){
                    $project->where('monitoring_office_id',Auth::user()->implementing_office_id);
                });



                if($this->procurement->count() == 0){
                    $project->where('implementing_office_id',Auth::user()->implementing_office_id);
                }

                if($this->project->count()==0){
                    $this->project = $project->where('implementing_office_id',Auth::user()->implementing_office_id);
                }
            }

            $this->project=$this->project->where('show_on_running','1')->where('cancelled', 0);

            $this->project=$this->project->join('pro_project_settings', 'pro_project_settings.project_id', '=', 'pro_projects.id')->where('fy', session()->get('pro_fiscal_year'))->select('pro_projects.*','pro_project_settings.id as setting_id','pro_project_settings.budget_id','pro_project_settings.budget_id','pro_project_settings.fy','pro_project_settings.budget_id','pro_project_settings.expenditure_id','pro_project_settings.implementing_id','pro_project_settings.monitoring_id');

            $budget_topic=$budgettopic->where('monitoring_office_id',Auth::user()->implementing_office_id);
            if($budget_topic->count()==0){
                $monitoringOffices=Auth::user()->implementingOffice->implementingSeesMonitor->pluck('id');
                $budget_topic=$budgettopic->whereIn('monitoring_office_id',$monitoringOffices);
            }
            $this->pro_data['budgettopics'] = $budget_topic->whereStatus(1)->pluck('budget_topic_num','id');

            $this->pro_data['expendituretopics'] = add_my_array($expendituretopic->whereStatus(1)->pluck('expenditure_topic_num','id'));
            return $next($request);
        });

    }

    public function index(ImplementingMode $implementingMode, ImplementingOffice $implementingOffice, Division $division, Contractor $contractor,JointVenture $jointVenture,Fiscalyear $fiscalyear){
        $this->commonSearchData();
        $contractors=$contractor->select('name','address','id')->whereStatus(1)->get();


        $contractorLists=array();
        foreach ($contractors as $contractor){
            $contractorLists[$contractor->id]=$contractor->name.', '.$contractor->address;
        }

        $this->pro_data['contractors']=collect($contractorLists);

        $this->pro_data['contractors']->prepend('None',0);
        $this->pro_data['joinVentures']=$jointVenture->whereStatus(1)->pluck('name','id');
        $this->pro_data['joinVentures']->prepend('None',0);
        $this->pro_data['contractors_edit']=$contractor->whereStatus(1)->pluck('name','id');
        $this->pro_data['projects'] = $this->project->whereStatus(1); //whereFy_id(session()->get('pro_fiscal_year'))
        $this->pro_data['totalProjects'] = count($this->project->where('project_status', '!=',2)->get());


        $this->pro_data['implementing_modes'] = $implementingMode->whereStatus(1)->pluck('name_eng','id');
        $this->pro_data['implementing_office_id'] = '';

        $this->pro_data['division_codes'] = add_my_array($division->whereStatus(1)->pluck('name_eng','id'));
        $this->pro_data['division_code'] = '';

//        if(isset($_GET['implementing_office_id'])){
//            if($_GET['implementing_office_id'] != 0){
//                $this->pro_data['implementing_office_id'] = $_GET['implementing_office_id'];
//                $this->pro_data['projects'] = $this->pro_data['projects']->whereImplementing_office_id($this->pro_data['implementing_office_id']);
//                if(isset($_GET['division_code'])){
//                    if($_GET['division_code'] != 0){
//                        $this->pro_data['division_code'] = $_GET['division_code'];
//                        $this->pro_data['projects'] = $this->pro_data['projects']->whereDivision_code($this->pro_data['division_code']);
//                    }
//                }
//            }
//        }

        $this->pro_data['fiscal_years'] = add_my_array($fiscalyear->orderBy('fy','asc')->pluck('fy','id'));
        $this->pro_data['count']=$this->pro_data['projects']->count();
        $this->pro_data['count']=$this->pro_data['projects']->count();
        $this->pro_data['projects'] = $this->pro_data['projects']->orderBy('id', 'asc')->simplePaginate($this->pro_data['limit']);

        return view('admin.procurement.index', $this->pro_data);
    }

    public function search(Contractor $contractor,JointVenture $jointVenture,ImplementingMode $implementingMode,Fiscalyear $fiscalyear)
    {
        $this->pro_data['fiscal_years'] = add_my_array($fiscalyear->orderBy('fy','asc')->pluck('fy','id'));
        $this->pro_data['contractors']=$contractor->whereStatus(1)->pluck('name','id');
        $this->pro_data['contractors']->prepend('None',0);
        $this->pro_data['joinVentures']=$jointVenture->whereStatus(1)->pluck('name','id');
        $this->pro_data['joinVentures']->prepend('None',0);
        $this->pro_data['default_search'] = $_GET['search'];
        $this->pro_data['other_data'] = '&search='.$_GET['search'];
        $implementing_office=isset($_GET['implementing_office_id'])?$_GET['implementing_office_id']:0;
        $budgettopic=isset($_GET['budgettopic'])?$_GET['budgettopic']:0;
        $expendituretopic=isset($_GET['expendituretopic'])?$_GET['expendituretopic']:0;
        $fiscalyear=isset($_GET['fiscal_year'])?$_GET['fiscal_year']:0;
        $this->pro_data['limit']=isset($_GET['limit'])?$_GET['limit']:50;
        $this->pro_data['implementing_modes'] = $implementingMode->whereStatus(1)->pluck('name_eng','id');

        $this->pro_data['projects'] = $this->project->whereStatus(1); //whereFy_id(session()->get('pro_fiscal_year'))

        if($expendituretopic != 0 ){
            //first filtered by fy then related field (on pro_project_settings table)
//            $this->pro_data['projects']=$this->pro_data['projects']->whereExpenditureTopicId($expendituretopic);
            $this->pro_data['projects']=$this->pro_data['projects']->where('expenditure_id', $expendituretopic);
            $this->pro_data['expendituretopic']=$_GET['expendituretopic'];
        }
        if($fiscalyear != 0 ){
            $this->pro_data['projects']=$this->pro_data['projects']->where('start_fy_id',$fiscalyear);
            $this->pro_data['fiscal_year']=$_GET['fiscal_year'];
        }

        if($budgettopic != 0 ){
            //first filtered by fy then related field (on pro_project_settings table)
//            $this->pro_data['projects']=$this->pro_data['projects']->whereBudgetTopicId($budgettopic);
            $this->pro_data['projects'] = $this->pro_data['projects']->where('budget_id', $budgettopic);
            $this->pro_data['budgettopic']=$_GET['budgettopic'];
        }
        if($implementing_office != 0 ){
            $this->pro_data['projects']=$this->pro_data['projects']->where('implementing_id', $implementing_office);
            $this->pro_data['implementing_office']=$_GET['implementing_office_id'];
        }
        $this->pro_data['projects']= $this->pro_data['projects']->search($_GET['search']);
        $this->pro_data['totalProjects'] = count($this->pro_data['projects']->get());

//        $this->pro_data['projects']= $this->pro_data['projects']->simplePaginate($this->pro_data['limit']);
        $this->pro_data['projects'] = $this->paginateCollection($this->pro_data['projects']->get(), $this->pro_data['limit']);

        $this->pro_data['count']=$this->pro_data['projects']->count();
        $this->pro_data['not_found']=true;
        if($this->pro_data['count']==0){
            $this->pro_data['count']=false;
        }

        return view('admin.procurement.index', $this->pro_data);
    }

    public function store(Request $request){
        $procurement=Procurement::create([
            'total_liability'=>$request->get('total_liability'),
            'estimated_amount'=>$request->get('estimated_amount'),
            'contract_amount'=>$request->get('contract_amount'),
            'contract_date'=>$request->get('contract_date'),
            'completion_date'=>$request->get('completion_date'),
            'project_code'=>$request->get('project_code'),
            'implementing_mode_id'=>$request->get('implementing_mode_id'),
            'status'=>1
        ]);

        session()->flash('store_success_info','" procurement with project code '.$request->get('project_code').'"');
        return redirect()->route('procurement.index');
    }

    public function edit(Procurement $procurement,Contractor $contractor, JointVenture $jointVenture){
        $this->pro_data['contractors']=$contractor->whereStatus(1)->pluck('name','id');
        $this->pro_data['contractors']->prepend('None',0);
        $this->pro_data['joinVentures']=$jointVenture->whereStatus(1)->pluck('name','id');
        $this->pro_data['joinVentures']->prepend('None',0);

        $this->pro_data['procurement']=$procurement;
        return view('admin.procurement.edit', $this->pro_data);
    }

    public function show(){
    }

    public function update(Request $request){
        foreach($request->get('project_code') as $index => $project){
            $contractor=null;
            $joinVenture=null;

            if($request->get('jv_selector')[$index]==1 && $request->get('joint_venture')[$index]!=0){
                $joinVenture=$request->get('joint_venture')[$index];
            }elseif($request->get('jv_selector')[$index]==0 && $request->get('contractors')[$index]!=0){
                $contractor=$request->get('contractors')[$index];
            }

            $procurement = Procurement::whereProjectCode($project)->first();
            $oldProcurement = $procurement->toArray();

            $procurement->fill([
                'contract_amount'=>$request->get('contract_amount')[$index],
                'total_liability'=>$request->get('total_liability')[$index],
                'contract_date'=>$request->get('contract_date')[$index],
                'completion_date'=>$request->get('completion_date')[$index],
                'implementing_mode_id'=>$request->get('implementing_mode_id')[$index],
                'status'=>1,
                'con_est_amt_net'=>$request->get('con_est_amt_net')[$index],
                'est_approved_date'=>$request->get('est_approved_date')[$index],
                'method'=>$request->get('method')[$index],
                'bid_does_ready_est'=>$request->get('bid_does_ready_est')[$index],
                'bid_does_ready_act'=>$request->get('bid_does_ready_act')[$index],
                'no_obj_est1'=>$request->get('no_obj_est1')[$index],
                'no_obj_act1'=>$request->get('no_obj_act1')[$index],
                'call_for_bid_est'=>$request->get('call_for_bid_est')[$index],
                'call_for_bid_act'=>$request->get('call_for_bid_act')[$index],
                'bid_open_est'=>$request->get('bid_open_est')[$index],
                'bid_open_act'=>$request->get('bid_open_act')[$index],
                'bid_eval_est'=>$request->get('bid_eval_est')[$index],
                'bid_eval_act'=>$request->get('bid_eval_act')[$index],
                'no_obj_est2'=>$request->get('no_obj_est2')[$index],
                'no_obj_act2'=>$request->get('no_obj_act2')[$index],
                'con_sign_est'=>$request->get('con_sign_est')[$index],
                'con_end_est'=>$request->get('con_end_est')[$index],
                'con_id_div'=>$request->get('con_id_div')[$index],
                'con_id_web'=>$request->get('con_id_web')[$index],
                'remarks'=>$request->get('remarks')[$index],
                'verified'=>$request->get('verified')[$index],
                'design_est_swikrit_miti'=>$request->get('design_est_swikrit_miti')[$index],
                'bolapatraswikriti'=>$request->get('bolapatraswikriti')[$index],
                'wo_date'=>$request->get('wo_date')[$index],
                'joint_venture'=>$joinVenture,
                'contractor'=>$contractor,
                'contingency'=>$request->get('contingency')[$index],
            ])->save();

            $change=logDescriptionUpdate($procurement, $oldProcurement);
            if($change!=false){
                storeLog($procurement->project->id,$change,1 ,'Prourement');
            }
        }
        session()->flash('update_success_info','" Procurements updated "');
        if (strpos(URL::previous(), '?search') !== false) {
            return redirect()->route('searchProject',strstr(URL::previous(), 'search='));
        }else{
            return redirect()->to($_SERVER['HTTP_REFERER']);
        }

    }

    public function add_pivot($input, $procurement_given){
        if($input){
            $procurements = explode(',',$input);
            $contractor_saving = [];
            foreach($procurements as $procurement){
                $procurement = trim($procurement);
                if($procurement != ''){
                    $selectContractor = Contractor::whereName($procurement)->first();
                    if($selectContractor){
                        $contractor_saving[] = $selectContractor->id;
                    }
                }
            }
            $procurement_given->contractors()->sync($contractor_saving);
        }else{
            $procurement_given->contractors()->detach();
        }
    }
}