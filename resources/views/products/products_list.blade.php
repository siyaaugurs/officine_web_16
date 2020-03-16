@extends('layouts.master_layouts')
@section('content')
<style>
.container{ padding:15px; }
 </style>
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="row" style="margin-bottom:10px;">
    <div class="col-sm-12">
        <a href="javascript::void();" class="btn btn-warning" id="import_export_kromeda_spare" style="color:white;">Import / Export Spare Product Files&nbsp;<span class="fa fa-file-excel-o"></span></a>
    </div>
</div>
<div class="card" style="margin-bottom:10px;">
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
                            <select class="form-control" name="car_makers" id="car_makers">
                               <option value="0">@lang('messages.selectMaker')</option>
                                 @foreach($cars__makers_category as $makers)
                                   <option value="@if(!empty($makers->idMarca)){{$makers->idMarca}}@endif">@if(!empty($makers->Marca)){{ $makers->Marca }} @endif</option>
                                 @endforeach 
                            </select>                                
                        </div> 
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                            <select class="form-control" name="car_models" id="car_models">
                                 <option value="0">@lang('messages.firstSelectMakers')</option>
                            </select>                                
                        </div>
                      </div>
                   </div>
                   <div class="row">
                      <div class="col-sm-6">
                        <div class="form-group">
                            <select class="form-control car_version_group" name="car_version" data-action="get_and_save_n1">
                                <option value="0">@lang('messages.firstSelectModels')</option>
                            </select>                                
                        </div> 
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                            <select class="form-control groups_n1" name="car_group_version" id="group_n1" data-action="get_and_save_products_item">
                              <option value="0">@lang('messages.firstSelectVersion')</option>
                            </select>                                
                        </div>
                      </div>
                   </div>
                   <div class="row">
                      <div class="col-sm-6">
                        <div class="form-group">
                            <select class="form-control groups" name="car_group" id="group_n2" data-action="get_and_save_products_item">
                              <option value="0">@lang('messages.firstSelectVersion')</option>
                            </select>                                
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                            <select class="form-control" name="item_id" id="item_id" data-action="get_items_new">
                                <option value="0">@lang('messages.firstSelectGroupItem')</option>
                            </select>                                
                        </div> 
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                          <a href='#' id="search_products_by_group" class="btn btn-warning" style="color:white;">Search &nbsp;<span class="glyphicon glyphicon-search"></span></a>                                 
                        </div>
                      </div>
                   </div>
                </div>
            </div>
        </div>  
        <hr /> 
        <div id="filter-panel">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form class="form-inline" role="form">
                        <div class="form-group">
                           <input type="text" name="products_id" id="products_id" placeholder="@lang('messages.productsID')" class="form-control col-sm-12">                                 
                        </div>&nbsp;&nbsp;&nbsp;
                        <div class="form-group">
                          <a href='#' id="search_products_by_id" class="btn btn-warning" style="color:white;">Search &nbsp;<span class="glyphicon glyphicon-search"></span></a>                                 
                        </div>
                    </form>
                </div>
            </div>
        </div>
  </div>
</div>
    <!-- Page length options -->
    @if(Session::has('msg'))
      {!! session::get('msg') !!}
    @endif
    <div id="user_data_body">
       @include('products.component.products_list' , ['products'=>$products])
       <div class="row" style="margin-top:10px;">
          <div class="col-sm-12">
            {{  $products->links() }}
          </div>
        </div>
    </div>
    <div id="final_products_response"></div>
    
    <!-- /page length options -->   
<!--Products details modal pop -->
<div class="modal" id="products_details_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Products Details</h4>
                <hr />
            </div>
            <!-- Modal body -->
            <div id="products_response"></div>
        </div>
        <div class="modal-footer">       
        </div>
    </div>
</div>
<!--End-->

<!--Products All N3 modal pop -->
<div class="modal" id="get_all_n3_modal_popup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>

                <h4 class="modal-title" id="myModalLabel">N3 Category Details</h4>

                <hr />

            </div>

            <!-- Modal body -->

            <div id="n3_products_response"></div>

        </div>

        <div class="modal-footer">       

        </div>

    </div>

</div>
<div class="modal" id="import_export_spare_sample_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Import / Export Spare Products </h4>
                <hr />
            </div>
            <div class="card-body">
                <a href="<?php echo url('export/kromeda_spare_product_list') ?>" class="btn btn-warning"><i class="fa fa-download"></i>&nbsp;Export Kromeda Spare Product</a>
                <hr />
                <h3 style="font-weight:600;">Import Spare Product Excel Files </h3> 
                <form id="import_kromeda_spare_product_file" name="import_spare_product_file">
                @csrf
                  <div class="control-group" id="fields">
                        <span id="rim_msg_response"></span>
                        <label class="control-label" for="field1">
                            Browse Spare Products Excel Files
                        </label>
                        <div class="controls">
                            <div class="entry input-group col-xs-3">
                                <input class="btn btn-primary" name="spare_products_file" type="file"  required>
                                <span class="input-group-btn">
                        &nbsp;&nbsp;
                        <button class="btn btn-success btn-add" type="submit" id="k_spare_btn">
                            Import Spare File
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
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
            <a href="{{ url('/') }}" class="breadcrumb-item">Admin </a>
            <a href="#" class="breadcrumb-item">Products </a>
            <span class="breadcrumb-item active"> Products List </span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop
@push('scripts')
 <script src="{{ url('validateJS/products_05_08.js') }}"></script>
<link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" />
<script src="{{ asset('validateJS/admin.js') }}"></script>
<script src="{{ url('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ url('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>
<script src="{{ asset('validateJS/import_export.js') }}"></script>
@endpush


