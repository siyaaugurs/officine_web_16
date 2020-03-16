@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="card" id="add_revision_special_condition" >
    <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-plus"></i>&nbsp;Edit Request Quotes Special Condition</h6>
    </div>
    <div class="card-body">
        <form id="edit_request_quotes_special_condition_form" autocomplete="off">
            @csrf
            <input type="hidden" name="special_condition_id" id="special_condition_id" value="{{ $request_quotes_details->id }}">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Select Services </label>
                        <select class="form-control" name="category_id" id="category_id" required>
                            <option value="0" <?= $request_quotes_details->all_services == 1 ? 'selected' : '' ?>>All Services</option>
                            @forelse($main_category as $category)
                                <option value="{{ $category->id }}" {{ $category->id == $request_quotes_details->category_id ? 'selected' : ''}}>{{ $category->main_cat_name }}</option>
                            @empty
                            @endforelse
                        </select>
                        <span id="title_err"></span>
                    </div>   
                </div>  
            </div>
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
                       <option value="1" {{ $request_quotes_details->makers == 1  ? 'selected' : ''}}>All Cars</option>
                         @foreach($cars__makers_category as $makers)
                           <option value="@if(!empty($makers->idMarca)){{ $makers->idMarca }} @endif" {{ $makers->idMarca == $request_quotes_details->makers ? 'selected' : ''}}>@if(!empty($makers->Marca)){{ $makers->Marca }} @endif</option>
                         @endforeach 
                    </select>                                
                </div> 
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                    <input type="hidden" id="model_value" value="{{ $request_quotes_details->models }}">
                    <select class="form-control models" name="car_models">
                    </select>                                
                </div>
              </div>
                   </div>
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                    <input type="hidden" id="version_value" value="{{ $request_quotes_details->versions }}">
                    <select class="form-control versions" id="version_id" name="car_version" data-action="get_n3_category">
                    </select>                                
                </div> 
              </div>
           </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Operation Type&nbsp;<span class="text-danger">*</span></label>
                        <select class="form-control" name="operation_type" id="operation_type" >
                            <option value="1" {{ $request_quotes_details->operation_type == 1 ? 'selected' : ''}}>Special Price</option>   
                            <option value="2" {{ $request_quotes_details->operation_type == 2 ? 'selected' : ''}}>Do not perform operation</option>   
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
                    <input type="text" class="form-control" id="start_time" name="start_time" placeholder="@lang('messages.StartTime')" required  value="{{ $request_quotes_details->start_hour }}"/>
                </div>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="end_time" name="end_time" placeholder="@lang('messages.EndTime')" required value="{{ $request_quotes_details->end_hour }}" />
                </div>
            </div>
            <div class="row rowPadding">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Select discount type </label>
                        <select class="form-control" name="discount_type" id="discount_type">
                            <option value="">--Select Option--</option>
                            <option value="1" {{ $request_quotes_details->discount_type == 1 ? 'selected' : ''}}>Price per hour </option>   
                            <option value="2" {{ $request_quotes_details->discount_type == 2 ? 'selected' : ''}}>Discount Percentage </option> 
                        </select>
                        <span id="title_err"></span>
                    </div>   
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Amount / Percentage</label>
                        <input type="text" class="form-control" id="amount" name="amount" placeholder="@lang('messages.Amount')" value="{{ $request_quotes_details->amount_percentage }}" />
                    </div>
                </div>   
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Maximum Appointment at the same time (default)&nbsp;<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="maximum_appointment" name="maximum_appointment" placeholder="@lang('messages.maxAppointment')" required value="{{ $request_quotes_details->max_appointement }}" />
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
                        <input type="text" class="form-control datepicker" name="start_date" placeholder="@lang('messages.StartDate')" id="start_date" value="{{ $request_quotes_details->start_date }}" readonly="readonly"  required/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Select type&nbsp;<span class="text-danger">*</span></label>
                        <select class="form-control" name="repeat_type" id="repeat_type">
                            <option value="0" {{ $request_quotes_details->select_type == 0 ? 'selected' : ''}}>Select type</option>   
                            <option value="1" {{ $request_quotes_details->select_type == 1 ? 'selected' : ''}}>Every Day</option>   
                            <option value="2" {{ $request_quotes_details->select_type == 2 ? 'selected' : ''}}>Every Week</option>   
                            <option value="3" {{ $request_quotes_details->select_type == 3 ? 'selected' : ''}}>Every Month</option>   
                            <option value="4" {{ $request_quotes_details->select_type == 4 ? 'selected' : ''}}>Every Year</option>   
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
                        <input type="text" class="form-control datepicker" id="expiry_date" name="expiry_date" placeholder="@lang('messages.ExpiryDate')" value="{{ $request_quotes_details->expiry_date }}"  readonly="readonly" required/>
                    </div>
                </div>
            </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" id="edit_request_special_condition_btn" class="btn bg-blue ml-3">Submit <i class="icon-paperplane ml-2"></i></button>
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
                <span class="breadcrumb-item active">Car Revision  </span>
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
 <script src="{{ asset('validateJS/special_conditions_cars.js') }}"></script>
<script src="{{ url('validateJS/vendors.js') }}"></script>
<script src="{{ url('validateJS/special_condition.js') }}"></script>
<script src="{{ url('validateJS/date/jquery-ui.css') }}"></script>
<script src="{{ url('validateJS/date/jquery-ui.js') }}"></script>
<script>
$(document).ready(function(e) {
    var car_maker = $('#makers').val();
    var model_id = $('#model_value').val();
    var version_id = $('#version_value').val();
    var type = 2;
    get_modals(car_maker, model_id, type);
    get_versions_details(model_id, version_id,type);
    var type_ = $("#repeat_type").val();
    if(type_ == 2) {
        edit_id = $("#special_condition_id").val();
        if(edit_id != "") {
            $.ajax({
                url:base_url+"/spacial_condition_ajax/get_selected_day",
                method:"GET",
                data:{edit_id:edit_id},
                success:function(data){
                    var parseJson = jQuery.parseJSON(data);
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

    var type = $("#repeat_type").val(); 
    if(type == 2){
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
});
</script>
@endpush