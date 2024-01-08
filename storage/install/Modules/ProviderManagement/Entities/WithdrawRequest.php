<?php

namespace Modules\ProviderManagement\Entities;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\TransactionModule\Entities\WithdrawalMethod;
use Modules\UserManagement\Entities\User;

class WithdrawRequest extends Model
{
    use HasFactory, HasUuid;

    protected $casts = [
        'amount' => 'float',
        'is_paid' => 'integer',
        'withdrawal_method_fields' => 'array',
    ];

    protected $fillable = [
        'user_id',
        'request_updated_by',
        'amount',
        'request_status',
        'is_paid',
        'note',
        'admin_note',
        'withdrawal_method_id',
        'withdrawal_method_fields'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'user_id', 'user_id');
    }

    public function request_updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'request_updated_by');
    }

    public function withdraw_method(): BelongsTo
    {
        return $this->belongsTo(WithdrawalMethod::class, 'withdrawal_method_id');
    }

    protected static function newFactory()
    {
        return \Modules\ProviderManagement\Database\factories\WithdrawRequestFactory::new();
    }
}
