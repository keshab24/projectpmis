<?php

namespace PMIS;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class Manpower extends Model
{
    use SearchableTrait;
    protected $searchable = [
        'columns' => [
            'manpowers.title' => 20,
            'manpowers.unit' => 5,
            'manpowers.description' => 5,
        ]
    ];

    protected $fillable = ['title','unit','status', 'countable' , 'created_by','updated_by','type','order','description','categories'];

    protected $casts =[
        'categories' => 'array',
    ];

    public function scopeActive($q){
        return $q->where('status',1);
    }

    public function scopeClient($q){
        return $q->where('type',1);
    }
    
    public function scopeConsultant($q){
        return $q->where('type',2);
    }
    public function scopeContractor($q){
        return $q->where('type',3);
    }

    public function getManpowerTypeAttribute(){
        return manpowerTypes()[$this->type]??'';
    }
}
