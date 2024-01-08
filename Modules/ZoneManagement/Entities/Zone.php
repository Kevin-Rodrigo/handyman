<?php

namespace Modules\ZoneManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use App\Traits\HasUuid;
use Modules\CategoryManagement\Entities\Category;
use Modules\ProviderManagement\Entities\Provider;
use Modules\UserManagement\Entities\User;

class Zone extends Model
{
    use HasFactory;
    use SpatialTrait;
    use HasUuid;

    protected $casts = [
        'is_active' => 'integer'
    ];

    protected $fillable = [];

    protected $spatialFields = [
        'coordinates'
    ];

    public function scopeOfStatus($query, $status)
    {
        $query->where('is_active', '=', $status);
    }

    public function providers()
    {
        return $this->hasMany(Provider::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    protected function getCoordinatesAttribute($values): array
    {
        $points = [];
        foreach ($values[0] as $point) {
            $points[] = (object)['lat' => $point->getLat(), 'lng' => $point->getLng()];
        }
        return $points;
    }
}
