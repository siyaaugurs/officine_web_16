@extends('layouts.master_layouts')
@section('content')
<div class="content">
	<input type="hidden" name="page" id="page" value="{{ $page }}" />
	<div class="tab_here mb-3">
		<ul class="nav nav-pills m-b-10" id="pills-tab" role="tablist">
			<li class="nav-item">
					<a class="nav-link <?php if($page == "add_business_details") echo "active"; ?>"  href='{{ url("add_business_details")}}'>@lang('messages.BusinessDetails')</a>
			</li>
			<li class="nav-item">
				<a class="nav-link  <?php if($page == "bank_details") echo "active"; ?>" href='{{ url("bank_details")}}'>@lang('messages.BankDetails')</a>
			</li>
		</ul>
	</div>
	@if($bank_details == NULL || $fill_form == TRUE)    
	<div class="card">
	    <div class="card-header bg-light header-elements-inline">
			<h6 class="card-title" style="font-weight:600;"><i class="fa fa-info-circle"></i>&nbsp;&nbsp;@lang('messages.AddBankDetails')</h6>
		</div>
		<div class="card-body">
			<form id="bank_details_form">
				@csrf
				<div class="form-group">
					<label>@lang('messages.OwnerName') <span class="text-danger">*</span></label>
					<input type="text" name="owner_name" id="owner_name" class="form-control" placeholder="@lang('messages.OwnerName')" value="{{ $bank_details->account_holder_name ?? '' }}" required="required"  />
					<span id="account_holder_name_err"></span>
				</div>
				<div class="row">
					<div class="col-md-6 form-group">
						<label>@lang('messages.IBANCode')&nbsp;<span class="text-danger">*</span></label>
						<input type="text" class="form-control" placeholder="@lang('messages.IBANCode')" name="iban_code" id="iban_code" value="{{ $bank_details->iban_code ?? '' }}" required="required"  />
					</div>
					<div class="col-md-6 form-group">
						<label>@lang('messages.SwiftCode')&nbsp;<span class="text-danger">*</span></label>
						<input type="text" class="form-control" placeholder="@lang('messages.SwiftCode')" name="swift_code" id="swift_code" value="{{ $bank_details->swift_code ?? '' }}" required="required"  />
					</div>
				</div>
				<div class="row" style="margin-top:15px; margin-bottom:15px;">
					<div class="col-sm-12">
						<input type="hidden" id="country_edit_id" value="@if(!empty($bank_details->country_id)){{ $bank_details->country_id }} @endif">
						<input type="hidden" id="country_edit_name" value="@if(!empty($bank_details->country_name)){{ $bank_details->country_name }} @endif">
						<label>@lang('messages.BankCountry') &nbsp;<span class="text-danger">*</span>&nbsp;</label>
						<select class="form-control country" name="country" id="country_1">
							@if(!empty($bank_details->country_id))
								<option value="<?php echo $bank_details->country_id."@".$bank_details->country_name; ?>">{{ $bank_details->country_name }}</option>
							@endif
								<option value="0">@lang('messages.SelectCountryName')</option>
						</select>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-12 form-group">
						<label>@lang('messages.BankAddress') &nbsp;<span class="text-danger">*</span></label>
						<textarea type="text"  row="5" class="form-control" placeholder="@lang('messages.BankAddress')" name="bank_address" id="bank_address" required="required"  />{{ $bank_details->bank_address ?? '' }}</textarea>
					</div>
				</div>
				
				<div class="d-flex justify-content-between align-items-center">
					<button type="submit" id="bank_details_sbmt" class="btn bg-blue ml-3">@lang('messages.Submit') <i class="icon-paperplane ml-2"></i></button>
				</div>
			</form>
			<div id="response_bank_details"></div>
		</div>
	</div>
	@else
	<div class="card" id="workshop_details_section">
		<div class="card-header bg-light header-elements-inline">
			<h6 class="card-title"> @lang('messages.BankDetails') &nbsp;<a href="{{ url('bank_details/edit') }}" class="ml-3 icn-sm green-bdr">
			<i class="icon-pencil"></i>
			</a></h6>
			<div class="header-elements">
				<div class="list-icons">
					<a class="list-icons-item" data-action="collapse"></a>
					<a class="list-icons-item" data-action="remove"></a>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-body" id="days_hour_section">
				<ul class="media-list media-chat-scrollable mb-3">
					<li class="media">
						<div class="mr-3">1.</div>
						<div class="media-body">
							<div class="media-title d-flex flex-nowrap">
								<a class="font-weight-semibold mr-3">@lang('messages.OwnerName')</a>
								<span class="font-size-sm text-muted text-nowrap ml-auto"></span>
							</div>
							{{ $bank_details->account_holder_name ?? "Not Mentioned" }}
						</div>
					</li>
					<li class="media">
					<div class="mr-3">2.</div>
					<div class="media-body">
						<div class="media-title d-flex flex-nowrap">
							<a class="font-weight-semibold mr-3">@lang('messages.IBANCode')</a>
							<span class="font-size-sm text-muted text-nowrap ml-auto"></span>
						</div>
						{{ $bank_details->iban_code ?? "Not Mentioned" }}
					</div>
					</li>
                    <li class="media">
					<div class="mr-3">3.</div>
					<div class="media-body">
						<div class="media-title d-flex flex-nowrap">
							<a class="font-weight-semibold mr-3">@lang('messages.SWIFTCode') </a>
							<span class="font-size-sm text-muted text-nowrap ml-auto"></span>
						</div>
						{{ $bank_details->swift_code ?? "Not Mentioned" }}
					</div>
					</li>
					<li class="media">
					<div class="mr-3">4.</div>
					<div class="media-body">
						<div class="media-title d-flex flex-nowrap">
							<a class="font-weight-semibold mr-3">@lang('messages.CountryName')</a>
							<span class="font-size-sm text-muted text-nowrap ml-auto"></span>
						</div>
						{{ $bank_details->country_name ?? "Not Mentioned" }} 
					</div>
					</li>
					<li class="media">
					<div class="mr-3">5.</div>
					<div class="media-body">
						<div class="media-title d-flex flex-nowrap">
							<a class="font-weight-semibold mr-3">@lang('messages.BankAddress')</a>
							<span class="font-size-sm text-muted text-nowrap ml-auto"></span>
						</div>
						{{ $bank_details->bank_address ?? "Not Mentioned" }}
					</div>
					</li>
				</ul>
			</div>
		</div>    
	</div>
	@endif                      
</div>     
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
	<div class="d-flex">
		<div class="breadcrumb">
			<a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang('messages.Home') </a>
			<span class="breadcrumb-item active"> {{ $page_name_bread }}  @lang('messages.BankDetails') </span>
		</div>
		<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
	</div>
</div>
@stop
@push('scripts')
  <script src="{{ url('validateJS/date/jquery-ui.css') }}"></script>
  <script src="{{ url('validateJS/date/jquery-ui.js') }}"></script>
  <script src="{{ url('validateJS/vendors.js') }}"></script>
@endpush

