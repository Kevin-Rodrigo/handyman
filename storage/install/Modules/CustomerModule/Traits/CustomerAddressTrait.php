<?php

namespace Modules\CustomerModule\Traits;

use Grimzy\LaravelMysqlSpatial\Types\Point;
use Modules\UserManagement\Entities\UserAddress;
use Modules\ZoneManagement\Entities\Zone;

trait CustomerAddressTrait
{
    public function add_address($service_address, $user_id, $is_guest = 0)
    {
        $point = new Point($service_address->lat, $service_address->lon);
        $zone = Zone::contains('coordinates', $point)->ofStatus(1)->latest()->first();
        if ($zone) {
            $zone_id = $zone->id;
        } else {
            $zone_id = null;
        }

        $address = new UserAddress;
        $address->user_id = $user_id;
        $address->lat = $service_address->lat;
        $address->lon = $service_address->lon;
        $address->city = $service_address->city;
        $address->street = $service_address->street ?? '';
        $address->zip_code = $service_address->zip_code;
        $address->country = $service_address->country;
        $address->address = $service_address->address;
        $address->zone_id = $zone_id;
        $address->address_type = $service_address->address_type ?? 'service';
        $address->contact_person_name = $service_address->contact_person_name;
        $address->contact_person_number = $service_address->contact_person_number;
        $address->address_label = $service_address->address_label;
        $address->house = $service_address->house;
        $address->floor = $service_address->floor;
        $address->is_guest = $is_guest;
        $address->save();

        return $address->id ?? null;
    }

}
