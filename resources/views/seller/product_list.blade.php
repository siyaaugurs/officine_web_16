@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<style>
    .container{
        padding:15px;
    }
</style>
<div class="content">
    @if(Session::has('msg'))
      {!! session::get('msg') !!}
    @endif

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>	
            <strong>{{ $message }}</strong>
        </div>
    @endif
    <div class="row" style="margin-bottom:10px;">
        <div class="col-sm-12">
            <a href="javascript::void();" class="btn btn-warning" id="import_export_product_inventory" style="color:white;">Import / Export Spare Product&nbsp;<span class="glyphicon glyphicon-plus"></span></a>&nbsp;&nbsp;&nbsp;
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
                                <input type="text" class="form-control" name="item_number" id="item_number" placeholder="Item Number">  
                            </div> 
                            </div>
                            <div class="col-sm-6">
                            <div class="form-group">
                                <label> EAN Number</label>
                                <input type="text" class="form-control" name="ean_number" id="ean_number" placeholder="EAN Number">                                  
                            </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <a href='#' id="search_invent_products" class="btn btn-warning" style="color:white;">Search &nbsp;<span class="glyphicon glyphicon-search"></span></a>                                  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </div>
    <div class="card" style="overflow:auto" id="user_data_body">
        <div class="card-header bg-light header-elements-inline">
            <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;@lang('messages.productsList')</h6>
        </div>
        <div class="card-body" id="inventory_ColWrap" style="overflow: auto;">
            @include('seller.component.product_list')
            <div class="row" style="margin-top:10px;">
                <div class="col-sm-12">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
    <!-- /page length options -->
</div>
<div class="modal" id="msg_response_popup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel"><i class="text-white icon-megaphone mr-3 icon-2x"></i> @lang('messages.Message')  </h4>
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

<div class="modal" id="import_export_product_inventory_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Import / Export Sapre Products Inventory</h4>
                <hr />
            </div>
            <div class="card-body">
                <a href="<?php echo url('export/sapre_part_inventory') ?>" class="btn btn-warning"><i class="fa fa-download"></i>&nbsp;Export Excel For Spare Inventory</a>
                <hr />
                <h3 style="font-weight:600;">Import Excel Files </h3> 
                <form id="import_spare_invent_file" name="import_tire_file">
                @csrf
                   <span id="tyre_msg_response"></span>
                  <div class="control-group" id="fields">
                        <label class="control-label" for="field1">
                            Browse Files
                        </label>
                        <div class="controls">
                            <div class="entry input-group col-xs-3">
                                <input class="btn btn-primary" name="spare_invent_files"  id="spare_invent_files" type="file"  accept=".csv" required>
                                <span class="input-group-btn">&nbsp;&nbsp;
                                    <button class="btn btn-success btn-add" type="submit" id="import_spare_invent_btn">
                                        Import  Sapre Inventory
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
            <a href="../vendors/index.html" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang('messages.Home')</a>
            <a href="../vendors/internationalization_fallback.html" class="breadcrumb-item">@lang('messages.Seller')</a>
            <span class="breadcrumb-item active">@lang('messages.ProductsList') </span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>   
</div>
@stop
@push('scripts')
<script src="{{ asset('validateJS/admin.js') }}"></script>
<script src="{{ asset('validateJS/products.js') }}"></script>
<script src="{{ asset('validateJS/seller.js') }}"></script>
<script src="{{ url('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ url('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>
@endpush

