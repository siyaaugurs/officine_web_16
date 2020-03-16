@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<style> .container{ padding:15px;} </style>
<div class="card" style="margin-bottom:10px;">
   <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp; Kromeda Monitoring Api List</h6>
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
                          <a href="javascript::void()" id="search_monitoring_by_date" class="btn btn-warning" style="color:white;">Search &nbsp;<span class="glyphicon glyphicon-search"></span></a>                        </div>             
        </div>
    </div>
  <hr />  
    <div class="row">
        <div class="col-sm-12">
          <div class="">
            <select class="form-control" id="select_seatch_status">
              <option hidden="hidden">Select Status</option>
              <option value="1">Last 7 days Api Hits </option>
              <option value="2">Last days Api Hits </option>
              <option value="3">Last Hour </option>
            <!--  <option>Last Minute </option>-->
            </select>
          </div>
        </div>
        <div class="col-sm-12" style="margin-top:30px;" id="monitoring_list">
          @include('admin.component.k_monitoring')
        </div>
    </div>
</div>
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="javascript::void()" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>@lang('messages.Home')</a>
            <a href="javascript::void()" class="breadcrumb-item">@lang('messages.Admin')</a>
            <span class="breadcrumb-item active">Kromeda Monitoring</span>
        </div>
        <a href="javascript::void()" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop
@push('scripts')
<script src="{{ url('validateJS/date/jquery-ui.css') }}"></script>
<script src="{{ url('validateJS/date/jquery-ui.js') }}"></script>
  <script>
  	$( function() {
     $( ".datepicker" ).datepicker();
    });
	
    $(document).ready(function(e) {
	  /*manage date*/
      $(document).on('click','#search_monitoring_by_date',function(){
	    var start_date = $("#start_date");
		var end_date = $("#end_date");
		if(start_date != "" || end_date != ""){
		   $("#preloader").show()
		   $.ajax({
				url: base_url+"/kromeda_monitoring_ajax/get_monitoring_by_dates",
				method: "GET",
				data: {start_date:start_date.val() , end_date:end_date.val()},
				success: function(data){
					 $("#preloader").hide()
					 $("#monitoring_list").html(data);
				}
			});
		   }
		else{
		    alert("Please select start and end date !!!");
		  }   
	  });
	  /*End*/	
      $(document).on('change','#select_seatch_status',function(e){
		 $("#preloader").show(); 
	     var status = $(this);
		 $.ajax({
			url: base_url+"/kromeda_monitoring_ajax/get_monitoring_status",
			method: "GET",
			data: {status:status.val()},
			success: function(data){
				 $("#preloader").hide(); 
				$("#monitoring_list").html(data);
			}
	    });
	  }); 
    });
  </script>
@endpush
