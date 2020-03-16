@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<input type="hidden" name="service_id" id="service_id" value="<?php if(!empty($service_id)) echo $service_id; ?>">
<style> .container{ padding:15px;} </style>
<!--<div class="card" style="margin-bottom:10px;">
    <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-filter"></i>&nbsp;Filter</h6>
    </div>
    <div class="content">
        <div id="filter-panel">
            <div class="panel panel-default">
                <div class="panel-body">
                   <div class="row form-group" id="">
                        <div class="col-sm-6">
                            <label>From :</label>
                            <input type="text" class="form-control datepicker" id="first_date" name="first_date" placeholder="Select first date"  readonly="readonly" required="required" />
                        </div>
                        <div class="col-sm-6">
                            <label>To :</label>
                            <input type="text" name="second_date" id="second_date" class="form-control datepicker" placeholder="Select second date" readonly="">
                        </div>
                    </div> 
                </div>
            </div>
        </div>  
    </div>
</div>-->
<div class="card" style="margin-bottom:10px;">
   <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="glyphicon glyphicon-list"></i>&nbsp;<?php if(!empty($lebel_heading)) echo $lebel_heading; ?></h6>
    </div>
    <div class="card-header header-elements-inline">
					<div class="card-body" id="message_body">
						<div class="row">
                           <div class="col-sm-12">
							   <table class="table table-bordered">
								  <tbody>
									  <tr>
										  <th>SN.</th>
										  <td>Customer </td>
										  <th>Workshop</th>
                                          <th>Service</th>
                                          @if($service_id == 1)
                                           <th>Car Size</th>
                                          @endif
                                          <th>Booking Date</th>
										  <td>Action </td>
									  </tr>
								  </tbody>
								  <tbody>
									  @forelse($service_booking_details as $booking_detail)
                                        <tr>
										  <th>{{ $loop->iteration }}</th>
										  <td><a target="_blank" href="<?php $user_id = base64_encode($booking_detail->users_id); echo url("admin/customers_profile/$user_id"); ?>">{{ $booking_detail->f_name." ".$booking_detail->l_name." ( OFFICINE-".$booking_detail->users_id." ) " }} </a></td>
										  <th>
                                          <a target="_blank" href="<?php $workshop_id = base64_encode($booking_detail->workshop_user_id); echo url("admin/company_profiles/$workshop_id"); ?>"><?php 
										  if(!empty($booking_detail->company_name)){
											   echo $booking_detail->company_name." ( ".$booking_detail->workshop_user_id.")";
											}
									?>
                                          </a>
                                          </th>
                                          <th>{{ $booking_detail->category_name }}</th>
										  @if($service_id == 1)
                                          <th>{{ sHelper::get_car_size($booking_detail->car_size) }}</th>
                                          @endif
                                          <th><?php if(!empty($booking_detail->booking_date)) echo sHelper::date_format_for_database($booking_detail->booking_date); ?></th>
                                          <td><a href="javascript::void()" class="btn btn-primary"><i class="glyphicon glyphicon-eye-open"></i></a></td>
									  </tr>
                                      @empty
                                        <tr>
										  <th colspan="5">No record found !!!</th>
									  </tr>
                                      @endforelse
								  </tbody>
							   </table>
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
            <a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>@lang('messages.Home')</a>
            <a href="#" class="breadcrumb-item">@lang('messages.Admin')</a>
            <span class="breadcrumb-item active">Service Booking List</span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop
@push('scripts')
<script src="{{ asset('validateJS/admin.js') }}"></script>
<script src="{{ asset('validateJS/customer_reports.js') }}"></script>
<script src="{{ url('validateJS/date/jquery-ui.css') }}"></script>
<script src="{{ url('validateJS/date/jquery-ui.js') }}"></script>
<script>
$(".datepicker").datepicker();
		$(function () {
			$('#datetimepicker3').datetimepicker({
				format: 'HH:mm:ss'
			});
		});
</script>
@endpush




