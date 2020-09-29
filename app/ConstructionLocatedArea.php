<?php namespace PMIS;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class ConstructionLocatedArea extends Model{

    use SearchableTrait;

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
    'columns' => [
        'located_area' => 30,
    ]
];

    protected $table = 'pro_construction_located_area';

    protected $fillable = [
        'id',
        'located_area',
        'located_area_nep',
        'monitoring_office_id',
        'status'
    ];



    protected $casts = [
        'monitoring_office_id' => 'integer',
    ];


}