@extends('layouts.master_layouts')
@section('content')
<style>
.fc-day-top{ cursor:pointer; }
</style>
	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="{{ asset('global_assets/css/icons/icomoon/styles.min.css
') }}" rel="stylesheet" type="text/css">
	 <script src="{{ asset('global_assets/js/main/bootstrap.bundle.min.js') }}"></script>
	<script src="{{ asset('global_assets/js/plugins/loaders/blockui.min.js') }}"></script>
	<script src="{{ asset('global_assets/js/plugins/ui/fullcalendar/core/main.min.js') }}"></script>
	<script src="{{ asset('global_assets/js/plugins/ui/fullcalendar/daygrid/main.min.js') }}"></script>
	<script src="{{ asset('global_assets/js/plugins/ui/fullcalendar/timegrid/main.min.js') }}"></script>
	<script src="{{ asset('global_assets/js/plugins/ui/fullcalendar/list/main.min.js') }}"></script>
	<script src="{{ asset('global_assets/js/plugins/ui/fullcalendar/interaction/main.min.js') }}"></script>
	<script src="{{ asset('global_assets/js/demo_pages/fullcalendar_basic.js') }}"></script>
	<!-- /theme JS files -->
</head>
<body>
	<!-- Page content -->
	<div class="page-content">
		<!-- Main content -->
		<div class="content-wrapper">
			<div class="content">
				<!-- Basic view -->
				<div class="card">
					<div class="card-header header-elements-inline">
						<h5 class="card-title">Calendar View </h5>
						<div class="header-elements">
						<!-- 	<div class="list-icons">
		                		<a class="list-icons-item" data-action="collapse"></a>
		                		<a class="list-icons-item" data-action="reload"></a>
		                		<a class="list-icons-item" data-action="remove"></a>
		                	</div> -->
	                	</div>
					</div>
					<div class="card-body">
						<!-- <p class="mb-3">FullCalendar is a jQuery plugin that provides a full-sized, drag &amp; drop event calendar like the one below. It uses AJAX to fetch events on-the-fly and is easily configured to use your own feed format. It is visually customizable with a rich API. Example below demonstrates a default view of the calendar with a basic setup: draggable and editable events, and starting date.</p> -->
						<div class="fullcalendar-basic"></div>
					</div>
				</div>
			</div>			
		</div>
	</div>
 </body>
</html>
<!--Show Open modal popup script start-->
<div class="modal show" id="show_service_booking_detail">
    <div class="modal-dialog">
        <div class="modal-content" style="width:1000px; margin-left:-100px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Service Booking Details</h4>
                <hr>
            </div>
            <!-- Modal body -->
            <form id="add_car_revision_service_form">
                <input type="hidden" value="" name="category_id" id="edit_category_id">
                <div class="modal-body">
                    <div id="service_response"></div>
                </div>
			</form>
        </div>
        <div class="modal-footer">       
        </div>
    </div>
</div>

<!--End-->
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>@lang('messages.Home')</a>
            <a href="#" class="breadcrumb-item">@lang('messages.Admin')</a>
            <span class="breadcrumb-item active">Calendar View</span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop
@push('scripts')
<script>
$(document).ready(function(e) {
   $(document).on('click','.fc-content-skeleton table tr td',function(e){
		date_param = $(this);
		selected_date = date_param.data('date');
		if(selected_date != "undefined"){
			$("#preloader").show();
			$.ajax({
					url: base_url+"/customer_report_ajax/get_daily_service_booking",
					type: "GET",        
					data: {'selected_date':selected_date},
					success: function(data){
						$("#preloader").hide();
						$("#service_response").html(data);
						$("#show_service_booking_detail").modal({
						  backdrop:'static',
						  keyboard:false  
						});
					} 
				});
		}
	}); 
});
</script>
@endpush


