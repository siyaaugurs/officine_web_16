@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="content">
    <!-- Page length options -->
    <div class="card" id="user_data_body">
    <table class="table datatable-show-all">
            <thead>
                <tr>
                    <th>SN.</th>
                    <th>Customer Name (#id)</th>
                    <th>Service Name</th>
                    <th>For Booking Date</th>
                    <th>Status</th>
                    <th>Booking Date</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
               @forelse ($booked_services as $service)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $service->f_name."(". $service->users_id.")" }}</td>
                    <td>{{ $service->category_name ? $service->category_name : 'N/A' }}</td>
                    <td>{{ $service->booking_date }}&nbsp; {{ $service->start_time }}</td>
                    <td>
                        @if($service->status == "P")
                            <span class="badge badge-danger" id="s_status">Pending</span>
                        @elseif($service->status == "C")
                            <span class="badge badge-success" id="s_status">Complete</span>
                        @elseif($service->status == "D")
                            <span class="badge badge-info" id="s_status">Job Completed</span>
                         @endif 
                    </td>
                    @php
                        $creted_at = sHelper::convert_italian_time($service->created_at);
                    @endphp
                    <td>{{ $creted_at}}</td>
                    <td class="text-center">
                        <a href="#" data-toggle="tooltip" data-serviceid="<?php echo $service->id; ?>" data-placement="top" title="View Services" class="btn btn-primary view_booking_service btn-xs"><i class="fa fa-eye" ></i></a> 
                        @if($service->status == "D")
                            <!-- <a href="#" data-id="<?php echo $service->id; ?>" data-status="<?php echo $service->status; ?>" class="btn btn-info change_services_status btn-xs"><i class="fa fa-truck"></i></a> -->
                            <span class="btn btn-info">Job Completed</span>
                        @elseif($service->status == "C")
                            <a href="#" data-toggle="tooltip" data-placement="top" title="Click To Dispatch" data-id="<?php echo $service->id; ?>" data-status="<?php echo $service->status; ?>" class="btn btn-danger change_services_status btn-xs">Complete Job</a>
                        @endif
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
    <!-- /page length options -->
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
                            <a href="#" class="breadcrumb-item">Workshop </a>
                            <span class="breadcrumb-item active"> Booked Services</span>
                        </div>
                        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
                    </div>
                    <!--
                    <div class="header-elements d-none">
                        <div class="breadcrumb justify-content-center">
                            <a href="#" class="breadcrumb-elements-item">
                                <i class="icon-comment-discussion mr-2"></i>
                                Support
                            </a>
                        </div>
                    </div>
                    -->
                </div>
@stop
@push('scripts')
<script src="{{ asset('validateJS/admin.js') }}"></script>
<script src="{{ asset('validateJS/workshop.js') }}"></script> 
<script src="{{ url('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ url('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>
@endpush


