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

            <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;@lang('messages.spareServiceGroup')</h6>

            <a href='#' class="btn btn-primary" id="add_service_group" style="color:white; float:right;" >Add New Service &nbsp;<span class="glyphicon glyphicon-plus"></span></a>

        </div>

        <table class="table datatable-show-all dataTable no-footer">

            <thead>

                <tr>

                    <th>@lang('messages.SN')</th>

                    <th>@lang('messages.ServiceGroup')</th>

                    <th>@lang('messages.Description')</th>

                    <th>Set Default</th>

                    <th>@lang('messages.Status')</th>

                    <th class="text-center">@lang('messages.Actions')</th>

                </tr>

            </thead>

            <tbody>

                @forelse($spare_groups as $spare_parts)

                    @php $description = str_limit($spare_parts->description, 50); @endphp

                    <tr>

                        <td>{{ $loop->iteration }}</td>

                        <td>{{ $spare_parts->main_cat_name }}</td>

                        <td>{{ $description }}</td>

                        <td>

                            <input type="checkbox" name="spare_service_group" class="spare_service_group" data-serviceid="{{ $spare_parts->id }}" {{ $spare_parts->private == 1 ? 'checked' : '' }}>

                        </td>

                        <td>

                            @if($spare_parts->status == "A")

                                <a href="#" class="change_spare_group_status" data-spareid="{{ $spare_parts->id }}" data-status="P"><i class="fa fa-toggle-on"></i> </a> 

                            @else 

                            <a href="#" class="change_spare_group_status" data-spareid="{{ $spare_parts->id }}" data-status="A"><i class="fa fa-toggle-off"></i> </a>

                            @endif

                        </td>

                        <td>

                            <a href="#" class="btn btn-primary edit_spare_group" data-spareid="{{ $spare_parts->id }}"> <i class="fa fa-edit"></i> </a>

                           <a  data-toggle="tooltip" data-placement="top" title="Remove Groups" href='{{ url("master/delete_main_cat/$spare_parts->id") }}' class="btn btn-danger delete_spare_group"><i class="fa fa-trash" ></i></a>

                            <!-- <a href="#" class="btn btn-danger delete_spare_group"> <i class="fa fa-trash"></i> </a> -->

                        </td>

                    </tr>

                @empty

                @endforelse

            </tbody>

        </table>

        <div class="row" style="margin-top:20px;">

            <div class="col-sm-12">

                @if($spare_groups->count() > 0) 

                    {{ $spare_groups->links() }}

                @endif 

            </div>

        </div>

    </div>

    <!-- /page length options -->

</div>

<!--Add Service Group popup modal-->

<div class="modal" id="add_new_service_group">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>

                <h4 class="modal-title" id="myModalLabel">Add New Service Group</h4>

                <hr />

            </div>

            <!-- Modal body -->

            <form id="add_service_group_form" >

                <input type="hidden" value="" name="spare_group_id" id="spare_group_id" />

                <div class="modal-body">

                    @csrf

                    <span id="add_response"></span>

                    <span id="err_response"></span>

                    <div class="row">

                        <div class="col-md-12 form-group">

                            <label>@lang('messages.ServiceGroupName')&nbsp;<span class="text-danger">*</span></label>

                            <input type="text" class="form-control" placeholder="@lang('messages.ServiceGroupName')" name="spare_group_name" id="spare_group_name" required="required"  />

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

                   <!-- <div class="row">

                        <div class="col-md-12 form-group">

                            <label>@lang('messages.Priority')&nbsp;</label>

                            <select name="priority" id="priority" class="form-control">

                                <option value=" " hidden="hidden">--Select Priority--</option>

                               

                            </select>

                            <span id="start_date_err"></span>

                        </div>

                    </div>-->

                   <!-- <div class="row">

                        <div class="col-md-12 form-group">

                            <label>@lang('messages.Status')&nbsp;</label>

                            <select name="status" id="status" class="form-control">

                                <option value="A">Publish</option>

                                <option value="P">Save in Draft</option>

                            </select>

                            <span id="start_date_err"></span>

                        </div>

                    </div>-->

                    

                    <div class="d-flex justify-content-between align-items-center">

                        <div class="form-check form-check-inline">

                            <button type="submit" id="service_group_btn" class="btn bg-blue ml-3" >@lang('messages.Submit') <i class="icon-paperplane ml-2"></i></button>

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

            <a href="{{ url('/') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>@lang('messages.Home')</a>
            <a href="{{ url('/') }}" class="breadcrumb-item">@lang('messages.Admin')</a>

            <span class="breadcrumb-item active"> @lang('messages.spareServiceGroup') </span>

        </div>

        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>

    </div>

</div>

@stop

@push('scripts')

<script src="{{ url('validateJS/admin.js') }}"></script>

<script src="{{ url('validateJS/spare_groups.js') }}"></script>

<script>

$(document).ready(function(e) {

   $(document).on('click','.delete_spare_group',function(e){

  e.preventDefault();

  var con = confirm("Are you sure want to remove this group .");

  if(con == true){

	  var href = $(this).attr('href');

	  window.location.href = href;

	}

}); 

});

</script>

@endpush





