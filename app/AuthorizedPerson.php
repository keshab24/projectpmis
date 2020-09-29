<?php namespace PMIS;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuthorizedPerson extends Model
{

    use SearchableTrait;
    use SoftDeletes;
    use Sluggable;
    /**
     * @var array
     */
    protected $hidden = ['pivot'];

    public function sluggable()
    {
        return [
            'slug' => [
                'build_from' => 'name',
                'save_to'    => 'slug',
                'on_update' => true,
                'slug_nep' => false,
                'max_length' => 80,
                'include_trashed' => true,
            ]
        ];
    }

    protected $casts = [
        'project_id' => 'integer',
    ];

    protected $searchable = [
        'columns' => [
            'name' => 30,
        ]
    ];

    protected $table = 'pro_authorized_person';

    protected $fillable = ['email', 'slug', 'name', 'nep_name', 'phone', 'mobile', 'fax', 'user_id', 'created_by', 'updated_by', 'status','type'];

    protected $dates = ['deleted_at'];


    public function creator()
    {
        return $this->belongsTo('PMIS\User', 'created_by');
    }


    public function updator()
    {
        return $this->belongsTo('PMIS\User', 'updated_by');
    }

    public function contractor()
    {
        //return $this->belongsTo('PMIS\Contractor','contractor_id');
        return $this->belongsToMany('PMIS\Contractor', 'contractor_authorized_person', 'authorized_person_id', 'contractor_id');
    }

    public function project()
    {
        return $this->belongsToMany('PMIS\Project', 'pro_project_authorized_persons', 'project_id', 'authorized_person_id');

    }

    public function myUser()
    {
        return $this->belongsTo('PMIS\User', 'user_id');
    }

    public function getTokenAttribute()
    {
        return $this->myUser->token;
    }

    public function getNameDefaultAttribute()
    {
        if($this->nep_name)
            return $this->nep_name;
        return $this->name;
    }

}