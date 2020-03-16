@extends('layouts.master_layouts')
@section('content')
<div class="content">
    @if (Auth::check() && Session::get('users_roll_type') == 2) 
	    @include('common.component.common_vendor_dashboard')
    @elseif(Auth::check() && Session::get('users_roll_type') == 1) 
    @else
    <div class="row">
						<div class="col-lg-4">
							<!-- Members online -->
							<div class="card bg-teal-400">
                              <a href='{{ url("admin/users_list")}}' style="text-decoration:none; color:#FFF;">
								<div class="card-body">
									<div class="d-flex">
										<h3 class="font-weight-semibold mb-0">{{ number_format($all_users) ?? 0}}</h3>
										<span class="badge bg-teal-800 badge-pill align-self-center ml-auto">100%<span>
				                	</div>
				                <div>
									@lang('messages.AllUsers')
									</div>
								</div>
								<div class="container-fluid">
									<div class="chart" id="members-online"></div>
								</div>
                               </a> 
							</div>
							<!-- /members online -->
						</div>
						<div class="col-lg-4">
							<!-- Current server load -->
							<div class="card bg-pink-400">
								 <a href='{{ url("admin/users_list/1")}}' style="text-decoration:none; color:#FFF;">
                                <div class="card-body">
									<div class="d-flex">
										<h3 class="font-weight-semibold mb-0">{{ number_format($all_sellers) ?? 0}}</h3>
                                        <span class="badge bg-teal-800 badge-pill align-self-center ml-auto">{{ sHelper::get_percentage($all_users , $all_sellers) }}</span>
							    	</div>
				                	<div>
										@lang('messages.Sellers')
										<div class="font-size-sm opacity-75"></div>
									</div>
								</div>
                                </a>
								<div class="chart" id="server-load"></div>
							</div>
							<!-- /current server load -->
						</div>
						<div class="col-lg-4">
							<!-- Today's revenue -->
							<div class="card bg-blue-400">
							   <a href='{{ url("admin/users_list/2")}}' style="text-decoration:none; color:#FFF;">	
                                <div class="card-body">
									<div class="d-flex">
										<h3 class="font-weight-semibold mb-0">{{ number_format($all_vendors) ?? 0}}</h3>
										  <span class="badge bg-teal-800 badge-pill align-self-center ml-auto">{{ sHelper::get_percentage($all_users , $all_vendors) }}</span>
                                    </div>
				                	<div>
										@lang('messages.Workshop')
									</div>
								</div>
                               </a> 

								<div class="chart" id="today-revenue"></div>
							</div>
							<!-- /today's revenue -->

						</div>
                        <div class="col-lg-4">
							<!-- Today's revenue -->
							<div class="card bg-blue-400">
							   <a href='{{ url("admin/customer_report")}}' style="text-decoration:none; color:#FFF;">	
                                <div class="card-body">
									<div class="d-flex">
										<h3 class="font-weight-semibold mb-0">{{ number_format($all_customers) ?? 0}}</h3>
										  <span class="badge bg-teal-800 badge-pill align-self-center ml-auto">{{ sHelper::get_percentage($all_users , $all_customers) }}</span>
                                    </div>
				                	<div>
										@lang('messages.Customers')
									</div>
								</div>
                               </a> 
								<div class="chart" id="today-revenue"></div>
							</div>
							<!-- /today's revenue -->
						</div>
						<div class="col-lg-4">
							<!-- Today's revenue -->
							<div class="card bg-blue-400">
							   <a href='{{ url("admin/service_booking_list")}}' style="text-decoration:none; color:#FFF;">	
                                <div class="card-body">
									<div class="d-flex">
										<h3 class="font-weight-semibold mb-0">Service booking</h3>
										  <span class="badge bg-teal-800 badge-pill align-self-center ml-auto">{{ $service_list->count() }}</span>
                                    </div>
				                	<div>
										Service Booking
									</div>
								</div>
                               </a> 
								<div class="chart" id="today-revenue"></div>
							</div>
							<!-- /today's revenue -->
						</div>
							<div class="col-lg-4">
							<!-- Today's revenue -->
							<div class="card bg-blue-400">
							   <a href='{{ url("admin/order_list")}}' style="text-decoration:none; color:#FFF;">	
                                <div class="card-body">
									<div class="d-flex">
										<h3 class="font-weight-semibold mb-0">Products Orders</h3>
										  <span class="badge bg-teal-800 badge-pill align-self-center ml-auto">{{ $order_list->count() }}</span>
                                    </div>
				                	<div>
									Products Orders
									</div>
								</div>
                               </a> 
								<div class="chart" id="today-revenue"></div>
							</div>
							<!-- /today's revenue -->
						</div>
						<div class="col-lg-4">
							<!-- Today's revenue -->
							<div class="card bg-blue-400">
							   <a href='{{ url("admin/customer_report/support_tickets")}}' style="text-decoration:none; color:#FFF;">	
                                <div class="card-body">
									<div class="d-flex">
										<h3 class="font-weight-semibold mb-0">Customer Support Ticket</h3>
										  <span class="badge bg-teal-800 badge-pill align-self-center ml-auto">{{ $tickets->count() }}</span>
                                    </div>
				                	<div>
									Customer Support Ticket
									</div>
								</div>
                               </a> 
								<div class="chart" id="today-revenue"></div>
							</div>
							<!-- /today's revenue -->
						</div>
						<!--/*<div class="col-lg-4">
							<div class="card bg-blue-400">
							   <a href='{{ url("admin/customer_report/calendar_view")}}' style="text-decoration:none; color:#FFF;">	
                                <div class="card-body">
									<div class="d-flex">
								<h3 class="font-weight-semibold mb-0">Calendar View</h3>
								  <span class="badge bg-teal-800 badge-pill align-self-center ml-auto">{{ $order_list->count() }}</span>
                                    </div>
				                	<div>
									Calendar View 
									</div>
								</div>
                               </a> 
								<div class="chart" id="today-revenue"></div>
							</div>
						</div>*/-->
						<div class="col-lg-4">
							<div class="card bg-green-400">
							   <a href='{{ url("admin/kromeda_monitoring")}}' style="text-decoration:none; color:#FFF;">	
                                <div class="card-body">
									<div class="d-flex">
								<h3 class="font-weight-semibold mb-0">Kromeda Monitoring</h3>
								  <span class="badge bg-teal-800 badge-pill align-self-center ml-auto"></span>
                                    </div>
				                	<div>
									Kromeda Monitoring
									</div>
								</div>
                               </a> 
							</div>
						</div>
							<div class="col-lg-4">
							<div class="card bg-blue-400">
							   <a href='{{ url("admin/add_master_bonus")}}' style="text-decoration:none; color:#FFF;">	
                                <div class="card-body">
									<div class="d-flex">
										<h3 class="font-weight-semibold mb-0">Manage Bonus Amount </h3>
										 
                                    </div>
				                	<div>
									Admin Bonus Amount
									</div>
								</div>
                               </a> 
								<div class="chart" id="today-revenue"></div>
							</div>
						</div>

					</div>
    @endif

</div>
<div id="myMap"></div>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD7OIFvK1-udIFDgZwvY7FVTFHMHipNy6Y">
</script>
<script type="text/javascript"> 
  var map;
  var marker;
  var myLatlng = new google.maps.LatLng(20.268455824834792,85.84099235520011);
  var geocoder = new google.maps.Geocoder();
  var infowindow = new google.maps.InfoWindow();
  function initialize(){
  var mapOptions = {
  zoom: 18,
  center: myLatlng,
  mapTypeId: google.maps.MapTypeId.ROADMAP
  };
  map = new google.maps.Map(document.getElementById("myMap"), mapOptions);

  marker = new google.maps.Marker({
  map: map,
  position: myLatlng,
  draggable: true 
  }); 

  geocoder.geocode({'latLng': myLatlng }, function(results, status) {
  if (status == google.maps.GeocoderStatus.OK) {
  if (results[0]) {
  $('#latitude,#longitude').show();
  $('#address').val(results[0].formatted_address);
  $('#latitude').val(marker.getPosition().lat());
  $('#longitude').val(marker.getPosition().lng());
  infowindow.setContent(results[0].formatted_address);
  infowindow.open(map, marker);
  }
  }
  });

  google.maps.event.addListener(marker, 'dragend', function() {

  geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
  if (status == google.maps.GeocoderStatus.OK) {
  if (results[0]) {
  $('#address').val(results[0].formatted_address);
  $('#latitude').val(marker.getPosition().lat());
  $('#longitude').val(marker.getPosition().lng());
  infowindow.setContent(results[0].formatted_address);
  infowindow.open(map, marker);
  }
  }
  });
  });

}
google.maps.event.addDomListener(window, 'load', initialize);
</script>

@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="{{ url('/') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>@lang('messages.Home')</a>
            <a href="{{ url('/') }}" class="breadcrumb-item">@lang('messages.Admin')</a>
            <span class="breadcrumb-item active"> @lang('messages.Dashboard')</span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>   
</div>
@stop
@push('script')

<link href='{{ url("cdn/css/croppie.css") }}' />
@endpush

@push('custom_script')
<script src="{{ url('cdn/js/croppie.js') }}"></script>
@endpush