<?php namespace PMIS;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model  {

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
                'include_trashed'  => true,
            ]
        ];
    }
    use SearchableTrait;
    use SoftDeletes;
    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'name' => 10,
            'name_nep' => 10,
            'description' => 10,
            'description_nep' => 10
        ]
    ];

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'pro_group_category';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['slug','name', 'name_nep', 'description','description_nep','category_id','type','order','level','show_on_menu','layout_id','show_on_home_page','page_category_order','created_by','updated_by','status'];
    protected $dates = ['deleted_at'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subMenu(){
        return $this->hasMany('PMIS\Menu','group_category_id');
    }



    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mainMenu(){
        return $this->belongsTo('PMIS\Menu','group_category_id');
    }



    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function creator(){
        return $this->belongsTo('ProNews\User','created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function updator(){
        return $this->belongsTo('ProNews\User','updated_by');
    }

}
