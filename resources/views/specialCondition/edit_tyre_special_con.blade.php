@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="card" id="add_maintenance_special_condition" >
    <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-plus"></i>&nbsp;Edit @lang('messages.AssembleServicesSpecialCon')</h6>
    </div>
        <div class="card-body">
        <form id="edit_special_condition_form_tyre" autocomplete="off">
            @csrf
             <input type="hidden" name="edit_id" id="special_condition_id" value="{{ $condition_detail->id }}">
            <div class="row">
                <div class="col-sm-12">
                    <label>Select Cars&nbsp;<span class="text-danger">*</span></label>
                </div>
            </div>
            <div class="row">
                      <div class="col-sm-6">
                        <div class="form-group">
                            <select class="form-control makers" name="car_makers" id="makers">
                                    <option value="0">--Select--Makers--Name--</option>
                                    <option value="1" <?= $condition_detail->makers == 1 ? 'selected' : '' ?>>All Cars</option>
                                    @foreach($cars__makers_category as $makers)
                                    <option value="@if(!empty($makers->idMarca)){{ $makers->idMarca }} @endif" <?=  $makers->idMarca == $condition_detail->makers ? 'selected' : '' ?>>@if(!empty($makers->Marca)){{ $makers->Marca }} @endif</option>
                                    @endforeach 
                            </select>                                
                        </div> 
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                            <input type="hidden" id="model_value" value="{{ $model_arr['model_value'] }}">
                            <select class="form-control models" name="car_models">
                            </select>                                
                        </div>
                      </div>
                   </div>
            <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                            <input type="hidden" id="version_value" value="{{ $version_arr['version_value'] }}">
                            <select class="form-control versions" id="version_id" name="car_version" data-action="get_n3_category">
                            </select>                                
                        </div> 
                      </div>
                   </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Select Tyre Size Group </label>
                        <select class="form-control" name="all_tyre_group" id="all_tyre_group">
                            <option value="0" <?php if($condition_detail->all_services == 1) echo "selected"; ?>>All Size</option>
                            @forelse($tyre_group as $category)
                            <option value="{{ $category->id }}"  <?php if($category->id == $condition_detail->category_id) echo "selected"; ?>>{{ $category->range_from." To ".$category->range_to }}</option>
                            @empty
                            @endforelse 
                        </select>
                    </div>   
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Vehicle Type&nbsp;<span class="text-danger">*</span></label>
                        <select class="form-control" name="vehicle_type" id="vehicle_type" required >
                            <option value="" hidden="hidden">--Select Option--</option>
                            <option value="all" <?php if($condition_detail->vehicle_type == "all") echo "Selected"; ?>>All Vehicle Type</option>   
                           <?php 
                                foreach($tyre_type as $t_type){
                                    $code = json_decode($t_type['code']);
                            ?>
                                    <option value="<?php if(!empty($t_type->id)) echo $t_type->id; ?>" <?= $t_type->id == $condition_detail->vehicle_type ? 'selected' : '' ?>>
                                        <?php if(!empty($t_type['name'])) echo $t_type['name']; ?>
                                    </option>
                            <?php
                                }
                            ?>
                           <!-- <option value="2"  <?php if($condition_detail->vehicle_type == 2) echo "Selected"; ?>>Car </option>   
                           <option value="3"  <?php if($condition_detail->vehicle_type == 3) echo "Selected"; ?>>Truck </option>     -->
                        </select>
                    </div>   
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Season Type&nbsp;<span class="text-danger">*</span></label>
                        <select class="form-control" name="season_type" id="season_type" required >
                            <!-- <option value="a" <?php if($condition_detail->season_type == "a") echo "Selected"; ?>>All Season</option>   
                            <option value="s" <?php if($condition_detail->season_type == "s") echo "Selected"; ?>>Summer tyre</option>   
                            <option value="w" <?php if($condition_detail->season_type == "w") echo "Selected"; ?>>Winter tyre</option>   
                            <option value="m" <?php if($condition_detail->season_type == "m") echo "Selected"; ?>>2-Wheel / Quad tyre</option>  
                            <option value="g" <?php if($condition_detail->season_type == "g") echo "Selected"; ?>>All-season tyre</option>
                            <option value="o" <?php if($condition_detail->season_type == "o") echo "Selected"; ?>>Off-road tyre</option>  
                            <option value="l" <?php if($condition_detail->season_type == "l") echo "Selected"; ?>>Truck tyre</option>  --> 
                            <?php 
                                foreach($season_tyre_type as $t_type){
                            ?>
                                    <option value="<?php if(!empty($t_type['code2'])) echo $t_type['code2']; ?>" <?= $t_type['code2'] == $condition_detail->season_type ? 'selected' : '' ?>>
                                        <?php if(!empty($t_type['name'])) echo $t_type['name']; ?>
                                    </option>
                            <?php
                                }
                            ?>
                        </select>
                    </div>   
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Operation Type&nbsp;<span class="text-danger">*</span></label>
                        <select class="form-control" name="operation_type" id="operation_type" required >
                            <option value="1" <?php if($condition_detail->operation_type == 1) echo "Selected"; ?>>Special Price</option>   
                            <option value="2" <?php if($condition_detail->operation_type == 2) echo "Selected"; ?>>Do not perform operation</option>   
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
                    <input type="text" class="form-control" id="start_time" name="start_time" placeholder="@lang('messages.StartTime')" required value="<?php if(!empty($condition_detail->start_hour)) echo sHelper::change_time_format_2($condition_detail->start_hour); ?>" />
                </div>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="end_time" name="end_time" placeholder="@lang('messages.EndTime')" required value="<?php if(!empty($condition_detail->end_hour)) echo sHelper::change_time_format_2($condition_detail->end_hour); ?>" />
                </div>
            </div>
            <div class="row rowPadding">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Select discount type </label>
                        <select class="form-control" name="discount_type" id="discount_type" >
                            <option value="">--Select Option--</option>
                            <option value="1" <?php if($condition_detail->discount_type == 1) echo "Selected"; ?>>Price per hour </option>   
                            <option value="2" <?php if($condition_detail->discount_type == 1)  echo "Selected"; ?>>Discount Percentage </option> 
                        </select>
                     
                    </div>   
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Amount / Percentage</label>
                        <input type="text" class="form-control" id="amount" name="amount" placeholder="@lang('messages.Amount')" value="<?php if(!empty($condition_detail->amount_percentage)) echo $condition_detail->amount_percentage; ?>" />
                    </div>
                </div>   
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Maximum Appointment at the same time (default)&nbsp;<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="maximum_appointment" name="maximum_appointment" placeholder="@lang('messages.maxAppointment')" value="<?php if(!empty($condition_detail->max_appointement)) echo $condition_detail->max_appointement; ?>" required />
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
                        <input type="text" class="form-control datepicker" name="start_date" placeholder="@lang('messages.StartDate')" id="start_date" value="<?php if(!empty($condition_detail->start_date)) echo $condition_detail->start_date; ?>" readonly="readonly"  required="required"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Select type&nbsp;<span class="text-danger">*</span></label>
                        <select class="form-control" name="repeat_type" id="repeat_type">
                            <option value="0" {{ $condition_detail->select_type == 0 ? 'selected' : ''}}>Select type</option>   
                            <option value="1" {{ $condition_detail->select_type == 1 ? 'selected' : ''}}>Every Day</option>   
                            <option value="2" {{ $condition_detail->select_type == 2 ? 'selected' : ''}}>Every Week</option>   
                            <option value="3" {{ $condition_detail->select_type == 3 ? 'selected' : ''}}>Every Month</option>   
                            <option value="4" {{ $condition_detail->select_type == 4 ? 'selected' : ''}}>Every Year</option>   
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
                        <input type="text" class="form-control datepicker" id="expiry_date" name="expiry_date" placeholder="@lang('messages.ExpiryDate')" value="<?php if(!empty($condition_detail->expiry_date)) echo $condition_detail->expiry_date; ?>"  readonly="readonly" required="required"/>
                    </div>
                </div>
            </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" id="special_condition_btn" class="btn bg-blue ml-3">Submit <i class="icon-paperplane ml-2"></i></button>
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
                <span class="breadcrumb-item active">Assemble Services  </span>
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
<script src="{{ asset('validateJS/special_conditions_cars.js') }}"></script>
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