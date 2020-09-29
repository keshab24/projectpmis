<?php namespace PMIS;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectGroup extends Model
{

    use Sluggable;

    /**
     * @var array
     */

    public function sluggable()
    {
        return [
            'slug' => [
                'build_from' => 'name',
                'save_to' => 'slug',
                'on_update' => true,
                'slug_nep' => false,
                'include_trashed' => true,
            ]
        ];
    }

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
            'name_nep' => 10,
            'description' => 10,
            'description_nep' => 10
        ]
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pro_group_category';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['slug', 'name', 'name_nep', 'description', 'description_nep', 'group_category_id', 'budget_topic', 'type', 'order', 'level', 'show_on_menu', 'layout_id', 'show_on_home_page', 'page_category_order', 'created_by', 'updated_by', 'status', 'monitoring_office_id'];
    protected $dates = ['deleted_at'];

    protected $casts = [
        'group_category_id' => 'integer',
        'monitoring_office_id' => 'integer',
        'layout_id' => 'integer',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function child()
    {
        return $this->hasMany('PMIS\ProjectGroup', 'group_category_id');
    }


    public function projects()
    {
        return $this->hasMany('PMIS\Project', 'project_group_id');
    }

    public function projectCount()
    {


        return $this->hasOne('PMIS\Project', 'project_group_id')->selectRaw('project_group_id, count(*) as count')->where('show_on_running', '1')->groupBy('project_group_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo('PMIS\ProjectGroup', 'group_category_id');
    }

    public function budgetTopic()
    {
        return $this->belongsTo('PMIS\BudgetTopic', 'budget_topic');
    }

    public function monitoringOffice()
    {
        return $this->belongsTo('PMIS\ImplementingOffice', 'monitoring_office_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function creator()
    {
        return $this->belongsTo('PMIS\User', 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function updator()
    {
        return $this->belongsTo('PMIS\User', 'updated_by');
    }

}
