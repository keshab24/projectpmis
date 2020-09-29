<?php namespace PMIS;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class State extends Model
{

    use SearchableTrait;
    use SoftDeletes;
    use Sluggable;

    public function sluggable()
    {
        return [
            'slug' => [
                'build_from' => 'name',
                'save_to' => 'slug',
                'on_update' => false,
                'slug_nep' => false,
                'max_length' => 80,
            ]
        ];
    }

    protected $searchable = [
        'columns' => [
            'name' => 10,
            'name_eng' => 10,
        ]
    ];

    protected $table = 'pro_states';
    protected $fillable = ['slug', 'name', 'name_eng', 'state_number', 'description', 'description_eng', 'coordinates', 'status'];
    protected $dates = ['deleted_at'];


}