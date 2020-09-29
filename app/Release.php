<?php
namespace PMIS;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Release extends Model {

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

    protected $table = 'pro_release';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['project_id','payment_id','release_date','amount','fy_id','created_by','updated_by','status'];
    protected $dates = ['deleted_at'];

    protected $casts = [
        'project_id' => 'integer',
        'payment_id' => 'integer',
        'fy_id' => 'integer',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function project(){
        return $this->belongsTo('PMIS\Project','project_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function payment(){
        return $this->belongsToMany('PMIS\Payment','pro_payment','release_id','payment_id')->withTimestamps();
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


}