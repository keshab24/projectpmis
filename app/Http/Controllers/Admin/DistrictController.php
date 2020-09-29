<?php
namespace PMIS\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use PMIS\Http\Controllers\AdminBaseController;
use PMIS\State;
use PMIS\Zone;
use PMIS\District;

class DistrictController extends AdminBaseController {
    protected $pro_data;
    protected $district;
    public function __construct(District $district){
        parent::__construct();
        $this->middleware(function ($request, $next) {
            restrictToImplementingOffice('abort');
            return $next($request);
        });
        $this->district = $district;
    }
    public function index(){
        $this->pro_data['orderBy'] = 'id';
        $this->pro_data['order'] = 'asc';
        $this->pro_data['other_data'] = '';
        $this->pro_data['default_search'] = '';
        if(isset($_GET['search'])){
            $this->pro_data['default_search'] = $_GET['search'];
            $this->pro_data['districts'] = $this->district->search($_GET['search']);
            $this->pro_data['other_data'] = '&search='.$_GET['search'];
            if($this->pro_data['districts']->get()->isEmpty()){
                $this->pro_data['not_found']= 'Sorry! could not find your content. Please try with another keywords.';
            }
        }else{
            $this->pro_data['districts'] = $this->district;
        }
        if(isset($_GET['orderBy']) && isset($_GET['order'])){
            $this->pro_data['orderBy'] = $_GET['orderBy'];
            $this->pro_data['order'] = (strtolower($_GET['order'])=='asc')?'desc':'asc';
        }

        if(isset($_GET['trashes'])){
            $this->pro_data['districts'] = $this->pro_data['districts']->onlyTrashed();
        }
        $this->pro_data['trashes_no'] = $this->district->onlyTrashed()->count();
        $this->pro_data['districts'] = $this->pro_data['districts']->orderBy($this->pro_data['orderBy'],$this->pro_data['order'])->simplePaginate(10);
        return view('admin.district.index',$this->pro_data);
    }

    public function create(Zone $zone, State $state){
        $this->pro_data['states']=$state->whereStatus(1)->pluck('name','id');
        $this->pro_data['zones']=$zone->whereStatus(1)->pluck('name','id');
        return view('admin.district.create', $this->pro_data);
    }

    public function store(Request $request){
        $status = $request->get('status') == 'on'?1:0;
        $this->district->create([
           'name'=>$request->get('name'),
           'name_eng'=>$request->get('name_eng'),
           'description'=>$request->get('description'),
           'description_eng'=>$request->get('description_eng'),
           'coordinates'=>$request->get('coordinates'),
           'zone_id'=>$request->get('zone_id'),
           'state_id'=>$request->get('state_id'),
           'geo_id'=>$request->get('geo_id'),
           'status'=>$status
        ]);
        $description=logDescriptionCreate($request->all());
        storeLog(null,$description,0 ,'District');

        session()->flash('store_success_info','" district name '.$request->get('name').'"');
        return redirect()->route('district.create');
    }

    public function show(){

    }

    public function edit(Zone $zone,State $state, District $district){
        $this->pro_data['states']=$state->whereStatus(1)->pluck('name','id');
        $this->pro_data['district'] = $district;
        $this->pro_data['zones']=$zone->whereStatus(1)->pluck('name','id');
        return view('admin.district.edit', $this->pro_data);
    }

    public function update(Request $request, District $district){
        $status = $request->get('status') == 'on'?1:0;
        $oldDistrict=$district->toArray();

        $district->fill([
            'name'=>$request->get('name'),
            'name_eng'=>$request->get('name_eng'),
            'description'=>$request->get('description'),
            'description_eng'=>$request->get('description_eng'),
            'coordinates'=>$request->get('coordinates'),
            'zone_id'=>$request->get('zone_id'),
            'state_id'=>$request->get('state_id'),
            'geo_id'=>$request->get('geo_id'),
            'status'=>$status
        ])->save();
        $change=logDescriptionUpdate($district, $oldDistrict);
        if($change!=false){
            storeLog(null,$change,1 ,'District');
        }

        session()->flash('update_success_info','" district named '.$request->get('name').'"');
        return redirect()->route('district.index');
    }

    public function destroy(District $district){
        $name = $district->name;
        if(Input::get('hardDelete')){
            $district->forceDelete();
        }else{
            $district->delete();
        }
        storeLog(null,$name,2 ,'District');

        session()->flash('delete_success_info','" district named '.$name.'"');
        return redirect()->route('district.index');
    }
}
