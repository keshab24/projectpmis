<?php

namespace PMIS;

use Illuminate\Database\Eloquent\Model;

class DailyProgressUser extends Model
{
    protected  $table = "daily_progress_users";

    protected $fillable = [
      'project_id','engineer_id','created_by','updated_by','created_at','updated_at'
    ];
    public function Projects(){
        return $this->belongsToMany('PMIS\Project','pro_project_engineers','engineer_id','project_id');
    }

    public function Engineer(){
        return $this->belongsTo('PMIS\Engineer',  'engineer_id');
    }
}
