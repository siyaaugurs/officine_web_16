@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="row" style="margin-bottom:10px;">
  <div class="col-sm-12">
    <a href='{{ url("products") }}' class="btn btn-primary" style="color:white;">Add New Products&nbsp;<span class="glyphicon glyphicon-plus"></span></a>
  </div>
</div>
<div class="row card" style="margin-bottom:10px;" >
    <style>
 .container{
    padding:15px;
}
 </style>

<div class="container">
	    <div id="filter-panel">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form class="form-inline" role="form">
                        <div class="form-group">
                            <select class="form-control" name="car_makers" id="car_makers">
                                <option>--Select-- Makers--Name--</option>
                                 @foreach($cars__makers_category as $makers)
                                   <option value="{{ $makers->idMarca }}">{{ $makers->Marca }}</option>
                                 @endforeach 
                            </select>                                
                        </div> &nbsp;&nbsp;&nbsp;
                        <div class="form-group">
                            <select class="form-control" name="car_models" id="car_models">
                                <option>--First--Select--Makers--Name--</option>
                            </select>                                
                        </div>&nbsp;&nbsp;&nbsp;
                        <div class="form-group">
                            <select class="form-control car_version_group" name="car_version" id="car_version">
                                <option>--First--Select--Model--Name--</option>
                            </select>                                
                        </div>&nbsp;&nbsp;&nbsp;
                        <div class="form-group">
                            <select class="form-control" name="car_group_version" id="group_item" data-action="get_and_save">
                                <option>--First--Select--Version--Name--</option>
                            </select>                                
                        </div>&nbsp;&nbsp;&nbsp;
                        <div class="form-group">
                          <a href='#' id="search_products_by_group" class="btn btn-warning" style="color:white;">Search &nbsp;<span class="glyphicon glyphicon-search"></span></a>                                 
                        </div>
                    </form>
                </div>
            </div>
        </div>       
  </div>
</div>
<div class="content">
    <!-- Page length options -->
    @if(Session::has('msg'))
      {!! session::get('msg') !!}
    @endif
    <div class="card" id="user_data_body" style="overflow:auto">
        <table class="table">
            <thead>
                <tr>
                    <th>SN.</th>
                    <th>Maker</th>
                    <th>Model</th>
                    <th>Version</th>
                    <th>Group</th>
                    <th>Product Name ( ID)</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
               @forelse($products as $product)
                  @php 
                  $maker_name = kRomedaHelper::get_maker_name($product->car_makers_name);
                  $model_name = kRomedaHelper::get_model_name($product->car_makers_name , $product->models_name);
                  $versions = kRomedaHelper::get_version_name($product->models_name , $product->car_version_id);
                   $group_name = kRomedaHelper::get_group_name($product->category_id);
                  @endphp
                  
               <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $maker_name->Marca }}</td>
                    <td>{{ $model_name->Modello }}</td>
                    <td>{{ $versions->Versione }}</td>
                    <td>{{ $group_name }}</td>
                    <td>{{ $product->products_name ?? "N/A" }} ( @if(!empty($product->CodiceOE)){{ $product->CodiceOE  }}@endif )</td>
                    <td>{{ $product->products_description ?? "N/A" }}</td>
                    <td>{{ $product->price ?? "N/A" }}</td>
                    <td>
                      <?php
						if(!empty($product->products_status)){
						   if($product->products_status == "P"){
							   ?>
							   <a href="#" data-productsid="<?php if(!empty($product->id))echo $product->id; ?>" class="change_products_status" data-type='1' data-status="A"><i class="fa fa-toggle-off"></i></a>
							   <?php
							 }
						   else if($product->products_status == "A"){
							   ?>
							   <a href="#" data-type='1'  class="change_products_status" data-productsid="<?php if(!empty($product->id))echo $product->id; ?>" data-status="P"><i class="fa fa-toggle-on"></i></a>
							   <?php
							 }	 
						 }
						?>
                    </td>
                    <td><a href='{{ url("products/add_new_products/$product->id") }}' class="btn btn-primary">
                        <i class="fa fa-pencil"></i>
                    </a>
                    &nbsp;<a href='#' data-productsid="{{ $product->id }}" class="btn btn-info get_products_details">
                        <i class="fa fa-eye"></i>
                    </a>
                    </td>
                </tr>
               @empty
                <tr>
                    <td>No Products Available</td>
                </tr>    
               @endforelse
            </tbody>
        </table>
        <div class="row" style="margin-top:10px;">
          <div class="col-sm-12">
             {{ $products->links() }}
          </div>
        </div>
      
    </div>
    <!-- /page length options -->
   
</div>
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
 <script src="{{ url('validateJS/products.js') }}"></script>
<link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" />
<script src="{{ asset('validateJS/admin.js') }}"></script>
<script src="{{ url('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ url('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>
<script>
$(document).ready(function() {
    $('#example').DataTable( {
    } );
} );
</script>
@endpush


