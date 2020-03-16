@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<style> .container{ padding:15px;} </style>
<!--<div class="card" style="margin-bottom:10px;">
   <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-filter"></i>&nbsp;Filter</h6>
    </div>
    <div class="content">
	    <div id="filter-panel">
            <div class="panel panel-default">
                <div class="panel-body">
                  <form id="customer_search">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <select class="form-control" name="car_makers" id="car_makers">                                  <option value="0">--Select--Makers--Name--</option>
                                    @forelse($cars__makers_category as $category)
                                        <option value="{{ $category->idMarca }}">{{ $category->Marca }}</option>
                                    @empty
                                        <option value="0">No Maker Available </option>
                                    @endforelse
								   
                                </select>                                
                            </div> 
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <select class="form-control" name="car_models" id="car_models">                                    <option value="0">--First--Select--Makers--Name--</option>
                                </select>                                
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <select class="form-control car_version_group" id="version_id" name="car_version" data-action="get_and_save_services_time">
                                    <option value="0">--First--Select--Model--Name--</option>
                                </select>                                
                            </div> 
                        </div> 
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                            <button type="submit" id="search_users_on_hold" class="btn btn-warning" style="color:white;">Search &nbsp;<span class="glyphicon glyphicon-search"></span></button>         
                            </div>
                        </div>
                    </div>
                   </form>
                </div>
            </div>
        </div>  
    </div>
</div>-->
<div class="card" style="margin-bottom:10px;">
   <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp; Ticket List</h6>
    </div>
<div class="content">
    <div id="user_data_body" style="overflow:auto">
      <div class="card-header header-elements-inline">
        @include('customer_report.component.support_tickets' )
      </div>
    </div>
    <div class="row" style="margin-top:10px;" id="pagination_row">
          <div class="col-sm-12">
             {{-- $tickets->links() --}}
          </div>
        </div>
</div>
</div>
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>@lang('messages.Home')</a>
            <a href="#" class="breadcrumb-item">@lang('messages.Admin')</a>
            <span class="breadcrumb-item active">Support Ticket List</span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop
@push('scripts')
<script src="{{ asset('validateJS/customer_reports.js') }}"></script>
<script src="{{ asset('validateJS/products.js') }}"></script>
<script src="{{ asset('validateJS/admin.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/datatables_basic.js') }}"></script>
<script>
$(document).ready(function() {
    $('#DataTables_Table_2').DataTable();
} );
</script>
@endpush


