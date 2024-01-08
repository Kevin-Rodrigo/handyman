<?php

namespace Modules\BookingModule\Repository;

use App\Lib\QueryInterface;
use Modules\BookingModule\Entities\Booking;

class BookingRepository implements QueryInterface
{
    private Booking $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }


    public function find($column, $value)
    {
        return $this->booking->with([
            'detail.service', 'schedule_histories', 'status_histories', 'service_address', 'customer', 'provider', 'zone', 'serviceman'
        ])->where(function ($query) use ($value) {
            return $query->where('provider_id', auth()->user()->provider->id)->orWhereNull('provider_id');
        })->where([$column => $value])->first();
    }
}
