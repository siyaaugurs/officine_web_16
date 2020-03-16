@extends('layouts.master_layouts')
@section('content')
@if($workshop_status == 100)
<input type="hidden" name="page" id="page" value="{{ $page }}" />
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
                            <a href="<?php echo url("spacial_condition/revision") ?>" class="btn btn-warning"><i class="fa fa-plus"></i>&nbsp;@lang('messages.SpecialPricecondition')</a> 
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-light header-elements-inline">
             <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;Service List</h6>
              <a href='javascript::void()' class="btn btn-warning" id="car_revision_import_export" style="color:white; margin-right:-100px;">Import / Export Details&nbsp;<span class="fa fa-download"></span></a>
             <a href='#' class="btn btn-primary" id="car_revision_details" style="color:white; float:right;" >Add Service Details&nbsp;<span class="glyphicon glyphicon-plus"></span></a>
        </div>
        <table class="table datatable-show-all">
            <thead>
                <tr>
                    <th>@lang('messages.SN')</th>
                    <th>Services</th>
                    <th>Price</th>
                    <th>Max Appointment</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($category_list as $category)
                   @php
                      $service_details = sHelper::get_car_revision_service_detail(Auth::user()->id , $category->id);
                    @endphp
                     @if($service_details != NULL)
                        @php
                            $price = $service_details->price;
                            $max_appointment = $service_details->max_appointment;
                        @endphp
                    @endif 
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>@if(!empty($category->category_name)){{ $category->category_name }} @endif</td>
                        <td>&euro;&nbsp;@if(!empty($price)){{ $price }} @else {{ "N/A" }} @endif</td>
                        <td>@if(!empty($max_appointment)){{ $max_appointment }} @else {{ "N/A" }} @endif</td>
                        <td>
                           <a href="#" class="btn btn-primary edit_car_revision_services" data-appointment="{{ $max_appointment }}" data-id="{{ $category->id }}" data-price="{{ $price }}"><i class="glyphicon glyphicon-edit"></i></a>
                        </td>
                    </tr>
                @empty
                <tr>
                   <td colspan="5">@lang('messages.NoRecordFound')</td>
                    </tr>  
                @endforelse
            </tbody>
        </table>
        <div class="row" style="margin-top:20px;">
                <div class="col-sm-12">
                    @if($category_list->count() > 0)
                    {{ $category_list->links() }}
                    @endif
                </div>
            </div>
    </div>
    <!-- /page length options -->
</div>
<!--Add Services Details-->
<div class="modal" id="add_car_revision_details_popup">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i>
				</button>
				<h4 class="modal-title" id="myModalLabel">Car Revision details </h4>
				<hr />
			</div>
			<!-- Modal body -->
            <div class="card-body">
               <form id="add_services_form" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label>@lang('messages.maxAppointment')&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="@lang('messages.maxAppointment')" value="@if(!empty($service_detail)){{ $service_detail->maximum_appointment }} @endif" name="max_appointment" id="max_appointment" required="required" />
                            <span id="title_err"></span>
                        </div>
                        <div class="form-group">
                            <label>@lang('messages.Price')&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="@lang('messages.Price')" name="price" id="price" required="required" value="{{ !empty($service_detail->price) ? $service_detail->price : ''}}" />
                            <span id="title_err"></span>
                        </div>
                             
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-check form-check-inline">
                                <button type="submit" id="add_car_revision_details_btn" class="btn bg-blue ml-3">@lang('messages.Submit')<i class="icon-paperplane ml-2"></i></button>
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
<!--Add category popup modal-->

<!--
<div class="modal" id="add_category_popup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Add New Service</h4>
                <hr />
            </div>
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
</div>-->
<!--End-->

<!--Add category popup modal-->
<!-- <div class="modal" id="edit_category_popup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Edit Category</h4>
                <hr />
            </div>
            <form id="edit_car_revision_category_form" >
                <input type="text" value="">
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label>@lang('messages.CategoryName')&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="@lang('messages.CategoryName')" name="category_name" id="category_name" required="required"  />
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
</div> -->
<!--Add Services Details-->
<div class="modal" id="edit_services">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i>
				</button>
				<h4 class="modal-title" id="myModalLabel">Edit Car Revision Services </h4>
				<hr />
			</div>
            <!-- Modal body -->
                <div class="card-body">
                   <form id="edit_revision_services_form" autocomplete="off">
                            @csrf
                            <input type="hidden" id="service_id" name="service_id">
                           <div class="row">    
                            <div class="col-sm-12">
                                <div class="form-group">
                                <label>@lang('messages.Price')&nbsp;<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="@lang('messages.Price')" name="price" id="service_price" required="required" value="" />
                                <span id="title_err"></span>
                            </div> 
                            </div>
                            
                           </div>    
                             <div class="row">
                        <div class="col-md-12">
                            <label>@lang('messages.maxAppointment')&nbsp;<span class="text-danger">*</span></label>
                            <!--<textarea name="description" id="description" class="form-control" placeholder="@lang('messages.Description')" required></textarea>
                            <span id="start_date_err"></span>-->
                            <input type="text" class="form-control" placeholder="@lang('messages.maxAppointment')" name="max_appointment" id="service_appointment" required="required" value="" />
                        </div>
                    </div> 
                    <div class="row" style="margin-top:15px;">
                        <div class="col-md-12">
                             <button type="submit" id="add_service_details_btn" class="btn bg-blue ml-3">@lang('messages.Submit')&nbsp;<i class="icon-paperplane ml-2"></i></button>
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
<script src="{{ url('validateJS/admin.js') }}"></script>
<script src="{{ url('validateJS/vendors.js') }}"></script>
@endpush


