<?php
namespace PMIS\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use PMIS\Http\Controllers\AdminBaseController;
use PMIS\Region;

class RegionController extends AdminBaseController {
    protected $pro_data;
    protected $region;
    public function __construct(Region $region){
        parent::__construct();
        $this->middleware(function ($request, $next) {
            restrictToImplementingOffice('abort');
            return $next($request);
        });
        $this->region = $region;
    }
    public function index(){
        $this->pro_data['orderBy'] = 'id';
        $this->pro_data['order'] = 'asc';
        $this->pro_data['other_data'] = '';
        $this->pro_data['default_search'] = '';
        $this->pro_data['regions'] = $this->region;
        if(isset($_GET['search'])){
            $this->pro_data['default_search'] = $_GET['search'];
            $this->pro_data['regions'] = $this->pro_data['regions']->search($_GET['search']);
            $this->pro_data['other_data'] = '&search='.$_GET['search'];
            if($this->pro_data['regions']->get()->isEmpty()){
                $this->pro_data['not_found']= 'Sorry! could not find your content. Please try with another keywords.';
            }
        }
        if(isset($_GET['orderBy']) && isset($_GET['order'])){
            $this->pro_data['orderBy'] = $_GET['orderBy'];
            $this->pro_data['order'] = (strtolower($_GET['order'])=='asc')?'desc':'asc';
        }

        if(isset($_GET['trashes'])){
            $this->pro_data['regions'] = $this->pro_data['regions']->onlyTrashed();
        }
        $this->pro_data['trashes_no'] = $this->region->onlyTrashed()->count();
        $this->pro_data['regions'] = $this->pro_data['regions']->orderBy($this->pro_data['orderBy'],$this->pro_data['order'])->paginate(10);
        return view('admin.region.index',$this->pro_data);
    }

    public function create(){
        return view('admin.region.create', $this->pro_data);
    }

    public function store(Request $request){
        $status = $request->get('status') == 'on'?1:0;
        $this->region->create([
            'name'=>$request->get('name'),
            'name_eng'=>$request->get('name_eng'),
            'description'=>$request->get('description'),
            'description_eng'=>$request->get('description_eng'),
            'coordinates'=>$request->get('coordinates'),
            'status'=>$status
        ]);
        $description=logDescriptionCreate($request->all());
        storeLog(null,$description,0 ,'Region');
        session()->flash('store_success_info','" region named '.$request->get('name').'"');
        return redirect()->route('region.create');
    }

    public function show(){

    }

    public function edit(Region $region){
        $this->pro_data['region'] = $region;
        return view('admin.region.edit', $this->pro_data);
    }

    public function update(Request $request, Region $region){
        $status = $request->get('status') == 'on'?1:0;
        $oldRegion=$region->toArray();
        $region->fill([
            'name'=>$request->get('name'),
            'name_eng'=>$request->get('name_eng'),
            'description'=>$request->get('description'),
            'description_eng'=>$request->get('description_eng'),
            'coordinates'=>$request->get('coordinates'),
            'status'=>$status
        ])->save();
        $change=logDescriptionUpdate($region, $oldRegion);
        if($change!=false){
            storeLog(null,$change,1 ,'Region');
        }

        session()->flash('update_success_info','" region named '.$request->get('name').'"');
        return redirect()->route('region.index');
    }

    public function destroy(Region $region){
        $name = $region->name;
        if(Input::get('hardDelete')){
            $region->forceDelete();
        }else{
            $region->delete();
        }
        storeLog(null,$name,2 ,'Region');

        session()->flash('delete_success_info','" region named '.$name.'"');
        return redirect()->route('region.index');
    }
}
