@extends('layouts.external_master')
  @section('page_conotent')
 {{-- Page content --}}
 <div class="page-content">

{{-- Main content --}}
<div class="content-wrapper">

	{{-- Content area --}}
	<div class="content d-flex justify-content-center align-items-center">
		{{-- Registration form --}}
		<form action="{{ route('register') }}" class="flex-fill" method="POST">
			@csrf
			<div class="row">
				<div class="col-lg-6 offset-lg-3">
					<div class="card mb-0">
						<div class="card-body">
							<div class="text-center mb-3">
								<img src="{{ url('global_assets/images/logo_light.png') }}"  style="height:50px;"/>
								<h5 class="mb-0" style="font-size:14px; margin-top:10px;">
                                 @lang('messages.welcomeMsg')
                                 </h5>
                                <h5 class="mb-0" style="font-size:14px; margin-top:10px;">
                                 @lang('messages.ragulation')
                                </h5>
                                <h5 class="mb-0" style="font-size:14px; margin-top:10px;">
                                 @lang('messages.thanksMsg')
                                </h5>
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
							<div class="row">
						    	<div class="col-md-6">
									<div class="form-group form-group-feedback form-group-feedback-right">
										<input type="text" class="form-control" placeholder="Name" name="name" value="{{ old('name') }}" required="required" onkeyup="checkCharacter(this.value , 'Name' , 'name_err_msg' ,'registration_submit')"/>
										<div class="form-control-feedback">
											<i class="icon-user-check text-muted"></i>
										</div>
                                         <span id="name_err_msg"></span>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group form-group-feedback form-group-feedback-right">
										<input type="text" class="form-control" placeholder="Company name" name="company_name" value="{{ old('company_name') }}" required="required" onkeyup="checkCharacter(this.value , 'Company Name' , 'l_name_err_msg' ,'registration_submit')" />
										<div class="form-control-feedback">
											<i class="icon-user-check text-muted"></i>
										</div>
                                         <span id="l_name_err_msg"></span>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group form-group-feedback form-group-feedback-right">
										<input type="email" class="form-control" placeholder="Your Email" name="email" value="{{ old('email') }}"  onblur="emailValidate(this.value , 'Email' , 'email_err_msg' ,'registration_submit')"  required="required">
										<div class="form-control-feedback">
											<i class="icon-envelop  text-muted"></i>
										</div>
                                         <span id="email_err_msg"></span>
									</div>
								</div>

								<div class="col-md-6">
									<div class="form-group form-group-feedback form-group-feedback-right">
										<input type="tel" class="form-control" placeholder="Your Mobile" name="mobile" value="{{ old('mobile') }}"  /> 
										<div class="form-control-feedback">
											<i class="icon-mobile text-muted"></i>
										</div>
                                        
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6">
									<div class="form-group form-group-feedback form-group-feedback-right">
										<input type="password" class="form-control" placeholder="Create password" name="password" id="password" required="required" onkeyup="CheckPasswordStrength()" />
										<div class="form-control-feedback">
											<i class="icon-user-lock text-muted"></i>
										</div>
                                        <span id="password_err_msg"></span>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group form-group-feedback form-group-feedback-right">
										<input type="password" class="form-control" placeholder="Repeat password" name="confirm_password" id="confirm_password" required="required" onkeyup="passowrdMatch()" />
										<div class="form-control-feedback">
											<i class="icon-user-lock text-muted"></i>
										</div>
                                        <span id="confirmPwdError"></span>
									</div>
								</div>
							</div>
							<div class="row" style="margin-top:0px;">
							 <div class="col-sm-12">
							    <select name="how_did_you_know" id="how_did_you_know" class="form-control" >
								<option value="0">@lang('messages.howdidyouknow')</option>
                                <option value="1">Newspaper Advert</option>
                                <option value="2">Magazine Advert</option>
                                <option value="3">Promotional Email</option>
                                <option value="4">Facebook/Twitter</option>
                                <option value="5">Google Search</option>
                                <option value="6">Other internet search</option>
                                <option value="7">Family / Friend</option>
								</select>         
                             </div>
                          </div>
                            <div class="row" style="margin-top:15px;">
							<div class="col-sm-3">
                                      <div class="d-flex justify-content-between align-items-center">
										<div class="form-check form-check-inline">
											<label class="form-check-label">
												<input type="radio" class="form-control-styled" name="roll_type"  id="roll_type" value="1" checked="checked">
												@lang('messages.Seller')
                                                
											</label>
										</div>
									</div>
                                     </div>
                                     <div class="col-sm-3">
                                      <div class="d-flex justify-content-between align-items-center">
										<div class="form-check form-check-inline">
											<label class="form-check-label">
												<input type="radio" class="form-control-styled" name="roll_type" id="roll_type" value="2">
												@lang('messages.Workshop')
											</label>
										</div>
									</div>
                                     </div>
                          </div>
                          <div class="row" style="margin-top:15px;">
							 <div class="col-sm-12">
                               <div class="d-flex justify-content-between align-items-center">
										<div class="form-check form-check-inline">
											<label class="form-check-label">
												<input type="checkbox" class="form-control-styled term_condition" name="term_condition" value="1" data-fouc required="required">
													@lang('messages.acceptNote')
											</label>
										</div>
									</div>         
                             </div>
                          </div>
                            <div class="row" style="margin-top:15px;">
							 <div class="col-sm-12">
                               <div class="d-flex justify-content-between align-items-center">
										<div class="form-check form-check-inline">
											<label class="form-check-label">
												<input type="checkbox" class="form-control-styled term_condition" name="news_letter" value="1" data-fouc required="required">
													@lang('messages.newsletter')
											</label>
										</div>
									</div>         
                             </div>
                          </div>
                            <div class="row" style="margin-top:15px;">
                              <div class="form-group">
							   <button type="submit" class="btn bg-teal-400 btn-labeled btn-labeled-right" id="registration_submit"><b><i class="icon-plus3"></i></b> Create account</button>
                               <a href="{{ url('/login') }}" class="btn btn-primary btn-labeled-right"><b><i class="icon-login"></i></b> Sign in </a>
                              </div>
                             </div>
						</div>
					</div>
				</div>
			</div>
		</form>
		{{-- /registration form --}}
	</div>
	{{-- /content area --}}
	@include('layouts.footer')
</div>
{{-- main content --}}
</div>
{{-- /page content --}}
    @stop
