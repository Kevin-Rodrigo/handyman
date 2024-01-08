<?php

namespace Modules\BidModule\Entities;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\ProviderManagement\Entities\Provider;

class PostBid extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\BidModule\Database\factories\PostBidFactory::new();
    }

    /** Relations */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }
}
