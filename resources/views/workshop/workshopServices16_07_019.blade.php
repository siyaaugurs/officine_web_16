@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />

<!-- <div class="card">  -->   
    <!-- <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title">Add New Services <a class="list-icons-plus-1" data-action="collapse"></a></h6>
        <div class="header-elements">
            <div class="list-icons">
              <a class="list-icons-item" data-action="remove"></a>
            </div>
        </div>
    </div> -->      
@if(Session::has('msg'))
            {!!  Session::get("msg") !!}
            @endif
    <!-- <div class="card-body">
       @if($service_days->count() > 0) 
        <form id="add_services_form" autocomplete="off">
            @csrf
            <div class="form-group">
               
                <label>@lang('messages.AboutServices')&nbsp;<span class="text-danger">*</span></label>
                <textarea type="text" class="form-control" placeholder="@lang('messages.AboutServices')" name="about_services" id="about_services" value="" required="required" onkeyup="checkaboutdata()"></textarea>
                <span id="title_err"></span>
            </div>
            <div class="row form-group">
               <div class="col-sm-12">
                      <label>Service Average Timing <span class="text-danger">(in minute) *</span></label>
                      <input type="number" name="service_average_time" id="service_average_time" value="" class="form-control"  placeholder="Service Average Time" required="required" min="0" max="1000"/>
                    </div>
                </div>
            <div class="row form-group">
               <div class="col-sm-12">
                      <label>Select Car size&nbsp;<span class="text-danger">*</span></label>
                      <select class="form-control" name="car_size" id="car_size">
                         <option hidden="hidden">--Select--Car--Size--</option>
                         <option value="1">Small</option>
                         <option value="2">Average </option>
                         <option value="3">Big</option>
                      </select>
                    </div>
                </div>        
            <div class="d-flex justify-content-between align-items-center">
                <div class="form-check form-check-inline">
                    <button type="submit" id="add_services_btn_copy" class="btn bg-blue ml-3">@lang('messages.Submit')<i class="icon-paperplane ml-2"></i></button>
                </div>
            </div>
        </form>
       @else
         <h2>First Complete your profile , and fill your workshop timing </h2>
         <a href="{{ url('add_time_details') }}" class="btn btn-primary">Go >></a>
       @endif 
    </div> -->
<!-- </div> -->
<div class="card" style="margin-bottom:10px;">
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
                            <label>@lang('messages.SelectCategory')</label>
                            <select name="washing_category" id="search_washing_category" class="form-control">
                                <option value="" hidden="hidden">--Selecty--Category--</option>
                                @forelse($car_washing_category as $category)
                                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                @empty
                                @endforelse
                            </select>                               
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
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;@lang('messages.CarWashServiceList')
        </h6>
        <a href='#' class="btn btn-primary" id="add_services" style="color:white; float:right;">Add New Services&nbsp;<span class="glyphicon glyphicon-plus"></span></a>
    </div>
	<div class="card-body" id="user_data_body">
	    @include('workshop.component.category_list' , ['listed_services_list'=>$listed_services_list]) 
        {{ $listed_services_list->links() }}
    </div>
</div>
<!--Manage time slot services-->
<div class="modal" id="manage_time_slot">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i>
				</button>
				<h4 class="modal-title" id="myModalLabel">Add Time Slots </h4>
				<hr />
			</div>
			<!-- Modal body -->
			<form id="time_slots_form">
                <input type="hidden" value="" id="slot_service_id" name="slot_service_id">
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12" id="edit_response"></div>
					</div>@forelse($service_days as $pakages_days)
					<div class="day-row">
						<div class="d-flex justify-content-between align-items-center mb-3">
							<div class="form-check form-check-inline service_days">
								<label class="form-check-label">
									<input type="checkbox" class="form-control-styled weekly_days" name="week_days[]" value="{{ $pakages_days->common_weekly_days_id}}" data-fouc onclick="check_rows_data(1)">{{ $pakages_days->name }}
                                </label>
							</div>
						</div>
						<div class="add_fields" style="display:none">
							<div class="row">
								<div class="col-sm-4">
									<div class="form-group">
										<label>@lang('messages.StartTime')&nbsp;<span class="text-danger">*</span>
										</label>
										<input type="text" class="form-control" id="start_time" name="start_time[]" placeholder="@lang('messages.StartTime')" onblur="check_rows_data(2)" />
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
										<label>@lang('messages.EndTime')&nbsp;<span class="text-danger">*</span>
										</label>
										<input type="text" class="form-control" id="end_time" name="end_time[]" placeholder="@lang('messages.EndTime')" onblur="check_rows_data(3)" />
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
										<label>Discount Type&nbsp;<span class="text-danger">*</span>
										</label>
										<select name="discount_type[]" id="discount_type" class="form-control">
                                            <option value="" hidden="hidden">--Select Type--</option>
                                            <option value="1">In Percantage</option>
                                            <option value="2">In Amount.</option>
                                        </select>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
										<label>Discount&nbsp;<span class="text-danger">*</span>
										</label>
										<input type="number" name="discount[]" id="discount" class="form-control" placeholder="Discount" />
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
										<label>Special Time Slot&nbsp;<span class="text-danger">*</span>
										</label>
										<select name="special_time_slot_type[]" id="special_time_slot_type" class="form-control special_slot">
                                            <option value="" hidden="hidden">--Select Type--</option>
                                            <option value="1">Daily</option>
                                            <option value="2">Weekly</option>
                                            <option value="3">Monthly</option>
                                        </select>
									</div>
								</div>
								<div class="col-sm-4 slot" style="display:none">
									<div class="form-group">
										<label>Special Date&nbsp;<span class="text-danger">*</span>
										</label>
                                        <input type="text" name="monthly_date[]" class="form-control datepicker monthly_date" placeholder="Select Date" readonly>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group pt-4">
										<button type="button" class="btn btn-success add_btn"> <i class="icon-plus3"></i>&nbsp;@lang('messages.AddMore')</button>
									</div>
								</div>
							</div>
						</div>
					</div>@empty @endforelse
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<button type="submit" class="btn btn-success" id="add_time_slot">@lang('messages.Save') &nbsp;<i class="icon-paperplane ml-2"></i>
								</button>
							</div>
						</div>
					</div>
				</div>
			</form>
			<div id="response_about_pakages"></div>
		</div>
		<div class="modal-footer"></div>
	</div>
</div>
<!--End-->

<div class="modal" id="add_car_washing_services">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i>
				</button>
				<h4 class="modal-title" id="myModalLabel">Add Services </h4>
				<hr />
			</div>
			<!-- Modal body -->
            <div class="card-body">
                @if($service_days->count() > 0) 
                    <form id="add_services_form" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label>@lang('messages.SelectCategory')&nbsp;<span class="text-danger">*</span></label>
                            <select name="washing_category" id="category_id" class="form-control">
                              <option value="0">--Selecty--Category--</option>
                              @forelse($car_washing_category as $category)
                                 <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                              @empty
                              @endforelse
                            </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('messages.AboutServices')&nbsp;<span class="text-danger">*</span></label>
                            <textarea type="text" class="form-control" placeholder="@lang('messages.AboutServices')" name="about_services" id="about_services" value="" required="required" ></textarea>
                            <span id="title_err"></span>
                        </div>
                        <div class="form-group">
                            <label>@lang('messages.HourlyRate')&nbsp;<span class="text-danger">*</span></label>
                            <input type="number" class="form-control" placeholder="@lang('messages.HourlyRate')" name="hourly_rate" id="hourly_rate" onkeyup="check_correct_data()" required="required" min="1" max="1000">
                               <span class="text-danger" id="hourly_rate_err"></span>
                        </div>
                        <div class="form-group">
                            <label>@lang('messages.maxAppointment')&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="@lang('messages.maxAppointment')" name="max_appointment" id="max_appointment" required="required" />
                            <span id="title_err"></span>
                        </div>
                        <div class="row form-group">
                            <div class="col-sm-12">
                                <table class="table table-bordered" style="margin-bottom:15px;">
                                    <thead>
                                        <tr>
                                            <th>Cars</th>
                                            <th>Small</th>
                                            <th>Average</th>
                                            <th>Big</th>
                                        </tr> 
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>Time&nbsp;<span class="text-danger">* In hour</span></th>
                                            <th><input type="text" class="form-control calculate_time" onkeyup="check_correct_data(1 , this.value)" placeholder="@lang('messages.time')" name="small_time" id="small_time" required="required"   /></th>
                                            <th><input type="text" class="form-control calculate_time" onkeyup="check_correct_data(2 , this.value)" placeholder="@lang('messages.time')" name="average_time" id="average_time" required="required" /></th>
                                            <th><input type="text" class="form-control calculate_time" onkeyup="check_correct_data(3 , this.value)" placeholder="@lang('messages.time')" name="big_time" onkeyup="check" id="big_time" required="required" /></th>
                                        </tr>
                                        <tr>
                                            <th>Price&nbsp;</th>
                                            <th>	   <input type="number" class="form-control calculate_price" placeholder="@lang('messages.price')" onkeyup="check_correct_data()" name="small_price" id="small_price" required="required" min="1" max="1000"  /></th>
                                            <th>	   <input type="number" class="form-control calculate_price" placeholder="@lang('messages.price')" onkeyup="check_correct_data()" name="average_price" id="average_price" required="required" min="1" max="1000"  /></th>
                                            <th>	   <input type="number" class="form-control calculate_price" placeholder="@lang('messages.price')" onkeyup="check_correct_data()" name="big_price" id="big_price" required="required" min="1" max="1000"  /></th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!--<div class="row form-group">
                        <div class="col-sm-12">
                                <label>Service Average Timing <span class="text-danger">(in minute) *</span></label>
                                <input type="number" name="service_average_time" id="service_average_time" value="" class="form-control"  placeholder="Service Average Time" required="required" min="0" max="1000"/>
                                </div>
                            </div>
                        <div class="row form-group">
                        <div class="col-sm-12">
                                <label>Select Car size&nbsp;<span class="text-danger">*</span></label>
                                <select class="form-control" name="car_size" id="car_size">
                                    <option hidden="hidden">--Select--Car--Size--</option>
                                    <option value="1">Small</option>
                                    <option value="2">Average </option>
                                    <option value="3">Big</option>
                                </select>
                                </div>
                            </div> -->       
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-check form-check-inline">
                                <button type="submit" id="add_services_btn_copy" class="btn bg-blue ml-3">@lang('messages.Submit')<i class="icon-paperplane ml-2"></i></button>
                            </div>
                        </div>
                    </form>
                @else
                    <h2>First Complete your profile , and fill your workshop timing </h2>
                    <a href="{{ url('add_time_details') }}" class="btn btn-primary">Go >></a>
                @endif 
            </div>
			<div id="response_err"></div>
		</div>
		<div class="modal-footer"></div>
	</div>
</div>
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
            <a href="#" class="breadcrumb-item">Workshop  </a>
            <a href="#" class="breadcrumb-item">Add Services </a>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop
@push('scripts')
    <script>
	  function calculate_price(hourly_rate , time){
		   return hourly_rate * time; 
		}
	  function check_correct_data(type = null , time = null){
		  var hourly_rate = $('#hourly_rate').val();
		  $("#hourly_rate_err").html("");
		  if(hourly_rate != ""){
			 var sub_total_price = calculate_price(hourly_rate , time)
			  if(type == 1){
			    $("#small_price").val(sub_total_price);
				}
			  else if(type == 2){
				$("#average_price").val(sub_total_price);
				}
			   else if(type == 3){
			     $("#big_price").val(sub_total_price);
				}
			   $("#hourly_rate_err").html(" ");
			   $("#add_services_btn_copy").attr('disabled' , false);			
			 }
		  else{
            $("#hourly_rate_err").html("Please first enter the price service hourly rate .");    		$("#add_services_btn_copy").attr('disabled' , true);
			}	
	   }
      $(document).ready(function(e) {
		   $("#add_services_btn_copy").attr('disabled' , true);
		   /*Add Discount daily in all*/
		    $(document).on('change','#special_time_slot_type',function(){
		       time_slot_type = $(this).val();
			   if(time_slot_type == 1){
				   $('.special_slot').find('option[value="1"]').attr('selected','selected');
				 }
			   else{
				    $('.special_slot').find('option[value="1"]').attr('selected', false);
				 }	 
			});
		  /*End*/
         /*Post Add services script start*/
		  $(document).on('submit','#add_services_form',function(e){
			 e.preventDefault();
             category_id = $("#category_id").val();
			 hourly_rate = $("#hourly_rate").val();
			 
             var btn_html = $("#add_services_btn_copy").html();
			 $('#add_services_btn_copy').html('Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
			 dataArr = [];
			 small_car_size = {'car_size':1 , 'price':$("#small_price").val() , 'time':$("#small_time").val() , 'appointment':$("#max_appointment").val()} 
			
			 average_car_size = {'car_size':2 , 'price':$("#average_price").val() , 'time':$("#average_time").val(),'appointment':$("#max_appointment").val()} 
			
			 big_car_size = {'car_size':3 , 'price':$("#big_price").val() , 'time':$("#big_time").val() , 'appointment':$("#max_appointment").val()} 
			dataArr.push(small_car_size);
			dataArr.push(average_car_size);
			dataArr.push(big_car_size);
			 $.ajax({
					url: base_url+"/services/add_car_wash_services",
					type: "POST",        
					data: {category_id:category_id , about_services:$("#about_services").val(),hourly_rate:hourly_rate,dataArr:dataArr},
					complete: function(e , xhr , setting){
					    $('#add_services_btn_copy').html(btn_html).attr('disabled' , false);
					   if(e.status == 200){
						 	var parseJson = jQuery.parseJSON(e.responseText);
						    if(parseJson.status == 200){
						          $("#add_services_form")[0].reset();	
							      $('.close').click();
								  $("#msg_response_popup").modal('show');
                                  $("#msg_response").html(parseJson.msg); 
							  }
							 if(parseJson.status == 100){
                                  $("#response_err").html(parseJson.msg); 
							  }  
						 }
					   //console.log(e);	
					},
					error: function(xhr, error){
                       $('#add_services_btn_copy').html(btn_html).attr('disabled' , false);
                       $("#response_err").html('<div class="notice notice-danger"><strong>Wrong </strong>Something Wrong , please try again . !!! </div>');
                      }
				});
			
          });
		 /*End*/ 
		 $(document).on('change','#washing_category',function(){
	        category_id = $(this).val();
			 $('#big_time').val('');
			 $("#average_time").val('');
			 $("#small_time").val('');
			 $("#small_price").val('');
			 $("#average_price").val('');									    
			 $("#big_price").val('');
			if(category_id != 0){
			     $.ajax({
					url: base_url+"/commonAjax/getTimePrice",
					type: "GET",        
					data: {category_id:category_id},
					complete: function(e , xhr , setting){
						var parseJson = jQuery.parseJSON(e.responseText);
						if(e.status == 200){
						  
							   if(parseJson.status == 200){
								     $('#big_time').val(parseJson.response.big_time);
									 $("#average_time").val(parseJson.response.average_time);
									 $("#small_time").val(parseJson.response.small_time);
									 $("#small_price").val(parseJson.response.small_price);
									 $("#average_price").val(parseJson.response.average_price);									 $("#big_price").val(parseJson.response.big_price);
								  }
							   //console.log(parse_json.response);
						  }
					}
				});
			  }
		 });  
      });
    </script>
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" />
    <script>
        $(document).ready( function () {
            $('#myTable').DataTable();
        } );
    </script>
    <script src="{{ url('validateJS/services.js') }}"></script> 
    <script src="{{ url('validateJS/service_slot.js') }}"></script> 
    <script src="{{ url('validateJS/vendor.js') }}"></script>
    <script src="{{ url('validateJS/date/jquery-ui.css') }}"></script>
    <script src="{{ url('validateJS/date/jquery-ui.js') }}"></script>
    <script src="{{ url('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ url('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>
    
	<script>
        $(document).ready(function(e){
            $('[data-toggle="tooltip"]').tooltip(); 
            $(".add_btn").click(function(){
                let $clone = $(this).closest('.row').clone();     
                $(this).closest('.row').after($clone);
                $(this).closest('.row').next().find('.add_btn').remove();
                $(this).closest('.row').next().find('.col-sm-4:last-child .form-group').html('<button type="button" class="btn btn-danger remove_add_fields"><i class="icon-x"></i>&nbsp;Remove</button>');
                    $('body').find('#start_time, #end_time').datetimepicker({
                        format: 'HH:mm'
                    });
                        
            })

            $(document).on('click', '.remove_add_fields', function(){
                $(this).closest('.row').remove();
            })
            $(".service_days input[type='checkbox']").on('click', function(){
                // $(this).closest('.add_fields').remove();
                if($(this).is(':checked')){
                    // alert();
                    $(this).closest('.d-flex').next('.add_fields').slideDown();
                    console.log( $(this).closest('.d-flex').next('.add_fields'));
                }
                else{
                    $(this).closest('.d-flex').next('.add_fields').slideUp();
                }
            })        
        })
    </script>
@endpush

