@extends('layouts.master_layouts')
@section('content')
<div class="content">
    <!-- Inner container -->
      @include('common.component.common_vendor_dashboard')
    <!-- /inner container -->
</div>
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="index.html" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang('messages.Home')</a>
            <a href="internationalization_fallback.html" class="breadcrumb-item">@lang('messages.Workshop')</a>
            <span class="breadcrumb-item active"> @lang('messages.Dashboard')</span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>   
</div>
@stop
@push('script')
<link href="{{ url("cdn/css/croppie.css") }}" />
@endpush
@push('custom_script')
<script src="{{ url('cdn/js/croppie.js') }}"></script>
@endpush