@extends('layouts.master_layouts')
@section('content')
<style>
.form-pfu{
 padding: 10px;
}
</style>
<div class="content">
    @if(Session::has('msg'))    
      {!! session::get('msg') !!}
    @endif
    <div class="row" style="margin-bottom:10px;">
        <div class="col-sm-12">
            <a href="javascript::void();" class="btn btn-warning" id="import_export_tyre_inventory" style="color:white;">Import / Export Tyre Inventory&nbsp;<span class="glyphicon glyphicon-plus"></span></a>&nbsp;&nbsp;&nbsp;
        </div>
    </div>
    <div class="card" style="margin-bottom:10px;" >
        <div class="card-header bg-light header-elements-inline">
            <h6 class="card-title" style="font-weight:600;"><i class="fa fa-filter"></i>&nbsp;@lang('messages.Filter')</h6>
        </div>
        <div class="content">
            <div id="filter-panel">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-6">
                            <div class="form-group">
                                <label> Item Number</label>
                                <input type="text" class="form-control" name="item_number" id="tyre_item_number" placeholder="Item Number">  
                            </div> 
                            </div>
                            <div class="col-sm-6">
                            <div class="form-group">
                                <label> EAN Number</label>
                                <input type="text" class="form-control" name="ean_number" id="tyre_ean_number" placeholder="EAN Number">                                  
                            </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <a href='#' id="search_invent_tyre" class="btn btn-warning" style="color:white;">Search &nbsp;<span class="glyphicon glyphicon-search"></span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-light header-elements-inline">
            <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;Tyre Inventory List</h6>
            <a href="#" style="float:right;" id="add_tyre_inventory" class="btn btn-success">Add Tyre Inventory &nbsp;<span class="fa fa-plus"></span></a>
        </div>
        <div class="card-body" id="inventory_ColWrap">
            @include('seller.component.tyre_inventory_list')
            <div class="row" style="margin-top:10px;">
                <div class="col-sm-12">
                    {{ $tyre_inventory->links() }}
                </div>
            </div>
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


<div class="modal" id="add_tyre_inventory_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="tyreModalLabel">Add Tyre Inventory</h4>
                <hr />
            </div>
            <form id="add_seller_tyre_inventory" >
                <input type="hidden" value="" name="seller_tyre_invent_id" id="seller_tyre_invent_id" />
                <input type="hidden" value="1" name="seller_tyre_invent_type" id="seller_tyre_invent_type" />
                <input type="hidden" value="" name="invent_quantity" id="invent_quantity" />
                <div class="modal-body">
                    @csrf
                    <span id="add_response"></span>
                    <span id="err_response"></span>
					<div class="row">
                        <div class="col-md-12 form-pfu">
                            <label>Ean Number</label>
                            <input type="text" class="form-control" name="ean_number"  placeholder="Ean Number" id="ean_number"  />
                            <span id="start_date_err"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-pfu">
                            <label>Item Number</label>
                            <input type="text" class="form-control" name="item_number"  placeholder="Item Number" id="item_number" />
                            <span id="start_date_err"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-pfu">
                            <label>Price&nbsp;<span class="text-danger">*</span></label>
							<input type="text" class="form-control" name="price"  placeholder="Price" id="price" required="required"  />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-pfu">
                            <label>Quantity&nbsp;<span class="text-danger">*</span></label>
							<input type="text" class="form-control" name="quantity"  placeholder="Quantity" id="quantity" required="required"  />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-pfu">
                            <label>Stock Warning</label>
							<input type="text" class="form-control" name="stock_warning"  placeholder="Stock Warning" id="stock_warning"  />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-pfu">
                            <label>Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="A">Publish</option>
                                <option value="P">Save in Draft</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center" style="margin: 10px;">
                        <div class="form-check form-check-inline">
                            <button type="submit" id="seller_tyre_invent_btn" class="btn bg-blue ml-3" >@lang('messages.Submit') <i class="icon-paperplane ml-2"></i></button>
                        </div>
                    </div>
				</div>
			</form>
        </div>
        <div class="modal-footer">       
        </div>
    </div>
</div>

<div class="modal" id="import_export_tyre_inventory_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Import / Export Tyre Inventory</h4>
                <hr />
            </div>
            <div class="card-body">
                <a href="<?php echo url('export/tyre_inventory') ?>" class="btn btn-warning"><i class="fa fa-download"></i>&nbsp;Export Excel For Tyre Inventory</a>
                <hr />
                <h3 style="font-weight:600;">Import Excel Files </h3> 
                <form id="import_tyre_invent_file" >
                @csrf
                   <span id="tyre_msg_response"></span>
                  <div class="control-group" id="fields">
                        <label class="control-label" for="field1">
                            Browse Files
                        </label>
                        <div class="controls">
                            <div class="entry input-group col-xs-3">
                                <input class="btn btn-primary" name="tyre_invent_files"  id="tyre_invent_files" type="file"  accept=".csv" required>
                                <span class="input-group-btn">&nbsp;&nbsp;
                                    <button class="btn btn-success btn-add" type="submit" id="import_tyre_invent_btn">
                                        Import  Tyre Inventory
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
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="../vendors/index.html" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
            <a href="../vendors/internationalization_fallback.html" class="breadcrumb-item">Seller</a>
            <span class="breadcrumb-item active">Tyre Inventory</span>
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