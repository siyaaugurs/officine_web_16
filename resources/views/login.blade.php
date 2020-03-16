@extends('layouts.external_master')
  @section('page_conotent')
 {{-- Page content --}}
	<div class="page-content">
		{{-- Main content --}}
		<div class="content-wrapper">
			{{-- Content area --}}
			<div class="content d-flex justify-content-center align-items-center">
				{{-- Login form --}}
				<form class="login-form" action='{{ url("sign_in") }}' method="POST">
                  @csrf
					<div class="card mb-0">
						<div class="card-body">
							<div class="text-center mb-3">
								<i class="icon-reading icon-2x text-slate-300 border-slate-300 border-3 rounded-round p-3 mb-3 mt-1"></i>
								<h5 class="mb-0">
                                @lang('messages.Login to your account')
                                </h5>
								<span class="d-block text-muted">Enter your credentials below</span>
                                @if(Session::has('msg'))
                                 {!!  Session::get("msg") !!}
                                @endif
                                @if ($errors->any())
                                 <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
                                @endif
							</div>
							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="text" class="form-control" placeholder="Login with your registered email ." name="email" id="email"  value='{{ old("email") }}' onblur="emailValidate(this.value , 'Email' , 'name_err_msg' ,'login_submit')" />
								<div class="form-control-feedback">
									<i class="icon-user text-muted"></i>
								</div>
                                <span id="name_err_msg"></span>
							</div>
							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="password" class="form-control" placeholder="Password" name="password" id="password" />
								<div class="form-control-feedback">
									<i class="icon-lock2 text-muted"></i>
								</div>
							</div>

							<div class="form-group">
								<button id="login_submit" type="submit"  class="btn btn-primary btn-block">Sign in <i class="icon-circle-right2 ml-2"></i></button>
							</div>

							<div class="text-center">
								<a href='{{ url("password/reset_password") }}'>Reset password&nbsp;? </a>
                                 <strong> | </strong>
                                <a href="{{ url('registration') }}">Register now  </a>
							</div>
						</div>
					</div>
				</form>
				<!-- /login form -->

			</div>
			<!-- /content area -->


			@include('layouts.footer')

		</div>
		<!-- /main content -->

	</div>
    <!-- /page content -->
    @stop