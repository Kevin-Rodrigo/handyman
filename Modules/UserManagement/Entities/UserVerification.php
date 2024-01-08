<?php

namespace Modules\UserManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'identity',
        'identity_type',
        'user_id',
        'otp',
        'expires_at',
    ];

    protected static function newFactory()
    {
        return \Modules\UserManagement\Database\factories\UserVerificationFactory::new();
    }
}
