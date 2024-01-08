<?php

namespace Modules\ServiceManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\UserManagement\Entities\User;

class VisitedService extends Model
{
    use HasFactory;

    protected $casts = [
        'count' => 'integer',
    ];

    protected $fillable = ['user_id', 'service_id', 'count'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    protected static function newFactory()
    {
        return \Modules\ServiceManagement\Database\factories\VisitedServiceFactory::new();
    }
}
