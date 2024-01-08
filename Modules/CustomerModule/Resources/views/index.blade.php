@extends('customermodule::layouts.master')

@section('content')
    {!! json_decode($page_data['live_values']??'') !!}
@endsection
