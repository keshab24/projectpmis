<?php
namespace PMIS\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use PMIS\Http\Controllers\AdminBaseController;
use PMIS\Region;
use PMIS\State;

class StateController extends AdminBaseController {
    protected $pro_data;
    protected $state;
    public function __construct(State $state){
        parent::__construct();
        $this->middleware(function ($request, $next) {
            restrictToImplementingOffice('abort');
            return $next($request);
        });
        $this->state = $state;
    }
    public function index(){
        $this->pro_data['orderBy'] = 'id';
        $this->pro_data['order'] = 'asc';
        $this->pro_data['other_data'] = '';
        $this->pro_data['default_search'] = '';
        if(isset($_GET['search'])){
            $this->pro_data['default_search'] = $_GET['search'];
            $this->pro_data['states'] = $this->state->search($_GET['search']);
            $this->pro_data['other_data'] = '&search='.$_GET['search'];
            if($this->pro_data['states']->get()->isEmpty()){
                $this->pro_data['not_found']= 'Sorry! could not find your content. Please try with another keywords.';
            }
        }else{
            $this->pro_data['states'] = $this->state;
        }
        if(isset($_GET['orderBy']) && isset($_GET['order'])){
            $this->pro_data['orderBy'] = $_GET['orderBy'];
            $this->pro_data['order'] = (strtolower($_GET['order'])=='asc')?'desc':'asc';
        }

        if(isset($_GET['trashes'])){
            $this->pro_data['states'] = $this->pro_data['states']->onlyTrashed();
        }
        $this->pro_data['trashes_no'] = $this->state->onlyTrashed()->count();
        $this->pro_data['states'] = $this->pro_data['states']->orderBy($this->pro_data['orderBy'],$this->pro_data['order'])->paginate(10);
        return view('admin.state.index',$this->pro_data);
    }

    public function create(){
        return view('admin.state.create', $this->pro_data);
    }

    public function store(Request $request){
        $status = $request->get('status') == 'on'?1:0;
        $this->state->create([
           'name'=>$request->get('name'),
           'name_eng'=>$request->get('name_eng'),
           'description'=>$request->get('description'),
           'description_eng'=>$request->get('description_eng'),
           'state_number'=>$request->get('state_number'),
           'coordinates'=>$request->get('coordinates'),
           'status'=>$status
        ]);
        $description=logDescriptionCreate($request->all());
        storeLog(null,$description,0 ,'State');

        session()->flash('store_success_info','" state name '.$request->get('name').'"');
        return redirect()->route('state.create');
    }

    public function show(){

    }

    public function edit(State $state){
        $this->pro_data['state'] = $state;
        return view('admin.state.edit', $this->pro_data);
    }

    public function update(Request $request, State $state){
        $status = $request->get('status') == 'on'?1:0;
        $oldState=$state->toArray();

        $state->fill([
            'name'=>$request->get('name'),
            'name_eng'=>$request->get('name_eng'),
            'description'=>$request->get('description'),
            'description_eng'=>$request->get('description_eng'),
            'coordinates'=>$request->get('coordinates'),
            'state_number'=>$request->get('state_number'),
            'status'=>$status
        ])->save();
        $change=logDescriptionUpdate($state, $oldState);
        if($change!=false){
            storeLog(null,$change,1 ,'State');
        }

        session()->flash('update_success_info','" state named '.$request->get('name').'"');
        return redirect()->route('state.index');
    }

    public function destroy(State $state){
        $name = $state->name;
        if(Input::get('hardDelete')){
            $state->forceDelete();
        }else{
            $state->delete();
        }
        storeLog(null,$name,2 ,'State');

        session()->flash('delete_success_info','" state named '.$name.'"');
        return redirect()->route('state.index');
    }
}
