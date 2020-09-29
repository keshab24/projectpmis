<?php

namespace PMIS;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Nicolaslopezj\Searchable\SearchableTrait;


class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;
    use Sluggable, Notifiable;

    /**
     * @var array
     */

    public function sluggable()
    {
        return [
            'slug' => [
                'build_from' => 'email',
                'save_to' => 'slug',
                'on_update' => true,
                'slug_nep' => false,
                'max_length' => 80,
            ]
        ];
    }

    use SearchableTrait;
    use SoftDeletes;
    /**
     * Searchable rules.
     *
     * @var array
     */

    protected $searchable = [
        'columns' => [
            'name' => 10,
            'email' => 10,
        ]
    ];
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pro_users';

    protected $casts = [
        'implementing_office_id' => 'integer',
        'contractor_id' => 'integer',
        'type_flag' => 'integer',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['slug', 'user_name', 'name', 'email', 'phone', 'verification_code', 'image', 'access', 'contractor_id', 'implementing_office_id', 'district_id', 'description', 'division_id', 'status', 'created_by', 'updated_by', 'password', 'api_token', 'type_flag'];
    protected $dates = ['deleted_at'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo('PMIS\User', 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function updator()
    {
        return $this->belongsTo('PMIS\User', 'updated_by');
    }


    public function implementingOffice()
    {
        return $this->belongsTo('PMIS\ImplementingOffice', 'implementing_office_id');
    }


    public function visibleNotices()
    {
        return $this->belongsToMany('PMIS\Notice', 'notice_listener', 'listener_id', 'notice_id');
    }

    public function devices()
    {
        return $this->hasMany('PMIS\Device', 'user_id');
    }

    public function Contractor()
    {
        return $this->hasOne('PMIS\Contractor', 'user_id');
    }


    public function Engineer()
    {
        return $this->hasOne('PMIS\Engineer', 'user_id');
    }

    public function AuthorizedPerson()
    {
        return $this->hasOne('PMIS\AuthorizedPerson', 'user_id');
    }

    public function typeFlag()
    {
        return $this->belongsTo('PMIS\UserTypeFlag', 'type_flag');
    }


    public function externalUserMonitoring()
    {
        return $this->belongsToMany('PMIS\ImplementingOffice', 'pro_external_visible_offices', 'user_id', 'monitoring_office_id');
    }

    public function district()
    {
        return $this->belongsTo('PMIS\District', 'district_id', 'id');
    }

    public function relatedLink()
    {
        $return = null;
        if ($this->Contractor) {
            $return['route'] = route('contractor.edit', $this->Contractor->slug);
            $return['related'] = "Contractor";
        }

        if ($this->Engineer) {
            $return['route'] = route('engineers.edit', $this->Engineer->slug);
            $return['related'] = "Engineer";
        }

//        if ($this->AuthorizedPerson) {
//            $return['route'] = route('authorized_person.edit', $this->AuthorizedPerson->slug);
//            $return['related'] = "Authorized Person";
//        }
        return $return;
    }

    public static function getTopLevelUser()
    {
        $user = User::where('access', 'Root Level')->first();
        return $user;
    }


    public function ConstructionType()
    {
        $q = new ConstructionType();
        if ($this->implementingOffice->isMonitoring == 1) {
            $q = $q->where('monitoring_office_id', $this->implementingOffice->id);
        } else {
            $arr = [];
            foreach ($this->implementingOffice->implementingSeesMonitor()->get() as $some) {
                array_push($arr, $some->id);
            }
            $q = $q->whereIn('monitoring_office_id', $arr);
        }
        return $q;
    }

    public function ConstructionLocatedArea()
    {
        $q = new ConstructionLocatedArea();
        if ($this->implementingOffice->isMonitoring == 1) {
            $q = $q->where('monitoring_office_id', $this->implementingOffice->id);
        } else {
            $arr = [];
            foreach ($this->implementingOffice->implementingSeesMonitor()->get() as $some) {
                array_push($arr, $some->id);
            }
            $q = $q->whereIn('monitoring_office_id', $arr);
        }
        return $q;
    }

    public function visibleProjects($not_running = null, $fy_filter = null)
    {
//        $projects = Project::class();
//        $projects = Project::where('pro_projects.id','<>','sadf');
        //first filtered by fy (on pro_project_settings table)  -- fy anusar project ko budget topic, expenditure change huna sakne huna le ..
//        per fiscal ko record chutai table ma maintan gareko.
//        future ma fy anusar project ko imp and monitoring (will also effect project code in this case) pani change huna sakne . tei anusar field manage gariyeko cha.
//        aru aunsa sakne field haru lai yei table ma add gardai lagne.

        if($not_running){
            $projects = Project::leftJoin('pro_project_settings', function($join){
                $join->on('pro_projects.id', '=', 'pro_project_settings.project_id')
//                ->where('pro_project_settings.fy','<=',session()->get('pro_fiscal_year'));
                ;
            })->select('pro_projects.*', 'pro_project_settings.id as setting_id', 'pro_project_settings.fy', 'pro_project_settings.budget_id', 'pro_project_settings.expenditure_id', 'pro_project_settings.implementing_id', 'pro_project_settings.monitoring_id', 'pro_project_settings.code', 'pro_project_settings.project_id');
            if ($this->implementing_office_id != 1) {
                if ($this->isDistrictType && $this->district_id > 0) {
                    $projects = $projects->where('district_id', $this->district_id);
                } else {
                    $projects1 = clone $projects;
                    $projects = $projects->where('pro_project_settings.monitoring_id', $this->implementing_office_id);
                    if ($projects->count() == 0) {
                        $projects = $projects1->where('pro_project_settings.implementing_id', $this->implementing_office_id);
                    }
                }
            }
            return $projects;
        }else{
            $projects = Project::join('pro_project_settings', 'pro_project_settings.project_id', '=', 'pro_projects.id')->select('pro_projects.*', 'pro_project_settings.id as setting_id', 'pro_project_settings.fy', 'pro_project_settings.budget_id', 'pro_project_settings.expenditure_id', 'pro_project_settings.implementing_id', 'pro_project_settings.monitoring_id', 'pro_project_settings.code');
        }
        if ($this->implementing_office_id != 1) {
            if ($this->isDistrictType && $this->district_id > 0) {
                $projects = $projects->where('district_id', $this->district_id);
            } else {
                $projects1 = clone $projects;
                $projects = $projects->where('pro_project_settings.monitoring_id', $this->implementing_office_id);
                if ($projects->count() == 0) {
                    $projects = $projects1->where('pro_project_settings.implementing_id', $this->implementing_office_id);
                }
            }
        }
        if($this->type_flag === 5) {
            $projects = $projects->whereHas('DailyProgressUsers', function ($q) {
                return $q->whereUserId($this->id);
            });
        }


        if($fy_filter)
            return $projects;

        return $projects->where('fy', session()->get('pro_fiscal_year'));
    }


    public function visibleImplementingOffices()
    {
        if ($this->type_flag == 7) {
            $io = new ImplementingOffice();
            $io = $io->whereHas('implementingSeesMonitor', function ($monitoring_office) {
                $monitoring_office->whereIn('monitoring_office_id', $this->externalUserMonitoring->pluck('id'));
            });
            return $io;
        } elseif ($this->implementing_office_id != 1) {
            if ($this->implementingOffice->isMonitoring == 1) {
                $io = $this->implementingOffice->MonitorSeesImplementing->pluck('id')->toArray();
                array_push($io, $this->implementingOffice->id);
                $io = ImplementingOffice::whereIn('id', $io);
            } else {
                $io = $this->implementingOffice();
            }
        } else {
            $io = new ImplementingOffice();
            $io = $io->whereStatus(1)->where('level', '>', 0)->where('IsMonitoring', 0);//
        }
        if ($this->implementing_office_id == 343 || $this->implementing_office_id === 410 ) //new town ma piu matra nabhayera sabai herna milne implementing office dekhaune ...
            return $io;
        return $io->where('after_update_of_office_structure', 1);//aru ma chai piu matra
    }

    public function visiblebudgetTopic()
    {
        $budget_topic = new BudgetTopic();
        $budget_topic = $budget_topic->where('monitoring_office_id', $this->implementing_office_id);
        if ($budget_topic->count() == 0) {
            $budget_topic = new BudgetTopic();
            $monitoringOffices = $this->implementingOffice->implementingSeesMonitor->pluck('id');
            $budget_topic = $budget_topic->whereIn('monitoring_office_id', $monitoringOffices);
        }
        return $budget_topic;
    }

    public function chatScreens()
    {
        return $this->hasMany(ChatScreen::class, 'user_id');
    }

    public function scopeActive($q)
    {
        return $q->where('status', 0);
    }

    public function visibleProjectsRegardelessOfFy()
    {
        $projects = Project::join('pro_project_settings', 'pro_project_settings.project_id', '=', 'pro_projects.id')->select('pro_projects.*', 'pro_project_settings.id as setting_id', 'pro_project_settings.fy', 'pro_project_settings.budget_id', 'pro_project_settings.expenditure_id', 'pro_project_settings.implementing_id', 'pro_project_settings.monitoring_id', 'pro_project_settings.code');
        if ($this->implementing_office_id != 1) {
            if ($this->district_id > 0) {
                $projects = $projects->where('district_id', $this->district_id);
            } else {
                $projects1 = clone $projects;
                $projects = $projects->where('pro_project_settings.monitoring_id', $this->implementing_office_id);
                if ($projects->count() == 0) {
                    $projects = $projects1->where('pro_project_settings.implementing_id', $this->implementing_office_id);
                }
            }
        }
        return $projects;
    }

    public function getIsDistrictTypeAttribute(){
        return $this->type_flag == 10;
    }

}