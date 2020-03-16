@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="content">
    @if($page == "admin_service_booking_list")
        <div class="row">
            <div class="col-lg-3">
                <div class="card bg-pink-400">
                    <a href="{{url('admin/car_revision_servicebooking')}}" style="text-decoration:none; color:#FFF;">
                        <div class="card-body">
                            <div class="d-flex">
                                <h3 class="font-weight-semibold mb-0"><?= $revision_service_list->count() ?></h3>
                                <!-- <span class="badge bg-teal-800 badge-pill align-self-center ml-auto">14.17%</span> -->
                            </div>
                            <div>
                                Car Revision Service <div class="font-size-sm opacity-75"></div>
                            </div>
                        </div>
                    </a>
                    <div class="chart" id="server-load"></div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card bg-green-400">
                    <a href="{{url('admin/service_booking_list/23')}}" style="text-decoration:none; color:#FFF;">
                        <div class="card-body">
                            <div class="d-flex">
                                <h3 class="font-weight-semibold mb-0"><?= $revision_service_list->count() ?></h3>
                                <!-- <span class="badge bg-teal-800 badge-pill align-self-center ml-auto">14.17%</span> -->
                            </div>
                            <div>
                                Tyre Service booking<div class="font-size-sm opacity-75"></div>
                            </div>
                        </div>
                    </a>
                    <div class="chart" id="server-load"></div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card bg-blue-400">
                    <a href="{{url('admin/service_booking_list/13')}}" style="text-decoration:none; color:#FFF;">
                        <div class="card-body">
                            <div class="d-flex">
                                <h3 class="font-weight-semibold mb-0">10</h3>
                            </div>
                            <div>
                                Wracker Service<div class="font-size-sm opacity-75"></div>
                            </div>
                        </div>
                    </a>
                    <div class="chart" id="server-load"></div>
                </div>
            </div>
        </div>
    @else
    @endif
    <div class="card" id="user_data_body">
        <table class="table datatable-show-all">
            <thead>
                <tr>
                    <th>SN.</th>
                    <th>Customer Name (#id)</th>
                    <th>Workshop Owner</th>
                    <th>Service Name</th>
                    @if($booking_type == 13)
                        <th>Wracker Service Type</th>
                    @else 
                    @endif
                    <th>For Booking Date</th>
                    <th>Start / End Time</th>
                    <th>Status</th>
                    <th>Created Date</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($service_booking as $booking)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $booking->customer_name }} (<a target="_blank" href='{{ url("admin/company_profiles/$booking->customer_ids") }}'> {{$booking->customer_id}}</a>)</td>
                    <td>{{ $booking->workshop_name }}</td>
                    <td>{{ $booking->service_name }}</td>
                    @if($booking_type == 13)
                        @if($booking->wrecker_service_type == 1)
                            <td>Service Booking for appointment</td>
                        @else 
                            <td>Service Booking For Emergency</td>
                        @endif
                    @else 
                    @endif
                    <td>{{ $booking->booking_date }}</td>
                    <td>{{ $booking->start_time."/".$booking->end_time }}</td>
                    <td>
                        <select id="change_booking_status" data-bookingid="{{ $booking->id }}" class="form-control btn btn-default" style="width: 145px;">
                            <option value="" hidden="hidden">--Select Option--</option>
                            <option value="P" <?= $booking->status == "P" ? 'selected' : '' ?>>Pending</option>
                            <option value="CA" <?= $booking->status == "CA" ? 'selected' : '' ?>>Canceled</option>
                            <option value="C" <?= $booking->status == "C" ? 'selected' : '' ?>>Paid</option>
                            <option value="D" <?= $booking->status == "D" ? 'selected' : '' ?>>Work Completed</option>
                        </select>
                    </td>
                    <td>{{ $booking->created_at }}</td>
                    <td class="text-center">
                        <!-- <a href="javascript::void()" data-toggle="tooltip" data-serviceid="<?php echo $booking->id; ?>" data-placement="top" title="View Services" class="btn btn-primary view_booking_services btn-xs"><i class="fa fa-eye"></i></a> -->
                        <a href='{{ url("admin/service_order_details/$booking->id") }}' target="_blank" data-toggle="tooltip" data-serviceid="<?php echo $booking->id; ?>" data-placement="top" title="View Services" class="btn btn-primary view_booking_services_details btn-xs"><i class="fa fa-eye"></i></a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">Users Not Available</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="modal" id="view_service_detail">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                        class="text-danger fa fa-times"></i></button>
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
    $(document).ready(function(e) {
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
