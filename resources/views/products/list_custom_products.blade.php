@extends('layouts.master_layouts')
@section('content')
<style>
.container{ padding:15px; }
 </style>
<input type="hidden" name="page" id="page" value="{{ $page }}" />
    @if(Session::has('msg'))
      {!! session::get('msg') !!}
    @endif
    <div class="row" style="margin-bottom:10px;">
        <div class="col-sm-12">
            <a href="javascript::void();" class="btn btn-warning" id="import_export_spare_sample_format" style="color:white;">Import / Export Spare Product Files&nbsp;<span class="fa fa-file-excel-o"></span></a>&nbsp;&nbsp;&nbsp;
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
                        <div class="row" style="margin-top:15px;">
                            <div class="col-sm-6">
                                <div class="form-group">
                                <input type="text" class="form-control" name="ean_number"  placeholder="EAN NUMBER" id="ean_number">                               
                                </div>
                            </div>	
                            <div class="col-sm-6">
                                <a href='#' id="search_spare_part_ean" class="btn btn-warning" style="color:white;">Search &nbsp;<span class="glyphicon glyphicon-search"></span></a> 
                            </div>		
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row" style="margin-top:15px;">
                            <div class="col-sm-6">
                                <div class="form-group">
                                <input type="text" class="form-control" name="item_number"  placeholder="ITEM NAME" id="item_number">                               
                                </div>
                            </div>	
                            <div class="col-sm-6">
                                <a href='#' id="search_spare_part_item" class="btn btn-warning" style="color:white;">Search &nbsp;<span class="glyphicon glyphicon-search"></span></a> 
                            </div>		
                        </div>
                    </div>
                </div>
            </div>  
        </div>
    </div>
    <div id="user_data_body">
       @include('products.component.custom_products_list' , ['custom_products'=>$custom_products])
       <div class="row" style="margin-top:10px;">
          <div class="col-sm-12">
             {{ $custom_products->links() }}
          </div>
        </div>
    </div>
    <div id="final_products_response"></div>
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

<div class="modal" id="import_export_spare_sample_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Import / Export Spare Products </h4>
                <hr />
            </div>
            <div class="card-body">
                <a href="<?php echo url('export/custom_spare_product_list') ?>" class="btn btn-warning"><i class="fa fa-download"></i>&nbsp;Export Custom Spare Product</a>
                <hr />
                <h3 style="font-weight:600;">Import Spare Product Excel Files </h3> 
                <form id="import_spare_product_file">
                @csrf
                  <div class="control-group" id="fields">
                        <span id="rim_msg_response"></span>
                        <label class="control-label" for="field1">
                            Browse Spare Products Excel Files
                        </label>
                        <div class="controls">
                            <div class="entry input-group col-xs-3">
                                <input class="btn btn-primary" name="custom_spare_products_file" type="file"  required>
                                <span class="input-group-btn">
                        &nbsp;&nbsp;
                        <button class="btn btn-success btn-add" type="submit" id="c_spare_btn">
                            Import Spare File
                            <span class="glyphicon glyphicon-import"></span>
                        </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row" style="margin-top:15px;">
                 <div class="col-sm-12">
                    <p style="color:#F00; font-weight:600; font-size:18px;">Status = A (For publish) , Status = P (For Save in draft )</p>
                    <p style="color:#F00; font-weight:600; font-size:18px;">Pair Status = 0 ( Not Sell in pair ) , Status = 1 (   Sell in pair)</p>
                 </div>
               </div>
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
<script>
    $(document).ready(function(e){
        $(document).on('click','.get_custom_products_details',function(e){
            e.preventDefault();
            var products_id = $(this).data('productsid');
            if(products_id != ""){
                $.ajax({
                    url:base_url+"/product/get_custom_products_details",
                    method:"GET",
                    data:{products_id:products_id},
                    success:function(data){
                        $("#products_response").html(data);
                        $("#products_details_modal").modal('show');
                    }
                });
            }
        });

        $(document).on('click','#search_spare_part_ean',function(e){
            e.preventDefault();
            var spare_ean_number = $("#ean_number").val();
            if(spare_ean_number != ""){
                $.ajax({
                    url: base_url+"/products_ajax/get_spare_parts_by_ean",
                    method: "GET",
                    data: {spare_ean_number:spare_ean_number},
                    success: function(data){
                        $("#user_data_body").html(data);
                    }
                }); 
            }
        });  

        $(document).on('click','#search_spare_part_item',function(e){
            $('#search_spare_part_item').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
            e.preventDefault();
            var spare_item_name = $("#item_number").val();
            if(spare_item_name != ""){
                $.ajax({
                    url: base_url+"/products_ajax/get_spare_parts_by_item",
                    method: "GET",
                    data: {spare_item_name:spare_item_name},
                    success: function(data){
                        $('#search_spare_part_item').html(' Search <i class="fa fa-search"></i>').attr('disabled' , false);
                        $("#user_data_body").html(data);
                    }
                }); 
            }
                
        }); 

         $(document).on('click', '.delete_custom_product', function(e){
		    e.preventDefault();
            var product_id = $(this).data('productid');
            var con = confirm("Are you sure want to delete?");
            var url = base_url+"/products/remove_custom_produts/"+product_id;
            if(con == true) {
                window.location.href = url;
            } else {
                return false;
            }
        }); 
    });
</script>
@endpush


