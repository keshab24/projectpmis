<?php
namespace PMIS;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;

class BudgetTopic extends Model {

    use SearchableTrait;
    use SoftDeletes;
    use Sluggable;
    use BudgetTopicTrait;
    /**
     * @var array
     */

    public function sluggable()
    {
        return [
            'slug' => [
                'build_from' => 'budget_head',
                'save_to'    => 'slug',
                'on_update'  => false,
                'slug_nep' => false,
                'max_length'  => 80,
            ]
        ];
    }


    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'budget_head' => 10,
            'budget_head_eng' => 10,
            'budget_topic_num'  =>10,
        ]
    ];

    protected $table = 'pro_budget_topics';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['slug','budget_head','budget_topic_num','budget_head_eng','priority','status','monitoring_office_id','budget_head_old','budget_head_eng_old','budget_topic_num_old','priority_old'];
    protected $dates = ['deleted_at'];

    public function projects(){
        return $this->hasMany('PMIS\Project','budget_topic_id');
    }

    public function projectGroup(){
        return $this->hasMany('PMIS\ProjectGroup','budget_topic');
    }

    protected $casts = [
        'budget_topic_id' => 'integer',
        'monitoring_office_id' => 'integer',
    ];

    public function lumpSumBudget(){
        return $this->hasMany('PMIS\LumpSumBudget','budget_topic_id');
    }

    public function monitoringOffice(){
        return $this->belongsTo('PMIS\ImplementingOffice','monitoring_office_id');
    }

    public function projectCount($fy_id)
    {
        $implementing_offices_id = [];
        if(auth()->user()->type_flag == 10){
            return count($this->projects()->where('show_on_running','1')->where('fy_id', '<=', $fy_id)->where('district_id', auth()->user()->district_id)->get());
        }elseif(auth()->user()->type_flag == 7){
            foreach(auth()->user()->externalUserMonitoring as $monitoring){
                foreach($monitoring->MonitorSeesImplementing as $imp){
                    $implementing_offices_id[] = $imp->id;
                }
            }
        }elseif(auth()->user()->type_flag == 2){
            $implementing_offices_id = [auth()->user()->implementing_office_id];
        }elseif(auth()->user()->type_flag == 3){
            $implementing_offices_id = auth()->user()->implementingOffice->MonitorSeesImplementing->pluck('id');
        }
//        return count($this->projects()->where('show_on_running','1')->where('fy_id', '<=', $fy_id)->whereIn('implementing_office_id', $implementing_offices_id)->get());
        return count(Project::where('show_on_running','1')->whereHas('projectSettings', function($setting) use ($fy_id, $implementing_offices_id){
            $setting->where('fy', $fy_id)->whereIn('implementing_id', $implementing_offices_id)->where('budget_id', $this->id);
        })->get());
    }
}