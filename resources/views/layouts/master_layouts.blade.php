<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
	<meta charset="utf-8">
	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>{{ $title }}</title>
	<!-- Global stylesheets -->
       <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css"> 
      <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
      <link href="{{ asset('global_assets/css/icons/icomoon/styles.min.css') }}" rel="stylesheet" type="text/css">
	  <link href="{{ asset('webu/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
       <link href="{{ asset('webu/assets/css/glymphicons.css') }}" rel="stylesheet" type="text/css">
	  <link href="{{ asset('webu/assets/css/bootstrap_limitless.min.css') }}" rel="stylesheet" type="text/css">
	  <link href="{{ asset('webu/assets/css/layout.min.css') }}" rel="stylesheet" type="text/css">
	  <link href="{{ asset('webu/assets/css/components.min.css') }}" rel="stylesheet" type="text/css">
	  <link href="{{ asset('webu/assets/css/colors.min.css') }}" rel="stylesheet" type="text/css">
	<!-- Core JS files -->
	  <script src="{{ asset('js/jquery.min.js') }}"></script>
	  <script src="{{ asset('global_assets/js/main/bootstrap.bundle.min.js') }}"></script>
	  <script src="{{ asset('global_assets/js//plugins/extensions/jquery_ui/widgets.min.js') }}" type="text/javascript"></script>
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
	  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
	  <link href="<?php echo asset("global_assets/css/icons/fontawesome/styles.min.css"); ?>" rel="stylesheet" type="text/css">
	<script src="{{ asset('global_assets/js/plugins/loaders/blockui.min.js') }}"></script>
	<!-- /core JS files -->
	<!-- Theme JS files -->
	<script src="{{ asset('global_assets/js/plugins/internationalization/jquery-i18next.min.js') }}"></script>
	<script src="{{ asset('global_assets/js/plugins/notifications/noty.min.js') }}"></script>
	<script src="{{ asset('global_assets/js/plugins/ui/prism.min.js') }}"></script>
	<script src="{{ asset('webu/assets/js/app.js') }}"></script>
	<script src="{{ asset('global_assets/js/demo_pages/internationalization_fallback.js') }}"></script>
    <script src="{{ asset('global_assets/js/demo_pages/form_actions.js') }}"></script>
    <script src="{{ asset('global_assets/js/plugins/forms/styling/switchery.min.js') }}"></script>
       <script src="{{ url('global_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
	<script src="{{ url('global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
	<script src="{{ url('global_assets/js/demo_pages/form_actions.js') }}"></script>
	<script src="{{ url('global_assets/js/demo_pages/jqueryui_forms.js') }}"></script>
	<!-- Theme JS files -->
	<script src="{{ url('global_assets/js/plugins/uploaders/plupload/plupload.full.min.js') }}"></script>
	<script src="{{ url('global_assets/js/plugins/uploaders/plupload/plupload.queue.min.js') }}"></script>
	<link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css">
	
	<script src="{{ url('global_assets/js/demo_pages/uploader_plupload.js') }}"></script>
     @stack('scripts')
  <style>
  ul{ list-style-type:none; }
 .notice {
    padding: 10px;
    background-color: #fafafa;
    border-left: 6px solid #7f7f84;
    margin-bottom: 10px;
    -webkit-box-shadow: 0 5px 8px -6px rgba(0,0,0,.2);
       -moz-box-shadow: 0 5px 8px -6px rgba(0,0,0,.2);
            box-shadow: 0 5px 8px -6px rgba(0,0,0,.2);
}
.notice-sm {padding: 5px; font-size: 80%; }
.notice-lg {padding: 35px; font-size: large; }
.notice-success { border-color: #80D651; }
.notice-success>strong { color: #80D651; }
.notice-info {border-color: #45ABCD; }
.notice-info>strong { color: #45ABCD;}
.notice-warning { border-color: #FEAF20; }
.notice-warning>strong { color: #FEAF20;}
.notice-danger { border-color: #d73814;}
.notice-danger>strong { color: #d73814; }
 	  </style>
<style>	

.icn-sm {
    width: 30px;
    height: 30px;
    line-height: 26px;
    padding: 0;
    text-align: center;
    border-radius: 6px;
}

.icn-sm:hover {
    box-shadow: none !important;
}

.green-bdr {
    border: 2px solid #4caf50;
    background: #4caf50;
    color: #fff;
}

.red-bdr {
    border: 2px solid red;
    background: #ff4040;
    color: #fff;
}
.modal-header
{
	background: #324148;
    color: #fff;
}
.text-white
{
	color:#fff;
	opacity:1 !important;
}

.card-body .card-img {
    border-radius: .1875rem;
    object-fit: contain;
    height: 160px;
}

.m-n-20 {
    margin-bottom: 20px;
}

.icn-sm i {
    font-size: 18px;
}
.trash { color:rgb(209, 91, 71); }
.flag { color:rgb(248, 148, 6); }
.panel-body { padding:0px; }
.panel-footer .pagination { margin: 0; }
.panel .glyphicon,.list-group-item .glyphicon { margin-right:5px; }
.panel-body .radio, .checkbox { display:inline-block;margin:0px; }
.panel-body input[type=checkbox]:checked + label { text-decoration: line-through;color: rgb(128, 144, 160); }
.list-group-item:hover, a.list-group-item:focus {text-decoration: none;background-color: rgb(245, 245, 245);}
.list-group { margin-bottom:0px; }
</style>
<style>
    #preloader {
        position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(245, 175, 25, 0.7);
    opacity: .9;
    z-index: 99999;
}
#loader {
    display: block;
    position: relative;
    left: 50%;
    top: 50%;
    width: 150px;
    height: 150px;
    margin: -75px 0 0 -75px;
    border-radius: 50%;
    border: 3px solid transparent;
    border-top-color: #9370DB;
    -webkit-animation: spin 2s linear infinite;
    animation: spin 2s linear infinite;
}
#loader:before {
    content: "";
    position: absolute;
    top: 5px;
    left: 5px;
    right: 5px;
    bottom: 5px;
    border-radius: 50%;
    border: 3px solid transparent;
    border-top-color: #BA55D3;
    -webkit-animation: spin 3s linear infinite;
    animation: spin 3s linear infinite;
}
.rowPadding{ margin-top:15px;}
#loader:after {
    content: "";
    position: absolute;
    top: 15px;
    left: 15px;
    right: 15px;
    bottom: 15px;
    border-radius: 50%;
    border: 3px solid transparent;
    border-top-color: #FF00FF;
    -webkit-animation: spin 1.5s linear infinite;
    animation: spin 1.5s linear infinite;
}
@-webkit-keyframes spin {
    0%   {
        -webkit-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        transform: rotate(0deg);
    }
    100% {
        -webkit-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}
@keyframes spin {
    0%   {
        -webkit-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        transform: rotate(0deg);
    }
    100% {
        -webkit-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}
  </style>
</head>
<body>
 <div id="preloader" style="display:none;">
  <div id="loader"></div>
</div>
<script>
var base_url = "{{ url('') }}";
$.ajaxSetup({
	headers: {
	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}
  });
function get_set_language(language){
  if(language == "en"){
	  return "ENG";
	} 
  else{
	  return "ITA";
	}	
}   
</script>
	@section('main_navbar')
	<div class="navbar navbar-expand-md navbar-dark">
		<div class="navbar-brand">
			<a href="{{ url('/') }}" class="d-inline-block">
				<img src="{{ asset('global_assets/images/logo_light.png') }}" alt="Officine Top" >
			</a>
		</div>
		<div class="d-md-none">
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
				<i class="icon-tree5"></i>
			</button>
			<button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
				<i class="icon-paragraph-justify3"></i>
			</button>
		</div>
		<div class="collapse navbar-collapse" id="navbar-mobile">
			<ul class="navbar-nav">
				<li class="nav-item">
					<a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
						<i class="icon-paragraph-justify3"></i>
					</a>
				</li>
				<li class="nav-item dropdown">
					<a href="#" class="navbar-nav-link dropdown-toggle caret-0" data-toggle="dropdown">
						<span class="d-md-none ml-2">Git updates</span>
						@lang('messages.SelectLanguage') 
					</a>
               	<div class="dropdown-menu dropdown-content wmin-md-100">
						<div class="dropdown-content-body dropdown-scrollable">
							<ul class="media-list">
								<li class="media">
									<div class="media-body">
									 <a href='{{ url("language/en") }}'> @lang('messages.English') </a>
									</div>
								</li> 
								<li class="divider"></li>
								<li class="media">
									<div class="media-body">
									 <a href='{{ url("language/it") }}'> @lang('messages.Italian') </a>
									</div>
								</li>
								<!-- <li class="media">
									<div class="mr-3">
										<a href="#" class="btn bg-transparent border-warning text-warning rounded-round border-2 btn-icon"><i class="icon-git-commit"></i></a>
									</div>
									
									<div class="media-body">
										Add full font overrides for popovers and tooltips
										<div class="text-muted font-size-sm">36 minutes ago</div>
									</div>
								</li> -->

								<!-- <li class="media">
									<div class="mr-3">
										<a href="#" class="btn bg-transparent border-info text-info rounded-round border-2 btn-icon"><i class="icon-git-branch"></i></a>
									</div>
									
									<div class="media-body">
										<a href="#">Chris Arney</a> created a new <span class="font-weight-semibold">Design</span> branch
										<div class="text-muted font-size-sm">2 hours ago</div>
									</div>
								</li> -->

								<!-- <li class="media">
									<div class="mr-3">
										<a href="#" class="btn bg-transparent border-success text-success rounded-round border-2 btn-icon"><i class="icon-git-merge"></i></a>
									</div>
									
									<div class="media-body">
										<a href="#">Eugene Kopyov</a> merged <span class="font-weight-semibold">Master</span> and <span class="font-weight-semibold">Dev</span> branches
										<div class="text-muted font-size-sm">Dec 18, 18:36</div>
									</div>
								</li> -->
							</ul>
						</div>
					</div> 
				</li>
			</ul>
			<span class="badge bg-success ml-md-3 mr-md-auto">@lang('messages.Online')</span>
			<ul class="navbar-nav">
				<!--<li class="nav-item dropdown">
					<a href="#" class="navbar-nav-link dropdown-toggle caret-0" data-toggle="dropdown">
						<i class="icon-people"></i>
						<span class="d-md-none ml-2"> Users </span>
					</a>
					 <div class="dropdown-menu dropdown-menu-right dropdown-content wmin-md-300">
						<div class="dropdown-content-header">
							<span class="font-weight-semibold">Users online</span>
							<a href="#" class="text-default"><i class="icon-search4 font-size-base"></i></a>
						</div>
						<!-- <div class="dropdown-content-body dropdown-scrollable">
							<ul class="media-list">
								<li class="media">
									<div class="mr-3">
										<img src="../../../../global_assets/images/demo/users/face18.jpg" width="36" height="36" class="rounded-circle" alt="">
									</div>
									<div class="media-body">
										<a href="#" class="media-title font-weight-semibold">Jordana Ansley</a>
										<span class="d-block text-muted font-size-sm">Lead web developer</span>
									</div>
									<div class="ml-3 align-self-center"><span class="badge badge-mark border-success"></span></div>
								</li>

								<li class="media">
									<div class="mr-3">
										<img src="../../../../global_assets/images/demo/users/face24.jpg" width="36" height="36" class="rounded-circle" alt="">
									</div>
									<div class="media-body">
										<a href="#" class="media-title font-weight-semibold">Will Brason</a>
										<span class="d-block text-muted font-size-sm">Marketing manager</span>
									</div>
									<div class="ml-3 align-self-center"><span class="badge badge-mark border-danger"></span></div>
								</li>

								<li class="media">
									<div class="mr-3">
										<img src="../../../../global_assets/images/demo/users/face17.jpg" width="36" height="36" class="rounded-circle" alt="">
									</div>
									<div class="media-body">
										<a href="#" class="media-title font-weight-semibold">Hanna Walden</a>
										<span class="d-block text-muted font-size-sm">Project manager</span>
									</div>
									<div class="ml-3 align-self-center"><span class="badge badge-mark border-success"></span></div>
								</li>

								<li class="media">
									<div class="mr-3">
										<img src="../../../../global_assets/images/demo/users/face19.jpg" width="36" height="36" class="rounded-circle" alt="">
									</div>
									<div class="media-body">
										<a href="#" class="media-title font-weight-semibold">Dori Laperriere</a>
										<span class="d-block text-muted font-size-sm">Business developer</span>
									</div>
									<div class="ml-3 align-self-center"><span class="badge badge-mark border-warning-300"></span></div>
								</li>

								<li class="media">
									<div class="mr-3">
										<img src="../../../../global_assets/images/demo/users/face16.jpg" width="36" height="36" class="rounded-circle" alt="">
									</div>
									<div class="media-body">
										<a href="#" class="media-title font-weight-semibold">Vanessa Aurelius</a>
										<span class="d-block text-muted font-size-sm">UX expert</span>
									</div>
									<div class="ml-3 align-self-center"><span class="badge badge-mark border-grey-400"></span></div>
								</li>
							</ul>
						</div>

						<div class="dropdown-content-footer bg-light">
							<a href="#" class="text-grey mr-auto">All users</a>
							<a href="#" class="text-grey"><i class="icon-gear"></i></a>
						</div>
					</div>
				</li>-->

				<!--<li class="nav-item dropdown">
					<a href="#" class="navbar-nav-link dropdown-toggle caret-0" data-toggle="dropdown">
						<i class="icon-bubbles4"></i>
						<span class="d-md-none ml-2">Messages</span>
						<span class="badge badge-pill bg-warning-400 ml-auto ml-md-0">2</span>
					</a>
					
					<div class="dropdown-menu dropdown-menu-right dropdown-content wmin-md-350">
						<div class="dropdown-content-header">
							<span class="font-weight-semibold">Messages</span>
							<a href="#" class="text-default"><i class="icon-compose"></i></a>
						</div>

						<div class="dropdown-content-body dropdown-scrollable">
							<ul class="media-list">
								<li class="media">
									<div class="mr-3 position-relative">
										<img src="../../../../global_assets/images/demo/users/face10.jpg" width="36" height="36" class="rounded-circle" alt="">
									</div>

									<div class="media-body">
										<div class="media-title">
											<a href="#">
												<span class="font-weight-semibold">James Alexander</span>
												<span class="text-muted float-right font-size-sm">04:58</span>
											</a>
										</div>

										<span class="text-muted">who knows, maybe that would be the best thing for me...</span>
									</div>
								</li>

								<li class="media">
									<div class="mr-3 position-relative">
										<img src="../../../../global_assets/images/demo/users/face3.jpg" width="36" height="36" class="rounded-circle" alt="">
									</div>

									<div class="media-body">
										<div class="media-title">
											<a href="#">
												<span class="font-weight-semibold">Margo Baker</span>
												<span class="text-muted float-right font-size-sm">12:16</span>
											</a>
										</div>

										<span class="text-muted">That was something he was unable to do because...</span>
									</div>
								</li>

								<li class="media">
									<div class="mr-3">
										<img src="../../../../global_assets/images/demo/users/face24.jpg" width="36" height="36" class="rounded-circle" alt="">
									</div>
									<div class="media-body">
										<div class="media-title">
											<a href="#">
												<span class="font-weight-semibold">Jeremy Victorino</span>
												<span class="text-muted float-right font-size-sm">22:48</span>
											</a>
										</div>

										<span class="text-muted">But that would be extremely strained and suspicious...</span>
									</div>
								</li>

								<li class="media">
									<div class="mr-3">
										<img src="../../../../global_assets/images/demo/users/face4.jpg" width="36" height="36" class="rounded-circle" alt="">
									</div>
									<div class="media-body">
										<div class="media-title">
											<a href="#">
												<span class="font-weight-semibold">Beatrix Diaz</span>
												<span class="text-muted float-right font-size-sm">Tue</span>
											</a>
										</div>

										<span class="text-muted">What a strenuous career it is that I've chosen...</span>
									</div>
								</li>

								<li class="media">
									<div class="mr-3">
										<img src="../../../../global_assets/images/demo/users/face25.jpg" width="36" height="36" class="rounded-circle" alt="">
									</div>
									<div class="media-body">
										<div class="media-title">
											<a href="#">
												<span class="font-weight-semibold">Richard Vango</span>
												<span class="text-muted float-right font-size-sm">Mon</span>
											</a>
										</div>
										
										<span class="text-muted">Other travelling salesmen live a life of luxury...</span>
									</div>
								</li>
							</ul>
						</div>

						<div class="dropdown-content-footer justify-content-center p-0">
							<a href="#" class="bg-light text-grey w-100 py-2" data-popup="tooltip" title="Load more"><i class="icon-menu7 d-block top-0"></i></a>
						</div>
					</div>
				</li>-->
				<li class="nav-item dropdown dropdown-user">
					<a href="{{url('admin')}}" class="navbar-nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown">
						@if(!empty($users_profile->profile_image) && file_exists("storage/profile_image/$users_profile->profile_image"))	
                        <img src='{{ url("storage/profile_image/$users_profile->profile_image") }}' class="rounded-circle mr-2" height="34" alt="">
                       @else
                        <img src="{{ asset('storage/profile_image/default.png') }}" class="rounded-circle mr-2" height="34" alt="">  
                       @endif
						<span>{{ Auth::user()->f_name }}</span>
					</a>
					<div class="dropdown-menu dropdown-menu-right">
						<a href="{{ url('/') }}" class="dropdown-item"><i class="icon-user-plus"></i> @lang('messages.Myprofile')</a>
						   <?php
                          if($alloted_roles->count() > 0){
							  foreach($alloted_roles as $roles){
								if($roles->roll_id == 4 && Auth::user()->roll_id == 4){
									     ?>
										<a href="{{ url('change_rolls/4') }}" class="dropdown-item"><i class="icon-user"></i> Login as Admin </a>
										 <?php
									}
									if($roles->roll_id == 1){
									     ?>
										<a href="{{ url('change_rolls/1') }}" class="dropdown-item"><i class="icon-user"></i> Login as Seller </a>
										 <?php
									  }
									if($roles->roll_id == 2){
									     ?>
										 <a href="{{ url('change_rolls/2') }}" class="dropdown-item"><i class="icon-user"></i> Login as Workshop</a>
										 <?php 
									  }  
								 }
							}
						?>
                        
						
                        <!--<a href="#" class="dropdown-item"><i class="icon-coins"></i> My balance</a>
						<a href="#" class="dropdown-item"><i class="icon-comment-discussion"></i> Messages <span class="badge badge-pill bg-blue ml-auto">58</span></a>
						<div class="dropdown-divider"></div>
						<a href="#" class="dropdown-item"><i class="icon-cog5"></i> Account settings</a>
						-->
                        <a href='{{ url("logout") }}' class="dropdown-item"><i class="icon-switch2"></i> @lang('messages.Logout')</a>
					</div>
				</li>
			</ul>
		</div>
	</div>
	@show
	<!-- Page content -->
	<div class="page-content">
		@section('left_sidebar')
		<div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">
			<!-- Sidebar mobile toggler -->
			<div class="sidebar-mobile-toggler text-center">
				<a href="#" class="sidebar-mobile-main-toggle">
					<i class="icon-arrow-left8"></i>
				</a>
				Navigation
				<a href="#" class="sidebar-mobile-expand">
					<i class="icon-screen-full"></i>
					<i class="icon-screen-normal"></i>
				</a>
			</div>
			<!-- /sidebar mobile toggler -->
			<!-- Sidebar content -->
			<div class="sidebar-content">
				<!-- User menu -->
				<div class="sidebar-user">
					<div class="card-body">
						<div class="media">
							<div class="mr-3">
								<a href="{{url('admin')}}">
                                @if(!empty($users_profile->profile_image) && file_exists("storage/profile_image/$users_profile->profile_image"))	
                                  <img src='{{ url("storage/profile_image/$users_profile->profile_image") }}' width="38" height="38" class="rounded-circle" alt="">
                       @else
                        <img src="{{ asset('storage/profile_image/default.png') }}" width="38" height="38" class="rounded-circle" alt="">  
                       @endif
                                
                                </a>
							</div>
							<div class="media-body">
								<div class="media-title font-weight-semibold">{{ ucfirst(Auth::user()->f_name)." ".ucfirst(Auth::user()->l_name) }}</div>
								<!-- <div class="font-size-xs opacity-50">
									<i class="icon-user sm"></i> &nbsp;Dashboard
								</div> -->
							</div>
							<!--<div class="ml-3 align-self-center">
								<a href="#" class="text-white"><i class="icon-cog3"></i></a>
							</div>-->
						</div>
					</div>
				</div>
				<!-- /user menu -->
				<!-- Main navigation -->
				<div class="card card-sidebar-mobile">
					<ul class="nav nav-sidebar" data-nav-type="accordion">
						<!-- Main -->
						<!-- <li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">Navigation</div> <i class="icon-menu" title="Main"></i></li> -->
						@if(Auth::check())
                            @if(Auth::user()->roll_id == 4 && Session::get('users_roll_type') == 4 )
		                <!--<li class="nav-item">
							<a class="nav-link demo_pop_up_btn" >
								<i class="icon-users"></i>
								<span>demo pop up</span>
							</a>
						</li>-->
						   <li class="nav-item">
							<a href="{{ url('/admin/dashboard') }}" class="nav-link {{ Request::path() == 'admin/dashboard' ? 'active' : '' }}">
								<i class="icon-home4"></i>
								<span>
									Dashboard
								</span>
							</a>
						</li>
                           <li class="nav-item">
							<a href="{{ url('admin/users_list') }}" class="nav-link {{ Request::path() == 'admin/users_list' ? 'active' : '' }}">
								<i class="icon-users"></i>
								<span>
									Users
								</span>
							</a>
						</li>
                          <li class="nav-item">
							<a href="{{ url('admin/category_list') }}" class="nav-link {{ Request::path() == 'admin/category_list' ? 'active' : '' }}"><i class="icon-copy"></i> <span>Manage Car Wash </span></a>

							<!--<ul class="nav nav-group-sub" data-submenu-title="Layouts">
								<li class="nav-item"><a href='{{  url("admin/add_category")}} ' class="nav-link active">Add New category</a></li>
                                <li class="nav-item"><a  class="nav-link active">Categories</a></li>
							</ul>-->
						</li>
						<li class="nav-item">
							<a href="{{ url('admin/car_revision') }}" class="nav-link {{ Request::path() == 'admin/car_revision' ? 'active' : '' }}"><i class="icon-copy"></i> <span>Manage Car Revision </span></a>
						</li>
						 <li class="nav-item">
							<a href="{{ url('admin/mot_test_services') }}" class="nav-link {{ Request::path() == 'admin/mot_test_services' ? 'active' : '' }}"><i class="icon-copy"></i> <span>Manage MOT Test </span></a>

							<!--<ul class="nav nav-group-sub" data-submenu-title="Layouts">
								<li class="nav-item"><a href='{{  url("admin/add_category")}} ' class="nav-link active">Add New category</a></li>
                                <li class="nav-item"><a  class="nav-link active">Categories</a></li>
							</ul>-->
						</li>
						<li class="nav-item">
							<a href="{{ url('admin/car_maintenance') }}" class="nav-link {{ Request::path() == 'admin/car_maintenance' ? 'active' : '' }}"><i class="icon-copy"></i><span>Manage Car Maintenance </span></a>
						</li>
						<li class="nav-item">
							<a href="{{ url('admin/wrecker_services') }}" class="nav-link {{ Request::path() == 'admin/wrecker_services' ? 'active' : '' }}"><i class="icon-copy"></i> <span>Manage Wrecker Services </span></a>
						</li>
						<!--<li class="nav-item">-->
						<!--	<a href="{{ url('admin/sos') }}" class="nav-link {{ Request::path() == 'admin/sos' ? 'active' : '' }}"><i class="icon-copy"></i> <span>Manage SOS </span></a>-->
						<!--</li>-->
                        <li class="nav-item  nav-item-submenu {{ Request::path() == 'admin/spare_groups' || Request::path() == 'admin/mapping_spare_group' || Request::path() == 'admin/list_spare_items'  ? 'nav-item-open' : '' }}" >
							<a href="#" class="nav-link"><i class="fa fa-car"></i> <span>Manage Spare Groups </span></a>
  							<ul class="nav nav-group-sub" data-submenu-title="Layouts" style="display:{{ Request::path() == 'admin/spare_groups' || Request::path() == 'admin/mapping_spare_group' || Request::path() == 'admin/list_spare_items' ? 'block' : '' }}">
								<li class="nav-item"><a href="{{ url('admin/spare_groups') }}" class="nav-link {{ Request::path() == 'admin/spare_groups' ? 'active' : '' }}"><span><i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;Add Spare Groups </span></a></li>
								
                                <li class="nav-item"><a href="{{ url('admin/mapping_spare_group') }}" class="nav-link {{ Request::path() == 'admin/mapping_spare_group' ? 'active' : '' }}"><i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;<span>Mapping Spare Groups</span> </a></li>
                               <li class="nav-item"><a href="{{ url('admin/list_spare_items') }}" class="nav-link {{ Request::path() == 'admin/list_spare_items' ? 'active' : '' }}"><i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;<span>List spare item</span> </a></li>
                               <!--<li class="nav-item"><a href="{{ url('admin/list_spare_items') }}" class="nav-link"><i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;<span>List Spare Items</span> </a></li>-->
							</ul>
						</li>
                          <!--
                          <li class="nav-item">
							<a href="{{ url('admin/car_wash_categories') }}" class="nav-link"><i class="icon-copy"></i> <span>Manage Car Washing </span></a>

							<ul class="nav nav-group-sub" data-submenu-title="Layouts">
								<li class="nav-item"><a href='{{  url("admin/add_category")}} ' class="nav-link active">Add New category</a></li>
                                <li class="nav-item"><a  class="nav-link active">Categories</a></li>
							</ul>
						</li>-->
						<li class="nav-item nav-item-submenu {{ Request::path() == 'products/category_list_new' || Request::path() == 'products/products_list' ? 'nav-item-open' : '' }}">
							<a href="#" class="nav-link"><i class="fab fa-product-hunt"></i> <span>Manage spare parts </span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Layouts" style="display:{{ Request::path() == 'products/category_list_new' || Request::path() == 'products/products_list' ? 'block' : ''  }}">
								<li class="nav-item"><a href='{{  url("products/category_list_new")}} ' class="nav-link active"><i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;Manage category</a></li>
								<!--<li class="nav-item"><a href='{{ url("products/manage_n3_category") }} ' class="nav-link active"><i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;Manage N3 Category</a>
								</li>-->
								<li class="nav-item"><a href='{{  url("products/products_list")}} ' class="nav-link active"><i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;Manage Products</a></li>
							
								<!-- <li class="nav-item"><a href='{{ url("products/add_new_custom_products") }} ' class="nav-link active"><i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;Manage Custom Products</a></li> -->
								
							</ul>
						</li>
						<li class="nav-item nav-item-submenu {{ Request::path() == 'products/add_new_custom_products' ? 'nav-item-open' : '' }}">
							<a href="#" class="nav-link"><i class="fab fa-product-hunt"></i> <span>Manage Custom spare parts </span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Layouts" style="display:{{ Request::path() == 'products/add_new_custom_products' ? 'block' : ''  }}">
								<li class="nav-item"><a href='{{ url("products/add_new_custom_products") }} ' class="nav-link active"><i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;Add Custom Products</a></li>
								<li class="nav-item"><a href='{{ url("products/list_custom_products") }} ' class="nav-link active"><i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;List Products</a></li>
							</ul>
						</li>
						<li class="nav-item nav-item-submenu {{ Request::path() == 'tyre24/manage_tires' ? 'nav-item-open' : '' }}">
							<a href="#" class="nav-link"><i class="fab fa-product-hunt"></i> <span>Manage Tire System </span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Layouts" style="display:{{ Request::path() == 'tyre24/manage_tires' ? 'block' : ''  }}">
							<li class="nav-item"><a href='{{ url("tyre24/manage_tyre_mesurement") }} ' class="nav-link active"><i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;Manage Tyre Measurement</a></li>
								<li class="nav-item"><a href='{{ url("tyre24/manage_groups") }} ' class="nav-link active"><i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;Manage Groups</a></li>
									<li class="nav-item"><a href='{{ url("tyre24/manage_pfu") }} ' class="nav-link active"><i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;Manage PFU</a></li>
								<li class="nav-item"><a href='{{ url("tyre24/manage_tires") }} ' class="nav-link active"><i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;Save Tires</a></li>
							</ul>
						</li>
						<li class="nav-item nav-item-submenu {{ Request::path() == 'tyre24/list_of_custom_tires' ? 'nav-item-open' : '' }}">
							<a href="#" class="nav-link"><i class="fab fa-product-hunt"></i> <span>Manage Custom Tire System </span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Layouts" style="display:{{ Request::path() == 'tyre24/list_of_custom_tires' ? 'block' : ''  }}">
								<li class="nav-item"><a href='{{ url("tyre24/list_of_custom_tires") }} ' class="nav-link active"><i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;List of custom tires</a></li>
							</ul>
						</li>
						<li class="nav-item nav-item-submenu {{ Request::path() == 'rim/manage_rim' ? 'nav-item-open' : '' }}">
							<a href="#" class="nav-link"><i class="fab fa-product-hunt"></i> <span>Manage Rim System </span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Layouts" style="display:{{ Request::path() == 'rim/manage_rim' ? 'block' : ''  }}">
								<li class="nav-item"><a href='{{ url("rim/manage_rim") }} ' class="nav-link active"><i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;Save Rim</a></li>
							</ul>
						</li>
						
						<li class="nav-item nav-item-submenu {{ Request::path() == 'products/brand_logo' ? 'nav-item-open' : '' }}">
							<a href="#" class="nav-link"><i class="fab fa-product-hunt"></i> <span>Manage Brands </span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Layouts" style="display:{{ Request::path() == 'products/brand_logo' ? 'block' : ''  }}">
									<li class="nav-item"><a href='{{  url("products/brand_logo")}} ' class="nav-link active"><i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;Manage Brand Logo</a></li>
							</ul>
						</li>
                           <!-- <li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link"><i class="icon-copy"></i> <span>Workshop</span></a>

							<ul class="nav nav-group-sub" data-submenu-title="Layouts">
							<li class="nav-item"><a href='{{  url("add_workshop")}} ' class="nav-link active">Add New Workshop</a></li>
                                <li class="nav-item"><a href='{{  url("select_category")}} ' class="nav-link active">Add New Workshop</a></li>
                                <li class="nav-item"><a href="{{ url('admin/workshops') }}" class="nav-link active"> Workshop List</a></li>
							</ul>
						</li> 
                           <li class="nav-item">
							<a href="{{ url('products') }}" class="nav-link"><i class="icon-copy"></i> <span>Products Management </span></a>
						</li>-->
                        <li class="nav-item nav-item-submenu {{ Request::path() == 'coupons' || Request::path() == 'coupon' ? 'nav-item-open' : '' }}">
							<a href="javascript::void()" class="nav-link"><i class="icon-gift"></i> <span>Coupons</span></a>

							<ul class="nav nav-group-sub" data-submenu-title="Layouts" style="display:{{ Request::path() == 'coupons' || Request::path() == 'coupon' ? 'block' : '' }}">
								<li class="nav-item"><a href='{{  url("coupon/add_new_coupon")}} ' class="nav-link active">Add New Coupon</a></li>
                                <li class="nav-item"><a href="{{ url('coupon/coupon_list') }}" class="nav-link active"> Coupon List</a></li>
							</ul>
						</li>

						<li class="nav-item">
							<a href="{{ url('admin/order_list') }}" class="nav-link {{ Request::path() == 'admin/order_list' ? 'active' : '' }}"><i class="icon-cart"></i> <span>Order Management </span></a>
						</li>
                        <li class="nav-item">
							<a href="{{ url('service_quotes') }}" class="nav-link {{ Request::path() == 'service_quotes' ? 'active' : '' }}"><i class="icon-copy"></i> <span>Quotes for Service </span></a>
						</li> 
                        <li class="nav-item">
							<a href="{{ url('admin/feedback') }}" class="nav-link {{ Request::path() == 'admin/feedback' ? 'active' : '' }}"><i class="icon-copy"></i> <span>Feedback Management </span></a>
						</li>
						<li class="nav-item nav-item-submenu {{ Request::path() == 'user_policy/terms_condition' ? 'nav-item-open' : '' }}">
							<a href="#" class="nav-link"><i class="fab fa-product-hunt"></i> <span>User Policy</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Layouts" style="display:{{ Request::path() == 'user_policy/terms_condition' ? 'block' : ''  }}">
								<li class="nav-item"><a href='{{ url("user_policy/terms_condition") }} ' class="nav-link active"><i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;Terms and Condition  </a></li>
							</ul>
						</li>
						<li class="nav-item nav-item-submenu {{ Request::path() == 'admin/notification' ? 'nav-item-open' : '' }}">
							<a href="#" class="nav-link"><i class="fab fa-product-hunt"></i> <span>Notification </span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Layouts" style="display:{{ Request::path() == 'admin/notification' ? 'block' : ''  }}">
								<li class="nav-item"><a href='{{ url("admin/notification") }} ' class="nav-link active"><i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;Notification  </a></li>
							</ul>
						</li>
						<li class="nav-item nav-item-submenu {{ Request::path() == 'admin/manage_advertising' ? 'nav-item-open' : '' }}">
							<a href="#" class="nav-link"><i class="fab fa-product-hunt"></i> <span>Manage Advertising </span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Layouts" style="display:{{ Request::path() == 'admin/manage_advertising' ? 'block' : ''  }}">
								<li class="nav-item"><a href='{{ url("admin/manage_advertising") }} ' class="nav-link active"><i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;Manage Advertising  </a></li>
							</ul>
						</li>	
                          @endif
                       @if(Session::get('users_roll_type') == 2) 
                          <!--Workshop Dashboard Side pannel start--->
                          <li class="nav-item">
							<a href="{{ url('/vendor/dashboard') }}" class="nav-link {{ Request::path() == 'vendor/dashboard' ? 'active' : '' }}">
								<i class="icon-home4"></i>
								<span>
									Dashboard
								</span>
							</a>
						</li>
                          <li class="nav-item">
							<a href="{{ url('/vendor/select_category') }}" class="nav-link {{ Request::path() == 'vendor/select_category' ? 'active' : '' }}">
								<i class="icon-home4"></i>
								<span>
									Select Category
								</span>
							</a>
						</li>
						<!--<li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link"><i class="icon-copy"></i> <span>Workshop</span></a>

							<ul class="nav nav-group-sub" data-submenu-title="Layouts">
								<li class="nav-item"><a href='{{  url("add_workshop")}} ' class="nav-link active">Add New Workshop</a></li>
                                <li class="nav-item"><a href="{{ url('vendor/workshops') }}" class="nav-link active"> Workshop List</a></li>
							</ul>
						</li>-->
                       
                       <!--Manage Services --> 
                        @if($selected_category != FALSE)
                           @forelse($selected_category as $services)
                            @if($services->id == 1)
                            <li class="nav-item">
							<a href="<?php echo url('vendor/workshopServices') ?>" class="nav-link {{ Request::path() == 'vendor/workshopServices' ? 'active' : '' }}"><i class="icon-copy"></i> <span>Manage {{ $services->main_cat_name }} </span></a>
                            </li>
                            @endif
                            @if($services->id == 2)
                            <li class="nav-item">
							<a href="<?php echo url('vendor/workshop_revision') ?>" class="nav-link {{ Request::path() == 'vendor/workshop_revision' ? 'active' : '' }}"><i class="icon-copy"></i> <span>Manage {{ $services->main_cat_name }} </span></a>
                            </li>
                            @endif
                            @if($services->id == 12)
                            <li class="nav-item">
							<a href="<?php echo url('vendor/car_maintenance') ?>" class="nav-link {{ Request::path() == 'vendor/car_maintenance' ? 'active' : '' }}"><i class="icon-copy"></i> <span>Manage {{ $services->main_cat_name }} </span></a>
                            </li>
                            @endif
                         	@if($services->id == 13)
                            	<li class="nav-item">
									<a href="<?php echo url('vendor/wrecker_services') ?>" class="nav-link {{ Request::path() == 'vendor/wrecker_services' ? 'active' : '' }}"><i class="icon-copy"></i> <span>Manage {{ $services->main_cat_name }} </span></a>
                            	</li>
                            @endif
                            @if($services->id == 23)
                            	<li class="nav-item">
									<a href="<?php echo url('vendor/workshop_tyre24') ?>" class="nav-link {{ Request::path() == 'vendor/workshop_tyre24' ? 'active' : '' }}"><i class="icon-copy"></i> <span>Tyre PFU Management</span></a>
                            	</li>
                            @endif
                            @if($services->id == 3)
                            <li class="nav-item">
							<a href="<?php echo url('vendor/workshop_mot_services') ?>" class="nav-link {{ Request::path() == 'vendor/workshop_mot_services' ? 'active' : '' }}"><i class="icon-copy"></i> <span>Manage {{ $services->main_cat_name }} </span></a>
                            </li>
                            @endif
							@if($services->id == 25)
                            	<li class="nav-item">
									<a href="<?php echo url('vendor/request_for_quotes') ?>" class="nav-link {{ Request::path() == 'vendor/request_for_quotes' ? 'active' : '' }}"><i class="icon-copy"></i> <span>Manage {{ $services->main_cat_name }} </span></a>
                            	</li>
                            @endif
                           @empty
                           @endforelse 
                        @endif
                        <!--@if($spare_group_selected_services != FALSE)
                          <li class="nav-item">
							<a href="<?php echo url('workshop/list_spare_items') ?>" class="nav-link"><i class="icon-copy"></i> <span>Spare list group  </span></a>
                            </li>
                        @endif-->
                        
                       <!--End--> 
                       <!--<li class="nav-item nav-item-submenu">
							<a href="" class="nav-link">
								<i class="icon-home4"></i>
								<span>
								  Assemble Services Management
								</span>
							</a>
							<ul class="nav nav-group-sub" data-submenu-title="Layouts">
								<li class="nav-item"><a href="{{ url('workshop/products_asseble') }}" class="nav-link active">Products Manage</a></li>
                                <li class="nav-item"><a href="{{ url('workshop/assemble_services') }}" class="nav-link active">Assemble Services</a></li>
							</ul>
						</li>-->
						@if($spare_group_selected_services != FALSE)	
                        <li class="nav-item">
							<a href="{{ url('workshop/assemble_service_categories') }}" class="nav-link {{ Request::path() == 'workshop/assemble_service_categories' ? 'active' : '' }}">
								<i class="icon-home4"></i>
								<span>
								  Assemble Services Management
								</span>
							</a>
						</li>
                       @endif 
						
						<li class="nav-item">
							<a href="{{ url('/vendor/feedback') }}" class="nav-link {{ Request::path() == 'vendor/feedback' ? 'active' : '' }}">
								<i class="icon-copy"></i>
								<span>
									Feedback Management
								</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="{{ url('/user_policies/all_user_policy') }}" class="nav-link {{ Request::path() == 'user_policy/all_user_policy' ? 'active' : '' }}">
								<i class="icon-copy"></i>
								<span>
									User Policy
								</span>
							</a>
						</li>
                          @endif
                        @if(Session::get('users_roll_type') == 1) 
                           <li class="nav-item">
							<a href="{{ url('/seller/dashboard') }}" class="nav-link {{ Request::path() == 'seller/dashboard' ? 'active' : '' }}">
								<i class="icon-home4"></i>
								<span>
								@lang('messages.Dashboard')
								</span>
							</a>
						</li>
						<li class="nav-item nav-item-submenu {{ Request::path() == 'seller/products' || Request::path() == 'seller/product_list' ? 'nav-item-open' : '' }}">
							<a href="#" class="nav-link"><i class="icon-copy"></i> <span>Products Management </span></a>
						   <ul class="nav nav-group-sub" data-submenu-title="Layouts" style="display:{{ Request::path() == 'seller/products' || Request::path() == 'seller/product_list' ? 'block' : '' }}">
								<li class="nav-item"><a href="{{ url('/seller/products') }}" class="nav-link active">Manage Inventory</a></li>
								<li class="nav-item"><a href="{{ url('/seller/product_list') }}" class="nav-link active">Product Lists</a></li>
							</ul>
						</li>
						<li class="nav-item">
							<a href="{{ url('seller/order_list')}}" class="nav-link {{ Request::path() == 'seller/order_list' ? 'active' : '' }}">
							<i class="icon-copy"></i>
								<span>Order Management</span>
							</a>
						</li>
						<li class="nav-item nav-item-submenu {{ Request::path() == 'seller/add_pfu' || Request::path() == 'seller/manage_tyre_inventory' ? 'nav-item-open' : '' }}">
							<a href="#" class="nav-link"><i class="icon-copy"></i> <span>Tyre Management </span></a>
						   <ul class="nav nav-group-sub" data-submenu-title="Layouts" style="display:{{ Request::path() == 'seller/add_pfu' || Request::path() == 'seller/manage_tyre_inventory' ? 'block' : '' }}">
								<li class="nav-item"><a href="{{ url('/seller/add_pfu') }}" class="nav-link active">Manage PFU</a></li>
								<li class="nav-item"><a href="{{ url('/seller/manage_tyre_inventory') }}" class="nav-link active">Manage Tyre Inventory</a></li>
							</ul>
						</li>
						<li class="nav-item">
							<a href="{{ url('/seller/seller_feedback') }}" class="nav-link {{ Request::path() == 'seller/seller_feedback' ? 'active' : '' }}">
							<i class="icon-copy"></i>
								<span>Feedback Management</span>
							</a>
						</li>
						<!-- <li class="nav-item">
							<a href="{{ url('/user_policies/all_user_policy') }}" class="nav-link {{ Request::path() == 'user_policy/all_user_policy' ? 'active' : '' }}">
								<i class="icon-copy"></i>
								<span>
									User Policy
								</span>
							</a>
						</li> -->
                          @endif 
                        @endif
                     
						<!-- <li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link"><i class="icon-insert-template"></i> <span>Form layouts</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Form layouts">
								<li class="nav-item"><a href="form_layout_vertical.html" class="nav-link">Vertical form</a></li>
								<li class="nav-item"><a href="form_layout_vertical_styled.html" class="nav-link disabled">Custom styles <span class="badge bg-transparent align-self-center ml-auto">Coming soon</span></a></li>
								<li class="nav-item-divider"></li>
								<li class="nav-item"><a href="form_layout_horizontal.html" class="nav-link">Horizontal form</a></li>
								<li class="nav-item"><a href="form_layout_horizontal_styled.html" class="nav-link disabled">Custom styles <span class="badge bg-transparent align-self-center ml-auto">Coming soon</span></a></li>
							</ul>
						</li> -->
						<!-- /forms -->

						<!-- Components -->
						<!-- <li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">Components</div> <i class="icon-menu" title="Components"></i></li>
						<li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link"><i class="icon-grid"></i> <span>Basic components</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Basic components">
								<li class="nav-item"><a href="components_modals.html" class="nav-link">Modals</a></li>
								<li class="nav-item"><a href="components_dropdowns.html" class="nav-link">Dropdown menus</a></li>
								<li class="nav-item"><a href="components_tabs.html" class="nav-link">Tabs component</a></li>
								<li class="nav-item"><a href="components_pills.html" class="nav-link">Pills component</a></li>
								<li class="nav-item"><a href="components_collapsible.html" class="nav-link">Collapsible</a></li>
								<li class="nav-item"><a href="components_navs.html" class="nav-link">Navs</a></li>
								<li class="nav-item"><a href="components_buttons.html" class="nav-link">Buttons</a></li>
								<li class="nav-item"><a href="components_popups.html" class="nav-link">Tooltips and popovers</a></li>
								<li class="nav-item"><a href="components_alerts.html" class="nav-link">Alerts</a></li>
								<li class="nav-item"><a href="components_pagination.html" class="nav-link">Pagination</a></li>
								<li class="nav-item"><a href="components_badges.html" class="nav-link">Badges</a></li>
								<li class="nav-item"><a href="components_progress.html" class="nav-link">Progress</a></li>
								<li class="nav-item"><a href="components_breadcrumbs.html" class="nav-link">Breadcrumbs</a></li>
								<li class="nav-item"><a href="components_media.html" class="nav-link">Media objects</a></li>
								<li class="nav-item"><a href="components_scrollspy.html" class="nav-link">Scrollspy</a></li>
							</ul>
						</li>
						<li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link"><i class="icon-puzzle2"></i> <span>Content styling</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Content styling">
								<li class="nav-item"><a href="content_page_header.html" class="nav-link">Page header</a></li>
								<li class="nav-item"><a href="content_page_footer.html" class="nav-link disabled">Page footer <span class="badge bg-transparent align-self-center ml-auto">Coming soon</span></a></li>
								<li class="nav-item-divider"></li>
								<li class="nav-item"><a href="content_cards.html" class="nav-link">Cards</a></li>
								<li class="nav-item"><a href="content_cards_content.html" class="nav-link">Card content</a></li>
								<li class="nav-item"><a href="content_cards_layouts.html" class="nav-link">Card layouts</a></li>
								<li class="nav-item"><a href="content_cards_header.html" class="nav-link">Card header elements</a></li>
								<li class="nav-item"><a href="content_cards_footer.html" class="nav-link">Card footer elements</a></li>
								<li class="nav-item"><a href="content_cards_draggable.html" class="nav-link">Draggable cards</a></li>
								<li class="nav-item-divider"></li>
								<li class="nav-item"><a href="content_text_styling.html" class="nav-link">Text styling</a></li>
								<li class="nav-item"><a href="content_typography.html" class="nav-link">Typography</a></li>
								<li class="nav-item"><a href="content_helpers.html" class="nav-link">Helper classes</a></li>
								<li class="nav-item"><a href="content_helpers_flex.html" class="nav-link">Flex utilities</a></li>
								<li class="nav-item"><a href="content_syntax_highlighter.html" class="nav-link">Syntax highlighter</a></li>
								<li class="nav-item"><a href="content_grid.html" class="nav-link">Grid system</a></li>
							</ul>
						</li>
						<li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link"><i class="icon-gift"></i> <span>Extra components</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Extra components">
								<li class="nav-item"><a href="extra_pnotify.html" class="nav-link">PNotify notifications</a></li>
								<li class="nav-item"><a href="extra_jgrowl_noty.html" class="nav-link">jGrowl and Noty notifications</a></li>
								<li class="nav-item"><a href="extra_sweetalert.html" class="nav-link">SweetAlert notifications</a></li>
								<li class="nav-item-divider"></li>
								<li class="nav-item"><a href="extra_sliders_noui.html" class="nav-link">NoUI sliders</a></li>
								<li class="nav-item"><a href="extra_sliders_ion.html" class="nav-link">Ion range sliders</a></li>
								<li class="nav-item"><a href="extra_trees.html" class="nav-link">Dynamic tree views</a></li>
								<li class="nav-item"><a href="extra_context_menu.html" class="nav-link">Context menu</a></li>
								<li class="nav-item"><a href="extra_fab.html" class="nav-link">Floating action buttons</a></li>
								<li class="nav-item"><a href="extra_session_timeout.html" class="nav-link">Session timeout</a></li>
								<li class="nav-item"><a href="extra_idle_timeout.html" class="nav-link">Idle timeout</a></li>
							</ul>
						</li>
						<li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link"><i class="icon-droplet2"></i> <span>Color system</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Color system">
								<li class="nav-item"><a href="colors_primary.html" class="nav-link">Primary palette</a></li>
								<li class="nav-item"><a href="colors_danger.html" class="nav-link">Danger palette</a></li>
								<li class="nav-item"><a href="colors_success.html" class="nav-link">Success palette</a></li>
								<li class="nav-item"><a href="colors_warning.html" class="nav-link">Warning palette</a></li>
								<li class="nav-item"><a href="colors_info.html" class="nav-link">Info palette</a></li>
								<li class="nav-item-divider"></li>
								<li class="nav-item"><a href="colors_pink.html" class="nav-link">Pink palette</a></li>
								<li class="nav-item"><a href="colors_violet.html" class="nav-link">Violet palette</a></li>
								<li class="nav-item"><a href="colors_purple.html" class="nav-link">Purple palette</a></li>
								<li class="nav-item"><a href="colors_indigo.html" class="nav-link">Indigo palette</a></li>
								<li class="nav-item"><a href="colors_blue.html" class="nav-link">Blue palette</a></li>
								<li class="nav-item"><a href="colors_teal.html" class="nav-link">Teal palette</a></li>
								<li class="nav-item"><a href="colors_green.html" class="nav-link">Green palette</a></li>
								<li class="nav-item"><a href="colors_orange.html" class="nav-link">Orange palette</a></li>
								<li class="nav-item"><a href="colors_brown.html" class="nav-link">Brown palette</a></li>
								<li class="nav-item"><a href="colors_grey.html" class="nav-link">Grey palette</a></li>
								<li class="nav-item"><a href="colors_slate.html" class="nav-link">Slate palette</a></li>
							</ul>
						</li>
						<li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link"><i class="icon-spinner2 spinner"></i> <span>Animations</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Animations">
								<li class="nav-item"><a href="animations_css3.html" class="nav-link">CSS3 animations</a></li>
								<li class="nav-item nav-item-submenu">
									<a href="#" class="nav-link">Velocity animations</a>
									<ul class="nav nav-group-sub">
										<li class="nav-item"><a href="animations_velocity_basic.html" class="nav-link">Basic usage</a></li>
										<li class="nav-item"><a href="animations_velocity_ui.html" class="nav-link">UI pack effects</a></li>
										<li class="nav-item"><a href="animations_velocity_examples.html" class="nav-link">Advanced examples</a></li>
									</ul>
								</li>
							</ul>
						</li>
						<li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link"><i class="icon-thumbs-up2"></i> <span>Icons</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Icons">
								<li class="nav-item"><a href="icons_icomoon.html" class="nav-link">Icomoon</a></li>
								<li class="nav-item"><a href="icons_material.html" class="nav-link">Material</a></li>
								<li class="nav-item"><a href="icons_fontawesome.html" class="nav-link">Font awesome</a></li>
							</ul>
						</li> -->
						<!-- /components -->

						<!-- Layout -->
						<!-- <li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">Layout</div> <i class="icon-menu" title="Layout options"></i></li>
						<li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link"><i class="icon-stack2"></i> <span>Page layouts</span></a>

							<ul class="nav nav-group-sub" data-submenu-title="Page layouts">
								<li class="nav-item"><a href="layout_fixed_navbar.html" class="nav-link">Fixed navbar</a></li>
								<li class="nav-item"><a href="layout_fixed_sidebar_custom.html" class="nav-link">Fixed sidebar - custom scroll</a></li>
								<li class="nav-item"><a href="layout_fixed_sidebar_native.html" class="nav-link">Fixed sidebar - native scroll</a></li>
								<li class="nav-item"><a href="layout_fixed_hideable_navbar.html" class="nav-link">Hideable navbar</a></li>
								<li class="nav-item"><a href="layout_fixed_footer.html" class="nav-link">Fixed footer</a></li>
								<li class="nav-item-divider"></li>
								<li class="nav-item"><a href="layout_boxed_default.html" class="nav-link">Boxed with default sidebar</a></li>
								<li class="nav-item"><a href="layout_boxed_mini.html" class="nav-link">Boxed with mini sidebar</a></li>
								<li class="nav-item"><a href="layout_boxed_full.html" class="nav-link">Boxed full width</a></li>
								<li class="nav-item"><a href="layout_boxed_content.html" class="nav-link">Boxed content</a></li>
							</ul>
						</li>
						<li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link"><i class="icon-indent-decrease2"></i> <span>Sidebars</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Sidebars">
								<li class="nav-item nav-item-submenu">
									<a href="#" class="nav-link">Main sidebar</a>
									<ul class="nav nav-group-sub">
										<li class="nav-item"><a href="sidebar_default_collapse.html" class="nav-link">Default collapsible</a></li>
										<li class="nav-item"><a href="sidebar_default_hide.html" class="nav-link">Default hideable</a></li>
										<li class="nav-item"><a href="sidebar_default_hidden.html" class="nav-link">Default hidden</a></li>
										<li class="nav-item"><a href="sidebar_mini_collapse.html" class="nav-link">Mini collapsible</a></li>
										<li class="nav-item"><a href="sidebar_mini_hide.html" class="nav-link">Mini hideable</a></li>
										<li class="nav-item"><a href="sidebar_mini_hidden.html" class="nav-link">Mini hidden</a></li>
										<li class="nav-item-divider"></li>
										<li class="nav-item"><a href="sidebar_default_color_light.html" class="nav-link">Light color</a></li>
										<li class="nav-item"><a href="sidebar_default_color_custom.html" class="nav-link">Custom color</a></li>
									</ul>
								</li>
								<li class="nav-item nav-item-submenu">
									<a href="#" class="nav-link">Secondary sidebar</a>
									<ul class="nav nav-group-sub">
										<li class="nav-item"><a href="sidebar_secondary_after.html" class="nav-link">After default</a></li>
										<li class="nav-item"><a href="sidebar_secondary_before.html" class="nav-link">Before default</a></li>
										<li class="nav-item"><a href="sidebar_secondary_hidden.html" class="nav-link">Hidden by default</a></li>
										<li class="nav-item-divider"></li>
										<li class="nav-item"><a href="sidebar_secondary_color_dark.html" class="nav-link">Dark color</a></li>
										<li class="nav-item"><a href="sidebar_secondary_color_custom.html" class="nav-link">Custom color</a></li>
									</ul>
								</li>
								<li class="nav-item nav-item-submenu">
									<a href="#" class="nav-link">Right sidebar</a>
									<ul class="nav nav-group-sub">
										<li class="nav-item"><a href="sidebar_right_default_collapse.html" class="nav-link">Show - collapse main</a></li>
										<li class="nav-item"><a href="sidebar_right_default_hide.html" class="nav-link">Show - hide main</a></li>
										<li class="nav-item"><a href="sidebar_right_default_toggle.html" class="nav-link">Show - fix default width</a></li>
										<li class="nav-item"><a href="sidebar_right_mini_toggle.html" class="nav-link">Show - fix mini width</a></li>
										<li class="nav-item"><a href="sidebar_right_secondary_hide.html" class="nav-link">Show - hide secondary</a></li>
										<li class="nav-item"><a href="sidebar_right_visible.html" class="nav-link">Visible by default</a></li>
										<li class="nav-item-divider"></li>
										<li class="nav-item"><a href="sidebar_right_color_dark.html" class="nav-link">Dark color</a></li>
										<li class="nav-item"><a href="sidebar_right_color_custom.html" class="nav-link">Custom color</a></li>
									</ul>
								</li>
								<li class="nav-item nav-item-submenu">
									<a href="#" class="nav-link">Content sidebar</a>
									<ul class="nav nav-group-sub">
										<li class="nav-item"><a href="sidebar_content_left.html" class="nav-link">Left position</a></li>
										<li class="nav-item"><a href="sidebar_content_left_stretch.html" class="nav-link">Left stretched</a></li>
										<li class="nav-item"><a href="sidebar_content_left_hidden.html" class="nav-link">Left hidden</a></li>
										<li class="nav-item"><a href="sidebar_content_right.html" class="nav-link">Right position</a></li>
										<li class="nav-item"><a href="sidebar_content_right_stretch.html" class="nav-link">Right stretched</a></li>
										<li class="nav-item"><a href="sidebar_content_right_hidden.html" class="nav-link">Right hidden</a></li>
										<li class="nav-item"><a href="sidebar_content_sections.html" class="nav-link">Sectioned sidebar</a></li>
										<li class="nav-item-divider"></li>
										<li class="nav-item"><a href="sidebar_content_color_dark.html" class="nav-link">Dark color</a></li>
										<li class="nav-item"><a href="sidebar_content_color_custom.html" class="nav-link">Custom color</a></li>
										<li class="nav-item"><a href="sidebar_content_color_sections_custom.html" class="nav-link">Custom sections color</a></li>
									</ul>
								</li>
								<li class="nav-item-divider"></li>
								<li class="nav-item"><a href="sidebar_components.html" class="nav-link">Sidebar components</a></li>
							</ul>
						</li>
						<li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link"><i class="icon-menu3"></i> <span>Navbars</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Navbars">
								<li class="nav-item nav-item-submenu">
									<a href="#" class="nav-link">Single navbar</a>
									<ul class="nav nav-group-sub">
										<li class="nav-item"><a href="navbar_single_top_static.html" class="nav-link">Single top static</a></li>
										<li class="nav-item"><a href="navbar_single_top_fixed.html" class="nav-link">Single top fixed</a></li>
										<li class="nav-item"><a href="navbar_single_bottom_static.html" class="nav-link">Single bottom static</a></li>
										<li class="nav-item"><a href="navbar_single_bottom_fixed.html" class="nav-link">Single bottom fixed</a></li>
										<li class="nav-item"><a href="navbar_single_header_before.html" class="nav-link">Before page header</a></li>
										<li class="nav-item"><a href="navbar_single_header_after.html" class="nav-link">After page header</a></li>
										<li class="nav-item"><a href="navbar_single_content_after.html" class="nav-link">After page content</a></li>
									</ul>
								</li>
								<li class="nav-item nav-item-submenu">
									<a href="#" class="nav-link">Multiple navbars</a>
									<ul class="nav nav-group-sub">
										<li class="nav-item"><a href="navbar_multiple_top_static.html" class="nav-link">Multiple top static</a></li>
										<li class="nav-item"><a href="navbar_multiple_top_fixed.html" class="nav-link">Multiple top fixed</a></li>
										<li class="nav-item"><a href="navbar_multiple_bottom_static.html" class="nav-link">Multiple bottom static</a></li>
										<li class="nav-item"><a href="navbar_multiple_bottom_fixed.html" class="nav-link">Multiple bottom fixed</a></li>
										<li class="nav-item"><a href="navbar_multiple_top_bottom.html" class="nav-link">Multiple - top and bottom</a></li>
										<li class="nav-item"><a href="navbar_multiple_secondary_sticky.html" class="nav-link">Multiple - secondary sticky</a></li>
									</ul>
								</li>
								<li class="nav-item nav-item-submenu">
									<a href="#" class="nav-link">Content navbar</a>
									<ul class="nav nav-group-sub">
										<li class="nav-item"><a href="navbar_component_single.html" class="nav-link">Single navbar</a></li>
										<li class="nav-item"><a href="navbar_component_multiple.html" class="nav-link">Multiple navbars</a></li>
									</ul>
								</li>
								<li class="nav-item-divider"></li>
								<li class="nav-item"><a href="navbar_colors.html" class="nav-link">Color options</a></li>
								<li class="nav-item"><a href="navbar_sizes.html" class="nav-link">Sizing options</a></li>
								<li class="nav-item"><a href="navbar_hideable.html" class="nav-link">Hide on scroll</a></li>
								<li class="nav-item"><a href="navbar_components.html" class="nav-link">Navbar components</a></li>
							</ul>
						</li>
						<li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link"><i class="icon-sort"></i> <span>Vertical navigation</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Vertical navigation">
								<li class="nav-item"><a href="navigation_vertical_collapsible.html" class="nav-link">Collapsible menu</a></li>
								<li class="nav-item"><a href="navigation_vertical_accordion.html" class="nav-link">Accordion menu</a></li>
								<li class="nav-item"><a href="navigation_vertical_bordered.html" class="nav-link">Bordered navigation</a></li>
								<li class="nav-item"><a href="navigation_vertical_right_icons.html" class="nav-link">Right icons</a></li>
								<li class="nav-item"><a href="navigation_vertical_badges.html" class="nav-link">Badges</a></li>
								<li class="nav-item"><a href="navigation_vertical_disabled.html" class="nav-link">Disabled items</a></li>
							</ul>
						</li>
						<li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link"><i class="icon-transmission"></i> <span>Horizontal navigation</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Horizontal navigation">
								<li class="nav-item"><a href="navigation_horizontal_click.html" class="nav-link">Submenu on click</a></li>
								<li class="nav-item"><a href="navigation_horizontal_hover.html" class="nav-link">Submenu on hover</a></li>
								<li class="nav-item"><a href="navigation_horizontal_elements.html" class="nav-link">With custom elements</a></li>
								<li class="nav-item"><a href="navigation_horizontal_tabs.html" class="nav-link">Tabbed navigation</a></li>
								<li class="nav-item"><a href="navigation_horizontal_disabled.html" class="nav-link">Disabled navigation links</a></li>
								<li class="nav-item"><a href="navigation_horizontal_mega.html" class="nav-link">Horizontal mega menu</a></li>
							</ul>
						</li> -->
						<!-- <li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link"><i class="icon-tree5"></i> <span>Menu levels</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Menu levels">
								<li class="nav-item"><a href="#" class="nav-link"><i class="icon-IE"></i> Second level</a></li>
								<li class="nav-item nav-item-submenu">
									<a href="#" class="nav-link"><i class="icon-firefox"></i> Second level with child</a>
									<ul class="nav nav-group-sub">
										<li class="nav-item"><a href="#" class="nav-link"><i class="icon-android"></i> Third level</a></li>
										<li class="nav-item nav-item-submenu">
											<a href="#" class="nav-link"><i class="icon-apple2"></i> Third level with child</a>
											<ul class="nav nav-group-sub">
												<li class="nav-item"><a href="#" class="nav-link"><i class="icon-html5"></i> Fourth level</a></li>
												<li class="nav-item"><a href="#" class="nav-link"><i class="icon-css3"></i> Fourth level</a></li>
											</ul>
										</li>
										<li class="nav-item"><a href="#" class="nav-link"><i class="icon-windows"></i> Third level</a></li>
									</ul>
								</li>
								<li class="nav-item"><a href="#" class="nav-link"><i class="icon-chrome"></i> Second level</a></li>
							</ul>
						</li> -->
						<!-- /layout -->

						<!-- Data visualization -->
						<!-- <li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">Data visualization</div> <i class="icon-menu" title="Data visualization"></i></li>
						<li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link"><i class="icon-graph"></i> <span>Echarts library</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="ECharts library">
								<li class="nav-item"><a href="echarts_lines.html" class="nav-link">Line charts</a></li>
								<li class="nav-item"><a href="echarts_areas.html" class="nav-link">Area charts</a></li>
								<li class="nav-item"><a href="echarts_columns_waterfalls.html" class="nav-link">Columns and waterfalls</a></li>
								<li class="nav-item"><a href="echarts_bars_tornados.html" class="nav-link">Bars and tornados</a></li>
								<li class="nav-item"><a href="echarts_scatter.html" class="nav-link">Scatter charts</a></li>
								<li class="nav-item"><a href="echarts_pies_donuts.html" class="nav-link">Pies and donuts</a></li>
								<li class="nav-item"><a href="echarts_funnels_calendars.html" class="nav-link">Funnels and calendars</a></li>
								<li class="nav-item"><a href="echarts_candlesticks_others.html" class="nav-link">Candlesticks and others</a></li>
							</ul>
						</li>
						<li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link"><i class="icon-statistics"></i> <span>D3 library</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="D3 library">
								<li class="nav-item"><a href="d3_lines_basic.html" class="nav-link">Simple lines</a></li>
								<li class="nav-item"><a href="d3_lines_advanced.html" class="nav-link">Advanced lines</a></li>
								<li class="nav-item"><a href="d3_bars_basic.html" class="nav-link">Simple bars</a></li>
								<li class="nav-item"><a href="d3_bars_advanced.html" class="nav-link">Advanced bars</a></li>
								<li class="nav-item"><a href="d3_pies.html" class="nav-link">Pie charts</a></li>
								<li class="nav-item"><a href="d3_circle_diagrams.html" class="nav-link">Circle diagrams</a></li>
								<li class="nav-item"><a href="d3_tree.html" class="nav-link">Tree layout</a></li>
								<li class="nav-item"><a href="d3_other.html" class="nav-link">Other charts</a></li>
							</ul>
						</li>
						<li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link"><i class="icon-stats-dots"></i> <span>Dimple library</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Dimple library">
								<li class="nav-item nav-item-submenu">
									<a href="#" class="nav-link">Line charts</a>
									<ul class="nav nav-group-sub">
										<li class="nav-item"><a href="dimple_lines_horizontal.html" class="nav-link">Horizontal orientation</a></li>
										<li class="nav-item"><a href="dimple_lines_vertical.html" class="nav-link">Vertical orientation</a></li>
									</ul>
								</li>
								<li class="nav-item nav-item-submenu">
									<a href="#" class="nav-link">Bar charts</a>
									<ul class="nav nav-group-sub">
										<li class="nav-item"><a href="dimple_bars_horizontal.html" class="nav-link">Horizontal orientation</a></li>
										<li class="nav-item"><a href="dimple_bars_vertical.html" class="nav-link">Vertical orientation</a></li>
									</ul>
								</li>
								<li class="nav-item nav-item-submenu">
									<a href="#" class="nav-link">Area charts</a>
									<ul class="nav nav-group-sub">
										<li class="nav-item"><a href="dimple_area_horizontal.html" class="nav-link">Horizontal orientation</a></li>
										<li class="nav-item"><a href="dimple_area_vertical.html" class="nav-link">Vertical orientation</a></li>
									</ul>
								</li>
								<li class="nav-item nav-item-submenu">
									<a href="#" class="nav-link">Step charts</a>
									<ul class="nav nav-group-sub">
										<li class="nav-item"><a href="dimple_step_horizontal.html" class="nav-link">Horizontal orientation</a></li>
										<li class="nav-item"><a href="dimple_step_vertical.html" class="nav-link">Vertical orientation</a></li>
									</ul>
								</li>
								<li class="nav-item"><a href="dimple_pies.html" class="nav-link">Pie charts</a></li>
								<li class="nav-item"><a href="dimple_rings.html" class="nav-link">Ring charts</a></li>
								<li class="nav-item"><a href="dimple_scatter.html" class="nav-link">Scatter charts</a></li>
								<li class="nav-item"><a href="dimple_bubble.html" class="nav-link">Bubble charts</a></li>
							</ul>
						</li>
						<li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link"><i class="icon-stats-bars"></i> <span>C3 library</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="C3 library">
								<li class="nav-item"><a href="c3_lines_areas.html" class="nav-link">Lines and areas</a></li>
								<li class="nav-item"><a href="c3_bars_pies.html" class="nav-link">Bars and pies</a></li>
								<li class="nav-item"><a href="c3_advanced.html" class="nav-link">Advanced examples</a></li>
								<li class="nav-item"><a href="c3_axis.html" class="nav-link">Chart axis</a></li>
								<li class="nav-item"><a href="c3_grid.html" class="nav-link">Grid options</a></li>
							</ul>
						</li>
						<li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link"><i class="icon-google"></i> <span>Google charts</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Google charts">
								<li class="nav-item"><a href="google_lines.html" class="nav-link">Line charts</a></li>
								<li class="nav-item"><a href="google_bars.html" class="nav-link">Bar charts</a></li>
								<li class="nav-item"><a href="google_pies.html" class="nav-link">Pie charts</a></li>
								<li class="nav-item"><a href="google_scatter_bubble.html" class="nav-link">Bubble &amp; scatter charts</a></li>
								<li class="nav-item"><a href="google_other.html" class="nav-link">Other charts</a></li>
							</ul>
						</li>
						<li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link"><i class="icon-map5"></i> <span>Maps integration</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Maps integration">
								<li class="nav-item nav-item-submenu">
									<a href="#" class="nav-link">Google maps</a>
									<ul class="nav nav-group-sub">
										<li class="nav-item"><a href="maps_google_basic.html" class="nav-link">Basics</a></li>
										<li class="nav-item"><a href="maps_google_controls.html" class="nav-link">Controls</a></li>
										<li class="nav-item"><a href="maps_google_markers.html" class="nav-link">Markers</a></li>
										<li class="nav-item"><a href="maps_google_drawings.html" class="nav-link">Map drawings</a></li>
										<li class="nav-item"><a href="maps_google_layers.html" class="nav-link ">Layers</a></li>
									</ul>
								</li>
								<li class="nav-item"><a href="maps_vector.html" class="nav-link">Vector maps</a></li>
								<li class="nav-item"><a href="maps_echarts.html" class="nav-link disabled">ECharts maps <span class="badge bg-transparent align-self-center ml-auto">Coming soon</span></a></li>
							</ul>
						</li> -->
						<!-- /data visualization -->

						<!-- Extensions -->
						<!-- <li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">Extensions</div> <i class="icon-menu" title="Extensions"></i></li>
						<li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link"><i class="icon-puzzle4"></i> <span>Extensions</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Extensions">
								<li class="nav-item"><a href="extension_image_cropper.html" class="nav-link">Image cropper</a></li>
								<li class="nav-item"><a href="extension_blockui.html" class="nav-link">Block UI</a></li>
								<li class="nav-item"><a href="extension_dnd.html" class="nav-link">Drag and drop</a></li>
							</ul>
						</li>
						<li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link"><i class="icon-popout"></i> <span>JQuery UI</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="jQuery UI">
								<li class="nav-item"><a href="jqueryui_interactions.html" class="nav-link">Interactions</a></li>
								<li class="nav-item"><a href="jqueryui_forms.html" class="nav-link">Forms</a></li>
								<li class="nav-item"><a href="jqueryui_components.html" class="nav-link">Components</a></li>
								<li class="nav-item"><a href="jqueryui_sliders.html" class="nav-link">Sliders</a></li>
								<li class="nav-item"><a href="jqueryui_navigation.html" class="nav-link">Navigation</a></li>
							</ul>
						</li>
						<li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link"><i class="icon-upload"></i> <span>File uploaders</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="File uploaders">
								<li class="nav-item"><a href="uploader_plupload.html" class="nav-link">Plupload</a></li>
								<li class="nav-item"><a href="uploader_bootstrap.html" class="nav-link">Bootstrap file uploader</a></li>
								<li class="nav-item"><a href="uploader_dropzone.html" class="nav-link">Dropzone</a></li>
							</ul>
						</li>
						<li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link"><i class="icon-calendar3"></i> <span>Event calendars</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Event calendars">
								<li class="nav-item"><a href="fullcalendar_views.html" class="nav-link">Basic views</a></li>
								<li class="nav-item"><a href="fullcalendar_styling.html" class="nav-link">Event styling</a></li>
								<li class="nav-item"><a href="fullcalendar_formats.html" class="nav-link">Language and time</a></li>
								<li class="nav-item"><a href="fullcalendar_advanced.html" class="nav-link">Advanced usage</a></li>
							</ul>
						</li>
						<li class="nav-item nav-item-submenu nav-item-expanded nav-item-open">
							<a href="#" class="nav-link"><i class="icon-sphere"></i> <span>Internationalization</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Internationalization">
								<li class="nav-item"><a href="internationalization_switch_direct.html" class="nav-link">Direct translation</a></li>
								<li class="nav-item"><a href="internationalization_switch_query.html" class="nav-link">Querystring parameter</a></li>
								<li class="nav-item"><a href="internationalization_fallback.html" class="nav-link active">Language fallback</a></li>
								<li class="nav-item"><a href="internationalization_callbacks.html" class="nav-link">Callbacks</a></li>
							</ul>
						</li> -->
						<!-- /extensions -->

						<!-- Tables -->
						<!-- <li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">Tables</div> <i class="icon-menu" title="Tables"></i></li>
						<li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link"><i class="icon-table2"></i> <span>Basic tables</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Basic tables">
								<li class="nav-item"><a href="table_basic.html" class="nav-link">Basic examples</a></li>
								<li class="nav-item"><a href="table_sizing.html" class="nav-link">Table sizing</a></li>
								<li class="nav-item"><a href="table_borders.html" class="nav-link">Table borders</a></li>
								<li class="nav-item"><a href="table_styling.html" class="nav-link">Table styling</a></li>
								<li class="nav-item"><a href="table_elements.html" class="nav-link">Table elements</a></li>
							</ul>
						</li>
						<li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link"><i class="icon-grid7"></i> <span>Data tables</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Data tables">
								<li class="nav-item"><a href="datatable_basic.html" class="nav-link">Basic initialization</a></li>
								<li class="nav-item"><a href="datatable_styling.html" class="nav-link">Basic styling</a></li>
								<li class="nav-item"><a href="datatable_advanced.html" class="nav-link">Advanced examples</a></li>
								<li class="nav-item"><a href="datatable_sorting.html" class="nav-link">Sorting options</a></li>
								<li class="nav-item"><a href="datatable_api.html" class="nav-link">Using API</a></li>
								<li class="nav-item"><a href="datatable_data_sources.html" class="nav-link">Data sources</a></li>
							</ul>
						</li>
						<li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link"><i class="icon-alignment-unalign"></i> <span>Data tables extensions</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Data tables extensions">
								<li class="nav-item"><a href="datatable_extension_reorder.html" class="nav-link">Columns reorder</a></li>
								<li class="nav-item"><a href="datatable_extension_row_reorder.html" class="nav-link">Row reorder</a></li>
								<li class="nav-item"><a href="datatable_extension_fixed_columns.html" class="nav-link">Fixed columns</a></li>
								<li class="nav-item"><a href="datatable_extension_fixed_header.html" class="nav-link">Fixed header</a></li>
								<li class="nav-item"><a href="datatable_extension_autofill.html" class="nav-link">Auto fill</a></li>
								<li class="nav-item"><a href="datatable_extension_key_table.html" class="nav-link">Key table</a></li>
								<li class="nav-item"><a href="datatable_extension_scroller.html" class="nav-link">Scroller</a></li>
								<li class="nav-item"><a href="datatable_extension_select.html" class="nav-link">Select</a></li>
								<li class="nav-item nav-item-submenu">
									<a href="#" class="nav-link">Buttons</a>
									<ul class="nav nav-group-sub">
										<li class="nav-item"><a href="datatable_extension_buttons_init.html" class="nav-link">Initialization</a></li>
										<li class="nav-item"><a href="datatable_extension_buttons_flash.html" class="nav-link">Flash buttons</a></li>
										<li class="nav-item"><a href="datatable_extension_buttons_print.html" class="nav-link">Print buttons</a></li>
										<li class="nav-item"><a href="datatable_extension_buttons_html5.html" class="nav-link">HTML5 buttons</a></li>
									</ul>
								</li>
								<li class="nav-item"><a href="datatable_extension_colvis.html" class="nav-link">Columns visibility</a></li>
							</ul>
						</li>
						<li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link"><i class="icon-file-spreadsheet"></i> <span>Handsontable</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Handsontable">
								<li class="nav-item"><a href="handsontable_basic.html" class="nav-link">Basic configuration</a></li>
								<li class="nav-item"><a href="handsontable_advanced.html" class="nav-link">Advanced setup</a></li>
								<li class="nav-item"><a href="handsontable_cols.html" class="nav-link">Column features</a></li>
								<li class="nav-item"><a href="handsontable_cells.html" class="nav-link">Cell features</a></li>
								<li class="nav-item"><a href="handsontable_types.html" class="nav-link">Basic cell types</a></li>
								<li class="nav-item"><a href="handsontable_custom_checks.html" class="nav-link">Custom &amp; checkboxes</a></li>
								<li class="nav-item"><a href="handsontable_ac_password.html" class="nav-link">Autocomplete &amp; password</a></li>
								<li class="nav-item"><a href="handsontable_search.html" class="nav-link">Search</a></li>
								<li class="nav-item"><a href="handsontable_context.html" class="nav-link">Context menu</a></li>
							</ul>
						</li>
						<li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link"><i class="icon-versions"></i> <span>Responsive tables</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Responsive tables">
								<li class="nav-item"><a href="table_responsive.html" class="nav-link">Responsive basic tables</a></li>
								<li class="nav-item"><a href="datatable_responsive.html" class="nav-link">Responsive data tables</a></li>
							</ul>
						</li> -->
						<!-- /tables -->
		
					</ul>
				</div>
				<!-- /main navigation -->

			</div>
			<!-- /sidebar content -->
		</div>
		@show
		<!-- Main content -->
		<div class="content-wrapper">
			@section('page_header')
			<div class="page-header page-header-light">
				<div class="page-header-content header-elements-md-inline">
					<div class="page-title d-flex">
                              @if(Session::has('login_success'))
                                {!!  session::get('login_success') !!}
                              @endif</h4>
						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>
				</div>
                {{-- breadcrum app script start --}}
				   @yield('breadcrum')
                {{-- breadcrum app script End --}}    
			</div>
			@show
			<!-- Content area -->
			<div class="content">
				<!-- Fallback language -->
                {{-- card body script start--}}
                      @yield('content')
				{{-- card body script End--}}
				<!-- /fallback language -->				
			</div>
            @stack('custom_script')
			@include('layouts.footer')
            @section('footer_bottom')
              
            <div class="modal" id="profile_pic_modal">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                    <h4 class="modal-title" id="myModalLabel"><i class="text-muted icon-profile mr-3 icon-1x"></i>@lang('messages.ChangeProfilePhoto')</h4>
                    <hr />
                  </div>
                  <!-- Modal body -->
										<div class="modal-body">
										<div class="row ">
											<div class="col-sm-12 text-center">
											<div id="upload-demo" style="width:350px">
												<span class="clear-uploaded-image">
													<span class="fa fa-close" ></span>
												</span>
											</div>
											</div>
                                            <span id="response_result"></span>
											<div class="col-sm-12" style="padding-top:30px;">
                                            <img id="profileimg"  src="" class="img img-responsive" style=" width:300px;margin-top: 20px;border: 1px #7f7f7f9c solid;" id="crop_image" />
											<strong>@lang('messages.SelectImage')</strong>
											<br/>
											<input type="file" id="upload">
											<br/>
											<button class="btn btn-success upload-result">@lang('messages.UploadImage')</button>
											</div>
										</div>
										</div>
                  </div>
                  <div class="modal-footer">       
                </div>
              </div>
            </div>
             <div class="modal" id="demo_pop_up">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                  	Car park
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                  </div>
                  <!-- Modal body -->
					<div class="modal-body">
					<div class="page-content">
						<div class="sidebar-left">
							<div class="side-header"><h6>List heading</h6></div>
							<ul>
								<li><a><img src="{{ asset('global_assets/images/logobmw.png') }}" alt="Officine Top" > <span>Audi</span></a></li>
								<li><a><img src="{{ asset('global_assets/images/logobmw.png') }}" alt="Officine Top" > <span>Audi</span></a></li>
								<li><a><img src="{{ asset('global_assets/images/logobmw.png') }}" alt="Officine Top" > <span>Audi</span></a></li>
								<li><a><img src="{{ asset('global_assets/images/logobmw.png') }}" alt="Officine Top" > <span>Audi</span></a></li>
								<li><a><img src="{{ asset('global_assets/images/logobmw.png') }}" alt="Officine Top" > <span>Audi</span></a></li>
								<li><a><img src="{{ asset('global_assets/images/logobmw.png') }}" alt="Officine Top" > <span>Audi</span></a></li><li><a><img src="{{ asset('global_assets/images/logobmw.png') }}" alt="Officine Top" > <span>Audi</span></a></li>	
								<li><a><img src="{{ asset('global_assets/images/logobmw.png') }}" alt="Officine Top" > <span>Audi</span></a></li>
								<li><a><img src="{{ asset('global_assets/images/logobmw.png') }}" alt="Officine Top" > <span>Audi</span></a></li>
								<li><a><img src="{{ asset('global_assets/images/logobmw.png') }}" alt="Officine Top" > <span>Audi</span></a></li>
								<li><a><img src="{{ asset('global_assets/images/logobmw.png') }}" alt="Officine Top" > <span>Audi</span></a></li>
								<li><a><img src="{{ asset('global_assets/images/logobmw.png') }}" alt="Officine Top" > <span>Audi</span></a></li>
								<li><a><img src="{{ asset('global_assets/images/logobmw.png') }}" alt="Officine Top" > <span>Audi</span></a></li><li><a><img src="{{ asset('global_assets/images/logobmw.png') }}" alt="Officine Top" > <span>Audi</span></a></li>								
								<li><a><img src="{{ asset('global_assets/images/logobmw.png') }}" alt="Officine Top" > <span>Audi</span></a></li>
								<li><a><img src="{{ asset('global_assets/images/logobmw.png') }}" alt="Officine Top" > <span>Audi</span></a></li>
								<li><a><img src="{{ asset('global_assets/images/logobmw.png') }}" alt="Officine Top" > <span>Audi</span></a></li>
								<li><a><img src="{{ asset('global_assets/images/logobmw.png') }}" alt="Officine Top" > <span>Audi</span></a></li>
								<li><a><img src="{{ asset('global_assets/images/logobmw.png') }}" alt="Officine Top" > <span>Audi</span></a></li>
								<li><a><img src="{{ asset('global_assets/images/logobmw.png') }}" alt="Officine Top" > <span>Audi</span></a></li><li><a><img src="{{ asset('global_assets/images/logobmw.png') }}" alt="Officine Top" > <span>Audi</span></a></li>	
							</ul>
						</div>
						<div class="content-right">
							<div class="tableFixHead">
								<table class="table table-bordered table-striped">
									<thead>
										<tr>
											<th>Model</th>
											<th>Year</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>RS6 << 4F5 >> Avant</td>
											<td>2005</td>
										</tr>
										<tr>
											<td >RS6 << 4G5 >> </td>
											<td >2011</td>
										</tr>
										<tr>
											<td>RS6 << 4F5 >> Avant</td>
											<td>2005</td>
										</tr>
										<tr>
											<td>RS6 << 4G5 >> </td>
											<td>2011</td>
										</tr>
										<tr>
											<td>RS6 << 4F5 >> Avant</td>
											<td>2005</td>
										</tr>
										<tr>
											<td>RS6 << 4F5 >> Avant</td>
											<td>2005</td>
										</tr>
										<tr>
											<td>RS6 << 4F5 >> Avant</td>
											<td>2005</td>
										</tr>
										<tr>
											<td>RS6 << 4F5 >> Avant</td>
											<td>2005</td>
										</tr>
										<tr>
											<td>RS6 << 4F5 >> Avant</td>
											<td>2005</td>
										</tr>
										</tbody>
								</table>
							</div>
							<div class="tableFixHead">
								<table class="table table-bordered table-striped">
									<thead>
										<tr>
										<th>Type</th>
										<th>Version Code</th>
										<th>Engine</th>
										<th>From</th>
										<th>To</th>
										<th>cm3</th>
										<th>kw</th>
										<th>cv</th>
										<th>Body</th>
										<th>Doors</th>
										<th>Fuels</th>
									</tr>
									</thead>
									<tbody>
									<tr>
										<td>2.0 E</td>
										<td>4A2</td>
										<td>AAD</td>
										<td>1992-08</td>
										<td>1993-07</td>
										<td>1984</td>
										<td>85</td>
										<td>116</td>
										<td>Saloon</td>
										<td>4</td>
										<td>B</td>
									</tr>
									<tr>
										<td>2.0 E</td>
										<td>4A2</td>
										<td>AAD</td>
										<td>1992-08</td>
										<td>1993-07</td>
										<td>1984</td>
										<td>85</td>
										<td>116</td>
										<td>Saloon</td>
										<td>4</td>
										<td>B</td>
									</tr>
									<tr>
										<td>2.0 E</td>
										<td>4A2</td>
										<td>AAD</td>
										<td>1992-08</td>
										<td>1993-07</td>
										<td>1984</td>
										<td>85</td>
										<td>116</td>
										<td>Saloon</td>
										<td>4</td>
										<td>B</td>
									</tr>
									<tr>
										<td>2.0 E</td>
										<td>4A2</td>
										<td>AAD</td>
										<td>1992-08</td>
										<td>1993-07</td>
										<td>1984</td>
										<td>85</td>
										<td>116</td>
										<td>Saloon</td>
										<td>4</td>
										<td>B</td>
									</tr>
									<tr>
										<td>2.0 E</td>
										<td>4A2</td>
										<td>AAD</td>
										<td>1992-08</td>
										<td>1993-07</td>
										<td>1984</td>
										<td>85</td>
										<td>116</td>
										<td>Saloon</td>
										<td>4</td>
										<td>B</td>
									</tr>
									<tr>
										<td>2.0 E</td>
										<td>4A2</td>
										<td>AAD</td>
										<td>1992-08</td>
										<td>1993-07</td>
										<td>1984</td>
										<td>85</td>
										<td>116</td>
										<td>Saloon</td>
										<td>4</td>
										<td>B</td>
									</tr>
									<tr>
										<td>2.0 E</td>
										<td>4A2</td>
										<td>AAD</td>
										<td>1992-08</td>
										<td>1993-07</td>
										<td>1984</td>
										<td>85</td>
										<td>116</td>
										<td>Saloon</td>
										<td>4</td>
										<td>B</td>
									</tr>

									<tr>
										<td>2.0 E</td>
										<td>4A2</td>
										<td>AAD</td>
										<td>1992-08</td>
										<td>1993-07</td>
										<td>1984</td>
										<td>85</td>
										<td>116</td>
										<td>Saloon</td>
										<td>4</td>
										<td>B</td>
									</tr>
									<tr>
										<td>2.0 E</td>
										<td>4A2</td>
										<td>AAD</td>
										<td>1992-08</td>
										<td>1993-07</td>
										<td>1984</td>
										<td>85</td>
										<td>116</td>
										<td>Saloon</td>
										<td>4</td>
										<td>B</td>
									</tr>
									<tr>
										<td>2.0 E</td>
										<td>4A2</td>
										<td>AAD</td>
										<td>1992-08</td>
										<td>1993-07</td>
										<td>1984</td>
										<td>85</td>
										<td>116</td>
										<td>Saloon</td>
										<td>4</td>
										<td>B</td>
									</tr>
								</tbody>
								</table>
							</div>
						</div>
					</div>
					</div>
                  </div>
                  <div class="modal-footer">       
                </div>
              </div>
            </div>
            @show
		</div>
	</div>
	<!-- /page content -->
	<style>
	span.clear-uploaded-image {
    display: inline-block;
    vertical-align: top;
    width: 21px;
    height: 21px;
    background-color: #ff4d4d;
    color: #fff;
    border-radius: 50%;
    line-height: 21px;
    float: right;
    text-align: center;
    position: absolute;
    top: 35px;
    z-index: 11;
    right: 25px;
    cursor: pointer;
}
#upload-demo{
	position: relative;
}
	</style>
<script>
$(document).on('click','.demo_pop_up_btn',function(){
   $("#demo_pop_up").modal('show'); 
});
</script>

<script>
$(function(){
	$(document).on('click','span.clear-uploaded-image',function(){
		$(this).closest('#upload-demo').find('img.cr-image').attr('src','');
		$(this).closest('.modal-body').find('#upload').val('');
	})
})

</script>

</body>
</html>
