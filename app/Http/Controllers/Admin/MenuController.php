<?php
namespace PMIS\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use PMIS\Http\Controllers\AdminBaseController;
use PMIS\ProjectGroup;

class MenuController extends AdminBaseController {
    protected $pro_data;
    protected $session;
    protected $menu;
    public function __construct(ProjectGroup $projectGroup){
        parent::__construct();
        $this->projectGroup = $projectGroup;
    }
    public function index(){
        $this->pro_data['orderBy'] = 'id';
        $this->pro_data['order'] = 'asc';
        $this->pro_data['other_data'] = '';
        $this->pro_data['default_search'] = '';
        $this->pro_data['projectGroups'] = $this->projectGroup;
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
        }

        if(isset($_GET['trashes'])){
            $this->pro_data['projectGroups'] = $this->pro_data['projectGroups']->onlyTrashed();
        }
        $this->pro_data['trashes_no'] = $this->projectGroup->onlyTrashed()->count();
        $this->pro_data['projectGroups'] = $this->pro_data['projectGroups']->orderBy($this->pro_data['orderBy'],$this->pro_data['order'])->paginate(10);
        return view('admin.projectGroup.index',$this->pro_data);
    }

    public function create(){
        return view('admin.projectGroup.create', $this->pro_data);
    }

    public function store(Request $request){
        $status = $request->get('status') == 'on'?1:0;
        $this->projectGroup->create([
            'name'=>$request->get('name'),
            'name_eng'=>$request->get('name_eng'),
            'status'=>$status
        ]);
        $description=logDescriptionCreate($request->all());
        storeLog(null,$description,0 ,'Project Group');

        session()->flash('store_success_info','" construction type named '.$request->get('name').'"');
        return redirect()->route('projectGroup.create');
    }

    public function show(){

    }

    public function edit(ProjectGroup $projectGroup){
        $this->pro_data['projectGroup'] = $projectGroup;
        return view('admin.projectGroup.edit', $this->pro_data);
    }

    public function update(Request $request, ProjectGroup $projectGroup){
        $status = $request->get('status') == 'on'?1:0;

        $oldProjectGroup=$projectGroup->toArray();

        $projectGroup->fill([
            'name'=>$request->get('name'),
            'name_eng'=>$request->get('name_eng'),
            'status'=>$status
        ])->save();


        $change=logDescriptionUpdate($projectGroup, $oldProjectGroup);
        if($change!=false){
            storeLog(null,$change,1 ,'Project Group');
        }

        session()->flash('update_success_info','" construction type named '.$request->get('name').'"');
        return redirect()->route('projectGroup.index');
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
        return redirect()->route('projectGroup.index');
    }
}
