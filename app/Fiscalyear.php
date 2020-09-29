<?php

namespace PMIS;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fiscalyear extends Model
{

    use SearchableTrait;
    use SoftDeletes;
    use Sluggable;

    /**
     * @var array
     */
    /*protected static function boot()
    {
        //    static::addGlobalScope(new ScopeFiscalYear());
    }*/

    public function sluggable()
    {
        return [
            'slug' => [
                'build_from' => 'fy',
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
            'fy' => 10,
        ]
    ];

    protected $table = 'pro_fiscalyears';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['slug', 'fy', 'status'];
    protected $dates = ['deleted_at'];


    public static function fyToday()
    {
        return session()->get('pro_fiscal_year');
    }
}