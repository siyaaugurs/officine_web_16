@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
@if(session::has('msg'))
  {!! Session::get('msg') !!}
@endif
 <style> .container{ padding:15px;} </style>
<div class="card">
    <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;Details</h6>
       <!-- <a href='{{ url("products/add_group") }}' class="btn btn-primary" style="color:white;">Add New Category &nbsp;<span class="glyphicon glyphicon-plus"></span></a>-->
        </div>
	<div class="card-body" id="mot_interval_body" style="overflow:auto;">
       <table class="table table-bordered">
         <tr>
           <th>Makers</th>
           <td><?php if(!empty($model_details->makers_name)) echo $model_details->makers_name; ?></td>
         </tr>
         <tr>
           <th>Model</th>
           <td><?php if(!empty($model_details->Modello)) echo $model_details->Modello." >>"; ?>
		   <?php if(!empty($model_details->ModelloAnno)) echo $model_details->ModelloAnno; ?></td>
         </tr>
         <tr>
           <th>Version</th>
           <td><?php if(!empty($version_details->Versione)) echo $version_details->Versione; ?> <?php if(!empty($version_details->ModelloCodice)) echo $version_details->ModelloCodice; ?></td>
         </tr>
       </table>
    </div>
</div>
@if($status_obj != FALSE) 
<div class="card">
    <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;@lang('messages.KpartsList')</h6>
       <!-- <a href='{{ url("products/add_group") }}' class="btn btn-primary" style="color:white;">Add New Category &nbsp;<span class="glyphicon glyphicon-plus"></span></a>-->
        </div>
	<div class="card-body" id="mot_interval_body" style="overflow:auto;">
      @if($kPartsList != FALSE) 
        @include('admin.component.kPartsList' , ['kPartsList'=>$kPartsList])
      @endif  
    </div>
</div>
<div id="part_item_number_response"></div>
<div id="part_item_response"></div>
<div class="card">
<div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;@lang('messages.MotIntervalsOperationList')</h6>
        <a href='#' class="btn btn-primary" id="add_interval_operation" style="color:white; float:right;" >Add Interval Operation&nbsp;<span class="glyphicon glyphicon-plus"></span></a>
       <!-- <a href='{{ url("products/add_group") }}' class="btn btn-primary" style="color:white;">Add New Category &nbsp;<span class="glyphicon glyphicon-plus"></span></a>-->
        </div>
	<div class="card-body" id="mot_interval_body" style="overflow:auto;">
        @include('admin.component.interval_operation' , ['interval_operation'=>$interval_operation])

      @if($interval_operation->count() > 0)
       {{ $interval_operation->links() }}
      @endif
    </div>
</div>
@else
 <h1>No operation available !!!</h1>
@endif

<div class="modal" id="add_new_operation_interval">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Add New Operation Interval</h4>
                <hr />
            </div>
            <!-- Modal body -->
            <span id="add_response"></span>
            <span id="err_response"></span>
            <form id="add_new_schedule" >
                <input type="hidden" value="" name="category_id" id="category_id" />
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>@lang('messages.GroupSequence')&nbsp;<span class="text-danger">*</span></label>
                            <input type="number" class="form-control" placeholder="@lang('messages.GroupSequence')" name="group_sequence" id="group_sequence" required="required"  />
                            <span id="start_date_err"></span>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>@lang('messages.GroupName')&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="@lang('messages.GroupName')" name="group_name" id="group_name" required="required"  />
                            <span id="start_date_err"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>@lang('messages.SortSequence')&nbsp;<span class="text-danger">*</span></label>
                            <input type="number" class="form-control" placeholder="@lang('messages.SortSequence')" name="sort_sequence" id="sort_sequence" required="required"  />
                            <span id="start_date_err"></span>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>@lang('messages.OperationId')&nbsp;<span class="text-danger">*</span></label>
                            <input type="number" class="form-control" placeholder="@lang('messages.OperationId')" name="operation_id" id="operation_id" required="required"  />
                            <span id="start_date_err"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>@lang('messages.OperationAction')&nbsp;<span class="text-danger">*</span></label>
                            <input type="number" class="form-control" placeholder="@lang('messages.OperationAction')" name="operation_action" id="operation_action" required="required"  />
                            <span id="start_date_err"></span>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>@lang('messages.ServiceNotes')&nbsp;<span class="text-danger">*</span></label>
                            <input type="number" class="form-control" placeholder="@lang('messages.ServiceNotes')" name="service_notes" id="service_notes" required="required"  />
                            <span id="start_date_err"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label>@lang('messages.AdditinalCharge')&nbsp;<span class="text-danger">*</span></label>
                            <input type="number" class="form-control" placeholder="@lang('messages.AdditinalCharge')" name="additional_charges" id="additional_charges" required="required"  />
                            <span id="start_date_err"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                        <label>@lang('messages.OperationDescription')&nbsp;<span class="text-danger">*</span></label>
                        <textarea class="form-control" name="operation_description" id="operation_description"  placeholder="@lang('messages.OperationDescription')"></textarea>
                        <span id="start_date_err"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                        <label>@lang('messages.PartDesc')&nbsp;<span class="text-danger">*</span></label>
                        <textarea class="form-control" name="part_description" id="part_description"  placeholder="@lang('messages.PartDesc')"></textarea>
                        <span id="start_date_err"></span>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="form-check form-check-inline">
                            <button type="submit" id="car_revision_submit" class="btn bg-blue ml-3">@lang('messages.Submit') <i class="icon-paperplane ml-2"></i></button>
                        </div>
                    </div>
				</div>
			</form>
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
							<a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
							<a href="#" class="breadcrumb-item">Admin </a>
                            <a href="#" class="breadcrumb-item">MOT Test   </a>
                            <span class="breadcrumb-item active">Interval Operation List</span>
						</div>
						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>
				</div>
@stop
@push('scripts')
<link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" />
 <script src="{{ url('validateJS/mot_service.js') }}"></script>
 <script src="{{ url('validateJS/products.js') }}"></script>
  <script src="{{ url('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
  <script src="{{ url('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>
  <script>
$(document).ready(function(e) {
/*Products item search by mot */	
  $(document).on('click','.mot_item_number_btn',function(e){
     var mot_item_number = $(this);
	  item_number = mot_item_number.data('itemnumber');
	 if(item_number != ""){
		$("#part_item_response").html(" ");
	    $.ajax({
			url:base_url+"/mot_spare_parts/get_parts",
			method:"GET",
			data:{item_number:item_number},
			success: function(data){
			  $("#part_item_response").html(data);
			},
			complete:function(e, xhr, settings){
			 // mot_item_number.html('<i class="fa fa-list"></i>').attr('disabled' , false);
			 },
			error: function(xhr, error){
			  $("#msg_response_popup").modal('show');
			  $("#msg_response").html('<div class="notice notice-danger"><strong>Wrong , </strong> Something went wrong please try again !!!.</div>');
		    }
	    	});
	  }
	 
	 
  });
/*End*/
  $(document).on('click','.get_mot_part_list',function(e){
       e.preventDefault();
	   var partListBtn = $(this);
	   kr_part_id = partListBtn.data('id');
	   if(kr_part_id != ""){
		 partListBtn.html('<i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
		 $("#part_item_number_response").html(' ');
		   $.ajax({
			url:base_url+"/mot_spare_parts/get_part_number_list",
			method:"GET",
			data:{kr_part_id:kr_part_id},
			success: function(data){
			  $("#part_item_number_response").html(data);
			},
			complete:function(e, xhr, settings){
			  partListBtn.html('<i class="fa fa-list"></i>').attr('disabled' , false);
			 },
			error: function(xhr, error){
			  $("#msg_response_popup").modal('show');
			  $("#msg_response").html('<div class="notice notice-danger"><strong>Wrong , </strong> Something went wrong please try again !!!.</div>');
		    }
	    	});
		 }
  });    
});
</script>
@endpush

