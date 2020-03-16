@extends('layouts.master_layouts')
@section('content')
<div class="card" id="workshop__mobile_section">
    <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title">@lang('messages.ContactDetails')</h6>
        <div class="header-elements">
            <div class="list-icons">
                <a class="list-icons-item" data-action="collapse"></a>
                <a class="list-icons-item" data-action="remove"></a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <ul class="media-list media-chat-scrollable mb-3">
            <li class="media text-muted">
                    <button type="button" class="btn btn-success popup_btn" data-modalname="mobile_popup">	<i class="icon-plus3"></i>&nbsp;@lang('messages.AddMobileNumber')</button>
            </li>
            @forelse($get_workshop_mobile as $mobile)
            <li class="media">
                <div class="mr-3">
                    {{ $loop->iteration ."." }}
                </div>
                <div class="media-body">
                    <div class="media-title d-flex flex-nowrap">
                        <a class="font-weight-semibold mr-3">
                            {{ $mobile->mobile }} 
                        </a>
                        <span class="font-size-sm text-muted text-nowrap ml-auto">
                            @if($mobile->users_id == Auth::user()->id) 
                        <a data-mobileid="{{ $mobile->id }}" href="#" class="ml-3 icn-sm red-bdr delete_mobile">
                        <i class="icon-x icon-2x"></i>
                        </a>
                        @endif
                        </span>
                    </div>
                </div>
            </li>
            @empty
            <li class="media">
                <div class="media-body">
                    <div class="media-title d-flex flex-nowrap">
                        <a class="font-weight-semibold mr-3">@lang('messages.NoMobileNumberAvailable') !!!</a>
                    </div>
                </div>
            </li>
            @endforelse   
        </ul>
    </div>
</div>

<!--Add Mobile number modal popup script start-->
<div class="modal" id="mobile_popup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel"><i class="text-white icon-profile mr-3 icon-1x"></i> @lang('messages.AddContactDetails') </h4>
                <hr />
            </div>
            <!-- Modal body -->
                <form id="add_contact_form" autocomplete="off">	
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>@lang('messages.MobileNumber') <span class="text-danger">*</span></label>
                                <input type="text" class="form-control"  name="mobile" id="mobile" placeholder="@lang('messages.MobileNumber')" required="required" onblur="mobileNumberValidate(this.value , 'Mobile' , 'email_err_msg' ,'add_mobile_number')" />
                                <span id="email_err_msg"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">  
                                    <button type="button" class="btn btn-success"  id="add_mobile_number">@lang('messages.Save') &nbsp;<i class="icon-paperplane ml-2"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <div id="response_mobilr_add"></div>   
        </div>
        <div class="modal-footer"> </div>
    </div>
</div>
<!--End--->
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang('messages.Home') </a>
            <span class="breadcrumb-item active"> @lang('messages.AddContactDetails') </span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop
@push('scripts')
    <script src="{{ url('validateJS/admin.js') }}"></script>
    <script src="{{ url('validateJS/date/jquery-ui.css') }}"></script>
    <script src="{{ url('validateJS/date/jquery-ui.js') }}"></script>
    <script src="{{ url('validateJS/vendors.js') }}"></script>
@endpush