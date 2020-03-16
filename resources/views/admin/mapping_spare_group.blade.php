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

        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-filter"></i>&nbsp;@lang('messages.Filter')</h6>

    </div>

    <div class="content">

        <div id="filter-panel">

            <div class="panel panel-default">

                <div class="panel-body">

                    <div class="row">

                        <div class="col-sm-6">

                            <div class="form-group">

                                <select class="form-control" name="car_makers" id="car_makers">

                                <option value="0">@lang('messages.selectMaker')</option>

                                    @foreach($cars__makers_category as $makers)

                                    <option value="@if(!empty($makers->idMarca)){{ $makers->idMarca }} @endif">@if(!empty($makers->Marca)){{ $makers->Marca }} @endif</option>

                                    @endforeach 

                                </select>                                

                            </div> 

                        </div>

                        <div class="col-sm-6">

                            <div class="form-group">

                                <select class="form-control" name="car_models" id="car_models">

                                    <option value="0">@lang('messages.firstSelectMakers')</option>

                                </select>                                

                            </div>

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-sm-6">

                            <div class="form-group">

                                <select class="form-control car_version_group" name="car_version" data-action="get_and_save_for_mapping">

                                    <option value="0">@lang('messages.firstSelectModels')</option>

                                </select>                                

                            </div> 

                        </div>

                        <div class="col-sm-6">

                            <div class="form-group">

                            <a href='#' id="search_group_item" class="btn btn-warning" style="color:white;">Search &nbsp;<span class="glyphicon glyphicon-search"></span></a>                                 

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>  

    </div>

</div>



<!--<div class="card" style="margin-bottom:10px;">

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

                                    <option value="0">@lang('messages.selectSpareGroup')</option>

                                    @foreach($spare_groups as $spare_group)

                                    <option value="@if(!empty($spare_group->id)){{ $spare_group->id }} @endif">@if(!empty($spare_group->main_cat_name)){{ $spare_group->main_cat_name }} @endif</option>

                                    @endforeach 

                                </select>                                

                            </div> 

                        </div>

                    </div>

                </div>

            </div>

        </div>  

    </div>

</div>-->

    <div class="card">

    <form id="spare_groups_form">

        <div class="card-header bg-light header-elements-inline">

            <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;@lang('messages.AddServiceGroupItem')</h6>

            <button type="submit" class="btn btn-success" id="save_selected_group" style="color:white; margin-left:500px;">Save &nbsp;<span class="glyphicon glyphicon-plus"></span></button>

        </div>

        <div class="content">

        <div id="filter-panel">

            <div class="panel panel-default">

                <div class="panel-body">

                    <div class="row">

                        <div class="col-sm-12">

                            <div class="form-group">

                                <select class="form-control" name="main_cat" id="main_cat">

                                    <option value="0">@lang('messages.selectSpareGroup')</option>

                                    @foreach($spare_groups as $spare_group)

                                    <option value="@if(!empty($spare_group->id)){{ $spare_group->id }} @endif">@if(!empty($spare_group->main_cat_name)){{ $spare_group->main_cat_name }} @endif</option>

                                    @endforeach 

                                </select>                                

                            </div> 

                        </div>

                    </div>

                </div>

            </div>

        </div>  

    </div>

        <div class="card-body" id="group_mapping_list">

           @include('admin.component.spare_group_item',['products_groups'=>$products_groups]) 

            <div class="row" style="margin-top:20px;">

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

            <a href="{{ url('/') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>@lang('messages.Home')</a>
            <a href="{{ url('/') }}" class="breadcrumb-item">@lang('messages.Admin')</a>

            <a href="#" class="breadcrumb-item">@lang('messages.SpareGroups') </a>

            <span class="breadcrumb-item active"> @lang('messages.SpareGroupMapping') </span>

        </div>

        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>

    </div>

</div>

@stop

@push('scripts')

<script>

$(document).ready(function(e) {

    $(document).on('submit','#spare_groups_form',function(e){

        e.preventDefault();

        var main_category_id = $('#main_cat').val();

        if(main_category_id != 0) {

            records = [];

            $("#group_table tr").each(function(){

                if($(this).find('.group_id').is(':checked')){

                   records.push({group_id:$(this).find('.group_id').val(), version_id:$(this).find('.group_id').data('versionid'),language:$(this).find('.group_id').data('lang')});

                }

            });

            $.ajax({

                url: base_url+"/spare_products/add_selected_service_group",

                type: "POST",        

                data: {records:records , main_category_id:main_category_id},

                dataType: 'json',

                success: function(data){

                   console.log(data);

				    $("#msg_response_popup").modal('show');

                    $("#msg_response").html(data.msg); 

                    setTimeout(function(){ location.reload(); } , 1000);

                }	,

                error: function(xhr, error){

                    $("#msg_response_popup").modal('show');

                    $("#msg_response").html('<div class="notice notice-danger"><strong>Wrong </strong>Something Wrong , please try again . !!! </div>');

                }

            });

        }

        else{

            alert("Please select group services !!!")

        }

    }); 

});

</script>

<script src="{{ url('validateJS/admin.js') }}"></script>

<script src="{{ url('validateJS/spare_groups.js') }}"></script>

<script src="{{ url('validateJS/products.js') }}"></script>

@endpush





