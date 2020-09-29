<?php namespace PMIS;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contractor extends Model{

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
            'name' => 30,
        ]
    ];

    protected $table = 'pro_contractor';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email','slug','name','nep_name','address','image','file','description','created_by','updated_by','user_id','status','type'];
    protected $dates = ['deleted_at'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator(){
        return $this->belongsTo('PMIS\User','created_by');
    }

    public function myUser(){
        return $this->belongsTo('PMIS\User','user_id');
    }



    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updator(){
        return $this->belongsTo('PMIS\User','updated_by');
    }

    public function jointVentures(){
        return $this->belongsToMany('PMIS\JointVenture','pro_contractor_jvs','contractor_id','jv_id')->withTimestamps();
    }

    public function authorizedPerson(){
        //return $this->hasMany('PMIS\AuthorizedPerson','contractor_id');
        return $this->belongsToMany('PMIS\AuthorizedPerson','contractor_authorized_person','contractor_id','authorized_person_id');
    }



    public function procurements()
    {
        return $this->hasMany('PMIS\Procurement','contractor');
    }

    public function getNameWithProjectCountAttribute()
    {
        $result = $this->name;
        if($this->procurements->count() > 0){
            $result = $result . '('.$this->procurements()->whereHas('project', function($project){$project->where('show_on_running','1');})->count().')';
        }
        return $result ;
    }

    public function scopeConsultants($q){
        return $q->where('type', 2);
    }
    
    
    public function scopeContractors($q){
        return $q->where('type', 1);
    }
}