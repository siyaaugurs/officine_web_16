@extends('layouts.master_layouts')
@section('content')
<input type="hidden"  id="page" value="" />
<!-- <div class="row" style="margin-bottom:10px;">
    <div class="col-sm-12">
         <a href="javascript::void();" class="btn btn-warning" id="export_spare_products" style="color:white;">Export Spare Products Files&nbsp;<span class="fa fa-file-excel-o"></span></a>&nbsp;&nbsp;&nbsp;
         <a href="javascript::void();" class="btn btn-warning" id="import_export_spare_sample_format" style="color:white;">Import / Export Spare Product Files&nbsp;<span class="fa fa-file-excel-o"></span></a>&nbsp;&nbsp;&nbsp;
    </div>
</div> -->
<div class="card">
<div class="card-header bg-light header-elements-inline">
     <h6 class="card-title" style="font-weight:600;"><i class="fa fa-plus"></i>&nbsp;@lang('messages.AddNewProducts')</h6>
</div>
    <div class="card-body">
        <form id="add_new_custom_products_by_admin">
        <div class="form-group">
			 <button type="submit" class="btn btn-success" style="float:;" id="add_products_btn">Save&nbsp; <span class="glyphicon glyphicon-plus"></span></button>
        </div>
         @csrf
            <div class="container">
                <ul class="nav nav-tabs">
                    <li class="nav-item"><a class="nav-link active" href="#tab-general" data-toggle="tab">General</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tab-data" data-toggle="tab">Data</a></li>
                    <!-- <li class="nav-item"><a class="nav-link" href="#tab-attribute" data-toggle="tab">Attribute</a></li> -->
                    <li class="nav-item"><a class="nav-link" href="#tab-image" data-toggle="tab">Image</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tab-assembly" data-toggle="tab">Assemble Status</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tab-carcompatible" data-toggle="tab">Category</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab-general">
                        <div class="form-group required">
                            <div class="col-sm-10">
                                <input type="checkbox" name="for_pair" id="for_pair" value="1" /> &nbsp;&nbsp;&nbsp;
                                Is This Product Sell On Pair ?
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-name1">Select Brand </label>
                            <div class="col-sm-10">
                              <select name="brand" id="brand" class="form-control">
                                  @forelse($brands as $brand)
                                    <option value="<?php if(!empty($brand->brand_name)) echo $brand->brand_name ?>"><?php if(!empty($brand->brand_name)) echo $brand->brand_name ?></option>
                                  @empty
                                  <option value="0">No Brand Available !!!</option>
                                  @endif 
                                </select>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-name1">Item <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" name="products_name"  placeholder="Item" class="form-control" required value="" />
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-name1">Product Name</label>
                            <div class="col-sm-10">
                                <input type="text" name="products_name1"  placeholder="Product Name" class="form-control" value="" />
                            </div>
                        </div>
                      <!--  /*<div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-name1">Item</label>
                            <div class="col-sm-10">
                                <input type="text" name="item" id="item"  placeholder="Item" class="form-control" value="" />
                            </div>
                        </div>*/-->
                        <!-- <div class="form-group">
                            <label class="col-sm-2 control-label">Kromeda Description</label>
                            <div class="col-sm-10">
                                <textarea  placeholder="Kromeda Description" name="kromeda_description" class="form-control"></textarea>
                            </div>
                        </div> -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Our Description</label>
                            <div class="col-sm-10">
                                <textarea  placeholder="Products Description" name="products_description" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" >Tag Title</label>
                            <div class="col-sm-10">
                                <input type="text"  placeholder="Meta Tag Title"  class="form-control" value="" name="meta_title" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-meta-keyword1">Meta Tag Keywords</label>
                            <div class="col-sm-10">
                                <textarea placeholder="Meta Tag Keywords" id="input-meta-keyword1" class="form-control" name="meta_keywords"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab-data">
                        <!-- <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-price">Kromeda Price</label>
                            <div class="col-sm-10">
                              <input type="text" name="kromeda_price" value=""  placeholder="Kromeda Price" id="input-price" class="form-control"  />
                            </div>
                             <span id="kromeda_price_err"></span>
                        </div> -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-price">Seller Price</label>
                            <div class="col-sm-10">
                              <input type="text" name="seller_price" value=""  placeholder="Seller Price" id="input-price" class="form-control" />
                            </div>
                            <span id="seller_price_err"></span>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Bar Code</label>
                            <div class="col-sm-10">
                              <input type="text" name="bar_code" id="bar_code" value=""  placeholder="Enter Bar Code" class="form-control" />
                            </div>
                            <span id="bar_code_err"></span>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-price">Quantity </label>
                            <div class="col-sm-10">
                              <input type="text" name="quantity" value=""  placeholder="Products Quantity" id="input-price" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-tax-class">Tax </label>
                            <div class="col-sm-10">
                                <select  id="tax_status" class="form-control" name="tax">
                                    <option value="0"> --- None --- </option>
                                    <option value="1">Taxable</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" id="tax_value_div" style="display:none;">
                            <label class="col-sm-2 control-label" for="input-tax-class">Tax Amount</label>
                            <div class="col-sm-10">
                                 <input type="text" name="tax_value"  placeholder="Tax value" id="input-minimum" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-minimum"><span data-toggle="tooltip" title="Force a minimum ordered amount">Stock Warning</span></label>
                            <div class="col-sm-10">
                                <input type="text" name="stock_warning"  placeholder="Stock Warning" id="input-minimum" class="form-control" value="" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-subtract">Subtract Stock</label>
                            <div class="col-sm-10">
                                <select  id="input-subtract" class="form-control" name="substract_stock">
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-weight-class">Unit</label>
                            <div class="col-sm-10">
                                <select  id="input-weight-class" class="form-control" name="unit">
                                    <option value="Pcs">Pcs.</option>
                                    <option value="Kg">Kilogram</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-status">Status</label>
                            <div class="col-sm-10">
                                <select  id="input-status" class="form-control" name="products_status">
                                    <option value="A">Publish</option>
                                    <option value="P">Save in draft</option>
                                   
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="tab-pane" id="tab-attribute">
                        <div class="table-responsive">
                            <table id="attribute" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <td class="text-left">Attribute</td>
                                        <td class="text-left">Text</td>
                                        <td></td>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2"></td>
                                        <td class="text-right"><button type="button"  data-toggle="tooltip" title="Add Attribute" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab-image">
                        <div class="table-responsive">
                            <div class="control-group" id="fields">
          <label class="control-label" for="field1">
           Browse Multiple Image
          </label>
          <div class="controls">
              <div class="entry input-group col-xs-3">
                <input class="btn btn-primary" name="products_gallery_image[]" type="file" multiple="multiple">
              </div>
          </div>
        </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab-assembly">
                        <div class="table-responsive">
                            <div class="control-group" id="fields">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-status">Products Assemble Status</label>
                                    <div class="col-sm-10">
                                        <select  id="input-status" class="form-control" name="products_assemble_status">
                                            <option value="A">Yes</option>
                                            <option value="N">No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab-carcompatible">
                        <div class="table-responsive">
                               <div class="form-group">
										<label>Select Makers&nbsp;<span class="text-danger">*</span></label>
										  <select class="form-control makers" name="car_makers" data-action="get_models">
                               <option value="0" hidden="hidden">Select Makers</option>
                                 @foreach($cars__makers_category as $makers)
                                   <option value="@if(!empty($makers->idMarca)){{ $makers->idMarca }} @endif">@if(!empty($makers->Marca)){{ $makers->Marca }} @endif</option>
                                 @endforeach 
                            </select> 
									</div>
                               <div class="form-group">
										<label>Select Model&nbsp;<span class="text-danger">*</span></label>
										   <select class="form-control models" name="car_models" data-action="get_versions">
                                 <option value="0">First Select Makers</option>
</option>
                            </select>     
                      			</div>
							   <div class="form-group">
										<label>Select Version&nbsp;<span class="text-danger">*</span>  </label>
										 <select class="form-control versions"  name="car_version" data-action="get_groups" >
                                <option value="0">First Select Model</option>
                            </select>
									</div>
                               <div class="form-group">
										<label>Select Category &nbsp;<span class="text-danger">*</span></label>
										 <select class="form-control groups" name="product_groups" data-action="get_sub_category">
                                          <option value="0">First Select Version</option>
                            </select>
									</div>
                                    <div class="form-group">
										<label>Select Sub Category&nbsp;<span class="text-danger">*</span> </label>
										 <select class="form-control sub_category" id="sub_groups" name="sub_groups" data-action="get_n3_category">
                                <option value="0">First Select Category</option>
                            </select>
									</div>
                                    <div class="form-group">
										<label>Select Items&nbsp;<span class="text-danger">*</span></label>
										 <select class="form-control items"  name="items">
                                         <option value="0">First Select Sub Category</option>
                                       </select>
									</div>
									<div class="form-group">
                                    <label class="col-sm-6 control-label" for="input-price">Assembly Time &nbsp; <span class="text-danger"> time in hour</span></label>
                                    <div class="col-sm-10">
                                    <input type="text" name="assemble_time" placeholder="Assemble Time" id="input-price" class="form-control assemble_time"  />
                                    </div>
                                </div>
                                    <div class="form-group">
                                    <label class="col-sm-6 control-label" for="input-price">Kromeda Time &nbsp; <span class="text-danger">* time in hour</span></label>
                                    <div class="col-sm-10">
                                    <input type="text" name="kromeda_assemble_time" placeholder="Kroemda Time" id="input-price" class="form-control"  />
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
        <!-- end tab-content -->
            </div>
        </form>
    </div>
    
</div>
<!--Import Export Modal popup script Start-->
  <!--Import Sample Format-->
<div class="modal" id="import_export_spare_sample_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Import / Export Spare Products </h4>
                <hr />
            </div>
            <div class="card-body">
                <a href="<?php echo url('export/spare_product_list_sample') ?>" class="btn btn-warning"><i class="fa fa-download"></i>&nbsp;Export Sample Excel File For Spare Product</a>
                <hr />
                <h3 style="font-weight:600;">Import Spare Product Excel Files </h3> 
                <form id="import_spare_product_file" name="import_spare_product_file">
                @csrf
                  <div class="control-group" id="fields">
                        <span id="rim_msg_response"></span>
                        <label class="control-label" for="field1">
                            Browse Spare Products Excel Files
                        </label>
                        <div class="controls">
                            <div class="entry input-group col-xs-3">
                                <input class="btn btn-primary" name="spare_products_file" type="file"  required>
                                <span class="input-group-btn">
                        &nbsp;&nbsp;
                        <button class="btn btn-success btn-add" type="submit" id="rim_import">
                            Import Spare File
                            <span class="glyphicon glyphicon-import"></span>
                        </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal-footer">
        </div>
    </div>
</div>
<!--End-->
<!--Export All data modal popup start--->
<div class="modal" id="export_spare_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Export Spare Product in CSV File </h4>
                <hr />
            </div>
            <div class="card-body">
                <a href="<?php echo url('export/spare_products_list') ?>" class="btn btn-warning"><i class="fa fa-download"></i>&nbsp;Export Excel For Spare Products</a>
            </div>
        </div>
        <div class="modal-footer">
        </div>
    </div>
</div>
<!--End-->
<!--End-->
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
            <a href="{{ url('/') }}" class="breadcrumb-item">Admin </a>
            <span class="breadcrumb-item active">Add New Products</span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop
@push('scripts')
<script src="{{ url('global_assets/js/demo_pages/form_multiselect.js') }}"></script>
<script src="{{ url('global_assets/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
<script src="{{ asset('validateJS/custom_product.js') }}"></script>
<link href="{{ url('cdn/css/croppie.css') }}" />
<script src="{{ asset('validateJS/import_export.js') }}"></script>
<script>
$(document).ready(function(e){
    $(document).on('blur', '#bar_code', function(e) {
        var bar_code = $('#bar_code').val();
        if(bar_code != "") {
            $.ajax({ 
                url:base_url+"/product/check_bar_code",
                method:"GET",
                data:{bar_code:bar_code},
                success:function(data){
                    if(data == 1){
                        $("#add_products_btn").attr('disabled' , true);
                        $("#msg_response_popup").modal('show');
                        $("#msg_response").html('<div class="notice notice-danger"><strong> Note! </strong>This Bar Code is already Taken !!! </div>');	
                    }
                    else{
                        $("#add_products_btn").attr('disabled' , false); 
                    }
             
                },
            });
        }
    })
    /*for  script start*/
    $(document).on('change','#tax_status',function(){
	   if($(this).val() != 0){
		   $('#tax_value_div').show();
		 }
	   else{
		   $('#tax_value_div').hide();
		 } 	 
	});
    /*End*/	
    /*Add New products custom products script start*/
    $(document).on('submit','#add_new_custom_products_by_admin',function(e){
	   	$('#add_products_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
		e.preventDefault();
        $.ajax({
            url: base_url+"/spare_products/save_custom_products",
            type: "POST",        
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData:false,  
            success: function(data){
                errorString = '';
                $('#car_compatible_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
                var parseJson = jQuery.parseJSON(data); 
                if(parseJson.status == 200){	
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                    setTimeout(function(){ location.reload(); } , 1000);
                }  
                if(parseJson.status == 400){
                    $.each(parseJson.error, function(key , value) {
                        errorString += '<div class="notice notice-danger"><strong>Wrong , </strong>'+ value+' .</div>';
                    });
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(errorString);	
                }
                if(parseJson.status == 100){	
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                }	 
            } , 
            error: function(xhr, error){
                $('#add_products_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
                $("#response_msg").html('<div class="notice notice-danger"><strong>Wrong , </strong>Something Went Wrong please try again  .</div>');
            },
            complete: function(e , xhr , setting){
               $('#add_products_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
            }
        }); 
    });
    /*End*/
});
</script>
@endpush
@push('custom_script')
<script src="{{ url('cdn/js/croppie.js') }}"></script>
@endpush