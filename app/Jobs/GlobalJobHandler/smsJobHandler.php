<?php
/**
 * Created by PhpStorm.
 * User: Lankuri
 * Date: 01/07/2018
 * Time: 14:57
 */

namespace PMIS\Jobs\GlobalJobHandler;


use Exception;
use PMIS\Http\Controllers\Admin\MessageService;
use Illuminate\Support\Facades\Log;

trait smsJobHandler
{
    public function doJob()
    {
            Log::info($this->mobile.' => '.$this->message);
            $messageService = new MessageService();
            if($messageService->sendSms($this->mobile,$this->message) == false){
                throw new Exception("Failed Sending SMS");
            }
    }
}