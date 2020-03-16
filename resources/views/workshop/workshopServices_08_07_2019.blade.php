@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="card">    
<div class="card-header bg-light header-elements-inline">
     <h6 class="card-title">Add New Services <a class="list-icons-plus-1" data-action="collapse"></a></h6>
          <div class="header-elements">
            <div class="list-icons">
              <a class="list-icons-item" data-action="remove"></a>
            </div>
          </div>
        </div>      
@if(Session::has('msg'))
            {!!  Session::get("msg") !!}
            @endif
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
            @forelse($service_days as $weekly_days)
            <div class="day-row">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check form-check-inline service_days">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-control-styled weekly_days" name="week_days[]" value="{{$weekly_days->common_weekly_days_id}}"   data-fouc onclick="check_rows_data(1)">
                            {{ $weekly_days->name }}
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
                                <label>@lang('messages.StartTime')&nbsp;<span class="text-danger">*</span></label>
                                    <input type="text"  class="form-control" id="start_time" name="start_time[]" placeholder="@lang('messages.StartTime')" onblur="check_rows_data(2)"  />
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>@lang('messages.EndTime')&nbsp;<span class="text-danger">*</span></label>
                                    <input type="text"  class="form-control" id="end_time" name="end_time[]" placeholder="@lang('messages.EndTime')" onblur="check_rows_data(3)" />
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>@lang('messages.Price')&nbsp;<span class="text-danger">*</span></label>
                                <input type="number" name="services_price[]" id="price"  placeholder="@lang('messages.Price')" onblur="check_rows_data(4)" class="form-control" min="0" max="10000" />
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                            <label>@lang('messages.mxappointment')&nbsp;<span class="text-danger">*</span></label>
                                <input type="number" name="maximum_appointment[]" id="maximum_appointment"  class="form-control"  placeholder="@lang('messages.mxappointment')" min="0" max="100" />
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
            @endforelse
            <div class="d-flex justify-content-between align-items-center">
                <div class="form-check form-check-inline">
                    <button type="submit" id="add_services_btn" class="btn bg-blue ml-3">@lang('messages.Submit')<i class="icon-paperplane ml-2"></i></button>
                </div>
            </div>
        </form>
       @else
         <h2>First Complete your profile , and fill your workshop timing </h2>
         <a href="{{ url('add_time_details') }}" class="btn btn-primary">Go >></a>
       @endif 
    </div>
</div>
<div class="card">
	<div class="card-body" id="user_data_body">
    <table class="table" id="myTable">
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
                <a href='{{ url("vendor/view_services/$enc_type_s_id") }}' class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span></a></td>
            </tr>
          @empty
          
          @endforelse
        </tbody>
        <tfoot>
          <tr>
                    <th>SN.</th>
                    <th>Services</th>
                    <th>Car Size</th>
                    <th>Service Average time</th>
                    <th>Action</th>
                </tr>
        </tfoot>
        </table>                     
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
  <script src="{{ url('validateJS/vendor.js') }}"></script>
  <script src="{{ url('validateJS/date/jquery-ui.css') }}"></script>
  <script src="{{ url('validateJS/date/jquery-ui.js') }}"></script>
   <script src="{{ url('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
  <script src="{{ url('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>
  <script>
    $(document).ready(function(e){
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

