@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
@if(session::has('msg'))
  {!! Session::get('msg') !!}
@endif
 <style> 
 .container{ padding:15px;} 
 .cardWrap1{ 
  overflow: auto;}
 </style>
<div class="row">
   <div class="col-sm-12">
      <div style="float:right; margin-bottom:15px;">
        <a data-toggle="collapse" data-target="#add_special_condition" data-toggle="tooltip" data-placement="top" title="Create new services " href='{{ url("master/delete_main_cat/") }}' class="btn btn-success"><i class="fa fa-plus" ></i></a>
      </div>  
   </div>  
</div>
<div class="card collapse" id="add_special_condition" >
    <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-plus"></i>&nbsp;Add Our Services</h6>
    </div>
	<div class="card-body">
        <form id="our_mot_services" autocomplete="off">
            @csrf
            <input type="hidden" name="mot_service_id" id="mot_service_id" value="">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Service Name&nbsp;<span class="text-danger">*</span></label>
                         <input type="text" class="form-control" id="service_name" name="service_name" placeholder="Service Name" />
                    </div>   
                </div>
            </div>	
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Service Description&nbsp;<span class="text-danger">*</span></label>
                         <textarea cols="5" name="service_description" class="form-control" placeholder="Service Description"></textarea>
                    </div>   
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                      <label>Service Km.&nbsp;<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="service_km" name="service_km" placeholder="Service Km." />
                </div>
                <div class="col-sm-6">
                    <label>Month.&nbsp;<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="month" name="month" placeholder="Month" />
                </div>
            </div>
            <div class="row rowPadding">
              <div class="col-sm-12">
                 <label>Car compatible.&nbsp;<span class="text-danger">*</span></label>          
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
            <div class="row rowPadding">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Select Category </label>
                        <select class="form-control multiselect" name="n3_category[]" id="n3_category" multiple>
                            <option value="0">--Select Version First--</option> 
                        </select>
                        <span id="title_err"></span>
                    </div>   
                </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                 <div id="err_response"></div>      
              </div>
             </div>
            <div class="row">
                    <div class="col-md-12">
                        <button type="submit" id="our_mot_services_btn" class="btn bg-blue ml-3">Submit <i class="icon-paperplane ml-2"></i></button>
                    </div>
                </div>
        </form>
	</div>
</div>
<div class="card" style="margin-bottom:10px;" >
    <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-filter"></i>&nbsp;@lang('messages.Filter')</h6>
        </div>
        <div class="content">
	    <div id="filter-panel">
            <div class="panel panel-default">
                <div class="panel-body">
                   <div class="row">
                      <div class="col-sm-6">
                        <div class="form-group">
                            <select class="form-control" name="car_makers" id="car_makers">
                               <option value="0">@lang('messages.selectMaker')</option>
                                 @foreach($cars__makers_category as $makers)
                                   <option value="@if(!empty($makers->idMarca)){{ $makers->idMarca }} @endif">@if(!empty($makers->Marca)){{ $makers->Marca }} @endif</option>
                                 @endforeach 
                            </select>                                
                        </div> 
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                            <select class="form-control" name="car_models" id="car_models">
                                 <option value="0">@lang('messages.firstSelectMakers')</option>
                            </select>                                
                        </div>
                      </div>
                   </div>
                   <div class="row">
                      <div class="col-sm-6">
                        <div class="form-group">
                            <select class="form-control car_version_group" id="version_id" name="car_version" data-action="save_mot_service">
                                <option value="0">@lang('messages.firstSelectModels')</option>
                            </select>                                
                        </div> 
                      </div>
                      <div class="col-sm-6" style="display:none" id="service_schedule_div">
                        <div class="form-group">
                            <select class="form-control" name="service_shedule" id="service_shedule" data-action="get_save_interval">
                              <option value="0">@lang('messages.firstSelectVersion')</option>
                            </select>                                
                        </div>
                      </div>
                   </div>
                   <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <a href='#' id="search_mot_services" class="btn btn-warning" style="color:white;">Search &nbsp;<span class="glyphicon glyphicon-search"></span></a>                                 
                          <a href='#' id="new_service_schedule" class="btn btn-primary" style="color:white;float:right;display:none" data-toggle="tooltip" data-placement="top" title="New Service Schedule" >  &nbsp;<span class="glyphicon glyphicon-plus"></span></a>
                        </div>
                      </div>
                   </div>
                </div>
            </div>
        </div>  
  </div>
</div>
<div class="card cardWrap1">
    <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;Our Mot Services</h6>
        </div>
    <div class="card-body" id="our">
        @include('admin.component.our_mot_service' , ['our_mot_services'=>$our_mot_services])
    </div>
</div>
<div id="mot_interval_body"></div>
<div class="modal" id="add_new_service_schedule">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Add New Schedule</h4>
                <hr />
            </div>
            <!-- Modal body -->
            <form id="add_new_schedule" >
                <input type="hidden" value="" name="category_id" id="category_id" />
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label>@lang('messages.ScheduleId')&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="@lang('messages.ScheduleId')" name="schedule_id" id="schedule_id" required="required"  />
                            <span id="start_date_err"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                        <label>@lang('messages.ScheduleDescription')&nbsp;<span class="text-danger">*</span></label>
                        <textarea class="form-control" name="schedule_description" id="schedule_description"  placeholder="@lang('messages.ScheduleDescription')"></textarea>
                        <span id="start_date_err"></span>
                        </div>
                    </div>
                    <span id="add_response"></span>
                    <span id="err_response"></span>
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
<div class="modal" id="add_new_mot_interval">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Add New MOT Interval</h4>
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
                            <label>@lang('messages.ServiceIntervalId')&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="@lang('messages.ScheduleId')" name="schedule_id" id="schedule_id" required="required"  />
                            <span id="start_date_err"></span>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>@lang('messages.Additional')&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="@lang('messages.Additional')" name="additional" id="additional" required="required"  />
                            <span id="start_date_err"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>@lang('messages.SortOrder')&nbsp;<span class="text-danger">*</span></label>
                            <input type="number" class="form-control" placeholder="@lang('messages.SortOrder')" name="sort_order" id="sort_order" required="required"  />
                            <span id="start_date_err"></span>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>@lang('messages.ServiceKm')&nbsp;<span class="text-danger">*</span></label>
                            <input type="number" class="form-control" placeholder="@lang('messages.ServiceKm')" name="service_km" id="service_km" required="required"  />
                            <span id="start_date_err"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label>@lang('messages.ServiceMonth')&nbsp;<span class="text-danger">*</span></label>
                            <input type="number" class="form-control" placeholder="@lang('messages.ServiceMonth')" name="service_month" id="service_month" required="required"  />
                            <span id="start_date_err"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label>@lang('messages.StandardServiceTime')&nbsp;<span style="color:red">(in hr.)</span>&nbsp;<span class="text-danger">*</span></label>
                            <input type="number" class="form-control timepicker" placeholder="@lang('messages.StandardServiceTime')" name="std_service_time" id="StandardServiceTime" required="required"  />
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
<!--Service Schedule interval modal start-->
  <div class="modal" id="interval_popup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Info</h4>
                <hr />
            </div>
            <!-- Modal body -->
             <div id="interval_response"></div> 
        </div>
        <div class="modal-footer">       
        </div>
    </div>
</div>
<!--End-->
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="{{ url('/') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>@lang('messages.Home')</a>
            <a href="{{ url('/') }}" class="breadcrumb-item">@lang('messages.Admin')</a>
            <span class="breadcrumb-item active">Mot Services List  </span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop
@push('scripts')
<link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" />
 <script src="{{ url('validateJS/mot_service.js') }}"></script>
 <script src="{{ url('validateJS/products.js') }}"></script>
 <script src="{{ asset('validateJS/special_conditions_cars.js') }}"></script>
 <script src="{{ url('global_assets/js/demo_pages/form_multiselect.js') }}"></script>
<script src="{{ url('global_assets/js/plugins/forms/selects/bootstrap_multiselect.js') }}">
</script>
<script>
$(document).ready(function(e) {
    $(document).on('change', '#version_id', function(e){
        e.preventDefault();
        var version_id = $('#version_id').val();
        if(version_id != 0) {
            $.ajax({
                url:base_url+"/mot_services/get_all_group",
                method:"GET",
                data:{version_id:version_id},
                complete:function(e, xhr, settings){
                    var parseJson = jQuery.parseJSON(e.responseText);
                    if(parseJson.status == 200){
                        // $("#n3_category").html(parseJson.response).multiselect('destroy').multiselect();
                        $("#n3_category").html(parseJson.response).multiselect('rebuild');
                        $("#preloader").hide();
                    }
                    if(parseJson.status == 100){
                        // $("#n3_category").html(parseJson.response).multiselect('destroy').multiselect();
                        $("#n3_category").html(parseJson.response).multiselect('rebuild');
                        $("#preloader").hide(); 
                    }
                },
                error: function(xhr, error){
                    $("#preloader").hide();
                }
		    }); 
        }
    });
 /*Our mot service category btn*/
  $(document).on('click','.our_mot_service_category',function(e){
    id = $(this).data('id');
    e.preventDefault();
	 $.ajax({
			url:base_url+"/mot_services/our_mot_service_category",
			method:"GET",
			data:{id:id},
			success:function(e, xhr, settings){
			  $("#interval_popup").modal('show');
		      $("#interval_response").html(e);
			 },
			error: function(xhr, error){
		     $("#preloader").hide();
		    }
		}); 
  });	
 /*End*/
 /*Our Mot Services info*/
  $(document).on('click','.our_mot_service_info',function(e){
	id = $(this).data('id');
    e.preventDefault();
	 $.ajax({
			url:base_url+"/mot_services/our_mot_service_info",
			method:"GET",
			data:{id:id},
			success:function(e, xhr, settings){
			  $("#interval_popup").modal('show');
		      $("#interval_response").html(e);
			 },
			error: function(xhr, error){
		     $("#preloader").hide();
		    }
		}); 
  });	
 /*End*/
  $(document).on('click','.interval_info',function(e){
	id = $(this).data('id');
    e.preventDefault();
	 $.ajax({
			url:base_url+"/mot_services/interval_info",
			method:"GET",
			data:{id:id},
			success:function(e, xhr, settings){
		      $("#interval_response").html(e);
			 },
			error: function(xhr, error){
			  $("#interval_response").html(e);
		    }
			,complete: function(e , xhr , setting){
			   $("#interval_popup").modal('show'); 
			}
		}); 
  });	
});
</script>
@endpush

