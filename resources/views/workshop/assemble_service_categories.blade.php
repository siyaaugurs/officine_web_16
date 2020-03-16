@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
@if($workshop_status == 100)
<div class="content">
    @if(Session::has('msg'))
      {!! session::get('msg') !!}
    @endif
    <div class="card" style="margin-bottom:10px;">
        <div class="content">
            <div id="filter-panel">
                <div class="panel panel-default">
                    <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <a href="<?php echo url("spacial_condition/assemble_services") ?>" class="btn btn-warning"><i class="fa fa-plus"></i>&nbsp;@lang('messages.SpecialPricecondition')</a> 
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card" style="margin-bottom:10px;">
        <div class="card-header bg-light header-elements-inline">
            <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;@lang('messages.AssembleServiceList')</h6>
            <a href='javascript::void()' class="btn btn-warning" id="car_assemble_import_export" style="color:white; margin-right:-100px;">Import / Export Details&nbsp;<span class="fa fa-download"></span></a>
            <a href='javascript::void()' class="btn btn-primary" id="edit_all_assemble_services" style="color:white; float:right;">Edit Assemble Details&nbsp;<span class="glyphicon glyphicon-edit"></span></a>
        </div>
        <div class="card-body">
            <table class="table datatable-show-all dataTable no-footer" id="list_spare_items">
                <thead>
                    <tr>
                        <th>@lang('messages.SN')</th>
                        <th>@lang('messages.ServiceGroup')</th>
                        <th>@lang('messages.Description')</th>
                        <th>@lang('messages.maxAppointment')</th>
                        <th>@lang('messages.HourlyCost')</th>
                        <th>@lang('messages.Actions')</th>
                    </tr>
                </thead>
                <tbody>
                   @if($spare_groups != FALSE)
                    @forelse($spare_groups as $spare_group)
                      <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $spare_group->main_cat_name }}</td>
                            <td>{{ $spare_group->description ?? "N/A" }}</td>
                            @if(!empty($spare_group->max_appointment))
                            <td>{{ $spare_group->max_appointment ?? "N/A" }}</td>
                            @else
                            <td>
                             {{ !empty($maximum_appointment) ? $maximum_appointment : "N/A" }}
                             </td>
                            @endif
                            @if(!empty($spare_group->hourly_rate))
                            <td>{{ $spare_group->hourly_rate ?? "N/A" }}</td>
                            @else
                            <td>{{ !empty($hourly_rate) ? $hourly_rate : "N/A" }}</td>
                            @endif
                            <td>
                                <a href="#" data-categorieid="{{ $spare_group->id }}" class="btn btn-info btn-sm edit_assemble_service_group"><i class="fa fa-edit"></i></a>
                            </td>
                        </tr>
                    @empty
                    <tr>
                        <td colspan="3">No record found !!!</td>
                    </tr>
                    @endforelse
                   @else 
                   <td colspan="3">No record found !!!</td>
                   @endif 
                </tbody>
            </table>
        </div>
    </div>
</div>
<!--Import Export car Assemble Services -->
<div class="modal" id="import_export_car_assemble_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Import / Export Assemble Services Details</h4>
                <hr />
            </div>
            <div class="card-body">
                <a href="<?php echo url('export/assemble_services') ?>" class="btn btn-warning"><i class="fa fa-download"></i>&nbsp;Export Assemble Service</a>
                <hr />
                <h3 style="font-weight:600;">Import Excel Files </h3> 
                <form id="import_car_assemble_file_form" >
                @csrf
                  <span id="import_file_response"></span>
                  <div class="control-group" id="fields">
                        <label class="control-label" for="field1">
                            Browse Files
                        </label>
                        <div class="controls">
                            <div class="entry input-group col-xs-3">
                                <input class="btn btn-primary" name="car_assemble_file" type="file"  accept=".csv" required>
                                <span class="input-group-btn">
                        &nbsp;&nbsp;
                        <button class="btn btn-success btn-add" type="submit" id="import_services">
                        Import 
                        <span class="glyphicon glyphicon-import"></span>
                                </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal-footer">
        </div>
    </div>
</div>
<!--End-->
<div class="modal" id="edit_workshop_assemble_services">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Edit Assemble Services
            </div>
            <!-- Modal body -->
            <form id="edit_workshop_assemble_services_form">
                <input type="hidden" name="category_id" id="category_id" value=""/>	
                <div class="modal-body">
                    <div class="form-group">
                        <label>@lang('messages.maxAppointment')<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="@lang('messages.maxAppointment')" name="max_appointment" id="max_appointment" value="" required="required"  />
                    </div>
                    <div class="form-group">
                        <label>@lang('messages.HourlyCost')&nbsp;<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="@lang('messages.HourlyCost')" name="hourly_cost" id="hourly_cost" value="" required="required"  />
                    </div>
                    <!-- <label>@lang('messages.Description')&nbsp;<span class="text-danger">*</span></label><br>
                    <div class="form-group">
                        <textarea name="description" id="description" placeholder="@lang('messages.Description')" required="required" class="form-control"></textarea>
                    </div> -->	                          
                    <div class="form-group">  
                        <button type="submit" id="workshop_assemble_services_btn" class="btn btn-success">@lang('messages.Save') &nbsp;<i class="icon-paperplane ml-2"></i></button>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">       
        </div>
    </div>
</div>
<div class="modal" id="edit_all_workshop_assemble_services">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Edit Assemble Services
            </div>
            <!-- Modal body -->
            <form id="edit_all_workshop_assemble_services_form">
                <input type="hidden" name="category_id" id="category_id" value=""/>	
                <div class="modal-body">
                    <div class="form-group">
                        <label>@lang('messages.maxAppointment')<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="@lang('messages.maxAppointment')" name="max_appointment" id="max_appointment"  required="required" value="{{ !empty($maximum_appointment) ? $maximum_appointment : "" }}"  />
                    </div>
                    <div class="form-group">
                        <label>@lang('messages.HourlyCost')&nbsp;<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="@lang('messages.HourlyCost')" name="hourly_cost" id="hourly_cost"  required="required" value="{{ !empty($hourly_rate) ? $hourly_rate : "" }}"  />
                    </div>	                          
                    <div class="form-group">  
                        <button type="submit" id="edit_workshop_assemble_services_btn" class="btn btn-success">@lang('messages.Save') &nbsp;<i class="icon-paperplane ml-2"></i></button>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">       
        </div>
    </div>
</div>
@else 
    <div>
        <div class="row card" style="padding:40px;">
            <div class="col-lg-12">
                <h3 align="center">Please Complete your Profile </h3>
                <p align="center"><a href="{{ url('')}}" class="btn btn-primary">Manage Profile</a></p>
            </div>
        </div>
    </div>
@endif
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>@lang('messages.Home') </a>
            <a href="#" class="breadcrumb-item">@lang('messages.Admin') </a>
            <!-- <a href="#" class="breadcrumb-item">@lang('messages.SpareGroups') </a> -->
            <span class="breadcrumb-item active"> @lang('messages.AssembleServices') </span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop
@push('scripts')
<script src="{{ asset('validateJS/import_export.js') }}"></script>
<script src="{{ url('validateJS/admin.js') }}"></script>
<script src="{{ url('validateJS/spare_groups.js') }}"></script>
<script src="{{ url('validateJS/products.js') }}"></script>
<script>
    $(document).ready(function(e) {
        $(document).on('click', '.edit_assemble_service_group', function(e){
            e.preventDefault();
            var $this = $(this);
            var category_id = $(this).data('categorieid');
            $.ajax({
                url: base_url+"/assemble_services/get_assemble_category_details",
                method: "GET",
                data: {id:category_id},
                success: function(data){
                    console.log(data);
                    var parseJson = jQuery.parseJSON(data);
                    if (parseJson.status == 200) {
                        $("#category_id").val(parseJson.response.categories_id);
                        $("#max_appointment").val(parseJson.response.max_appointment);
                        $("#hourly_cost").val(parseJson.response.hourly_rate);
                        $("#description").val(parseJson.response.description);
                        $("#edit_workshop_assemble_services").modal({
					       backdrop:'static',
						   keyboard:false
						});
                    }
                }
            });
        });
        $(document).on('submit', '#edit_workshop_assemble_services_form', function(e){
            $('#response').html(" ");
            $("err_response").html(" ");
            $('#workshop_assemble_services_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
            e.preventDefault();
            $.ajax({
                url: base_url+"/assemble_services/edit_assemble_service_details",
                type: "POST",        
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData:false,  
                success: function(data){
                    errorString = '';
                    $('#workshop_assemble_services_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
                    var parseJson = jQuery.parseJSON(data); 
                    if(parseJson.status == 400){
                        $.each(parseJson.error, function(key , value) {
                            errorString += '<div class="notice notice-danger"><strong>Note , </strong>'+ value+' .</div>';
                        });
                            $("#msg_response_popup").modal('show');
                            $("#msg_response").html(errorString);	
                    }
                    if(parseJson.status == 200){
                        $(".close").click();	
                        $("#msg_response_popup").modal('show');
                        $("#msg_response").html(parseJson.msg);
                        setTimeout(function(){ location.reload(); } , 1000);
                    } 
                    if(parseJson.status == 100){
                        $(".close").click();
                        $("#msg_response_popup").modal('show');
                        $("#msg_response").html(parseJson.msg);
                    }	 
                } 
            });
        });
        $(document).on('click', '#edit_all_assemble_services', function(e){
           $('#edit_all_workshop_assemble_services').modal({
			   backdrop:'static',
			   keyboard:false
			});
        });
        $(document).on('submit', '#edit_all_workshop_assemble_services_form', function(e){
            $('#response').html(" ");
            $("err_response").html(" ");
            $('#edit_workshop_assemble_services_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
            e.preventDefault();
            $.ajax({
                url: base_url+"/assemble_services/edit_all_assemble_service_details",
                type: "POST",        
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData:false,  
                success: function(data){
                    errorString = '';
                    $('#edit_workshop_assemble_services_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
                    var parseJson = jQuery.parseJSON(data); 
                    if(parseJson.status == 400){
                        $.each(parseJson.error, function(key , value) {
                            errorString += '<div class="notice notice-danger"><strong>Note , </strong>'+ value+' .</div>';
                        });
                            $("#msg_response_popup").modal('show');
                            $("#msg_response").html(errorString);	
                    }
                    if(parseJson.status == 200){
                        $(".close").click();	
                        $("#msg_response_popup").modal('show');
                        $("#msg_response").html(parseJson.msg);
                        setTimeout(function(){ location.reload(); } , 1000);
                    } 
                    if(parseJson.status == 100){
                        $(".close").click();
                        $("#msg_response_popup").modal('show');
                        $("#msg_response").html(parseJson.msg);
                    }	 
                } 
            });
        });
    });
</script>
@endpush


