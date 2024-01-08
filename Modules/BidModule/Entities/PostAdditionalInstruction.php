<?php

namespace Modules\BidModule\Entities;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PostAdditionalInstruction extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'id',
        'details',
        'post_id'
    ];

    protected static function newFactory()
    {
        return \Modules\BidModule\Database\factories\PostAdditionalInstructionFactory::new();
    }
}
