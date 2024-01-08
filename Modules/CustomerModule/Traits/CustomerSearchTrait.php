<?php

namespace Modules\CustomerModule\Traits;

use Illuminate\Support\Facades\Config;
use Modules\CustomerModule\Entities\SearchedData;

trait CustomerSearchTrait
{

    protected function Searched_data_log($user_id, $attribute, $attribute_id, $response_data_count)
    {
        $searched_data = SearchedData::firstOrNew(['user_id' => $user_id, 'attribute_id' =>  $attribute_id]);
        $searched_data->zone_id = Config::get('zone_id');
        $searched_data->attribute = $attribute;
        $searched_data->response_data_count += $response_data_count;
        $searched_data->volume += 1;
        $searched_data->save();
    }

    protected function search_log_volume_update($user_id, $attribute_id)
    {
        SearchedData::where(['user_id' => $user_id, 'attribute_id' =>  $attribute_id])->increment('volume', 1);
    }
}
