<?php

namespace Modules\ServiceManagement\Entities;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\CategoryManagement\Entities\Category;
use Modules\ProviderManagement\Entities\Provider;
use Modules\UserManagement\Entities\User;

class ServiceRequest extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = ['category_id', 'service_name', 'service_description', 'status', 'user_id'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function scopeOfStatus($query, $status)
    {
        $query->where('status', '=', $status);
    }

    protected static function newFactory()
    {
        return \Modules\ServiceManagement\Database\factories\ServiceRequestFactory::new();
    }
}
