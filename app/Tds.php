<?php namespace PMIS;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tds extends Model {

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

    protected $table = 'pro_tds';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id','amount','expense_id','created_by','updated_by','status'];
    protected $dates = ['deleted_at'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses(){
        return $this->hasMany('PMIS\Address','district_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function transaction(){
        return $this->belongsToMany('PMIS\FundTransaction','pro_transaction_expense_pivot_table','expense_id','transaction_id')->withTimestamps();
    }

    public function expenditure_topic(){
        return $this->belongsTo('PMIS\ExpenditureTopic','expenditure_topic_id');
    }

    public function expense(){
        return $this->belongsTo('PMIS\Expense','expense_id');
    }

}