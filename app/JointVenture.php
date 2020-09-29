<?php namespace PMIS;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class JointVenture extends Model {

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
                'on_update'  => true,
                'slug_nep'  => false,
                'max_length'  => 80,
                'include_trashed'  => true,
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

    protected $table = 'pro_joint_ventures';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['slug','name','address','nep_name','description','email','fax','mobile','created_by','updated_by','status'];
    protected $dates = ['deleted_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator(){
        return $this->belongsTo('PMIS\User','created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updator(){
        return $this->belongsTo('PMIS\User','updated_by');
    }

    public function contractors(){
        return $this->belongsToMany('PMIS\Contractor','pro_contractor_jvs','jv_id','contractor_id')->withTimestamps();
    }

    public function procurements()
    {
        return $this->hasMany('PMIS\Procurement','joint_venture');
    }


}