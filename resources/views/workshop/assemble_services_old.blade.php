@extends('layouts.master_layouts')
@section('content')
<div class="content">
    <!--Assemble Services Script Strat-->
    <div class="card">
        <div class="card-body">
            <table class="table">
        <thead>
          <tr>
                    <th>SN.</th>
                    <th>Makers</th>
                    <th>Model</th>
                  
                    <th>Product Name</th>
                    <th>Average Timing</th>
                    <th>Action</th>
                </tr>
        </thead>
        <tbody>
           @forelse($listed_services as $service)
             @php
              $maker_name = kRomedaHelper::get_maker_name($service->car_makers_name);
              $model_name = kRomedaHelper::get_model_name($service->car_makers_name , $service->models_name);
            
             @endphp
             @php $enc_type_s_id = encrypt($service->id); @endphp
             <tr>
                    <td>{{ $loop->iteration }}</th>
                    <th><?php echo $maker_name->Marca; ?></th>
                    <th><?php echo $model_name->Modello; ?></th>
                  
                    <th><?php echo $service->products_name; ?></th>
                    <th><?php echo $service->service_average_time; ?></th>
                    <td>
                        <a href="#" data-serviceid = "<?php echo $service->id; ?>" class="btn btn-danger remove_services"><span class="glyphicon glyphicon-trash"></span></a>&nbsp;
                        <!--<a href='{{ url("vendor/view_assemble_services/$enc_type_s_id") }}' class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span></a>-->
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
    <div class="card">
        <div class="card-body">
            <form id="assemble_products_service_form" autocomplete="off">
                @csrf
                <div class="row form-group">
                    <div class="col-sm-12">
                        <label>Select  Product </label>
                        <select class="form-control multiselect" name="products_id"  id="products_id">
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->products_name }}</option>
                            @endforeach
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
                @forelse($service_days as $pakages_days)
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
                                <input type="number" name="services_price[]" id="price"  placeholder="@lang('messages.Price')" onblur="check_rows_data(4)" class="form-control" min="0" max="66600" />
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
          @endforelse
                <div id="response_coupon"></div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                        <button type="submit" class="btn btn-success"  id="add_services_btn">Save &nbsp;<i class="icon-paperplane ml-2"></i></button>
                        </div>
                    </div>
            </form>
        </div>
    </div>
    <!-- /page length options -->
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
<script src="{{ url('validateJS/assemble_service.js') }}"></script>
<script src="{{ asset('validateJS/admin.js') }}"></script>
<script src="{{ url('validateJS/services.js') }}"></script>
<script>
    $(document).ready(function(e){
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