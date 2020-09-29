<?php namespace PMIS;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
class Progress extends Model{

    use SearchableTrait;
    use SoftDeletes;

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'project_code' => 10, // yo field change garnu parxa
        ]
    ];

    protected  $with=['fy'];

    protected $table = 'pro_progresses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['project_remarks','bill_exp','cont_exp','current_physical_progress','project_code','fy_id','month_id','status','pt_id'];
    protected $dates = ['deleted_at'];

    protected $casts = [
        'pt_id' => 'integer',
        'fy_id' => 'integer',
        'project_code' => 'integer',
        'month_id' => 'integer',
    ];


    public function getCurrentPhysicalProgressAttribute($value){
        return $this->fy_id>13?$value:'-';
    }

    public function getProjectRemarksAttribute($value){
        return $this->fy_id>13?$value:'-';
    }

    public function implementing_mode(){
        return $this->belongsTo('PMIS\ImplementingMode','implementing_mode_id');
    }

    public function month(){
        return $this->belongsTo('PMIS\Month','month_id');
    }

    public function fy(){
        return $this->belongsTo('PMIS\Fiscalyear','fy_id');
    }

    public function project(){
        return $this->belongsTo('PMIS\Project','project_code');
    }

    public function progressTrack(){
        return $this->belongsTo('PMIS\ProgressTrack','pt_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function progressTracks(){
        return $this->belongsToMany('PMIS\ProgressTrack','pro_pt_pivot','progress_id','pt_id')->withTimestamps();
    }






}