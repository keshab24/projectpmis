<?php
namespace PMIS;

use Illuminate\Database\Eloquent\Model;

class ImplementingOfficePivot extends Model {


    protected $table = 'pro_implementingoffice_pivot';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['implementing_office_id','district_id','status','fy_id'];
    protected $dates = ['deleted_at'];

    protected $casts = [
        'implementing_office_id' => 'integer',
        'district_id' => 'integer',
        'fy_id' => 'integer',
    ];

}