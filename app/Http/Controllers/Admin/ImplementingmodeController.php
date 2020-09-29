<?php
namespace PMIS\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use PMIS\Http\Controllers\AdminBaseController;
use PMIS\ImplementingMode;

class ImplementingmodeController extends AdminBaseController {
    protected $pro_data;
    protected $implementingmode;
    public function __construct(Implementingmode $implementingmode){
        parent::__construct();
        $this->middleware(function ($request, $next) {
            restrictToImplementingOffice('abort');
            return $next($request);
        });
        $this->implementingmode = $implementingmode;
    }
    public function index(){
        $this->pro_data['orderBy'] = 'id';
        $this->pro_data['order'] = 'asc';
        $this->pro_data['other_data'] = '';
        $this->pro_data['default_search'] = '';
        $this->pro_data['implementingmodes'] = $this->implementingmode;
        if(isset($_GET['search'])){
            $this->pro_data['default_search'] = $_GET['search'];
            $this->pro_data['implementingmodes'] = $this->pro_data['implementingmodes']->search($_GET['search']);
            $this->pro_data['other_data'] = '&search='.$_GET['search'];
            if($this->pro_data['implementingmodes']->get()->isEmpty()){
                $this->pro_data['not_found']= 'Sorry! could not find your content. Please try with another keywords.';
            }
        }
        if(isset($_GET['orderBy']) && isset($_GET['order'])){
            $this->pro_data['orderBy'] = $_GET['orderBy'];
            $this->pro_data['order'] = (strtolower($_GET['order'])=='asc')?'desc':'asc';
        }

        if(isset($_GET['trashes'])){
            $this->pro_data['implementingmodes'] = $this->pro_data['implementingmodes']->onlyTrashed();
        }
        $this->pro_data['trashes_no'] = $this->implementingmode->onlyTrashed()->count();
        $this->pro_data['implementingmodes'] = $this->pro_data['implementingmodes']->orderBy($this->pro_data['orderBy'],$this->pro_data['order'])->simplePaginate(10);
        return view('admin.implementingmode.index',$this->pro_data);
    }

    public function create(){
        return view('admin.implementingmode.create', $this->pro_data);
    }

    public function store(Request $request){
        $status = $request->get('status') == 'on'?1:0;
        $this->implementingmode->create([
            'name'=>$request->get('name'),
            'name_eng'=>$request->get('name_eng'),
            'status'=>$status
        ]);
        $description=logDescriptionCreate($request->all());
        storeLog(null,$description,0 ,'Implementing Mode');

        session()->flash('store_success_info','" implementing mode named '.$request->get('name').'"');
        return redirect()->route('implementingmode.create');
    }

    public function show(){

    }

    public function edit(Implementingmode $implementingmode){
        $this->pro_data['implementingmode'] = $implementingmode;
        return view('admin.implementingmode.edit', $this->pro_data);
    }

    public function update(Request $request, Implementingmode $implementingmode){
        $status = $request->get('status') == 'on'?1:0;
        $oldImplementingmode=$implementingmode->toArray();
        $implementingmode->fill([
            'name'=>$request->get('name'),
            'name_eng'=>$request->get('name_eng'),
            'status'=>$status
        ])->save();
        $change=logDescriptionUpdate($implementingmode, $oldImplementingmode);
        if($change!=false){
            storeLog(null,$change,1 ,'Implementing Mode');
        }

        session()->flash('update_success_info','" implementing mode named '.$request->get('name').'"');
        return redirect()->route('implementingmode.index');
    }

    public function destroy(Implementingmode $implementingmode){
        $name = $implementingmode->name;
        if(Input::get('hardDelete')){
            $implementingmode->forceDelete();
        }else{
            $implementingmode->delete();
        }
        storeLog(null,$name,2 ,'Implementing Mode');
        session()->flash('delete_success_info','" implementing mode named '.$name.'"');
        return redirect()->route('implementingmode.index');
    }
}
