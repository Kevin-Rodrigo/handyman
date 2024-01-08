<?php

namespace Modules\ServiceManagement\Traits;

use Modules\ServiceManagement\Entities\VisitedService;

trait VisitedServiceTrait
{
    protected function visited_service_update($user_id, $service_id)
    {
        $visited_service = VisitedService::firstOrNew(['service_id' =>  $service_id, 'user_id' => $user_id]);
        $visited_service->count += 1;
        $visited_service->save();
    }
}
