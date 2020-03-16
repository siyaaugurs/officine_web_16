@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="row" style="margin-bottom:10px;">
  <div class="col-sm-12">
    <a href='{{ url("workshop/products_asseble") }}' class="btn btn-primary" style="color:white;">Add New Products&nbsp;<span class="glyphicon glyphicon-plus"></span></a>
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
                                   <option value="@if(!empty($makers->idMarca)){{ $makers->idMarca }} @endif">@if(!empty($makers->Marca)){{ $makers->Marca }} @endif</option>
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
                            <select class="form-control car_version_group" name="car_version" data-action="get_and_save">
                                <option value="0">@lang('messages.firstSelectModels')</option>
                            </select>                                
                        </div> 
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                            <select class="form-control" name="groups_item" id="group_item" data-action="get_and_save_products_item">
                              <option value="0">@lang('messages.firstSelectVersion')</option>
                            </select>                                
                        </div>
                      </div>
                   </div>
                   <div class="row">
                      <div class="col-sm-6">
                        <div class="form-group">
                            <select class="form-control" name="item_id" id="item_id" data-action="get_items">
                                <option value="0">@lang('messages.firstSelectGroupItem')</option>
                            </select>                                
                        </div> 
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                          <a href='#' id="search_products_asseble" class="btn btn-warning" style="color:white;">Search &nbsp;<span class="glyphicon glyphicon-search"></span></a>                                 
                        </div>
                      </div>
                   </div>
                </div>
            </div>
        </div>  
  </div>
</div>
    <!-- Page length options -->
    <div class="card" id="user_data_body">
        @include('workshop.component.products_asseble' , ['products'=>$products])
        <div class="row" style="margin-top:10px;" id="row_paging_table">
          <div class="col-sm-12">
             {{ $products->links() }}
          </div>
        </div>
    </div>
    <!-- /page length options -->
<!--Products details modal pop -->
<div class="modal" id="products_assemble_details_modal">
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
            <span class="breadcrumb-item active"> Products Assemble List </span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop
@push('scripts')
<script src="{{ url('validateJS/assemble_service.js') }}"></script>
 <script src="{{ url('validateJS/products.js') }}"></script>
<link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" />
<script src="{{ asset('validateJS/admin.js') }}"></script>
<script src="{{ url('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ url('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>
<script src="{{ url("") }}validateJS/services.js"></script>
@endpush


