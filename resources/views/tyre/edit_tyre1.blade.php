@extends('layouts.master_layouts')
@section('content')
<input type="hidden"  id="page" value="" />
<div class="card">
<div class="card-header bg-light header-elements-inline">
     <h6 class="card-title" style="font-weight:600;"><i class="fa fa-plus"></i>&nbsp;Edit Tyre Details</h6>
</div>
    <div class="card-body">
        @if($tyre != NULL)
         @csrf
        <form id="edit_tyre_details_by_admin">
        <div class="form-group">
             <button type="submit" class="btn btn-success" style="float:;" id="edit_tyre_details">Save&nbsp; <span class="glyphicon glyphicon-plus"></span></button>
        </div>
            <input type="hidden" name="tyres_id" id="tyres_id" value="<?php if(!empty($tyre->id))echo $tyre->id;?>" readonly="readonly">
            <input type="hidden" name="type_status" id="type_status" value="<?php if(!empty($tyre->type_status))echo $tyre->type_status;?>" readonly="readonly">
            <div class="container">
                <ul class="nav nav-tabs">
                    <li class="nav-item"><a class="nav-link active" href="#tab-general" data-toggle="tab">General</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tab-data" data-toggle="tab">Data</a></li>
                    <!-- <li class="nav-item"><a class="nav-link" href="#tab-attribute" data-toggle="tab">Attribute</a></li> -->
                    <li class="nav-item"><a class="nav-link" href="#tab-image" data-toggle="tab">Images</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tab-tyredescription" data-toggle="tab">Tyre Description</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tab-others" data-toggle="tab">Others</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab-general">
                        <div class="form-group required">
                            <div class="col-sm-10">
                                <input type="checkbox" name="for_pair" id="for_pair" value="1" <?php if(!empty($tyre->pair)){ if($tyre->pair == 1) echo "checked"; } ?>/> &nbsp;&nbsp;&nbsp;
                                Is This Product Sell On Pair ?
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-name1">Tyre24 Item Number </label>
                            <div class="col-sm-10">
                                <input type="text" name="tyre_item_id" id="tyre_item_id"  placeholder="Tyre" class="form-control" value="<?php if(!empty($tyre->itemId)) if(!is_object($tyre->itemId)) echo $tyre->itemId; ?>" readonly="readonly"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Wet Grip</label>
                            <div class="col-sm-10">
                               <!-- <input type="text" name="wet_grip" id="wet_grip"  placeholder="Wet Grip" class="form-control" value="<?php if(!empty($tyre->tyre_details->wetGrip)) if(!is_object($tyre->tyre_details->wetGrip)) echo $tyre->tyre_details->wetGrip; ?>" /> -->
                               <select name="wet_grip" id="wet_grip" class="form-control">
                                    <option value="" hidden="hidden">--Select Option--</option>
                                    <option value="A" <?= $tyre->tyre_details->wetGrip == "A" ? 'selected' : '' ?>>A</option>
                                    <option value="B" <?= $tyre->tyre_details->wetGrip == "B" ? 'selected' : '' ?>>B</option>
                                    <option value="C" <?= $tyre->tyre_details->wetGrip == "C" ? 'selected' : '' ?>>C</option>
                                    <option value="D" <?= $tyre->tyre_details->wetGrip == "D" ? 'selected' : '' ?>>D</option>
                                    <option value="E" <?= $tyre->tyre_details->wetGrip == "E" ? 'selected' : '' ?>>E</option>
                                    <option value="F" <?= $tyre->tyre_details->wetGrip == "F" ? 'selected' : '' ?>>F</option>
                                    <option value="G" <?= $tyre->tyre_details->wetGrip == "G" ? 'selected' : '' ?>>G</option>
                               </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Our Description</label>
                            <div class="col-sm-10">
                                <textarea  placeholder="Our Tyre Description" name="our_tyre_description" id="our_tyre_description" class="form-control"><?php if(!empty($tyre->our_description)) echo $tyre->our_description; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label">Meta Tag Title</label>
                            <div class="col-sm-10">
                                <input type="text"  placeholder="Meta Tag Title"  class="form-control" name="meta_title" value="<?php if(!empty($tyre->meta_key_title)) echo $tyre->meta_key_title; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-meta-keyword1">Meta Tag Keywords</label>
                            <div class="col-sm-10">
                                <textarea placeholder="Meta Tag Keywords" id="input-meta-keyword1" class="form-control" name="meta_keywords"><?php if(!empty($tyre->meta_key_word)) echo $tyre->meta_key_word; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab-data">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-price">Tyre24  Price <span class="text-danger">In Euro</span></label>
                            <div class="col-sm-10">
                              <input type="text" name="tyre24_price" value="<?php if(!empty($tyre->tyre_resp->price)) if(!is_object($tyre->tyre_resp->price)) echo $tyre->tyre_resp->price; ?>"  placeholder="Tyre24 Price" id="input-price" class="form-control"  />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-price">Seller Price <span class="text-danger">In Euro</span></label>
                            <div class="col-sm-10">
                              <input type="text" name="seller_price"  placeholder="Seller Price" id="input-price" class="form-control" value="<?php if(!empty($tyre->seller_price)) echo $tyre->seller_price; ?>" />
                            </div>
                            <span id="seller_price_err"></span>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-price">Quantity </label>
                            <div class="col-sm-10">
                              <input type="number" name="quantity" placeholder="Products Quantity" id="input-price" value="<?php if(!empty($tyre->quantity)) echo $tyre->quantity; ?>" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-minimum"><span data-toggle="tooltip" title="Force a minimum stock quantity">Stock Warning</span></label>
                            <div class="col-sm-10">
                                <input type="text" name="stock_warning"  placeholder="Stock Warning" id="input-minimum" value="<?php if(!empty($tyre->stock_warning)) echo $tyre->stock_warning; ?>" class="form-control" value="" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-tax-class">Tax </label>
                            <div class="col-sm-10">
                                <select  id="tax_status" class="form-control" name="tax">
                                    <option value="0" <?php if($tyre->tax == 0) echo "Selected";  ?>> --- None --- </option>
                                    <option value="1" <?php if($tyre->tax == 1) echo "Selected";  ?>>Taxable</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" id="tax_value_div" style="display:none;">
                            <label class="col-sm-2 control-label" for="input-tax-class">Tax Amount</label>
                            <div class="col-sm-10">
                                 <input type="text" name="tax_value"  placeholder="Tax value" id="input-minimum" class="form-control"  value="<?php if($tyre->tax_value) echo $tyre->tax_value; ?>" />
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
                                    <option value="Pcs" <?php if($tyre->tax == "Pcs") echo "Selected";  ?>>Pcs.</option>
                                    <option value="Kg" <?php if($tyre->tax == "Kg") echo "Selected";  ?>>Kilogram</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-status">Status</label>
                            <div class="col-sm-10">
                                <select  id="input-status" class="form-control" name="products_status">
                                    <option value="A" <?php if($tyre->status == "A") echo "Selected"; ?>>Publish</option>
                                    <option value="P" <?php if($tyre->status == "S") echo "Selected"; ?>>Save in draft</option>
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
                                        <input class="btn btn-primary" name="tyre_image[]" type="file" multiple="multiple">
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top:10px;" id="image_grid_section">
                                @if(!empty($tyre->tyre_resp->imageUrl))
                                @php   $image = serviceHelper::set_tyre_image($tyre->tyre_resp->imageUrl);@endphp
                                  <div class="col-sm-4 col-md-3 col-lg-3">
                                        <div class="card">
                                            <div class="card-img-actions m-1">
                                            <img class="card-img img-fluid" src="{{ $image}}" alt="" />
                                                <div class="card-img-actions-overlay card-img">
                                                    <a href="{{ $image }}" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round" data-popup="lightbox" rel="group">
                                                        <i class="icon-plus3"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                @endif
                                @if(!empty($tyre->tyre_image))
                                    @forelse($tyre->tyre_image as $images)
                                     <div class="col-sm-4 col-md-3 col-lg-3 tyre_grid_col">
                                        <div class="card">
                                            <div class="card-img-actions m-1">
                                            <img class="card-img img-fluid" src="{{ $images->image_url}}" alt="" />
                                                <div class="card-img-actions-overlay card-img">
                                                    <a href="{{ $images->image_url }}" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round" data-popup="lightbox" rel="group">
                                                        <i class="icon-plus3"></i>
                                                    </a>
                                                    <a href='javascript::void()' data-imageid="{{ $images->id }}" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2 remove_tyre_image">
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
                            <label class="col-sm-2 control-label" for="input-price">Vehicles Tyre Type</label>
                            <div class="col-sm-10">
                                <select name="tyre_type" class="form-control">
                                    <option value="" hidden="hidden">--Select--Vehicles--Tyre--Type--</option>
                                     <?php 
                                       foreach($tyre_type as $t_type){
                                             ?>
                                            <option value="<?php if(!empty($t_type['code'])) echo $t_type['code']; ?>" <?php if($t_type['code'] == $tyre->vehicle_tyre_type)echo "Selected"; ?>>
                                               <?php if(!empty($t_type['name'])) echo $t_type['name']; ?>
                                            </option>
                                             <?php
                                          }
                                     ?>
                                   </select>  
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-price">Season Tyre Type</label>
                            <div class="col-sm-10">
                                <select name="season_tyre_type" class="form-control">
                                    <option value="" hidden="hidden">--Select--Season--Tyre--Type--</option>
                                     <?php 
                                       foreach($season_tyre_type as $t_type){
                                             ?>
                                            <option value="<?php if(!empty($t_type['code'])) echo $t_type['code']; ?>" <?php if($t_type['code'] == $tyre->season_tyre_type)echo "Selected"; ?>>
                                               <?php if(!empty($t_type['name'])) echo $t_type['name']; ?>
                                            </option>
                                             <?php
                                          }
                                     ?>
                                   </select>  
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">is3PMSF</label>
                            <div class="col-sm-10">
                              <input type="text" name="is3PMSF" id="is3PMSF" value="<?php if(!empty($tyre->tyre_resp->is3PMSF)) { echo  $tyre->tyre_resp->is3PMSF; } else { echo 0;  }?>"  placeholder="is3PMSF" class="form-control"  />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Weight</label>
                            <div class="col-sm-10">
                              <input type="text" name="weight" id="weight" value="<?php if(!empty($tyre->tyre_resp->weight)) { if(!is_object($tyre->tyre_resp->weight)) echo  $tyre->tyre_resp->weight; else echo 0; } else echo 0;  ?>"  placeholder="Weight" class="form-control"  />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-price">Tyre24  Description</label>
                            <div class="col-sm-10">
                              <input type="text" name="tyre24_description" id="tyre24_description" value="<?php if(!empty($tyre->tyre_resp->description)) if(!is_object($tyre->tyre_resp->description)) echo $tyre->tyre_resp->description; ?>"  placeholder="Tyre24 Description" class="form-control"  />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-price">Tyre Description 1</label>
                            <div class="col-sm-10">
                              <input type="text" name="tyre_description_1" id="tyre_description_1"  placeholder="Tyre Description 1 " id="input-price" value="<?php if(!empty($tyre->tyre_resp->description1)) if(!is_object($tyre->tyre_resp->description1)) echo  $tyre->tyre_resp->description1; else{ echo "N/A"; }?>" class="form-control"  />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-price">Pr Description </label>
                            <div class="col-sm-10">
                              <input type="text" name="pr_description" id="pr_description"  placeholder="Pr Description" id="input-price" value="<?php if(!empty($tyre->tyre_resp->pr_description)) if(!is_object($tyre->tyre_resp->pr_description)) echo $tyre->tyre_resp->pr_description; else{ echo "N/A"; }?>" class="form-control"  />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="input-price">Manufacturer Description </label>
                            <div class="col-sm-10">
                                <select name="manufacturer_description" class="form-control">
                                    <option value="0">--Select--Manufacturer--</option>
                                     <?php 
                                       foreach($manufacturer as $manufacturer){
                                             ?>
                                            <option value="<?php if(!empty($manufacturer['brand_name'])) echo $manufacturer['brand_name']; ?>" <?php if($manufacturer['brand_name'] == $tyre->tyre_resp->manufacturer_description)echo "Selected"; ?>>
                                               <?php if(!empty($manufacturer['brand_name'])) echo $manufacturer['brand_name']; ?>
                                            </option>
                                             <?php
                                          }
                                     ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" id="tax_value_div" style="display:none;">
                            <label class="col-sm-2 control-label" for="input-tax-class">Tax Amount</label>
                            <div class="col-sm-10">
                                 <input type="text" name="tax_value"  placeholder="Tax value" id="input-minimum" class="form-control"  value="<?php if(!empty($tyre->tax_value)) echo $tyre->tax_value; ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab-others">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-price" style="display:<?= $tyre->tyre_details->tyreLabelUrl ? 'block' : 'none' ?>">Tyre Label Image </label>
                            <div class="col-sm-10">
                              <div class="row" style="margin-top:10px;" id="image_grid_section">
                                @if(!empty($tyre->tyre_details->tyreLabelUrl))
                                  <div class="col-sm-4 col-md-3 col-lg-3">
                                        <div class="card">
                                            <div class="card-img-actions m-1">
                                            <img class="card-img img-fluid" src="{{ $tyre->tyre_details->tyreLabelUrl }}" alt="" />
                                                <div class="card-img-actions-overlay card-img">
                                                    <a href="{{ $tyre->tyre_details->tyreLabelUrl }}" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round" data-popup="lightbox" rel="group">
                                                        <i class="icon-plus3"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                @endif
                              </div>  
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-price">Match code</label>
                            <div class="col-sm-10">
                              <input type="text" name="matchcode" id="matchcode"  placeholder="Match Code" value="<?php if(!empty($tyre->tyre_resp->matchcode)) if(!is_object($tyre->tyre_resp->matchcode)) echo  $tyre->tyre_resp->matchcode; else{ echo "N/A"; }?>" class="form-control"  />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-weight-class">Ean Number</label>
                            <div class="col-sm-10">
                                <input type="text" name="ean_number" id="ean_number"  placeholder="Ean Number" value="<?php if(!empty($tyre->tyre_resp->ean_number))  if(!is_object($tyre->tyre_resp->ean_number)) echo  $tyre->tyre_resp->ean_number; else{ echo "N/A"; }?>" class="form-control"  />  
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-status">Whole saler Article Id</label>
                            <div class="col-sm-10">
                              <input type="text" name="whole_saller_article_id" id="whole_saller_article_id"  placeholder="Whole saler Article Id" value="<?php if(!empty($tyre->tyre_resp->wholesalerArticleNo)) if(!is_object($tyre->tyre_resp->wholesalerArticleNo)) echo  $tyre->tyre_resp->wholesalerArticleNo; else{ echo "N/A"; }?>" class="form-control"  />  
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-status">Run Flat</label>
                            <div class="col-sm-10">
                              <!-- <input type="text" name="run_flat" id="run_flat"  placeholder="Run Flat" value="<?php echo $tyre->runflat; ?>" class="form-control"  />  -->
                              <select name="run_flat" id="run_flat" class="form-control">
                                 <option value="1" <?= $tyre->runflat == '1' ? 'selected' : '' ?>>Yes</option>
                                 <option value="0" <?= $tyre->runflat == '0' ? 'selected' : '' ?>>No</option>
                              </select> 
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-status">Rein Forced</label>
                            <div class="col-sm-10">
                              <!-- <input type="text" name="reinforced" id="reinforced"  placeholder="Reinforced " value="<?php echo $tyre->reinforced;?>" class="form-control"  />   -->
                              <select name="reinforced" id="reinforced" class="form-control">
                                 <option value="1" <?= $tyre->reinforced == '1' ? 'selected' : '' ?>>Yes</option>
                                 <option value="0" <?= $tyre->reinforced == '0' ? 'selected' : '' ?>>No</option>
                              </select> 
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="input-status">3 Peak Mountain Snowflake</label>
                            <div class="col-sm-10">
                              <select class="form-control" name="peak_mountain_snowflake" id="peak_mountain_snowflake">
                                <option value="" hidden="hidden">--Select Option--</option>
                                <option value="1" <?= $tyre->peak_mountain_snowflake == "1" ? 'selected' : '' ?>>Yes</option>
                                <option value="0" <?= $tyre->peak_mountain_snowflake == "0" ? 'selected' : '' ?>>No</option>
                              </select>
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
            <span class="breadcrumb-item active">Products</span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop
@push('scripts')
<script>
$(document).ready(function(e) {
  $(document).on('change','#tax_status',function(){
    tax_status = $(this).val();
    if(tax_status == 1){
     $("#tax_value_div").show();
     }
    else{
     $("#tax_value_div").hide();
     } 
  });  
});
</script>
<script src="{{ url('global_assets/js/demo_pages/form_multiselect.js') }}"></script>
<script src="{{ url('global_assets/js/plugins/forms/selects/bootstrap_multiselect.js') }}">
</script>
<script src="{{ url('validateJS/tyre_custom.js') }}"></script>
<link href="{{ url('cdn/css/croppie.css') }}" />
@endpush
@push('custom_script')
<script src="{{ url('cdn/js/croppie.js') }}"></script>
@endpush