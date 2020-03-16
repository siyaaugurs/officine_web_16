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
			                	<form id="business_details_form">
                                    @csrf
									<div class="form-group">
										<label>Owner Name <span class="text-danger">*</span></label>
										<input type="text" name="owner_name" id="owner_name" class="form-control" placeholder="Owner Name" value="{{ $business_details->owner_name ?? '' }}"  />
                                        <span id="owner_name_err"></span>
									</div>
									<div class="row">
								     <div class="col-md-12 form-group">
                                    <label>Business Name&nbsp;<span class="text-danger">*</span></label>
									<input type="text" class="form-control" placeholder="Business Name" name="business_name" id="business_name" value="{{ $business_details->business_name ?? '' }}"  />
								</div>
							        </div>
                                    <div class="row">
								     <div class="col-md-12 form-group">
                                       <label>About Business  <span class="text-danger">*</span></label>
									   <textarea type="text" class="form-control" placeholder="About Business" name="about_business" id="about_business">{{ $business_details->about_business ?? '' }}</textarea>
                                       <span id="about_business_err"></span>
								     </div>
                                    </div>
                                    <div class="row">
								     <div class="col-md-6 form-group">
                                       <label> Registration Proof <span class="text-danger">*</span>&nbsp; @if(!empty($business_details->registration_proof)) <a target="_blank" href='{{ asset("storage/business_details/$business_details->registration_proof") }}'>click to view</a> @endif
                                       </label>
                                        <input type="hidden" name="registration_proof_copy" value="{{ $business_details->address_proof ?? ''}}" />
									   <input type="file" class="form-control" placeholder="Browse Registration Proof" name="registration_proof" id='registration_proof' />
                                       <span id="registration_proof_err"></span>
								     </div>
                                     <div class="col-md-6 form-group">
                                    <label>Address Proof<span class="text-danger">*</span>&nbsp; @if(!empty($business_details->address_proof))<a target="_blank" href='{{ asset("storage/business_details/$business_details->address_proof") }}'>click to view</a> @endif</label>
									   <input type="hidden" name="address_proof_copy" value="{{ $business_details->address_proof ?? ''}}" />
                                       <input type="file" class="form-control" placeholder="Browse Address proof" name="address_proof" id='address_proof'   />
                                       <span id="title_err"></span>
								</div>
							        </div>
                                    <div class="row form-group">
								     <div class="col-sm-6">
                                      <label>Address 1</label>
                                       <input type="text" class="form-control" placeholder="Address 1" name="address_1" id="address_1" value="{{ $business_details->address_1 ?? '' }}"  />
                                     </div>
                                     <div class="col-sm-6">
                                      <label>Address 2</label>
                                       <input type="text" class="form-control" placeholder="Address 2" name="address_2" id="address_2" value="{{ $business_details->address_2 ?? '' }}" />
                                     </div>
							        </div>
                                    <div class="row form-group">
                                     <div class="col-sm-6">
                                       <label>Address 3</label>
                                       <input type="text" class="form-control" placeholder="Address 3" name="address_3" id="address_3" value="{{ $business_details->address_3 ?? '' }}" />
                                     </div>
                                     <div class="col-sm-6">
                                       <label>Landmark</label>
                                       <input type="text" class="form-control" placeholder="Landmark" name="landmark" id="landmark" value="{{ $business_details->landmark ?? '' }}" />
                                     </div>
							        </div>
                                    <div id="response"></div>
                                    <div class="d-flex justify-content-between align-items-center">
										<button type="submit" id="business_details_sbmt" class="btn bg-blue ml-3">Submit <i class="icon-paperplane ml-2"></i></button>
									</div>
								</form>
							</div>
		                </div>
                   </div>     
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
							<span class="breadcrumb-item active"> Add Business Details </span>
						</div>
						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
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

