<tr>
    <th scope="col">{{translate('variations')}}</th>
    <th scope="col">{{translate('default_price')}}</th>
    @foreach($zones as $zone)
        <th scope="col">{{$zone->name}}</th>
    @endforeach
    <th scope="col">{{translate('action')}}</th>
</tr>
