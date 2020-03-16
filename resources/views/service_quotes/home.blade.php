@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<style>
select[name="service_for_quotes_tbl_length"] , #service_for_quotes_tbl_filter{
    margin:20px;
}
</style>
<div class="content">
	<!-- Page length options -->
    <div class="card" style="margin-bottom:10px;">

    <div class="card-header bg-light header-elements-inline">

        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-filter"></i>&nbsp;Filter</h6>

    </div>

    <div class="content">
	    <div id="filter-panel">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <select name="select_service_for" id="select_service_for" class="form-control">
                                    <option value="1">For Admin</option>
                                    <option value="2">For Workshop Service List</option>
                                </select> 
                            </div> 
                        </div>
                        <div class="col-sm-6">
                        <div class="form-group">
                          <a href="#" id="search_booking_service_quotes_via_type" class="btn btn-warning" style="color:white;">Search &nbsp;<span class="glyphicon glyphicon-search"></span></a>                                 
                        </div>
                        </div>
                    </div>
               </div>
            </div>
        </div> 
    </div>

</div>
    
	<div class="card">
        <div class="card-header bg-light header-elements-inline">
            <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;Service Booking for Request Quotes List</h6>
        </div>
		<table class="table" id="service_for_quotes_tbl">
			<thead>
				<tr>
					<th>SN.</th>
					<th>Requested Date</th>
					<th>Customer Name</th>
					<th>Status</th>
					<th class="text-center">Actions</th>
				</tr>
			</thead>
		</table>
	</div>
	<!-- /page length options -->
</div>
<!--Service Quotes details modal popup -->
<div class="modal" id="service_quotes__details_modal">
    <div class="modal-dialog">
        <div class="modal-content" style="width:700px; margin-left: -100px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Service Quotes details </h4>
                <hr />
            </div>
            <div id="err_response"></div>
            <!-- Modal body -->
                @csrf
                <input type="hidden" name="service_quote_id" id="service_quote_id"  readonly="readonly" />
                <div class="modal-body">
                  <div id="modal_response"></div>
                </div>
            <div id="image_result"></div>
        </div>
        <div class="modal-footer">
        </div>
    </div>
</div>
<!--End-->
@include('common.component.feedback_popup')
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
            <a href="{{ url('/') }}" class="breadcrumb-item">Admin </a>
							<span class="breadcrumb-item active"> Service Quotes Booking List </span>
						</div>
						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>
				</div>
@stop
@push('scripts')
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.12/datatables.min.css"/> -->
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.12/datatables.min.js"></script>
<script>
$(document).ready(function(e) {
 /*Draw datatable on search time */
 
 /*End*/   
/*Change Status script strat*/
   $(document).on('click','.change_service_status',function(e){
     e.preventDefault();
	 btn_details = $(this);
	 $.ajax({
			   url: base_url+"/service_quotes_agax/change_status",
			   type: "GET",        
			   data: {service_quote_id:btn_details.data('serviceid') , status:btn_details.data('status')},
			   	success: function(data){
					if(data == 1){
					  if(btn_details.data('status') == "D"){
					      btn_details.removeClass('btn btn-warning').addClass('btn btn-success').data('status' , 'P').html('Dispatch <i class="fa fa-toggle-on"></i>'); 
					     }
					  else{
					      btn_details.removeClass('btn btn-success').addClass('btn btn-warning').data('status' , 'D').html('Pending <i class="fa fa-toggle-off"></i>'); 
					     }  
					  }
			   	}
		   	});
   });
 /*End*/ 	
/*Get Service quotes detils script start*/	
  $(document).on('click','.service_quotes_details',function(e){
     e.preventDefault();
	 quotes_details = $(this);
     this_html = quotes_details.html();
     quotes_details.html('<i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
	 service_quote_id = quotes_details.data('quotesid');
	 if(service_quote_id != null){
	      $.ajax({
			   url: base_url+"/service_quotes_agax/get_quote_details",
			   type: "GET",        
			   data: {service_quote_id:service_quote_id},
			   	success: function(data){
                   $('#service_quote_id').val(service_quote_id);
					$("#modal_response").html(data);
					$("#service_quotes__details_modal").modal({
					  backdrop:'static' , 
					  keyboard:false 
					});
                },
                complete(e , xhr , setting){
                    quotes_details.html(this_html).attr('disabled' , false);
                }
		   	});
	   }
	 else{
	     alert("Somethind went wrong , please try again !!!");
	   }  
  });
 /*End*/
 
});
</script>
<script>
   $(function () {
    //var for_type =  $('#select_service_for option:selected').val();
        $('#service_for_quotes_tbl').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax":{
                     "url": "{{ url('service_quotes_agax/service_for_quotes_list') }}",
                     "dataType": "json",
                     "type": "GET",
                     "data":function(d){
                        d.for_type = $('#select_service_for option:selected').val();
                     }
                   },
            "columns": [
                { "data": "sNo" },
                { "data": "requested_date" },
                { "data": "customer_name" },
                { "data": "status" },
                { "data": "action" }
            ]	 

        });

        
        $(document).on('click' , '#search_booking_service_quotes_via_type' , function(e){
              e.preventDefault();
              $('#service_for_quotes_tbl').DataTable().draw(true);
        });
    });
    
</script>
@endpush


