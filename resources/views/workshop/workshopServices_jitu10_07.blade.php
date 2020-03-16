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
                <input type="hidden" name="service_id" id="service_id" value="{{ $services_details->id }}" readonly="readonly" />
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
<div class="card">
    <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;Service List</h6>
    </div>
	<div class="card-body" id="user_data_body">
	    <div class="row" style="margin-bottom:10px;">
            <div class="col-sm-12">
                <a href='#' class="btn btn-primary" id="add_services" style="color:white;">Add New Services&nbsp;<span class="glyphicon glyphicon-plus"></span></a>
            </div>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>SN.</th>
                    <th>Services</th>
                    <th>Car Size</th>
                    <th>Service Average time <span class="text-danget" style="color:#F00;">(in minute)</span></th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            @forelse($listed_services_list as $services)
                @php $enc_type_s_id = encrypt($services->id); @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $services->category_name }}</td>
                    <td>@if(!empty($services->car_size)){{ sHelper::get_car_size($services->car_size) }} @endif</td>
                    <td>{{ $services->service_average_time }} Minute</td>
                    <td>
                        <a href="#" data-serviceid = "<?php echo $services->id; ?>" class="btn btn-danger remove_services"><span class="glyphicon glyphicon-trash"></span></a>&nbsp;
                        <a href='{{ url("vendor/view_services/$enc_type_s_id") }}' class="btn btn-primary"><span class="fa fa-eye"></span></a>
                        <a href='#' data-toggle="tooltip" data-placement="top" title="Manage Time Slot" class="btn btn-primary manage_time_slot" data-serviceid="<?php echo $services->id; ?>"><span class="fa fa-clock-o"></span></a>
                    </td>
                </tr>
            @empty
            
            @endforelse
            </tbody>
        </table>                     
    </div>
</div>
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
                <input type="hidden" value="" id="slot_service_id">
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
										<label>@lang('messages.Price')&nbsp;<span class="text-danger">*</span>
										</label>
										<input type="number" name="services_price[]" id="price" placeholder="@lang('messages.Price')" onblur="check_rows_data(4)" class="form-control" />
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
										<label>@lang('messages.mxappointment')&nbsp;<span class="text-danger">*</span>
										</label>
										<input type="number" name="maximum_appointment[]" id="maximum_appointment" class="form-control" placeholder="Maximum Appointment" />
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
										<label>Discount Type&nbsp;<span class="text-danger">*</span>
										</label>
										<select name="discount_type[]" id="discount_type" class="form-control">
                                            <option value="" hidden="hidden">--Select Type--</option>
                                            <option value="1">In %</option>
                                            <option value="2">In Rs.</option>
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
                            <input type="hidden" name="service_id" id="service_id" value="{{ $services_details->id }}" readonly="readonly" />
                            <label>@lang('messages.AboutServices')&nbsp;<span class="text-danger">*</span></label>
                            <textarea type="text" class="form-control" placeholder="@lang('messages.AboutServices')" name="about_services" id="about_services" value="" required="required" onkeyup="checkaboutdata()"></textarea>
                            <span id="title_err"></span>
                        </div>
                        <!--<div class="row form-group">
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
                                            <th>Price</th>
                                            <th>	   <input type="text" class="form-control" placeholder="@lang('messages.price')" name="small_price" required="required"  /></th>
                                            <th>	   <input type="text" class="form-control" placeholder="@lang('messages.price')" name="average_price" required="required"  /></th>
                                            <th>	   <input type="text" class="form-control" placeholder="@lang('messages.price')" name="big_price" required="required"  /></th>
                                        </tr>
                                        <tr>
                                            <th>Time</th>
                                            <th>	   <input type="text" class="form-control" placeholder="@lang('messages.time')" name="small_time" required="required"  /></th>
                                            <th>	   <input type="text" class="form-control" placeholder="@lang('messages.time')" name="average_time" required="required"  /></th>
                                            <th>	   <input type="text" class="form-control" placeholder="@lang('messages.time')" name="big_time" required="required"  /></th>
                                        </tr> 
                                    </tbody>
                                </table>
                            </div>
                        </div>-->
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
            <a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
            <a href="#" class="breadcrumb-item">Workshop  </a>
            <a href="#" class="breadcrumb-item">Add Services </a>
            <span class="breadcrumb-item active">{{ $services_details->category_name }}</span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop
@push('scripts')
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
                $(this).closest('.row').next().find('.col-sm-2:last-child .form-group').html('<button type="button" class="btn btn-danger remove_add_fields"><i class="icon-x"></i>&nbsp;Remove</button>');
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

