@extends('system.layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-6 col-md-12">
            <system-certificate-index></system-certificate-index>
        </div>
        <div class="col-lg-6 col-md-12">
            <system-configuration-token></system-configuration-token>
        </div>
    </div>

@endsection
