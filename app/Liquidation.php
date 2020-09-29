<?php
namespace PMIS;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Liquidation extends Model {

    /**
     * Searchable rules.
     *
     * @var array
     */

    protected $table = 'pro_liquidated_damage';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['amount','collected_date','status','file','remarks','project_code'];

    protected $casts = [
        'project_code' => 'integer',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function project(){
        return $this->belongsTo('PMIS\Project','project_code');
    }

}