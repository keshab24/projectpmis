<?php

namespace PMIS;

use Illuminate\Database\Eloquent\Model;

class UserTypeFlag extends Model
{
    protected $table = 'pro_user_type';

    protected $fillable = ['type'];

    public function Users()
    {
        return $this->hasMany('PMIS\User','type_flag');
    }

    public function NotificationType()
    {
        return $this->belongsToMany('PMIS\NotificationType','pro_notification_type_to_user','user_type_flag','notification_type');
    }
}
