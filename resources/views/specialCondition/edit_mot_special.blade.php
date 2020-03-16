@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="card" id="add_maintenance_special_condition" >
    <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-plus"></i>&nbsp;Edit MOT Special Conditions</h6>
    </div>
	<div class="card-body">
        <form id="edit_mot_special_condition_form" autocomplete="off">
            @csrf
            <input type="hidden" name="special_condition_id" id="special_condition_id" value="{{ $p1 }}">
            <div class="row">
                <div class="col-sm-12">
                    <label>Select Cars&nbsp;<span class="text-danger">*</span></label>
                </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                    <select class="form-control makers" name="car_makers" id="makers">
                       <option value="0" hidden="hidden">@lang('messages.selectMaker')</option>
                         @foreach($cars__makers_category as $makers)
                           <option value="@if(!empty($makers->idMarca)){{ $makers->idMarca }} @endif" <?= $makers->idMarca == $mot_details->makers ? 'selected' : '' ?>>@if(!empty($makers->Marca)){{ $makers->Marca }} @endif</option>
                         @endforeach 
                    </select>                                
                </div> 
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                    <input type="hidden" id="model_value" value="{{ $mot_details->models }}">
                    <select class="form-control models" name="car_models"></select>                                
                </div>
              </div>
                   </div>
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                    <input type="hidden" id="version_value" value="{{ $mot_details->versions }}">
                    <select class="form-control versions" id="version_id" name="car_version" data-action="save_mot_service"></select>                                 
                </div> 
              </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Select Services </label>
                        <input type="hidden" id="category_value" value="{{ $mot_details->all_services == '0' ? $mot_details->category_id : 'all' }}">
                        <select class="form-control" name="category_id" id="category_id">
                            <option value="" hidden="hidden">--Select Car Version First--</option>
                        </select>
                        <span id="title_err"></span>
                    </div>   
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Operation Type&nbsp;<span class="text-danger">*</span></label>
                        <select class="form-control" name="operation_type" id="operation_type" required >
                            <option value="1" <?= $mot_details->operation_type == 1 ? 'selected' : '' ?>>Special Price</option>   
                            <option value="2" <?= $mot_details->operation_type == 2 ? 'selected' : '' ?>>Do not perform operation</option>   
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
                    <input type="text" class="form-control" id="start_time" name="start_time" placeholder="@lang('messages.StartTime')" required value="{{ $mot_details->start_hour }}" />
                </div>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="end_time" name="end_time" placeholder="@lang('messages.EndTime')" required value="{{ $mot_details->end_hour }}" />
                </div>
            </div>
            <div class="row rowPadding">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Select discount type </label>
                        <select class="form-control" name="discount_type" id="discount_type">
                            <option value="">--Select Option--</option>
                            <option value="1" <?= $mot_details->discount_type == 1 ? 'selected' : '' ?>>Price per hour </option>   
                            <option value="2" <?= $mot_details->discount_type == 2 ? 'selected' : '' ?>>Discount Percentage </option> 
                        </select>
                        <span id="title_err"></span>
                    </div>   
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Amount / Percentage</label>
                        <input type="text" class="form-control" id="amount" name="amount" placeholder="@lang('messages.Amount')" value="{{ $mot_details->amount_percentage }}" />
                    </div>
                </div>   
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Maximum Appointment at the same time (default)&nbsp;<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="maximum_appointment" name="maximum_appointment" placeholder="@lang('messages.maxAppointment')" required value="{{ $mot_details->max_appointement }}" />
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
                        <input type="text" class="form-control datepicker" name="start_date" placeholder="@lang('messages.StartDate')" id="start_date" value="{{ $mot_details->start_date }}" readonly="readonly"  required/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Select type&nbsp;<span class="text-danger">*</span></label>
                        <select class="form-control" name="repeat_type" id="repeat_type">
                            <option value="0" {{ $mot_details->select_type == 0 ? 'selected' : ''}}>Select type</option>   
                            <option value="1" {{ $mot_details->select_type == 1 ? 'selected' : ''}}>Every Day</option>   
                            <option value="2" {{ $mot_details->select_type == 2 ? 'selected' : ''}}>Every Week</option>   
                            <option value="3" {{ $mot_details->select_type == 3 ? 'selected' : ''}}>Every Month</option>   
                            <option value="4" {{ $mot_details->select_type == 4 ? 'selected' : ''}}>Every Year</option>   
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
                        <input type="text" class="form-control datepicker" id="expiry_date" name="expiry_date" placeholder="@lang('messages.ExpiryDate')" value="{{ $mot_details->expiry_date }}"  readonly="readonly" required/>
                    </div>
                </div>
            </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" id="edit_mot_special_condition_btn" class="btn bg-blue ml-3">Submit <i class="icon-paperplane ml-2"></i></button>
                    </div>
                </div>
        </form>
	</div>
</div>
@endsection
@section('breadcrum')
	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="JavaScript:Void(0);" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
				<span class="breadcrumb-item active">MOT Services  </span>
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
<script src="{{ asset('validateJS/products_groups.js') }}"></script>
<script src="{{ url('validateJS/special_condition_mot.js') }}"></script>
<script src="{{ url('validateJS/special_condition.js') }}"></script>
<script src="{{ url('validateJS/date/jquery-ui.css') }}"></script>
<script src="{{ url('validateJS/date/jquery-ui.js') }}"></script>
<!-- <script src="{{ url('validateJS/mot_service.js') }}"></script> -->
<script>
function save_mot_services(version_id, category_id, type){
    $("#preloader").show();  
    var language = $('html').attr('lang');
    $.ajax({
        url:base_url+"/mot_services/get_mot_services",
        method:"GET",
        data:{version_id:version_id , language:language},
        complete:function(e, xhr, settings){
            $("#preloader").hide();  
            if(e.status == 200){
                var parseJson = jQuery.parseJSON(e.responseText);  
                var html_content = '';
                html_content += '<option value="" hidden="hidden">--Select--Service--Schedule--</option>';
                if(type == 2 && category_id == "all") {
                    html_content += '<option value="all" selected="selected">All Services</option>';
                } else {
                    html_content += '<option value="all">All Services</option>';
                }
                if(parseJson.status == 200){
                    $.each(parseJson.response , function(index , value){
                       html_content += '<option value="'+value.id+'">'+value.interval_description_for_kms+'</option>';
                    });	 
                    if (type == 2 && category_id != "" && category_id != "all") {
                        $("#category_id").html(html_content).find("option[value='" + category_id + "']").attr('selected', 'selected');
                        $("#preloader").hide();
                    } else {
                        $("#preloader").hide();
                        $("#category_id").html(html_content);
                    }
                }
                if(parseJson.status == 404){
                    $("#category_id").append($('<option>' ,{value:0}).text('--No--Record--Found--')); 
                }  
            }
        },
        error: function(xhr, error){
            $("#service_shedule").append($('<option>' ,{value:0}).text('--No--Record--Found--')); 
            $("#preloader").hide();
        }
    });
}
$(document).ready(function(e) {
    var car_maker = $('#makers').val();
    var model_id = $('#model_value').val();
    var version_id = $('#version_value').val();
    var cat_id = $('#category_value').val();
    var type = 2;
    get_modals(car_maker, model_id, type);
    get_versions_details(model_id, version_id,type);
    save_mot_services(version_id, cat_id, type)
    var repeat_type = $("#repeat_type").val();
    if(repeat_type == 2) {
        $("#preloader").show();
        edit_id = $("#special_condition_id").val();
        if(edit_id != "") {
            $.ajax({
                url:base_url+"/spacial_condition_ajax/get_selected_day",
                method:"GET",
                data:{edit_id:edit_id},
                success:function(data){
                    var parseJson = jQuery.parseJSON(data);
                    $("#preloader").hide();
                    if(parseJson.status == 200){
                        $('#').find('.days').val();
                        var days_id_arr = [];
                        $.each(parseJson.response , function(index ,value){
                            $('#days_all_row').find('input:checkbox[value='+ value.days_id +']').prop('checked' ,true);
                        });
                    }
                }
            });
        }
    }

    // var type = $("#repeat_type").val(); 
    if(repeat_type == 2){
        $("#days_all_row").show();
    } else {
        $("#days_all_row").hide();
        $(".days").prop('checked',false);
    }
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
    $(document).on('change','#version_id',function(e){
        action = $(this).data('action');
        version_id = $(this).val();
        var category_id = "";
        if(action == "save_mot_service"){
            save_mot_services(version_id,  category_id, type=1);
        }	
    });  
});
</script>
@endpush