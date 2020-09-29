<?php
namespace PMIS\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use PMIS\District;
use PMIS\Http\Controllers\AdminBaseController;
use PMIS\Http\Requests\CreateUserRequest;
use PMIS\ImplementingOffice;
use PMIS\User;
use PMIS\UserTypeFlag;

class UserController extends AdminBaseController {
    /**
     * @var array
     */
    protected $pro_data;
    protected $user;

    /**
     * @param User $user
     */
    public function __construct(User $user){
        parent::__construct();
        $this->pro_data['pageTitle'] = 'User List';
        $this->pro_data['order']='asc';
        $this->user = $user;
        $this->pro_data['implementing_offices'] = add_my_array(ImplementingOffice::whereStatus(1)->where('level','>',0)->where('IsMonitoring',0)->pluck('name_eng','id'),'Choose Office');
        $this->middleware(function ($request, $next) {
            if(Auth::user()->implementing_office_id!=1){
                $officeVisible=array();
                if(Auth::User()->implementingOffice->isMonitoring==1){
                    $officeVisible=Auth::User()->implementingOffice->MonitorSeesImplementing->pluck('id')->toArray();
                }
                array_push($officeVisible,Auth::User()->implementingOffice->id);
                $this->pro_data['implementing_offices'] = ImplementingOffice::whereIn('id',$officeVisible)->pluck('name_eng','id');
            }
            return $next($request);
        });


    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $this->checkAccessLevel(1);
        $this->commonSearchData();
        $this->pro_data['implementing_offices'] = add_my_array(Auth::user()->visibleImplementingOffices()->pluck('name_eng', 'id'), "Any");
        //piu office ko child office pani implementing office select garne list ma chahiyeko le..
        $this->pro_data['implementing_offices_new_update'] = Auth::user()->visibleImplementingOffices()->with('child')->get();
        $this->pro_data['type_flags'] = UserTypeFlag::where('id','>',1)->has('Users')->pluck('type','id');
        if(Auth::User()->implementingOffice->isMonitoring==1){

            $listFromImplementing=Auth::User()->implementingOffice->MonitorSeesImplementing->pluck('id')->toArray();
            $listFromMonitoring=Auth::User()->implementing_office_id;
            array_push($listFromImplementing,$listFromMonitoring);
//            $this->pro_data['users']=$this->user->whereIn('type_flag',[2,3])->whereIn('implementing_office_id',$listFromImplementing);
            $this->pro_data['users']=$this->user->whereIn('type_flag',[2,3,5])->whereIn('implementing_office_id',$listFromImplementing);
        }elseif(Auth::User()->id==4){
            $this->pro_data['users']=$this->user->whereTypeFlag(7);
        }
        else{
            $this->pro_data['users']=$this->user->whereIn('implementing_office_id',[Auth::User()->implementing_office_id]);
        }

        if(isset($_GET['contractor'])){ // filter users
            $this->pro_data['users']=$this->user->has('Contractor');
        }
        
        if(isset($_GET['implementing_office']) && $_GET['implementing_office']){
            $this->pro_data['users']=$this->pro_data['users']->where('implementing_office_id', $_GET['implementing_office']);
        }
       
        if(isset($_GET['type_flag']) && $_GET['type_flag']){
            $this->pro_data['users']=$this->pro_data['users']->where('type_flag', $_GET['type_flag']);
        }
        
        
        if(isset($_GET['search']) && $_GET['search']){
            $this->pro_data['users'] = $this->user->search($_GET['search'],null,true);
            $this->pro_data['other_data'] = '&search='.$_GET['search'];
            
            if($this->pro_data['users']->get()->isEmpty()){
                $this->pro_data['not_found']= 'Sorry! couldn\'t find your content. Please try with another keywords.';
            }
        }
        if(isset($_GET['trashes'])){
            $this->pro_data['users'] = User::onlyTrashed();
        }
        
        
        if(!isset($_GET['orderBy'])){
            $this->pro_data['users'] = $this->pro_data['users']->orderBy('id','desc');
        }
        if(!(request()->has('all-users') && request()->get('all-users'))){

            $visible_implementing_offices = $this->pro_data['user_info']->visibleImplementingOffices()->pluck('id');

            $this->pro_data['users']=$this->pro_data['users']->whereHas('implementingOffice', function($imp_office) use ($visible_implementing_offices){
                $imp_office->where(function($q) use ($visible_implementing_offices){
                    $q->whereIn('id',$visible_implementing_offices)
                    ->orWhereIn('parent_id', $visible_implementing_offices);
                });
            });
        }

        
        $this->pro_data['users'] = $this->paginateCollection($this->pro_data['users']->get(), $this->pro_data['limit'] ?? 50);
        $this->pro_data['trashes_no'] = User::onlyTrashed()->count();
        $this->pro_data['user_info'] = $this->user_info;
        return view('admin.user.index',$this->pro_data);
    }

    /**
     * @param ImplementingOffice $implementingOffice
     * @return \Illuminate\View\View
     */

    public function create(){
        abort_unless(optional(optional($this->user_info)->implementingOffice)->isMonitoring, 403, 'Not Authorized');
        $this->checkAccessLevel(1);

        $user_types = UserTypeFlag::where('id', '<>', 1)->get()->pluck('type', 'id');

        if(Auth::User()->id==4){
            $this->pro_data['implementing_offices'] = add_my_array(ImplementingOffice::whereStatus(1)->where('level','>',0)->pluck('name_eng','id'),'Choose Office');
            $this->pro_data['monitoring_offices'] = ImplementingOffice::whereStatus(1)->where('isMonitoring',1)->get();
        }

        //
        $this->pro_data['implementing_offices'] = $this->user_info->visibleImplementingOffices()->with('child')->get();

        //
        $ds = District::all()->pluck('name_eng', 'id');
        $ds[0] = "Select One";
        $user_types[0] = "Select One...";
        $this->pro_data['districts'] = $ds;
        $this->pro_data['user_types'] = $user_types;
        return view('admin.user.create',$this->pro_data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateUserRequest $request\
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateUserRequest $request)
    {
        abort_unless(optional(optional($this->user_info)->implementingOffice)->isMonitoring, 403, 'Not Authorized');
        $this->checkAccessLevel(1);
        $status = $request->get('status')=='on'?0:1;
        $image = $request->file('image');
        $fileName = NULL;
        if($image != null && $image != '' && !empty($image) && isset($image)){
            $fileName = makeImageName($image->getClientOriginalName());
            $this->uploadImage('users', $fileName, $image);
        }
        $implementing_office_id=$request->get('implementing_office_id');
        if($request->has('type_flag')){ // this is false if the user edited his own account
            $typeFlag=$request->type_flag; // user created by implementing office !!!!
            if($typeFlag==7){
                $implementing_office_id=null;
            }

        }else{
            $typeFlag=ImplementingOffice::find($request->get('implementing_office_id'))->isMonitoring==1?3:2; // user made by monitoring office
        }

        $typeFlag = $request->get('type_flag');

        $access = $request->get('access');
        $user_save = $this->user->create([
            'name'=>$request->get('name'),
            'email'=>$request->get('email'),
            'token'=>md5(md5($request->get('email')).md5(date('"Y-m-d h:i:sa"'))),
            'password'=>bcrypt($request->get('password')),
            'phone'=>$request->get('phone'),
            'access'=>$access,
            'description'=>$request->get('description'),
            'implementing_office_id'=>$implementing_office_id,
            'district_id'=>$request->get('district_id'),
            'image'=>$fileName,
            'stock_notification'=>1,
            'created_by'=>$this->user_info->id,
            'updated_by'=>$this->user_info->id,
            'status'=>$status,
            'type_flag'=>$typeFlag,
        ]);
        if($typeFlag==7){
            $moId=array();
            foreach (explode(',',$request->monitoring_offices) as $monitoringOffice){
                if($monitoringOffice){
                    $moId[]=ImplementingOffice::whereName(trim($monitoringOffice))->first()->id;
                }
            }
            $user_save->externalUserMonitoring()->sync($moId);
        }

        session()->flash('store_success_info','" user named '.$request->get('name').'"');
        if($this->user_info->access == 'Limited'){
            return redirect()->route('user.show', $user_save->slug);
        }
        return redirect()->route('user.index');
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return Response
     */
    public function show(User $user)
    {
        if($this->user_info->access != 'Root Level'){
            if($user->website_id != $this->user_info->website_id){abort(403);}
            $this->checkAccessLevel(2, $user->id);
        }
        $this->pro_data['user'] = $user;
        return view('admin.user.show', $this->pro_data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @param ImplementingOffice $implementingOffice
     * @return Response
     */
    public function edit(User $user)
    {
        abort_unless(optional(optional($this->user_info)->implementingOffice)->isMonitoring, 403, 'Not Authorized');
        $this->pro_data['implementing_offices'] = add_my_array($this->user_info->visibleImplementingOffices()->get()->pluck('title_eng','id'),'Choose Office');
//        $this->pro_data['implementing_offices'] = add_my_array(ImplementingOffice::whereStatus(1)->where('level','>',0)->pluck('name_eng','id'),'Choose Office');
        $this->pro_data['monitoring_offices'] = ImplementingOffice::whereStatus(1)->where('isMonitoring','1')->get();

        if($this->user_info->access != 'Root Level'){
            $this->checkAccessLevel(2, $user->id);
        }

        $user_types = UserTypeFlag::where('id', '<>', 1)->get()->pluck('type', 'id');

        $this->pro_data['access_levels'] = ['Top Level'=>'Top Level','Limited'=>'Limited'];
        if($this->user_info->access === 'Limited') {
            $this->pro_data['access_levels'] = ['Limited'=>'Limited'];
        }

        $ds = District::all()->pluck('name_eng', 'id');
        $ds[0] = "Select One";
        $user_types[0] = "Select One...";
        $this->pro_data['user_types'] = $user_types;
        $this->pro_data['districts'] = $ds;

        $this->pro_data['user_edit'] = $user;
        return view('admin.user.edit', $this->pro_data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CreateUserRequest $request
     * @param User $user
     * @return Response
     */
    public function update(CreateUserRequest $request, User $user)
    {
        abort_unless(optional(optional($this->user_info)->implementingOffice)->isMonitoring, 403, 'Not Authorized');
        $implementing_office_id = $user->implementing_office_id;
        $access = $user->access;

        if(Auth::user()->implementingOffice->isMonitoring == 1 && $implementing_office_id) {
            $implementing_office_id = $request->get('implementing_office_id');
        }

        if(Auth::user()->access == 'Root Level' || Auth::user()->access =='Top Level') {
            $access = $request->get('access');
        }

        $status = $request->get('status')=='on'?0:1;
        if($this->user_info->access == 'Root Level'){
            $website_id = $request->get('website_id');
        }else{
            $website_id = $this->user_info->website_id;
        }
        $image = $request->file('image');
        $fileName = $oldFileName = $user->image;
        if($image != null && $image != '' && !empty($image) && isset($image)){
            $fileName = makeImageName($image->getClientOriginalName());
            $this->uploadImage('users', $fileName, $image, $oldFileName);
        }

        if($request->get('password') == ''){
            $password = $user->password;
        }else{
            $password = bcrypt($request->get('password'));
        }


        if($request->has('type_flag')){
            $typeFlag=$request->type_flag; // user created by implementing office !!!!
            if($typeFlag==7){
                $implementing_office_id=null;
            }
        }else{
            if($user->implementing_office_id){
                $typeFlag=ImplementingOffice::find($request->get('implementing_office_id'))->isMonitoring==1?3:2; // user made by monitoring office
            }else{
                $typeFlag=$user->type_flag;
            }
        }

   //     $type_flag = $request->get('type_flag');
        if($implementing_office_id !== "0"){

            $user->fill([
                'implementing_office_id'=>$implementing_office_id,
            ]);
        }
        $user->fill([
            'name'=>$request->get('name'),
            'email'=>$request->get('email'),
            'phone'=>$request->get('phone'),
            'password'=>$password,
            'access'=>$access,
            'description'=>$request->get('description'),
            'district_id'=>$request->get('district_id'),
            'image'=>$fileName,
            'stock_notification'=>1,
            'type_flag'=>$typeFlag,
            'website_id'=>$website_id,
            'updated_by'=>$this->user_info->id,
            'status'=>$status,
        ])->save();

        if($typeFlag==7){
            $moId=array();
            foreach (explode(',',$request->monitoring_offices) as $monitoringOffice){
                if($monitoringOffice){
                    $moId[]=ImplementingOffice::whereName(trim($monitoringOffice))->first()->id;
                }
            }
            $user->externalUserMonitoring()->sync($moId);
        }

        if($user->Contractor){
            $user->Contractor->fill([
                'email'=>$request->get('email')
            ]);
            $user->Contractor->save();

        }

        if($user->Engineer){
            $user->Engineer->fill([
                'email'=>$request->get('email'),
                'phone'=>$request->get('phone')
            ]);
            $user->Engineer->save();
        }

//        if($user->AuthorizedPerson){
//            $user->AuthorizedPerson->fill([
//                'email'=>$request->get('email'),
//                'phone'=>$request->get('phone')
//            ]);
//            $user->AuthorizedPerson->save();
//
//        }



        session()->flash('update_success_info','" user named '.$request->get('name').'"');
        return redirect()->route('user.edit',$user->slug);
    }

    /**
     * @param User $user
     * @return bool|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function destroy(User $user)
    {
        //if($user->access === 'Root Level'){abort(403);}
        if($this->user_info->access != 'Root Level'){
            $this->checkAccessLevel(1);
        }

        if($user->newsCreator == null){
            $this->realDestroyer($user);
        }else{
            if(Input::get('hardDelete')){
                if(Input::get('hardDelete') == 'vtx'){
                    $this->realDestroyer($user);
                }
            }else{
                $this->pro_data['confirm_data'] = $user->newsCreator;
                $this->pro_data['account'] = $user;
                return $this->showConfirmDetail();
            }

        }
        return redirect()->route('user.index');
    }

    /**
     * @param $user
     * @return bool
     */
    public function realDestroyer($user){
        if($user->access === 'Root Level'){abort(403);}
        if($this->user_info->access != 'Root Level'){
            if($user->website_id != $this->user_info->website_id){abort(403);}
            $this->checkAccessLevel(1);
        }

        $name = $user->getAttribute('name');
        if(Input::get('hardDelete')){
            $user->forceDelete();
        }else{
            $user->delete();
        }
        storeLog(null,$name,2 ,'User');

        session()->flash('delete_success_info','" user named '.$name.'"');
        return redirect()->route('user.index');
    }

    public function passwordChange(User $user){
        abort_unless($user->id == $this->user_info->id, 403);
        $this->pro_data['user'] = $user;
        return view('admin.user.password', $this->pro_data);
    }

    public function passwordChangeStore(Request $request, User $user){
        abort_unless($user->id == $this->user_info->id, 403);
        $request->validate([
            'password' => 'required|confirmed'
        ]);
        $password = $user->password;
        if (request()->get('password')) {
            $password = bcrypt(request()->get('password'));
        }
        $user->fill([
            'password' => $password,
        ])->save();
        session()->flash('update_success_info','" user named '.$user->name.' password successfully changed"');
        return redirect()->back();
    }
}
