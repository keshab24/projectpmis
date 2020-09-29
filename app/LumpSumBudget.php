<?php namespace PMIS;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class LumpSumBudget extends Model{

    use SearchableTrait;
    use SoftDeletes;

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
    'columns' => [
        'running_capital' => 10,
        'pro_expenditure_topics.expenditure_head' => 5,
        'pro_expenditure_topics.expenditure_head_eng' => 5,
        'pro_expenditure_topics.expenditure_topic_num' => 5,
        'pro_budget_topics.expenditure_head' => 5,
        'pro_budget_topics.expenditure_head_eng' => 5,
        'pro_budget_topics.expenditure_topic_num' => 2,
        'pro_fiscalyears.fy' => 2
    ],
    'joins'=>[
        'pro_expenditure_topics'=>['pro_lump_sum_budget.expenditure_topic_id','pro_expenditure_topics.id'],
        'pro_budget_topics'=>['pro_lump_sum_budget.budget_topic_id','pro_budget_topics.id'],
        'pro_fiscalyears'=>['pro_lump_sum_budget.fy_id','pro_fiscalyears.id']
    ]
];

    protected $table = 'pro_lump_sum_budget';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['running_capital','total_budget','gon','loan','grants','direct_payments','fy_id','expenditure_topic_id','budget_topic_id','status'];
    protected $dates = ['deleted_at'];

    protected $casts = [
        'fy_id' => 'integer',
        'expenditure_topic_id' => 'integer',
        'budget_topic_id' => 'integer',
    ];

    public function expendituretopic(){
        return $this->belongsTo('PMIS\ExpenditureTopic','expenditure_topic_id');
    }
    public function budgettopic(){
        return $this->belongsTo('PMIS\BudgetTopic','budget_topic_id');
    }
    public function fiscalyear(){
        return $this->belongsTo('PMIS\Fiscalyear','fy_id');
    }

}