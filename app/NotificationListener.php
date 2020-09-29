<?php

namespace PMIS;

use Illuminate\Database\Eloquent\Model;

class NotificationListener extends Model
{
    protected $table = 'notice_listener';
    protected $fillable = ['notice_id','listener_id','seen','type'];

    public function notice()
    {
        return $this->belongsTo(Notice::class,'notice_id');
    }

    public function listener()
    {
        return $this->belongsTo(User::class, 'listener_id');
    }

    public function scopeChats($q)
    {
        return $q->where('type',2);
    }

    public function scopeApps($q)
    {
        return $q->where('type',1);
    }
}
