<?php

namespace Modules\BookingModule\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\ServiceManagement\Entities\Service;
use Modules\UserManagement\Entities\User;

class BookingStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    protected static function newFactory()
    {
        return \Modules\BookingModule\Database\factories\BookingStatusHistoryFactory::new();
    }
}
