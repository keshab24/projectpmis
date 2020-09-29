<?php

namespace PMIS;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class WorkActivity extends Model
{
    use SearchableTrait;
    protected $searchable = [
        'columns' => [
            'title' => 25,
            'description' => 5,
        ]
    ];

    protected $fillable = ['title','status','created_by','updated_by','type','order','description','code','unit','batch','date'];

    public function scopeActive($q){
        return $q->where('status',1);
    }
}
