<?php

namespace Modules\CartModule\Entities;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartServiceInfo extends Model
{
    use HasFactory;
    use HasUuid;

    protected $fillable = ['customer_id', 'zone_id', 'service_address_id', 'service_schedule'];

    protected static function newFactory()
    {
        return \Modules\CartModule\Database\factories\CartServiceInfoFactory::new();
    }
}
