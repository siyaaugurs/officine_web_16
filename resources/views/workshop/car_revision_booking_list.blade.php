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
            <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;@lang('messages.CarRevisionBookingList')</h6>
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
                    <!-- <th class="text-center">Actions</th> -->
                </tr>
            </thead>
            <tbody>
                @forelse($booked_car_revision as $booking)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>@if(!empty($booking->f_name)){{ $booking->f_name }} @else {{ "N/A"}} @endif</td>
                    <td>{{ $booking->category_name ? $booking->category_name : 'N/A' }} </td>
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
                    @php $encrypt_id=  encrypt($booking->id) @endphp
                    <!-- <td class="text-center"> 
                        <a href='{{  url("vendor/view_car_revision_booking/$encrypt_id") }}' class="btn btn-primary"  data-toggle="tooltip" data-placement="top" title="Edit"  ><i class="fa fa-eye"></i></a>
                    </td> -->
                </tr>
                @empty
                <tr>
                    <td colspan="5">@lang('messages.NoRecordFound')</td>
                </tr>  
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- /page length options -->
</div>
<!--Add category popup modal-->
<!-- <div class="modal" id="add_category_popup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Add New Category</h4>
                <hr />
            </div>
            <form id="add_car_revision_category_form" >
                <input type="hidden" value="" name="category_id" id="category_id" />
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label>@lang('messages.CategoryName')&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="@lang('messages.CategoryName')" name="category_name" id="category_name" required="required"  />
                            <span id="start_date_err"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                        <label>@lang('messages.Price')&nbsp;<span class="text-danger">*</span></label>
                        <input type="number"  name="price" id="price" placeholder="@lang('messages.Price')" class="form-control"/>
                        <span id="start_date_err"></span>
                        </div>
                    </div>
                    <span id="add_response"></span>
                    <span id="err_response"></span>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="form-check form-check-inline">
                            <button type="submit" id="car_revision_submit" class="btn bg-blue ml-3">@lang('messages.Submit') <i class="icon-paperplane ml-2"></i></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">       
        </div>
    </div>
</div> -->
<!--End-->
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>@lang('messages.Home') </a>
            <a href="#" class="breadcrumb-item">@lang('messages.Admin') </a>
            <span class="breadcrumb-item active"> @lang('messages.CarRevisionBookings') </span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop
@push('scripts')
<script src="{{ url('validateJS/admin.js') }}"></script>
<script src="{{ url('validateJS/vendors.js') }}"></script>
@endpush


