{{--for update variants--}}
@if(isset($variants))
    @php($variant_keys = $variants->pluck('variant_key')->unique()->toArray())
    @foreach($variant_keys as $key=>$item)
        <tr>
            <th scope="row">
                {{str_replace('-',' ',$item)}}
                <input name="variants[]" value="{{$item}}" class="hide-div">
            </th>
            <td>
                <input type="number"
                       value="{{$variants->where('price','>',0)->where('variant_key',$item)->first()->price??0}}"
                       class="theme-input-style" id="default-set-{{$key}}-update"
                       onkeyup="set_update_values('{{$key}}')">
            </td>
            @foreach($zones as $zone)
                <td>
                    <input type="number" name="{{$item}}_{{$zone->id}}_price"
                           value="{{$variants->where('zone_id',$zone->id)->where('variant_key',$item)->first()->price??0}}"
                           class="theme-input-style default-get-{{$key}}-update">
                </td>
            @endforeach
            <td>
                <a class="btn btn-sm btn--danger"
                   onclick="ajax_remove_variant('{{route('admin.service.ajax-delete-db-variant',[$item,$variants->first()->service_id])}}','variation-update-table')">
                    <span class="material-icons m-0">delete</span>
                </a>
            </td>
        </tr>
    @endforeach
@endif

<script>
    "use strict"
    function set_update_values(key) {
        $('.default-get-' + key + '-update').val($('#default-set-' + key + '-update').val())
    }
</script>
