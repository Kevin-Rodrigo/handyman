<?php

namespace Modules\BookingModule\Entities;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingDetailsAmount extends Model
{
    use HasFactory, HasUuid;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'service_unit_cost' => 'float',

        'service_quantity' => 'integer',
        'service_tax' => 'float',
        'discount_by_admin' => 'float',
        'discount_by_provider' => 'float',

        'coupon_discount_by_admin' => 'float',
        'coupon_discount_by_provider' => 'float',

        'campaign_discount_by_admin' => 'float',
        'campaign_discount_by_provider' => 'float',

        'admin_commission' => 'float',
        'provider_earning' => 'float',
    ];

    protected $fillable = [
        'id',
        'booking_details_id',
        'booking_id',
        'service_unit_cost',

        'discount_by_admin',
        'discount_by_provider',

        'coupon_discount_by_admin',
        'coupon_discount_by_provider',

        'campaign_discount_by_admin',
        'campaign_discount_by_provider',
    ];

    public function booking(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Booking::class,  'booking_id', 'id');
    }

    protected static function newFactory()
    {
        return \Modules\BookingModule\Database\factories\BookingDetailsAmountFactory::new();
    }
}
