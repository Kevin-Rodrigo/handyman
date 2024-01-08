<?php

namespace Modules\BookingModule\Entities;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingPartialPayment extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\BookingModule\Database\factories\BookingPartialPaymentFactory::new();
    }
}
