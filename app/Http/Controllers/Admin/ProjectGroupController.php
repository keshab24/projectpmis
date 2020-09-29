<?php
namespace PMIS\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use PMIS\BudgetTopic;
use PMIS\Http\Controllers\AdminBaseController;
use PMIS\ProjectGroup;

class ProjectGroupController extends AdminBaseController {
    protected $pro_data;
    protected $session;
    protected $menu;
    public function __construct(ProjectGroup $projectGroup,BudgetTopic $budgetTopic){
        parent::__construct();
        $this->projectGroup = $projectGroup;
        $this->middleware(function ($request, $next) use ($projectGroup, $budgetTopic){
            restrictToImplementingOffice('abort');
            if(Auth::user()->implementing_office_id!=1){
                $this->projectGroup = $projectGroup->where('monitoring_office_id',$this->user_info->implementing_office_id);
            }
            $budget_topic=$budgetTopic->where('monitoring_office_id',Auth::user()->implementing_office_id);
            if($budget_topic->count()==0){
                $monitoringOffices=Auth::user()->implementingOffice->implementingSeesMonitor->pluck('id');
//                $budget_topic=$budgetTopic->whereIn('monitoring_office_id',$monitoringOffices);
            }
            $this->pro_data['budgettopics'] = $budget_topic->whereStatus(1)->pluck('budget_topic_num','id');
            return $next($request);
        });


    }
    public function index(ProjectGroup $projectGroup){
        $this->pro_data['orderBy'] = 'id';
        $this->pro_data['order'] = 'asc';
        $this->pro_data['other_data'] = '';
        $this->pro_data['default_search'] = '';
        $this->pro_data['projectGroups'] = $this->projectGroup->where('level','>','0')->where('slug','!=','root');
        if(isset($_GET['search'])){
            $this->pro_data['default_search'] = $_GET['search'];
            $this->pro_data['projectGroups'] = $this->pro_data['projectGroups']->search($_GET['search']);
            $this->pro_data['other_data'] = '&search='.$_GET['search'];
            if($this->pro_data['projectGroups']->get()->isEmpty()){
                $this->pro_data['not_found']= 'Sorry! could not find your content. Please try with another keywords.';
            }
        }
        if(isset($_GET['orderBy']) && isset($_GET['order'])){
            $this->pro_data['orderBy'] = $_GET['orderBy'];
            $this->pro_data['order'] = (strtolower($_GET['order'])=='asc')?'desc':'asc';
        }else{
            $this->pro_data['orderBy'] = 'group_category_id';
            $this->pro_data['order'] = 'asc';
        }

        if(isset($_GET['trashes'])){
            $this->pro_data['projectGroups'] = $projectGroup->onlyTrashed();
        }
        $this->pro_data['trashes_no'] = $projectGroup->onlyTrashed()->count();
        $this->pro_data['projectGroups'] = $this->pro_data['projectGroups']->orderBy($this->pro_data['orderBy'],$this->pro_data['order'])->simplePaginate(10);
        return view('admin.projectGroup.index',$this->pro_data);
    }

    public function create(){
        $this->pro_data['project_groups'] = $this->projectGroup->whereLevel(1)->where('slug','!=','root')->get();
        return view('admin.projectGroup.create', $this->pro_data);
    }

    public function store(Request $request){
        $status = $request->get('status') == 'on'?1:0;
        ProjectGroup::create([
            'name'=>$request->get('name'),
            'name_nep'=>$request->get('name_nep'),
            'description'=>$request->get('description'),
            'description_nep'=>$request->get('description_nep'),
            'level'=>$request->get('level'),
            'order'=>$request->get('order'),
            'budget_topic'=>$request->get('budget_topic'),
            'group_category_id'=>$request->get('group_category_id'),
            'monitoring_office_id'=>Auth::user()->implementing_office_id,
            'created_by'=>$this->user_info->id,
            'updated_by'=>$this->user_info->id,
            'status'=>$status
        ]);

        $description=logDescriptionCreate($request->all());
        storeLog(null,$description,0 ,'Project Group');

        session()->flash('store_success_info','" construction type named '.$request->get('name').'"');
        return redirect()->route('project-group.create');
    }

    public function show(){

    }

    public function edit(ProjectGroup $projectGroup){
        $this->pro_data['projectGroup'] = $projectGroup;
        $this->pro_data['project_groups'] = $this->projectGroup->whereLevel(1)->where('slug','!=','root')->get();
        return view('admin.projectGroup.edit', $this->pro_data);
    }

    public function update(Request $request, ProjectGroup $projectGroup){
        $status = $request->get('status') == 'on'?1:0;
        $oldProjectGroup=$projectGroup->toArray();

        $projectGroup->fill([
            'name'=>$request->get('name'),
            'name_nep'=>$request->get('name_nep'),
            'description'=>$request->get('description'),
            'description_nep'=>$request->get('description_nep'),
            'level'=>$request->get('level'),
            'order'=>$request->get('order'),
            'group_category_id'=>$request->get('group_category_id'),
            'budget_topic'=>$request->get('budget_topic'),
            'created_by'=>$this->user_info->id,
            'updated_by'=>$this->user_info->id,
            'status'=>$status
        ])->save();
        $change=logDescriptionUpdate($projectGroup, $oldProjectGroup);
        if($change!=false){
            storeLog(null,$change,1 ,'Project Group');
        }

        session()->flash('update_success_info','" Project Group type named '.$request->get('name').'"');
        return redirect()->route('project-group.index');
    }

    public function destroy(ProjectGroup $projectGroup){
        $name = $projectGroup->name;
        if(Input::get('hardDelete')){
            $projectGroup->forceDelete();
        }else{
            $projectGroup->delete();
        }
        storeLog(null,$name,2 ,'Project Group');

        session()->flash('delete_success_info','" construction type named '.$name.'"');
        return redirect()->route('project-group.index');
    }
}
