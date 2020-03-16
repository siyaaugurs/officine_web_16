@extends('layouts.master_layouts')
@section('content')
<style>
    .unselectable {
        background-color: #ddd;
        cursor: not-allowed;
    }

</style>
<input type="hidden" name="page" id="page" value="{{ $page }}" />
@if($workshop_status == 100)
<div class="content">
    <!-- Page length options -->
    @if(Session::has('msg'))
    {!! session::get('msg') !!}
    @endif
    <div class="card" style="margin-bottom:10px;">
        <div class="content">
            <div id="filter-panel">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <a href="<?= url("spacial_condition/mot_services") ?>" class="btn btn-warning"><i class="fa fa-plus"></i>&nbsp;@lang('messages.SpecialPricecondition')</a>
                            </div>
                            <div class="col-sm-6">
                                <a href="#" class="btn btn-primary edit_all_mot_service_details" style="float:right; align:right">Edit MOT Service Details&nbsp; <span class="glyphicon glyphicon-edit"></span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card" style="margin-bottom:10px;">
        <div class="card-header bg-light header-elements-inline">
            <h6 class="card-title" style="font-weight:600;"><i class="fa fa-filter"></i>&nbsp;@lang('messages.Filter')
            </h6>
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
                                        <option value="@if(!empty($makers->idMarca)){{ $makers->idMarca }} @endif">
                                            @if(!empty($makers->Marca)){{ $makers->Marca }} @endif</option>
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
                                    <select class="form-control car_version_group" id="version_id" name="car_version"
                                        data-action="save_mot_service">
                                        <option value="0">@lang('messages.firstSelectModels')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6" style="display:none" id="service_schedule_div">
                                <div class="form-group">
                                    <select class="form-control" name="service_shedule" id="service_shedule"
                                        data-action="get_save_interval">
                                        <option value="0">@lang('messages.firstSelectVersion')</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <a href='#' id="search_workshop_mot_services" class="btn btn-warning" style="color:white;">Search &nbsp;<span class="glyphicon glyphicon-search"></span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-light header-elements-inline">
            <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;MOT Service List</h6>
            <!-- <a href='javascript::void()' class="btn btn-warning" data-serviceid="3" style="color:white;">Import / Export
                Details&nbsp;<span class="fa fa-download"></span></a> -->
        </div>
        <div class="card-body" id="user_data_body" style="overflow:auto">
            @include('workshop.component.mot_service_list' )
        </div>
    </div>
</div>
<div id="mot_interval_body"></div>

<div class="modal" id="edit_service_details_popup">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i>
				</button>
				<h4 class="modal-title" id="myModalLabel">MOT Service Details</h4>
				<hr />
			</div>
            <div class="card-body">
                <form id="edit_services_form" autocomplete="off">
                    @csrf
                    <input type="hidden" name="service_id" id="service_id" value="" readonly="readonly">
                    <input type="hidden" name="service_type" id="service_type" value="" readonly="readonly">
                    <div class="form-group">
                        <label>@lang('messages.HourlyRate')&nbsp;<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" placeholder="@lang('messages.HourlyRate')" name="service_hourly_rate" id="service_hourly_rate" required="required" min="1" max="1000" value="">
                            <span class="text-danger" id="hourly_rate_err"></span>
                    </div>
                    <div class="form-group">
                        <label>@lang('messages.maxAppointment')&nbsp;<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="@lang('messages.maxAppointment')" name="service_max_appointment" id="service_max_appointment" required="required" value="" />
                        <span id="title_err"></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="form-check form-check-inline">
                            <button type="submit" id="edit_services_btn_copy" class="btn bg-blue ml-3">@lang('messages.Submit')<i class="icon-paperplane ml-2"></i></button>
                        </div>
                    </div>
                </form>
            </div>
			<div id="response_err"></div>
		</div>
		<div class="modal-footer"></div>
	</div>
</div>
<div class="modal" id="edit_all_service_details_popup">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i>
				</button>
				<h4 class="modal-title" id="myModalLabel">MOT Service Details</h4>
				<hr />
			</div>
            <div class="card-body">
                <form id="edit_all_mot_services_form" autocomplete="off">
                    @csrf
                    <div class="form-group">
                        <label>@lang('messages.HourlyRate')&nbsp;<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" placeholder="@lang('messages.HourlyRate')" name="mot_hourly_rate" id="mot_hourly_rate" required="required" min="1" max="1000" value="">
                        <span class="text-danger" id="hourly_rate_err"></span>
                    </div>
                    <div class="form-group">
                        <label>@lang('messages.maxAppointment')&nbsp;<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="@lang('messages.maxAppointment')" name="mot_max_appointment" id="mot_max_appointment" required="required" value="" />
                        <span id="title_err"></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="form-check form-check-inline">
                            <button type="submit" id="edit_all_mot_services_btn" class="btn bg-blue ml-3">@lang('messages.Submit')<i class="icon-paperplane ml-2"></i></button>
                        </div>
                    </div>
                </form>
            </div>
			<div id="response_err"></div>
		</div>
		<div class="modal-footer"></div>
	</div>
</div>

@else
<div>
    <div class="row card" style="padding:40px;">
        <div class="col-lg-12">
            <h3 align="center">Please Complete your Profile </h3>
            <p align="center"><a href="{{ url('')}}" class="btn btn-primary">Manage Profile</a></p>
        </div>
    </div>
</div>
@endif
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>@lang('messages.Home') </a>
            <a href="#" class="breadcrumb-item">Workshop</a>
            <a href="#" class="breadcrumb-item">MOT Services </a>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop
@push('scripts')
<script src="{{ asset('validateJS/import_export.js') }}"></script>
<script src="{{ url('validateJS/admin.js') }}"></script>
<script src="{{ url('validateJS/spare_groups.js') }}"></script>
<script src="{{ url('validateJS/products.js') }}"></script>
<script src="{{ url('validateJS/wracker_service.js') }}"></script>
<script src="{{ url('validateJS/mot_service.js') }}"></script>
<script>
    $(document).ready(function(e) {
        $(document).on('click','#search_workshop_mot_services',function(e){
            $("#preloader").show();  
            e.preventDefault();
            var service_schedule_id = $("#service_shedule").val();
            var version_id = $(".car_version_group").val();
            var language = $('html').attr('lang');
            if(service_schedule_id != ""){
                $.ajax({
                    url:base_url+"/vendor_ajax/get_services_interval",
                    method:"GET",
                    data:{service_schedule_id:service_schedule_id , version_id:version_id, language:language},
                    complete:function(e, xhr, settings){
                        $("#preloader").hide();  
                        if(e.status == 200){
                        $("#mot_interval_body").html(e.responseText);
                        }
                    },
                    error: function(xhr, error){
                        $("#mot_interval_body").html("No record found");
                        $("#preloader").hide();
                    }
                });
            }
        });
        $(document).on('click', '.edit_mot_service_details', function(e) {
            e.preventDefault();
            var type = $(this).data('type');
            var service_id = $(this).data('serviceid');
            if (service_id != 0) {
                $.ajax({
                    url: base_url + "/vendor_ajax/get_mot_service_details",
                    type: "GET",
                    data: { service_id: service_id, type: type },
                    success: function(data) {
                        var parseJson = jQuery.parseJSON(data);
                        console.log(parseJson.response)
                        if (parseJson.status == 200) {
                            $("#service_hourly_rate").val(parseJson.response.hourly_cost);
                            $("#service_max_appointment").val(parseJson.response.max_appointment);
                        }
                        $('#service_id').val(service_id);
                        $('#service_type').val(type);
                        $("#edit_service_details_popup").modal({
                            backdrop: 'static',
                            keyboard: false
                        });
                    }
                });
            }
        });

        $(document).on('submit', '#edit_services_form', function(e) {
            $('#response').html(" ");
            $("err_response").html(" ");
            $('#edit_services_btn_copy').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
            e.preventDefault();
            $.ajax({
                url: base_url + "/vendor_ajax/edit_mot_service_details",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    errorString = '';
                    $('#edit_services_btn_copy').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled', false);
                    var parseJson = jQuery.parseJSON(data);
                    if (parseJson.status == 200) {
                        $(".close").click();
                        $("#msg_response_popup").modal('show');
                        $("#msg_response").html(parseJson.msg);
                        setTimeout(function() { location.reload(); }, 1000);
                    }
                    if (parseJson.status == 400) {
                        $.each(parseJson.error, function(key, value) {
                            errorString += '<div class="notice notice-danger"><strong>Wrong , </strong>' + value + ' .</div>';
                        });
                        $("#msg_response_popup").modal('show');
                        $('#msg_response').html(errorString);
                    }
                    if (parseJson.status == 100) {
                        $("#msg_response_popup").modal('show');
                        $("#msg_response").html(parseJson.msg);
                    }
                }
            });
        });

        $(document).on('click', '.edit_all_mot_service_details', function(e) {
            e.preventDefault();
            $.ajax({
                url: base_url + "/vendor_ajax/get_all_mot_service_details",
                type: "GET",
                success: function(data) {
                    var parseJson = jQuery.parseJSON(data);
                    console.log(parseJson.response)
                    if (parseJson.status == 200) {
                        $("#mot_hourly_rate").val(parseJson.response.hourly_rate);
                        $("#mot_max_appointment").val(parseJson.response.maximum_appointment);
                    }
                    $("#edit_all_service_details_popup").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                }
            });
        });

        $(document).on('submit', '#edit_all_mot_services_form', function(e) {
            $('#response').html(" ");
            $("err_response").html(" ");
            $('#edit_all_mot_services_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
            e.preventDefault();
            $.ajax({
                url: base_url + "/vendor_ajax/edit_all_mot_service_details",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    errorString = '';
                    $('#edit_all_mot_services_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled', false);
                    var parseJson = jQuery.parseJSON(data);
                    if (parseJson.status == 200) {
                        $(".close").click();
                        $("#msg_response_popup").modal('show');
                        $("#msg_response").html(parseJson.msg);
                        setTimeout(function() { location.reload(); }, 1000);
                    }
                    if (parseJson.status == 400) {
                        $.each(parseJson.error, function(key, value) {
                            errorString += '<div class="notice notice-danger"><strong>Wrong , </strong>' + value + ' .</div>';
                        });
                        $("#msg_response_popup").modal('show');
                        $('#msg_response').html(errorString);
                    }
                    if (parseJson.status == 100) {
                        $("#msg_response_popup").modal('show');
                        $("#msg_response").html(parseJson.msg);
                    }
                }
            });
        });
    });
</script>
@endpush
