@extends('layouts.master_layouts')
@section('content')
<style>
.table {
    margin-bottom: 18px;
}
.table thead td {
	font-weight: bold;
}
.panel-heading h3 {
	font-weight: bold;
	display: inline-block;
}
.panel-primary .panel-heading {
	color: #1e91cf;
	border-color: #96d0f0;
	background: white;
}
.panel-default {
	border: 1px solid #dcdcdc;
	border-top: 1px solid #dcdcdc;
}
.panel-default .panel-heading {
	color: #4c4d5a;
	border-color: #dcdcdc;
	background: #f6f6f6;
	text-shadow: 0 -1px 0 rgba(50,50,50,0);
    height: 44px;
    border-bottom:1px solid #ddd;
}
.panel .panel-heading {
    padding: 10px;
}
.btn-xs, .btn-group-xs > .btn {
  padding: 1px 5px;
  font-size: 12px;
  line-height: 1.5;
  border-radius: 2px; 
}
.panel-body {
    padding: 15px;
}
</style>
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="content">
    <!-- Page length options -->
    <div class="">
    <div class="container-fluid">
        <div class="row" style="margin-bottom:20px;">
          <div class="col-sm-12">
            <a href="<?php echo url("admin/generate_xml/$order_deatil->id") ?>" class="btn btn-primary generate_xml" data-orderid="<?php if(!empty($order_deatil->id)) echo $order_deatil->id; ?>">
               Generate Xml
            </a>
          </div>
          <div class="col-sm-12" style="margin-top:15px;">
              <input type="text" name="copied_url" id="copied_url" class="form-control" style="display:none;" />
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default card">
                    <div class="panel-heading">
                        <h6 class="panel-title"><i class="fa fa-shopping-cart"></i> Order Details</h6>
                    </div>
                    <table class="table">
                        <tbody>
                            <tr>
                                <td style="width: 1%;"><button data-toggle="tooltip" title="Store" class="btn btn-info btn-xs"><i class="fa fa-shopping-cart fa-fw"></i></button></td>
                                <td>{{ $company_name ?? "N/A"}}</td>
                            </tr>
                            <tr>
                                <td><button data-toggle="tooltip" title="Order Date" class="btn btn-info btn-xs"><i class="fa fa-calendar fa-fw"></i></button></td>
                                <td>{{ $order_deatil->order_date }}</td>
                            </tr>
                            <tr>
                                <td><button data-toggle="tooltip" title="Payment Method" class="btn btn-info btn-xs"><i class="fa fa-credit-card fa-fw"></i></button></td>
                                <td>
                                    @if($order_deatil->payment_mode == 1)
                                         Case On Delivery 
                                    @elseif($order_deatil->payment_mode == 2)
                                         Paytm 
                                    @elseif($order_deatil->payment_mode == 3)
                                        Via Credit Card
                                    @elseif($order_deatil->payment_mode == 4)
                                        Via Debit Card
                                    @elseif($order_deatil->payment_mode == 5)
                                        Net Banking
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><button data-toggle="tooltip" title="Shipping Method" class="btn btn-info btn-xs"><i class="fa fa-euro fa-fw"></i></button></td>
                                <td>&euro; {{ $order_deatil->total_price }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-default card">
                    <div class="panel-heading">
                        <h6 class="panel-title"><i class="fa fa-user"></i> Customer Details</h6>
                    </div>
                    <table class="table">
                        <tr>
                            <td style="width: 1%;"><button data-toggle="tooltip" title="Customer" class="btn btn-info btn-xs"><i class="fa fa-user fa-fw"></i></button></td>
                            <td> {{ $order_deatil->f_name }}
                            </td>
                        </tr>
                        <tr>
                            <td><button data-toggle="tooltip" title="Customer Group" class="btn btn-info btn-xs"><i class="fa fa-group fa-fw"></i></button></td>
                            <td>Default</td>
                        </tr>
                        <tr>
                            <td><button data-toggle="tooltip" title="E-Mail" class="btn btn-info btn-xs"><i class="fa fa-envelope-o fa-fw"></i></button></td>
                            <td><a href="mailto:bharathigct5@gmail.com">{{ $order_deatil->email }}</a></td>
                        </tr>
                        <tr>
                            <td><button data-toggle="tooltip" title="Telephone" class="btn btn-info btn-xs"><i class="fa fa-phone fa-fw"></i></button></td>
                            <td>{{ $order_deatil->mobile_number }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default card">
        <div class="panel-heading">
            <h6 class="panel-title"><i class="fa fa-info-circle"></i> Order (#{{ $order_deatil->id }})</h6>
        </div>
        <div class="panel-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <td style="width: 50%;" class="text-left">Payment Address</td>
                        <td style="width: 50%;" class="text-left">Shipping Address</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-left">Bharathi<br />R<br />west saidapet,chennai<br />chennai 600015<br />Tamil Nadu<br />India</td>
                        <td class="text-left">@if(!empty($shipping_address_details->address_1)){!! $shipping_address_details->address_1."<br />" !!}@endif 
                          @if(!empty($shipping_address_details->address_2)) {!! $shipping_address_details->address_2."<br />" !!} @endif   
                          @if(!empty($shipping_address_details->address_3)){!! $shipping_address_details->address_3."<br />" !!}@endif 
                          @if(!empty($shipping_address_details->landmark)){!! $shipping_address_details->landmark."<br />" !!} @endif 
                          @if(!empty($shipping_address_details->zip_code)){!! $shipping_address_details->zip_code."<br />" !!} @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-left">S No.</th>
                        <th class="text-left">Product Order Id</th>
                        <th class="text-left">Product Name</th>
                        <th class="text-left">Product Description</th>
                        <th class="text-left">Product Coupons</th>
                        <th class="text-left">Unit Price</th>
                        <th class="text-left">Discount</th>
                        <th class="text-left">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($order as $orders)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $orders->products_orders_id }}</td>
                            <td>{{ $orders->product_name ?? "N/A" }}</td>
                            <td>{{ $orders->product_description ?? "N/A" }}</td>
                            <td>{{ $orders->coupons_id ?? "N/A" }}</td>
                            <td>&euro; {{ $orders->price ?? "N/A" }}</td>
                            <td>{{ $orders->discount ?? "N/A" }}</td>
                            <td>&euro;{{ $orders->total_price ?? "N/A" }}</td>
                        </tr>
                    @empty
                        <tr>
                        <td colspan="5">Data Not Available</td>
                        </tr>
                    @endforelse
                    <tr>
                        <td colspan="7" class="text-right">Sub-Total</td>
                        <td class="text-right">&euro;{{ $order_deatil->total_price }}</td>
                    </tr>
                    <tr>
                        <td colspan="7" class="text-right">Total Discount</td>
                        <td class="text-right">&euro;{{ $order_deatil->total_discount }}</td>
                    </tr>
                        @php
                            $sub_total = $order_deatil->total_price;
                            $discount = $order_deatil->total_discount;
                            $total = ($sub_total - $discount);
                        @endphp
                    <tr>
                        <td colspan="7" class="text-right">Total</td>
                        <td class="text-right">&euro;{{ $total }}</td>
                    </tr>
                     <tr>
                        <td colspan="7" class="text-right">PFU</td>
                        <td class="text-right">&euro;{{ $pfu_price }}</td>
                    </tr>
                    <tr>
                        <td colspan="7" class="text-right">VAT</td>
                        <td class="text-right">&euro;{{ $vat_price }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    </div>
    <!-- /page length options -->
</div>
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="{{ url('/') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
            <a href="#" class="breadcrumb-item">Admin </a>
            <span class="breadcrumb-item active"> Order Details </span>
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
<script src="{{ url('validateJS/vendors.js') }}"></script>
<script src="{{ url('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ url('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>
<script>
 function CopyToClipboard() {
                var text = document.createElement("textarea");
                text.innerHTML = window.location.href;
                Copied = text.createTextRange();
                Copied.execCommand("Copy");
 }
 
$(document).ready(function(){
	$(document).on('click','.generate_xml',function(e){
	   var btn = $(this);
	   btn.html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);	
	   e.preventDefault();
	   var order_href = $(this).attr('href');
	   var order_id = $(this).data('orderid');
	   $.ajax({
			url:order_href,
			method: "GET",
			success: function(data){
			   btn.html('Generate Xml');	
			   if(data == 200){
				  copied_url =  base_url+"public/"+order_id+".xml"; 	  
				   //$(".generate_xml").append( $('<input>' , { value:copied_url , class:'form-control'}));
				  $("#copied_url").val(copied_url).show(); 
				 }
			   //console.log();
			},
			error: function(xhr, error){
                alert("Something went wrong , please try again !!!");
			}
	    });
	});
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
@endpush


