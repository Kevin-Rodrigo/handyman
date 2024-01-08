<?php

namespace Modules\ServiceManagement\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ServiceTag extends Pivot
{
    use HasFactory;

    protected $fillable = ['id','service_id', 'tag_id'];

    protected static function newFactory()
    {
        return \Modules\ServiceManagement\Database\factories\ServiceTagFactory::new();
    }
}
