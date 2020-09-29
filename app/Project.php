<?php

namespace PMIS;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;


class Project extends Model
{

    use SoftDeletes;
    use SearchableTrait;
    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        'columns' => [
//            'project_code' => 10,
            'pro_project_settings.code' => 10,
            'name' => 10,
            'name_eng' => 10,
        ],
        'join' => [
            'pro_project_settings as setting' => ['setting.project_id','pro_projects.id'],
        ]
    ];

    protected $table = 'pro_projects';
    protected $hidden = array('pivot');


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['show_on_running', 'lmbis_code', 'ho_fy', 'ho_date', 'payment_status', 'project_code', 'name', 'name_eng', 'division_code', 'expenditure_topic_id', 'budget_topic_id', 'construction_type_id', 'fy_id', 'implementing_office_id', 'monitoring_office_id', 'address', 'coordinates', 'description', 'district_id', 'approved_date', 'trimester_id', 'amendment_id', 'unit', 'quantity', 'status', 'liability', 'start_fy_id', 'project_group_id', 'nature_of_project_id', 'construction_located_area_id', 'headquarter', 'pr_code', 'story_area_unite', 'land_ownership', 'swamittwo', 'whose', 'kittanumber', 'shitnumber', 'soiltest', 'baringcapacity', 'bstype', 'rooftype', 'doorwindow', 'wall_type', 'design_type', 'project_status', 'completed_date', 'completed_fy', 'cancelled', 'cancelled_reason', 'cancelled_date', 'cancelled_remarks', 'cancelled_project_id'];
    protected $dates = ['deleted_at'];


    protected $casts = [
        'expenditure_topic_id' => 'integer',
        'division_code' => 'integer',
        'construction_type_id' => 'integer',
        'budget_topic_id' => 'integer',
        'fy_id' => 'integer',
        'implementing_office_id' => 'integer',
        'monitoring_office_id' => 'integer',
        'district_id' => 'integer',
        'start_fy_id' => 'integer',
        'project_group_id' => 'integer',
        'nature_of_project_id' => 'integer',
        'completed_fy' => 'integer',
        'ho_fy' => 'integer',
        'payment_status' => 'integer',
        'project_status' => 'integer',
    ];

    public function getContractBreakDateAttribute($value)
    {
        return dateBS($value);
    }

    public function getContractorAttribute()
    {
        return $this->procurement()->get()[0]->contractor;
    }

    public function procurement()
    {
        return $this->hasOne('PMIS\Procurement', 'project_code');
    }

    public function getProjectCodeAttribute($projectcode)
    {
        /*if($this->monitoringOffice->isAyojanaType){
            return explode("-", $projectcode)[1] . '-' . explode("-", $projectcode)[2];// returns "f"
        }*/
        if ($this->monitoring_office_id == 343) { // ntp . . mathi ko query le project ma monitoring office pani load garyo .. that loads other dependencies related to monitoring office
            return explode("-", $projectcode)[1] . '-' . explode("-", $projectcode)[2];// returns "f"
        }


            return explode(":", $projectcode)[1];
    }

    public function getNameCodeAttribute()
    {
        return $this->name . '- ' . $this->project_code;
    }

    public function division()
    {
        return $this->belongsTo('PMIS\Division', 'division_code');
    }

    public function fiscal_year()
    {
        return $this->belongsTo('PMIS\Fiscalyear', 'start_fy_id');
    }

    public function ho_fiscalYear()
    {
        return $this->belongsTo('PMIS\Fiscalyear', 'ho_fy');
    }

    public function completedFiscalYear()
    {
        return $this->belongsTo('PMIS\Fiscalyear', 'completed_fy');
    }

    public function implementing_office()
    {
        return $this->belongsTo('PMIS\ImplementingOffice', 'implementing_office_id');
    }

    public function implementing_office_setting()
    {
        return $this->belongsTo('PMIS\ImplementingOffice', 'implementing_id');
    }

    public function monitoringOffice()
    {
        return $this->belongsTo('PMIS\ImplementingOffice', 'monitoring_office_id');
    }

    public function district()
    {
        return $this->belongsTo('PMIS\District', 'district_id');
    }

    public function expenditure_topic()
    {
        return $this->belongsTo('PMIS\ExpenditureTopic', 'expenditure_topic_id');
    }

    public function budget_topic()
    {
        return $this->belongsTo('PMIS\BudgetTopic', 'budget_topic_id');
    }

    public function construction_type()
    {
        return $this->belongsTo('PMIS\ConstructionType', 'construction_type_id');
    }

    public function consturctionLocatedArea()
    {
        return $this->belongsTo('PMIS\ConstructionLocatedArea', 'construction_located_area_id');
    }

    public function allocation()
    {
        return $this->hasMany('PMIS\Allocation', 'project_code');
    }

    public function progresses()
    {
        return $this->hasMany('PMIS\Progress', 'project_code');
    }

    public function lastProgress()
    {
        return $this->hasOne('PMIS\Progress', 'project_code')->orderBy('id', 'desc')->limit(1);
    }
    
    public function lastProgressWithoutLimit()
    {
        return $this->hasOne('PMIS\Progress', 'project_code')->orderBy('id', 'desc');
    }

    public function releases()
    {
        return $this->hasMany('PMIS\Release', 'project_id');
    }

    public function activityLogs()
    {
        return $this->hasMany('PMIS\ActivityLog', 'project_id');
    }

    public function lastTimeExtension()
    {
        return $this->hasOne('PMIS\TimeExtension', 'project_code')->orderBy('id', 'desc');
    }

    public function timeExtensionCount()
    {
        return $this->hasOne('PMIS\TimeExtension', 'project_code')->selectRaw('project_code, count(*) as aggregate, count(*) as count')->groupBy('project_code');
    }

    public function revisedFrom()
    {
        return $this->belongsTo('PMIS\Project', 'cancelled_project_id');
    }

    public function variation()
    {
        return $this->hasMany('PMIS\Variation', 'project_code');
    }

    public function liquidation()
    {
        return $this->hasMany('PMIS\Liquidation', 'project_code');
    }

    public function group()
    {
        return $this->belongsTo('PMIS\ProjectGroup', 'project_group_id');
    }

    public function ProcurementDates()
    {
        return $this->hasMany('PMIS\ProcurementDate', 'project_id');
    }

    public function authorizedPersons()
    {
        return $this->belongsToMany('PMIS\AuthorizedPerson', 'pro_project_authorized_persons', 'project_id', 'authorized_person_id');
    }

    public function Engineers()
    {
        return $this->belongsToMany('PMIS\Engineer', 'pro_project_engineers', 'project_id', 'engineer_id');
    }

    public function DailyProgressUsers()
    {
        return $this->belongsToMany('PMIS\Engineer','daily_progress_users', 'project_id', 'engineer_id');
    }

    public function getEndDateAttribute()
    {
        $end_date = $this->procurement->completion_date;
        if ($this->timeExtension->count() > 0) {
            $end_date = $this->timeExtension()->orderBy('end_date', 'desc')->first()->end_date;
        }
        $end_date = dateAD($end_date);
        return $end_date;
    }

    public function timeExtension()
    {
        return $this->hasMany('PMIS\TimeExtension', 'project_code');
    }

    public function getIsContractBreakAttribute()
    {
        if ($this->getAttributes()['cancelled'] == 1)
            return true;
        return false;
    }

    public function getShowOnRunningAttribute()
    {
        if ($this->getAttributes()['show_on_running'] == 1 && $this->getAttributes()['cancelled'] == 0)
            return true;
        return false;
    }

    public function brokeProjectParent()
    {
        return $this->belongsTo(Project::class, 'cancelled_project_id');
    }

    public function brokeProjectContinue()
    {
        return $this->hasOne(Project::class, 'cancelled_project_id');
    }


    public function actualVariation()
    {
        return $this->hasMany('PMIS\Variation', 'project_code')->where('status', 0);
    }

    public function totalVariation()
    {
        $v = 0;
        foreach ($this->actualVariation as $variations) {
            $v = $v + $variations->amount;
        }
        return $v;
    }

    public function actualPriceEscalation()
    {
        return $this->hasMany('PMIS\Variation', 'project_code')->where('status', 1);
    }

    public function totalPriceEscalation()
    {
        $pe = 0;
        foreach ($this->actualPriceEscalation as $priceEscalation) {
            $pe = $pe + $priceEscalation->amount;
        }
        return $pe;
    }

    public function actualBonus()
    {
        return $this->hasMany('PMIS\Variation', 'project_code')->where('status', 2);
    }

    public function totalBonus()
    {
        $b = 0;
        foreach ($this->actualBonus as $bonus) {
            $b = $b + $bonus->amount;
        }
        return $b;
    }

    public function actualBudgetManagement()
    {
        return $this->hasMany('PMIS\Variation', 'project_code')->where('status', 3);
    }

    public function totalBudgetManagement()
    {
        $bm = 0;
        foreach ($this->actualBudgetManagement as $budgetManagement) {
            $bm = $bm + $budgetManagement->amount;
        }
        return $bm;
    }

    function projectCost()
    {
        $cost = 0;
        $variation = $this->totalVariation();
        $priceEscalation = $this->totalPriceEscalation();
        $bonus = $this->totalBonus();
        $budgetManagement = $this->totalBudgetManagement();
        $contingency = $this->procurement->contingency / 100;
        //2019-08-21 -- prabhat (in request by Ridesh Tamrakar)
        //kunai date dekhin contingency percentage fixed percentage (5) bata 4 percent gariyeko
        //so ko future requirement herdai. percentage chooseable and configurable from helpers gariyeko
        //for now its limited to 4 & 5 percent
        //5% case ma contingency included price ma vat lagaune
        //4% case ma pure cost ma matrai vat lagaune .. then (amount + contingency amount + vat amount )
        //after bidding (4% case ma)  (contract amount + contingency amount(स्वीकृत लईको 4 %) + vat amount of bidding amount (contract amount) )
        //4% case ma variation ma contingency lai matlab nagarne.. just like other factors (bonus, price escalation .. etc .. )
//        dd($this->procurement->whereProject_code(1172)->first()->con_est_amt_net);
        if ($this->procurement) {
            //contingency
            $contingency_amount = $this->procurement->con_est_amt_net * $contingency;
//            dd($this->procurement->contract_amount);
            if ($this->procurement->contract_amount == 0) {
                if ($this->procurement->con_est_amt_net == 0) {
                    $cost = $this->procurement->estimated_amount * 1000;
                } else {
                    if($this->procurement->contingency == 5){
                        $vatable_amount = $this->procurement->con_est_amt_net + $contingency_amount;
                        $vat_amount = $vatable_amount * 0.13;
                        $cost = round($vatable_amount + $vat_amount, 2);
                    }elseif($this->procurement->contingency == 4){
                        $vat_amount = $this->procurement->con_est_amt_net * 0.13;
                        $cost = round($this->procurement->con_est_amt_net + $contingency_amount + $vat_amount,2);
                    }
                }
            } else {
                //if($this->procurement->contingency == 5) {
                    $contingency_amount = $this->procurement->contract_amount * $contingency;
                    $vatable_amount = $this->procurement->contract_amount + $contingency_amount;
                    $vat_amount = $vatable_amount * 0.13;
                    $cost = round(($vatable_amount + $vat_amount) + (($variation + ($variation * $contingency)) * 1.13) + ($priceEscalation + $bonus + $budgetManagement), 2);
                /*}elseif($this->procurement->contingency == 4){
                    $vat_amount = $this->procurement->contract_amount * 0.13;
                    $cost = round(($this->procurement->contract_amount + $contingency_amount + $vat_amount) + ($variation * 1.13) + ($priceEscalation + $bonus + $budgetManagement), 2);
                }*/

            }
        }
        $cost = $cost / 1000;
        return $cost;
    }

    public function projectExpenditure()
    {
        $totalExpenditure = 0;
        foreach ($this->progresses()->where('fy_id', '>=', $this->start_fy_id)->get() as $index => $progress) {
            $totalExpenditure += $progress->bill_exp;
        }
        return $totalExpenditure;
    }


    public function getUnitAttribute($value)
    {
        return isset(unit()[$value]) ? unit()[$value] : "N/A";
    }

    public function getYearlyAimedBudgetAttribute()
    {
        $lastAllocation = $this->allocation()->orderBy('id', 'desc')->first();
        return $lastAllocation ? $lastAllocation->total_budget : 0;
    }


    public function projectLoad()
    {
////        $this-$this->totalBudget()/totalProgram->totaoBudget
        /*        return $this->totalBudget() == 0 || $this->projectCost() == 0 ? 0 : $this->totalBudget() / $this->projectCost() * 100;*/
    }

    public function yearlyAimedQuantity()
    {
        $this->yearly_aimed_budget == 0 || $this->projectCost() == 0 ? 0 : $this->yearly_aimed_budget() / $this->projectCost() * 100;
    }

    public function scopeProjectWithNullLmbisCode($q)
    {
        return $q->whereLmbisCode(null);
    }

    public function activityLogFiles()
    {
        $activity_logs_files = [];
        $project = $this;
        foreach (fieldsThatCanHaveActivityLogs() as $activity_type => $field_name) {
            $some = $project->activityLogs()->where('type', $activity_type)->get();
            if (count($some)) {
                foreach ($some as $activity) {
                    foreach ($activity->ActivityLogFiles as $file) {
                        $activity_logs_files[$field_name][] = [
                            'name' => $file->file_path,
                            'title' => $activity->title,
                            'type' => $activity->type,
                            'desc' => $activity->description,
                        ];
                    }
                }
            } else {
                $activity_logs_files[$field_name] = [];
            }
        }
        return $activity_logs_files;
    }

    public function chats()
    {
        return $this->hasMany(Chat::class, 'project_id');
    }

    public function allRelatedUsers()
    {
        $project_implementing_offices = $this->implementing_office;
        $project_monitoring_offices = $this->monitoringOffice;

        //including account, administration and district users of these offices
        $monitoring_users = $project_monitoring_offices->users()->active()->get();
        $implementing_users = $project_implementing_offices->users()->active()->get();
        //external users of these offices
        $monitoring_external_users = $project_monitoring_offices->externalUserRegistered()->active()->get();
        $implementing_external_users = $project_implementing_offices->externalUserRegistered()->active()->get();
        //contractor / contractors / jv
        $contractors = [];
        if ($this->procurement->Contractor) {
            $contractor = $this->procurement->Contractor;
            $contractor_users = $contractor->myUser;
            array_push($contractors, $contractor_users);
        }

        if ($this->procurement->JointVenture) {
            $jointVenture = $this->procurement->JointVenture;
            $contractor_users = $jointVenture->myUser;
            array_push($contractors, $contractor_users);
        }

        if ($this->procurement->contractors) {
            foreach ($this->procurement->contractors as $contractor) {
                $contractor_users = $contractor->myUser;
                array_push($contractors, $contractor_users);
            }
        }
        //project ko authorized(focal) person
        $authorized_persons = [];
        foreach ($this->authorizedPersons as $authorizedPerson) {
            array_push($authorized_persons, $authorizedPerson->myUser);
        }
        //contractor ends

        //site engineers
        $site_engineers = [];
        foreach ($this->Engineers as $engineer) {
            array_push($site_engineers, $engineer->myUser);
        }
        //dailyProgressUsers
        $dailyProgressUsers = [];
        foreach ($this->DailyProgressUsers as $dailyProgressUser) {
            array_push($dailyProgressUsers, $dailyProgressUser->myUser);
        }
        $all_related_users = $monitoring_users->merge($implementing_users->merge($monitoring_external_users->merge($implementing_external_users->merge($authorized_persons))));
        $all_related_users = $all_related_users->merge($site_engineers);
        $all_related_users = $all_related_users->merge($dailyProgressUsers);
        $all_related_users = $all_related_users->merge($contractors);
//        $all_related_users = array_merge($contractors, $monitoring_users, $implementing_users, $monitoring_external_users, $implementing_external_users, $authorized_persons, $site_engineers);
        return $all_related_users;
    }

    public function getAppNotificationsAttribute()
    {
        return $this->notifications()->apps()->get();
    }

    public function getChatNotificationsAttribute()
    {
        return $this->notifications()->chats()->get();
    }

    public function notifications()
    {
        $user = auth()->user();
        $last_seen = $user->chatScreens()->orderBy('last_seen', 'desc')->where('project_id', $this->id)->first();
        if ($last_seen) {
            $last_seen = $last_seen->last_seen;
        } else {
            $last_seen = date('Y-m-d H:i:s');
        }
        $notifications = NotificationListener::whereHas('notice', function ($notice) {
            $notice->where('project_id', $this->id);
        })->where('seen', 0)->where('updated_at', '<', $last_seen)->where('listener_id', $user->id);
        return $notifications;
    }

    public function getNotificationsCountAttribute()
    {
        return count($this->notifications()->apps()->get());
    }

    protected $appends = ['notifications_count'];

    public function notices()
    {
        return $this->hasMany(Notice::class, 'project_id');
    }

    public function scopeRunning($q)
    {
        return $q->where('show_on_running', '1');
    }

    public function getTimeOverrunAttribute()
    {
        $is_overrun = 'no';
        $time_overrun = $this->time_elapsed_string($this->end_date, true) . ' left.';
        if ($this->end_date && $this->end_date < date('Y-m-d')) {
            $is_overrun = 'yes';
            $time_overrun = $this->time_elapsed_string($this->end_date, true). ' ago.';
        }elseif ($this->procurement && !$this->procurement->contract_date) {
            $is_overrun = 'n/a';
            $time_overrun = 'सम्झौता नभएको / तथ्यांक प्रबिस्ट नगरिएको';
        }
        $new_p['time_overrun'] = $time_overrun;
        return ['is_overrun' => $is_overrun, 'time_overrun' => $time_overrun];
    }

    public function time_elapsed_string($datetime, $full = false)
    {
        $now = new \DateTime();
        $ago = new \DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
//        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
//            'w' => 'week',
            'd' => 'day',
//            'h' => 'hour',
//            'i' => 'minute',
//            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ' : ' ';
    }

    public function projectSettings()
    {
        return $this->hasMany(ProjectSetting::class,'project_id');
    }

    public function dailyProgress(){
        return $this->hasMany(DailyProgress::class,'project_id');
    }

    // public function apugKagajat(){
    //     return $this->hasOne(ApugKagajat::class,'project_id');
    // }

    public function myadThapDocument(){
        return $this->hasOne(MyadThapDocument::class,'project_id');
    }

}