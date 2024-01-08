<?php

namespace Modules\TransactionModule\Entities;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WithdrawalMethod extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'method_name',
        'method_fields',
        'is_default',
        'is_active',
    ];

    protected $casts = [
        'method_fields' => 'array',
    ];

    protected function scopeOfStatus($query, $status)
    {
        $query->where('is_active', $status);
    }

    protected static function newFactory()
    {
        return \Modules\TransactionModule\Database\factories\WithdrawalMethodFactory::new();
    }
}
