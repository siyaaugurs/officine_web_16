@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<style> .container{ padding:15px;} </style>
<!--<div class="card" style="margin-bottom:10px;">
    <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-filter"></i>&nbsp;Filter</h6>
    </div>
    <div class="content">
        <div id="filter-panel">
            <div class="panel panel-default">
                <div class="panel-body">
                   <div class="row">
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label>From :</label>
                            <input type="text" class="form-control datepicker" id="first_date" name="first_date" placeholder="Select first date"  readonly="readonly" required="required" />
                            </div>
                        </div>
                        <div class="col-sm-6">
                           <div class="form-group">
                            <label>To :</label>
                            <input type="text" name="second_date" id="second_date" class="form-control datepicker" placeholder="Select second date">
                           </div> 
                        </div>
                    </div>
                   <div class="row">
                        <div class="col-sm-12">
                          <div class="form-group">
                            <label>Select order status </label>
                            <select name="status" id="status" class="form-control">
                                <option value="" hidden="hidden">--Select Status--</option>
                                <option value="I">In Process</option>
                                <option value="D">Dispatched</option>
                                <option value="IN">Intransit</option>
                                <option value="DE">Delivered</option>
                            </select>
                           </div> 
                        </div>
                    </div>
                   <div class="row">
                        <div class="col-sm-12">
                          <a href="javascript::void()" class="btn btn-warning search_order">Search&nbsp;<span class="glyphicon glyphicon-search"></span></a> 
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
                      <th>Order ID</th>
                      <th>Order Date</th>
										  <td>Customer </td>
										  <!-- <th>Workshop / Seller </th> -->
                      <th>Order Price</th>
										  <td>Action </td>
									  </tr>
								  </tbody>
								  <tbody>
									  @forelse($orders_list as $order)
                                        <tr>
										  <th>{{ $loop->iteration }}</th>
                                          <th>order-{{ $order->id }} </th>
										  <td><?php if(!empty($order->created_at)) echo sHelper::date_format_for_database($order->created_at); ?></td>
                                          <td><a target="_blank" href="<?php $user_id = base64_encode($order->users_id); echo url("admin/customers_profile/$user_id"); ?>">{{ $order->f_name." ".$order->l_name." ( OFFICINE-".$order->users_id." ) " }} </a></td>
										  <!-- <th>
                                          <a target="_blank" href="<?php $workshop_id = base64_encode($order->seller_id); echo url("admin/company_profiles/$workshop_id"); ?>"><?php 
										  if(!empty($order->company_name)){
											 echo $order->company_name." ( OFFICINE-".$order->seller_id.")";
											}
									?>
                                          </a>
                                          </th> -->
                                          <th>&euro;&nbsp;<?php if(!empty($order->total_price)) echo $order->total_price; ?></th>
                                          <td><a target="_blank" href="<?php echo url("admin/customer_report/order_details/$order->id") ?>" class="btn btn-primary"><i class="glyphicon glyphicon-eye-open"></i></a></td>
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
            <span class="breadcrumb-item active">Order List</span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop
@push('scripts')
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
  /*Get Order list */
    $(document).on('click','.search_order',function(e){
	   e.preventDefault();
	   start_date = $("#first_date").val();
	   end_date = $("#second_date").val();
	   status = $("#status").val();
			$.ajax({
			url: base_url+"/customer_report/get_order_list",
			method: "GET",
			data: {start_date:start_date,end_date:end_date,status:status},
			success: function(data){
			   console.log(data);
			}
	  });
	});
    

  /*End*/		
</script>
@endpush




