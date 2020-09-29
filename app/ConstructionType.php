<?php
namespace PMIS;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConstructionType extends Model {

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
                'build_from' => 'name_eng',
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
            'name' => 10,
            'name_eng' => 10,
        ]
    ];

    protected $table = 'pro_construction_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['slug','name','name_eng','status','monitoring_office_id'];
    protected $dates = ['deleted_at'];

    public function progressTracks(){
        return $this->hasMany('PMIS\ProgressTrack','progress_type');
    }

}