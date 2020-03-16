@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="content">
    <!-- Page length options -->
    @if(Session::has('msg'))
      {!! session::get('msg') !!}
    @endif
	<div id="success_message" class="ajax_response" style="float:top"></div>
    <div class="card">
        <div class="card-header bg-light header-elements-inline">
            <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;@lang('messages.Groups')</h6>
            <a href='#' class="btn btn-primary" id="add_group" style="color:white; float:right;" >@lang('messages.AddNewGroup') &nbsp;<span class="glyphicon glyphicon-plus"></span></a>
        </div>
        <table class="table datatable-show-all dataTable no-footer">
            <thead>
                <tr>
                    <th>@lang('messages.SN')</th>
                    <th>@lang('messages.CategoryImage')</th>
                    <th>@lang('messages.GroupName')</th>
                    <th>@lang('messages.RangeFrom')(Diameter)</th>
                    <th>@lang('messages.RangeTo')(Diameter)</th>
                    <th>@lang('messages.Description')</th>
                    <!-- <th>Set Default</th> -->
                    <th>@lang('messages.Status')</th>
                    <th class="text-center">@lang('messages.Actions')</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tyre_groups as $groups)
                    @php $description = str_limit($groups->description, 50); @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><img src="<?php echo $groups->cat_image_url; ?>" class="img-thumbnail" style="width:125px;height:70px"></td>
                        <td>{{ $groups->category_name }}</td>
                        <td>{{ $groups->range_from }}</td>
                        <td>{{ $groups->range_to }}</td>
                        <td>{{ $description }}</td>
                        <!-- <td>
                            <input type="checkbox" name="tyre_group" class="tyre_group" data-group_id="{{ $groups->id }}" {{ $groups->private == 1 ? 'checked' : '' }}>
                        </td> -->
                        <td>
                            @if($groups->status == "0")
                                <a href="#" class="change_group_status" data-group_id="{{ $groups->id }}" data-status="1"><i class="fa fa-toggle-on"></i> </a> 
                            @else 
                                <a href="#" class="change_group_status" data-group_id="{{ $groups->id }}" data-status="0"><i class="fa fa-toggle-off"></i> </a>
                            @endif
                        </td>
                        <td>
                            <a href="#" class="btn btn-primary edit_group" data-group_id="{{ $groups->id }}"> <i class="fa fa-edit"></i> </a>
                           <a  data-toggle="tooltip" data-placement="top" title="Remove Groups" href="#" data-group_id="{{ $groups->id }}" class="btn btn-danger delete_group"><i class="fa fa-trash" ></i></a>
                        </td>
                    </tr>
                @empty
                <tr>
                   <td colspan="6">@lang('messages.NoRecordFound')</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="row" style="margin-top:20px;">
            <div class="col-sm-12">
                @if($tyre_groups->count() > 0) 
                    {{ $tyre_groups->links() }}
                @endif 
            </div>
        </div>
    </div>
    <!-- /page length options -->
</div>
<!--Add Group popup modal-->
<div class="modal" id="add_new_group">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">@lang('messages.AddNewGroup')</h4>
                <hr />
            </div>
            <!-- Modal body -->
            <form id="add_group_form" >
                <input type="hidden" value="" name="group_id" id="group_id" />
                <div class="modal-body">
                    @csrf
                    <span id="add_response"></span>
                    <span id="err_response"></span>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label>@lang('messages.GroupName')&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="@lang('messages.GroupName')" name="group_name" id="group_name" required="required"  />
                            <span id="start_date_err"></span>
                        </div>
                    </div>
                   
                    <div class="row">
                        <div class="col-md-12 form-group">
                        <label>@lang('messages.Description')&nbsp;</label>
                        <textarea name="description" id="description" class="form-control" placeholder="@lang('messages.Description')" required="required"></textarea>
                        <span id="start_date_err"></span>
                        </div>
                    </div>
                   <div class="row">
                        <div class="col-md-12 form-group">
                            <label>@lang('messages.Priority')&nbsp;</label>
                            <input type="number" class="form-control" placeholder="@lang('messages.Priority')" name="group_priority" min="1" id="group_priority" required="required"  />
                            <span id="start_date_err"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                        <label>@lang('messages.StandardServiceTime')&nbsp;<span style="color:red">(in hr.)</span>&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control timepicker" placeholder="@lang('messages.StandardServiceTime')" min="1" name="service_time" id="service_time" required="required"  />
                            <span id="start_date_err"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                        <label>@lang('messages.RangeFrom')&nbsp;</span>&nbsp;<span class="text-danger">(Diameter)*</span></label>
                            <input type="number" class="form-control timepicker" placeholder="@lang('messages.RangeFrom')" min="1" name="range_from" id="range_from" required="required"  />
                            <span id="start_date_err"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                        <label>@lang('messages.RangeTo')&nbsp;</span>&nbsp;<span class="text-danger">(Diameter)*</span></label>
                            <input type="number" class="form-control timepicker" placeholder="@lang('messages.RangeTo')" min="1" name="range_to" id="range_to" required="required"  />
                            <span id="start_date_err"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label>@lang('messages.BrowseImage')&nbsp;</label>
                            <input type="file"  name="cat_file_name[]" id="cat_file_name" placeholder="Category Name" class="form-control" multiple="multiple"/>
                            <span id="start_date_err"></span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="form-check form-check-inline">
                            <button type="submit" id="group_btn" class="btn bg-blue ml-3" >@lang('messages.Submit') <i class="icon-paperplane ml-2"></i></button>
                        </div>
                    </div>
				</div>
			</form>
        </div>
        <div class="modal-footer">       
        </div>
    </div>
</div>
<!--End-->
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
            <a href="{{ url('/') }}" class="breadcrumb-item">Admin </a>
            <span class="breadcrumb-item active"> @lang('messages.Groups') </span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop
@push('scripts')
<script src="{{ url('validateJS/admin.js') }}"></script>
<script src="{{ url('validateJS/add_group.js') }}"></script>
@endpush


