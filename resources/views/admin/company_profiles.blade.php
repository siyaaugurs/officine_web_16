@extends('layouts.master_layouts')
@section('content')
<?php
//echo $edit_status;exit;
?>
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="content">
      @if(Session::has('msg'))
        {!! Session::get('msg') !!}
      @endif
  <div class="row">
                    <div class="col-md-12">
                        <div class="card" id="workshop_details_section">
                            <div class="card-header bg-light header-elements-inline">
								<h6 class="card-title"><i class="fa fa-info-circle"></i>&nbsp;Business Details
								    @if($business_details != NULL)
                               		@if($business_details->status == 'P')         
    								    <a href="#"  class="ml-3 icn-sm change_business_status" data-status="{{ $business_details->status }}" data-businessid="{{ $business_details->id }}"><i class="fa fa-toggle-off"></i><a>         
    								@else
    								    <a href="#" class="ml-3 icn-sm change_business_status" data-status="{{ $business_details->status }}" data-businessid="{{ $business_details->id }}"><i class="fa fa-toggle-on"></i><a>       
    								@endif
    								@endif
								</h6>
                                <div class="header-elements">
									<div class="list-icons">
				                	<a href='<?php echo url("admin/company_profiles/$p2/edit_business") ?>' class="btn btn-primary" style="color:white; float:right;">Edit Business Details&nbsp;<span class="glyphicon glyphicon-edit"></span></a>
                                    <a class="list-icons-item" data-action="collapse"></a>                                        </div>
			                	</div>
							</div>
                         <div class="container" style="padding:20px;">
                            <div class="row" id="days_hour_section">
						     <div class="col-sm-12"> 
                             @if($edit_status != NULL && $edit_status == "edit_business") 
                                @include('admin.component.business_details' , ['business_details'=>$business_details])
                             @else
                               @if($business_details != NULL) 
                                 @include('admin.component.business_details_list' , ['business_details'=>$business_details])
                              @else
                              @include('admin.component.business_details' , ['business_details'=>$business_details])
                              @endif
                             @endif 
                             </div>
					        </div>
						</div>    
						</div>
                        <div class="card" id="workshop_details_section">
                            <div class="card-header bg-light header-elements-inline">
								<h6 class="card-title">Bank Details &nbsp;
								    @if($bank_details != NULL)
	                           		@if($bank_details->status == 'P')         
									    <a href="#"  class="ml-3 icn-sm change_bank_status" data-status="{{ $bank_details->status }}" data-bankid="{{ $bank_details->id }}"><i class="fa fa-toggle-off"></i><a>         
									@else
									    <a href="#" class="ml-3 icn-sm change_bank_status" data-status="{{ $bank_details->status }}" data-bankid="{{ $bank_details->id }}"><i class="fa fa-toggle-on"></i><a>       
									@endif
									@endif
								</h6>
								<div class="header-elements">
									<div class="list-icons">
				                	   <a href='<?php echo url("admin/company_profiles/$p2/edit_bank_detials") ?>' class="btn btn-primary" style="color:white; float:right;">Edit Bank Details&nbsp;<span class="glyphicon glyphicon-edit"></span></a>
                                       <a class="list-icons-item" data-action="collapse"></a>
				                	</div>
			                	</div>
							</div>
                         <div class="card">
                            <div class="card-body">
						   @if($edit_status != NULL && $edit_status == "edit_bank_detials") 
                              @include('admin.component.bank_details' , ['bank_details'=>$bank_details])
                           @else
                             @if($bank_details != NULL)
                              @include('admin.component.bank_details_list' , ['bank_details'=>$bank_details])
                             @else
                             @include('admin.component.bank_details' , ['bank_details'=>$bank_details])
                             @endif
                             
                           @endif
					        </div>
						</div>    
						</div>
                        <div class="card" id="workshop_address_section">
    <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title">Address</h6>
        <div class="header-elements">
            <div class="list-icons">
                <a class="list-icons-item" data-action="collapse"></a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <ul class="media-list media-chat-scrollable mb-3">
            @forelse($address_list as $adrs_list)
            <li class="media">
                <div class="mr-3">
                    {{ $loop->iteration ."." }}
                </div>
                <div class="media-body">
                    <div class="media-title d-flex flex-nowrap">
                        <a class="font-weight-semibold mr-3">
                        {{ ucfirst($adrs_list->address_1)." , " ?? ''}} 
                        {{ ucfirst($adrs_list->address_2)." , " ?? ''}} 
                        {{ ucfirst($adrs_list->address_3) ?? ' '}} 
                        {{ ucfirst($adrs_list->landmark) ?? ' '}} 
                        {{ ucfirst($adrs_list->zip_code)." ," ?? ' '}} 
                        {{ ucfirst($adrs_list->country_name)."," ?? ' '}} 
                        {{ ucfirst($adrs_list->state_name)." ," ?? ' '}} 
                        {{ ucfirst($adrs_list->city_name) ?? ' '}} 
                        </a>
                    </div>
                </div>
            </li>
        @empty
        <li class="media">
            <div class="media-body">
                <div class="media-title d-flex flex-nowrap">
                    <a class="font-weight-semibold mr-3">No Address available , please add new address</a>
                </div>
            </div>
        </li>
        @endforelse   
        </ul>
    </div>
</div>
                    </div>
				</div>
</div>
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="{{ url('/') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>@lang('messages.Home')</a>
            <a href="{{ url('/') }}" class="breadcrumb-item">@lang('messages.Admin')</a>
            <span class="breadcrumb-item active"> Company Profile </span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop
@push('scripts')
<script src="{{ url('validateJS/admin.js') }}"></script>
<script src='{{ url("validateJS/vendors.js") }}'></script>
<script src="{{ url('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
 <script src="{{ url('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>
  <script src="{{ url('global_assets/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
  <script src="{{ url('global_assets/js/demo_pages/form_multiselect.js') }}"></script>
  
@endpush


