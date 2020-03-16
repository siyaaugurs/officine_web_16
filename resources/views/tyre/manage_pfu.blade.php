@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />

<style>
.form-pfu{
 padding: 10px;
}
</style>
<div class="content">
    <!-- Page length options -->
    @if(Session::has('msg'))
      {!! session::get('msg') !!}
    @endif
	<div id="success_message" class="ajax_response" style="float:top"></div>
    <div class="card"  style="overflow:auto">
         <div class="card-header bg-light header-elements-inline">
            <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;PFU</h6>
            <a href='#' class="btn btn-primary" id="add_pfu" style="color:white; float:right;" >Add PFU Detail &nbsp;<span class="glyphicon glyphicon-plus"></span></a>
        </div>

        <table class="table datatable-show-all dataTable no-footer" >
            <thead>
                <tr>
                    <th>SN</th>
					<!-- <th>Tyre Type</th>
					<th>Category</th> -->
                    <th>Description</th>
                    <th>Admin Price</th>
                    <th>Tyre Class</th>
					<!-- <th>Weights</th> -->
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tyre_pfu as $pfu)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ !empty($pfu->tyre_type_description) ? $pfu->tyre_type_description : "N/A" }}</td>
                        <td>{{ $pfu->admin_price ? $pfu->admin_price : 'N/A' }}</td>
                        <td>{{ $pfu->tyre_class ? $pfu->tyre_class : 'N/A' }}</td>
                        <td>
                            <a href="javascript::void()" class="btn btn-primary edit_pfu" data-pfu_id="{{ $pfu->id }}"> <i class="fa fa-edit"></i> </a>&nbsp;
                            <a href="javascript::void()" class="btn btn-danger delete_admin_pfu" data-pfuid="{{ $pfu->id }}"> <i class="fa fa-trash"></i> </a>
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
               
            </div>
        </div>
    </div>
    <!-- /page length options -->
</div>
<!--Add pfu popup modal-->
<div class="modal" id="add_new_pfu">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">@lang('messages.AddNewpfu')</h4>
                <hr />
            </div>
            <!-- Modal body -->
            <form id="add_pfu_form" >
                <input type="hidden" value="" name="pfu_id" id="pfu_id" />
                <div class="modal-body">
                    @csrf
                    <span id="add_response"></span>
                    <span id="err_response"></span>
                    <!-- <div class="row">
                        <div class="col-md-12 form-pfu">
                            <label>Tyre Type&nbsp;<span class="text-danger">*</span></label>
							<select id="tyre_type" name="tyre_type" class="form-control" required="required">
							@foreach($tyre_type as $tyre)
							<option value="{{$tyre['code']}}">{{$tyre['name']}}</option>
							@endforeach
							</select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-pfu">
                            <label>Category&nbsp;<span class="text-danger">*</span></label>
                           <select id="category" name="category" class="form-control" required="required">
							@foreach($category_type as $category)
							<option value="{{$category['code']}}">{{$category['name']}}</option>
							@endforeach
							</select>
                        </div>
                    </div> -->
					<!-- <div class="row">
                        <div class="col-md-12 form-pfu">
                            <label>Tyre Type Description for seller&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="" name="tyre_type_description_for_seller" id="tyre_type_description_for_seller" required="required"  />
                            <span id="start_date_err"></span>
                        </div>
                    </div>
					<div class="row">
                        <div class="col-md-12 form-pfu">
                            <label>Tyre Type description for customer&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="" name="tyre_type_description_for_customer" id="tyre_type_description_for_customer" required="required"  />
                            <span id="start_date_err"></span>
                        </div>
                    </div> -->
					
					<div class="row">
                        <div class="col-md-12 form-pfu">
                            <label>Admin Price&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="admin_price"  placeholder="Admin Price" id="admin_price" required="required"  />
                            <span id="start_date_err"></span>
                        </div>
                    </div>
					<div class="row">
                        <div class="col-md-12 form-pfu">
                            <label>Tyre Class&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="tyre_class"  placeholder="Tyre Class" id="tyre_class" required="required"  />
                            <span id="start_date_err"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-pfu">
                            <label>Description&nbsp;<span class="text-danger">*</span></label>
							 <textarea name="description" id="description" class="form-control" placeholder="Description" required="required"></textarea>
                        </div>
                    </div>
					<!-- <div class="row" style="margin-left:2px; margin-bottom: -7px;">
                            <label>Weights Of Tyres &nbsp;<span class="text-danger">*</span></label>
                    </div>
					<div class="row">
                        <div class="col-md-6 form-pfu">
                            <label>From&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="weights of tyres from" name="weights_of_tyres_from" id="weights_of_tyres_from" required="required"  />
                            <span id="start_date_err"></span>
                        </div>
                        <div class="col-md-6 form-pfu">
							 <label>To &nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="weights of tyres to" name="weights_of_tyres_to" id="weights_of_tyres_to" required="required"  />
                            <span id="start_date_err"></span>
                        </div>
                    </div> -->
                    <div class="d-flex justify-content-between align-items-center" style="margin: 10px;">
                        <div class="form-check form-check-inline">
                            <button type="submit" id="pfu_btn" class="btn bg-blue ml-3" >@lang('messages.Submit') <i class="icon-paperplane ml-2"></i></button>
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
            <span class="breadcrumb-item active"> @lang('messages.pfu') </span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop
@push('scripts')
<script src="{{ url('validateJS/admin.js') }}"></script>
<script src="{{ url('validateJS/add_pfu.js') }}"></script>
@endpush


