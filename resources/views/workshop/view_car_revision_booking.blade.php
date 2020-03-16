@extends('layouts.master_layouts')
@section('content')
<style>
.table {
    margin-bottom: 18px;
}
.table thead td {
	font-weight: bold;
}
.panel-heading h3 {
	font-weight: bold;
	display: inline-block;
}
.panel-primary .panel-heading {
	color: #1e91cf;
	border-color: #96d0f0;
	background: white;
}
.panel-default {
	border: 1px solid #dcdcdc;
	border-top: 1px solid #dcdcdc;
}
.panel-default .panel-heading {
	color: #4c4d5a;
	border-color: #dcdcdc;
	background: #f6f6f6;
	text-shadow: 0 -1px 0 rgba(50,50,50,0);
    height: 44px;
    border-bottom:1px solid #ddd;
}
.panel .panel-heading {
    padding: 10px;
}
.btn-xs, .btn-group-xs > .btn {
  padding: 1px 5px;
  font-size: 12px;
  line-height: 1.5;
  border-radius: 2px; 
}
.panel-body {
    padding: 15px;
}
</style>
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="content">
  @if(Session::has('msg'))
    {!! Session::get('msg') !!}
  @endif
  <div class="row">
    <div class="col-md-6">
        <div class="panel panel-default card">
            <div class="panel-heading">
                <h6 class="panel-title"><i class="fa fa-shopping-cart"></i> Order Details</h6>
            </div>
            <table class="table">
                <tbody>
                    <tr>
                        <td style="width: 1%;"><button data-toggle="tooltip" title="Customer Name" class="btn btn-info btn-xs"><i class="fa fa-user fa-fw"></i></button></td>
                        <td>{{ $booked_details->f_name ?? "N/A"}}</td>
                    </tr>
                    <tr>
                        <td><button data-toggle="tooltip" title="Email" class="btn btn-info btn-xs"><i class="fa fa-envelope fa-fw"></i></button></td>
                        <td>{{ $booked_details->email }}</td>
                    </tr>
                    <tr>
                        <td><button data-toggle="tooltip" title="Order Date" class="btn btn-info btn-xs"><i class="fa fa-calendar fa-fw"></i></button></td>
                        <td>
                            {{ $booked_details->order_date }}
                        </td>
                    </tr>
                    <tr>
                        <td><button data-toggle="tooltip" title="Booking Date" class="btn btn-info btn-xs"><i class="fa fa-calendar fa-fw"></i></button></td>
                        <td> {{ $booked_details->created_at }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
    <div class="panel panel-default card">
        <div class="panel-heading">
            <h6 class="panel-title"><i class="fa fa-info-circle"></i> Add Services</h6>
        </div>
        <div class="panel-body">
            <form id="add_selected_services">
                
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-left">S No.</th>
                            <th class="text-left">Service Name</th>
                            <th class="text-left">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($unselected_service->count() > 0)
                            @forelse ($unselected_service as $category)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="services_row">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <div class="form-check form-check-inline service_days">
                                                    <label class="form-check-label">
                                                    <input type="hidden" value="{{ $booked_details->id }}" id="car_revision_booking_id" name="car_revision_booking_id">
                                                        <input type="checkbox" data-serviceid="{{ $category->id }}" data-price="{{ $category->price ?? 0 }}"  data-servicename="{{ $category->category_name }}" class="form-control-styled calculate_total_price" name="calculate_total_price[]"  value="{{ $category->id }}" id="services">

                                                        {{ $category->category_name }}<!-- <input type="text" value="{{ $category->category_name }}" class="form-control" style="border:none" name="service_name[]" id="service_name" > -->
                                                    </label>
                                                        <input type="hidden" value="{{ $category->id }}" class="form-control" style="border:none" name="service_id[]" id="service_id" onblur="check_service_rows_data(2)">
                                                
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td> 
                                       &euro; {{ $category->price ?? 0 }} <!-- <input type="text" value="" style="border:none" name="service_price[]" id="service_price" >  -->
                                    </td>
                                </tr>
                            @empty
                            @endforelse 
                        @endif
                        @if($services != FALSE)
                        @forelse ($services as $service)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="services_row">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div class="form-check form-check-inline service_days">
                                                <label class="form-check-label">
                                                <input type="hidden" value="{{ $booked_details->id }}" id="car_revision_booking_id" name="car_revision_booking_id">
                                                    <input type="checkbox" data-serviceid="{{ $service['service_id'] }}" data-price="{{ $service['service_price'] }}" data-servicename="{{ $service['service_name'] }}" class="form-control-styled calculate_total_price" name="calculate_total_price[]"  value="{{ $service['service_id'] }}" id="services" checked>

                                                    {{ $service['service_name'] }}<!-- <input type="text" value="{{ $service['service_name'] }}" class="form-control" style="border:none" name="service_name[]" id="service_name" > -->
                                                </label>
                                                    <input type="hidden" value="{{ $service['service_id'] }}" class="form-control" style="border:none" name="service_id[]" id="service_id" >
                                            
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>&euro;
                                        {{ $service['service_price']  ?? 0}}
                                   <!--  <input type="text" value="" style="border:none" name="service_price[]" id="service_price" >  -->
                                </td>
                            </tr>
                            @empty
                        @endforelse
                        @endif
                        
                            <tr>
                                <td colspan="2" style="text-align:right">Total Price</td>
                                <td>&euro; 
                                <input type="text" id="total_price" name="total_price" value="@if($booked_details->total_price != 0 ) {{ $booked_details->total_price }} @else 0 @endif" style="border:none" />
                                </td>
                            </tr>
                    </tbody>
                </table>
                <div class="row" style="margin-bottom:10px;">
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-success" id="add_services" style="color:white;">Save&nbsp;<span class="glyphicon glyphicon-plus"></span></button type="submit">
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang('messages.Home')</a>
            <span class="breadcrumb-item active"> @lang('messages.ServiceDetails') </span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop
@push('scripts')
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
<script src="{{ url('validateJS/admin.js') }}"></script>
<script src='{{ url("validateJS/vendors.js") }}'></script>
<script src="{{ url('global_assets/js/plugins/media/fancybox.min.js')}}"></script>
<script src="{{ url('global_assets/js/demo_pages/gallery.js')}}"></script>
<script src="{{ url('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>
<script src="{{ url('validateJS/services.js') }}"></script> 
<script src="{{ url('validateJS/date/jquery-ui.css') }}"></script>
<script src="{{ url('validateJS/date/jquery-ui.js') }}"></script>
@endpush


