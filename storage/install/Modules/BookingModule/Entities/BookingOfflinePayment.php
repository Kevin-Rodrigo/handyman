<?php

namespace Modules\BookingModule\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasUuid;

class BookingOfflinePayment extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'booking_id',
        'customer_information',
    ];
    protected $casts = [
        'customer_information' => 'array',
    ];

    protected static function newFactory()
    {
        return \Modules\BookingModule\Database\factories\BookingOfflinePaymentFactory::new();
    }
}
