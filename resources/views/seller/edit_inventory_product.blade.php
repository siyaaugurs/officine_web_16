@extends('layouts.master_layouts')
@section('content')
<style>
.panel-heading h6 {
	display: inline-block;
}
.panel-primary .panel-heading {
	color: #1e91cf;
	border-color: #96d0f0;
	background: white;
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
.panel-default {
	border: 1px solid #dcdcdc;
	border-top: 1px solid #dcdcdc;
}
</style>
<div class="content">
    <div class="card">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h6 class="panel-title"><i class="fa fa-shopping-cart"></i>&nbsp; Edit Product</h6>
            </div>
        </div>
        <div class="card-body">
            <form id="edit_products_invent_form">
                @csrf
                <input type="hidden" name="inventory_id" value="{{ $product_deatils->id ?? '' }}">
                <input type="hidden" name="inventory_quentity" value="{{ $product_deatils->quantity ?? '' }}">
                <div class="row form-group" id="">
                    <div class="col-sm-6">
                        <label>Item Number</label>
                        <input type="text" class="form-control" name="item_name" placeholder="Item Number" value="{{ $product_deatils->product_new_product_name ?? '' }}"/>
                    </div>
                    <div class="col-sm-6">
                        <label>Ean Number</label>
                        <input type="text" class="form-control" name="ean_number" placeholder="Ean Number" value="{{ $product_deatils->product_new_details_bar_code ?? '' }}"/>

                        <span id="title_err"></span>
                    </div>
                </div>
                <div class="row form-group" id="">
                    <div class="col-sm-6">
                        <label>@lang('messages.Price')&nbsp;<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="product_price" placeholder="@lang('messages.Price')" value="{{ $product_deatils->products_sale_price ?? '' }}" required="required"/>

                        <span id="title_err"></span>
                    </div>
                    <div class="col-sm-6">
                        <label>@lang('messages.Quantity')&nbsp;<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="product_quantity" placeholder="@lang('messages.Quantity')" value="{{ $product_deatils->quantity ?? '' }}" required="required"/>
                    </div>
                </div>
                <div class="row form-group" id="">
                    <div class="col-sm-12">
                        <label>@lang('messages.StockWarning')</label>
                        <input type="number" class="form-control" name="stock_warning" placeholder="@lang('messages.StockWarning')" value="{{ $product_deatils->stock_warning ?? '' }}"  />
                    </div>
                </div>
                
                
                <div class="row form-group" id="">
                    <div class="col-sm-12">
                        <label>@lang('messages.Status')&nbsp;<span class="text-danger">*</span></label>
                        <select class="form-control multiselect" name="product_status" id="status" >
                                <option value="P" <?= $product_deatils->status == 'P' ? 'selected' : '' ?>>Saved In Draft</option> 
                                <option value="A" <?= $product_deatils->status == 'A' ? 'selected' : '' ?>>Publish</option> 
                        </select>
                    </div>
                </div>
                <!-- <div class="row form-group" id="">
                    <div class="col-sm-10">
                        <input type="checkbox" name="tax" value="0" style="height:15px;width:15px;" id="tax" <?= $product_deatils->tax == 1 ? 'checked' : '' ?> />
                        @lang('messages.TaxInclude')
                    </div>
                </div>
                <div class="row form-group" id="tax_hide_show"  style="display:none">
                    <div class="col-sm-12">
                        <label>@lang('messages.TaxAmount')&nbsp;<span class="text-danger">*</span></label>
                        <input type="number" class="form-control"  name="tax_value" placeholder="@lang('messages.TaxAmount')" id="tax_amount" value="{{ $product_deatils->tax_value ?? '' }}"/>
                    </div>
                </div>
                <div class="row form-group" id="">
                    <div class="col-sm-10">
                        <input type="checkbox" id="assemble_product"  style="height:15px;width:15px;" <?= $product_deatils->assemble_service == 1 ? 'checked' : '' ?>/>
                        <label> @lang('messages.AssembleServiceProvider') &nbsp;</label>
                    </div>
                </div> -->
                <div id="response_coupon"></div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                        <button type="submit" id="edit_product_sbmt" class="btn bg-blue ml-3">Submit <i class="icon-paperplane ml-2"></i></button>
                        </div>
                    </div>
            </form>
        </div>
    </div>
</div>

<div class="modal" id="msg_response_popup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel"><i class="text-white icon-megaphone mr-3 icon-2x"></i> Message  </h4>
                <hr />
            </div>
            <div class="modal-body">
                <div class="row ">
                    <div class="col-md-12">
                        <div id="msg_response"></div>  
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer"> </div>
    </div>
</div>
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="../vendors/index.html" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
            <a href="../vendors/internationalization_fallback.html" class="breadcrumb-item">Seller</a>
            <span class="breadcrumb-item active">Edit Inventory products</span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>   
</div>
@stop
@push('script')
<link href='{{ url("cdn/css/croppie.css") }}' />
@endpush

@push('custom_script')
<script src="{{ url('cdn/js/croppie.js') }}"></script>
@endpush
@push('scripts')
<script src="{{ asset('validateJS/admin.js') }}"></script>
<script src="{{ asset('validateJS/seller.js') }}"></script>
<script src="{{ asset('validateJS/products.js') }}"></script>
<script src="{{ url('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ url('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>
<script>
    function check_tax_box(checkBox, text) {
        if (checkBox.checked == true){
            text.style.display = "block";
        } else {
            text.style.display = "none";
        }
    }
    $(document).ready(function(){
        var checkBox1 = document.getElementById("tax");
        var text1 = document.getElementById("tax_hide_show");
        check_tax_box(checkBox1, text1);
        $(document).on('click' , '#tax' , function(){
            var checkBox = document.getElementById("tax");
            var text = document.getElementById("tax_hide_show");
            check_tax_box(checkBox, text);
        })
    })
</script>
@endpush