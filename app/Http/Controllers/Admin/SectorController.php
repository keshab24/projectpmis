<?php
namespace PMIS\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use PMIS\Http\Controllers\AdminBaseController;
use PMIS\Sector;

class SectorController extends AdminBaseController {
    protected $pro_data;
    protected $sector;
    public function __construct(Sector $sector){
        parent::__construct();
        $this->middleware(function ($request, $next) {
            restrictToImplementingOffice('abort');
            return $next($request);
        });
        $this->sector = $sector;
    }
    public function index(){
        $this->pro_data['orderBy'] = 'id';
        $this->pro_data['order'] = 'asc';
        $this->pro_data['other_data'] = '';
        $this->pro_data['default_search'] = '';
        $this->pro_data['sectors'] = $this->sector;
        if(isset($_GET['search'])){
            $this->pro_data['default_search'] = $_GET['search'];
            $this->pro_data['sectors'] = $this->pro_data['sectors']->search($_GET['search']);
            $this->pro_data['other_data'] = '&search='.$_GET['search'];
            if($this->pro_data['sectors']->get()->isEmpty()){
                $this->pro_data['not_found']= 'Sorry! could not find your content. Please try with another keywords.';
            }
        }
        if(isset($_GET['orderBy']) && isset($_GET['order'])){
            $this->pro_data['orderBy'] = $_GET['orderBy'];
            $this->pro_data['order'] = (strtolower($_GET['order'])=='asc')?'desc':'asc';
        }

        if(isset($_GET['trashes'])){
            $this->pro_data['sectors'] = $this->pro_data['sectors']->onlyTrashed();
        }
        $this->pro_data['trashes_no'] = $this->sector->onlyTrashed()->count();
        $this->pro_data['sectors'] = $this->pro_data['sectors']->orderBy($this->pro_data['orderBy'],$this->pro_data['order'])->paginate(10);
        return view('admin.sector.index',$this->pro_data);
    }

    public function create(){
        return view('admin.sector.create', $this->pro_data);
    }

    public function store(Request $request){
        $status = $request->get('status') == 'on'?1:0;
        $this->sector->create([
            'name'=>$request->get('name'),
            'name_eng'=>$request->get('name_eng'),
            'status'=>$status
        ]);
        $description=logDescriptionCreate($request->all());
        storeLog(null,$description,0 ,'District');

        session()->flash('store_success_info','" sector named '.$request->get('name').'"');
        return redirect()->route('sector.create');
    }

    public function show(){

    }

    public function edit(Sector $sector){
        $this->pro_data['sector'] = $sector;
        return view('admin.sector.edit', $this->pro_data);
    }

    public function update(Request $request, Sector $sector){
        $status = $request->get('status') == 'on'?1:0;
        $oldSector=$sector->toArray();

        $sector->fill([
            'name'=>$request->get('name'),
            'name_eng'=>$request->get('name_eng'),
            'status'=>$status
        ])->save();
        $change=logDescriptionUpdate($sector, $oldSector);
        if($change!=false){
            storeLog(null,$change,1 ,'Sector');
        }

        session()->flash('update_success_info','" sector named '.$request->get('name').'"');
        return redirect()->route('sector.index');
    }

    public function destroy(Sector $sector){
        $name = $sector->name;
        if(Input::get('hardDelete')){
            $sector->forceDelete();
        }else{
            $sector->delete();
        }
        storeLog(null,$name,2 ,'Sector');

        session()->flash('delete_success_info','" sector named '.$name.'"');
        return redirect()->route('sector.index');
    }
}
