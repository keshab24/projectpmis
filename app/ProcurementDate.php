<?php
namespace PMIS;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProcurementDate extends Model {

    /**
     * Searchable rules.
     *
     * @var array
     */


    protected $table = 'pro_procurement_dates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['company_name','reference_number','company_branch','amount','project_id','file','start_date','end_date','type','created_by','updated_by'];

    protected $casts = [
        'project_id' => 'integer',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function project(){
        return $this->belongsTo('PMIS\Project','project_id');
    }


}