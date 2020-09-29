<?php
namespace PMIS;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImplementingOffice extends Model {

    use SearchableTrait;
    use SoftDeletes;
    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'name' => 15,
            'name_eng' => 15,
        ]
    ];

    protected $table = 'pro_implementing_offices';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['isMonitoring','office_code','name','name_eng','piu_code','description','description_eng','district_id','address','phone','email','chief','mobile','coordinates','is_physical_office','parent_id','bank_name','branch_address','account_no','is_last_node','level','order','created_by','updated_by','status','after_update_of_office_structure'];
    protected $dates = ['deleted_at'];



    public function getNameAttribute($name){
        if(!request()->has('token'))
        if (strpos($name, ',') !== false)
            return explode(',',$name)[1];
        return trim($name);
    }

    public function getNameEngAttribute($name){
        if(!request()->has('token'))
        if (strpos($name, ',') !== false)
            return explode(',',$name)[1];
        return $name;
    }

    protected $casts = [
        'district_id' => 'integer',
        'parent_id' => 'integer',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function child(){
        return $this->hasMany('PMIS\ImplementingOffice','parent_id');
    }

    public function cheifs(){
        return $this->belongsToMany('PMIS\Cheif','pro_implementingoffice_cheif_pivot','implementing_office_id','cheif_id')->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projects(){
        return $this->hasMany('PMIS\Project','implementing_office_id');
    }
    public function projectSetting(){
        return $this->hasMany('PMIS\ProjectSetting','implementing_id');
    }



    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function releases(){
        return $this->hasMany('PMIS\Release','implementing_office_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activityLogs(){
        return $this->hasMany('PMIS\ActivityLog','implementing_office_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function parent(){
        return $this->belongsTo('PMIS\ImplementingOffice','parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function district(){
        return $this->belongsTo('PMIS\District','district_id');
    }

    public function Users(){
        return $this->hasMany('PMIS\User','implementing_office_id');
    }

    public function UserUnderThisMonitoringOf(){
        return $this->hasMany('PMIS\User','implementing_office_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function creator(){
        return $this->belongsTo('PMIS\User','created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function updator(){
        return $this->belongsTo('PMIS\User','updated_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function implementingSeesMonitor(){
        return $this->belongsToMany('PMIS\ImplementingOffice','monitoringseesimplementing','implementing_office_id','monitoring_office_id');
    }


    public function externalUserRegistered(){
        return $this->belongsToMany('PMIS\User','pro_external_visible_offices','monitoring_office_id','user_id');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */

    public function MonitorSeesImplementing(){
        return $this->belongsToMany('PMIS\ImplementingOffice','monitoringseesimplementing','monitoring_office_id','implementing_office_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function districts(){
        return $this->belongsToMany('PMIS\District','pro_implementingoffice_pivot','implementing_office_id','district_id')->withTimestamps()->withPivot('fy_id');
    }

    public function budgetTopics()
    {
        return $this->hasMany('PMIS\BudgetTopic','monitoring_office_id');
    }


    public static function boot()
    {
        parent::boot();
        self::created(function($model){
            $officeCode = $model->district_id == 0?$model->id: sprintf("%'.02d", $model->district_id );
            $model->office_code = $officeCode;
            $model->piu_code = $model->id;
            $model->update();
        });
    }

    public function getIsNewTownAttribute()
    {
        return $this->id == 343;
    }

    public function getTitleAttribute()
    {
        return $this->getOriginal()['name'];
    }

    public function getTitleEngAttribute()
    {
        return $this->getOriginal()['name_eng'];
    }

    public function scopeAfterUpdateLogic($query)
    {
        $query->where('after_update_of_office_structure',1);
    }

    public function getIsAyojanaTypeAttribute()
    {
        //ntp matrai ayojana type ko office ho ahile lai.
        return $this->is_new_town;
    }

}