<?php namespace PMIS;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;

class Address extends Model{

    use SoftDeletes;
    use SearchableTrait;

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
    'columns' => [
        'tole' => 10,
        'tole_eng' => 10,
        'pro_districts.name'=>10,
        'pro_districts.name_eng'=>10,
        'vdc_municipality_name'=>10,
        'vdc_municipality_name_eng'=>10
    ],
    'joins'=>[
        'pro_districts'=>['pro_addresses.district_id','pro_districts.id']
    ]
];

    protected $table = 'pro_addresses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['ward','tole','tole_eng','vdc_municipality','vdc_municipality_name','vdc_municipality_name_eng','image','coordinates','district_id','status'];
    protected $dates = ['deleted_at'];


    protected $casts = [
        'district_id' => 'integer',
    ];

    public function district(){
        return $this->belongsTo('PMIS\District','district_id');
    }

}