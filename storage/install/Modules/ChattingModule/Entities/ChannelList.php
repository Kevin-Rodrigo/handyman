<?php

namespace Modules\ChattingModule\Entities;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChannelList extends Model
{
    use HasFactory, SoftDeletes, HasUuid;

    protected $fillable = [
        'reference_id',
        'reference_type',
    ];

    //relation
    public function channelUsers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ChannelUser::class, 'channel_id', 'id');
    }

    public function channelConversations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ChannelConversation::class, 'channel_id', 'id');
    }

    protected static function newFactory()
    {
        return \Modules\ChattingModule\Database\factories\ChannelListFactory::new();
    }
}
