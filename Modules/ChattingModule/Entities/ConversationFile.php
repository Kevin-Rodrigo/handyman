<?php

namespace Modules\ChattingModule\Entities;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConversationFile extends Model
{
    use HasFactory, SoftDeletes, HasUuid;

    protected $fillable = [
        'conversation_id',
        'file_name',
        'file_type',
    ];

    protected static function newFactory()
    {
        return \Modules\ChattingModule\Database\factories\ConversationFileFactory::new();
    }
}
