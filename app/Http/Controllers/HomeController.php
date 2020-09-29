<?php
namespace PMIS\Http\Controllers;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PMIS\District;

use PMIS\Exports\Export\AnnualProgressReportExport;
use PMIS\Exports\Export\ProgressFormatExport;
use PMIS\Fiscalyear;

use PMIS\Http\Controllers\Admin\MailController;
use PMIS\ImplementingOffice;

use PMIS\Jobs\DeadLine\DeadLineEmail;
use PMIS\Notice;
use PMIS\Project;
use PMIS\User;


class HomeController extends BaseController {
    use DispatchesJobs;
    protected $pro_data;
    protected $notAccessTo;
    public function __construct()
	{
        parent::__construct();
        $unauthorized=androidUnAuthorized();
        //array_push($unauthorized,341);
        $this->notAccessTo=$unauthorized;
	}

    public function index(){
        return redirect()->route('admin.home');
    }

    public function apiLogin(Request $request) {
        $email=$request->get('email');
        $password=$request->get('password');
        $data['login']=false;
        $data['error']=true;
        $data['error_msg']='Email or Password did not match';

        $users = User::whereEmail($email)->get();
        foreach ($users as $user){
            if (Auth::attempt(['email' => $user->email, 'password' => $password])){
                    if(!$user->token){
                        $user->token = md5(md5($user->email).md5(date('"Y-m-d h:i:sa"')));
                        $user->save();
                    }
                    $data['login']=true;
                    $data['user']=Auth::User();
                    $data['title']=Auth::User()->name;
                    $data['is_monitoring']=optional(Auth::User()->implementingOffice()->select('id','isMonitoring')->first())->isMonitoring == '1';
                    $data['redirect_to_project']=$user->type_flag == 3 || $user->type_flag==2 || $user->type_flag==1 || $user->type_flag==7?false:true; //imple, monitoring and external user only allowed to select budget topics / fy year and view dashboard .. other users can only view projects related to them
                    $data['upload_current_progress']=$user->type_flag == 3 || $user->type_flag==2 ? true:false; //implementing and monitoring only allowed to upload current progress
                    $data['show_select_implementing_office']=$user->type_flag == 3 || $user->type_flag==7 ? true:false; //monitoring and external allowed to select implementing office on project list
                    $data['error']=false;
                    $data['error_msg']=null;
//                    TODO return values by checking..
                    $data['upload_project_activity']=$user->type_flag == 3 || $user->type_flag==2 || $user->type_flag==1 || $user->type_flag==7?true:false;
//                    $data['upload_project_activity']=false;
                    $data['view_documents']=$user->type_flag == 3 || $user->type_flag==2 || $user->type_flag==1 || $user->type_flag==7?true:false;
                    $data['add_authorized_person']=$user->type_flag == 3 || $user->type_flag==2 || $user->type_flag==1 || $user->type_flag==7?true:false;
                }
                Auth::logout(); // json instead session
                break;
        }
        return json_encode($data,200);
    }

}




