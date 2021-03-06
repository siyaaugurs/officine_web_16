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
                            <a href="<?php echo url("spacial_condition/tyre24") ?>" class="btn btn-warning"><i class="fa fa-plus"></i>&nbsp;@lang('messages.SpecialPricecondition')</a> 
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-light header-elements-inline">
             <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;@lang('messages.Tyre24GroupList')</h6>
               <a href='javascript::void()' class="btn btn-warning" id="tyre_import_export" style="color:white; margin-right:-100px;">Import / Export Details&nbsp;<span class="fa fa-download"></span></a>
             <a href='#' class="btn btn-primary" id="workshop_tyre24_group_details" style="color:white; float:right;" >Add Workshop Tyre24 Group Details&nbsp;<span class="glyphicon glyphicon-plus"></span></a>
        </div>
        <table class="table datatable-show-all">
            <thead>
                <tr>
                    <th>SN.</th>
                    <th>Services</th>
                    <th>Description</th>
                    <th>Range From</th>
                    <th>Range To</th>
                    <th>Time</th>
                    <th>Hourly Rate</th>
                    <th>Price</th>
                    <th>Max. Appointment</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($workshop_tyre24_category as $services)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $services->category_name }}</td>
                        <td>{{ $services->description }}</td>
                        <td>{{ $services->range_from }}</td>
                        <td>{{ $services->range_to }}</td>
                        <td>{{ $services->time }}</td>
                        <td>&euro;&nbsp; {{ $services->hourly_rate }}</td>
                        <td>&euro;&nbsp;{{ $services->service_price }}</td>
                        <td>{{ $services->max_appointment }}</td>
                        <td><a href="#" data-max_appointment="{{ $services->max_appointment }}" data-id="{{ $services->id }}" data-hourly_rate="{{ $services->hourly_rate }}" data-toggle="tooltip" data-placement="top" title="Edit services details"  class="btn btn-primary btn-sm edit_workshop_tyre24_group_services"><span class="glyphicon glyphicon-edit"></span></a>
                        </td>
                    </tr>
                @empty
                <tr>
                   <td colspan="10">@lang('messages.NoRecordFound')</td>
                </tr>  
                @endforelse
            </tbody>
        </table>
        <div class="row" style="margin-top:20px;">
                <div class="col-sm-12">
                   
                </div>
            </div>
    </div>
    <!-- /page length options -->
</div>
<!--Import Export car Assemble Services -->
<div class="modal" id="import_export_tyre_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Import / Export Tyre Service Details</h4>
                <hr />
            </div>
            <div class="card-body">
                <a href="<?php echo url('export/tyre_service_detail') ?>" class="btn btn-warning"><i class="fa fa-download"></i>&nbsp;Export Tyre Service Detail</a>
                <hr />
                <h3 style="font-weight:600;">Import Excel Files </h3> 
                <form id="import_tyre_service_details" >
                @csrf
                  <span id="import_file_response"></span>
                  <div class="control-group" id="fields">
                        <label class="control-label" for="field1">
                            Browse Files
                        </label>
                        <div class="controls">
                            <div class="entry input-group col-xs-3">
                                <input class="btn btn-primary" name="tyre_service_file" type="file"  accept=".csv" required>
                                <span class="input-group-btn">
                        &nbsp;&nbsp;
                        <button class="btn btn-success btn-add" type="submit" id="import_services">
                        Import 
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
<!--Add Services Details-->
<div class="modal" id="add_workshop_tyre24_group_details_popup">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i>
				</button>
				<h4 class="modal-title" id="myModalLabel">Add Workshop Tyre24 Group Detail</h4>
				<hr />
			</div>
			<!-- Modal body -->
            <div class="card-body">
               <form id="add_services_form" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label>@lang('messages.maxAppointment')&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="@lang('messages.maxAppointment')" value="@if(!empty($workshop_tyre24_detail)){{ $workshop_tyre24_detail->maximum_appointment }} @endif" name="max_appointment" id="max_appointment" required="required" />
                            <span id="title_err"></span>
                        </div>
                        <div class="form-group">
                            <label>@lang('messages.HourlyRate')&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="@lang('messages.HourlyRate')" name="hourly_rate" id="hourly_rate" required="required" value="{{ !empty($workshop_tyre24_detail->hourly_rate) ? $workshop_tyre24_detail->hourly_rate : ''}}" />
                            <span id="title_err"></span>
                        </div>
                             
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-check form-check-inline">
                                <button type="submit" id="add_workshop_tyre24_group_details_btn" class="btn bg-blue ml-3">@lang('messages.Submit')<i class="icon-paperplane ml-2"></i></button>
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
<!--Add Services Details-->
<div class="modal" id="edit_workshop_tyre24_services">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i>
				</button>
				<h4 class="modal-title" id="myModalLabel">Edit Workshop Tyre24 Group Detail </h4>
				<hr />
			</div>
            <!-- Modal body -->
            <div class="card-body">
                <form id="edit_workshop_tyre24_group_form" autocomplete="off">
                    @csrf
                    <input type="hidden" id="group_id" name="group_id">
                    <div class="row">    
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>@lang('messages.HourlyRate')&nbsp;<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="@lang('messages.HourlyRate')" name="hourly_rate" id="workshop_tyre24_hourly_rate" required="required" value="" />
                                <span id="title_err"></span>
                            </div> 
                        </div>
                    
                    </div>    
                    <div class="row">
                        <div class="col-md-12">
						<div class="form-group">
                            <label>@lang('messages.maxAppointment')&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="@lang('messages.maxAppointment')" name="max_appointment" id="workshop_tyre24_max_appointment" required="required" value="" />
                        </div>
						</div>
                    </div> 
						<!-- <div class="row">
							<div class="col-md-12">
							<div class="form-group">
							 <label>Delivery Days &nbsp;<span class="text-danger">*</span></label>
                             <input type="text" name="delivery_days" id="workshop_tyre24_delivery_days"  placeholder="Delivery Days"  required="required"  class="form-control"  /> 
							</div>	 
							</div>
						</div>
						
						  <div class="row">
						  <div class="col-md-12">
                             <label>PFU &nbsp;<span class="text-danger">*</span></label>
                              <input type="text" name="PFU" id="workshop_tyre24_PFU"  placeholder="PFU (Expenditure)"  required="required" class="form-control"  />  
                            </div>
                        </div> -->
                    <div class="row" style="margin-top:15px;">
                        <div class="col-md-12">
                            <button type="submit" id="edit_workshop_tyre24_service_price_btn" class="btn bg-blue ml-3">@lang('messages.Submit')&nbsp;<i class="icon-paperplane ml-2"></i></button>
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
<!--End-->
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
            <a href="#" class="breadcrumb-item">@lang('messages.Workshop') </a>
            <span class="breadcrumb-item active"> Workshop Tyre24 </span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop
@push('scripts')
<script src="{{ asset('validateJS/import_export.js') }}"></script>
<script src='{{ url("validateJS/add_group.js") }}'></script>
@endpush