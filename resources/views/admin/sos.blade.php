@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
@if(session::has('msg'))
  {!! Session::get('msg') !!}
@endif
<style> .container{ padding:15px;} </style>
<div class="card">
<div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;@lang('messages.SOSCategory')</h6>
       <a href='#' class="btn btn-primary" id="add_new_sos_category" style="color:white;">Add New Services &nbsp;<span class="glyphicon glyphicon-plus"></span></a>
        </div>
	<div class="card-body" id="car_maintinance_ColWrap">
        <table class="table">
            <thead>
                <tr>
                    <th>@lang('messages.SN')</th>
                    <th>@lang('messages.Image')</th>
                    <th>@lang('messages.Services')</th>
                    <th>@lang('messages.Description')</th>
                  
                    <th>@lang('messages.Status')</th>
                    <th>@lang('messages.Actions')</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sos_category as $sos)
                    <tr>
                        <td>{{  $loop->iteration }}</td>
                        <td> <img src="<?php echo $sos->cat_image_url; ?>" class="img-thumbnail" style="width:125px;height:70px"> </td>
                        <td>{{  $sos->category_name }}</td>
                        <td>{{  $sos->description }}</td>
                        <td>
                            @if($sos->status == 0)
                                <a href="#" data-categoryid="{{ $sos->id }}" data-status="1" class="change_sos_category_status"> <i class="fa fa-toggle-off"></i> </a>
                            @elseif($sos->status == 1)
                            <a href="#" data-categoryid="{{ $sos->id }}" data-status="0" class="change_sos_category_status"> <i class="fa fa-toggle-on"></i> </a>
                            @endif
                        </td>
                        <td>
                            <a href="#" data-toggle="tooltip" data-placement="top" title="Edit" class="btn btn-primary edit_sos_category btn-sm" data-categoryid="{{ $sos->id }}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
                            <a href='#' data-toggle="tooltip" data-placement="top" title="Upload Multiple Images" class="btn btn-primary btn-sm upload_multiple_images" data-categoryid="{{ $sos->id }}" ><i class="fa fa-picture-o"></i></a>
                        </td>
                    </tr>
                @empty
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!--Add SOS Category -->
<div class="modal" id="add_sos_category_popup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Add SOS Services
            </div>
            <!-- Modal body -->
            <form id="add_sos_category_form">
                <input type="hidden" name="sos_category_id" id="sos_category_id" />	
                <div class="modal-body">
                    <div class="form-group">
                        <label>@lang('messages.Services')&nbsp;<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="@lang('messages.Services')" name="category_name" id="category_name" value="" required="required"  />
                        <span id="start_date_err"></span>
                    </div>
                    <div class="form-group">
                        <label>@lang('messages.Description')&nbsp;<span class="text-danger">*</span></label>
                        <textarea class="form-control" name="description" id="description" placeholder="@lang('messages.decription')"></textarea>
                    </div>
                   <!-- <div class="form-group">
                        <label>@lang('messages.Priority')&nbsp;</label>
                        <select name="priority" id="priority" class="form-control">
                            <option value=" " hidden="hidden">--Select Priority--</option>
                            @php
                                for($i = 1 ; $i <= 20; $i++ )
                                {
                                    echo "<option value=".$i.">".$i."</option>";
                                }
                            @endphp
                        </select>
                    </div>-->
                    <!--<div class="form-group">
                        <label>@lang('messages.Status')&nbsp;</label>
                        <select name="status" id="status" class="form-control">
                            <option value="1">Publish</option>
                            <option value="0">Save in Draft</option>
                        </select>
                    </div>-->
                    <input type="hidden" name="categotry_image" value="" id="categotry_image">
                    <div class="form-group">
                        <label>@lang('messages.Image')&nbsp;<span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="cat_file_name[]" id="cat_file_name" multiple>
                    </div>
                    <div class="form-group">
                    <label></label>
                    </div>	
                    <span id="edit_err_response"></span>                           
                    <div class="form-group">  
                        <button type="submit" id="add_sos_category_btn" class="btn btn-success">@lang('messages.Save') &nbsp;<i class="icon-paperplane ml-2"></i></button>
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
<script src="{{ url('validateJS/sos.js') }}"></script>
<script src="{{ url('validateJS/products.js') }}"></script>
<script src="{{ url('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ url('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>
<script>
    $(document).ready(function(e){
        $('[data-toggle="tooltip"]').tooltip(); 
    });
</script>
@endpush

