<?php
namespace PMIS\Http\Controllers\Admin;

use Guzzle\Http\Exception\CouldNotRewindStreamException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use PMIS\Http\Controllers\AdminBaseController;
use PMIS\ConstructionType;

class ConstructiontypeController extends AdminBaseController {
    protected $pro_data;
    protected $constructiontype;
    public function __construct(){
        parent::__construct();
        $this->middleware(function ($request, $next) {
            restrictToImplementingOffice('abort');
            $this->constructiontype = $this->user_info->constructionType();
            return $next($request);
        });

    }
    public function index(){
        $this->pro_data['orderBy'] = 'id';
        $this->pro_data['order'] = 'asc';
        $this->pro_data['other_data'] = '';
        $this->pro_data['default_search'] = '';
        $this->pro_data['constructiontypes'] = $this->constructiontype;
        if(isset($_GET['search'])){
            $this->pro_data['default_search'] = $_GET['search'];
            $this->pro_data['constructiontypes'] = $this->pro_data['constructiontypes']->search($_GET['search']);
            $this->pro_data['other_data'] = '&search='.$_GET['search'];
            if($this->pro_data['constructiontypes']->get()->isEmpty()){
                $this->pro_data['not_found']= 'Sorry! could not find your content. Please try with another keywords.';
            }
        }
        if(isset($_GET['orderBy']) && isset($_GET['order'])){
            $this->pro_data['orderBy'] = $_GET['orderBy'];
            $this->pro_data['order'] = (strtolower($_GET['order'])=='asc')?'desc':'asc';
        }

        if(isset($_GET['trashes'])){
            $this->pro_data['constructiontypes'] = ConstructionType::where('monitoring_office_id',$this->user_info->implementing_office_id)->onlyTrashed();
        }
        $this->pro_data['trashes_no'] = ConstructionType::where('monitoring_office_id',$this->user_info->implementing_office_id)->onlyTrashed()->count();
        $this->pro_data['constructiontypes'] = $this->pro_data['constructiontypes']->orderBy($this->pro_data['orderBy'],$this->pro_data['order'])->paginate(10);

        return view('admin.constructiontype.index',$this->pro_data);
    }

    public function create(){
        return view('admin.constructiontype.create', $this->pro_data);
    }

    public function store(Request $request){
        $status = $request->get('status') == 'on'?1:0;
        ConstructionType::create([
            'name'=>$request->get('name'),
            'name_eng'=>$request->get('name_eng'),
            'monitoring_office_id'=>$this->user_info->implementing_office_id,
            'status'=>$status
        ]);
        $description=logDescriptionCreate($request->all());
        storeLog(null,$description,0 ,'Construction Type');
        session()->flash('store_success_info', 'construction type named ' . $request->get('name') . ' created');
        return redirect()->route('constructiontype.create');
    }

    public function show(){

    }

    public function edit(Constructiontype $constructiontype){
        $this->pro_data['constructiontype'] = $constructiontype;
        return view('admin.constructiontype.edit', $this->pro_data);
    }

    public function update(Request $request, Constructiontype $constructiontype){
        $status = $request->get('status') == 'on'?1:0;
        $oldConstructiontype=$constructiontype->toArray();
        $constructiontype->fill([
            'name'=>$request->get('name'),
            'name_eng'=>$request->get('name_eng'),
            'status'=>$status
        ])->save();
        $change=logDescriptionUpdate($constructiontype, $oldConstructiontype);
        if($change!=false){
            storeLog(null,$change,1 ,'Construction Type');
        }
        session()->flash('update_success_info','" construction type named '.$request->get('name').'"');
        return redirect()->route('constructiontype.index');
    }

    public function destroy(Constructiontype $constructiontype){
        $name = $constructiontype->name;
        if(Input::get('hardDelete')){
            $constructiontype->forceDelete();
        }else{
            $constructiontype->delete();
        }
        storeLog(null,$name,2 ,'Construction Type');
        session()->flash('delete_success_info','" construction type named '.$name.'"');
        return redirect()->route('constructiontype.index');
    }
}
