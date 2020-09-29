<?php

namespace PMIS;

use Illuminate\Database\Eloquent\Model;

class OldProjectCode extends Model
{
    protected $table = 'old_project_codes';

    protected $fillable = ['old_project_code', 'project_id', 'fy_id', 'updated_by'];
}
