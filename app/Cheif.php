<?php namespace PMIS;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cheif extends Model {

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

    protected $table = 'pro_office_cheif';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','nep_name','slug','email','address','phone','mobile','image','created_by','updated_by','status'];
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

}