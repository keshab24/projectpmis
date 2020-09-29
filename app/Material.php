<?php

namespace PMIS;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class Material extends Model
{
    use SearchableTrait;
    protected $searchable = [
        'columns' => [
            'title' => 20,
            'unit' => 5,
            'description' => 5,
        ]
    ];

    protected $fillable = ['title','unit','status','created_by','updated_by','type','order','description'];

    public function scopeActive($q){
        return $q->where('status',1);
    }
}
