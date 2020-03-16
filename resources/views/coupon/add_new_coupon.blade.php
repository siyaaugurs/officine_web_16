@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="card">
	<div class="card-header bg-light header-elements-inline">
		<h6 class="card-title" style="font-weight:600;"><i class="fa fa-plus"></i>&nbsp;Add New Coupon</h6>
	</div>
	<div class="card-body">
		<form id="coupon_form">
      		@csrf
			<div class="form-group">
				<label>Select  Category </label>
                    <select class="form-control" name="coupon_type" id="coupon_group">
					    <option value="1">Coupon</option>
                        <option value="2">Group Coupon</option>
                    </select>
			</div>
            <div class="form-group" id="for_coupon_group">
			</div>
			<div class="form-group">
				<label>Title&nbsp;<span class="text-danger">*</span></label>
				<input type="text" name="coupon_title" id="coupon_title" class="form-control" placeholder="@lang('messages.couponTitle')" required="required" value="" />
				<span id="title_err"></span>
			</div>
			<div class="row form-group" id="number_of_users_group_div" style="display:none;">
				<div class="col-sm-12">
					<label>Number of user in coupon group &nbsp;<span class="text-danger">*</span></label>
					<input type="number" class="form-control" name="number_of_user_in_group" placeholder="Number of user in coupon group"  min="0" />
                </div>
            </div>
			<div class="row form-group">
				<div class="col-sm-6">
					<label>Coupon Avail Quantity&nbsp;<span class="text-danger">*</span></label>
					<input type="number" class="form-control" name="coupon_quantity" placeholder="@lang('messages.Quantity')" value="" min="0" required="required"/>
                </div>
                <div class="col-sm-6">
					<label>Per User (Allowed Quantity)&nbsp;<span class="text-danger">*</span></label>
					<input type="number" class="form-control" name="per_user_allot" placeholder="@lang('messages.AllowedUserQuantity')" value="" min="1" max="4" required="required"/>
				</div>
			</div>
            <div class="row form-group">
				<div class="col-sm-6">
					<label>Launching Date&nbsp;<span class="text-danger">*</span></label>
					<input type="text" id="datecupan" class="form-control datepicker" name="launching_date" placeholder="@lang('messages.StartDate')" value="" readonly="readonly" required="required"/>
				</div>
				<div class="col-sm-6">
					<label>Closed On&nbsp;<span class="text-danger">*</span></label>
					<input type="text" class="form-control datepicker" name="closed_date" placeholder="@lang('messages.ClosingDate')" id="datecupan1" value="" readonly="readonly" required="required"/>
				</div>
			</div>
            <div class="row form-group" id="">
				<div class="col-sm-6">
					<label>Available Date&nbsp;<span class="text-danger">*</span></label>
					<input type="text" class="form-control datepicker" name="avail_date" placeholder="@lang('messages.AvailableDate')" id="avail_date" value="" readonly="readonly"  required="required"/>
				</div>
				<div class="col-sm-6">
					<label>Expiry Date<span class="text-danger">*</span></label>
					<input type="text" class="form-control datepicker" id="cpoupon_expiry_date" name="avail_close_date" placeholder="@lang('messages.ExpiryDate')" value=""  readonly="readonly" required="required"/>
				</div>
			</div>
			<div class="row form-group" id="">
				<div class="col-sm-6">
					<label>Select Offer Type</label>
					<select class="form-control" name="offer_type" >
					    <option value="1">In percentage</option>
                        <option value="2">In Amount</option>
                    </select>
				</div>
				<div class="col-sm-6">
					<label>Amount <span class="text-danger">*</span></label>
					<input type="number" class="form-control" name="amount" placeholder="@lang('messages.Amount')" value="" min="0" required="required"/>
                </div>
			</div>
            <div class="row form-group">
				<div class="col-sm-12">
					<label>Select Discount on Service / Products</label>
					<select class="form-control" name="select_dscount_on_products_service" id="select_dscount_on_products_service">
						<option value="" hidden="hidden">--Select Option--</option>
					    <!-- <option value="1">Discount on Total Price</option>
                        <option value="2">Discount on shipments </option> -->
                        <option value="3">Discount on the Single Product </option>
                        <option value="4">Discount on the single Service </option>
                        <option value="5">Discount on the Service Category</option>
                        <!-- <option value="6">Discount on a Specific Brand</option> -->
                    </select>
				</div>
			</div>

		
            <div class="row form-group" id="more_coupon_section">
			</div>
            <div class="row">
                <div class="col-md-12" id="coupon_response">
                </div>
			</div>
				<div class="row form-group">
				<div class="col-sm-12">
					<label>Workshop</label>
					<select class="form-control multiselect-select-all" multiple="multiple"  name="workshop_list[]" id="workshop_list">
					    @foreach($workshop_lists as $workshop_list)
					      @if( \serviceHelper::get_profile_status($workshop_list->id) == 100) }	
					         <option value="{{$workshop_list->id}}">{{$workshop_list->company_name}}(Officine-{{$workshop_list->id}})</option>
                          @endif
						@endforeach	
                    </select>
				</div>
			</div>
				<div class="row">
					<div class="col-md-12 form-group">
					<button type="submit" id="coupon_sbmt" class="btn bg-blue ml-3">Submit <i class="icon-paperplane ml-2"></i></button>
					</div>
				</div>
		</form>
	</div>
</div>
@endsection
@section('breadcrum')
	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
            <a href="{{ url('/') }}" class="breadcrumb-item">Admin </a>
				<span class="breadcrumb-item active">  Coupons / Add New Coupons </span>
			</div>
			<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
		</div>
	</div>

@stop
@push('scripts')
	<script src="{{ url('global_assets/js/plugins/forms/selects/bootstrap_multiselect.js')}}"></script>
  <script src="{{ url('global_assets/js/demo_pages/form_multiselect.js')}}"></script>
  <script src="{{ url('validateJS/coupon.js') }}"></script>
  <script src="{{ url('validateJS/date/jquery-ui.css') }}"></script>
  <script src="{{ url('validateJS/date/jquery-ui.js') }}"></script>
<script>
  // Material Select Initialization

	$(document).ready(function(e){
		$('select').selectpicker();
		$(".datepicker").datepicker();
		$(function () {
			$('#datetimepicker3').datetimepicker({
				format: 'HH:mm:ss'
			});
		});
		function readURL() {
            var $input = $(this);
            var $newinput =  $(this).parent().parent().parent().find('#coupon_image');
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    reset($newinput.next('.delbtn'), true);
                    $newinput.attr('src', e.target.result).show();
                    $newinput.after('<input type="button" class="btn btn-danger m-l-10 delbtn removebtn" value="X">');
                }
                reader.readAsDataURL(this.files[0]);
            }
        }
        $("#imgInp").change(readURL);
        $("form").on('click', '.delbtn', function (e) {
            reset($(this));
			$("#new").text('');
        });
        function reset(elm, prserveFileName) {
            if (elm && elm.length > 0) {
                var $input = elm;
                $input.prev('#coupon_image').attr('src', '').hide();
                if (!prserveFileName) {
                    $($input).parent().parent().parent().find('input#imgInp ').val("");
                    //input.fileUpload and input#uploadre both need to empty values for particular div
                }
                elm.remove();
            }
        }
	});
</script>

@endpush