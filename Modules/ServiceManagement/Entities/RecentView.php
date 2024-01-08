<?php

namespace Modules\ServiceManagement\Entities;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecentView extends Model
{
    use HasFactory,HasUuid, SoftDeletes;

    protected $casts = [
        'total_service_view' => 'integer',
        'total_category_view' => 'integer',
        'total_sub_category_view' => 'integer'
    ];

    protected $fillable = [
        'user_id',
        'service_id',
        'total_service_view',
        'category_id',
        'total_category_view',
        'sub_category_id',
        'total_sub_category_view'
    ];

    protected static function newFactory()
    {
        return \Modules\ServiceManagement\Database\factories\RecentViewFactory::new();
    }
}
