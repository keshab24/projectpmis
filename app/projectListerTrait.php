<?php
namespace PMIS;

trait projectListerTrait {

    function projectContractor($projects){

        $contractorIdWithGivenEmail = User::where('pro_users.email',$this->user->email)
            ->join('pro_contractor','pro_users.id','=','pro_contractor.user_id')
            ->select('pro_contractor.id')->get()->pluck('id')->toArray();

        /*
         *
         * This is a weird requirement where
         * I have to find multiple contractor with same email
         * and list the project of all the listed contractor
         *
         * */

        $project=$projects->whereHas('procurement',function ($procurement)use($contractorIdWithGivenEmail){//individial projets
            $procurement->wherein('contractor',$contractorIdWithGivenEmail);
        });
        $data = [];

        if(isset($data['jv'])){ // to check if he is requesting for jv or single projetcs.. just a flag so unset it
            unset($data['jv']); 
            $project= $data['projects']->whereHas('procurement',function ($procurement){
                $procurement->whereHas('JointVenture',function($jv){
                    $jv->whereHas('contractors',function($contractor){//his project with any other jv
                        $contractor->where('contractor_id',$this->user->Contractor->id);
                    });
                });
            });
        }
        return $project;
    }

    function projectEngineer($projects){
        $engineer_id = $this->user->Engineer->id;
        $project=$projects->whereHas('Engineers', function($engineer)use ($engineer_id){
            $engineer->where('pro_engineers.id',$engineer_id);
        });
        return $project;
    }

    function projectAuthorizedPerson($projects){
        $authorizedPersonId= $this->user->AuthorizedPerson->id;
        $project=$projects->whereHas('authorizedPersons', function($authorizedPersons)use ($authorizedPersonId){
            $authorizedPersons->where('pro_authorized_person.id',$authorizedPersonId);
        });
        return $project;
    }

    function projectMonitoringOffice($projects){
        $projects = $projects->where('monitoring_id',$this->user->implementing_office_id);
        return $projects;
    }

    function projectImplementingOffice($projects){
        $projects = $projects->where('implementing_id',$this->user->implementing_office_id);
        return $projects;
    }

    function projectExternal($projects){
        $projects = $projects->whereIn('monitoring_id',$this->user->externalUserMonitoring->pluck('id'));
        return $projects;
    }
    function projectDistrict($projects) {
        $projects = $projects->where('district_id', $this->user->district_id);
        return $projects;
    }
}
