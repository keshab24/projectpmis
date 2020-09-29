<?php namespace PMIS;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Engineer extends Model {

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
                'include_trashed' => true,
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
        'name' => 20,
        'email' => 10,
        'type' => 10,
    ]
];

    protected $table = 'pro_engineers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['slug','name','home_address','email','category','type','mobile','phone','office_address','office_phone','fax','office_email','implementing_office','user_id','image','status'];
    protected $dates = ['deleted_at'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Projects(){
        return $this->belongsToMany('PMIS\Project','pro_project_engineers','engineer_id','project_id');
    }

    public function myUser(){
        return $this->belongsTo('PMIS\User','user_id');
    }

    public function implementingOffice()
    {
        return $this->belongsTo(ImplementingOffice::class, 'implementing_office');
    }

    public function getTokenAttribute()
    {
        return $this->myUser->token;
    }
}