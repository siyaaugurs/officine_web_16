<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from demo.interface.club/limitless/demo/bs4/Template/layout_1/LTR/default/full/error_404.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 16 Apr 2019 11:40:39 GMT -->
<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Limitless - Responsive Web Application Kit by Eugene Kopyov</title>

	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	 <link href="{{ asset('global_assets/css/icons/icomoon/styles.min.css') }}" rel="stylesheet" type="text/css">
	  <link href="{{ asset('webu/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
	 <link href="{{ asset('webu/assets/css/bootstrap_limitless.min.css') }}" rel="stylesheet" type="text/css">
	  <link href="{{ asset('webu/assets/css/layout.min.css') }}" rel="stylesheet" type="text/css">
	  <link href="{{ asset('webu/assets/css/components.min.css') }}" rel="stylesheet" type="text/css">
	  <link href="{{ asset('webu/assets/css/colors.min.css') }}" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->
	<!-- Core JS files -->
	 <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
	 <script src="{{ asset('global_assets/js/main/bootstrap.bundle.min.js') }}"></script>
	 <script src="{{ asset('global_assets/js/plugins/loaders/blockui.min.js') }}"></script>
	 <script src="{{ asset('webu/assets/js/app.js') }}"></script>
</head>
<body>
	<!-- Main navbar -->
	<div class="navbar navbar-expand-md navbar-dark">
		<div class="navbar-brand">
			<a href="#" class="d-inline-block">
				<img src="{{ asset('global_assets/images/logo_light.png') }}" alt="" />
			</a>
		</div>

		<div class="d-md-none">
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
				<i class="icon-tree5"></i>
			</button>
		</div>
	</div>
	<!-- /main navbar -->
	<!-- Page content -->
	<div class="page-content">

		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Content area -->
			<div class="content d-flex justify-content-center align-items-center">

				<!-- Container -->
				<div class="flex-fill">

					<!-- Error title -->
					<div class="text-center mb-3">
						<h1 class="error-title">404</h1>
						<h5>Oops, an error has occurred. Page not found!</h5>
					</div>
					<!-- /error title -->
					<!-- Error content -->
					<div class="row">
						<div class="col-xl-4 offset-xl-4 col-md-8 offset-md-2">
							<!--Buttons -->
							<div class="row">
								<div class="col-sm-12">
									<a href="#" onclick="goBack()" class="btn btn-primary btn-block mt-3 mt-sm-0"><i class="icon-menu7 mr-2"></i>Go Back</a>
								</div>
							</div>
							<!-- /buttons -->

						</div>
					</div>
					<!-- /error wrapper -->

				</div>
				<!-- /container -->

			</div>
			<!-- /content area -->
			<!-- Footer -->
		   @include('layouts.footer')
		</div>
	</div>
    <script>
function goBack() {
  window.history.back();
}
</script>
</body>
</html>
