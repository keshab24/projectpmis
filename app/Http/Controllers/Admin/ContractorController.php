<?php
namespace PMIS\Http\Controllers\Admin;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use PMIS\AuthorizedPerson;
use PMIS\Http\Controllers\AdminBaseController;
use PMIS\Contractor;
use PMIS\Http\Requests\CreateContractorRequest;
use PMIS\User;

class ContractorController extends AdminBaseController {
    protected $pro_data;
    protected $contractor;
    public function __construct(Contractor $contractor){
        parent::__construct();
        $this->contractor = $contractor;
        $this->middleware(function ($request, $next) {
            restrictEngineers($this->user_info->type_flag);
            $this->pro_data['title'] = 'Contractor';
            return $next($request);
        });
    }
    public function index(){
//        abort_unless(Auth::User()->implementingOffice->isMonitoring==1, 403);
        $this->pro_data['orderBy'] = 'id';
        $this->pro_data['order'] = 'desc';
        $this->pro_data['other_data'] = '';
        $this->pro_data['default_search'] = '';
        if(isset($_GET['orderBy']) && isset($_GET['order'])){
            $this->pro_data['orderBy'] = $_GET['orderBy'];
            $this->pro_data['order'] = (strtolower($_GET['order'])=='asc')?'desc':'asc';
        }

        if(isset($_GET['search'])){
            $this->pro_data['default_search'] = $_GET['search'];
            $this->pro_data['contractors'] = $this->contractor->search($_GET['search']);
            $this->pro_data['other_data'] = '&search='.$_GET['search'];
            if($this->pro_data['contractors']->get()->isEmpty()){
                $this->pro_data['not_found']= 'Sorry! could not find your content. Please try with another keywords.';
            }
        }else{
            $this->pro_data['contractors'] = $this->contractor->orderBy($this->pro_data['orderBy'],$this->pro_data['order']);
        }

        if(isset($_GET['trashes'])){
            $this->pro_data['contractors'] = $this->pro_data['contractors']->onlyTrashed();
        }

        $this->pro_data['trashes_no'] = $this->contractor->onlyTrashed()->count();
        $this->pro_data['contractors'] = $this->pro_data['contractors']->simplePaginate(100);

        return view('admin.contractor.index',$this->pro_data);
    }

    public function create(){
//        abort_unless(Auth::User()->implementingOffice->isMonitoring==1, 403);
        return view('admin.contractor.create', $this->pro_data);
    }

    public function store(CreateContractorRequest $request){
//        abort_unless(Auth::User()->implementingOffice->isMonitoring==1, 403);
        $image = $request->file('image');
        $imageName = '';
        if($image != null && $image != '' && !empty($image) && isset($image)){
            $imageName = makeImageName($image->getClientOriginalName());
            $this->uploadImage('contractor', $imageName, $image);
        }
        $filesToUpload = '';
        if($request->file('files')){
            foreach($request->file('files') as $file){
                if($file){
                    $files[] = $fileName = getFileName($file);
                    $file->move('public/contractorFiles',$fileName);
                }
            }
        }
        if(isset($files)){
            $filesToUpload = implode(',',$files);
        }
        $email=$request->get('email')==null?md5(rand().md5(date('"Y-m-d h:i:sa"'))).'@contractordudbc.com':$request->get('email');
        $status=$request->get('email')==null?0:1;
        $user=User::Create([
            'name'=>$request->get('name'),
            'email'=>$email,
            'password'=>bcrypt('123admin'),
            'token'=>md5(md5($request->get('email')).md5(date('"Y-m-d h:i:sa"'))),
            'access'=>'Limited',
            'created_by'=> Auth::user()->id,
            'updated_by'=> Auth::user()->id,
            'status'=>$status,
            'type_flag'=>4,
        ]);

        Contractor::create([
            'name'=>$request->get('name'),
            'nep_name'=>$request->get('nep_name'),
            'address'=>$request->get('address'),
            'email'=>$request->get('email'),
            'image'=>$imageName,
            'file'=> $filesToUpload,
            'description'=>$request->get('description'),
            'created_by'=> Auth::user()->id,
            'updated_by'=> Auth::user()->id,
            'user_id'=>$user->id,
            'status'=>1
        ]);
        $description=logDescriptionCreate($request->all());
        storeLog(null,$description,0 ,'Contractor');

        session()->flash('store_success_info','" contractor name '.$request->get('name').'"');
        return redirect()->route('contractor.create');
    }


    public function show(Contractor $contractor){
        $this->pro_data['authorizedPersons']=AuthorizedPerson::select(DB::raw('CONCAT(name, " - ",email," - ",mobile) AS full_name'), 'id')
            ->orderBy('name','asc')
            ->pluck('full_name', 'id');

        $this->pro_data['contractor'] = $contractor;
        return view('admin.contractor.show', $this->pro_data);
    }

    public function edit(Contractor $contractor){
//        abort_unless(Auth::User()->implementingOffice->isMonitoring==1, 403);
        $this->pro_data['contractor'] = $contractor;
        return view('admin.contractor.edit', $this->pro_data);
    }

    public function update(CreateContractorRequest $request, Contractor $contractor){
//        abort_unless(Auth::User()->implementingOffice->isMonitoring==1, 403);
        $status = $request->get('status') == 'on'?1:0;
        $image = $request->file('image');
        $imageName = $contractor->image;
        if($image != null && $image != '' && !empty($image) && isset($image)){
            $imageName = makeImageName($image->getClientOriginalName());
            $this->uploadImage('contractor', $imageName, $image);
        }

        $oldImages = $request->get('oldImages');
        $deleteMe = $request->get('deleteMe');
        $filesUploaded = '';
        foreach($oldImages as $index => $image){
            if($image != 0 ){
                if($deleteMe[$index] == 0)
                    if($filesUploaded == '')
                        $filesUploaded.=$image;
                    else
                        $filesUploaded.=",".$image;
                else{
                    if(file_exists(asset('public/contractorFiles/'.$image)))
                        unlink(asset('public/contractorFiles/'.$image));
                }

            }
        }
        if($request->has('files')){
            foreach($request->file('files') as $file){
                if($file){
                    $files[] = $fileName = getFileName($file);
                    $file->move('public/contractorFiles',$fileName);
                }
            }
        }

        if(isset($files)){
            if($filesUploaded == '')
                $filesUploaded.=implode(',',$files);
            else
                $filesUploaded.=",".implode(',',$files);
        }
        $oldContractor=$contractor->toArray();
        $contractor->fill([
            'name'=>$request->get('name'),
            'nep_name'=>$request->get('nep_name'),
            'address'=>$request->get('address'),
            'email'=>$request->get('email'),
            'image'=>$imageName,
            'file'=> $filesUploaded,
            'description'=>$request->get('description'),
            'created_by'=> Auth::user()->id,
            'updated_by'=> Auth::user()->id,
            'status'=>$status
        ])->save();
        $email=$request->get('email')==null?$contractor->slug.'@contractordudbc.com':$request->get('email');

        $status=$request->get('email')==null?0:1;

        try{
            $contractor->myUser->fill([
                'name'=>$request->get('name'),
                'email'=>$email,
                'updated_by'=> Auth::user()->id,
                'status'=>$status,
                'phone'=>$request->get('phone'),
            ])->save();
        }catch(QueryException $e){
            echo "<br>";
            echo "<h1>Your request terminated, please provide the log to admin</h1>";
            dd($e);


        }


        $change=logDescriptionUpdate($contractor, $oldContractor);
        if($change!=false){
            storeLog(null,$change,1 ,'Contractor');
        }
        session()->flash('update_success_info','" contractor named '.$request->get('name').'"');
        return redirect()->route('contractor.index');
    }

    public function destroy(Contractor $contractor){
        $name = $contractor->name;
        if(Input::get('hardDelete')){
            $contractor->forceDelete();
            $contractor->myUser->forceDelete();
        }else{
            $contractor->delete();
        }
        storeLog(null,$name,2 ,'Contractor');
        session()->flash('delete_success_info','" contractor named '.$name.'"');
        return redirect()->route('contractor.index');
    }
}
