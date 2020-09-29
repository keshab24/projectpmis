<?php namespace PMIS;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;

class District extends Model
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

    protected $table = 'pro_districts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['slug', 'name', 'name_eng', 'description', 'description_eng', 'image', 'coordinates', 'zone_id', 'state_id', 'status', ''];
    protected $dates = ['deleted_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses()
    {
        return $this->hasMany('PMIS\Address', 'district_id');
    }

    public function projects()
    {
        return $this->hasMany('PMIS\Project', 'district_id');
    }

    public function implementing_office()
    {
        return $this->belongsTo('PMIS\District', 'division_code');
    }


    public function zone()
    {
        return $this->belongsTo('PMIS\Zone', 'zone_id');
    }

    public function State()
    {
        return $this->belongsTo('PMIS\State', 'state_id');
    }

    public function implementingOffice()
    {
        return $this->belongsToMany('PMIS\ImplementingOffice', 'pro_implementingoffice_pivot', 'district_id', 'implementing_office_id')->withTimestamps()->withPivot('fy_id');
    }

    // public function rates(){
    //     return $this->hasMany(Rate::class);   
    // }
        

}