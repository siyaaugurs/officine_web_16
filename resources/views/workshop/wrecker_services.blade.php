@extends('layouts.master_layouts')
@section('content')
<style>
    .unselectable{
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
                            <a href="<?php echo url("spacial_condition/wrecker_services") ?>" class="btn btn-warning"><i class="fa fa-plus"></i>&nbsp;@lang('messages.SpecialPricecondition')</a> 
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-light header-elements-inline">
            <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;@lang('messages.WreckerServiceList')</h6>
              <a href='javascript::void()' class="btn btn-warning" data-serviceid="13" id="wracker_services_import" style="color:white;">Import / Export Details&nbsp;<span class="fa fa-download"></span></a>
        </div>
    	<div class="card-body" id="user_data_body" style="overflow:auto">
            @include('workshop.component.wrecker_service_list' )
        </div>
    </div>

    <!-- /page length options -->
</div>
<!--Manage time slot services-->
<div class="modal" id="wracker_service_popup">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i>
				</button>
				<h4 class="modal-title" id="myModalLabel">Wracker Service Details </h4>
				<hr />
			</div>
			<!-- Modal body -->
            <div class="card-body">
                <form id="add_workshop_wrecker_services_form" autocomplete="off">
                    @csrf
                    <div class="form-group">
                        <label>@lang('messages.ServiceName')&nbsp;<span class="text-danger">*</span></label>
                        <select name="service_name" id="service_name" class="form-control wrecker_type">
                                <option value="0" hidden="hidden">--Select Service--</option>
                        @foreach($wrecker_services as $services)
                            <option value="{{ $services->id }}" type="{{ $services->wracker_service_type }}">{{ $services->services_name }}</option>
                        @endforeach
                        </select>
                        <span class="text-danger" id="hourly_rate_err"></span>
                    </div>
                    <div class="form-group">
                        <label>@lang('messages.TimeArrives')&nbsp;(+15 minutes)&nbsp;<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="@lang('messages.TimeArrives')" name="time_arrives" id="time_arrives" value="" required="required"  />
                    </div>
                    <div class="form-group">
                        <label>@lang('messages.HourlyCost')&nbsp;<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" placeholder="@lang('messages.HourlyRate')" name="hourly_rate" id="hourly_rate" required="required" min="1" max="1000" value="">
                            <span class="text-danger" id="hourly_rate_err"></span>
                    </div>
                    <div class="form-group">
                        <label>@lang('messages.CostPerKm')&nbsp;<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="@lang('messages.CostPerKm')" name="distance_cost" id="distance_cost" value="" />
                        <span id="title_err"></span>
                    </div>   
                    <div class="form-group wrecker_call_price" style="display:none">
                        <label>@lang('messages.CallPrice')&nbsp;<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" placeholder="@lang('messages.CallPrice')" name="call_price" id="call_price"  min="1" max="1000" value="">
                            <span class="text-danger" id="hourly_rate_err"></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="form-check form-check-inline">
                            <button type="submit" id="add_wrecker_services_btn" class="btn bg-blue ml-3">@lang('messages.Submit')<i class="icon-paperplane ml-2"></i></button>
                        </div>
                    </div>
                </form>
            </div>
			<div id="response_err"></div>
		</div>
		<div class="modal-footer"></div>
	</div>
</div>
<!--End-->
<!--Manage time slot services-->
<div class="modal" id="edit_wracker_service_popup">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i>
				</button>
				<h4 class="modal-title" id="myModalLabel">Edit Service Details </h4>
				<hr />
			</div>
			<!-- Modal body -->
            <div class="card-body">
                <form id="edit_workshop_wrecker_services_form" autocomplete="off">
                    @csrf
                    <input type="hidden" id="wrecker_service_id" name="wrecker_service_id" value="">
                    <div class="form-group">
                        <label>Service Name</label>
                        <input type="text" class="form-control" name="wrecker_service_name" id="wrecker_service_name" required="required" value="" readonly>
                        <span class="text-danger" id="hourly_rate_err"></span>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-bordered" style="margin-bottom:15px;">
                                <thead>
                                    <tr>
                                        <th>Service Type</th>
                                        <th>Service By Appointment</th>
                                        <th>Emergency Service</th>
                                    </tr> 
                                    <tr>
                                        <th>Preparation time</th>
                                        <td><input type="text" class="form-control" placeholder="@lang('messages.TimeArrives')" name="service_time_arrives" id="service_time_arrives" value="" required="required"  /></td>
                                        <td><input type="text" class="form-control" placeholder="@lang('messages.TimeArrives')" name="emergency_time_arrives" id="emergency_time_arrives" value="" required="required"  /></td>
                                    </tr>
                                    <tr>
                                        <th>Hourly Cost</th>
                                        <td><input type="number" class="form-control" placeholder="@lang('messages.HourlyRate')" name="service_hourly_rate" id="service_hourly_rate" required="required" min="1" max="1000" value=""></td>
                                        <td><input type="number" class="form-control" placeholder="@lang('messages.HourlyRate')" name="emergency_hourly_rate" id="emergency_hourly_rate" required="required" min="1" max="1000" value=""></td>
                                    </tr>
                                    <tr>
                                        <th>Cost / Km</th>
                                        <td><input type="text" class="form-control" placeholder="@lang('messages.CostPerKm')" name="servicecost_per_km" id="servicecost_per_km" value="" /></td>
                                        <td><input type="text" class="form-control" placeholder="@lang('messages.CostPerKm')" name="emergencycost_per_km" id="emergencycost_per_km" value="" /></td>
                                    </tr>
                                    <tr>
                                        <th>Call Cost</th>
                                        <td><input type="number" class="form-control" placeholder="@lang('messages.CallPrice')" name="service_service_call_price" id="service_service_call_price"  value=""></td>
                                        <td><input type="number" class="form-control" placeholder="@lang('messages.CallPrice')" name="emergency_service_call_price" id="emergency_service_call_price"  value=""></td>
                                    </tr>
                                    <tr>
                                        <th>Max. Appointment</th>
                                        <td><input type="number" class="form-control" placeholder="@lang('messages.maxAppointment')" name="service_max_appointment" id="service_max_appointment" value=""></td>
                                        <td><input type="number" class="form-control" placeholder="@lang('messages.maxAppointment')" name="emergency_max_appointment" id="emergency_max_appointment" value=""></td>
                                        </tr>
                                </thead>
                                <!-- <tbody>
                                    <tr>
                                        <th>Weight&nbsp;<span class="text-danger">* In Percent</span></th>
                                        <th>
                                            <input type="text" class="form-control" placeholder="@lang('messages.TypeOfWeight')" name="weight_type_1" id="weight_type_1" value="" required="required"  />
                                        </th>
                                        <th>
                                            <input type="text" class="form-control" placeholder="@lang('messages.TypeOfWeight')" name="weight_type_2" id="weight_type_2" value=""  />
                                        </th>
                                    </tr>
                                </tbody> -->
                            </table>
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <label>@lang('messages.TimeArrives')&nbsp;(+15 minutes)&nbsp;<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" placeholder="@lang('messages.TimeArrives')" name="edit_time_arrives" id="edit_time_arrives" required="required" value="">
                        <span class="text-danger" id="hourly_rate_err"></span>
                    </div>
                    <div class="form-group">
                        <label>@lang('messages.HourlyCost')&nbsp;<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" placeholder="@lang('messages.HourlyRate')" name="service_hourly_rate" id="service_hourly_rate" required="required" value="">
                        <span class="text-danger" id="hourly_rate_err"></span>
                    </div>
                    <div class="form-group">
                        <label>@lang('messages.CostPerKm')&nbsp;<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="@lang('messages.CostPerKm')" name="cost_per_km" id="cost_per_km" value="" />
                        <span id="title_err"></span>
                    </div>   
                    <div class="form-group workshop_wrecker_call_price" style="display:none">
                        <label>@lang('messages.CallPrice')&nbsp;<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" placeholder="@lang('messages.CallPrice')" name="service_call_price" id="service_call_price"  value="">
                            <span class="text-danger" id="hourly_rate_err"></span>
                    </div> -->
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="form-check form-check-inline">
                            <button type="submit" id="edit_wrecker_services_btn" class="btn bg-blue ml-3">@lang('messages.Submit')<i class="icon-paperplane ml-2"></i></button>
                        </div>
                    </div>
                </form>
            </div>
			<div id="response_err"></div>
		</div>
		<div class="modal-footer"></div>
	</div>
</div>
<!--End-->
<div class="modal" id="wrecker_service_details_modal">
    <div class="modal-dialog">
        <div class="modal-content" style="width: 745px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Wrecker Service Details</h4>
                <hr />
            </div>
            <!-- Modal body -->
            <div id="service_response" style="overflow:auto"></div>
        </div>
        <div class="modal-footer">       
        </div>
    </div>
</div>
@include('workshop.component.common_import')
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
            <a href="#" class="breadcrumb-item">Wrecker Services </a>
            <!-- <span class="breadcrumb-item active"> @lang('messages.SapreItemsList') </span> -->
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
@endpush


