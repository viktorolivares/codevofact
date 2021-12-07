@extends('tenant.layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pos.css') }}"/>
@endpush

@section('content')

    <tenant-pos-index
     	:configuration="{{ $configuration}}"
     	:soap-company="{{ json_encode($soap_company) }}"
        :type-user="{{json_encode(Auth::user()->type)}}">
    </tenant-pos-index>
@endsection

@push('scripts')
    <script></script>
@endpush
