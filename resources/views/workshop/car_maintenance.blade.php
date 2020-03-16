@extends('layouts.master_layouts')
@section('content')
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
                            <a href="<?php echo url("spacial_condition/car_maintenance") ?>" class="btn btn-warning"><i class="fa fa-plus"></i>&nbsp;@lang('messages.SpecialPricecondition')</a> 
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-light header-elements-inline">
                <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;@lang('messages.CarMaintinance')</h6>
                 <a href='javascript::void()' class="btn btn-warning" data-serviceid="12" id="car_maintinance_import" style="color:white;">Import / Export Details&nbsp;<span class="fa fa-download"></span></a>
                @if($car_maintainance_details == NULL)
                    <a href='#' class="btn btn-primary" id="car_maintinance_detils" style="color:white; float:right;" >Add Service Details&nbsp;<span class="glyphicon glyphicon-plus"></span></a>
                @else
                    <a href='#' class="btn btn-primary" id="car_maintinance_detils" style="color:white; float:right;" >Edit Service Details&nbsp;<span class="glyphicon glyphicon-edit"></span></a>
                @endif
                
                </div>
            <div class="card-body" id="car_maintinance_ColWrap" style="overflow:auto">
            @include('workshop.component.car_maintinance' , ['car_maintinance_service_list'=>$car_maintinance_service_list])
            
            </div>
    </div>
    <!-- /page length options -->
</div>
<!--Add Services Details-->
<div class="modal" id="add_car_maintanance_details_popup">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i>
				</button>
				<h4 class="modal-title" id="myModalLabel">Car Maintenance details </h4>
				<hr />
			</div>
			<!-- Modal body -->
            <div class="card-body">
               <form id="add_services_form" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label>@lang('messages.HourlyRate')&nbsp;<span class="text-danger">*</span></label>
                            <input type="number" class="form-control" placeholder="@lang('messages.HourlyRate')" name="hourly_rate" id="hourly_rate" required="required" min="1" max="1000" value="{{ !empty($hourly_cost) ? $hourly_cost : ''}}">
                               <span class="text-danger" id="hourly_rate_err"></span>
                        </div>
                        <div class="form-group">
                            <label>@lang('messages.maxAppointment')&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="@lang('messages.maxAppointment')" name="max_appointment" id="max_appointment" required="required" value="{{ !empty($max_appointment) ? $max_appointment : ''}}" />
                            <span id="title_err"></span>
                        </div>      
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-check form-check-inline">
                                <button type="submit" id="add_car_maintainance_details_btn" class="btn bg-blue ml-3">@lang('messages.Submit')<i class="icon-paperplane ml-2"></i></button>
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
<div class="modal" id="edit_car_maintainance_details">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i>
				</button>
				<h4 class="modal-title" id="myModalLabel">Car Maintenance details </h4>
				<hr />
			</div>
			<!-- Modal body -->
            <div class="card-body">
               <form id="edit_car_maintainance_service_details" autocomplete="off">
                        @csrf
                        <input type="hidden" name="items_repairs_servicestimes_id" id="items_repairs_servicestimes_id" value="">
                        <div class="form-group">
                            <label>@lang('messages.HourlyRate')&nbsp;<span class="text-danger">*</span></label>
                            <input type="number" class="form-control" placeholder="@lang('messages.HourlyRate')" name="hourly_rate" id="car_maintainance_hourly_rate" required="required"  value="">
                               <span class="text-danger" id="hourly_rate_err"></span>
                        </div>
                        <div class="form-group">
                            <label>@lang('messages.maxAppointment')&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="@lang('messages.maxAppointment')" name="max_appointment" id="car_maintainance_max_appointment" required="required" value="" />
                            <span id="title_err"></span>
                        </div>      
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-check form-check-inline">
                                <button type="submit" id="edit_car_maintainance_details_btn" class="btn bg-blue ml-3">@lang('messages.Submit')<i class="icon-paperplane ml-2"></i></button>
                            </div>
                        </div>
                    </form>
            </div>
			<div id="response_err"></div>
		</div>
		<div class="modal-footer"></div>
	</div>
</div>
<!--Add category popup modal-->
<div class="modal" id="add_category_popup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Add New Service</h4>
                <hr />
            </div>
            <!-- Modal body -->
            <form id="add_car_revision_category_form" >
                <input type="hidden" value="" name="category_id" id="category_id" />
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label>Service Name&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="@lang('messages.ServiceName')" name="category_name" id="category_name" required="required"  />
                            <span id="start_date_err"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                        <label>@lang('messages.Price')&nbsp;<span class="text-danger">*</span></label>
                        <input type="number"  name="price" id="price" placeholder="@lang('messages.Price')" class="form-control"/>
                        <span id="start_date_err"></span>
                        </div>
                    </div>
                    <span id="add_response"></span>
                    <span id="err_response"></span>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="form-check form-check-inline">
                            <button type="submit" id="car_revision_submit" class="btn bg-blue ml-3">@lang('messages.Submit') <i class="icon-paperplane ml-2"></i></button>
                        </div>
                    </div>
				</div>
			</form>
        </div>
        <div class="modal-footer">       
        </div>
    </div>
</div>
<!--End-->
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
            <a href="#" class="breadcrumb-item">@lang('messages.Admin') </a>
            <span class="breadcrumb-item active"> Service List </span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop
@push('scripts')
<script src="{{ asset('validateJS/import_export.js') }}"></script>
<script src='{{ url("validateJS/car_wash.js") }}'></script>
<script src='{{ url("validateJS/car_maintinance.js") }}'></script>
<script src="{{ url('validateJS/admin.js') }}"></script>
<script src="{{ url('validateJS/vendors.js') }}"></script>
<script src="{{ url('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
 <script src="{{ url('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>
@endpush


