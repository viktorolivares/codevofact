@extends('system.layouts.app')

@section('content')

    <system-backup :last-zip="{{json_encode($last_zip)}}"></system-backup>

@endsection
