<div class="row">
	<div class="col-lg-4">
		<!-- Today's revenue -->
		<div class="card bg-blue-400">
			<a href='{{ url("vendor/service_booking_list")}}' style="text-decoration:none; color:#FFF;">
				<div class="card-body">
					<div class="d-flex">
						<h3 class="font-weight-semibold mb-0">Car Wash Service booking</h3>
						<span
							class="badge bg-teal-800 badge-pill align-self-center ml-auto">{{ $booked_services->count() }}</span>
					</div>
					<div>
						Car Wash Service booking
					</div>
				</div>
			</a>
			<div class="chart" id="today-revenue"></div>
		</div>
		<!-- /today's revenue -->
	</div>
	<div class="col-lg-4">
		<!-- Today's revenue -->
		<div class="card bg-teal-400">
			<a href='{{ url("vendor/car_revision_booking_list")}}' style="text-decoration:none; color:#FFF;">
				<div class="card-body">
					<div class="d-flex">
						<h3 class="font-weight-semibold mb-0">Car Revision Booking</h3>
						<span
							class="badge bg-teal-800 badge-pill align-self-center ml-auto">{{ $booked_car_revision->count() }}</span>
					</div>
					<div>
						Car Revision Booking
					</div>
				</div>
			</a>
			<div class="chart" id="today-revenue"></div>
		</div>
		<!-- /today's revenue -->
	</div>
	<div class="col-lg-4">
		<div class="card bg-teal-400">
			<a href='{{ url("vendor/sos_service_booking")}}' style="text-decoration:none; color:#FFF;">
				<div class="card-body">
					<div class="d-flex">
						<h3 class="font-weight-semibold mb-0">SOS Services Bookings</h3>
						<span
							class="badge bg-teal-800 badge-pill align-self-center ml-auto">{{ $booked_sos->count() }}</span>
					</div>
					<div>
						SOS Services Bookings
					</div>
				</div>
			</a>
			<div class="chart" id="today-revenue"></div>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="card bg-green-400">
			<a href='{{ url("vendor/request_quotes_list")}}' style="text-decoration:none; color:#FFF;">
				<div class="card-body">
					<div class="d-flex">
						<h3 class="font-weight-semibold mb-0">Request Quotes</h3>
						<span class="badge bg-teal-800 badge-pill align-self-center ml-auto">{{ $request_quotes->count() }}</span>
					</div>
					<div>
						Request Quotes
					</div>
				</div>
			</a>
			<div class="chart" id="today-revenue"></div>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="card bg-blue-400">
			<a href='{{ url("vendor/tyre_booking_list")}}' style="text-decoration:none; color:#FFF;">	
			<div class="card-body">
				<div class="d-flex">
					<h3 class="font-weight-semibold mb-0">Tyre Service Bookings</h3>
					<span class="badge bg-teal-800 badge-pill align-self-center ml-auto">{{ $tyre_booking->count() }}</span>
				</div>
				<div>Tyre Service Bookings</div>
			</div>
			</a> 
			<div class="chart" id="today-revenue"></div>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="card bg-teal-400">
			<a href='{{ url("vendor/assemble_booking_list")}}' style="text-decoration:none; color:#FFF;">	
			<div class="card-body">
				<div class="d-flex">
					<h3 class="font-weight-semibold mb-0">Assemble Service Bookings</h3>
					<span class="badge bg-teal-800 badge-pill align-self-center ml-auto">{{ $assemble_booking->count() }}</span>
				</div>
				<div>Assemble Service Bookings</div>
			</div>
			</a> 
			<div class="chart" id="today-revenue"></div>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="card bg-blue-400">
			<a href='{{ url("customer_report/calendar_view")}}' style="text-decoration:none; color:#FFF;">
				<div class="card-body">
					<div class="d-flex">
						<h3 class="font-weight-semibold mb-0">Calendar View</h3>
						<!-- <span class="badge bg-teal-800 badge-pill align-self-center ml-auto">{{ $order_list->count() }}</span> -->
					</div>
					<div>
						Calendar View
					</div>
				</div>
			</a>
			<div class="chart" id="today-revenue"></div>
		</div>

	</div>
	
</div>