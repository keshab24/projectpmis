<?php
namespace PMIS;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model {

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

        protected $table = 'pro_payment';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['payment_method','cheque_office','payment_detail','cheque_no','file_path','fy_id','release_date','created_by','updated_by','status'];
    protected $dates = ['deleted_at'];

    protected $casts = [
        'fy_id' => 'integer',
        'expenditure_topic_id' => 'integer',
        'budget_topic_id' => 'integer',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function transaction(){
        return $this->belongsToMany('PMIS\FundTransaction','pro_transaction_payment_pivot_table','payment_id','transaction_id')->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function implementing_office(){
        return $this->belongsTo('PMIS\ImplementingOffice','project_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function release(){
        return $this->hasMany('PMIS\Release','payment_id');
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
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function fiscal_year(){
        return $this->belongsTo('PMIS\FiscalYear','fy_id');
    }


}