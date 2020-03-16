@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="content">
    <!-- Page length options -->
   <div class="tab_here mb-3">
        <ul class="nav nav-pills m-b-10" id="pills-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link <?php if(empty($type)) echo "active"; ?>"  href='{{ url("admin/users_list")}}'>@lang('messages.ALL')</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if($type == 2) echo "active"; ?>"  href='{{ url("admin/users_list/2")}}'>@lang('messages.Workshop')</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if($type == 1) echo "active"; ?>" href='{{ url("admin/users_list/1")}}'>@lang('messages.Seller')</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if($type == 3) echo "active"; ?>"  href='{{ url("admin/users_list/3")}}'>@lang('messages.Customers')</a>
            </li>
        </ul>
    </div>
    <div class="card" id="user_data_body" style="overflow:auto">
              @include('admin.component.user_data' )
    </div>
    <!-- /page length options -->
</div>
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="{{ url('/') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>@lang('messages.Home')</a>
            <a href="{{ url('/') }}" class="breadcrumb-item">@lang('messages.Admin')</a>
            <span class="breadcrumb-item active"> @lang('messages.Dashboard')</span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
    <!--
    <div class="header-elements d-none">
        <div class="breadcrumb justify-content-center">
            <a href="#" class="breadcrumb-elements-item">
                <i class="icon-comment-discussion mr-2"></i>
                Support
            </a>
        </div>
    </div>
    -->
</div>
@stop
@push('scripts')
<script src="{{ asset('validateJS/admin.js') }}"></script>
<script src="{{ url('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ url('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>
@endpush


