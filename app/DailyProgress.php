<?php

namespace PMIS;

use Illuminate\Database\Eloquent\Model;

class DailyProgress extends Model
{
    protected $table = 'daily_progresses';
    protected $fillable = ['project_id','manpower','equipments','materials','problems','activities','date','weather','weather_remarks','temperature','created_by','updated_by','samples'];
    protected $casts = [
        'manpower' => 'array',
        'equipments' => 'array',
        'materials' => 'array',
        'problems' => 'array',
        'temperature' => 'array',
        'activities' => 'array',
        'samples' => 'array',
    ];

    public  function project(){
        return $this->belongsTo(Project::class, 'project_id');
    }
}
