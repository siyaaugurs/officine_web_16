@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="content">
    <!-- Page length options -->
    @if(Session::has('msg'))
      {!! session::get('msg') !!}
    @endif
    <div class="card">
        <div class="card-header bg-light header-elements-inline">
            <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;@lang('messages.CategoryList')</h6>
        </div>
        <table class="table datatable-show-all">
            <thead>
                <tr>
                    <th>@lang('messages.SN')</th>
                    <th>@lang('messages.Type')</th>
                    <th>@lang('messages.Image')</th>
                    <th>@lang('messages.Category')</th>
                    <th class="text-center">@lang('messages.Actions')</th>
                </tr>
            </thead>
            <tbody>
                {!! $category_list !!}
            </tbody>
        </table>
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
                            <span class="breadcrumb-item active"> @lang('messages.CategoryList') </span>
                        </div>
                        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
                    </div>
                    <!--<div class="header-elements d-none">
                        <div class="breadcrumb justify-content-center">
                            <a href="#" class="breadcrumb-elements-item">
                                <i class="icon-comment-discussion mr-2"></i>
                                Support
                            </a>
                        </div>
                    </div>-->
                </div>
@stop
@push('scripts')
<script src="{{ url('validateJS/admin.js') }}"></script>
@endpush


