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
.text-width{
    width: 50%;
}
.text-size{
    font-weight: bold;
}
</style>
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="content">
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
                             <tr>
                                <td><button data-toggle="tooltip" title="Order Status" class="btn btn-info btn-xs"><i class="fa fa-euro fa-fw"></i></button></td>
                                <td>&euro;
                                 @if($order_deatil->status == 'P')
                                    Pending
                                 @else
                                    Confirm
                                 @endif
                                </td>
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
                        <td class="text-left text-width">Payment Address</td>
                       @if($order != NULL)
                        @if($order->for_assemble_service == 1)
                        <td class="text-left text-width">Shipping Address</td>
                        @endif
                          @endif

                    </tr>
                </thead>
                <tbody>
                    <tr>
                    
                        <td class="text-left">
                         @if(!empty($payments_address_details->address_1)){!! $payments_address_details->address_1."<br />" !!}@endif 
                         @if(!empty($payments_address_details->address_2)) {!! $payments_address_details->address_2."<br />" !!} @endif   
                         @if(!empty($payments_address_details->address_3)){!! $payments_address_details->address_3."<br />" !!}@endif 
                         @if(!empty($payments_address_details->landmark)){!! $payments_address_details->landmark."<br />" !!} @endif 
                         @if(!empty($payments_address_details->zip_code)){!! $payments_address_details->zip_code."<br />" !!} @endif</td> 
                       @if($order != NULL)
                       @if($order->for_assemble_service == 1)
                        <td class="text-left">
                          @if(!empty($shipping_address_details->address_1)){!! $shipping_address_details->address_1."<br />" !!}@endif 
                         @if(!empty($shipping_address_details->address_2)) {!! $shipping_address_details->address_2."<br />" !!} @endif   
                         @if(!empty($shipping_address_details->address_3)){!! $shipping_address_details->address_3."<br />" !!}@endif 
                         @if(!empty($shipping_address_details->landmark)){!! $shipping_address_details->landmark."<br />" !!} @endif 
                         @if(!empty($shipping_address_details->zip_code)){!! $shipping_address_details->zip_code."<br />" !!} @endif</td> 
                        </td>
                        @endif
                         @endif
                    </tr>
                </tbody>
            </table>
            <table class="table table-bordered">
            <thead>
            <tr>
            <td>Business Name</td>
            <td>Registered Office</td>
            <td>About Business</td>
            </tr>
            </thead>
             @if(!empty($seller_info))
            <tr>
            <td>{{$seller_info->business_name}}</td>
            <td>{{$seller_info->registered_office}}</td>
            <td>{{$seller_info->about_business}}</td>
            </tr>
            @else
             <tr>
            <td></td>
            <td></td>
            <td></td>
            </tr>
            @endif
            </table>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <td>Service Name</td>
                        <td>Price</td>
                        <td>Discount Price</td>
                        <td>VAT</td>
                        <td>Total Price</td>
                    </tr>
                </thead>
                
                <tr>
                    <td>{{ $service_name }}</td>
                    <td>&euro;&nbsp;{{ $service_details->price }}</td>
                    <td>&euro;&nbsp;{{ $service_details->discount }}</td>
                    <td>&euro;&nbsp;{{ $service_details->service_vat }}</td>
                    <td>&euro;&nbsp;{{ $service_details->after_discount_price }}</td>
                </tr>
            </table>
            <table class="table table-bordered">
                @if(!empty($order))
                    <thead>
                        <tr>
                            <td class="text-left">Product Order Id</td>
                            <td class="text-left">Product Name</td>
                            <td class="text-left">Product Description</td>
                            <td class="text-left">Product Coupons</td>
                            <td colspan ="4" class="text-left">Unit Price</td>
                            <td  colspan ="4"  class="text-left">Discount</td>
                            <td  class="text-left">Total</td>
                        </tr>
                    </thead>
                @else
                @endif
                <tbody>
                    @if(!empty($order))
                        <tr>
                            <td>{{ $order->products_orders_id }}</td>
                            <td>{{ $order->product_name ?? "N/A" }}</td>
                            <td>{{ $order->product_description ?? "N/A" }}</td>
                            <td>{{ $order->coupons_id ?? "N/A" }}</td>
                            <td colspan ="4">&euro; {{ $order->price ?? "N/A" }}</td>
                            <td  colspan ="4">{{ $order->discount ?? "N/A" }}</td>
                            <td >&euro;{{ $order->total_price ?? "N/A" }}</td>
                        </tr>
                    @else
                    @endif
                    <tr>
                    @if($service_details !=  NULL)
                    <td colspan ="3" class="text-right text-size">Appointment Time </td>
                    <td colspan ="3">{{$service_details->start_time}} -{{$service_details->end_time}}  </td>
                    @endif
                     <td colspan="6" class="text-right text-size">Sub-Total</td>
                        <td class="text-right">&euro;&nbsp;{{ $sub_total }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right text-size">Car Version</td>
                        <td colspan ="3 class="text-right">{{ $user_detail->carVersion }}</td>
                         <td colspan="6" class="text-right text-size">Total Discount</td>
                        <td class="text-right">&euro;&nbsp;{{ $discount }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right text-size">License Plate</td>
                        <td colspan ="3 class="text-right">{{ $user_detail->number_plate }}</td>
                        <td colspan="6" class="text-right text-size">Total VAT</td>
                        <td class="text-right">&euro;&nbsp;{{ $service_vat }}</td>
                    </tr>
                    @if($service_details->type == 4)
                    <tr>
                        <td colspan="3" class="text-right text-size"></td>
                        <td  colspan="3" class="text-right"></td>
                         <td colspan="6" class="text-right text-size">PFU</td>
                        <td class="text-right">&euro;&nbsp;{{ $pfu_price }}</td>
                    </tr>
                    @endif
                    <tr>
                     <td colspan="3" class="text-right text-size"></td>
                        <td  colspan="3" class="text-right"></td>
                        <td colspan="6" class="text-right text-size">Total</td>
                        <td  colspan="6" class="text-right">&euro;&nbsp;{{ $total_price }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
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


