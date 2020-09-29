<?php

namespace PMIS;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class FundStore extends Model
{

    //
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
                'save_to' => 'slug',
                'on_update' => false,
                'slug_nep' => false,
                'max_length' => 80,
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

    protected $table = 'pro_fund_store';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['slug', 'name', 'name_eng', 'type', 'description', 'description_eng', 'account_name', 'account_no', 'branch', 'address', 'created_by', 'updated_by', 'status'];
    protected $dates = ['deleted_at'];


    public function transaction()
    {
        return $this->hasMany('PMIS\FundTransaction', 'fund_store_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo('PMIS\User', 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updator()
    {
        return $this->belongsTo('PMIS\User', 'updated_by');
    }

}