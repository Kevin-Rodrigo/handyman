<?php

namespace Modules\ChattingModule\Entities;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\UserManagement\Entities\User;

class ChannelUser extends Model
{
    use HasFactory, SoftDeletes, HasUuid;

    protected $casts = [
        'is_read' => 'integer'
    ];

    protected $fillable = [
        'channel_id',
        'user_id',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function newFactory()
    {
        return \Modules\ChattingModule\Database\factories\ChannelUserFactory::new();
    }
}
