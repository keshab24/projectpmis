<?php
namespace PMIS\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use PMIS\Http\Controllers\AdminBaseController;
use PMIS\Zone;
use PMIS\District;
use PMIS\Address;

class AddressController extends AdminBaseController {
    protected $pro_data;
    protected $address;
    public function __construct(Address $address){
        parent::__construct();
        restrictToImplementingOffice('abort');
        $this->address = $address;
    }
    public function index(){
        $this->pro_data['orderBy'] = 'id';
        $this->pro_data['order'] = 'asc';
        $this->pro_data['other_data'] = '';
        $this->pro_data['default_search'] = '';
        $this->pro_data['addresses'] = $this->address;
        if(isset($_GET['search'])){
            $this->pro_data['default_search'] = $_GET['search'];
            $this->pro_data['addresses'] = $this->pro_data['addresses']->search($_GET['search']);
            $this->pro_data['other_data'] = '&search='.$_GET['search'];
            if($this->pro_data['addresses']->get()->isEmpty()){
                $this->pro_data['not_found']= 'Sorry! could not find your content. Please try with another keywords.';
            }
        }
        if(isset($_GET['orderBy']) && isset($_GET['order'])){
            $this->pro_data['orderBy'] = $_GET['orderBy'];
            $this->pro_data['order'] = (strtolower($_GET['order'])=='asc')?'desc':'asc';
        }

        if(isset($_GET['trashes'])){
            $this->pro_data['addresses'] = $this->pro_data['addresses']->onlyTrashed();
        }
        $this->pro_data['trashes_no'] = $this->address->onlyTrashed()->count();
        $this->pro_data['addresses'] = $this->pro_data['addresses']->orderBy($this->pro_data['orderBy'],$this->pro_data['order'])->simplePaginate(10);
        return view('admin.address.index',$this->pro_data);
    }

    public function create(District $district){
        $this->pro_data['districts']=$district->whereStatus(1)->pluck('name','id');
        $this->pro_data['vdc_municipalities']=['VDC'=>'VDC','Municipality'=>'Municipality'];
        return view('admin.address.create', $this->pro_data);
    }

    public function store(Request $request){

        $status = $request->get('status') == 'on'?1:0;
        $this->address->create([
           'ward'=>$request->get('ward'),
           'tole'=>$request->get('tole'),
           'tole_eng'=>$request->get('tole_eng'),
           'vdc_municipality'=>$request->get('vdc_municipality'),
           'vdc_municipality_name'=>$request->get('vdc_municipality_name'),
           'vdc_municipality_name_eng'=>$request->get('vdc_municipality_name_eng'),
           'coordinates'=>$request->get('coordinates'),
           'district_id'=>$request->get('district_id'),
           'status'=>$status
        ]);

        $description=logDescriptionCreate($request->all());
        storeLog(null,$description,0 ,'Address');

        session()->flash('store_success_info','" address name '.$request->get('tole').'"');
        return redirect()->route('address.create');
    }

    public function show(){

    }

    public function edit(District $district, Address $address){
        //dd($address->all());
        $this->pro_data['address'] = $address;
        $this->pro_data['districts']=$district->whereStatus(1)->pluck('name','id');
        $this->pro_data['vdc_municipalities']=['VDC'=>'VDC','Municipality'=>'Municipality'];
        return view('admin.address.edit', $this->pro_data);
    }

    public function update(Request $request, Address $address){
        $oldAddress=$address->toArray();
        $status = $request->get('status') == 'on'?1:0;
        $address->fill([
            'ward'=>$request->get('ward'),
            'tole'=>$request->get('tole'),
            'tole_eng'=>$request->get('tole_eng'),
            'vdc_municipality'=>$request->get('vdc_municipality'),
            'vdc_municipality_name'=>$request->get('vdc_municipality_name'),
            'vdc_municipality_name_eng'=>$request->get('vdc_municipality_name_eng'),
            'coordinates'=>$request->get('coordinates'),
            'district_id'=>$request->get('district_id'),
            'status'=>$status
        ])->save();
        $change=logDescriptionUpdate($address, $oldAddress);
        if($change!=false){
            storeLog(null,$change,1 ,'Address');
        }


        session()->flash('update_success_info','" address named '.$request->get('tole').'"');
        return redirect()->route('address.index');
    }

    public function destroy(Address $address){
        $tole = $address->tole;
        if(Input::get('hardDelete')){
            $address->forceDelete();
        }else{
            $address->delete();
        }
        storeLog(null,$tole,2 ,'Address');
        session()->flash('delete_success_info','" address named '.$tole.'"');
        return redirect()->route('address.index');
    }
}
