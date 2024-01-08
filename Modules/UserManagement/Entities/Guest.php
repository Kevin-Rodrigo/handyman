<?php

namespace Modules\UserManagement\Entities;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Guest extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\UserManagement\Database\factories\GuestFactory::new();
    }
}
