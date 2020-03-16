@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
@if(Session::has('msg'))
    {!! session::get('msg') !!}
@endif
<div class="content">
    <div class="tab_here mb-3">
        <ul class="nav nav-pills m-b-10" id="pills-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link <?php if($page_type == "manage_tyre_mesurement") echo "active"; ?>"  href='{{ url("tyre24/manage_tyre_mesurement")}}'>Tyre Type</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if($page_type == "season_type_management") echo "active"; ?>"  href='{{ url("tyre24/season_type_management")}}'>Season Type</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if($page_type == "speed_index") echo "active"; ?>"  href='{{ url("tyre24/speed_index")}}'>Speed Index</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if($page_type == "aspect_ratio") echo "active"; ?>"  href='{{ url("tyre24/aspect_ratio")}}'>Aspect Ratio</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if($page_type == "manage_diameter") echo "active"; ?>"  href='{{ url("tyre24/manage_diameter")}}'>Diameter</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if($page_type == "manage_width") echo "active"; ?>"  href='{{ url("tyre24/manage_width")}}'>Width</a>
            </li>
        </ul>
    </div>
    <div class="card">
        <div class="card-header bg-light header-elements-inline">
            <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;Speed Index List</h6>
            <a href='#' class="btn btn-success" id="speed_index_btn" style="color:white; float:right;" >Add Speed Index&nbsp;&nbsp;<span class="glyphicon glyphicon-plus"></span></a>
        </div>
        <div class="card-body" id="" style="overflow:auto;">
            <table class="table" id="brand_logo">
                <thead>
                    <tr>
                        <th>@lang('messages.SN')</th>
                        <th>Speed Index Value</th>
                        <th>@lang('messages.Actions')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($speed_index as $measure)
                        <tr>
                            <td>{{  $loop->iteration }}</td>
                            <td>{{  $measure->name }}</td>
                            <td>
                                <a href="#" data-toggle="tooltip" data-placement="top" title="Edit Tyre Type" class="btn btn-primary edit_speed_index_measure btn-sm" data-measuretype="{{  $measure->type }}" data-measureid="{{ $measure->id }}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
                                <a href='#' data-toggle="tooltip" data-placement="top" title="Delete" class="btn btn-danger btn-sm delete_tyre_type_measure" data-measureid="{{ $measure->id }}" ><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">No Record Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal" id="speed_index_measure_popup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Add Speed Index</h4>
                <hr />
            </div>
            <form id="add_new_speed_index_measurement" >
                <input type="hidden" value="" name="speed_index_id" />
                <div class="modal-body">
                    @csrf
                    <label>Speed Index Value&nbsp;<span class="text-danger">*</span></label>
                    <div class="input-group mb-3" id= "tyre_speed_index_code_div">
                        <input type="text" class="form-control" placeholder="Enter Value" id="speed_index_value" name="speed_index_value[]" required="required">
                        <div class="input-group-append ">
                            <span class="btn btn-success input-group-text" id="append_speed_index">Add More</span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="form-check form-check-inline">
                            <button type="submit" id="add_tyre_speed_index_btn" class="btn btn-success ml-3" >@lang('messages.Submit') <i class="icon-paperplane ml-2"></i></button>
                        </div>
                    </div>
				</div>
			</form>
        </div>
        <div class="modal-footer">       
        </div>
    </div>
</div>

<div class="modal" id="edit_speed_index_measure_popup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="medityModalLabel">Edit Aspect Ratio</h4>
                <hr />
            </div>
            <form id="edit_speed_index_measurement_form" >
                <input type="hidden" value="" name="speed_index_id" id="tyre_speed_index_id" />
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label>Aspect Ratio Value&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="Enter Value" id="tyre_speed_index_value" name="speed_index_value" required="required">
                            <span id="start_date_err"></span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="form-check form-check-inline">
                            <button type="submit" id="edit_tyre_speed_index_btn" class="btn btn-success ml-3" >@lang('messages.Submit') <i class="icon-paperplane ml-2"></i></button>
                        </div>
                    </div>
				</div>
			</form>
        </div>
        <div class="modal-footer">       
        </div>
    </div>
</div>
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
            <a href="{{ url('/') }}" class="breadcrumb-item">Admin </a>
            <span class="breadcrumb-item active"> Tyre Measurement </span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop
@push('scripts')
<script src="{{ url('validateJS/admin.js') }}"></script>
<script src="{{ url('validateJS/add_group.js') }}"></script>
<script src="{{ url('validateJS/tyre_measurement.js') }}"></script>
<script>
    $(document).ready(function(e) {
        $(document).on('click', '#append_speed_index', function(e) {
            e.preventDefault();
            var html_content = '<div class="input-group mb-3 speed_index_code_div" style="margin-top:10px;">'
                        +'<input type="text" class="form-control" placeholder="Enter Value" id="speed_index_value" name="speed_index_value[]" required="required">'
                        +'<div class="input-group-append ">'
                        +'<span class="btn btn-danger remove_speed_index_div">X</span>'
                        +'</div>'
                    +'</div>';
            $('#tyre_speed_index_code_div').after().append(html_content);
        });
        $(document).on('click', '.remove_speed_index_div', function(e) {
            e.preventDefault();
            $(this).parent().parent('.speed_index_code_div').remove();
        });
    });
</script>
@endpush


