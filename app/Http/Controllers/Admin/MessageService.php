<?php
namespace PMIS\Http\Controllers\Admin;


use PMIS\Http\Controllers\AdminBaseController;


class MessageService extends AdminBaseController {

    public function sendSmsSparrow($numbers,$text){
        if($numbers){
/*            $api_url = "http://api.sparrowsms.com/v2/sms/?".
                http_build_query(array(
                    'token' => 'GOaM2lBeNHTMlc0pkwHZ',
                    'from'  => 'Demo',
                    'to'    => $numbers,
                    'text'  => $text));

            $response = file_get_contents($api_url);
            echo  $response;
            echo "<br/>";*/
        }
    }


    public function sendSms($number,$text){

        //return false;
           // $token = '4p26DQ7o6Y3L1E0E518XKw4UXrfl3AbcQKS'; //
            $token = 'OWNqwePpJ2CxAK66732AyY6iVZ7N0RBMzgO';
//        $token = 't6KF0uEAwSLHYUPi712O7ABSZv4goS1nFhE';

        $to = $number;
        $sender = 'SMSService';
        $message = $text;

        $content =
            '&token=' . rawurlencode($token) .
            '&to=' . rawurlencode($to) .
            '&sender=' . rawurlencode($sender) .
            '&message=' . rawurlencode($message);

        $logWriter = new LogWriter();
        $logWriter->write('sms', $this->matricsSms($content));
        return true;
    }

    function matricsSms($content)
    {
        // $ch = curl_init("http://sms.mstech.com.np/api/v3/sms?".$content);
        $ch = curl_init("http://beta.thesmscentral.com/api/v3/sms?".$content);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }
}
