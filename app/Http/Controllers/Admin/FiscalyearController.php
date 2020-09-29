<?php
namespace PMIS\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use PMIS\Http\Controllers\AdminBaseController;
use PMIS\Fiscalyear;

class FiscalyearController extends AdminBaseController {
    protected $pro_data;
    protected $fiscalyear;
    public function __construct(Fiscalyear $fiscalyear){
        parent::__construct();
        $this->middleware(function ($request, $next) {
            restrictToImplementingOffice('abort');
            return $next($request);
        });
        $this->fiscalyear = $fiscalyear;
    }
    public function index(){
        $this->pro_data['orderBy'] = 'id';
        $this->pro_data['order'] = 'asc';
        $this->pro_data['other_data'] = '';
        $this->pro_data['default_search'] = '';
        $this->pro_data['fiscalyears'] = $this->fiscalyear;
        if(isset($_GET['search'])){
            $this->pro_data['default_search'] = $_GET['search'];
            $this->pro_data['fiscalyears'] = $this->pro_data['fiscalyears']->search($_GET['search']);
            $this->pro_data['other_data'] = '&search='.$_GET['search'];
            if($this->pro_data['fiscalyears']->get()->isEmpty()){
                $this->pro_data['not_found']= 'Sorry! could not find your content. Please try with another keywords.';
            }
        }
        if(isset($_GET['orderBy']) && isset($_GET['order'])){
            $this->pro_data['orderBy'] = $_GET['orderBy'];
            $this->pro_data['order'] = (strtolower($_GET['order'])=='asc')?'desc':'asc';
        }

        if(isset($_GET['trashes'])){
            $this->pro_data['fiscalyears'] = $this->pro_data['fiscalyears']->onlyTrashed();
        }
        $this->pro_data['trashes_no'] = $this->fiscalyear->onlyTrashed()->count();
        $this->pro_data['fiscalyears'] = $this->pro_data['fiscalyears']->orderBy($this->pro_data['orderBy'],$this->pro_data['order'])->paginate(10);
        return view('admin.fiscalyear.index',$this->pro_data);
    }

    public function create(){
        return view('admin.fiscalyear.create', $this->pro_data);
    }

    public function store(Request $request){
        $status = $request->get('status') == 'on'?1:0;
        $this->fiscalyear->create([
            'fy'=>$request->get('fy'),
            'status'=>$status
        ]);
        $description=logDescriptionCreate($request->all());
        storeLog(null,$description,0 ,'Fiscal Year');

        session()->flash('store_success_info','" fiscalyear named '.$request->get('name').'"');
        return redirect()->route('fiscalyear.create');
    }

    public function show(){

    }

    public function edit(Fiscalyear $fiscalyear){
        $this->pro_data['fiscalyear'] = $fiscalyear;
        return view('admin.fiscalyear.edit', $this->pro_data);
    }

    public function update(Request $request, Fiscalyear $fiscalyear){
        $status = $request->get('status') == 'on'?1:0;
        $oldFiscalyear=$fiscalyear->toArray();

        $fiscalyear->fill([
            'fy'=>$request->get('fy'),
            'status'=>$status
        ])->save();
        $change=logDescriptionUpdate($fiscalyear, $oldFiscalyear);
        if($change!=false){
            storeLog(null,$change,1 ,'District');
        }

        session()->flash('update_success_info','" fiscalyear named '.$request->get('name').'"');
        return redirect()->route('fiscalyear.index');
    }

    public function destroy(Fiscalyear $fiscalyear){

        $name = $fiscalyear->fy;
        if(Input::get('hardDelete')){
            $fiscalyear->forceDelete();
        }else{
            $fiscalyear->delete();
        }

        storeLog(null,$name,2 ,'Fiscal Year');

        session()->flash('delete_success_info','" fiscalyear named '.$name.'"');
        return redirect()->route('fiscalyear.index');
    }
}
