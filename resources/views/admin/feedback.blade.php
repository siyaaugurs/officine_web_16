@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="card" style="margin-bottom:10px;">
   <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp; Feedback List</h6>
    </div>
<div class="content">
<div class="row">
 		<div class="col-sm-6">
           	<div class="form-group">  
               <label>From :</label>
               <input type="text" class="form-control datepicker" name="start_date" placeholder="Start Date" id="start_date" value="" readonly="readonly"  required/>
           	</div>             
         </div>
        <div class="col-sm-6">
          <div class="form-group">  
               <label>To :</label>
               <input type="text" class="form-control datepicker" name="end_date" placeholder="End Date" id="end_date" value="" readonly="readonly"  required/>
           </div>             
        </div>
        <div class="col-sm-6">
          <div class="form-group">
                          <a href="javascript::void()" id="search_feedback_by_date" class="btn btn-warning" style="color:white;">Search &nbsp;<span class="glyphicon glyphicon-search"></span></a>                        </div>             
        	</div>
    	</div>
	
	</div>
</div>

	<!-- Page length options -->
	<div class="card" id="feedback_list">
	@include('admin.component.feedback_list')
							<div class="row" style="margin-top:20px;">
								<div class="col-sm-12">
									{{ $all_feedback->links() }}
							</div>
							</div>
	
	<!-- /page length options -->
</div>
@include('common.component.feedback_popup')
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
            <a href="{{ url('/') }}" class="breadcrumb-item">Admin </a>
							<span class="breadcrumb-item active"> Workshops </span>
						</div>
						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>
					<!--/*/*<div class="header-elements d-none">
						<div class="breadcrumb justify-content-center">
							<a href="#" class="breadcrumb-elements-item">
								<i class="icon-comment-discussion mr-2"></i>
								Support
							</a>
						</div>
					</div>*/*/-->
				</div>
@stop
@push('scripts')
<script src="{{ url('validateJS/admin.js') }}"></script>
<script src="{{ url('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ url('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>
<script src="{{ url('validateJS/date/jquery-ui.css') }}"></script>
<script src="{{ url('validateJS/date/jquery-ui.js') }}"></script>
<script>
  	$( function() {
     $( ".datepicker" ).datepicker();
    });
	
    $(document).ready(function(e) {
	  /*manage date*/
      $(document).on('click','#search_feedback_by_date',function(){
	    var start_date = $("#start_date");
		var end_date = $("#end_date");
		if(start_date != "" || end_date != ""){
		   $("#preloader").show()
		   $.ajax({
				url: base_url+"/admin_ajax/get_feedback_list",
				method: "GET",
				data: {start_date:start_date.val() , end_date:end_date.val()},
				success: function(data){		
					 $("#preloader").hide()
					$("#feedback_list").html(data);
				}
			});
		   }
		else{
		    alert("Please select start and end date !!!");
		  }   
	  });
	  /*End*/	 
    });
  </script>
@endpush


