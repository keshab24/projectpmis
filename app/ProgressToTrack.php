<?php namespace PMIS;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class ProgressToTrack extends Model{

    use SearchableTrait;
    use SoftDeletes;

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
    'columns' => [
        'progress_type' => 10,
        'progress_eng' => 10,
        'progress'  => 10,
    ]
];

    protected $table = 'pro_pt_pivot';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['progress_id','pt_id','status'];

    protected $casts = [
        'progress_id' => 'integer',
        'pt_id' => 'integer',
    ];

    protected $dates = ['deleted_at'];


    public function progress(){
        return $this->belongsTo('PMIS\Progress','progress_id');
    }

    public function progresstrack(){
        return $this->belongsTo('PMIS\ProgressTrack','pt_id');
    }
}