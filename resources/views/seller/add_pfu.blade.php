@extends('layouts.master_layouts')
@section('content')
<style>
.form-pfu{
 padding: 10px;
}
</style>
<div class="content">
    <div class="card">
        <div class="card-header bg-light header-elements-inline">
            <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;PFU List</h6>
            <a href="#" style="float:right;" id="add_seller_tyre_pfu" class="btn btn-success">Add Tyre PFU &nbsp;<span class="fa fa-plus"></span></a>
        </div>
        <div class="card-body">
            <table class="table datatable-show-all dataTable no-footer">
                <thead>
                    <tr>
                        <th>S No.</th>
                        <th>Seller Price</th>
                        <th>Tyre Class</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($seller_pfu as $pfu)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $pfu->price ? $pfu->price : 'N/A' }}</td>
                            <td>{{ $pfu->tyre_class ? $pfu->tyre_class : 'N/A' }}</td>
                            <td>{{ $pfu->description ? $pfu->description : 'N/A' }}</td>
                            <td>
                                <a href="javascript::void()" class="btn btn-primary edit_seller_pfu" data-sellerpfu_id="{{ $pfu->id }}"> <i class="fa fa-edit"></i> </a>&nbsp;
                                <a href="javascript::void()" class="btn btn-danger delete_seller_pfu" data-sellerpfu_id="{{ $pfu->id }}"> <i class="fa fa-trash"></i> </a>
                            </td>
                        </tr>
                    @empty
                    <tr>
                        <td colspan="6">@lang('messages.NoRecordFound')</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal" id="msg_response_popup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel"><i class="text-white icon-megaphone mr-3 icon-2x"></i> Message  </h4>
                <hr />
            </div>
            <div class="modal-body">
                <div class="row ">
                    <div class="col-md-12">
                        <div id="msg_response"></div>  
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer"> </div>
    </div>
</div>

<div class="modal" id="add_seller_pfu_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Add New PFU</h4>
                <hr />
            </div>
            <form id="add_seller_pfu_form" >
                <input type="hidden" value="" name="seller_pfu_id" id="seller_pfu_id" />
                <div class="modal-body">
                    @csrf
                    <span id="add_response"></span>
                    <span id="err_response"></span>
                    <div class="row">
                        <div class="col-md-12 form-pfu">
                            <label>Seller Price&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="seller_price"  placeholder="Seller Price" id="seller_price" required="required"  />
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
                    <div class="d-flex justify-content-between align-items-center" style="margin: 10px;">
                        <div class="form-check form-check-inline">
                            <button type="submit" id="seller_pfu_btn" class="btn bg-blue ml-3" >@lang('messages.Submit') <i class="icon-paperplane ml-2"></i></button>
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
            <a href="../vendors/index.html" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
            <a href="../vendors/internationalization_fallback.html" class="breadcrumb-item">Seller</a>
            <span class="breadcrumb-item active">Edit Inventory products</span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>   
</div>
@stop
@push('scripts')
<script src="{{ asset('validateJS/seller.js') }}"></script>
<script src="{{ url('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ url('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>
@endpush