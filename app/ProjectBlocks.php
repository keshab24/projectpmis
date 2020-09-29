<?php

namespace PMIS;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectBlocks extends Model
{
    use SoftDeletes;
    protected $table = "project_blocks";

    protected $fillable = [
        'project_id','block_name',
        'structure_type','story_area_unite','plint_area','floor_area','roof_type','door_window','wall_type',
        'created_by','updated_by','deleted_at'
    ];
}
