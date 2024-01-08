<?php

namespace Modules\PaymentModule\Entities;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bonus extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [];

    protected function scopeOfStatus($query, $status)
    {
        $query->where('is_active', $status);
    }

    protected static function newFactory()
    {
        return \Modules\PaymentModule\Database\factories\BonusFactory::new();
    }
}
