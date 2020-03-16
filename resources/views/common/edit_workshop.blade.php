@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<input type="hidden" name="country_id" id="country_id" value="{{ $workshop_details->country_id ?? "" }}" />
<input type="hidden" name="city_id" id="city_id" value="{{ $workshop_details->city_id ?? "" }}" />
<div class="card">
			                <div class="card-body">
			                @if($workshop_details != null)	
                              <form id="edit_workshop_form" >
                                    @csrf
									<div class="form-group">
										<label>Title <span class="text-danger">*</span></label>
                                        <input type="hidden" name="edit_id" id="edit_id" value="{{ $workshop_details->id }}"> 
										<input type="text" name="title" id="title" class="form-control" placeholder="WorkShop title" required="required" value='{{ $workshop_details->title ?? "Not mentioned"}}' />
                                        <span id="title_err"></span>
									</div>
									<div class="row">
								     <div class="col-md-6 form-group">
                                       <label>Start Date <span class="text-danger">*</span></label>
									   <input type="text" class="form-control" placeholder="Select start date" name="start_date" id="datepicker" required="required" value='{{ $workshop_details->workshop_start_date ?? " "}}' readonly="readonly"  />
                                       <span id="start_date_err"></span>
								     </div>
                                     <div class="col-md-6 form-group">
                                    <label>End Date <span class="text-danger">*</span></label>
									<input type="text" class="form-control" placeholder="Select End date" name="end_date" id="datepicker1" value='{{ $workshop_details->workshop_end_date ?? " "}}' required="required" readonly="readonly" />
								</div>
							        </div>
                                    <div class="row">
								     <div class="col-md-6 form-group">
                                       <label>Start time <span class="text-danger">*</span></label>
									   <input type="text" class="form-control" placeholder="Select Start timing" name="start_time" id='start_time' value="{{ $workshop_details->workshop_start_time ?? " "}}" required="required" />
                                       <span id="title_err"></span>
								     </div>
                                     <div class="col-md-6 form-group">
                                    <label>End time <span class="text-danger">*</span></label>
									<input type="text" class="form-control" placeholder="Select End timing" name="end_time" id="end_time" value='{{ $workshop_details->workshop_end_time ?? " "}}' required="required" />
								</div>
							        </div>
                                    <div class="row form-group">
								     <div class="col-sm-3">
                                      <div class="d-flex justify-content-between align-items-center">
										<div class="form-check form-check-inline">
											<label class="form-check-label">
												<input type="checkbox" class="form-control-styled paid_status" name="work_shop_paid" value="1" <?php if(!empty($workshop_details->paid_status)) echo "checked"; ?> data-fouc>
												Paid
											</label>
										</div>
									</div>
                                     </div>
                                     <div class="col-sm-3">
                                      <div class="d-flex justify-content-between align-items-center">
										<div class="form-check form-check-inline">
											<label class="form-check-label">
												<input type="checkbox" class="form-control-styled address" name="address_status" value="1" <?php if(!empty($workshop_details->address_status)) echo "checked"; ?> data-fouc>
												Address
											</label>
										</div>
									</div>
                                     </div>
                                     <div class="col-sm-3">
                                     </div>
							        </div>
                                    <div class="row form-group" id="paid_amount_div" style="display:<?php if(empty($workshop_details->paid_status)){ echo "none"; } ?>">
								     <div class="col-sm-12">
                                      <label>Amount</label>
                                       <input type="text" class="form-control" placeholder="Paid Amount" name="amount" value="{{ $workshop_details->amount ?? "" }}"  />
                                     </div>
							        </div>
                                    <div id="address_div" style="display:<?php if(empty($workshop_details->address_status)){ echo "none"; } ?> ">
                                    <div class="row form-group">
								     <div class="col-sm-6">
                                      <label>Address</label>
                                       <input type="text" class="form-control" placeholder="Workshop Address" name="address" value="{{ $workshop_details->address ?? "" }}" />
                                     </div>
                                     <div class="col-sm-6">
                                       <label>Landmark</label>
                                       <input type="text" class="form-control" placeholder="Landmark" name="landmark" id="landmark" value="{{ $workshop_details->landmark ?? "" }}" />
                                     </div>
							        </div>
                                    <div class="row form-group" id="paid_amount_div">
                                     <div class="col-sm-6">
                                       <label>Country</label>
                                       <select class="form-control" name="country" id="country">
                                        <option value="0">--Select--Country--Name</option>
                                       </select>
                                     </div>
                                     <div class="col-sm-6">
                                      <label>City</label>
                                      <select class="form-control" name="city" id="city">
                                        <option value="0">First Select Country Name</option>
                                       </select>
                                     </div>
							        </div>
                                   </div> 
                                    <div class="row">
								     <div class="col-md-12 form-group">
                                       <label>Description <span class="text-danger">*</span></label>
									   <textarea class="form-control" rows="5" name="description" id="description"  placeholder="Workshop Description" required="required">{{ $workshop_details->description }}</textarea>
                                       <span id="title_err"></span>
								     </div>
                                    </div>
                                    <div id="response"></div>
                                    <div class="d-flex justify-content-between align-items-center">
										<div class="form-check form-check-inline">
											<button type="submit" id="workshop_sbmt" class="btn bg-blue ml-3">Submit <i class="icon-paperplane ml-2"></i></button>
										</div>
									</div>
								</form>
                            @else
                              <h1>Content is hide</h1>
                            @endif    
							</div>
		                </div>

@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="../vendor/index.html" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
							<a href="../vendor/internationalization_fallback.html" class="breadcrumb-item">Vendor </a>
							<span class="breadcrumb-item active"> Edit Workshop </span>
						</div>
						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>
					<div class="header-elements d-none">
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
					</div>
				</div>
@stop
@push('scripts')
  <script src="{{ url('validateJS/date/jquery-ui.css') }}"></script>
  <script src="{{ url('validateJS/date/jquery-ui.js') }}"></script>
  <script src="{{ url('validateJS/vendors.js') }}"></script>
  <script>
  /*Date and time picker script start*/
  $( function() {
    $( "#datepicker" ).datepicker();
  } );
  $( function() {
    $( "#datepicker1" ).datepicker();
  } );
 $(function () {
	$('#datetimepicker3').datetimepicker({
		format: 'LT'
	});
  });
/*End*/
  </script>
@endpush

