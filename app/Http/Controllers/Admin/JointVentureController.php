<?php
namespace PMIS\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use PMIS\Contractor;
use PMIS\Http\Controllers\AdminBaseController;
use PMIS\JointVenture;
use PMIS\Http\Requests\CreateJointVentureRequest;

class JointVentureController extends AdminBaseController {
    protected $pro_data;
    protected $joint_venture;
    public function __construct(JointVenture $joint_venture){
        parent::__construct();
        $this->middleware(function ($request, $next) use ($joint_venture) {
            restrictEngineers($this->user_info->type_flag);
            $this->joint_venture = $joint_venture;
            return $next($request);
        });
    }
    public function index(){
        $this->pro_data['orderBy'] = 'id';
        $this->pro_data['order'] = 'asc';
        $this->pro_data['other_data'] = '';
        $this->pro_data['default_search'] = '';
        if(isset($_GET['search'])){
            $this->pro_data['default_search'] = $_GET['search'];
            $this->pro_data['JointVentures'] = $this->joint_venture->search($_GET['search']);
            $this->pro_data['other_data'] = '&search='.$_GET['search'];
            if($this->pro_data['JointVentures']->get()->isEmpty()){
                $this->pro_data['not_found']= 'Sorry! could not find your content. Please try with another keywords.';
            }
        }else{
            $this->pro_data['JointVentures'] = $this->joint_venture;
        }
        if(isset($_GET['orderBy']) && isset($_GET['order'])){
            $this->pro_data['orderBy'] = $_GET['orderBy'];
            $this->pro_data['order'] = (strtolower($_GET['order'])=='asc')?'desc':'asc';
        }
        if(isset($_GET['trashes'])){
            $this->pro_data['JointVentures'] = $this->pro_data['JointVentures']->onlyTrashed();
        }
        $this->pro_data['trashes_no'] = $this->joint_venture->onlyTrashed()->count();
        $this->pro_data['JointVentures'] = $this->pro_data['JointVentures']->orderBy($this->pro_data['orderBy'],$this->pro_data['order'])->paginate(10);
        return view('admin.JointVenture.index',$this->pro_data);
    }

    public function create(Contractor $contractor){
        $this->pro_data['contractors']=$contractor->whereStatus(1)->get();
        $this->pro_data['contractor_base']=$contractor->whereStatus(1)->pluck('name','id');
        return view('admin.JointVenture.create', $this->pro_data);
    }

    public function store(Request $request){
        $status = $request->get('status') == 'on'?1:0;
        $jv=$this->joint_venture->create([
            'name'=>$request->get('name'),
            'nep_name'=>$request->get('nep_name'),
            'address'=>$request->get('address'),
            'email'=>$request->get('email'),
            'mobile'=>$request->get('mobile'),
            'fax'=>$request->get('fax'),
            'description'=>$request->get('description'),
            'created_by'=> Auth::user()->id,
            'updated_by'=> Auth::user()->id,
            'status'=>$status
        ]);


        $description=logDescriptionCreate($request->all());
        storeLog(null,$description,0 ,'Joint Venture');

        $this->add_pivot($request->get('contractors'), $jv);



        session()->flash('store_success_info','" JointVenture name '.$request->get('name').'"');
        return redirect()->route('joint_venture.create');
    }

    public function add_pivot($contractorsToken, $jointVenture){
        $oldContractors='';
        foreach($jointVenture->contractors()->get() as $belongContractor){
            $oldContractors[]=$belongContractor->name;
        }

        if($contractorsToken){
            $contractors = explode(',',$contractorsToken);
            $contractor_saving = [];
            foreach($contractors as $contractor){
                $contractor = trim($contractor);
                if($contractor != ''){
                    $selectContractor = Contractor::whereName($contractor)->first();
                    if($selectContractor){
                        $contractor_saving[] = $selectContractor->id;
                    }
                }
            }
            $jointVenture->contractors()->sync($contractor_saving);
        }else{
            $jointVenture->contractors()->detach();
        }
        $newContractor='';
        foreach($jointVenture->contractors()->get() as $belongContractor){
            $newContractor[]=$belongContractor->name;
        }

        if($oldContractors != $newContractor){
            $description=logPivot($jointVenture->name,$newContractor, "Contractors");
            storeLog(null,$description,1 ,'Joint Venture contractors');
        }
    }


    public function show(){

    }

    public function edit(JointVenture $jointVenture,Contractor $contractors ){
        $this->pro_data['jointVenture']=$jointVenture;
        $this->pro_data['contractorsHere']=implode(',',$jointVenture->contractors()->pluck('name')->toArray());;
        $this->pro_data['contractors']=$contractors->whereStatus(1)->get();
        $this->pro_data['contractor_base']=$contractors->whereStatus(1)->pluck('name','id');
        return view('admin.JointVenture.edit', $this->pro_data);
    }

    public function update(Request $request, JointVenture $joint_venture){
        $status = $request->get('status') == 'on'?1:0;

        $oldJv=$joint_venture->toArray();

        $joint_venture->fill([
            'name'=>$request->get('name'),
            'nep_name'=>$request->get('nep_name'),
            'address'=>$request->get('address'),
            'email'=>$request->get('email'),
            'mobile'=>$request->get('mobile'),
            'fax'=>$request->get('fax'),
            'description'=>$request->get('description'),
            'created_by'=> Auth::user()->id,
            'updated_by'=> Auth::user()->id,
            'status'=>$status
        ])->save();
        $change=logDescriptionUpdate($joint_venture, $oldJv);
        if($change!=false){
            storeLog(null,$change,1 ,'Income');
        }
        $this->add_pivot($request->get('contractors'), $joint_venture);
        session()->flash('update_success_info','" JointVenture named '.$request->get('name').'"');
        return redirect()->route('joint_venture.index');
    }

    public function destroy(JointVenture $joint_venture){
        $name = $joint_venture->name;
        if(Input::get('hardDelete')){
            $joint_venture->forceDelete();
        }else{
            $joint_venture->delete();
        }
        storeLog(null,$name,2 ,'Joint Venture');
        session()->flash('delete_success_info','" JointVenture named '.$name.'"');
        return redirect()->route('joint_venture.index');
    }
}
