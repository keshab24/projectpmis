<?php
/**
 * Created by PhpStorm.
 * User: Lankuri
 * Date: 01/07/2018
 * Time: 14:57
 */

namespace PMIS\Jobs\GlobalJobHandler;


use Illuminate\Support\Facades\Log;
use PMIS\Http\Controllers\Admin\PushNotification;

trait notificationJobHandler
{

    public function doJob()
    {
        $notification = new PushNotification();
        $res  = $notification->notificationPush($this->device, $this->message);
        if(!$res){
            throw new \Exception("Failed Sending Notification");
        }
    }
}