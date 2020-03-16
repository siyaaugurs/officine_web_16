@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
@if(session::has('msg'))
  {!! Session::get('msg') !!}
@endif
<style> .container{ padding:15px;} </style>
<div class="card">
<div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;@lang('messages.WreckerServiceList')</h6>
       <a href='#' class="btn btn-primary" id="add_new_sos_category" style="color:white;">Add New Services &nbsp;<span class="glyphicon glyphicon-plus"></span></a>
        </div>
	<div class="card-body" id="car_maintinance_ColWrap" style="overflow:auto;">
        <table class="table">
            <thead>
                <tr>
                    <th>@lang('messages.SN')</th>
                    <th>@lang('messages.Image')</th>
                    <th>@lang('messages.ServicesName')</th>
                    <th>@lang('messages.Description')</th>
                    <th>@lang('messages.TypeOfWeight')&nbsp;(1-2000)</th>
                    <th>@lang('messages.TypeOfWeight')&nbsp;(2000-3000)</th>
                    <th>Time/Km</th>
                    <th>Loading/Unloading Time</th>
                    <!-- <th>Service Type</th> -->
                    <th>@lang('messages.Status')</th>
                    <th>@lang('messages.Actions')</th>
                </tr>
            </thead>
            <tbody>
                @forelse($wracker_service as $services)
                    <tr>
                        <td>{{  $loop->iteration }}</td>
                        <td> <img src="<?php echo $services->service_image_url; ?>" class="img-thumbnail" style="max-width:200px;height:60px"> </td>
                        <td>{{  $services->services_name }}</td>
                        <td>{{  $services->description ?? "N/A" }}</td>
                        <td>{{  $services->type_of_weight_1_2000 }}</td>
                        <td>{{  $services->type_of_weight_2000_3000 ?? "N/A" }}</td>
                        <td>{{  $services->time_per_km ?? "N/A" }}</td>
                        <td>{{  $services->loading_unloading_time ?? "N/A" }}</td>
                        <!-- <td>
                            @if($services->wracker_service_type == 1)
                                Service by Appointment
                            @elseif($services->wracker_service_type == 2)
                                Emergency service
                            @endif
                        </td> -->
                        <td>
                            @if($services->status == 'A')
                                <a href="#" data-categoryid="{{ $services->id }}" data-status="P" class="change_wracker_service_status"> <i class="fa fa-toggle-on"></i> </a>
                            @elseif($services->status == 'P')
                            <a href="#" data-categoryid="{{ $services->id }}" data-status="A" class="change_wracker_service_status"> <i class="fa fa-toggle-off"></i> </a>
                            @endif
                        </td>
                        <td>
                            <a href="#" data-toggle="tooltip" data-placement="top" title="Edit" class="btn btn-primary edit_wracker_service btn-sm" data-categoryid="{{ $services->id }}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
                            <a style="margin:2px;" href='#' data-toggle="tooltip" data-placement="top" title="Upload Multiple Images" class="btn btn-primary btn-sm upload_multiple_images" data-categoryid="{{ $services->id }}" ><i class="fa fa-picture-o"></i></a>
                            <a href='#' class="btn btn-danger btn-sm delete_wrecker_service" data-serviceid="{{ $services->id }}" ><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9">No Services Avilable</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!--Add SOS Category -->
<div class="modal" id="add_wracker_service_popup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Add Wrecker Services
            </div>
            <!-- Modal body -->
            <form id="add_wracker_service_form">
                <input type="hidden" name="wracker_service_id" id="wracker_service_id" />	
                <div class="modal-body">
                    <div class="form-group">
                        <label>@lang('messages.ServiceName')&nbsp;<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="@lang('messages.ServiceName')" name="service_name" id="service_name" value="" required="required"  />
                        <span id="start_date_err"></span>
                    </div>
                    <div class="form-group">
                        <label>@lang('messages.TimePerKm')(in Minutes)&nbsp;<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="@lang('messages.TimePerKm')" name="time_per_km" id="time_per_km" value="" required="required"  />
                    </div>
                    <div class="form-group">
                        <label>@lang('messages.LoadingUnloadingTime')&nbsp;<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="@lang('messages.LoadingUnloadingTime')" name="loading_unloading" id="loading_unloading" value="" required="required"  />
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-bordered" style="margin-bottom:15px;">
                                <thead>
                                    <tr>
                                        <th>Type of Weights</th>
                                        <th>@lang('messages.TypeOfWeight')&nbsp;(1-2000)</th>
                                        <th>@lang('messages.TypeOfWeight')&nbsp;(2000-3000)</th>
                                    </tr> 
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>Weight&nbsp;<span class="text-danger">* In Percent</span></th>
                                        <th>
                                            <input type="text" class="form-control" placeholder="@lang('messages.TypeOfWeight')" name="weight_type_1" id="weight_type_1" value="" required="required"  />
                                        </th>
                                        <th>
                                            <input type="text" class="form-control" placeholder="@lang('messages.TypeOfWeight')" name="weight_type_2" id="weight_type_2" value=""  />
                                        </th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>@lang('messages.Description')&nbsp;<span class="text-danger">*</span></label>
                       <textarea name="description" id="description" placeholder="@lang('messages.Description')" class="form-control" ></textarea>
                    </div>
                    <!-- <label>@lang('messages.WrackerServiceType')&nbsp;<span class="text-danger">*</span></label><br> -->
                    <!-- <div class="row">
                        <div class="col-sm-6">
                            <input type="radio" name="service_type" id="service_type" value="1" required="required"  />&nbsp;&nbsp;Service by Appointment
                        </div>
                        <div class="col-sm-6">
                            <input type="radio" name="service_type" id="service_type" value="2" required="required"  />&nbsp;&nbsp;Emergency service
                        </div>
                    </div><br/> -->
                    <input type="hidden" name="categotry_image" value="" id="categotry_image">
                    <div class="form-group">
                        <label>@lang('messages.Image')&nbsp;<span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="cat_file_name[]" id="cat_file_name" multiple required>
                    </div>
                    <div class="form-group">
                    <label></label>
                    </div>	
                    <span id="edit_err_response"></span>                           
                    <div class="form-group">  
                        <button type="submit" id="add_wracker_service_btn" class="btn btn-success">@lang('messages.Save') &nbsp;<i class="icon-paperplane ml-2"></i></button>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">       
        </div>
    </div>
</div>
<!--End-->
@include('admin.component.category_common')
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="{{ url('/') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>@lang('messages.Home')</a>
            <a href="{{ url('/') }}" class="breadcrumb-item">@lang('messages.Admin')</a>
            <span class="breadcrumb-item active">Car Maintenance  </span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop
@push('scripts')
<script src="{{ url('validateJS/car_maintinance.js') }}"></script>
<link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" />
<script src="{{ url('validateJS/mot_service.js') }}"></script>
<script src="{{ url('validateJS/wracker_service.js') }}"></script>
<script src="{{ url('validateJS/products.js') }}"></script>
<script src="{{ url('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ url('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>
<script>
    $(document).ready(function(e){
        $('[data-toggle="tooltip"]').tooltip(); 
        $(document).on('click', '.delete_wrecker_service', function(e){
            e.preventDefault();
            var service_id = $(this).data('serviceid');
            console.log(service_id,'service_id')
            var con = confirm("Are you sure want to delete?");
            if(con == true) {
                $.ajax({
                    url: base_url+"/wrecker_ajax/remove_wrecker_service",
                    method: "GET",
                    data: { service_id: service_id },
                    success: function(data){
                        console.log(data,'data')
                        var parseJson = jQuery.parseJSON(data);
                        $("#msg_response_popup").modal('show');
                        $("#msg_response").html(parseJson[0].msg);
                        setTimeout(function() { location.reload(); }, 1000);
                    }
                }); 
            } else {
                return false;
            }
        });
    });
</script>
@endpush

