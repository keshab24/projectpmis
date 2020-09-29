<?php
namespace PMIS;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class ProLog extends Model {
    use SearchableTrait;

    /**
     * Searchable rules.
     *
     * @var array
     */

    protected $searchable = [
        'columns' => [
            'pro_projects.project_code' => 15,
            'pro_users.name' => 15,
        ],
        'joins' => [
            'pro_projects' => ['pro_projects.id','pro_logs.project_id'],
            'pro_users' => ['pro_users.id','pro_logs.user_id'],
        ],

    ];

    protected $table = 'pro_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id','project_id','description','status','base_model'];

    public function project(){
        return $this->belongsTo('PMIS\Project','project_id');
    }

    protected $casts = [
        'project_id' => 'integer',
    ];


    public function user(){
        return $this->belongsTo('PMIS\User','user_id');
    }

}