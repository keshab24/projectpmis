<?php namespace PMIS;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Zone extends Model {

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
                'build_from' => 'name',
                'save_to'    => 'slug',
                'on_update'  => false,
                'slug_nep'  => false,
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

    protected $table = 'pro_zones';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['slug','name','name_eng','description','description_eng','image','coordinates','region_id','status'];
    protected $dates = ['deleted_at'];

    protected $casts = [
        'region_id' => 'integer',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

}