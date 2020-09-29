<?php

namespace PMIS;

use http\Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectSetting extends Model
{
//        per fiscal ko record chutai table ma maintan gareko.
//        future ma fy anusar project ko imp and monitoring (will also effect project code in this case) pani change huna sakne . tei anusar field manage gariyeko cha.
//        aru aunsa sakne field haru lai yei table ma add gardai lagne.

    use SoftDeletes;
    protected $table = 'pro_project_settings';
    protected $fillable = ['code', 'fy', 'budget_id', 'expenditure_id', 'implementing_id', 'monitoring_id', 'created_by', 'updated_by'];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function budget_topic()
    {
        return $this->belongsTo(BudgetTopic::class, 'budget_id');
    }

    public function expenditure_topic()
    {
        return $this->belongsTo('PMIS\ExpenditureTopic', 'expenditure_id');
    }

    public function implementing_office()
    {
        return $this->belongsTo('PMIS\ImplementingOffice', 'implementing_id');
    }



    public function monitoringOffice()
    {
        return $this->belongsTo('PMIS\ImplementingOffice', 'monitoring_id');
    }

    public function getProjectCodeAttribute()
    {
        //todo query optimization required
        $projectcode = $this->code;

        if ($this->monitoringOffice->id == 343) { // ntp . . mathi ko query le project ma monitoring office pani load garyo .. that loads other dependencies related to monitoring office
            return explode("-", $projectcode)[1] . '-' . explode("-", $projectcode)[2];// returns "f"
        }
        $implementing_office = auth()->user()->implementingOffice;
        // $implementing_office_id = auth()->user()->implementing_office_id;

        if(optional($implementing_office)->isMonitoring != "1"){
            return $projectcode;
        }else{
            return explode(":", $projectcode)[1];
        }
    }

    public function getProjectCodeWithFyAttribute()
    {
        return '('.$this->fiscal_year->fy . ') ' . $this->project_code;
    }

    public function fiscal_year()
    {
        return $this->belongsTo('PMIS\Fiscalyear', 'fy');
    }
}
