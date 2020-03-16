@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="content">
    <!-- Page length options -->
    @if(Session::has('msg'))
      {!! session::get('msg') !!}
    @endif

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>	
            <strong>{{ $message }}</strong>
        </div>
    @endif
    <div class="row card" style="margin-bottom:10px;" >
    </div>
    <div class="row card" style="margin-bottom:10px;" >
<div class="container">
    <h4>Search By date</h4>
    <div class="row form-group" id="">
        <div class="col-sm-6">
            <label>From :</label>
            <input type="text" name="start_date" id="start_date" class="form-control datepicker" placeholder="Please select start date" readonly>
        </div>
        <div class="col-sm-6">
            <label>To :</label>
            <input type="text" name="end_date" id="end_date" class="form-control datepicker" placeholder="Please select end date" readonly>
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
</div>
    <div class="card" style="overflow:auto" id="user_data_body">
        <table class="table" id="manage_seller_order_list">
            <thead>
                <tr>
                    <th>SN.</th>
                    <th>Customer Name</th>
                    <th>Order Date</th>
                    <th>Transaction Id</th>
                    <th>Total Products</th>
                    <th>Total Price</th>
                    <th>Created At</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
        </table>
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
<div class="modal" id="view_seller_order">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Order Deatils</h4>
                <hr />
            </div>
            <div id="err_response"></div>
            <div id="seller_order_response">

            </div>
        </div>
        <div class="modal-footer">       
        </div>
    </div>
</div>
<div class="modal" id="view_seller_product_description">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Products Description</h4>
                <hr />
            </div>
            <div id="err_response"></div>
            <div id="seller_description_response">

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
            <span class="breadcrumb-item active">Order List </span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>   
</div>
@stop
@push('scripts')
<script>
$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip(); 
    $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });
    $('#manage_seller_order_list').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100, 1000],
        ajax:{
            url: "{{ url('/seller_ajax/get_seller_order_lists') }}",
            type: 'GET',
            data: function (d) {
                d.start_date = $('#start_date').val();
                d.end_date = $('#end_date').val();
                d.status = $('#status').val();
            },
        },
        columns:[
        {
            data: 'sNo',
            name: 'sNo'
        },
        {
            data: 'f_name',
            name: 'f_name'
        },
        {
            data: 'order_date',
            name: 'order_date'
        },
        {
            data: 'transaction_id',
            name: 'transaction_id'
        },
        {
            data: 'no_of_products',
            name: 'no_of_products'
        },
        {
            data: 'total_price',
            name: 'total_price'
        },
        {
            data: 'created_at',
            name: 'created_at'
        },
        {
            data: 'action',
            name: 'action',
            orderable: false, 
            searchable: false
        }
    ]
    });
    $('#search_order_date').click(function(){
        $('#manage_seller_order_list').DataTable().draw(true);
    });
    $('#manage_seller_order_list').on('draw.dt', function() {
        $('[data-toggle="tooltip"]').tooltip();
    })
});
</script>
<script src="{{ asset('validateJS/admin.js') }}"></script>
<script src="{{ asset('validateJS/products.js') }}"></script>
<script src="{{ asset('validateJS/seller.js') }}"></script>
<script src="{{ url('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ url('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>
@endpush

