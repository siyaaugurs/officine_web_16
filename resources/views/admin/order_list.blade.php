@extends('layouts.master_layouts')
@section('content')
<style>
.btn-group > .btn + .dropdown-toggle {
    padding-left: 8px;
    padding-right: 8px;
}
.btn-group > .btn:last-child:not(:first-child), .btn-group > .dropdown-toggle:not(:first-child) {
    border-bottom-left-radius: 0;
    border-top-left-radius: 0;
}
.btn-primary {
    color: #fff;
    background-color: #1e91cf;
    border-color: #197bb0;
}
.btn-group > .btn, .btn-group-vertical > .btn {
    position: relative;
    float: left;
}
.btn-group .btn + .btn, .btn-group .btn + .btn-group, .btn-group .btn-group + .btn, .btn-group .btn-group + .btn-group {
    margin-left: -1px;
}
.panel-heading h6 {
	display: inline-block;
}
.panel-primary .panel-heading {
	color: #1e91cf;
	border-color: #96d0f0;
	background: white;
}
.panel-default {
	border: 1px solid #dcdcdc;
	border-top: 1px solid #dcdcdc;
}
.panel-default .panel-heading {
	color: #4c4d5a;
	border-color: #dcdcdc;
	background: #f6f6f6;
	text-shadow: 0 -1px 0 rgba(50,50,50,0);
    height: 44px;
    border-bottom:1px solid #ddd;
}
.panel .panel-heading {
    padding: 10px;
}
.panel-body {
    padding: 15px;
}
#manage_order_list_length , #manage_order_list_filter{ margin:15px;}
</style>
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="container-fluid"> 
    <div class="row">
        <div id="filter-order" class="col-md-12">
            <div class="panel panel-default card">
                <div class="panel-heading">
                    <h6 class="panel-title"><i class="fa fa-filter"></i>&nbsp;@lang('messages.Filter')</h6>
                </div>
                <div class="panel-body">
                    <div class="row form-group" id="">
                        <div class="col-sm-6">
                            <label>@lang('messages.From') :</label>
                            <input type="text" name="start_date" id="start_date" class="form-control datepicker" placeholder="@lang('messages.PleaseSelectStartDate')" readonly>
                        </div>
                        <div class="col-sm-6">
                            <label>@lang('messages.To') :</label>
                            <input type="text" name="end_date" id="end_date" class="form-control datepicker" placeholder="@lang('messages.PleaseSelectEndDate')" readonly>
                        </div>
                    </div>
                    <div class="row form-group" id="">
                        <div class="col-sm-6">
                            <select name="status" id="status" class="form-control">
                                <option value="" hidden="hidden">@lang('messages.SelectStatus')</option>
                                <option value="I">@lang('messages.InProcess')</option>
                                <option value="D">@lang('messages.Dispatched')</option>
                                <option value="IN">@lang('messages.Intransit')</option>
                                <option value="DE">@lang('messages.Delivered')</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <button type="text" id="search_order_date" class="btn btn-warning" >@lang('messages.Search')&nbsp;<span class="glyphicon glyphicon-search"></span></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--<div class="row card" style="margin-bottom:10px;" >
<div class="container">
    <h4>Search By date</h4>
    <div class="row form-group" id="">
        <div class="col-sm-6">
            <label>From :</label>
            <input type="text" name="start_date" id="start_date" class="form-control datepicker" placeholder="Please select start date">
        </div>
        <div class="col-sm-6">
            <label>To :</label>
            <input type="text" name="end_date" id="end_date" class="form-control datepicker" placeholder="Please select end date">
        </div>
    </div>
    <h4>Search By Status</h4>
    <div class="row form-group" id="">
        <div class="col-sm-6">
            <select name="status" id="status" class="form-control">
                <option value="" hidden="hidden">--Select Status--</option>
                <option value="I">In Process</option>
                <option value="D">Dispatched</option>
                <option value="IN">Intransit</option>
                <option value="DE">Delivered</option>
            </select>
        </div>
        <div class="col-sm-6">
            <button type="text" id="search_order_date" class="btn btn-warning" >Search&nbsp;<span class="glyphicon glyphicon-search"></span></button>
        </div>
    </div>    
  </div>
</div>-->
    <!-- Page length options -->
<div class="container-fluid"> 
    <div class="row">    
     <div class="col-md-12">
       <div class="card" id="user_data_body" style="overflow:auto">
    <div class="card-header bg-light header-elements-inline">
            <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;Order List</h6>
        </div>
    <!--<table class="table" id="manage_order_list">
            <thead>
                <tr>
                    <th>@lang('messages.SN')</th>
                    <th>@lang('messages.TrackingId')</th>
                    <th>@lang('messages.CustomerName')</th>
                    <th>@lang('messages.TotalPrice')</th>
                    <th>@lang('messages.PaymentStatus')</th>
                    <th>@lang('messages.OrderDate')</th>
                    <th>@lang('messages.OrderStatus')</th>
                    <th>@lang('messages.Actions')</th>
                </tr>
            </thead>
        </table>-->
        <table class="table" id="manage_order_list">
			<thead>
				<tr>
					<th>SN.</th>
					<th>Order Date</th>
					<th>Customer</th>
                    <th>Workshop / Seller </th>
                    <th>Total Price </th>
					<th>Order Status</th>
					<th class="text-center">Actions</th>
				</tr>
			</thead>
		</table>     
    </div>
     </div>
    </div>
  </div>  
    <!-- /page length options -->
<div class="modal" id="view_order">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Order Deatils</h4>
                <hr />
            </div>
            <div id="err_response"></div>
            <div id="order_response">
            </div>
        </div>
        <div class="modal-footer">       
        </div>
    </div>
</div>
<div class="modal" id="view_product_description">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Products Description</h4>
                <hr />
            </div>
            <div id="err_response"></div>
            <div id="description_response">

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
            <a href="{{ url('/') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
            <a href="#" class="breadcrumb-item">Admin </a>
            <span class="breadcrumb-item active"> Order List </span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop
@push('scripts')
<script src="{{ asset('validateJS/admin.js') }}"></script>
<script src="{{ url('validateJS/vendors.js') }}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.12/datatables.min.js"></script>
<script>
   $(function () {
    //var for_type =  $('#select_service_for option:selected').val();
        $('#manage_order_list').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax":{
                     "url": "{{ url('order_agax/get_products_order_lists') }}",
                     "dataType": "json",
                     "type": "GET",
                     "data":function(d){
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                        d.status = $('#status').val();
                     }
                   },
            "columns": [
                { "data": "sNo" },
                { "data": "order_date" },
                { "data": "customer_name" },
                { "data": "workshop_seller" },
                { "data": "total_price" },
                { "data": "order_status" },
                { "data": "action" }
            ]	 
        });

        $(document).on('click' , '#search_order_date' , function(e){
            alert("yes");
              e.preventDefault();
              $('#manage_order_list').DataTable().draw(true);
        });
    });
</script>
@endpush


