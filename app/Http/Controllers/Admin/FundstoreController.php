<?php
namespace PMIS\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use PMIS\Http\Controllers\AdminBaseController;
use PMIS\FundStore;

class FundStoreController extends AdminBaseController {
    protected $pro_data;
    protected $fundStore;
    public function __construct(fundStore $fundStore){
        parent::__construct();
        $this->middleware(function ($request, $next) use ($fundStore) {
            restrictEngineers($this->user_info->type_flag);
            $this->fundStore= $fundStore;
            return $next($request);
        });

    }
    public function index(){
        $this->pro_data['orderBy'] = 'id';
        $this->pro_data['order'] = 'asc';
        $this->pro_data['other_data'] = '';
        $this->pro_data['default_search'] = '';
        $this->pro_data['fundStores'] = $this->fundStore;
        if(isset($_GET['search'])){
            $this->pro_data['default_search'] = $_GET['search'];
            $this->pro_data['fundStores'] = $this->pro_data['fundStores']->search($_GET['search']);
            $this->pro_data['other_data'] = '&search='.$_GET['search'];
            if($this->pro_data['fundStores']->get()->isEmpty()){
                $this->pro_data['not_found']= 'Sorry! could not find your content. Please try with another keywords.';
            }
        }
        if(isset($_GET['orderBy']) && isset($_GET['order'])){
            $this->pro_data['orderBy'] = $_GET['orderBy'];
            $this->pro_data['order'] = (strtolower($_GET['order'])=='asc')?'desc':'asc';
        }

        if(isset($_GET['trashes'])){
            $this->pro_data['fundStores'] = $this->pro_data['fundStores']->onlyTrashed();
        }
        $this->pro_data['trashes_no'] = $this->fundStore->onlyTrashed()->count();
        $this->pro_data['fundStores'] = $this->pro_data['fundStores']->orderBy($this->pro_data['orderBy'],$this->pro_data['order'])->paginate(50);
        return view('admin.fund_store.index',$this->pro_data);

    }

    public function create(){
        return view('admin.fund_store.create', $this->pro_data);
    }

    public function store(Request $request){
        $status = $request->get('status') == 'on'?1:0;
        $this->fundStore->create([
            'name'=>$request->get('name'), // nepali
            'name_eng'=>$request->get('name_eng'),
            'description'=>$request->get('description'), // nepali
            'description_eng'=>$request->get('description_eng'),
            'type'=>'bank',
            'account_name' => $request->get('account_name'),
            'account_no' => $request->get('account_no'),
            'branch' => $request->get('branch'),
            'address' => $request->get('address'),
            'created_by'=> Auth::user()->id,
            'updated_by'=> Auth::user()->id,
            'status'=>$status
        ]);

        $description=logDescriptionCreate($request->all());
        storeLog(null,$description,0 ,'Fund Store');

        session()->flash('store_success_info','" Bank named '.$request->get('name').'"');
        return redirect()->route('fundstore.create');
    }

    public function show(){

    }

    public function edit(fundStore $fundStore){
        $this->pro_data['fundStore'] = $fundStore;
        return view('admin.fund_store.edit', $this->pro_data);
    }

    public function update(Request $request, fundStore $fundStore){
        $status = $request->get('status') == 'on'?1:0;
        $oldFundStore=$fundStore->toArray();

        $fundStore->fill([
            'name'=>$request->get('name'), // nepali
            'name_eng'=>$request->get('name_eng'),
            'description'=>$request->get('description'), // nepali
            'description_eng'=>$request->get('description_eng'),
            'account_name' => $request->get('account_name'),
            'account_no' => $request->get('account_no'),
            'branch' => $request->get('branch'),
            'address' => $request->get('address'),
            'updated_by'=> Auth::user()->id,
            'status'=>$status
        ])->save();

        $change=logDescriptionUpdate($fundStore, $oldFundStore);
        if($change!=false){
            storeLog(null,$change,1 ,'Fund Store');
        }

        session()->flash('update_success_info','" bank named '.$request->get('name').'"');
        return redirect()->route('fundstore.index');
    }

    public function destroy(fundStore $slaryhead){
        $name = $slaryhead->name;
        if(Input::get('hardDelete')){
            $slaryhead->forceDelete();
        }else{
            $slaryhead->delete();
        }
        storeLog(null,$name,2 ,'Address');
        session()->flash('delete_success_info','" salary head named '.$name.'"');
        return redirect()->route('fundstore.index');
    }
}
