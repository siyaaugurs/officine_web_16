@extends('layouts.master_layouts')
@section('content')
<style>
.container{ padding:15px; }
 </style>
<input type="hidden" name="page" id="page" value="{{ $page }}" />
    <!-- Page length options -->
    @if(Session::has('msg'))
      {!! session::get('msg') !!}
    @endif

@if($type != 0)   
<div class="card">
    <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;Details</h6>
       <!-- <a href='{{ url("products/add_group") }}' class="btn btn-primary" style="color:white;">Add New Category &nbsp;<span class="glyphicon glyphicon-plus"></span></a>-->
        </div>
	<div class="card-body" id="mot_interval_body" style="overflow:auto;">
       <table class="table table-bordered">
         <tr>
           <th>Makers</th>
           <td><?php if(!empty($model->makers_name)) echo $model->makers_name;  echo "N/A"; ?></td>
         </tr>
         <tr>
           <th>Model</th>
           <td><?php if(!empty($model->Modello)) echo $model->Modello." >>"; else echo "N/A"; ?>
		   <?php if(!empty($model->ModelloAnno)) echo $model->ModelloAnno; else echo "N/A"; ?></td>
         </tr>
         <tr>
           <th>Version</th>
           <td><?php if(!empty($version->Versione)) echo $version->Versione; ?> <?php if(!empty($version->ModelloCodice)) echo $version->ModelloCodice; else echo "N/A"; ?></td>
         </tr>
       </table>
    </div>
</div>
@endif
<div class="card" style="margin-bottom:10px;">
    <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-plus"></i>&nbsp;Add Product Item</h6>
    </div>
    @if(Session::has('delete_msg'))
      {!! session::get('delete_msg') !!}
    @endif
    <div class="content">
        <div id="filter-panel">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form id="add_kpart_product_item">
                        <div class="row">
                            <input type="hidden" value="{{ $item_repair_id }}" name="item_repair_part_id" id="item_repair_part_id" />
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Product Item Number</label>
                                    <input type="text" class="form-control" placeholder="Product Item Number" name="product_item_number" id="product_item_number" required="required"  />                             
                                </div> 
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <button type="submit" id="add_product_item_btn" class="btn bg-blue ml-3" style="margin-top: 27px;">@lang('messages.Submit') <i class="icon-paperplane ml-2"></i></button>                            
                                </div> 
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <table class="table table-bordered">
                            <tr>
                                <th>S No.</th>
                                <th>Product Item Id</th>
                                <th>Product Brand</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                            @forelse($product_item_details as $product_details)
                               @php
                                 $products_details = sHelper::get_product_info($product_details->item_number);
                               @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ !empty($product_details->item_number) ? $product_details->item_number : "N/A" }}</td>
                                    <td>{{ !empty($products_details->listino) ? $products_details->listino : "N/A" }}</td>
                                    @if(!empty($products_details->kromeda_description))
                                     <td>{{ !empty($products_details->kromeda_description) ? $products_details->kromeda_description : "N/A" }}</td>
                                    @else
                                    <td>{{ !empty($products_details->our_products_description) ? $products_details->our_products_description : "N/A" }}</td>
                                    @endif
                                    <td><a href="#" data-repairid="{{ $product_details->id }}" class="btn btn-danger btn-sm delete_product_item_details"><i class="glyphicon glyphicon-trash"></i></a></td>
                                </tr>
                            @empty
                                <tr>  
                                    <td colspan="5">No record found !!!</td>
                                </tr>
                            @endforelse
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <div id="user_data_body">
       <div class="card" id="user_data_body" style="overflow:auto">
        <div class="card-header bg-light header-elements-inline">
          <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;@lang('messages.productsList')</h6>
        </div>
         <table class="table table-bordered" id="products_list">
            <thead>
                <tr>
                    <th>@lang('messages.SN')</th>
                    <th>@lang('messages.ProductImage')</th>
                    <th>@lang('messages.ProductBrand')</th>
                    <th>@lang('messages.ProductItem')</th>
                    <th>@lang('messages.Description')</th>
                    <th>@lang('messages.Price')</th>
                    <th class="text-center">@lang('messages.Actions')</th>
                </tr>
            </thead>
            <tbody id="products_list_body">
               @forelse($products_response as $product)
                  @php 
                    $p_id = encrypt($product->id);
                    $product->image = sHelper::get_item_repair_image($product->id);
                  @endphp
               <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if(!empty($product->image))
                        <img src="<?php echo $product->image; ?>" class="img img-thumbnail" style="height:50px;"  />
                        @else
                        @endif
                    </td>
                    <td>{{ !empty($product->listino) ? $product->listino : "N/A" }} </td>
                    <td>{{!empty($product->CodiceArticolo) ? $product->CodiceArticolo: "N/A" }}</td>
                    <td>{{ !empty($product->kromeda_description) ? substr($product->kromeda_description , 0 , 25) : "N/A" }}</td>
                    <td>{{ $product->price ?? "N/A" }}</td>
                    <td>
                    &nbsp;
                    <!--<a href='#' data-productsid="{{ $product->id }}" class="btn btn-info get_products_details">
                        <i class="fa fa-eye"></i>
                    </a>-->
                    <a href='#' data-productsid="{{ $product->id }}" class="btn btn-info get_kpart_products_details">
                        <i class="fa fa-eye"></i>
                    </a>
                    </td>
                </tr>
               @empty
                <tr>
                    <td colspan="5">No Products Available</td>
                </tr>    
               @endforelse
            </tbody>
        </table>
    </div>

       <div class="row" style="margin-top:10px;">
          <div class="col-sm-12">
           
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
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="{{ url('/') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
            <a href="#" class="breadcrumb-item">Admin </a>
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
<script>
$(document).ready(function(e) {
    $('#example').DataTable( {
    } );
    $(document).on('click', '.get_kpart_products_details', function(e){
        e.preventDefault();
        var products_id = $(this).data('productsid');
        if(products_id != ""){
            $.ajax({
    			 url:base_url+"/product/get_kpart_products_details",
    			 method:"GET",
    			 data:{products_id:products_id},
    			 success:function(data){
    				$("#products_response").html(data);
    				$("#products_details_modal").modal('show');
    			 }
		    });
        }
    });
	
    $(document).on('click', '.delete_product_item_details', function(e) {
        e.preventDefault();
        var our_maintainance_id = $(this).data('repairid');
        var con = confirm("Are you sure want to delete ?");
        var url = base_url+"/admin/delete_our_car_maintainance_product/"+our_maintainance_id;
        if(con == true) {
            window.location.href = url;
        } else {
            return false;
        }
    })
	
    $(document).on('submit', '#add_kpart_product_item', function(e){
        $('#response').html(" ");
		$("err_response").html(" ");
		$('#add_product_item_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
        e.preventDefault();
        $.ajax({
            url: base_url+"/car_maintinance/add_product_item",
            type: "POST",        
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData:false,  
            success: function(data){
                errorString = '';
                $('#add_product_item_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
                var parseJson = jQuery.parseJSON(data); 
                console.log(parseJson.status);
                if(parseJson.status == 200){
                    $(".close").click();	
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                    setTimeout(function(){ location.reload(); } , 1000);
                } 
                if(parseJson.status == 100){
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                }	  
            } 
        });
    });
} );
</script>
@endpush


