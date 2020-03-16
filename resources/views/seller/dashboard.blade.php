@extends('layouts.master_layouts')
@section('content')
<div class="content">
    <!-- Inner container -->
    <div class="row">
						<div class="col-lg-4">
							<!-- Members online -->
							<div class="card bg-success-400">
                              <a href='#' style="text-decoration:none; color:#FFF;">
								<div class="card-body">
									<div class="d-flex">
										<h3 class="font-weight-semibold mb-0">5000</h3>
										
				                	</div>
				                <div>
										All Products
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
							<!-- Members online -->
							<div class="card bg-teal-400">
                              <a href='#' style="text-decoration:none; color:#FFF;">
								<div class="card-body">
									<div class="d-flex">
										<h3 class="font-weight-semibold mb-0">100</h3>
										<span class="badge bg-teal-800 badge-pill align-self-center ml-auto">100%<span>
				                	</div>
				                <div>
										Today Order
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
								 <a href='#' style="text-decoration:none; color:#FFF;">
                                <div class="card-body">
									<div class="d-flex">
										<h3 class="font-weight-semibold mb-0">100</h3>
                                        <span class="badge bg-teal-800 badge-pill align-self-center ml-auto">100%</span>
							    	</div>
				                	<div>
										Total Order
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
							   <a href='#' style="text-decoration:none; color:#FFF;">	
                                <div class="card-body">
									<div class="d-flex">
										<h3 class="font-weight-semibold mb-0">2000</h3>
										  <span class="badge bg-teal-800 badge-pill align-self-center ml-auto">100%</span>
                                    </div>
				                	<div>
										Pending order 
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
							   <a href='{{ url("seller/seller_order_list")}}' style="text-decoration:none; color:#FFF;">	
                                <div class="card-body">
									<div class="d-flex">
										<h3 class="font-weight-semibold mb-0">Products Orders</h3>
										  <span class="badge bg-teal-800 badge-pill align-self-center ml-auto">{{ $product_order_list->count() }}</span>
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
                    </div>
    <!-- /inner container -->
</div>
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="../vendors/index.html" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
            <a href="../vendors/internationalization_fallback.html" class="breadcrumb-item">Seller</a>
            <span class="breadcrumb-item active"> dashboard</span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>   
</div>
@stop
@push('script')
<link href="../vendors/{{ url("cdn/css/croppie.css") }}" />
@endpush

@push('custom_script')
<script src="{{ url('cdn/js/croppie.js') }}"></script>
@endpush