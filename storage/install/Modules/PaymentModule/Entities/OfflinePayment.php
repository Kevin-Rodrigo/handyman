<?php

namespace Modules\PaymentModule\Entities;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OfflinePayment extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'method_name',
        'payment_information',
        'customer_information',
        'is_active',
    ];
    protected $casts = [
        'payment_information' => 'array',
        'customer_information' => 'array',
    ];

    protected function scopeOfStatus($query, $status)
    {
        $query->where('is_active', $status);
    }

    protected static function newFactory()
    {
        return \Modules\PaymentModule\Database\factories\OfflinePaymentFactory::new();
    }
}
