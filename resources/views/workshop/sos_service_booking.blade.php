@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="content">
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
                    <!-- <th class="text-center">Actions</th> -->
                </tr>
            </thead>
            <tbody>
                @forelse ($booked_sos as $service)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $service->f_name }}</td>
                        <td>{{ $service->services_name }}</td>
                        <td>{{ $service->booking_date ? $service->booking_date : '' }} &nbsp; {{ $service->start_time ? $service->start_time : '' }}</td>
                        <td>
                            @if($service->status == "P")
                                <span class="badge badge-danger" id="s_status">Pending</span>
                            @elseif($service->status == "C")
                                <span class="badge badge-success" id="s_status">Complete</span>
                            @elseif($service->status == "D")
                                <span class="badge badge-info" id="s_status">Dispatched</span>
                            @endif
                        </td>
                        @php
                            $creted_at = sHelper::convert_italian_time($service->created_at);
                        @endphp
                        <td>{{ $creted_at }}</td>
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
            <span class="breadcrumb-item active">Wrecker Booked Services</span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop
@push('scripts')
<script src="{{ asset('validateJS/admin.js') }}"></script>
<script src="{{ asset('validateJS/workshop.js') }}"></script> 
<script src="{{ url('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ url('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>
@endpush


