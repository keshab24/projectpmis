<?php
namespace PMIS\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use PMIS\Http\Controllers\AdminBaseController;
use PMIS\Region;
use PMIS\Zone;

class ZoneController extends AdminBaseController {
    protected $pro_data;
    protected $zone;
    public function __construct(Zone $zone){
        parent::__construct();
        $this->middleware(function ($request, $next) {
            restrictToImplementingOffice('abort');
            return $next($request);
        });
        $this->zone = $zone;
    }
    public function index(){
        $this->pro_data['orderBy'] = 'id';
        $this->pro_data['order'] = 'asc';
        $this->pro_data['other_data'] = '';
        $this->pro_data['default_search'] = '';
        if(isset($_GET['search'])){
            $this->pro_data['default_search'] = $_GET['search'];
            $this->pro_data['zones'] = $this->zone->search($_GET['search']);
            $this->pro_data['other_data'] = '&search='.$_GET['search'];
            if($this->pro_data['zones']->get()->isEmpty()){
                $this->pro_data['not_found']= 'Sorry! could not find your content. Please try with another keywords.';
            }
        }else{
            $this->pro_data['zones'] = $this->zone;
        }
        if(isset($_GET['orderBy']) && isset($_GET['order'])){
            $this->pro_data['orderBy'] = $_GET['orderBy'];
            $this->pro_data['order'] = (strtolower($_GET['order'])=='asc')?'desc':'asc';
        }

        if(isset($_GET['trashes'])){
            $this->pro_data['zones'] = $this->pro_data['zones']->onlyTrashed();
        }
        $this->pro_data['trashes_no'] = $this->zone->onlyTrashed()->count();
        $this->pro_data['zones'] = $this->pro_data['zones']->orderBy($this->pro_data['orderBy'],$this->pro_data['order'])->simplePaginate(10);
        return view('admin.zone.index',$this->pro_data);
    }

    public function create(Region $region){
        $this->pro_data['regions']=$region->whereStatus(1)->pluck('name','id');
        return view('admin.zone.create', $this->pro_data);
    }

    public function store(Request $request){
        $status = $request->get('status') == 'on'?1:0;
        $this->zone->create([
           'name'=>$request->get('name'),
           'name_eng'=>$request->get('name_eng'),
           'description'=>$request->get('description'),
           'description_eng'=>$request->get('description_eng'),
           'coordinates'=>$request->get('coordinates'),
            'region_id'=>$request->get('region_id'),
            'status'=>$status
        ]);
        $description=logDescriptionCreate($request->all());
        storeLog(null,$description,0 ,'Zone');

        session()->flash('store_success_info','" zone name '.$request->get('name').'"');
        return redirect()->route('zone.create');
    }

    public function show(){

    }

    public function edit(Zone $zone, Region $region){
        $this->pro_data['zone'] = $zone;
        $this->pro_data['regions']=$region->whereStatus(1)->pluck('name','id');
        return view('admin.zone.edit', $this->pro_data);
    }

    public function update(Request $request, Zone $zone){
        $status = $request->get('status') == 'on'?1:0;
        $oldZone=$zone->toArray();

        $zone->fill([
            'name'=>$request->get('name'),
            'name_eng'=>$request->get('name_eng'),
            'description'=>$request->get('description'),
            'description_eng'=>$request->get('description_eng'),
            'coordinates'=>$request->get('coordinates'),
            'region_id'=>$request->get('region_id'),
            'status'=>$status
        ])->save();
        $change=logDescriptionUpdate($zone, $oldZone);
        if($change!=false){
            storeLog(null,$change,1 ,'Zone');
        }

        session()->flash('update_success_info','" zone named '.$request->get('name').'"');
        return redirect()->route('zone.index');
    }

    public function destroy(Zone $zone){
        $name = $zone->name;
        if(Input::get('hardDelete')){
            $zone->forceDelete();
        }else{
            $zone->delete();
        }
        storeLog(null,$name,2 ,'Zone');

        session()->flash('delete_success_info','" zone named '.$name.'"');
        return redirect()->route('zone.index');
    }
}
