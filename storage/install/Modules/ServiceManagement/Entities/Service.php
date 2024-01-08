<?php

namespace Modules\ServiceManagement\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Config;
use Modules\BookingModule\Entities\BookingDetail;
use Modules\CategoryManagement\Entities\Category;
use Modules\PromotionManagement\Entities\DiscountType;
use Modules\ReviewModule\Entities\Review;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory;
    use HasUuid;
    use SoftDeletes;

    protected $casts = [
        'tax' => 'float',
        'order_count' => 'float',
        'is_active' => 'integer',
        'rating_count' => 'integer',
        'avg_rating' => 'float',
    ];

    protected $fillable = [];

    public function variations(): HasMany
    {
        return $this->hasMany(Variation::class, 'service_id', 'id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(BookingDetail::class, 'service_id', 'id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'sub_category_id', 'id')->withoutGlobalScopes();
    }

    public function service_discount(): HasMany
    {
        return $this->hasMany(DiscountType::class, 'type_wise_id')
            ->whereHas('discount', function ($query) {
                $query->whereIn('discount_type', ['service', 'mixed'])
                    ->where('promotion_type', 'discount')
                    ->whereDate('start_date', '<=', now())
                    ->whereDate('end_date', '>=', now())
                    ->where('is_active', 1);
            })->whereHas('discount.discount_types', function ($query) {
                if (request()->is('api/*/provider?*') || request()->is('api/*/provider/*')) {
                    $query->where(['discount_type' => 'zone', 'type_wise_id' => request()->user()->provider->zone_id]);
                } elseif (request()->is('api/*/customer?*') || request()->is('api/*/customer/*')) {
                    $query->where(['discount_type' => 'zone', 'type_wise_id' => config('zone_id')]);
                }
            })->with(['discount'])->latest();
    }

    public function campaign_discount(): HasMany
    {
        return $this->hasMany(DiscountType::class, 'type_wise_id')
            ->whereHas('discount', function ($query) {
                $query->where('promotion_type', 'campaign')
                    ->whereDate('start_date', '<=', now())
                    ->whereDate('end_date', '>=', now())
                    ->where('is_active', 1);
            })->whereHas('discount.discount_types', function ($query) {
                if (request()->is('api/*/provider?*') || request()->is('api/*/provider/*')) {
                    $query->where(['discount_type' => 'zone', 'type_wise_id' => request()->user()->provider->zone_id]);
                } elseif (request()->is('api/*/customer?*') || request()->is('api/*/customer/*')) {
                    $query->where(['discount_type' => 'zone', 'type_wise_id' => config('zone_id')]);
                }
            })->with(['discount'])->latest();
    }

    public function scopeActive($query)
    {
        $query->where(['is_active' => 1])
            ->whereHas('category', function ($query) {
                $query->where('is_active', 1);
            })
            ->whereHas('subCategory', function ($query) {
                $query->where('is_active', 1);
            });
    }

    public function scopeInActive($query)
    {
        $query->where(['is_active' => 0]);
    }

    public function scopeOfStatus($query, $status)
    {
        if($status == 1) {
            $query->where(['is_active' => 1])
                ->whereHas('category', function ($query) {
                    $query->where('is_active', 1);
                })
                ->whereHas('subCategory', function ($query) {
                    $query->where('is_active', 1);
                });

        } else if($status = 0) {
            $query->where(['is_active' => 0]);
        }
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(Faq::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    protected static function booted()
    {
        static::addGlobalScope('zone_wise_data', function (Builder $builder) {
            if (request()->is('api/*/customer?*') || request()->is('api/*/customer/*')) {
                $builder->whereHas('category.zones', function ($query) {
                    $query->where('zone_id', Config::get('zone_id'));
                })->with(['service_discount', 'campaign_discount']);
            } elseif (request()->is('api/*/provider?*') || request()->is('api/*/provider/*')) {
                if (auth()->check() && request()->user()->provider != null) {
                    $builder->whereHas('category.zones', function ($query) {
                        $query->where('zone_id', request()->user()->provider->zone_id);
                    })->with(['service_discount', 'campaign_discount']);
                }
            }
        });
    }
}
