
@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="card">
	<div class="card-body">
		<form id="coupon_form">
      		@csrf
			<div class="form-group">
				<label>Select  Category </label>
                    <select class="form-control multiselect" name="coupon_type" >
                        <option value="1">Coupon</option>
                        <option value="2">Group Coupon</option>
                    </select>
					<span id="title_err"></span>
			</div>
			<div class="form-group">
				<label>Title <span class="text-danger">*</span></label>
				<input type="text" name="coupon_title" id="coupon_title" class="form-control" placeholder="@lang('messages.couponTitle')" required="required" />
				<span id="title_err"></span>
			</div>
			
            <div class="row form-group" id="">
				<div class="col-sm-6">
					<label>Quantity<span class="text-danger">*</span>                                                                                                                       </label>
					<input type="number" class="form-control" name="coupon_quantity" value=""  required="required"/>
                </div>
                <div class="col-sm-6">
					<label>Per User (Allowed Quantity)<span class="text-danger">*</span></label>
					<input type="number" class="form-control" name="per_user_allot" value=""  required="required"/>
				</div>
			</div>
            <div class="row form-group" id="">
				<div class="col-sm-6">
					<label>Lanching Date<span class="text-danger">*</span></label>
					<input type="text" class="form-control datepicker" name="launching_date" value=""  required="required"/>
				</div>
				<div class="col-sm-6">
					<label>Closed On<span class="text-danger">*</span></label>
					<input type="text" class="form-control datepicker" name="closed_date" value=""  required="required"/>
				</div>
			</div>
            <div class="row form-group" id="">
				<div class="col-sm-6">
					<label>Available Date<span class="text-danger">*</span></label>
					<input type="text" class="form-control datepicker" name="avail_date" value=""  required="required"/>
				</div>
				<div class="col-sm-6">
					<label>Expiry Date<span class="text-danger">*</span></label>
					<input type="text" class="form-control datepicker" name="avail_close_date" value=""  required="required"/>
				</div>
			</div>
            <div class="row">
                <div class="col-md-12 form-group">
                    <label>No. Of Coupon Being Purchased<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="per_user_allot" value=""  required="required"/>
                    <span id="title_err"></span>
                </div>
            </div>
			<!-- <div class="row">
				<div class="col-md-12 form-group">
                    <label>Coupon Description<span class="text-danger">*</span></label>
                    <textarea class="form-control" rows="5" name="description" id="description" value="" placeholder="@lang('messages.couponDescription')" required="required"></textarea>
                    <span id="title_err"></span>
				</div>
			</div> -->
            <div class="col-md-12 form-group">
                <img id="coupon_image" src="{{ asset('storage/coupon/default.png') }}" alt="" height="150" width="150">
                <input type="hidden"  name="coupon_image_copy" id="" value=""/>
				<input type="file" class="form-control" placeholder="" name="coupon_image" id="imgInp" />
				<!-- <span class="form-control">
                    Browse… <input type="file" class="form-control" id="imgInp" name="coupon_image" required="required">
                </span> -->
                <span id="start_date_err"></span>
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
				<a href="../vendor/index.html" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
				<span class="breadcrumb-item active"> Add Coupons </span>
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