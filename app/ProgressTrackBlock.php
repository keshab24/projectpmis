<?php namespace PMIS;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProgressTrackBlock extends Model
{

    use SearchableTrait;
    use SoftDeletes;
    use Sluggable;

    /**
     * @var array
     */

    public function sluggable()
    {
        return [
            'slug' => [
                'build_from' => 'progress',
                'save_to' => 'slug',
                'on_update' => false,
                'slug_nep' => true,
                'max_length' => 80,
                'includeTrashed' => true,
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
            'progress_type' => 10,
            'progress_eng' => 10,
            'progress' => 10,
        ]
    ];

    protected $table = 'progress_track_blocks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['project_id','block_id','storey_area', 'slug', 'progress_type', 'progress', 'progress_eng', 'physical_percentage', 'monitoring_office_id', 'status'];
    protected $dates = ['deleted_at'];


    public function constructiontype()
    {
        return $this->belongsTo('PMIS\ConstructionType', 'progress_type');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function progress()
    {
        return $this->belongsToMany('PMIS\Progress', 'pro_pt_pivot', 'pt_id', 'progress_id')->withTimestamps();
    }

    public function block(){
        return $this->belongsTo('PMIS\ProjectBlocks','block_id');
    }
}