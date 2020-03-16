@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="card">
<div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;Add Bonus Amount</h6>
    </div>
    <div class="card-body" id="days_hour_section">
              
	<form id="" action="{{ url('admin_ajax/add_master_bonus') }}" method="POST">
    @csrf
    <div class="form-group">
         <input type="hidden" name="customer_id" id="customer_id" class="form-control"  value="" required  />
    </div>
    <div class="row">
        <div class="col-md-12 form-group">
            <label>Add For Registration amount&nbsp;<span class="text-danger">*</span></label>
            <input type="text" class="form-control" placeholder="Add for registration amount" name="for_registration" id="for_registration" value="{{ $selected_bonus_amount->for_registration }}" required="required"  />
        </div>
		 <div class="col-md-12 form-group">
            <label>Add 2 level amount &nbsp;<span class="text-danger">*</span></label>
            <input type="text"  class="form-control" placeholder="Add 2 level amount" name="two_level_amount" id="two_level_amount" value="{{ $selected_bonus_amount->two_level_amount }}" required="required"/>
        </div>
		 <div class="col-md-12 form-group">
            <label>Add 3 level amount  &nbsp;<span class="text-danger">*</span></label>
             <input type="text"   class="form-control" placeholder="Add 3 level amount" name="three_level_amount" id="three_level_amount" value="{{ $selected_bonus_amount->three_level_amount }}" required="required"/>
        </div>
        
    </div>

    <div id="response"></div>
    <div class="d-flex justify-content-between align-items-center">
        <button type="submit" id="customer_details_sbmt" class="btn bg-blue ml-3">@lang('messages.Submit') <i class="icon-paperplane ml-2"></i></button>
    </div>
	</form>
     </div>
</div>     
              
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang('messages.Home')</a>
							<a href="#" class="breadcrumb-item">@lang('messages.Admin') </a>
							<span class="breadcrumb-item active">Add bonus </span>
						</div>
						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>
				</div>
@stop
@push('scripts')
  <script src="{{ url('validateJS/admin.js') }}"></script>
  <script src="{{ url('validateJS/date/jquery-ui.css') }}"></script>
  <script src="{{ url('validateJS/date/jquery-ui.js') }}"></script>
@endpush

