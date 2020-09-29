<?php
namespace PMIS\Http\Controllers\Admin;

use PMIS\Device;

trait SelectUserTrait {

    public function __construct(){
        parent::__construct();
        $data['user_id']=array();
        $data['devices']=array();
    }


    function userFromImplementingOffice($project){
        $setting = $project->projectSettings()->where('fy',session()->get('pro_fiscal_year'))->first();
        if(!$setting){
            $setting = $project;
        }
        $this->data['user_id']=$setting->implementing_office->users()->active()->whereTypeFlag(2)->pluck('id')->toArray();
        $this->data['phone_number']=$setting->implementing_office->users()->active()->whereTypeFlag(2)->pluck('phone')->toArray();
        $this->data['email']=$setting->implementing_office->users()->active()->whereTypeFlag(2)->pluck('email')->toArray();
        $this->getDevices($this->data['user_id']);
        return $this->data;
    }

    function userFromMonitoringOffice($project){
        $setting = $project->projectSettings()->where('fy',session()->get('pro_fiscal_year'))->first();
        if(!$setting){
            $setting = $project;
        }
        $this->data['user_id']=$setting->monitoringOffice->users()->active()->whereTypeFlag(3)->pluck('id')->toArray();
        $this->data['phone_number']=$setting->monitoringOffice->users()->active()->whereTypeFlag(3)->pluck('phone')->toArray();
        $this->data['email']=$setting->monitoringOffice->users()->active()->whereTypeFlag(3)->pluck('email')->toArray();
        $this->getDevices($this->data['user_id']);
        return $this->data;

    }

    function userFromEngineer($project){
        $user=array();
        $phone=array();
        foreach ($project->Engineers as $engineer){
            if($engineer->myUser){
                $user[]=$engineer->myUser->id;
                $phone[]=$engineer->myUser->phone;
            }
        }
        $this->data['user_id']=$user;
        $this->data['phone_number']=$phone;
        $this->getDevices($this->data['user_id']);
        return $this->data;

    }

    function userFromAuthorizedPerson($project){
        $user=array();
        $email=array();
        $phone=array();
        foreach ($project->authorizedPersons as $authorizedPerson){
            $user[]=$authorizedPerson->myUser->id;
            $email[]=$authorizedPerson->myUser->email;
            $phone[]=$authorizedPerson->myUser->phone;
        }
        $this->data['user_id']=$user;
        $this->data['email']=$email;
        $this->data['phone_number']=$phone;
        $this->getDevices($this->data['user_id']);
        return $this->data;
    }

    function userFromContractor($project){
        if($project->procurement->Contractor){
            $this->data['user_id']=$project->procurement->Contractor->myUser()->active()->pluck('id')->toArray();
            $this->data['email']=$project->procurement->Contractor->myUser()->active()->pluck('email')->toArray();
            $this->data['phone_number']=$project->procurement->Contractor->myUser()->active()->pluck('phone')->toArray();
        }elseif($project->procurement->JointVenture){
            $user=array();
            $phone=array();
            $email=array();
            foreach ($project->procurement->JointVenture->contractors as $contractor){
                $user[]=$contractor->myUser->id;
                $phone[]=$contractor->myUser->phone;
                $email[]=$contractor->myUser->email;
            }
            $this->data['user_id']=$user;
            $this->data['phone_number']=$phone;
            $this->data['email']=$email;
        }
        $this->getDevices($this->data['user_id']);
        return $this->data;
    }

    function userFromExternal($project){
        $setting = $project->projectSettings()->where('fy',session()->get('pro_fiscal_year'))->first();
        if(!$setting){
            $setting = $project;
        }
        $this->data['user_id']=$setting->monitoringOffice->externalUserRegistered->pluck('id')->toArray();
        $this->data['phone_number']=$setting->monitoringOffice->externalUserRegistered->pluck('phone')->toArray();
        $this->data['email']=$setting->monitoringOffice->externalUserRegistered->pluck('email')->toArray();
        $this->getDevices($this->data['user_id']);
        return $this->data;
    }


    function userFromAccountant($project){
        $setting = $project->projectSettings()->where('fy',session()->get('pro_fiscal_year'))->first();
        if(!$setting){
            $setting = $project;
        }
        $this->data['user_id']=$setting->implementing_office->users()->active()->whereTypeFlag(8)->pluck('id')->toArray();
        $this->data['email']=$setting->implementing_office->users()->active()->whereTypeFlag(8)->pluck('email')->toArray();
        $this->data['phone_number']=$setting->implementing_office->users()->active()->whereTypeFlag(8)->pluck('phone')->toArray();
        $this->getDevices($this->data['user_id']);
        return $this->data;

    }

    function userFromAdministration($project){
        $setting = $project->projectSettings()->where('fy',session()->get('pro_fiscal_year'))->first();
        if(!$setting){
            $setting = $project;
        }
        $this->data['user_id']=$setting->implementing_office->users()->active()->whereTypeFlag(9)->pluck('id')->toArray();
        $this->data['email']=$setting->implementing_office->users()->active()->whereTypeFlag(9)->pluck('email')->toArray();
        $this->data['phone_number']=$setting->implementing_office->users()->active()->whereTypeFlag(9)->pluck('phone')->toArray();
        $this->getDevices($this->data['user_id']);
        return $this->data;
    }


    function getDevices($usersArray){
        $this->data['devices']=Device::whereHas('user',function ($user)use($usersArray){
            $user->whereIn('id',$usersArray);
        })->pluck('fcmtoken')->toArray();
    }

    function userFromIds($usersArray){
        $this->getDevices($usersArray);
        return $this->data['devices'];
    }


    function preventFromPublic($noticeObject){
        // in case this notice is not published to any user, i want this to be seen by root so that public cannot read it !!!
        if($noticeObject->visibleToUser()->count()==0){
            $noticeObject->visibleToUser()->sync([4]);
        }
        return true;
    }
}
