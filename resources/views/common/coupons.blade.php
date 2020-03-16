@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="card">
	<div class="card-body">
		<form id="coupon_form">
      		@csrf
			<input type="hidden" value="<?php if(!empty($coupons_details)) echo $coupons_details->id; ?>" name="edit_id" />  
			<div class="form-group">
				<label>Select  Category </label>
                    <select class="form-control multiselect" name="coupon_type" >
					    <option value="1" <?php if(!empty($coupons_details)){ if($coupons_details->coupon_type == 1) echo "selected"; }?>>Coupon</option>
                        <option value="2" <?php if(!empty($coupons_details)){ if($coupons_details->coupon_type == 2) echo "selected"; }?>>Group Coupon</option>
                    </select>
					<span id="title_err"></span>
			</div>
			<div class="form-group">
				<label>Title&nbsp;<span class="text-danger">*</span></label>
				<input type="text" name="coupon_title" id="coupon_title" class="form-control" placeholder="@lang('messages.couponTitle')" required="required" value="<?php if(!empty($coupons_details)) echo $coupons_details->coupon_title; ?>" />
				<span id="title_err"></span>
			</div>
			
            <div class="row form-group" id="">
				<div class="col-sm-6">
					<label>Quantity&nbsp;<span class="text-danger">*</span></label>
					<input type="number" class="form-control" name="coupon_quantity" placeholder="@lang('messages.Quantity')" value="<?php if(!empty($coupons_details)) echo $coupons_details->coupon_quantity; ?>" min="0" required="required"/>
                </div>
                <div class="col-sm-6">
					<label>Per User (Allowed Quantity)&nbsp;<span class="text-danger">*</span></label>
					<input type="number" class="form-control" name="per_user_allot" placeholder="@lang('messages.AllowedUserQuantity')" value="<?php if(!empty($coupons_details)) echo $coupons_details->per_user_allot; ?>" min="1" max="4" required="required"/>
				</div>
			</div>
            <div class="row form-group" id="">
				<div class="col-sm-6">
					<label>Launching Date&nbsp;<span class="text-danger">*</span></label>
					<input type="text" id="datecupan" class="form-control datepicker" name="launching_date" placeholder="@lang('messages.StartDate')" value="<?php if(!empty($coupons_details)) echo $coupons_details->launching_date; ?>" readonly="readonly" required="required"/>
				</div>
				<div class="col-sm-6">
					<label>Closed On&nbsp;<span class="text-danger">*</span></label>
					<input type="text" class="form-control datepicker" name="closed_date" placeholder="@lang('messages.ClosingDate')" id="datecupan1" value="<?php if(!empty($coupons_details)) echo $coupons_details->closed_date; ?>" readonly="readonly" required="required"/>
				</div>
			</div>
            <div class="row form-group" id="">
				<div class="col-sm-6">
					<label>Available Date&nbsp;<span class="text-danger">*</span></label>
					<input type="text" class="form-control datepicker" name="avail_date" placeholder="@lang('messages.AvailableDate')" id="avail_date" value="<?php if(!empty($coupons_details)) echo $coupons_details->avail_date; ?>" readonly="readonly"  required="required"/>
				</div>
				<div class="col-sm-6">
					<label>Expiry Date<span class="text-danger">*</span></label>
					<input type="text" class="form-control datepicker" id="cpoupon_expiry_date" name="avail_close_date" placeholder="@lang('messages.ExpiryDate')" value="<?php if(!empty($coupons_details)) echo $coupons_details->avail_close_date; ?>"  readonly="readonly" required="required"/>
				</div>
			</div>
			<div class="row form-group" id="">
				<div class="col-sm-6">
					<label>select offer type</label>
					<select class="form-control multiselect" name="offer_type" >
					    <option value="1" <?php if(!empty($coupons_details)){ if($coupons_details->offer_type == 1) echo "selected"; }?> >In percentage</option>
                        <option value="2" <?php if(!empty($coupons_details)){ if($coupons_details->offer_type == 2) echo "selected"; }?> >In rate</option>
                    </select>
				</div>
				<div class="col-sm-6">
					<label>Amount <span class="text-danger">*</span></label>
					<input type="number" class="form-control" name="amount" placeholder="@lang('messages.Amount')" value="<?php if(!empty($coupons_details)) echo $coupons_details->amount; ?>" min="0" required="required"/>
                </div>
			</div>
            <div class="col-md-12 form-group">
				@if(empty($coupons_details->coupon_image))
					<img id="coupon_image" src="{{ asset('storage/coupon/default.png') }}" alt="" height="150" width="150">
				@else
					<img id="coupon_image" src="{{ asset('storage/coupon_image/'.$coupons_details->coupon_image) }}" alt="" height="150" width="150">
				@endif
				<span class="btn btn-default btn-file">
                    Browse… <input type="file" class="form-control form-group" id="imgInp" name="coupon_image">
                </span>
				<img id='coupon_image'/>
            </div>
			<div id="response_coupon"></div>
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
				<a href="JavaScript:Void(0);" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
				<span class="breadcrumb-item active"> {{ $page_bread_crum }} Coupons </span>
			</div>
			<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
		</div>
	</div>

@stop

@push('scripts')
  <script src="{{ url('validateJS/vendors.js') }}"></script>
  <script src="{{ url('validateJS/date/jquery-ui.css') }}"></script>
  <script src="{{ url('validateJS/date/jquery-ui.js') }}"></script>
<script>
	$(document).ready(function(e){
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