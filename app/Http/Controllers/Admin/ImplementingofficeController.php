<?php
namespace PMIS\Http\Controllers\Admin;

use Faker\Provider\pt_BR\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use PMIS\ActivityLog;
use PMIS\Cheif;
use PMIS\DateCon;
use PMIS\District;
use PMIS\Http\Controllers\AdminBaseController;
use PMIS\ImplementingOffice;
use PMIS\ImplementingOfficePivot;

class ImplementingofficeController extends AdminBaseController {
    protected $pro_data;
    protected $implementingoffice;
    public function __construct(Implementingoffice $implementingoffice){
        parent::__construct();
        $this->middleware(function ($request, $next) use ($implementingoffice) {
            restrictToImplementingOffice('abort');
            $this->implementingoffice = $implementingoffice;
            if(Auth::user()->implementing_office_id!=1) {
                if(Auth::user()->implementingOffice->isMonitoring==1){
                    $this->implementingoffice=Auth::user()->implementingOffice->MonitorSeesImplementing();
                }else{
                    $this->implementingoffice=$implementingoffice->whereId(Auth::user()->implementing_office_id);
                }
            }
            return $next($request);
        });


    }
    public function index(ImplementingOffice $implementingOffice){
        $this->commonSearchData();
        $this->pro_data['orderBy'] = 'parent_id';
        $this->pro_data['order'] = 'asc';
        $this->pro_data['other_data'] = '';
        $this->pro_data['default_search'] = '';
        $this->pro_data['implementingoffices'] = $this->implementingoffice->where('level','>',0);
        if(isset($_GET['search'])){
            $this->pro_data['default_search'] = $_GET['search'];
            $this->pro_data['implementingoffices'] = $this->pro_data['implementingoffices']->search($_GET['search']);
            $this->pro_data['other_data'] = '&search='.$_GET['search'];
            if($this->pro_data['implementingoffices']->get()->isEmpty()){
                $this->pro_data['not_found']= 'Sorry! could not find your content. Please try with another keywords.';
            }
        }
        if(isset($_GET['orderBy']) && isset($_GET['order'])){
            $this->pro_data['orderBy'] = $_GET['orderBy'];
            $this->pro_data['order'] = (strtolower($_GET['order'])=='asc')?'desc':'asc';
            $this->pro_data['implementingoffices']->orderBy($this->pro_data['orderBy'],$this->pro_data['order']);
        }

        if(isset($_GET['trashes'])){
            $this->pro_data['implementingoffices'] = $implementingOffice->onlyTrashed();
        }
        $this->pro_data['trashes_no'] = $implementingOffice->onlyTrashed()->count();
        $this->pro_data['implementingoffices'] = $this->pro_data['implementingoffices']->paginate($this->pro_data['limit']);
        // $parent = $this->pro_data['implementingoffices'][0];
        // dd($parent->parent->title);
        
        return view('admin.implementingoffice.index',$this->pro_data);
    }

    public function create(ImplementingOffice $implementingOffice, District $district,Cheif $cheif){
        $this->pro_data['districts_eng']=$district->whereStatus(1)->pluck('name_eng','id');
        $this->pro_data['cheifs']=$cheif->whereStatus(1)->pluck('name','id');
        $this->pro_data['implementing_offices'] = $implementingOffice->whereLevel(1)->whereIsLastNode(0)->get();
        $this->pro_data['districts'] = add_my_array($district->whereStatus(1)->pluck('name_eng','id'));
        //$this->pro_data['districts'] = add_my_array($district->whereStatus(1)->pluck('name','id'));
        return view('admin.implementingoffice.create', $this->pro_data);
    }

    public function store(Request $request){
        // dd($request->all());
        $stored=ImplementingOffice::whereName($request->get('name'))->first();
        if($stored!=null){
            session()->flash('fail_info','" implementing office named  already recorded"');
            return redirect()->route('implementingoffice.create');
        }
        $status = $request->get('status') == 'on'?1:0;
        $isMonitoring = $request->get('is_monitoring') == 'on'?1:0;
        $implementingoffice=$this->implementingoffice->create([
            'name'=>$request->get('name'),
            'name_eng'=>$request->get('name_eng'),
            'description'=>$request->get('description'),
            'description_eng'=>$request->get('description_eng'),
            'district_id'=>$request->get('district_id')==0?Null:$request->get('district_id'),
            'office_code'=>$request->get('district_id'),
            'address'=>$request->get('address'),
            'phone'=>$request->get('phone'),
            'email'=>$request->get('email'),
            'mobile'=>$request->get('mobile'),
            'coordinates'=>$request->get('coordinates'),
            'level'=>$request->get('level'),
            'order'=>$request->get('order'),
            'bank_name'=>'na',
            'branch_address'=>'na',
            'account_no'=>'na',
            'parent_id'=>$request->get('implementing_office_id'),
            'is_last_node'=>$request->get('is_last_node') == 'on'?1:0,
            'is_physical_office'=>$request->get('is_last_node') == 'on'?1:0,
            'created_by'=>$this->user_info->id,
            'updated_by'=>$this->user_info->id,
            'status'=>$status,
            'isMonitoring'=>$isMonitoring,
        ]);
        $implementingoffice->cheifs()->attach($request->get('cheif_id'));
        $this->add_pivot($request->get('districts'), $implementingoffice);


        $description=logDescriptionCreate($request->all());
        storeLog(null,$description,0 ,'Implementing Office');

        session()->flash('store_success_info','implementing office named '.$request->get('name').'');
        return redirect()->route('implementingoffice.create');
    }

    public function show(ImplementingOffice $implementingOffice){
        $this->pro_data['implementingoffice'] = $implementingOffice;
        $this->pro_data['payments'] = \PMIS\Payment::whereHas('release',function($release) use($implementingOffice){
           $release->whereImplementing_office_id = $implementingOffice->id;
        })->get();
        return view('admin.implementingoffice.show',$this->pro_data);
    }

    public function edit(ImplementingOffice $implementingOffice, District $district, Cheif $cheif){
        $this->pro_data['implementingoffice']=$implementingOffice;
        $this->pro_data['districts_eng']=$district->whereStatus(1)->pluck('name_eng','id');
        $this->pro_data['cheifs']=$cheif->whereStatus(1)->pluck('name','id');
        if($implementingOffice->cheifs()->get()->last()==null){
            $this->pro_data['cheif']=1;
        }else{
            $this->pro_data['cheif']=$implementingOffice->cheifs()->get()->last()->id;
        }
        $this->pro_data['implementing_offices'] = $implementingOffice->whereLevel(1)->whereIsLastNode(0)->get();
        $this->pro_data['districts'] = add_my_array($district->whereStatus(1)->pluck('name_eng','id'));
        //$this->pro_data['districts'] = add_my_array($district->whereStatus(1)->pluck('name','id'));
        return view('admin.implementingoffice.edit', $this->pro_data);
    }

    public function update(Request $request, Implementingoffice $implementingoffice){
        $status = $request->get('status') == 'on'?1:0;
        $oldImplementingOffice=$implementingoffice->toArray();
        $isMonitoring = $request->get('is_monitoring') == 'on'?1:0;
        $implementingoffice->fill([
            'name'=>$request->get('name'),
            'name_eng'=>$request->get('name_eng'),
            'description'=>$request->get('description'),
            'description_eng'=>$request->get('description_eng'),
            'district_id'=>$request->get('district_id')==0?Null:$request->get('district_id'),
            //'office_code'=>$request->get('district_id'),
            'address'=>$request->get('address'),
            'phone'=>$request->get('phone'),
            'email'=>$request->get('email'),
            'mobile'=>$request->get('mobile'),
            'coordinates'=>$request->get('coordinates'),
            'level'=>$request->get('level'),
            'order'=>$request->get('order'),
            'bank_name'=>'na',
            'branch_address'=>'na',
            'account_no'=>'na',
            'parent_id'=>$request->get('implementing_office_id'),
            'is_last_node'=>$request->get('is_last_node') == 'on'?1:0,
            'is_physical_office'=>$request->get('is_last_node') == 'on'?1:0,
            'created_by'=>$this->user_info->id,
            'updated_by'=>$this->user_info->id,
            'status'=>$status,
            'isMonitoring'=>$isMonitoring,

        ])->save();
        if($request->get('cheif_id')!=1){
            $implementingoffice->cheifs()->attach($request->get('cheif_id'));
        }

        $change=logDescriptionUpdate($implementingoffice, $oldImplementingOffice);
        if($change!=false){
            storeLog(null,$change,1 ,'Implementing Office');
        }

        $this->add_pivot($request->get('districts'), $implementingoffice);

        session()->flash('update_success_info','implementing office named '.$request->get('name').'');
        return redirect()->route('implementingoffice.edit', $implementingoffice->id);
        return redirect()->route('implementingoffice.index');
    }

    public function uploadLog(Request $request, ImplementingOffice $implementingOffice)
    {
        $fileName = '';
        if($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = getFileName($file);
            $mime = $file->getMimeType();
            if($mime == 'image/jpeg' || $mime == 'image/jpeg' || $mime == 'image/gif' ||$mime == 'image/gif' || $mime == 'image/png' || $mime == 'application/pdf' ){
                $file->move('public/activityFiles',$fileName);
            }else{
                return redirect()->route('implementingoffice.show', $implementingOffice->id)->with('error');
            }
        }
        $submitted_date = dateAD($request->get('date'));
        ActivityLog::create([
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'submitted_date' => $submitted_date,
            'implementing_office_id' => $implementingOffice->id,
            'file_path' => $fileName,
            'created_by' => $this->user_info->id,
            'updated_by' => $this->user_info->id,
            'status' => 1
        ]);
        return redirect()->route('implementingoffice.show', $implementingOffice->id);
    }

    public function destroy(Implementingoffice $implementingoffice){
        $name = $implementingoffice->name;
        if(Input::get('hardDelete')){
            $implementingoffice->forceDelete();
        }else{
            $implementingoffice->delete();
        }
        storeLog(null,$name,2 ,'Implementing Office');
        session()->flash('delete_success_info','" implementing office named '.$name.'"');
        return redirect()->route('implementingoffice.index');
    }

    function add_pivot($input, $implementing_office){
        $oldDistricts=[];
        foreach($implementing_office->districts as $belongDistrict){
            $oldDistricts[]=$belongDistrict->name;
        }


        if($input){
            $districts = explode(',',$input);
            $district_save = [];
            foreach($districts as $district){
                $district = trim($district);
                if($district != ''){
                   // $district_select = District::whereName($district)->first();
                    $district_select = District::whereNameEng($district)->first();
                    $district_save[] = $district_select->id;
                }
            }
            //kun implementing office le kun kun jilla herne kura pani fy anusar diff hune dekhiyo..
            //so yo pivot table lai pani fy year anusar manage gariyeko
            //2076/77 bata dhankuta  -  fpiu dhankuta le herne .. 2075/76 samma fpiu morang le herthyio .
            $current = ImplementingOfficePivot::where('implementing_office_id',$implementing_office->id)->where('fy_id', session()->get('pro_fiscal_year'))->get()->pluck('district_id');
            $detach = $current->diff($district_save)->all();
            ImplementingOfficePivot::where('implementing_office_id',$implementing_office->id)->where('fy_id', session()->get('pro_fiscal_year'))->whereIn('district_id',$detach)->delete();
            $attach_ids = collect($district_save)->diff($current)->all();
            $attach_pivot = array_fill(0, count($attach_ids), ['fy_id' => session()->get('pro_fiscal_year')]);
            $attach = array_combine($attach_ids, $attach_pivot);
            $implementing_office->districts()->attach($attach);
        }else{
            ImplementingOfficePivot::where('implementing_office_id',$implementing_office->id)->where('fy_id', session()->get('pro_fiscal_year'))->delete();
        }
        $newDistrict=[];

        foreach($implementing_office->districts as $belongDistrict){
            $newDistrict[]=$belongDistrict->name;
        }
        if($oldDistricts != $newDistrict){
            $description=logPivot($implementing_office->name,$newDistrict, "Districts");
            storeLog(null,$description,1 ,'Implementing Office District');
        }
    }
}
