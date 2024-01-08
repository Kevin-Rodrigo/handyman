<?php

namespace Modules\ProviderManagement\Entities;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProviderSetting extends Model
{
    use HasFactory,HasUuid;

    protected $casts = [
        'live_values'=>'array',
        'test_values'=>'array',
        'is_active'=>'integer',
    ];

    protected $fillable = ['key_name', 'provider_id', 'live_values', 'test_values', 'settings_type', 'mode', 'is_active'];

    protected static function newFactory()
    {
        return \Modules\ProviderManagement\Database\factories\ProviderSettingFactory::new();
    }
}
