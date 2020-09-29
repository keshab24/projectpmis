<?php
/**
 * Created by PhpStorm.
 * User: Lankuri
 * Date: 01/07/2018
 * Time: 14:57
 */

namespace PMIS\Jobs\GlobalJobHandler;


use PMIS\Http\Controllers\Admin\MailController;

trait emailJobHandler
{
    public function doJob()
    {
        $mail = new MailController();
        if(!$mail->sendEmail($this->email, $this->message,$this->mailTitle)){
            throw new \Exception("Failed Sending email");
        }
    }
}