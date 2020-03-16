@extends('layouts.master_layouts')
@section('content')
<div class="content">
				<!-- Inner container -->
				<div class="d-md-flex align-items-md-start">
					<!-- Left sidebar component -->
					<div class="sidebar sidebar-light bg-transparent sidebar-component sidebar-component-left wmin-300 border-0 shadow-0 sidebar-expand-md">
						<!-- Sidebar content -->
						<div class="sidebar-content">
							<!-- Navigation -->
							<div class="card">
								<div class="card-body bg-indigo-400 text-center card-img-top" style="background-image: url(file:///D|/xampp/htdocs/global_assets/images/backgrounds/panel_bg.png); background-size: contain;">
									<div class="card-img-actions d-inline-block mb-3">
									   @if(!empty($users_profile->profile_image) && file_exists("storage/profile_image/$users_profile->profile_image"))	
                                        <img class="img-fluid rounded-circle" src='{{ url("storage/profile_image/$users_profile->profile_image") }}' width="170" height="170" alt="">
                                       @else
                                        <img class="img-fluid rounded-circle" src="{{ asset('storage/profile_image/default.png') }}" width="170" height="170" alt="">  
                                       @endif 
										<div class="card-img-actions-overlay rounded-circle">
											<a href="#" id="profile_image" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round">
												<i class="icon-camera"></i>
											</a>
										</div>
									</div>
						    		<h6 class="font-weight-semibold mb-0">{{ Auth::user()->f_name }} </h6>
						    		<span class="d-block opacity-75">Workshop dashboard</span>
					    			<!--<div class="list-icons list-icons-extended mt-3">
				                    	<a href="#" class="list-icons-item text-white" data-popup="tooltip" title="" data-container="body" data-original-title="Google Drive"><i class="icon-google-drive"></i></a>
				                    	<a href="#" class="list-icons-item text-white" data-popup="tooltip" title="" data-container="body" data-original-title="Twitter"><i class="icon-twitter"></i></a>
				                    	<a href="#" class="list-icons-item text-white" data-popup="tooltip" title="" data-container="body" data-original-title="Github"><i class="icon-github"></i></a>
			                    	</div>-->
						    	</div>
								<div class="card-body p-0">
									<ul class="nav nav-sidebar mb-2">
										<li class="nav-item">
											<a href="{{ url('/') }}" class="nav-link active">
												<i class="icon-user"></i>
												 My profile
											</a>
										</li>
										<li class="nav-item">
											<a href='{{ url("gallery") }}' class="nav-link" >
												<i class="icon-link"></i>
												Gallery
											</a>
										</li>
										<li class="nav-item">
											<a href='{{ url("add_business_details") }}' class="nav-link" >
												<i class="icon-link"></i>
												Business Details
											</a>
										</li>
                                        <li class="nav-item">
											<a href='{{ url("bank_details") }}' class="nav-link" >
												<i class="icon-link"></i>
												Bank Details
											</a>
										</li>
 										<li class="nav-item">
											<a href='{{ url("add_contact_details") }}' class="nav-link" >
												<i class="icon-phone"></i>
												Contact Details
											</a>
										</li>
                                        <li class="nav-item">
											<a href='{{ url("add_address_details") }}' class="nav-link" >
												<i class="icon-address-book"></i>
												Address Details
											</a>
										</li>
                                        <li class="nav-item">
											<a href='{{ url("manage_time_slot") }}' class="nav-link" >
												<i class="icon-link"></i>
												Workshop Timing
											</a>
										</li>
                                        <!--<li class="nav-item">
											<a href="#inbox" class="nav-link" data-toggle="tab">
												<i class="icon-envelop2"></i>
												Inbox
												<span class="badge bg-danger badge-pill ml-auto">29</span>
											</a>
										</li>
										<li class="nav-item">
											<a href="#orders" class="nav-link" data-toggle="tab">
												<i class="icon-cart2"></i>
												Orders
												<span class="badge bg-success badge-pill ml-auto">16</span>
											</a>
										</li>-->
										<li class="nav-item-divider"></li>
										<li class="nav-item">
											<a href='{{ url("/logout") }}' class="nav-link">
												<i class="icon-switch2"></i>
												Logout
											</a>
										</li>
									</ul>
								</div>
							</div>
							<!-- /navigation -->
						</div>
						<!-- /sidebar content -->
					</div>
					<!-- Right content -->
                    
                    <!-- /right content -->

				</div>
				<!-- /inner container -->

			</div>
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
							<a href="#" class="breadcrumb-item">Workshop </a>
							<span class="breadcrumb-item active"> dashboard </span>
						</div>
						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>
					<!--<div class="header-elements d-none">
						<div class="breadcrumb justify-content-center">
							<a href="#" class="breadcrumb-elements-item">
								<i class="icon-comment-discussion mr-2"></i>
								Support
							</a>

							<div class="breadcrumb-elements-item dropdown p-0">
								<a href="#" class="breadcrumb-elements-item dropdown-toggle" data-toggle="dropdown">
									<i class="icon-gear mr-2"></i>
									Settings
								</a>

								<div class="dropdown-menu dropdown-menu-right">
									<a href="#" class="dropdown-item"><i class="icon-user-lock"></i> Account security</a>
									<a href="#" class="dropdown-item"><i class="icon-statistics"></i> Analytics</a>
									<a href="#" class="dropdown-item"><i class="icon-accessibility"></i> Accessibility</a>
									<div class="dropdown-divider"></div>
									<a href="#" class="dropdown-item"><i class="icon-gear"></i> All settings</a>
								</div>
							</div>
						</div>
					</div>-->
				</div>
@stop
@push('script')
<link href="../vendor/{{ url("cdn/css/croppie.css") }}" />
@endpush

@push('custom_script')
<script src="{{ url('cdn/js/croppie.js') }}"></script>
@endpush
