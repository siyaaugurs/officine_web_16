@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
@if($workshop_status == 100)
    <div class="card" style="margin-bottom:10px;">
        <div class="content">
            <div id="filter-panel">
                <div class="panel panel-default">
                    <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <a href="<?php echo url("spacial_condition/request_quots") ?>" class="btn btn-warning"><i class="fa fa-plus"></i>&nbsp;@lang('messages.SpecialPricecondition')</a> 
                            <button type="button" style="float:right" class="btn btn-info" data-toggle="collapse" data-target="#add_request_quotes_details">Add Service details &nbsp;<i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- <div class="row" style="margin-bottom:20px;">
    <div class="col-sm-6">
        <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#add_request_quotes_details">Add Service details &nbsp;<i class="fa fa-plus"></i></button>
    </div>
</div> -->
<div class="card collapse" id="add_request_quotes_details" >
    <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-plus"></i>&nbsp; Save Service Detail </h6>
    </div>
	<div class="card-body">
        <form id="save_request_for_quotes_details" autocomplete="off">
            @csrf
            <div class="row">
                <div class="col-sm-6">
                    <label>Select Service&nbsp;<span class="text-danger">*</span></label>
                    <div class="form-group">
                         <select class="form-control" name="category" id="category" required >
                          @forelse($main_category as $category)
                            <option value="{{ $category->id }}">{{ $category->main_cat_name }}</option>
                          @empty
                          @endforelse   
                        </select>                                
                        </div>
                </div>
            </div>
            <div class="row">
                      <div class="col-sm-6">
                       <label>Maximum Appointment&nbsp;<span class="text-danger">*</span></label>
                        <div class="form-group">
                         <input type="text" class="form-control" id="max_appointment" name="max_appointment" placeholder="Maximum  Appointment" required />           
                        </div> 
                      </div>
                   </div>
            <!-- <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Hourly Rate&nbsp;<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="hourly_rate" name="hourly_rate" placeholder="Hourly Rate" required />
                    </div>
                </div>   
            </div> -->
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" id="save_service_details" class="btn bg-blue ml-3">Save&nbsp;<i class="icon-paperplane ml-2"></i></button>
                    </div>
                </div>
        </form>
	</div>
</div>
<div class="card" id="history_special_condition" >
<div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;Service Details</h6>
    </div>
	<div class="card-body" style="overflow: auto">
		<table class="table table-bordered">
            <thead>
                <tr>
                    <th>@lang('messages.SN')</th>
                    <th>@lang('messages.Services')</th>
                    <th>Max. Appointment</th>
                   <!--  <th>Price</th> -->
                    <th class="text-center">@lang('messages.Actions')</th>
                </tr>
                
                  @forelse($service_list as $list)
                  <tr>
                    <th>{{ $loop->iteration }}</th>
                    <th>{{ $list->main_cat_name }}</th>
                    <th>{{ !empty($list->max_appointment) ? $list->max_appointment : "N/A" }}</th>
                    <!-- <th>&euro; {{ !empty($list->hourly_cost) ? $list->hourly_cost : "N/A" }}</th> -->
                     <th><a href="javascript::void()" data-hourlyrate="{{ $list->hourly_cost }}"  data-maxappointment="{{ $list->max_appointment }}" data-category="{{ $list->main_category_id }}" class="btn btn-primary editservice" data-toggle="collapse" data-target="#add_request_quotes_details"><i class="fa fa-edit"></i></a></th>
                  </tr>
                  @empty
                   <tr>
                     <td colspan="5" class="danger">No record found</td>
                   </tr>
                  @endforelse   
            </thead>
            <tbody>
                 
            </tbody>
        </table>
	</div>
</div>
@else 
  <div>
    <div class="row card" style="padding:40px;">
        <div class="col-lg-12">
            <h3 align="center">Please Complete your Profile </h3>
            <p align="center"><a href="{{ url('')}}" class="btn btn-primary">Manage Profile</a></p>
        </div>
    </div>
  </div>
@endif
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
<script>
$(document).ready(function(e) {
 /*Edit Service */	
 $(document).on('click','.editservice',function(){
     var edit_service = $(this);
	 category = edit_service.data('category');
	 $('#category').find("option[value='"+ category +"']").attr('selected','selected');
	 $("#hourly_rate").val(edit_service.data('hourlyrate'));
	 $("#max_appointment").val(edit_service.data('maxappointment'))  
 });
 /*End*/
 /*Save Service detail script start*/
  $(document).on('submit','#save_request_for_quotes_details',function(e){
		$('#msg_response').html(" ");
		$('#save_service_details').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
		e.preventDefault();
		$.ajax({
			url: base_url+"/vendor/request_for_quotes_ajax/save_service_detail",
			type: "POST",        
			data: new FormData(this),
			contentType: false,
			cache: false,
			processData:false,  
			success: function(data){
				var parseJson = jQuery.parseJSON(data); 
				$('#save_service_details').html('Save <i class="icon-paperplane ml-2"></i>').attr('disabled' , false);
				if(parseJson.status == 200){
					$("#msg_response").html(parseJson.msg);
					setTimeout(function(){ location.reload(); } , 1000);
				}  
				if(parseJson.status == 400){
					$.each(parseJson.error, function(key , value) {
					    errorString += '<div class="notice notice-danger"><strong>Wrong , </strong>'+ value+' .</div>';
					});
				   $("#msg_response").html(errorString);	
			   	}
				if(parseJson.status == 100){
					$("#msg_response").html(parseJson.msg);
				}	
			   $("#msg_response_popup").modal({
				  backdrop:'static',
				  keyboard:false,
				});	
			} , 
			error: function(xhr, error){
				$('#group_btn').html('Save <i class="icon-paperplane ml-2"></i>').attr('disabled' , false);
				$("#msg_response").html('<div class="notice notice-danger"><strong>Wrong , </strong> Something went wrong , please try again .</div>');
				$("#msg_response_popup").modal({
				  backdrop:'static',
				  keyboard:false,
				});	
			}
		});
	});
 /*End*/	
$('[data-toggle="tooltip"]').tooltip(); 
});
</script>
@endpush