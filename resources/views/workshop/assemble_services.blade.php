@extends('layouts.master_layouts')
@section('content')
<div class="content">
    
    <!--Assemble Services Script Strat-->
    <div class="card">
        <div class="card-header bg-light header-elements-inline">
            <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;Assemble Service List</h6>
        </div>
        <div class="card-body">
            <div class="row" style="margin-bottom:10px;">
                <div class="col-sm-12">
                    <a href='#' class="btn btn-primary" id="add_assemble_services" style="color:white;">Add New Services&nbsp;<span class="glyphicon glyphicon-plus"></span></a>
                </div>
            </div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>SN.</th>
                        <th>Product Name</th>
                        <th>About Services</th>
                        <th>Average Timing</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($listed_services as $service)
                    @php $enc_type_s_id = encrypt($service->id); @endphp
                    @php $about_services = str_limit($service->about_services, 17); @endphp
                    <tr>
                            <td>{{ $loop->iteration }}</th>
                            <th>{{ $service->listino ?? "N/A" }}</th>
                            <th>{{ $about_services ?? "N/A" }}</th>
                            <th>{{ $service->service_average_time ?? "N/A" }}</th>
                            <td>
                                <a href="#" data-toggle="tooltip" data-placement="top" title="Delete Services" data-serviceid = "<?php echo $service->id; ?>" class="btn btn-danger btn-sm remove_services"><span class="glyphicon glyphicon-trash"></span></a>&nbsp;&nbsp;&nbsp;
                                <a href="#" data-toggle="tooltip" data-placement="top" title="Manage Time Slot" data-serviceid = "{{ $service->id }}" class="btn btn-primary btn-sm manage_assemble_time_slot"><span class="fa fa-clock-o"></span></a>&nbsp;
                                <a href='{{ url("workshop/edit_assemble_services/$enc_type_s_id") }}' data-toggle="tooltip" data-placement="top" title="View And Edit" class="btn btn-primary"><span class="fa fa-eye"></span></a>
                            </th>
                        </tr>
                @empty
                    <tr>
                            <td colspan="5">No Group Available !!!</th>
                        
                        </tr>
                @endforelse
                
                </tbody>
        </table>
        <div class="row" style="margin-top:10px;">
          <div class="col-sm-12">
             {{ $listed_services->links() }}
          </div>
        </div>
        </div>
    </div>
    
    <!--End-->
    <!-- Page length options -->
    
    <!-- /page length options -->
</div>
<div class="modal" id="add_assemble_service">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i>
				</button>
				<h4 class="modal-title" id="myModalLabel">Add Assemble Services </h4>
				<hr />
			</div>
			<!-- Modal body -->
            <div class="card-body">
            <form id="assemble_products_service_form" autocomplete="off">
                @csrf
                <div class="row form-group">
                    <div class="col-sm-12">
                        <label>@lang('messages.SelectMakers') </label>
                        <select class="form-control" name="car_makers" id="car_makers">
                            <option>--Select-- Makers--Name--</option>
                            @foreach($cars__makers_category as $makers)
                            <option value="{{ $makers->idMarca }}">{{ $makers->Marca }}</option>
                            @endforeach 
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>@lang('messages.SelectModel')</label>
                    <select class="form-control" name="car_models" id="car_models">
                        <option>--First--Select--Makers--Name--</option>
                    </select>                                
                </div>
                <div class="form-group">
                <label>@lang('messages.SelectVersion')</label>
                    <select class="form-control car_version_group" name="car_version" id="car_version">
                        <option>--First--Select--Model--Name--</option>
                    </select>                                
                </div>
                <div class="form-group">
                    <label>@lang('messages.SelectGroupItem')</label>
                    <select class="form-control" name="car_group_version" id="group_item" data-action="get_and_save_products_item">
                        <option value="0">@lang('messages.firstSelectVersion')</option>
                    </select> 
                </div>
                <div class="form-group">
                    <label>Select Item </label>
                    <select class="form-control" name="item_id" id="item_id" data-action="save_get_products">
                        <option value="0">@lang('messages.firstSelectGroupItem')</option>
                    </select>
                </div>
                <div class="row form-group">
                    <div class="col-sm-12">
                        <label>@lang('messages.SelectProduct') </label>
                        <select class="form-control" name="inventory_product" id="inventory_product">
                            <option value="0">--First--Select--Item--</option>
                        </select>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-12">
                      <label>Service Average Timing <span class="text-danger">(in minute) *</span></label>
                      <input type="number" name="service_average_time" id="service_average_time" value="" class="form-control"  placeholder="Service Average Time" required="required"/>
                    </div>
                </div>
                <div class="form-group">
                <label>@lang('messages.AboutServices')&nbsp;<span class="text-danger">*</span></label>
                <textarea type="text" class="form-control" placeholder="@lang('messages.AboutServices')" name="about_services" id="about_services" value="" required="required" onkeyup="checkaboutdata()"></textarea>
                <span id="title_err"></span>
            </div>
                <!-- @forelse($service_days as $pakages_days)
          <div class="day-row">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check form-check-inline service_days">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-control-styled weekly_days" name="week_days[]" value="{{ $pakages_days->common_weekly_days_id}}"   data-fouc onclick="check_rows_data(1)">
                            {{ $pakages_days->name }}
                        </label>
                    </div>
                </div>
                <div class="add_fields" style="display:none">
                    <div class="row">
                      <div class="col-sm-12 err_msg" id="date_err" ></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>@lang('messages.StartDate')&nbsp;<span class="text-danger">*</span></label>
                                    <input type="text"  class="form-control" id="start_time" name="start_time[]" placeholder="@lang('messages.StartDate')" onblur="check_rows_data(2)"  />
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>@lang('messages.EndDate')&nbsp;<span class="text-danger">*</span></label>
                                    <input type="text"  class="form-control" id="end_time" name="end_time[]" placeholder="@lang('messages.EndDate')" onblur="check_rows_data(3)" />
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>@lang('messages.Price')&nbsp;<span class="text-danger">*</span></label>
                                <input type="number" name="services_price[]" id="price"  placeholder="@lang('messages.Price')" onblur="check_rows_data(4)" class="form-control"/>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                            <label>@lang('messages.mxappointment')&nbsp;<span class="text-danger">*</span></label>
                                <input type="number" name="maximum_appointment[]" id="maximum_appointment"  class="form-control"  placeholder="Maximum Appointment" min="0" max="100" />
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group pt-4">
                                <button type="button" class="btn btn-success add_btn">
                                    <i class="icon-plus3"></i>&nbsp;Add More
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          @empty
          @endforelse -->
                <div id="response_coupon"></div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                        <button type="submit" class="btn btn-success"  id="add_assemble_services_btn">Save &nbsp;<i class="icon-paperplane ml-2"></i></button>
                        </div>
                    </div>
            </form>
        </div>
			<div id="response_about_pakages"></div>
		</div>
		<div class="modal-footer"></div>
	</div>
</div>

<div class="modal" id="manage_assble_time_slot">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i>
				</button>
				<h4 class="modal-title" id="myModalLabel">Add Time Slots </h4>
				<hr />
			</div>
			<!-- Modal body -->
			<form id="time_assemble_slots_form">
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
								<button type="submit" class="btn btn-success" id="add_asmble_time_slot">@lang('messages.Save') &nbsp;<i class="icon-paperplane ml-2"></i>
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
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="../vendors/index.html" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
            <a href="../vendors/internationalization_fallback.html" class="breadcrumb-item">Seller</a>
            <span class="breadcrumb-item active"> products</span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>   
</div>
@stop
@push('script')
<link href='{{ url("cdn/css/croppie.css") }}' />
<script src="{{ url('cdn/js/croppie.js') }}"></script>
@endpush

@push('scripts')
<script src="{{ asset('validateJS/admin.js') }}"></script>
<script src="{{ url('validateJS/products.js') }}"></script>
<script src="{{ url('validateJS/assemble_service.js') }}"></script>
<script src="{{ url('validateJS/assemble_products.js') }}"></script>
<script src="{{ url('validateJS/services.js') }}"></script>
<script>
    $(document).ready(function(e){
        $('[data-toggle="tooltip"]').tooltip(); 
        $(".add_btn").click(function(){
            let $clone = $(this).closest('.row').clone();     
            $(this).closest('.row').after($clone);
            $(this).closest('.row').next().find('.add_btn').remove();
            $(this).closest('.row').next().find('.col-sm-3:last-child .form-group').html('<button type="button" class="btn btn-danger remove_add_fields"><i class="icon-x"></i>&nbsp;Remove</button>')
                    
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