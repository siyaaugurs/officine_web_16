@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
@if(Session::has('msg'))
{!!  Session::get("msg") !!}
@endif
@if($workshop_status == 100) 
<div class="card" style="margin-bottom:10px;">
    <div class="content">
        <div id="filter-panel">
            <div class="panel panel-default">
                <div class="panel-body">
                <div class="row">
                    <div class="col-sm-6">
                        <a href="<?php echo url("spacial_condition/washing") ?>" class="btn btn-warning"><i class="fa fa-plus"></i>&nbsp;@lang('messages.SpecialPricecondition')</a> 
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
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
                            <label>@lang('messages.SelectCategory')</label>
                            <select name="washing_category" id="search_washing_category" class="form-control">
                                <option hidden="hidden">--Selecty--Category--</option>
                                <option value="0" >Select All </option>
                                                                
                                @forelse($car_washing_category as $category)
                                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                @empty
                                @endforelse
                            </select>                               
                        </div> 
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Html Demo-->
<div class="card">
    <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;@lang('messages.CarWashServiceList')
        </h6>
           <a href='javascript::void()' class="btn btn-warning" id="car_washing_import_export" style="color:white; margin-right:-100px;">Import / Export Details&nbsp;<span class="fa fa-download"></span></a>
        <a href='#' class="btn btn-primary" id="add_services" style="color:white; float:right;">Edit Service Details&nbsp;<span class="glyphicon glyphicon-edit"></span></a>
     
     
    </div>
    <div class="card-body" id="user_data_body">
        <table class="table">
         <thead>
          <tr>
            <th>SN.</th>
            <th>Services</th>
            <th>Description</th>
            <th>Car Size</th>
            <th>Time</th>
            <th>Hourly Cost</th>
            <th>Price</th>
            <th>Max. Appointment</th>
            <th>Action</th>
          </tr>
         </thead> 
         <tbody>
         @php  $i = 1; @endphp
          @foreach($car_washing_category as $services) 
             @foreach($car_size as $key=>$size_value)
               @php 
                 $enctype_id = $services->id."/".$key;
                 $enc_type_s_id = base64_encode($enctype_id); 
                 $service_price = sHelper::car_wash_price_max_appointment(Auth::user()->id , $services->id , $key);
               @endphp
               <tr>
                <td>{{ $i }}</td>
                <td>{{ $services->category_name }}</td>
                <td>{{ $services->description }}</td>
                <td>{{ $size_value }}</td>
                <td>
                @php
                 $service_average_time =  sHelper::get_car_wash_service_time($key , $services->id);
                 @endphp
                {{ !empty($service_average_time) ? $service_average_time : "N/A" }}</td>
                <td>&euro;&nbsp;{{ !empty($service_price['hourly_rate']) ? $service_price['hourly_rate'] : "N/A" }}</td>
                <td>
               @php $price =  sHelper::calculate_service_price($service_average_time ,$service_price['hourly_rate']) 
               @endphp 
               &euro;&nbsp;{{ $price }}
               </td>
                <td>{{ !empty($service_price['max_appointment']) ? $service_price['max_appointment'] : "N/A" }}</td>
                <td>
                    <a href="#" data-serviceid="<?php echo $services->id; ?>" data-size="<?php if(!empty($key))echo $key; ?>" data-toggle="tooltip" data-placement="top" title="Edit services details"  class="btn btn-primary btn-sm edit_service_details"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;
                   
                    <a data-toggle="tooltip" data-placement="top" title="view services details" href='{{ url("vendor/view_services/$enc_type_s_id") }}' class="btn btn-warning btn-sm"><i class="glyphicon glyphicon-eye-open"></i></a>
                </td> 
           </tr>
             @php $i++ @endphp
             @endforeach
          @endforeach 
         </tbody> 
        </table> 
    </div>
</div>
<!--End-->
<!--Import Export car washing details-->
<div class="modal" id="import_export_car_washing_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Import / Export Car Wasing Details</h4>
                <hr />
            </div>
            <div class="card-body">
                <a href="<?php echo url('export/car_washing') ?>" class="btn btn-warning"><i class="fa fa-download"></i>&nbsp;Export Car Washing Details</a>
                <hr />
                <h3 style="font-weight:600;">Import Excel Files </h3> 
                <form id="import_car_washing_file" name="import_car_washing_file">
                @csrf
                   <span id="tyre_msg_response"></span>
                  <div class="control-group" id="fields">
                        <label class="control-label" for="field1">
                            Browse Files
                        </label>
                        <div class="controls">
                            <div class="entry input-group col-xs-3">
                                <input class="btn btn-primary" name="car_washing_file" type="file"  accept=".csv" required>
                                <span class="input-group-btn">
                        &nbsp;&nbsp;
                        <button class="btn btn-success btn-add" type="submit" id="import_car_washing">
                        Import  Car Washing Details
                        <span class="glyphicon glyphicon-import"></span>
                                </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal-footer">
        </div>
    </div>
</div>
<!--End-->
<!--Manage time slot services-->
<div class="modal" id="add_car_washing_services">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i>
				</button>
				<h4 class="modal-title" id="myModalLabel">Car Wash details </h4>
				<hr />
			</div>
			<!-- Modal body -->
            <div class="card-body">
                @if($service_days->count() > 0) 
                    <form id="add_services_form" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label>@lang('messages.HourlyRate')&nbsp;<span class="text-danger">*</span></label>
                            <input type="number" class="form-control" placeholder="@lang('messages.HourlyRate')" name="hourly_rate" id="hourly_rate" required="required" min="1" max="1000" value="{{ !empty($workshop_payment_details->hourly_rate) ? $workshop_payment_details->hourly_rate : ''}}">
                               <span class="text-danger" id="hourly_rate_err"></span>
                        </div>
                        <div class="form-group">
                            <label>@lang('messages.maxAppointment')&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="@lang('messages.maxAppointment')" name="max_appointment" id="max_appointment" required="required" value="{{ !empty($workshop_payment_details->max_appointment) ? $workshop_payment_details->max_appointment : ''}}" />
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-check form-check-inline">
                                <button type="submit" id="add_services_btn_copy" class="btn bg-blue ml-3">@lang('messages.Submit')<i class="icon-paperplane ml-2"></i></button>
                            </div>
                        </div>
                    </form>
                @else
                    <h2>First Complete your profile , and fill your workshop timing </h2>
                    <a href="{{ url('add_time_details') }}" class="btn btn-primary">Go >></a>
                @endif 
            </div>
			<div id="response_err"></div>
		</div>
		<div class="modal-footer"></div>
	</div>
</div>
<div class="modal" id="edit_service_details_popup">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i>
				</button>
				<h4 class="modal-title" id="myModalLabel">Car Wash details</h4>
				<hr />
			</div>
            <div class="card-body">
                <form id="edit_services_form" autocomplete="off">
                    @csrf
                    <input type="hidden" name="category_id" id="washing_service_id" value="" readonly="readonly">
                    <input type="hidden" name="car_size" id="car_size" value="" readonly="readonly">
                    <div class="form-group">
                        <label>@lang('messages.HourlyRate')&nbsp;<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" placeholder="@lang('messages.HourlyRate')" name="hourly_rate" id="washing_hourly_rate" required="required" min="1" max="1000" value="">
                            <span class="text-danger" id="hourly_rate_err"></span>
                    </div>
                    <div class="form-group">
                        <label>@lang('messages.maxAppointment')&nbsp;<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="@lang('messages.maxAppointment')" name="max_appointment" id="washing_max_appointment" required="required" value="" />
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
@else
 <div>
<!-- Row Complete Start  -->
	<div class="row card" style="padding:40px;">
		<div class="col-lg-12">
            <h3 align="center">Please Complete your Profile </h3>
           <!--/* <p align="center">Connect with your contacts and never lose touch</p>*/-->
            <p align="center"><a href="{{ url('')}}" class="btn btn-primary">Manage Profile</a></p>
        </div>
<!-- Row Complete End  -->
    </div>
<!-- Page Start End  -->
</div>
@endif
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
            <a href="#" class="breadcrumb-item">Workshop  </a>
            <a href="#" class="breadcrumb-item">Car Wash Services </a>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop
@push('scripts')
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" />
    <script src="{{ asset('validateJS/import_export.js') }}"></script>
    <script src="{{ asset('validateJS/service_slot.js') }}"></script> 
    <script src='{{ url("validateJS/car_wash.js") }}'></script>
    <script src="{{ url('validateJS/services.js') }}"></script> 
    <script src="{{ url('validateJS/vendor.js') }}"></script>
    <script src="{{ url('validateJS/date/jquery-ui.css') }}"></script>
    <script src="{{ url('validateJS/date/jquery-ui.js') }}"></script>
<script src="{{ url('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ url('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>
@endpush

