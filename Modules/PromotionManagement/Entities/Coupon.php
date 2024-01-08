<?php

namespace Modules\PromotionManagement\Entities;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [];

    protected $casts = [
        'is_active' => 'integer'
    ];

    public function discount(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Discount::class, 'discount_id');
    }

    public function scopeOfStatus($query, $status)
    {
        $query->where('is_active', '=', $status);
    }

    public function coupon_customers(): HasMany
    {
        return $this->hasMany(CouponCustomer::class, 'coupon_id');
    }

    protected static function booted()
    {
        static::addGlobalScope('zone_wise_data', function (Builder $builder) {
            if (request()->is('api/*/customer?*') || request()->is('api/*/customer/*')) {
                $builder->whereHas('discount', function ($query) {
                    $query->where('promotion_type', 'coupon')
//                        ->whereDate('start_date', '<=', now())
//                        ->whereDate('end_date', '>=', now())
                        ->where('is_active', 1);
                })->whereHas('discount.discount_types', function ($query) {
                    $query->where(['discount_type' => 'zone', 'type_wise_id' => config('zone_id')]);
                })->latest()->with(['discount']);
            }
        });
    }
}
