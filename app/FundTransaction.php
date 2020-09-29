<?php

namespace PMIS;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class FundTransaction extends Model {

    //
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
        ]
    ];

    protected $table = 'pro_fund_transaction';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['expenditure_topic_id','fund_store_id','type','cheque_no','cheque_name','cheque_type','voucher_no','deposited_by','implementing_office_id','amount','image','description','created_by','updated_by','status'];
    protected $dates = ['deleted_at'];

    protected $casts = [
        'expenditure_topic_id' => 'integer',
        'fund_store_id' => 'integer',
        'implementing_office_id' => 'integer',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function expense(){
        return $this->belongsToMany('PMIS\Expense','pro_transaction_expense_pivot_table','transaction_id','expense_id')->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function income(){
        return $this->belongsToMany('PMIS\Income','pro_transaction_income_pivot','transaction_id','income_id')->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function payment(){
        return $this->belongsToMany('PMIS\Expense','pro_transaction_payment_pivot','transaction_id','payment_id')->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fund_store(){
        return $this->belongsTo('PMIS\FundStore','fund_store_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator(){
        return $this->belongsTo('PMIS\User','created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updator(){
        return $this->belongsTo('PMIS\User','updated_by');
    }

}