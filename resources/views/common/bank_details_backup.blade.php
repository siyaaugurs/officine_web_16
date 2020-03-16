@extends('layouts.master_layouts')
@section('content')
<div class="content">

<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="tab_here mb-3">
        <ul class="nav nav-pills m-b-10" id="pills-tab" role="tablist">
           <li class="nav-item">
                <a class="nav-link <?php if($page == "add_business_details") echo "active"; ?>"  href='{{ url("add_business_details")}}'>Business Details</a>
            </li>
           <li class="nav-item">
             <a class="nav-link  <?php if($page == "bank_details") echo "active"; ?>" href='{{ url("bank_details")}}'>Bank Details</a>
            </li>
        </ul>
    </div>
<div class="card">
			                <div class="card-body">
			                	<form id="bank_details_form">
                                    @csrf
									<div class="form-group">
										<label>Account Holder Name <span class="text-danger">*</span></label>
										<input type="text" name="account_holder_name" id="account_holder_name" class="form-control" placeholder="Account Holder Name" value="{{ $bank_details->account_holder_name ?? '' }}" required="required"  />
                                        <span id="account_holder_name_err"></span>
									</div>
									<div class="row">
								     <div class="col-md-6 form-group">
                                    <label>Account Number&nbsp;<span class="text-danger">*</span></label>
									<input type="text" class="form-control" placeholder="Account Number" name="account_number" id="account_number" value="{{ $bank_details->account_number ?? '' }}" required="required" />
								</div>
                                     <div class="col-md-6 form-group">
                                    <label>IFSC Code&nbsp;<span class="text-danger">*</span></label>
									<input type="text" class="form-control" placeholder="IFSC Code" name="ifsc_code" id="ifsc_code" value="{{ $bank_details->ifsc_code ?? '' }}" required="required"  />
								</div>
							        </div>
                                    <div class="row">
								     <div class="col-md-12 form-group">
                                       <label>Bank Name&nbsp;<span class="text-danger">*</span></label>
									  <input type="text" class="form-control" placeholder="Bank Name" name="bank_name" id="bank_name" value="{{ $bank_details->bank_name ?? '' }}" required="required"  />
                                       <span id="about_business_err"></span>
								     </div>
                                    </div>
                                    <div class="row">
								     <div class="col-md-6 form-group">
                                       <label> Branch Name&nbsp;<span class="text-danger">*</span>&nbsp;
                                       </label>
                                       <input type="text" class="form-control" placeholder=" Branch Name" name="branch_name" id='branch_name' required="required" value="{{ $bank_details->branch_name ?? '' }}" />
                                       <span id="branch_name_err"></span>
								     </div>
                                     <div class="col-sm-6">
                                      <input type="hidden" id="country_edit_id" value="@if(!empty($bank_details->country_id)){{ $bank_details->country_id }} @endif">
                                         <input type="hidden" id="country_edit_name" value="@if(!empty($bank_details->country_name)){{ $bank_details->country_name }} @endif">
                                      <label>Country &nbsp;<span class="text-danger">*</span>&nbsp;</label>
                                       <select class="form-control country" name="country" id="country_1">
                                        @if(!empty($bank_details->country_id))
                                        <option value="<?php echo $bank_details->country_id."@".$bank_details->country_name; ?>">{{ $bank_details->country_name }}</option>
                                        @endif
                                        <option value="0">--Select-- Country--Name</option>
                                       </select>
                                     </div>
							        </div>
                                    <div class="row form-group">
								     <div class="col-sm-6">
                                      <label>State &nbsp;<span class="text-danger">*</span>&nbsp;</label>
                                     
                                       <select class="form-control state" name="state" id="state">
                                         @if(!empty($bank_details->state_id))
                                        <option value="<?php echo $bank_details->state_id."@".$bank_details->state_name; ?>">{{ $bank_details->state_name }}</option>
                                        @else
                                         <option value="0">--Select--State--Name--</option>
                                        @endif
                                        
                                       </select>
                                     </div>
                                     <div class="col-sm-6">
                                       <label>City &nbsp;<span class="text-danger">*</span>&nbsp;</label>
                                       <select class="form-control cities" name="city" id="city">
                                       @if(!empty($bank_details->state_id))
                                        <option value="<?php echo $bank_details->city_id."@".$bank_details->city_name; ?>">{{ $bank_details->city_name }}</option>
                                        @else
                                         <option value="0">--Select--City--Name--</option>
                                        @endif
                                        
                                        
                                       </select>
                                     </div>
							        </div>
                                 
                                    <div class="d-flex justify-content-between align-items-center">
										<button type="submit" id="bank_details_sbmt" class="btn bg-blue ml-3">Submit <i class="icon-paperplane ml-2"></i></button>
									</div>
								</form>
                              <div id="response_bank_details"></div>
							</div>
		                </div>
                   </div>     
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home </a>
							<span class="breadcrumb-item active"> Add Bank Details </span>
						</div>
						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>
				</div>
@stop
@push('scripts')
  <script src="{{ url('validateJS/date/jquery-ui.css') }}"></script>
  <script src="{{ url('validateJS/date/jquery-ui.js') }}"></script>
  <script src="{{ url('validateJS/vendors.js') }}"></script>
@endpush

