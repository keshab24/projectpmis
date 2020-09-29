<?php
namespace PMIS;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityLog extends Model {

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
            'name_eng' => 10,
        ]
    ];

    protected $table = 'pro_activity_log';
    protected $with = ['ActivityLogFiles'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title','description','url','submitted_date','type','implementing_office_id','project_id','created_by','updated_by','status','block_id', 'daily_progress_id'];
    protected $dates = ['deleted_at'];

    protected $casts = [
        'implementing_office_id' => 'integer',
        'project_id' => 'integer',
        'status' => 'integer',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function project(){
        return $this->belongsTo('PMIS\Project','project_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function implementing_office(){
        return $this->belongsTo('PMIS\ImplementingOffice','project_id');
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

    public function ActivityLogFiles(){
        return $this->hasMany('PMIS\ActivityLogFiles','activity_log');
    }

}