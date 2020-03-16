@extends('layouts.master_layouts')
@section('content')
<input type="hidden"  id="page" value="" />
<div class="card">
<div class="card-header bg-light header-elements-inline">
     <h6 class="card-title" style="font-weight:600;"><i class="fa fa-plus"></i>&nbsp;Edit Rim Details</h6>
</div>
    <div class="card-body">
        @if($rim != NULL)
         @csrf
        <form id="edit_rim_details_by_admin">
            <div class="form-group">
                <button type="submit" class="btn btn-success" style="float:;" id="edit_tyre_details">Save&nbsp; <span class="glyphicon glyphicon-plus"></span></button>
            </div>
            <input type="hidden" name="rim_id" id="rim_id" value="<?php if(!empty($rim->id))echo $rim->id;?>" readonly="readonly">
            <div class="container">
                <ul class="nav nav-tabs">
                    <li class="nav-item"><a class="nav-link active" href="#tab-general" data-toggle="tab">General</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tab-data" data-toggle="tab">Data</a></li>
                    <!-- <li class="nav-item"><a class="nav-link" href="#tab-attribute" data-toggle="tab">Attribute</a></li> -->
                    <li class="nav-item"><a class="nav-link" href="#tab-image" data-toggle="tab">Images</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tab-tyredescription" data-toggle="tab">Description</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tab-otherdetails" data-toggle="tab">Other Details</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab-general">
                        <div class="form-group required">
                            <div class="col-sm-10">
                                <input type="checkbox" name="for_pair" id="for_pair" value="1"<?php if(!empty($rim->rim_detail->for_pair)) { if($rim->rim_detail->for_pair == 1) echo "checked"; }?>/> &nbsp;&nbsp;&nbsp;
                                Is This Product Sell On Pair ?
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-name1">Manufacturer </label>
                            <div class="col-sm-10">
                                <input type="text" name="rim_manufacturer" id="rim_manufacturer"  placeholder="Manufacturer" class="form-control" value="<?php if(!empty($rim->decode_rim_response->manufacturer)) echo $rim->decode_rim_response->manufacturer; ?>" />
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-name1">Our  Products Name</label>
                            <div class="col-sm-10">
                                <input type="text" name="our_product_name" id="our_product_name"  placeholder="Our Products Name" class="form-control" value="<?php if(!empty($rim->rim_detail->our_product_name)) echo $rim->rim_detail->our_product_name; ?>" />
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-name1">Item Number</label>
                            <div class="col-sm-10">
                                <input type="text" name="tyre_item_id" id="tyre_item_id"  placeholder="Rim Item id" class="form-control" value="<?php if(!empty($rim->decode_rim_response->id)) echo $rim->decode_rim_response->id; ?>" readonly="readonly"/>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-name1">Rim Size </label>
                            <div class="col-sm-10">
                                <input type="text" name="size" id="size"  placeholder="Rim Size" class="form-control" value="<?php if(!empty($rim->decode_rim_response->size)) echo $rim->decode_rim_response->size; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">ET</label>
                            <div class="col-sm-10">
                               <input type="text" name="et" id="et"  placeholder="ET" class="form-control" value="<?php if(!empty($rim->decode_rim_response->ET)) echo $rim->decode_rim_response->ET; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Color</label>
                            <div class="col-sm-10">
                               <input type="text"  placeholder="Color"  class="form-control" name="color" value="<?php if(!empty($rim->rim_detail->color)) echo $rim->rim_detail->color; ?>" />

                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label">Meta Tag Title</label>
                            <div class="col-sm-10">
                                <input type="text"  placeholder="Meta Tag Title"  class="form-control" name="meta_title" value="<?php if(!empty($rim->rim_detail->meta_key_title)) echo $rim->rim_detail->meta_key_title; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-meta-keyword1">Meta Tag Keywords</label>
                            <div class="col-sm-10">
                                <textarea placeholder="Meta Tag Keywords" id="input-meta-keyword1" class="form-control" name="meta_keywords"><?php if(!empty($rim->rim_detail->meta_key_words)) echo $rim->rim_detail->meta_key_words; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab-data">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-price">Rim  Price <span class="text-danger">In Euro</span></label>
                            <div class="col-sm-10">
                              <input type="text" name="rim_price" value="<?php if(!empty($rim->rim_detail->decoded_response->price)) if(!is_object($rim->rim_detail->decoded_response->price)) echo $rim->rim_detail->decoded_response->price; ?>"  placeholder="Rim Price" id="input-price" class="form-control"  />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-price">Seller Price <span class="text-danger">In Euro</span></label>
                            <div class="col-sm-10">
                              <input type="text" name="seller_price"  placeholder="Seller Price" id="input-price" class="form-control" value="<?php if(!empty($rim->rim_detail->seller_price)) if(!is_object($rim->rim_detail->seller_price)) echo $rim->rim_detail->seller_price; ?>" />
                            </div>
                            <span id="seller_price_err"></span>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-price">Quantity </label>
                            <div class="col-sm-10">
                              <input type="text" name="quantity" placeholder="Products Quantity" id="input-price" value="<?php if(!empty($rim->rim_detail->products_quantity)) echo $rim->rim_detail->products_quantity; ?>" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-minimum"><span data-toggle="tooltip" title="Force a minimum stock quantity">Stock Warning</span></label>
                            <div class="col-sm-10">
                                <input type="text" name="stock_warning"  placeholder="Stock Warning" id="input-minimum" value="<?php if(!empty($rim->rim_detail->minimum_quantity)) echo $rim->rim_detail->minimum_quantity; ?>" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-tax-class">Tax </label>
                            <div class="col-sm-10">
                                <select  id="tax_status" class="form-control" name="tax" >
                                    <option value="0" <?php if(!empty($rim->rim_detail->tax)) if($rim->rim_detail->tax == 0) echo "Selected";  ?>> --- None --- </option>
                                    <option value="1" <?php if(!empty($rim->rim_detail->tax))  if($rim->rim_detail->tax == 1) echo "Selected";  ?>>Taxable</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" id="tax_value_div" style="display:none;">
                            <label class="col-sm-2 control-label" for="input-tax-class">Tax Amount</label>
                            <div class="col-sm-10">
                                 <input type="text" name="tax_value"  placeholder="Tax value" id="input-minimum" class="form-control"  value="<?php if(!empty($rim->rim_detail->tax_value)) echo $rim->rim_detail->tax_value; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-subtract">Subtract Stock</label>
                            <div class="col-sm-10">
                                <select  id="input-subtract" class="form-control" name="substract_stock">
                                    <option value="Y" <?php if(!empty($rim->rim_detail->substract_stock)) if($rim->rim_detail->substract_stock == 'Y'){ echo "Selected"; } ?>>Yes</option>
                                    <option value="N" <?php if(!empty($rim->rim_detail->substract_stock)) if($rim->rim_detail->substract_stock == 'N'){ echo "Selected"; } ?>>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-status">Status</label>
                            <div class="col-sm-10">
                                <select  id="input-status" class="form-control" name="products_status">
                                    <option value="A" <?php if(!empty($rim->rim_detail->substract_stock)) if(!empty($rim_details->status)) if($rim->rim_detail->status == "A") echo "Selected"; ?>>Publish</option>
                                    <option value="P" <?php if(!empty($rim_details->status))  if($rim->rim_detail->status == "P") echo "Selected"; ?>>Save in draft</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab-image">
                        <div class="table-responsive">
                            <div class="control-group" id="fields">
                                <label class="control-label" for="field1">Browse Multiple Image</label>
                                <div class="controls">
                                    <div class="entry input-group col-xs-3">
                                        <input class="btn btn-primary" name="rim_image[]" type="file" multiple="multiple">
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top:10px;" id="image_grid_section">
                                @if(!empty($rim->rim_detail->imageUrl))
                                  <div class="col-sm-4 col-md-3 col-lg-3">
                                        <div class="card">
                                            <div class="card-img-actions m-1">
                                            <img class="card-img img-fluid" src="{{ $rim->image }}" />
                                                <div class="card-img-actions-overlay card-img">
                                                    <a href="{{ $rim_details_response->imageUrl }}" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round" data-popup="lightbox" rel="group">
                                                        <i class="icon-plus3"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                @endif
                                @if($rim->images != NULL)
                                    @forelse($rim->images as $images)
                                     <div class="col-sm-4 col-md-3 col-lg-3 image_grid_col">
                                        <div class="card">
                                            <div class="card-img-actions m-1">
                                            <img class="card-img img-fluid" src="{{ $images->image_url}}" alt="" />
                                                <div class="card-img-actions-overlay card-img">
                                                    <a href="{{ $images->image_url }}" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round" data-popup="lightbox" rel="group">
                                                        <i class="icon-plus3"></i>
                                                    </a>
                                                    <a href='javascript::void()' data-imageid="{{ $images->id }}" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2 remove_rim_image">
                                                        <i class="icon-trash"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    @endforelse
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab-tyredescription">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-price">Rim Description</label>
                            <div class="col-sm-10">
                              <input type="text" name="rim_description" id="rim_description" value="<?php if(!empty($rim->rim_detail->decoded_response->description)) if(!is_object($rim->rim_detail->decoded_response->description)) echo $rim->rim_detail->decoded_response->description; ?>"  placeholder="Rim Description" class="form-control"  />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-price">Rim Description 1</label>
                            <div class="col-sm-10">
                             <input type="text" name="rim_description_1" id="rim_description_1"  placeholder="Rim Description 1 " id="input-price" value="<?php if(!empty($rim->rim_detail->decoded_response->description1)){ if(!is_object($rim->rim_detail->decoded_response->description1)) echo $rim->rim_detail->decoded_response->description1; else{ echo " "; } } ?>" class="form-control"  />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-price">Pr Description </label>
                            <div class="col-sm-10">
                              <input type="text" name="pr_description" id="pr_description"  placeholder="Pr Description" id="input-price" value="<?php if(!empty($rim->rim_detail->decoded_response->pr_description)){ if(!is_object($rim->rim_detail->decoded_response->pr_description)) echo $rim->rim_detail->decoded_response->pr_description; else{ echo " "; } } ?>
                                  " class="form-control"  />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-price">Manufacturer Description </label>
                            <div class="col-sm-10">
                              <input type="text" name="manufacturer_description" id="manufacturer_description"  placeholder="Manufacturer Description" id="input-price" value=" <?php if(!empty($rim->rim_detail->decoded_response->manufacturer_description)){ if(!is_object($rim->rim_detail->decoded_response->manufacturer_description)) echo $rim->rim_detail->decoded_response->manufacturer_description; else{ echo " "; } } ?>" class="form-control"  />
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab-otherdetails">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-price">Number of Holes</label>
                            <div class="col-sm-10">
                              <select class="form-control" name="number_of_holes">
                                   @if(count($holes_list) > 0)
                                     @foreach($holes_list as $key=>$value)
                                      <option value="{{ $value }}">{{ $value }}</option>
                                     @endforeach
                                   @endif
                              </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-price">Ean number</label>
                            <div class="col-sm-10">
                              <input type="text" name="tyre_description_1" id="tyre_description_1"  placeholder="Ean Number" id="input-price" value="<?php if(!empty($rim->rim_detail->decoded_response->ean_number)){ if(!is_object($rim->rim_detail->decoded_response->ean_number)) echo $rim->rim_detail->decoded_response->ean_number; else{ echo " "; }} ?>" class="form-control"  />
                            </div>
                        </div>
                        <div class="form-group">
                         <label class="col-sm-2 control-label" for="input-price">Wholesaler ArticleNo </label>
                            <div class="col-sm-10">
                              <input type="text" name="pr_description" id="pr_description"  placeholder="Pr Description" id="input-price" value="<?php if(!empty($rim->rim_detail->decoded_response->wholesalerArticleNo)){ if(!is_object($rim->rim_detail->decoded_response->wholesalerArticleNo)) echo $rim->rim_detail->decoded_response->wholesalerArticleNo; else{ echo " "; } } ?>" class="form-control"  />
                            </div>
                        </div>
                        <div class="form-group">
                         <label class="col-sm-2 control-label" for="input-price">Bar Code </label>
                            <div class="col-sm-10">
                              <input type="text" name="bar_code" id="bar_code"  placeholder="Bar Code" id="input-price" value="<?php if(!empty($rim->rim_detail->bar_code))echo $rim->rim_detail->bar_code; ?>" class="form-control"  />
                            </div>
                        </div>
                        <div class="form-group">
                         <label class="col-sm-2 control-label" for="input-price">Our Assemble Time </label>
                            <div class="col-sm-10">
                              <input type="text" name="our_assemble_time" id="our_assemble_time"  placeholder="Our Assembly time" id="input-price" value="<?php if(!empty($rim->rim_detail->our_assemble_time))echo $rim->rim_detail->our_assemble_time; ?>" class="form-control"  />
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end tab-content -->
            </div>
        </form>
        @else
          <h2>Something Went wrong !!!</h2>
        @endif
    </div>
</div>
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
            <a href="{{ url('/') }}" class="breadcrumb-item">Admin </a>
            <span class="breadcrumb-item active">Edit Rim Details</span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop
@push('scripts')
<script>
$(document).ready(function(e) {
 tax_status = $("#tax_status").val();	
 show_tax_div(tax_status);
 
function show_tax_div(tax_status){
 	if(tax_status == 1){
	 $("#tax_value_div").show();
	 }
	else{
     $("#tax_value_div").hide();
	 } 
}
	
  $(document).on('change','#tax_status',function(){
    show_tax_div( $(this).val() );
  });  
});
</script>
<script src="{{ url('global_assets/js/demo_pages/form_multiselect.js') }}"></script>
<script src="{{ url('global_assets/js/plugins/forms/selects/bootstrap_multiselect.js') }}">
</script>
<script src="{{ url('validateJS/rim_management.js') }}"></script>
<link href="{{ url('cdn/css/croppie.css') }}" />
@endpush
@push('custom_script')
<script src="{{ url('cdn/js/croppie.js') }}"></script>
@endpush