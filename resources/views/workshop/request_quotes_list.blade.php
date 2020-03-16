@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="content">
    <!-- Page length options -->
    @if(Session::has('msg'))
      {!! session::get('msg') !!}
    @endif
    <div class="card">
        <div class="card-header bg-light header-elements-inline">
            <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;Service Quotes Booking List</h6>
        </div>
        <table class="table datatable-show-all">
            <thead>
                <tr>
                    <th>SN.</th>
                    <th>Customer Name (#id)</th>
                    <th>Service Name</th>
                    <th>For Booking Date</th>
                    <th>Status</th>
                    <th>Booking Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($request_quotes as $booking)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>@if(!empty($booking->f_name)){{ $booking->f_name }} @else {{ "N/A"}} @endif &nbsp;(#{{ $booking->booking_users_id ? $booking->booking_users_id : ''  }})</td>
                    <td>{{ $booking->main_cat_name ? $booking->main_cat_name : 'N/A' }} </td>
                    <td>@if(!empty($booking->booking_date)){{ $booking->booking_date }} @endif &nbsp; @if(!empty($booking->start_time)){{ $booking->start_time }} @endif</td>
                    <td>
                        @if($booking->status == "P")
                            <span class="badge badge-danger" id="s_status">Pending</span>
                        @elseif($booking->status == "C")
                            <span class="badge badge-success" id="s_status">Complete</span>
                        @elseif($booking->status == "D")
                            <span class="badge badge-info" id="s_status">Dispatched</span>
                        @endif
                    </td>
                    @php
                        $creted_at = sHelper::convert_italian_time($booking->created_at);
                    @endphp
                    <td>{{ $creted_at }}</td>
                    <td class="text-center"> 
                        <a href="#" class="btn btn-warning view_quotes_details" data-servicequotesid="{{ $booking->servicequotes_id }}"  data-toggle="tooltip" data-placement="top" title="Info"  ><i class="fa fa-info-circle"></i></a>
                    </td>
                    @php $encrypt_id=  encrypt($booking->id) @endphp
                </tr>
                @empty
                <tr>
                    <td colspan="5">@lang('messages.NoRecordFound')</td>
                </tr>  
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal" id="service_quotes__details_modal">
    <div class="modal-dialog">
        <div class="modal-content" style="width:700px; margin-left: -100px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Service Quotes details </h4>
                <hr />
            </div>
            <div id="err_response"></div>
                <div class="modal-body">
                  <div id="modal_response"></div>
                </div>
            <div id="image_result"></div>
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
            <a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>@lang('messages.Home') </a>
            <a href="#" class="breadcrumb-item">@lang('messages.Admin') </a>
            <span class="breadcrumb-item active"> Service Quotes Bookings </span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop
@push('scripts')
<script src="{{ url('validateJS/admin.js') }}"></script>
<script src="{{ url('validateJS/vendors.js') }}"></script>
<script>
    $(document).ready(function(e) {
        $(document).on('click', '.view_quotes_details', function(e) {
            e.preventDefault();
            var quotes_id = $(this).data('servicequotesid');
            if(quotes_id != "") {
                $.ajax({
                    url: base_url + "/workshop_ajax/get_service_quotes_details",
                    method: "GET",
                    data: { quotes_id: quotes_id},
                    success: function(data) {
                        $("#modal_response").html(data);
                        $("#service_quotes__details_modal").modal({
                            backdrop:'static' , 
                            keyboard:false 
                        });
                    }
                });
            }
        });
    });
</script>
@endpush


