<?php

namespace Modules\CustomerModule\Entities;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\ServiceManagement\Entities\RecentSearch;
use Modules\ServiceManagement\Entities\Service;
use Modules\UserManagement\Entities\User;
use Modules\ZoneManagement\Entities\Zone;

class SearchedData extends Model
{
    use HasFactory, HasUuid;

    protected $casts = [
        'response_data_count' => 'integer',
        'volume' => 'integer',
    ];

    protected $fillable = ['user_id', 'attribute', 'attribute_id', 'response_data_count', 'volume'];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class, 'zone_id');
    }

    public function searched_key()
    {
        return $this->belongsTo(RecentSearch::class, 'attribute_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'attribute_id');
    }

    protected static function newFactory()
    {
        return \Modules\CustomerModule\Database\factories\SearchedDataFactory::new();
    }
}
