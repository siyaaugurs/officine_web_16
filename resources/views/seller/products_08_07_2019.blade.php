@extends('layouts.master_layouts')
@section('content')
<div class="content">
    <div class="card">
        <div class="card-header bg-light header-elements-inline">
            <h6 class="card-title" style="font-weight:600;"><i class="icon-cart"></i>&nbsp;@lang('messages.AddProducts')</h6>
        </div>
        <div class="card-body">
            <form id="products_invent_form">
                @csrf
                <div class="row form-group" id="">
                    <div class="col-sm-6">
                        <label>@lang('messages.SelectMakers')</label>
                        <select class="form-control" name="car_makers" id="car_makers">
                            <option value="0">@lang('messages.selectMaker')</option>
                            @foreach($cars__makers_category as $makers)
                            <option value="{{ $makers->idMarca }}">{{ $makers->Marca }}</option>
                            @endforeach 
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label>@lang('messages.SelectModel')</label>
                        <select class="form-control" name="car_models" id="car_models">
                            <option value="0">@lang('messages.firstSelectMakers')</option>
                        </select>
                    </div>
                </div>
                <div class="row form-group" id="">
                    <div class="col-sm-6">
                        <label>@lang('messages.SelectVersion')</label>
                        <select class="form-control car_version_group" name="car_version" data-action="get_and_save">
                            <option value="0"> @lang('messages.firstSelectModels')</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label>@lang('messages.SelectGroupItem')</label>
                        <select class="form-control" name="group_item_inventory" id="group_item_inventory" data-action="get_and_save_products">
                            <option value="0"> @lang('messages.firstSelectVersion')</option>
                        </select>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-6">
                        <label>@lang('messages.SelectProduct') </label>
                        <select class="form-control" name="inventory_product" id="inventory_product">
                            <option value="0">@lang('messages.FirstSelectGroupItem')</option>
                        </select>
                        <span id="title_err"></span>
                    </div>
                    <div class="col-sm-6">
                        <label>@lang('messages.Price')&nbsp;<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="product_price" placeholder="@lang('messages.Price')" value="" min="0" required="required"/>
                    </div>
                </div>
                <div class="row form-group" id="">
                    <div class="col-sm-6">
                        <label>@lang('messages.Quantity')&nbsp;<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="product_quantity" placeholder="@lang('messages.Quantity')" value="" required="required"/>
                    </div>
                    <div class="col-sm-6">
                        <label>@lang('messages.StockWarning')&nbsp;<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="stock_warning" placeholder="@lang('messages.StockWarning')" value="" required="required" />
                    </div>
                </div>
                
                
                <div class="row form-group" id="">
                    <div class="col-sm-12">
                        <label>@lang('messages.Status')&nbsp;<span class="text-danger">*</span></label>
                        <select class="form-control multiselect" name="product_status" id="status" >
                                <option hidden="hidden">@lang('messages.SelectStatus')</option> 
                                <option value="0">Saved In Draft</option> 
                                <option value="1">Publish</option> 
                        </select>
                    </div>
                </div>
                <div class="row form-group" id="">
                    <div class="col-sm-10">
                        <input type="checkbox" name="tax" value="0" style="height:15px;width:15px;" id="tax"/>
                        @lang('messages.TaxInclude')
                    </div>
                </div>
                <div class="row form-group" id="tax_hide_show"  style="display:none">
                    <div class="col-sm-12">
                        <label>@lang('messages.TaxAmount')&nbsp;<span class="text-danger">*</span></label>
                        <input type="number" class="form-control"  name="tax_value" placeholder="@lang('messages.TaxAmount')" id="tax_amount"/>
                    </div>
                </div>
                <!--<div class="row form-group" id="">
                    <div class="col-sm-10">
                        <input type="checkbox" id="assemble_product"  style="height:15px;width:15px;"/>
                        <label> @lang('messages.AssembleServiceProvider') &nbsp;</label>
                    </div>
                </div>-->
                <div id="response_coupon"></div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                        <button type="submit" id="product_sbmt" class="btn bg-blue ml-3">Submit <i class="icon-paperplane ml-2"></i></button>
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
            <a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>@lang('messages.Home') </a>
            <a href="#" class="breadcrumb-item">@lang('messages.Seller')</a>
            <span class="breadcrumb-item active"> @lang('messages.Products')</span>
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
    $(document).ready(function(){
        $(document).on('click' , '#tax' , function(){
            var checkBox = document.getElementById("tax");
            var text = document.getElementById("tax_hide_show");
            if (checkBox.checked == true){
                text.style.display = "block";
            } else {
                text.style.display = "none";
            }
        })
    })
</script>
@endpush