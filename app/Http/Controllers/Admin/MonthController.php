<?php
namespace PMIS\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use PMIS\Http\Controllers\AdminBaseController;
use PMIS\Month;

class MonthController extends AdminBaseController {
    protected $pro_data;
    protected $month;
    public function __construct(Month $month){
        parent::__construct();
        $this->middleware(function ($request, $next) {
            restrictToImplementingOffice('abort');
            return $next($request);
        });
        $this->month = $month;
    }
    public function index(){
        $this->pro_data['orderBy'] = 'id';
        $this->pro_data['order'] = 'asc';
        $this->pro_data['other_data'] = '';
        $this->pro_data['default_search'] = '';
        $this->pro_data['months'] = $this->month;
        if(isset($_GET['search'])){
            $this->pro_data['default_search'] = $_GET['search'];
            $this->pro_data['months'] = $this->pro_data['months']->search($_GET['search']);
            $this->pro_data['other_data'] = '&search='.$_GET['search'];
            if($this->pro_data['months']->get()->isEmpty()){
                $this->pro_data['not_found']= 'Sorry! could not find your content. Please try with another keywords.';
            }
        }
        if(isset($_GET['orderBy']) && isset($_GET['order'])){
            $this->pro_data['orderBy'] = $_GET['orderBy'];
            $this->pro_data['order'] = (strtolower($_GET['order'])=='asc')?'desc':'asc';
        }

        if(isset($_GET['trashes'])){
            $this->pro_data['months'] = $this->pro_data['months']->onlyTrashed();
        }
        $this->pro_data['trashes_no'] = $this->month->onlyTrashed()->count();
        $this->pro_data['months'] = $this->pro_data['months']->orderBy($this->pro_data['orderBy'],$this->pro_data['order'])->paginate(10);
        return view('admin.month.index',$this->pro_data);
    }

    public function create(){
        $this->pro_data['trimesters']=['1'=>'प्रथम चौमासिक','2'=>'दोस्रो चौमासिक', '3'=>'तेस्रो चौमासिक'];
        return view('admin.month.create', $this->pro_data);
    }

    public function store(Request $request){
        $status = $request->get('status') == 'on'?1:0;
        $this->month->create([
            'name'=>$request->get('name'),
            'name_eng'=>$request->get('name_eng'),
            'name_eng_eng'=>$request->get('name_eng_eng'),
            'trim_id'=>$request->get('trim_id'),
            'status'=>$status
        ]);
        $description=logDescriptionCreate($request->all());
        storeLog(null,$description,0 ,'Month');

        session()->flash('store_success_info','" month named '.$request->get('name').'"');
        return redirect()->route('month.create');
    }

    public function show(){

    }

    public function edit(Month $month){
        $this->pro_data['month'] = $month;
        $this->pro_data['trimesters']=['1'=>'प्रथम चौमासिक','2'=>'दोस्रो चौमासिक', '3'=>'तेस्रो चौमासिक'];
        return view('admin.month.edit', $this->pro_data);
    }

    public function update(Request $request, Month $month){
        $status = $request->get('status') == 'on'?1:0;

        $oldMonth=$month->toArray();

        $month->fill([
            'name'=>$request->get('name'),
            'name_eng'=>$request->get('name_eng'),
            'name_eng_eng'=>$request->get('name_eng_eng'),
            'trim_id'=>$request->get('trim_id'),
            'status'=>$status
        ])->save();
        $change=logDescriptionUpdate($month, $oldMonth);
        if($change!=false){
            storeLog(null,$change,1 ,'Month');
        }

        session()->flash('update_success_info','" month named '.$request->get('name').'"');
        return redirect()->route('month.index');
    }

    public function destroy(Month $month){
        $name = $month->name;
        if(Input::get('hardDelete')){
            $month->forceDelete();
        }else{
            $month->delete();
        }
        storeLog(null,$name,2 ,'Month');

        session()->flash('delete_success_info','" month named '.$name.'"');
        return redirect()->route('month.index');
    }
}
