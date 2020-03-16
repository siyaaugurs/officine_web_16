@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="content">
    <!-- Page length options -->
    @if(Session::has('msg'))
      {!! session::get('msg') !!}
    @endif

<div class="card" style="margin-bottom:10px;">
    <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-info-circle"></i>&nbsp;@lang('messages.AddServiceGroup')</h6>
    </div>
    <div class="content">
        <div id="filter-panel">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <select class="form-control" name="main_cat" id="main_cat">
                                     <option value="0">@lang('messages.allServices')</option>
                                    @foreach($spare_groups as $spare_group)
                                    <option value="@if(!empty($spare_group->id)){{ $spare_group->id }} @endif">@if(!empty($spare_group->main_cat_name)){{ $spare_group->main_cat_name }} @endif</option>
                                    @endforeach 
                                </select>                                
                            </div> 
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                            <a href='#' id="search_spare_group_item" class="btn btn-warning" style="color:white;">Search &nbsp;<span class="glyphicon glyphicon-search"></span></a>                                 
                            </div>   
                        </div>
                    </div>
                </div>
            </div>
        </div>  
    </div>
</div>
    <div class="card">
    <form id="spare_groups_form">
        <div class="card-header bg-light header-elements-inline">
            <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;@lang('messages.SapreItemsList')</h6>
        </div>
        <div class="card-body" id="list_spare_items">
            @include('workshop.component.list_spare_items') 
            <div class="row" style="margin-top:20px;">
                <div class="col-sm-12">
                    @if($spare_items->count() > 0)
                    {{ $spare_items->links() }}
                    @endif
                </div>
            </div>
        </div>
        <form> 
    </div>
    <!-- /page length options -->
</div>
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>@lang('messages.Home') </a>
            <a href="#" class="breadcrumb-item">@lang('messages.Admin') </a>
            <a href="#" class="breadcrumb-item">@lang('messages.SpareGroups') </a>
            <span class="breadcrumb-item active"> @lang('messages.SapreItemsList') </span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop
@push('scripts')
<script src="{{ url('validateJS/admin.js') }}"></script>
<script src="{{ url('validateJS/spare_groups.js') }}"></script>
<script src="{{ url('validateJS/products.js') }}"></script>
@endpush


