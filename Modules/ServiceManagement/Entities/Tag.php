<?php

namespace Modules\ServiceManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['tag'];

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class);
    }

    protected static function newFactory()
    {
        return \Modules\ServiceManagement\Database\factories\TagFactory::new();
    }
}
