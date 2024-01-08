<?php

namespace Modules\UserManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserZone extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','zone_id'];

    protected static function newFactory()
    {
        return \Modules\UserManagement\Database\factories\UserZoneFactory::new();
    }
}
