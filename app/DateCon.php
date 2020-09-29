<?php
namespace PMIS;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class DateCon extends Model {

    use SearchableTrait;
    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'name' => 10,
            'name_eng' => 10,
        ]
    ];

    protected $table = 'datecon';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = ['datenepformatted'];


}