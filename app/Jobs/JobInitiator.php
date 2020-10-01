<?php
/**
 * Created by PhpStorm.
 * User: Lankuri
 * Date: 12/07/2018
 * Time: 10:06
 */

namespace PMIS\Jobs;


use Illuminate\Foundation\Bus\DispatchesJobs;
use PMIS\Http\Controllers\Admin\SelectUserTrait;

class JobInitiator
{
    use SelectUserTrait,DispatchesJobs;

    protected $notification,$theme;
    public function __construct($notification,$theme)
    {
        $this->notification = $notification; // type of notificaiton
        $this->theme = $theme; // name space of job
    }

    public function putOnQueue($noticeObject , $project)
    {
        //dd();
        $smsClass = "PMIS\Jobs\\".$this->theme."\\".$this->theme."Sms";

        foreach ($this->notification->UserType as $userTypes) {
            $function = 'userFrom' . $userTypes->type;
            $data = $this->$function($project);

            foreach ($data['phone_number'] as $number) {
                if(class_exists($smsClass)){
                    dispatch(new $smsClass($noticeObject->description, $number))->onQueue('sms');
                }else{
                    //dd("sms not found");
                    \Log::info("class". $smsClass ."not found while dispatching job");
                }
            }

        }
        $this->preventFromPublic($noticeObject);
    }
}


