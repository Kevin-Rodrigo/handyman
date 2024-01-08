<?php

namespace Modules\PromotionManagement\Entities;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\UserManagement\Entities\User;

class CouponCustomer extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = ['coupon_id', 'customer_user_id'];

    protected static function newFactory()
    {
        return \Modules\PromotionManagement\Database\factories\CouponCustomerFactory::new();
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_user_id');
    }
}
