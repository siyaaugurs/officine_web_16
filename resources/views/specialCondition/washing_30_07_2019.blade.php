@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="row" style="margin-bottom:20px;">
  <div class="col-sm-12">
  <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#add_special_condition">Add Special Condition&nbsp;<i class="fa fa-plus"></i></button>
  </div>
</div>
<div class="card collapse" id="add_special_condition" >
<div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-plus"></i>&nbsp;@lang('messages.CarWashingSpecialCon')</h6>
    </div>
	<div class="card-body">
		<form id="special_condition_form" autocomplete="off">
      		@csrf
		    <div class="row">
             <div class="col-sm-6">
                <div class="form-group">
				<label>Select Services </label>
                    <select class="form-control" name="coupon_type" >
					  @forelse($car_washing_category as $category)
                      <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                      @empty
                      @endforelse 
                    </select>
					<span id="title_err"></span>
			</div>   
             </div>
             <div class="col-sm-6">
              <div class="form-group">
				<label>Select Size&nbsp;<span class="text-danger">*</span></label>
				<select class="form-control" name="coupon_type" >
					<option value="1">Small</option>   
                    <option value="2">Average</option>   
                    <option value="3">Big</option>   
                </select>
			</div>
			</div>   
           </div>
            <div class="row">
             <div class="col-sm-12">
                <div class="form-group">
				<label>Operation Type&nbsp;<span class="text-danger">*</span></label>
                    <select class="form-control" name="coupon_type" >
					 <option value="1">Special Price</option>   
                     <option value="2">Do not perform operation</option>   
                    </select>
			</div>   
             </div>
           </div>	
            <div class="row">
              <div class="col-sm-12">
                <label>Choose Hour &nbsp;<span class="text-danger">*</span></label>
			  </div>
           </div>
            <div class="row">
			  <div class="col-sm-6">
                <input type="text" class="form-control" id="start_time" name="start_time[]" placeholder="@lang('messages.StartTime')" />
              </div>
              <div class="col-sm-6">
                <input type="text" class="form-control" id="end_time" name="end_time" placeholder="@lang('messages.EndTime')" />
              </div>
           </div>
            <div class="row rowPadding">
             <div class="col-sm-6">
                <div class="form-group">
				 <label>Select discount type </label>
                    <select class="form-control" name="coupon_type" >
					 <option value="1">Price per hour </option>   
                     <option value="2">Discount Percentage </option> 
                    </select>
					<span id="title_err"></span>
			</div>   
             </div>
             <div class="col-sm-6">
              <div class="form-group">
				<label>Amount / Percentage</label>
				<input type="text" class="form-control" id="amount" name="amount" placeholder="@lang('messages.Amount')" />
			</div>
			</div>   
           </div>
            <div class="row">
             <div class="col-sm-12">
              <div class="form-group">
				<label>Maximum Appointment at the same time (default)&nbsp;<span class="text-danger">*</span></label>
				<input type="text" class="form-control" id="maximum_appointment" name="maximum_appointment" placeholder="@lang('messages.maxAppointment')" />
			</div>
			</div>   
           </div>
            <div class="row">
              <div class="col-sm-12">
                 <h6 class="card-title" style="font-weight:600;">Repetition details
</h6>
              </div>
            </div>
            <div class="row">
		        <div class="col-sm-12">
				 <div class="form-group">
                   <label>Start Date&nbsp;<span class="text-danger">*</span></label>
					<input type="text" class="form-control datepicker" name="avail_date" placeholder="@lang('messages.StartDate')" id="start_date" value="" readonly="readonly"  required="required"/>
                 </div>
				</div>
			</div>
            <div class="row">
		      <div class="col-sm-12">
				<div class="form-group">
                 <label>Select type&nbsp;<span class="text-danger">*</span></label>
                 <select class="form-control" name="repeat_type" id="repeat_type">
					<option value="0">Select type</option>   
                    <option value="1">Every Day</option>   
                    <option value="2">Every Week</option>   
                    <option value="3">Every Month</option>   
                    <option value="4">Every Year</option>   
                </select>
                </div>
			  </div>
			</div>
              <div class="row" id="days_all_row" style="display:none;">
		      <div class="col-sm-12">
				<div class="form-group">
                 <label>Select days&nbsp;<span class="text-danger">*</span></label>
                    <table class="table table-bordered">
                      @foreach($all_weekly_day as $days)
                         <tr>
                           <td><input type="checkbox" value="" name="days[]" class="days"></td> 
                           <td>{{ $days->name }}</td>
                         </tr>
                      @endforeach 
                    </table>
                </div>
			  </div>
			</div>
            <div class="row">
			  <div class="col-sm-12">
				<div class="form-group">
                  <label>Expiry Date&nbsp;<span class="text-danger">*</span></label>
					<input type="text" class="form-control datepicker" id="cpoupon_expiry_date" name="avail_close_date" placeholder="@lang('messages.ExpiryDate')" value="<?php if(!empty($coupons_details)) echo $coupons_details->avail_close_date; ?>"  readonly="readonly" required="required"/>
                </div>
			  </div>
			</div>
				<div class="row">
					<div class="col-md-12">
					<button type="submit" id="" class="btn bg-blue ml-3">Submit <i class="icon-paperplane ml-2"></i></button>
					</div>
				</div>
		</form>
	</div>
</div>
<div class="card" id="history_special_condition" >
<div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;@lang('messages.HistoryCarWashingSpecialCon')</h6>
    </div>
	<div class="card-body">
		<table class="table table-bordered" style="overflow:auto;">
            <thead>
               <tr>
                    <th colspan="7"></th>
                    <th colspan="2">@lang('messages.Repetitiondetails')</th>
                    <th class="text-center"></th>
                </tr>
                <tr>
                    <th>@lang('messages.SN')</th>
                    <th>@lang('messages.Services')</th>
                    <th>@lang('messages.Size')</th>
                    <th>@lang('messages.OperationType')</th>
                    <th>@lang('messages.Hour')</th>
                    <th>@lang('messages.Amount/Percentage')</th>
                    <th>@lang('messages.maxAppointment')</th>
                    <th>@lang('messages.StartDate')/@lang('messages.EndDate')</th>
                     <th>@lang('messages.Repetitiondetails')</th>
                    <th class="text-center">@lang('messages.Actions')</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                   <td>1</th>
                   <td>Interior + exterior washing</th>
                    <td>Small</td>
                    <td>Special price</td>
                    <td>08:00- 12:00</td>
                    <td>50 Euro </td>
                    <td>1</td>
                    <td>14-06-2019 / 30-06-2019</td>
                    <td>Monthly </td>
                    <td class="text-center"></td>
                </tr>
                <tr>
                   <td>1</th>
                   <td>Body Wash</th>
                    <td>Small</td>
                    <td>Special price</td>
                    <td>08:00- 12:00</td>
                    <td>50% </td>
                    <td>1</td>
                    <td>14-06-2019 / 30-06-2019</td>
                    <td>Every week</td>
                    <td class="text-center">
                    <a href="#" data-toggle="tooltip" data-placement="top" title="Selected days list" class="btn btn-primary btn-sm check_days" data-original-title="Selected days list"><i class="fa fa-list"></i></a>
                    </td>
                </tr>    
            </tbody>
        </table>
	</div>
</div>
<div class="modal" id="selected_days_popup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Selected days</h4>
                <hr />
            </div>
            <!-- Modal body -->
                <table class="table table-bordered">
                      @foreach($all_weekly_day as $days)
                         <tr>
                           <td>{{ $days->name }}</td>
                           <td><a href="javascript:void()" class="btn btn-danger"><i class="fa fa-trash"></i></a></td> 
                         </tr>
                      @endforeach 
                    </table>
        </div>
        <div class="modal-footer">       
        </div>
    </div>
</div>
@endsection
@section('breadcrum')
	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="JavaScript:Void(0);" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
				<span class="breadcrumb-item active">Car Washing  </span>
                <span class="breadcrumb-item active">Special  Condition  </span>
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
$(document).ready(function(e) {
	 $('[data-toggle="tooltip"]').tooltip(); 
/*Checkdays modal open popup*/
$(document).on('click','.check_days',function(e){
   e.preventDefault();
  $('#selected_days_popup').modal('show');
});
/*End*/	
  $(document).on('change','#repeat_type',function(){
     var type = $("#repeat_type").val();
	 if(type == 2){
	      $("#days_all_row").show();
		}  
	 else{
		  $("#days_all_row").hide();
		  $(".days").prop('checked',false);
		}	
   });  
});
</script>
@endpush