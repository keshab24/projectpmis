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
        //Email push is in dummy mode and notification already pushed from another job... so only sms needed to be pushed from here .
//        $emailClass = "PMIS\Jobs\\".$this->theme."\\".$this->theme."Email";
//        $notificationClass = "PMIS\Jobs\\".$this->theme."\\".$this->theme."Notification";

        foreach ($this->notification->UserType as $userTypes) {
            $function = 'userFrom' . $userTypes->type;
            $data = $this->$function($project);
//            $noticeObject->visibleToUser()->attach($data['user_id']);

            foreach ($data['phone_number'] as $number) {
                if(class_exists($smsClass)){
                    dispatch(new $smsClass($noticeObject->description, $number))->onQueue('sms');
                }else{
                    //dd("sms not found");
                    \Log::info("class". $smsClass ."not found while dispatching job");
                }
            }
            /*foreach ($data['email'] as $index => $email) {
                if(class_exists($emailClass)){
                    dispatch(new $emailClass($noticeObject->description, $email))->onQueue('email');
                }else{
                    //dd("emmail not found");
                    Log::info("class". $emailClass ."not found while dispatching job");
                }
            }*/

           /* foreach ($data['devices'] as $deviceKey) {
                if(class_exists($notificationClass)){
                    dispatch(new $notificationClass($noticeObject, $deviceKey))->onQueue('notification');
                }else{
                    //dd("notification not found");
                    Log::info("class". $notificationClass ."not found while dispatching job");
                }
            }*/
//            $noticeObject->visibleToUser()->attach($data['user_id']);
        }
        $this->preventFromPublic($noticeObject);
    }
}


