<?php
namespace PMIS;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncomeTopic extends Model {

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
                'slug_nep' => true,
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
        ]
    ];

    protected $table = 'pro_income_topics';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['slug','parent_id','order','level','name','name_eng','code','status','created_by','updated_by'];
    protected $dates = ['deleted_at'];

    protected $casts = [
        'parent_id' => 'integer',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function child(){
        return $this->hasMany('PMIS\IncomeTopic','parent_id');
    }


}