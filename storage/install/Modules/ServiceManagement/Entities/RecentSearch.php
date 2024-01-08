<?php

namespace Modules\ServiceManagement\Entities;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\CustomerModule\Entities\SearchedData;

class RecentSearch extends Model
{
    use HasFactory, HasUuid, SoftDeletes;

    protected $casts = [];

    protected $fillable = [
        'user_id',
        'keyword',
    ];

    public function response_data_count()
    {
        return $this->hasOne(SearchedData::class, 'attribute_id');
    }

    public function volume()
    {
        return $this->hasOne(SearchedData::class, 'attribute_id');
    }


    protected static function newFactory()
    {
        return \Modules\ServiceManagement\Database\factories\RecentSearchFactory::new();
    }
}
