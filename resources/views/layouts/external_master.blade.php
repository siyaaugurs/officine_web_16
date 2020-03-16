<!DOCTYPE html>
<html lang="{{ Session::get('locale') }}">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>{{ $title }}</title>
	 <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
	  <link href="{{ asset('global_assets/css/icons/icomoon/styles.min.css') }}" rel="stylesheet" type="text/css">
	  <link href="{{ asset('webu/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
	  <link href="{{ asset('webu/assets/css/bootstrap_limitless.min.css') }}" rel="stylesheet" type="text/css">
	  <link href="{{ asset('webu/assets/css/layout.min.css') }}" rel="stylesheet" type="text/css">
	  <link href="{{ asset('webu/assets/css/components.min.css') }}" rel="stylesheet" type="text/css">
	  <link href="{{ asset('webu/assets/css/colors.min.css') }}" rel="stylesheet" type="text/css">
	<!-- Core JS files -->
	  <script src="{{ asset('js/jquery.min.js') }}"></script>
	  <script src="{{ asset('global_assets/js/main/bootstrap.bundle.min.js') }}"></script>
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
	  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
    <!--
	<script src="{{ asset('global_assets/js/main/jquery.min.js') }}"></script>
	-->
    <script src="{{ asset('global_assets/js/main/bootstrap.bundle.min.js') }}"></script>
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
	<!-- /theme JS files -->
    <style>
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
</head>
<body>
<script>
var base_url = "{{ url('') }}";
$.ajaxSetup({
	headers: {
	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}
  });
</script>
	@section('main_nav_bar')
	<div class="navbar navbar-expand-md navbar-dark">
		<div class="navbar-brand">
			<a href="{{ url('/') }}" class="d-inline-block">
				<img src="{{ url('global_assets/images/logo_light.png')}}" alt="">
			</a>
		</div>
        <ul class="navbar-nav">
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
							</ul>
						</div>
					</div> 
				</li>
		</ul>
		<div class="d-md-none">
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
				<i class="icon-tree5"></i>
			</button>
    </div>	
	</div>
    @show
    @yield('page_conotent')
</body>
</html>
