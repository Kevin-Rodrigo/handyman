<?php

namespace Modules\BidModule\Entities;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IgnoredPost extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = ['post_id', 'provider_id'];

    protected static function newFactory()
    {
        return \Modules\BidModule\Database\factories\IgnoredPostFactory::new();
    }
}
