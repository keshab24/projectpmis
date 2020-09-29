<?php
namespace PMIS;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class ActivityLogFiles extends Model {

    use SearchableTrait;
    /**
     * Searchable rules.
     *
     * @var array
     */

    protected $table = 'pro_activity_log_files';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['activity_log','file_path','type','description'];
    protected $dates = ['deleted_at'];

    protected $casts = [
        'activity_log' => 'integer',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function activityLog(){
        return $this->belongsTo('PMIS\ActivityLog','activity_log');
    }
}