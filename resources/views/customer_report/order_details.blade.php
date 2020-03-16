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
        text-shadow: 0 -1px 0 rgba(50, 50, 50, 0);
        height: 44px;
        border-bottom: 1px solid #ddd;
    }

    .panel .panel-heading {
        padding: 10px;
    }

    .btn-xs,
    .btn-group-xs>.btn {
        padding: 1px 5px;
        font-size: 12px;
        line-height: 1.5;
        border-radius: 2px;
    }

    .panel-body {
        padding: 15px;
    }


        .reset-this {
            all: initial;
        }

    .redo-fieldset {
        border: 1px solid black;
        padding : 10px;
        width: 98%;
        padding: 8px 7px;
    } 

    .redo-legend {
        color: black;
        margin-left: 40px;
    } 

    table tr td, table tr th {
        padding: 7px 6px;
        /* border-right: 0px; */
        width: 25%;
        line-height: 11px;
        height: 22px;
        font-size: 13px;
        text-transform: capitalize;
    }
    .redo-fieldset table tr td, table tr th {
        width: 23%;
        line-height: 11px;
        height: 22px;
        font-size: 14px;
        text-transform: capitalize;
    }

</style>
<input type="hidden" name="page" id="page" value="{{ $page }}" />
    <div class="card">
        <div class="card-body">
            <table align="center">
                <tr>
                    <td>
                        <table border="1" height="100%">
                            <tr>
                                <th colspan="3">Order details</th>
                            </tr>
                            <tr>
                                <td>Order Id#</td>
                                <td colspan="3">
                                    <?= $order_id ?>
                                </td>
                            </tr>
                            <tr>
                                <td><i class="fa fa-calendar fa-fw"></i></td>
                                <td colspan="3"><?= $order_deatil->created_at ? date('d-m-Y H:i:s' , strtotime($order_deatil->created_at ) ) : '' ?></td>
                            </tr>
                            <tr>
                                <td><i class="fa fa-credit-card fa-fw"></i></td>
                                <td colspan="3">
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
                                <td>Payment Status</td>
                                <td colspan="3">
                                    @if($order_deatil->payment_status == "P")
                                        Pending 
                                    @else
                                        Confirmed 
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Order Status</td>
                                <td colspan="3"><?= $order_status ? $order_status : '' ?></td>
                            </tr>
                            <tr>
                                <td>Service Status</td>
                                <td colspan="3"> Inprocess / Completed</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td colspan="3"></td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table border="1" height="100%">
                            <tr>
                                <th colspan="3">Customer details</th>
                            </tr>
                            <tr>
                                <td>Name</td>
                                <td colspan="3"><?= $order_deatil->f_name ? $order_deatil->f_name : ''  ?></td>
                            </tr>
                            <tr>
                                <td>Country</td>
                                <td colspan="3"></td>
                            </tr>
                            <tr>
                                <td>Region</td>
                                <td colspan="3"></td>
                            </tr>
                            <tr>
                                <td>Province</td>
                                <td colspan="3"></td>
                            </tr>
                            <tr>
                                <td>City</td>
                                <td colspan="3"></td>
                            </tr>
                            <tr>
                                <td>Zip Code</td>
                                <td colspan="3"></td>
                            </tr>
                            <tr>
                                <td>Address</td>
                                <td colspan="3">
                                    @if(!empty($order_deatil->shipping_address->address_1)){!! $order_deatil->shipping_address->address_1.", &nbsp;" !!}@endif 
                                    @if(!empty($order_deatil->shipping_address->address_2)) {!! $order_deatil->shipping_address->address_2.", &nbsp;" !!} @endif   
                                    @if(!empty($order_deatil->shipping_address->address_3)){!! $order_deatil->shipping_address->address_3.", &nbsp;" !!}@endif 
                                    @if(!empty($order_deatil->shipping_address->landmark)){!! $order_deatil->shipping_address->landmark.", &nbsp;" !!} @endif 
                                    @if(!empty($order_deatil->shipping_address->zip_code)){!! $order_deatil->shipping_address->zip_code."<br />" !!} @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table border="1" height="100%" width="100%">
                            <tr>
                                <th colspan="3">Car Info</th>
                            </tr>
                            <tr>
                                <td>Maker</td>
                                <td colspan="3"><?= (!empty($order_deatil->model->makers_name)) ? $order_deatil->model->makers_name : '' ?></td>
                            </tr>
                            <tr>
                                <td>Model</td>
                                <td colspan="3"><?= (!empty($order_deatil->model->Modello)) ? $order_deatil->model->Modello.' >> '.$order_deatil->model->ModelloAnno : '' ?></td>
                            </tr>
                            <tr>
                                <td>Version</td>
                                <td colspan="3"><?= (!empty($order_deatil->version->Versione)) ? $order_deatil->version->Versione : '' ?>&nbsp;<?= (!empty($order_deatil->version->Motore)) ? $order_deatil->version->Motore : '' ?>&nbsp;<?= (!empty($order_deatil->version->ModelloCodice)) ? $order_deatil->version->ModelloCodice : ''  ?>&nbsp;<?= (!empty($order_deatil->version->Body)) ? $order_deatil->version->Body : '' ?></td>
                            </tr>
                            <tr>
                                <td>Fuel Type</td>
                                <td colspan="3"><?= $order_deatil->fuel_type ? $order_deatil->fuel_type : '' ?></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td colspan="3"></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td colspan="3"></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td colspan="3"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <fieldset class="reset-this redo-fieldset" style="margin-left: 10px;">
                <legend class="reset-this redo-legend">Order #<?= $order_id ?></legend>
                <table width="100%">
                    <tr width="100%" align="center">
                        <td>
                            <table width="100%" border="1">
                                <tr>
                                    <th colspan="8">Seller details</th>
                                </tr>
                                <tr>
                                    <td colspan="3">Name</td>
                                    <td colspan="5"><?= $order_deatil->seller_details->f_name ? $order_deatil->seller_details->f_name : '' ?>&nbsp;<?= $order_deatil->seller_details->l_name ? $order_deatil->seller_details->l_name : '' ?></td>
                                </tr>
                                <tr>
                                    <td colspan="3">Country</td>
                                    <td colspan="5"></td>
                                </tr>
                                <tr>
                                    <td colspan="3">Region</td>
                                    <td colspan="5"></td>
                                </tr>
                                <tr>
                                    <td colspan="3">Province</td>
                                    <td colspan="5"></td>
                                </tr>
                                <tr>
                                    <td colspan="3">City</td>
                                    <td colspan="5"></td>
                                </tr>
                                <tr>
                                    <td colspan="3">Zip Code</td>
                                    <td colspan="5"><?= $order_deatil->seller_address->postal_code ? $order_deatil->seller_address->postal_code : '' ?></td>
                                </tr>
                                <tr>
                                    <td colspan="3">Address</td>
                                    <td colspan="5"><?= $order_deatil->seller_address->registered_office ? $order_deatil->seller_address->registered_office : '' ?></td>
                                </tr>
                                <tr>
                                    <td colspan="3"></td>
                                    <td colspan="5"></td>
                                </tr>
                                <tr>
                                    <td colspan="3"></td>
                                    <td colspan="5"></td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table width="100%" border="1">
                                <tr>
                                    <th colspan="8">Ship To</th>
                                </tr>
                                <tr>
                                    <td colspan="3">Name</td>
                                    <td colspan="5"><?= $order_deatil->f_name ? $order_deatil->f_name : ''  ?></td>
                                </tr>
                                <tr>
                                    <td colspan="3">Country</td>
                                    <td colspan="5"></td>
                                </tr>
                                <tr>
                                    <td colspan="3">Region</td>
                                    <td colspan="5"></td>
                                </tr>
                                <tr>
                                    <td colspan="3">Province</td>
                                    <td colspan="5"></td>
                                </tr>
                                <tr>
                                    <td colspan="3">City</td>
                                    <td colspan="5"></td>
                                </tr>
                                <tr>
                                    <td colspan="3">Zip Code</td>
                                    <td colspan="5"></td>
                                </tr>
                                <tr>
                                    <td colspan="3">Address</td>
                                    <td colspan="5">
                                        @if(!empty($order_deatil->shipping_address->address_1)){!! $order_deatil->shipping_address->address_1.", &nbsp;" !!}@endif 
                                        @if(!empty($order_deatil->shipping_address->address_2)) {!! $order_deatil->shipping_address->address_2.", &nbsp;" !!} @endif   
                                        @if(!empty($order_deatil->shipping_address->address_3)){!! $order_deatil->shipping_address->address_3.", &nbsp;" !!}@endif 
                                        @if(!empty($order_deatil->shipping_address->landmark)){!! $order_deatil->shipping_address->landmark.", &nbsp;" !!} @endif 
                                        @if(!empty($order_deatil->shipping_address->zip_code)){!! $order_deatil->shipping_address->zip_code."<br />" !!} @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">Courier</td>
                                    <td>dfdf</td>
                                </tr>
                                <tr>
                                    <td colspan="3">Traking Id</td>
                                    <td>2346343</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    @php
                        $spare_order_description = serviceHelper::spare_product_description($order_id);
                    @endphp
                    @php
                        $tyre_order_description = serviceHelper::tyre_product_description($order_id);
                    @endphp
                    @php
                        $tyre_order_description = serviceHelper::service_product_description($order_id);
                    @endphp
                    <tr>
                        <table border="1" width="50%" style="margin-top: 30px;">
                            <tr>
                                <th>Status</th>
                                <td>
                                    <select name="" id="">
                                        <option value="">Pending</option>
                                        <option value="">InProcess</option>
                                        <option value="">Shipped</option>
                                        <option value="">Dispatched</option>
                                        <option value="">Deliverd</option>
                                        <option value="">Cencled</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <table border="1" width="50%" style="margin-top: 30px;">
                            <tr>
                                <th>Sippment Price</th>
                                <td>&euro; 200</td>
                            </tr>
                            <tr>
                                <th>VAT</th>
                                <td>200</td>
                            </tr>
                            <tr>
                                <th>Total Price</th>
                                <td>&euro; 50000</td>
                            </tr>
                        </table>
                    </tr>
                    <tr>
                    </tr>
                    <!-- <td>
                    </td> -->
                </table>
            </fieldset>    
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
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

</script>
@endpush
