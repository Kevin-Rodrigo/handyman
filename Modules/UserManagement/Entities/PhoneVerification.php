<?php

namespace Modules\UserManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PhoneVerification extends Model
{
    use HasFactory;

    protected $fillable = ['phone_or_email', 'token'];

    protected static function newFactory()
    {
        return \Modules\UserManagement\Database\factories\PhoneVerificationFactory::new();
    }
}
