<?php

namespace Modules\TransactionModule\Entities;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\BookingModule\Entities\Booking;
use Modules\UserManagement\Entities\User;

class Transaction extends Model
{
    use HasFactory, HasUuid;

    protected $casts = [
        'debit' => 'float',
        'credit' => 'float',
        'balance' => 'float',
    ];

    protected $fillable = ['ref_trx_id', 'booking_id', 'trx_type', 'debit', 'credit', 'balance', 'from_user_id', 'to_user_id', 'from_user_account', 'to_user_account', 'reference_note'];

    public function booking(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Booking::class,'booking_id');
    }

    public function from_user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class,'from_user_id');
    }

    public function to_user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class,'to_user_id');
    }

    protected static function newFactory()
    {
        return \Modules\TransactionModule\Database\factories\TransactionFactory::new();
    }
}
