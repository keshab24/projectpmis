<?php
namespace PMIS\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use PMIS\Contractor;
use PMIS\Engineer;
use PMIS\Http\Controllers\AdminBaseController;
use PMIS\ImplementingOffice;
use PMIS\Manpower;
use PMIS\User;

class EngineersController extends AdminBaseController {
    protected $pro_data;
    protected $engineer;
    public function __construct(Engineer $engineer){
        parent::__construct();
        $this->middleware(function ($request, $next) use ($engineer) {
            restrictEngineers($this->user_info->type_flag);
            $this->engineer = $engineer;
            return $next($request);
        });
    }
    public function index(Engineer $engineer){
        $this->pro_data['orderBy'] = 'id';
        $this->pro_data['order'] = 'desc';
        $this->pro_data['other_data'] = '';
        $this->pro_data['default_search'] = '';
        /* if sbcpco */
        if (in_array('410', Auth::user()->visibleImplementingOffices()->get()->pluck('id')->toArray())){
            $this->pro_data['engineers'] = $this->engineer->where('implementing_office', '410');
        }else{//no filter
            $this->pro_data['engineers'] =$this->engineer;
        }
        if(isset($_GET['search'])){
            $this->pro_data['default_search'] = $_GET['search'];
            $this->pro_data['engineers'] = $this->engineer->search($_GET['search']);
            $this->pro_data['other_data'] = '&search='.$_GET['search'];
            if($this->pro_data['engineers']->get()->isEmpty()){
                $this->pro_data['not_found']= 'Sorry! could not find your content. Please try with another keywords.';
            }
        }
        if(isset($_GET['orderBy']) && isset($_GET['order'])){
            $this->pro_data['orderBy'] = $_GET['orderBy'];
            $this->pro_data['order'] = (strtolower($_GET['order'])=='asc')?'desc':'asc';
        }

        if(isset($_GET['trashes'])){
            $this->pro_data['engineers'] = $engineer->onlyTrashed();
        }
        $this->pro_data['trashes_no'] = $engineer->onlyTrashed()->count();
        $this->pro_data['engineers'] = $this->pro_data['engineers']->orderBy($this->pro_data['orderBy'],$this->pro_data['order'])->get();
        $this->pro_data['engineers'] = $this->paginateCollection($this->pro_data['engineers'], 10);
        return view('admin.engineer.index',$this->pro_data);
    }

    public function create(ImplementingOffice $implementingOffice){
        $this->pro_data['implementing_office'] = add_my_array(Auth::user()->visibleImplementingOffices()->get()->pluck('title', 'id'), "Any");
        //piu office ko child office pani implementing office select garne list ma chahiyeko le..
        $this->pro_data['implementing_offices_new_update'] = Auth::user()->visibleImplementingOffices()->with('child')->get();
        $this->pro_data['es'] = Engineer::orderBy('name', 'asc')->get();
        $this->pro_data['designations'] = Manpower::all()->pluck('title', 'id');
        return view('admin.engineer.create', $this->pro_data);
    }

    public function store(Request $request){
        $request->validate([
            'implementing_office' => 'required',
        ]);
        $status = $request->get('status') == 'on'?1:0;
        $image = $request->file('image');
        $fileName = NULL;
        if($image != null && $image != '' && !empty($image) && isset($image)){
            $fileName = makeImageName($image->getClientOriginalName());
            $this->uploadImage('engineer', $fileName, $image);
        }

        $email=$request->get('email')==null?md5(rand().md5(date('"Y-m-d h:i:sa"'))).'@enginnerdudbc.com':$request->get('email');
        $userStatus=$request->get('email')==null?1 :0;

        $user=User::Create([
            'name'=>$request->get('name'),
            'email'=>$email,
            'password'=>bcrypt($request->mobile),
            'token'=>md5(md5($request->get('email')).md5(date('"Y-m-d h:i:sa"'))),
            'access'=>'Limited',
            'implementing_office_id'=>$request->get('implementing_office'),
            'created_by'=> Auth::user()->id,
            'updated_by'=> Auth::user()->id,
            'status'=>$userStatus,
            'type_flag'=>5,
        ]);


        $engineer=Engineer::create([
           'name'=>$request->get('name'),
           'home_address'=>$request->get('home_address'),
           'email'=>$request->get('email'),
           'mobile'=>$request->get('mobile'),
           'type'=>$request->get('type'),
           'phone'=>$request->get('phone'),
           'image'=>$fileName,
           'implementing_office'=>$request->get('implementing_office'),
           'status'=>$status,
           'user_id'=>$user->id
        ]);
        $description=logDescriptionCreate($request->all());
        storeLog(null,$description,0 ,'Engineer');

        session()->flash('store_success_info','" Engineer name '.$request->get('name').'"');
        return redirect()->route('engineers.edit',$engineer->slug);
    }

    public function show(){

    }

    public function edit(ImplementingOffice $implementingOffice, Engineer $engineer){
        $this->pro_data['engineer'] = $engineer;
        $this->pro_data['implementing_office'] = add_my_array(Auth::user()->visibleImplementingOffices()->get()->pluck('title', 'id'), "Any");
        //piu office ko child office pani implementing office select garne list ma chahiyeko le..
        $this->pro_data['implementing_offices_new_update'] = Auth::user()->visibleImplementingOffices()->with('child')->get();
        $this->pro_data['es'] = Engineer::orderBy('name', 'asc')->get();
        $this->pro_data['category'] = $engineer->category == 0 ? 1 : $engineer->category;
        $this->pro_data['designations'] = Manpower::all()->pluck('title', 'id');

        return view('admin.engineer.edit', $this->pro_data);
    }

    public function update(Request $request, Engineer $engineer){
        $status = $request->get('status') == 'on'?1:0;
        $image = $request->file('image');
        $fileName = $oldFileName = $engineer->image;
        if($image != null && $image != '' && !empty($image) && isset($image)){
            $fileName = makeImageName($image->getClientOriginalName());
            $this->uploadImage('engineer', $fileName, $image, $oldFileName);
        }
        $oldEngineer=$engineer->toArray();

        $engineer->fill([
            'name'=>$request->get('name'),
            'home_address'=>$request->get('home_address'),
            'email'=>$request->get('email'),
            'mobile'=>$request->get('mobile'),
            'type'=>$request->get('type'),
            'phone'=>$request->get('phone'),
            'image'=>$fileName,
            'implementing_office'=>$request->get('implementing_office'),
            'status'=>$status
        ])->save();

        $email=$engineer->myUser->email;
        $status=0;
        if($request->has('email')){
            $status=1;
            $email=$request->get('email');
        }
        $engineer->myUser->fill([
            'name'=>$request->get('name'),
            'email'=>$email,
            'updated_by'=> Auth::user()->id,
            'status'=>$status,
            'phone'=>$request->get('phone')
        ])->save();


        $change=logDescriptionUpdate($engineer, $oldEngineer);
        if($change!=false){
            storeLog(null,$change,1 ,'Engineer');
        }

        session()->flash('update_success_info','" Engineer named '.$request->get('name').'"');
        return redirect()->route('engineers.edit',$engineer->slug);
    }

    public function destroy(Engineer $engineer){
        $name = $engineer->name;
        if(Input::get('hardDelete')){
            $this->removeFiles('engineer',$engineer->image);
            $engineer->forceDelete();
        }else{
            $engineer->delete();
        }
        storeLog(null,$name,2 ,'Engineer');
        session()->flash('delete_success_info','" Engineer named '.$name.'"');
        return redirect()->route('engineers.index');
    }
}
