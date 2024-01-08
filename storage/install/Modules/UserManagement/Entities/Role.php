<?php

namespace Modules\UserManagement\Entities;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\ModuleManagement\Entities\Module;

class Role extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [];

    protected $casts = [
        'create'=>'integer',
        'read'=>'integer',
        'update'=>'integer',
        'delete'=>'integer',
        'is_active'=>'integer',
        'modules'=>'array'
    ];

    public function scopeOfStatus($query, $status)
    {
        $query->where('is_active', '=', $status);
    }
}
