@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="card" style="margin-bottom:10px;" >
    <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-filter"></i>&nbsp;@lang('messages.Filter')</h6>
    </div>
    <div class="content">
        <div id="filter-panel">
            <div class="panel panel-default">
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
                            <button type="text" id="search_order_date" class="btn btn-warning" >@lang('messages.Search')&nbsp;<span class="glyphicon glyphicon-search"></span></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;Car Revision Services Booking List</h6>
    </div>
    <div class="card-body" id="car_maintinance_ColWrap">
        <table class="table"  id="manage_list">
            <thead>
                <tr>
                    <th>SN.</th>
                    <th>Customer Name (#id)</th>
                    <th>Workshop Owner</th>
                    <th>Service Name</th>
                    <th>For Booking Date</th>
                    <th>Status</th>
                    <th>Booking Date</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
        </table>     
    </div>
</div>
<div class="modal" id="view_service_detail">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Service Deatils</h4>
                <hr />
            </div>
            <div id="err_response"></div>
            <div id="service_response">

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
            <span class="breadcrumb-item active"> Users List </span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop
@push('scripts')
<script src="{{ asset('validateJS/admin.js') }}"></script>
<script src="{{ url('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ url('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>
<script>
$(document).ready(function(){
    $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });
    $('#manage_list').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100, 1000],
        ajax:{
            url: "{{ url('/admin_ajax/get_revision_servicebooking') }}",
            type: 'GET',
            data: function (d) {
                d.start_date = $('#start_date').val();
                d.end_date = $('#end_date').val();
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
            data: 'company_name',
            name: 'company_name'
        },
        {
            data: 'category_name',
            name: 'category_name',
        },
        {
            data: 'booking_date',
            name: 'booking_date'
        },
        {
            /* data: 'status',
            render: function(data) {
                if(data == "P") {
                    return '<span class="badge badge-info">pending </span>' 
                } else if(data == "C") {
                    return '<span class="badge badge-primary">Complete</span>'
                }else {
                    return '<span class="badge badge-warning">Dispatched</span>'
                }
            } */
            data: 'show_status',
            name: 'show_status',
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
        $('#manage_list').DataTable().draw(true);
    });
    $('#manage_list').on('draw.dt', function() {
        $('[data-toggle="tooltip"]').tooltip();
    })
    $(document).on('change', '#change_booking_status', function(e) {
        e.preventDefault();
        var booking_status = $('#change_booking_status').val();
        var booking_id = $(this).data('bookingid');
        if(booking_status != "") {
            var con = confirm("Are you sure want to change status ?");	
            if(con == true){
                $.ajax({
                    url:base_url+"/admin_ajax/change_booking_status",
                    data:{booking_status:booking_status, booking_id : booking_id},
                    method:"GET",
                    success: function(data){
                        var parseJson = jQuery.parseJSON(data);
                        $("#msg_response_popup").modal('show');
                        $("#msg_response").html(parseJson[0].msg);
                    }
                }); 
            } else {
                return false;
            }
        }
    });
});
</script>
@endpush


