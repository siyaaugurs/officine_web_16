@extends('layouts.external_master')
  @section('page_conotent')
 {{-- Page content --}}
	<div class="page-content">
		{{-- Main content --}}
		<div class="content-wrapper">
			{{-- Content area --}}
			<div class="content d-flex justify-content-center align-items-center">
				{{-- Reset Form form --}}
				<form class="login-form"  id="password_reset" >
                  @csrf
					<div class="card mb-0">
						<div class="card-body">
							<div class="text-center mb-3">
								<i class="icon-reading icon-2x text-slate-300 border-slate-300 border-3 rounded-round p-3 mb-3 mt-1"></i>
								<h5 class="mb-0">
                                @lang('messages.Reset Password')
                                </h5>
								<span class="d-block text-muted">Enter your credentials below</span>
                                @if(Session::has('msg'))
                                  {!!  Session::get("msg") !!}
                                @endif
                            </div>
							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="text" class="form-control" placeholder= "@lang('messages.Enter your registered email')" name="email" id="email"  value='{{ old("email") }}'  />
								<div class="form-control-feedback">
									<i class="icon-user text-muted"></i>
								</div>
                                <span id="name_err_msg"></span>
							</div>
							<span id="response"></span>
                            <div class="form-group">
								<button id="resetPwdbtn" type="submit"  class="btn btn-primary btn-block">Reset <i class="icon-circle-right2 ml-2"></i></button>
							</div>
							<div class="text-center">
								<a href='{{ url("login") }}'>Login  </a>
                                 <strong>|</strong>
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