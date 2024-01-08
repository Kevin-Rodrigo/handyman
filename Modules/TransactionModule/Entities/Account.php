<?php

namespace Modules\TransactionModule\Entities;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use HasFactory;
    use HasUuid;

    protected $casts = [
        'balance_pending' => 'float',
        'received_balance' => 'float',
        'account_payable' => 'float',
        'account_receivable' => 'float',
        'total_withdrawn' => 'float',
    ];

    protected $fillable = ['user_id','balance_pending','received_balance','account_payable','account_receivable','total_withdrawn'];

    protected static function newFactory()
    {
        return \Modules\TransactionModule\Database\factories\AccountFactory::new();
    }
}
