<?php

namespace Modules\BookingModule\Events;

use Illuminate\Queue\SerializesModels;
use Modules\BookingModule\Entities\Booking;

class BookingRequested
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
