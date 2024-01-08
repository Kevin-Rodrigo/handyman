<?php

namespace Modules\UserManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserRole extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','role_id'];

    protected static function newFactory()
    {
        return \Modules\UserManagement\Database\factories\UserRoleFactory::new();
    }
}
