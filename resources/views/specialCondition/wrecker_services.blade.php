@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="row" style="margin-bottom:20px;">
    <div class="col-sm-12">
        <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#add_wrecker_special_condition">Add Special Condition&nbsp;<i class="fa fa-plus"></i></button>
    </div>
</div>
<div class="card collapse" id="add_wrecker_special_condition" >
    <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-plus"></i>&nbsp;Wrecker Services Special Condition</h6>
    </div>
	<div class="card-body">
        <form id="special_wrecker_condition_form" autocomplete="off">
            @csrf
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Select Services Type &nbsp;<span class="text-danger">*</span></label>
                        <select class="form-control" name="service_type" >
                            <option value="" hidden="hidden">--Select Service Type--</option>
                            <option value="0">All Services</option>
                            <option value="1">Service by Appointment</option>
                            <option value="2">Emergency service</option>
                        </select>
                        <span id="title_err"></span>
                    </div>   
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Select Services&nbsp;<span class="text-danger">*</span></label>
                            <select class="form-control" name="service_name" id="service_name" required>
                             <option value="0">All Services</option>
                             @forelse($wrecker_services_category as $service_cat)
                                <option value="{{ $service_cat->id }}">{{ $service_cat->services_name }}</option>
                             @empty
                             <option value="0">No category </option>
                             @endforelse
                            </select>
                    </div>
                </div>   
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <label>Select Cars&nbsp;<span class="text-danger">*</span></label>
                </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                    <select class="form-control makers" name="car_makers">
                       <option value="0" hidden="hidden">@lang('messages.selectMaker')</option>
                       <option value="1">All Cars</option>
                         @foreach($cars__makers_category as $makers)
                           <option value="@if(!empty($makers->idMarca)){{ $makers->idMarca }} @endif">@if(!empty($makers->Marca)){{ $makers->Marca }} @endif</option>
                         @endforeach 
                    </select>                                
                </div> 
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                    <select class="form-control models" name="car_models">
                         <option value="0">@lang('messages.firstSelectMakers')</option>
                    </select>                                
                </div>
              </div>
           </div>
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                    <select class="form-control versions" id="version_id" name="car_version" data-action="get_n3_category">
                        <option value="0">@lang('messages.firstSelectModels')</option>
                    </select>                                
                </div> 
              </div>
           </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Operation Type&nbsp;<span class="text-danger">*</span></label>
                        <select class="form-control" name="operation_type" id="operation_type" required>
                            <option value="1">Special Price</option>   
                            <option value="2">Do not perform operation</option>   
                        </select>
                    </div>   
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Weight Type&nbsp;<span class="text-danger">*</span></label>
                        <select class="form-control" name="weight_type" id="weight_type" required>
                            <option value="0">All Types Of Weight</option>
                            <option value="1">Type 1 (1 to 2000)</option>   
                            <option value="2">Type 2 (2000 to 3000)</option>   
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
                    <input type="text" class="form-control" id="start_time" name="start_time" placeholder="@lang('messages.StartTime')" required />
                </div>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="end_time" name="end_time" placeholder="@lang('messages.EndTime')" required />
                </div>
            </div>
            <div class="row rowPadding">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Select discount type </label>
                        <select class="form-control" name="discount_type" id="discount_type">
                            <option value="">--Select Option--</option>  
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
                        <input type="text" class="form-control" id="maximum_appointment" name="maximum_appointment" placeholder="@lang('messages.maxAppointment')" required />
                    </div>
                </div>   
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <h6 class="card-title" style="font-weight:600;">Repetition details</h6>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Start Date&nbsp;<span class="text-danger">*</span></label>
                        <input type="text" class="form-control datepicker" name="start_date" placeholder="@lang('messages.StartDate')" id="start_date" value="" readonly="readonly"  required/>
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
                            <td><input type="checkbox" value="{{ $days->id }}" name="weekly_days[]" id="weekly_days" class="days"></td> 
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
                        <input type="text" class="form-control datepicker" id="expiry_date" name="expiry_date" placeholder="@lang('messages.ExpiryDate')" value="<?php if(!empty($coupons_details)) echo $coupons_details->avail_close_date; ?>"  readonly="readonly" required="required"/>
                    </div>
                </div>
            </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" id="wrecker_special_condition_btn" class="btn bg-blue ml-3">Submit <i class="icon-paperplane ml-2"></i></button>
                    </div>
                </div>
        </form>
	</div>
</div>
<div class="card" id="history_special_condition" >
<div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;Wrecker Services Special Conditions List</h6>
    </div>
	<div class="card-body" style="overflow: auto">
		<table class="table table-bordered">
            <thead>
               <tr>
                    <th colspan="10"></th>
                    <th colspan="2">@lang('messages.Repetitiondetails')</th>
                    <th class="text-center"></th>
                </tr>
                <tr>
                    <th>@lang('messages.SN')</th>
                    <th>@lang('messages.Services')</th>
                     <th>@lang('messages.Makers')</th>
                    <th>@lang('messages.CarModel')</th>
                    <th>@lang('messages.CarVersion')</th>
                    <th>@lang('messages.WeightType')</th>
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
                @forelse ($special_conditions as $special_condition)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        @if($special_condition->services_name == NULL)
                            <td>All Services</th>
                        @else 
                            <td>{{ $special_condition->services_name }}</th>
                        @endif
                           <td><?php if(!empty($special_condition->maker_name))  echo $special_condition->maker_name; else echo "N/A"; ?></td>
                           <td><?php if(!empty($special_condition->model_name))  echo $special_condition->model_name; else echo "N/A"; ?></td>
                           <td><?php if(!empty($special_condition->version_name))  echo $special_condition->version_name; else echo "N/A"; ?></td>
                        <td>
                            @if($special_condition->weight_type == 1)
                                Weight Type 1 (1 to 2000)
                            @elseif($special_condition->weight_type == 2)
                                Weight Type 2 (2000 to 3000)
                            @elseif($special_condition->weight_type == 0)
                                All Types of Weight
                            @endif
                        </td>
                        <td>
                            @if($special_condition->operation_type == 1)
                                Special Price
                            @elseif($special_condition->operation_type == 2)
                                Do not perform operation
                            @endif
                        </td>
                        <td>{{ $special_condition->start_hour }} &nbsp;- &nbsp;{{ $special_condition->end_hour }} </td>
                        <td>{{ $special_condition->amount_percentage ? $special_condition->amount_percentage : 'N/A' }} </td>
                        <td>{{ $special_condition->max_appointement }}</td>
                        <td>{{ $special_condition->start_date }}/ {{ $special_condition->expiry_date }}</td>
                        <td> 
                            @if($special_condition->select_type == 1)
                                Daily
                            @elseif($special_condition->select_type == 2)
                                Weekly
                            @elseif($special_condition->select_type == 3)
                                Monthly
                            @elseif($special_condition->select_type == 4)
                                Yearly
                            @endif
                        </td>
                        <td class="text-center">
                            @if($special_condition->select_type == 2)
                                <a href="#" data-toggle="tooltip" data-placement="top" data-id="{{ $special_condition->id }}" title="View Selected Days" class="btn btn-primary btn-sm check_days" ><i class="fa fa-list"></i></a>
                            @else
                                
                            @endif
                            &nbsp;&nbsp;&nbsp;&nbsp;<a href='{{ url("spacial_condition/edit_wrecker_service/$special_condition->enctype_id") }}' data-toggle="tooltip" data-placement="top" title="Edit" class="btn btn-primary btn-sm" ><i class="fa fa-edit"></i></a>
                            &nbsp;&nbsp;&nbsp;&nbsp;<a href='{{ url("spacial_condition/remove_special_condition/$special_condition->id") }}' data-id="{{ $special_condition->id }}" data-toggle="tooltip" data-placement="top" title="Delete" class="btn btn-danger btn-sm delete_special_condition" ><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>  
                @empty
                    <tr>
                    <td colspan="10">Special Conditions Not Available</td>
                    </tr>
                @endforelse  
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

<div class="modal" id="view_selected_service_days">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Selected days</h4>
                <hr />
            </div>
            <div id="days_result">
            
            </div>
            <div id="response_about_pakages"></div>
        </div>
        <div class="modal-footer"></div>
    </div>
</div>
<div class="modal" id="view_selected_service_cars">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Selected Cars</h4>
                <hr />
            </div>
            <div id="cars_result">
            
            </div>
            <div id="response_about_pakages"></div>
        </div>
        <div class="modal-footer"></div>
    </div>
</div>
@endsection
@section('breadcrum')
	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="JavaScript:Void(0);" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
				<span class="breadcrumb-item active">Wrecker Services</span>
                <span class="breadcrumb-item active">Special  Condition  </span>
			</div>
			<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
		</div>
	</div>
@stop
@push('scripts')
<script src="{{ url('global_assets/js/demo_pages/form_multiselect.js') }}"></script>
<script src="{{ url('global_assets/js/plugins/forms/selects/bootstrap_multiselect.js') }}">
</script>
<script src="{{ url('validateJS/vendors.js') }}"></script>
<script src="{{ asset('validateJS/special_conditions_cars.js') }}"></script>
<script src="{{ url('validateJS/special_condition.js') }}"></script>
<script src="{{ url('validateJS/date/jquery-ui.css') }}"></script>
<script src="{{ url('validateJS/date/jquery-ui.js') }}"></script>
<script>
$(document).ready(function(e) {
	 $('[data-toggle="tooltip"]').tooltip(); 
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