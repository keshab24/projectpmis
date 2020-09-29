<?php
namespace PMIS\Http\Controllers\Admin;


use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use PMIS\AuthorizedPerson;
use PMIS\Contractor;
use PMIS\Http\Controllers\AdminBaseController;
use PMIS\User;


class AuthorizedPersonController extends AdminBaseController {
    protected $pro_data;
    protected $authorizedPerson;

    public function __construct(AuthorizedPerson $authorizedPerson){
        parent::__construct();
        $this->authorizedPerson = $authorizedPerson;
    }

    public function index(){
        $this->pro_data['orderBy'] = 'id';
        $this->pro_data['order'] = 'asc';
        $this->pro_data['other_data'] = '';
        $this->pro_data['default_search'] = '';
        $this->pro_data['authorized_person'] = $this->authorizedPerson;

        if(isset($_GET['search'])){
            $this->pro_data['default_search'] = $_GET['search'];
            $this->pro_data['authorized_person'] = $this->pro_data['authorized_person']->search($_GET['search']);
            $this->pro_data['other_data'] = '&search='.$_GET['search'];
            if($this->pro_data['authorized_person']->get()->isEmpty()){
                $this->pro_data['not_found']= 'Sorry! could not find your content. Please try with another keywords.';
            }
        }

        if(isset($_GET['orderBy']) && isset($_GET['order'])){
            $this->pro_data['orderBy'] = $_GET['orderBy'];
            $this->pro_data['order'] = (strtolower($_GET['order'])=='asc')?'desc':'asc';
        }

        if(isset($_GET['trashes'])){
            $this->pro_data['constructiontypes'] = AuthorizedPerson::onlyTrashed();
        }
        $this->pro_data['trashes_no'] = AuthorizedPerson::onlyTrashed()->count();

        $this->pro_data['authorized_persons'] = $this->pro_data['authorized_person']->orderBy($this->pro_data['orderBy'],$this->pro_data['order'])->paginate(10);

        return view('admin.authorized_person.index', $this->pro_data);
    }

    /**
     * @param Request $request
     * @param LumpSumBudget $lumpsumbudget
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function edit(AuthorizedPerson $authorizedPerson){
        $this->pro_data['authorized_person']=$authorizedPerson;
        return view('admin.authorized_person.edit', $this->pro_data);
    }

    public function create(){
        abort(404);
        return view('admin.authorized_person.create',$this->pro_data);
    }

    public function show()
    {
        abort(404);
    }

    public function store(Request $request)
    {
        $status = $request->get('status') == 'on'?1:0;
        $email=$request->get('email')==null?md5(rand().md5(date('"Y-m-d h:i:sa"'))).'@focalpersondudbc.com':$request->get('email');
        $userStatus=$request->get('email')==null?0:1;
        $user=User::Create([
            'name'=>$request->get('name'),
            'email'=>$email,
            'password'=>bcrypt('authorizedPerson'),
            'token'=>md5(md5($request->get('email')).md5(date('"Y-m-d h:i:sa"'))),
            'access'=>'Limited',
            'created_by'=> Auth::user()->id,
            'updated_by'=> Auth::user()->id,
            'status'=>$userStatus,
            'type_flag'=>6,
        ]);

        $authorized_person=AuthorizedPerson::create([
            'email'=>$request->get('email'),
            'name'=>$request->get('name'),
            'nep_name'=>$request->get('nep_name'),
            'phone'=>$request->get('phone'),
            'mobile'=>$request->get('mobile'),
            'fax'=>$request->get('fax'),
            'contractor_id'=>$request->get('contractor_id'),
            'created_by'=> Auth::user()->id,
            'updated_by'=> Auth::user()->id,
            'status'=>$status,
            'user_id'=>$user->id,
            'type'=>$request->get('type'),
        ]);
        Contractor::find($request->get('contractor_id'))->authorizedPerson()->attach([$authorized_person->id]);
        $description=logDescriptionCreate($request->all());
        storeLog(null,$description,0 ,'Contractor');

        session()->flash('store_success_info','" Authorized person name '.$request->get('name').'"');
        return redirect()->back();
    }

    public function update(Request $request,AuthorizedPerson $authorizedPerson){

        $oldAuthorizedPerson=$authorizedPerson->toArray();
        $status = $request->get('status') == 'on'?1:0;
        $authorizedPerson->fill([
            'email'=>$request->get('email'),
            'name'=>$request->get('name'),
            'nep_name'=>$request->get('nep_name'),
            'phone'=>$request->get('phone'),
            'mobile'=>$request->get('mobile'),
            'fax'=>$request->get('fax'),
            'updated_by'=> Auth::user()->id,
            'status'=>$status
        ])->save();

        $email=$authorizedPerson->myUser->email;
        $status=0;
        if($request->has('email')){
            $status=1;
            $email=$request->get('email');
        }
        $authorizedPerson->myUser->fill([
            'name'=>$request->get('name'),
            'email'=>$email,
            'phone'=>$request->get('phone'),
            'updated_by'=> Auth::user()->id,
            'status'=>$status
        ])->save();




        $change=logDescriptionUpdate($authorizedPerson, $oldAuthorizedPerson);
        if($change!=false){
            storeLog($authorizedPerson->id,$change,1 ,'Authorized Person');
        }
        session()->flash('update_success_info','" Authorized Person '.$request->get('name').'"');
        return redirect()->route('authorized_person.edit',$authorizedPerson->slug);
    }
}
