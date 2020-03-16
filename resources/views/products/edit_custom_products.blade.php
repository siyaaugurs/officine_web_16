@extends('layouts.master_layouts')
@section('content')
<input type="hidden"  id="page" value="" />
<div class="card">
<div class="card-header bg-light header-elements-inline">
     <h6 class="card-title" style="font-weight:600;"><i class="fa fa-plus"></i>&nbsp;Edit Custom Products</h6>
</div>
    <div class="card-body">
        <form id="edit_new_custom_products_by_admin">
        <div class="form-group">
             <button type="submit" class="btn btn-success" style="float:;" id="edit_products_btn">Save&nbsp; <span class="glyphicon glyphicon-plus"></span></button>
        </div>
         @csrf
            <input type="hidden" name="custom_product_id" id="custom_product_id" value="{{ $product_details->id }}">
            <div class="container">
                <ul class="nav nav-tabs">
                    <li class="nav-item"><a class="nav-link active" href="#tab-links" data-toggle="tab">Category</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tab-general" data-toggle="tab">General</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tab-data" data-toggle="tab">Data</a></li>
                    <!-- <li class="nav-item"><a class="nav-link" href="#tab-attribute" data-toggle="tab">Attribute</a></li> -->
                    <li class="nav-item"><a class="nav-link" href="#tab-image" data-toggle="tab">Image</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tab-assembly" data-toggle="tab">Assemble Status</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tab-carcompatible" data-toggle="tab">Car Compatible</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab-links">
                        <div class="form-group">
                            <label>Category </label>
                            <select name="group_n1" id="group_n1" class="form-control" data-action="get_sub_category">
                            <option value="" hidden="hidden">--Select Category--</option>
                                @forelse($category_list_new as $category)
                                    <option value="{{ $category->id }}" data-type="{{ $category->type }}" <?= $category->group_id == $product_details->product_categories['product_group_group_id'] ? 'selected': ''  ?> >{{ $category->group_name }}</option>
                                @empty   
                                @endforelse
                                @forelse($all_custom_n1 as $category)
                                    <option value="{{ $category->id }}" data-type="{{ $category->type }}" <?= $category->id == $product_details->product_categories['product_category_n1'] ? 'selected': ''  ?>>{{ $category->group_name }}</option>
                                @empty  
                                @endforelse
                            </select>
                            <!-- <input type="text" class="form-control" name="group_n1" id="group_n1" value="@if(!empty($product_details->product_categories['product_category_n1'])){{ $product_details->product_categories['product_category_n1_name'] }} @endif" readonly="readonly"/> -->
                        </div>
                        <div class="form-group">
                            <label> Sub Category </label>
                            <input type="hidden" id="custom_n2_value" value="{{ $product_details->product_categories['product_group_n2_group'] }}">
                            <input type="hidden" id="custom_n2_type" value="{{ $product_details->product_categories['product_group_n2_type'] }}">
                            <input type="hidden" id="custom_n2_cat" value="{{ $product_details->product_categories['product_category_n2'] }}">
                            <select class="form-control" id="custom_sub_group"  name="custom_sub_group" >
                                <option value="">First Select Category</option>
                                <!-- <option value="@if(!empty($product_details->product_categories['product_category_n2'])){{ $product_details->product_categories['product_category_n2'] }} @endif" data-type="@if(!empty($product_details->product_categories['product_group_n2_type'])){{ $product_details->product_categories['product_group_n2_type'] }} @endif">@if(!empty($product_details->product_categories['product_category_n2_name'])){{ $product_details->product_categories['product_category_n2_name'] }} @endif</option>      -->
                            </select>
                        </div>
                       <div class="form-group">
                            <label>Category Items</label>
                            <input type="hidden" id="custom_n3_group" value="{{ $product_details->product_group_item->id ? $product_details->product_group_item->id : '' }}">
                            <select class="form-control"  name="custom_items" id="custom_items">
                                <option value="0">First Select Sub Category</option>
                                <!-- <option value="@if(!empty($product_details->product_group_item->id)){{ $product_details->product_group_item->id }} @endif">@if(!empty($product_details->product_group_item->item)){{ $product_details->product_group_item->item }} @endif {{ !empty($product_details->product_group_item->front_rear) ? $product_details->product_group_item->front_rear : '' }} {{ !empty($product_details->product_group_item->left_right) ? $product_details->product_group_item->left_right : '' }}</option> -->
                            </select>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab-general">
                        <div class="form-group required">
                            <div class="col-sm-10">
                                <input type="checkbox" name="for_pair" id="for_pair" value="1" <?php if(!empty($product_details)){  if(!empty($product_details->for_pair)) echo "checked"; }  ?> /> &nbsp;&nbsp;&nbsp;
                                Is This Product Sell On Pair ?
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-name1">Select Brand </label>
                            <div class="col-sm-10">
                              <select name="brand" id="brand" class="form-control">
                                <option value="" hidden="hidden">--Select Option--</option>
                                  @forelse($brands as $brand)
                                    <option value="<?php if(!empty($brand->brand_name)) echo $brand->brand_name ?>"  <?= $brand->brand_name == $product_details->listino ? 'selected' : ''  ?>><?php if(!empty($brand->brand_name)) echo $brand->brand_name ?></option>
                                  @empty
                                  <option value="0">No Brand Available !!!</option>
                                  @endif 
                                </select>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-name1">Item <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" name="products_name"  placeholder="Item" required class="form-control" value="<?php if(!empty($product_details->products_name)) echo $product_details->products_name; ?>" />
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-name1">Product Name</label>
                            <div class="col-sm-10">
                                <input type="text" name="product_name1"  placeholder="Product Name" class="form-control" value="<?php if(!empty($product_details->products_name1)) echo $product_details->products_name1; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Our Description</label>
                            <div class="col-sm-10">
                                <textarea  placeholder="Products Description" name="products_description" class="form-control"><?php if(!empty($product_details->our_products_description)) echo $product_details->our_products_description; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-sm-2 control-label" >Tag Title</label>
                            <div class="col-sm-10">
                                <input type="text"  placeholder="Meta Tag Title"  class="form-control" value="<?php if(!empty($product_details->meta_key_title)) echo $product_details->meta_key_title; ?>" name="meta_title"  />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-meta-keyword1">Meta Tag Keywords</label>
                            <div class="col-sm-10">
                                <textarea placeholder="Meta Tag Keywords" id="input-meta-keyword1" class="form-control" name="meta_keywords"><?php if(!empty($product_details->meta_key_words)) echo $product_details->meta_key_words; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab-data">
                      
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-price">Seller Price</label>
                            <div class="col-sm-10">
                              <input type="text" name="seller_price" value="<?php if(!empty($product_details->seller_price))echo $product_details->seller_price; ?>"  placeholder="Seller Price" id="input-price" class="form-control"  />
                            </div>
                            <span id="seller_price_err"></span>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-price">Quantity </label>
                            <div class="col-sm-10">
                              <input type="text" name="quantity" value="<?php if(!empty($product_details->products_quantiuty))echo $product_details->products_quantiuty; ?>"  placeholder="Products Quantity" id="input-price" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-tax-class">Tax </label>
                            <div class="col-sm-10">
                                <select  id="tax_status" class="form-control" name="tax">
                                    <option value="0" <?php if(!empty($product_details)){ if($product_details->tax == 0){ echo "Selected"; } } ?>> --- None --- </option>
                                    <option value="1" <?php if(!empty($product_details)){ if($product_details->tax == 1){ echo "Selected"; } } ?>>Taxable </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" id="tax_value_div" style="display:<?php if(!empty($product_details)) if($product_details->tax != 1) echo "none"; ?>;">
                            <label class="col-sm-2 control-label" for="input-tax-class">Tax Amount</label>
                            <div class="col-sm-10">
                                 <input type="text" name="tax_value"  placeholder="Tax value" id="input-minimum" class="form-control"  value="<?php if(!empty($product_details->tax_value)){ echo $product_details->tax_value; } ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-minimum"><span data-toggle="tooltip" title="Force a minimum ordered amount">Stock Warning</span></label>
                            <div class="col-sm-10">
                                <input type="text" name="stock_warning"  placeholder="Stock Warning" id="input-minimum" class="form-control" value="<?php if(!empty($product_details->minimum_quantity)){ echo $product_details->minimum_quantity; } ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-subtract">Subtract Stock</label>
                            <div class="col-sm-10">
                                <select  id="input-subtract" class="form-control" name="substract_stock">
                                    <option value="1" <?php if(!empty($product_details)) if($product_details->substract_stock == 1) echo "Selected"; ?>>Yes</option>
                                    <option value="0" <?php if(!empty($product_details)) if($product_details->substract_stock == 0) echo "Selected"; ?>>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-weight-class">Unit</label>
                            <div class="col-sm-10">
                                <select  id="input-weight-class" class="form-control" name="unit">
                                    <option value="Pcs" <?php if(!empty($product_details->unit)) if($product_details->unit == "Pcs") echo "Selected"; ?>>Pcs.</option>
                                    <option value="Kg" <?php if(!empty($product_details->unit)) if($product_details->unit == "Kg") echo "Selected"; ?>>Kilogram</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-status">Status</label>
                            <div class="col-sm-10">
                                <select  id="input-status" class="form-control" name="products_status">
                                    <option value="A" <?php if(!empty($product_details->products_status)) if($product_details->products_status == "A") echo "Selected"; ?>>Publish</option>
                                    <option value="P" <?php if(!empty($product_details->products_status))if($product_details->products_status == "P") echo "Selected"; ?>>Save in draft</option>
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
                                <label class="control-label" for="field1">Browse Multiple Image</label>
                                <div class="controls">
                                    <div class="entry input-group col-xs-3">
                                        <input class="btn btn-primary" name="products_gallery_image[]" type="file" multiple="multiple">
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top:10px;" id="image_grid_section">
                                @if($product_details->products_images != NULL)
                                    @forelse($product_details->products_images as $images)
                                    <div class="col-sm-4 col-md-3 col-lg-3 image_grid_view">
                                        <div class="card">
                                            <div class="card-img-actions m-1">
                                            <img class="card-img img-fluid" src="{{ $images->image_url}}" alt="" />
                                                <div class="card-img-actions-overlay card-img">
                                                    <a href="{{ $images->image_url }}" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round" data-popup="lightbox" rel="group">
                                                        <i class="icon-plus3"></i>
                                                    </a>
                                                    <a href='javascript::void();' data-imageid="{{ $images->id }}" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2 remove_products_image">
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
                    <div class="tab-pane" id="tab-assembly">
                        <div class="table-responsive">
                            <div class="control-group" id="fields">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="input-status">Products Assemble Status</label>
                                    <div class="col-sm-10">
                                        <select  id="input-status" class="form-control" name="products_assemble_status">
                                            <option value="N" {{ $product_details->assemble_status == 'N'  ? 'selected' : ''}}>No</option>
                                            <option value="Y" {{ $product_details->assemble_status == 'Y'  ? 'selected' : ''}}>Yes</option>
                                        
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="tab-pane" id="tab-carcompatible">
                        <div class="table-responsive">
                               <div class="form-group">
                                    <label>Select Makers</label>
                                        <select class="form-control makers" name="car_makers" data-action="get_models">
                                        <option value="0" hidden="hidden">Select Makers</option>
                                            <option value="1">All Cars</option>
                                            @foreach($cars__makers_category as $makers)
                                            <option value="@if(!empty($makers->idMarca)){{ $makers->idMarca }} @endif">@if(!empty($makers->Marca)){{ $makers->Marca }} @endif</option>
                                            @endforeach 
                                        </select> 
                                </div>
                               <div class="form-group">
                                    <label>Select Model</label>
                                    <select class="form-control models" name="car_models" data-action="get_versions">
                                        <option value="0">First Select Makers</option>
                                    </select>     
                                </div>
                               <div class="form-group">
                                    <label>Select Version  </label>
                                        <select class="form-control versions"  name="car_version" data-action="get_groups" >
                                        <option value=  "0">First Select Model</option>
                                    </select>
                                </div>
                               <div class="form-group">
                                    <label>Select Category </label>
                                    <select class="form-control groups" name="product_groups" data-action="get_sub_category">
                                        <option value="0" hidden="hidden">Select category </option>
                                        <option value="all">All category</option>
                                        @forelse($category_list_new as $category)
                                            <option value="{{ $category->id }}" data-type="{{ $category->type }}">{{ $category->group_name }}</option>
                                         @empty   
                                         @endforelse
                                         @forelse($all_custom_n1 as $category)
                                           <option value="{{ $category->id }}" data-type="{{ $category->type }}">{{ $category->group_name }}</option>
                                         @empty  
                                         @endforelse
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Select Sub Category </label>
                                        <select class="form-control sub_groups" id="sub_groups" name="sub_groups" data-action="get_n3_category">
                                        <option value="0">First Select Category</option>
                                     
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Select Items</label>
                                        <select class="form-control items"  name="items" id="items">
                                        <option value="0">First Select Sub Category</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-6 control-label" for="input-price">Kromeda time</span> </label>
                                    <div class="col-sm-10">
                                    <input type="text" name="assemble_kromeda_time" value="@if(!empty($times->time_hrs)){{ $times->time_hrs }} @endif"  placeholder="Kromeda time" id="input-price" class="form-control assemble_kromeda_time"  />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-6 control-label" for="input-price">Product Item number</span> </label>
                                    <div class="col-sm-10">
                                        <input type="text" name="item_number"  placeholder="Item Number" id="item_number" class="form-control"  />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-6 control-label" for="input-price">Assembly Time &nbsp; <span class="text-danger">* time in hour</span></label>
                                    <div class="col-sm-10">
                                    <input type="text" name="assemble_time" placeholder="Assemble Time" id="input-price" class="form-control assemble_time" value="<?php if(!empty($product_details->assemble_time)) echo $product_details->assemble_time; ?>"  />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <a href="javascript::void()" name="custom_car_compatible" id="custom_car_compatible" class="btn btn-success">Save car compatible </a>
                                </div>
                                    
                        </div>
                    </div>
                </div>
        <!-- end tab-content -->
            </div>
        </form>
    </div>
    <div class="card" style="margin-bottom:10px;">
    <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-filter"></i>&nbsp;Car compatible </h6>
        </div>
      <div class="content">
      <table class="table table-bordered" id="products_list">
                <thead>
                    <tr>
                        <th>@lang('messages.SN')</th>
                        <th>@lang('messages.Compatible')</th>
                        <th>Kromeda Time</th>
                        <th>@lang('messages.OurTime')</th>
                        <th>@lang('messages.Actions')</th>
                    </tr>
                </thead>
                <tbody id="item_number_list_id">
                    @if($compatible_details != NULL)
                    @forelse($compatible_details as $car_compitable)
                     @php   @endphp
                        @if(!empty($car_compitable->maker))  
                            @if($car_compitable->maker == "0")
                                @php $maker_name = "N/A";  @endphp
                            @elseif($car_compitable->maker == "1")
                               @php $maker_name = "All Makers";  @endphp
                            @else
                                @php 
                                    $maker_details =  \App\Maker::get_makers($car_compitable->maker);
                                    $maker_name = $maker_details->Marca;
                                @endphp
                            @endif
                        @else
                           @php $maker_name = "N/A";  @endphp
                        @endif
                        @if($car_compitable->model == "0")
                            @php
                                $model_name = "N/A";    
                            @endphp
                        @else 
                            @if($car_compitable->model == "1")
                                @php
                                    $model_name = "All Model";    
                                @endphp
                            @else
                                @php 
                                    $model_details =  \App\Models::get_model($car_compitable->model);
                                    $model_name = $model_details->idModello." >> ".$model_details->ModelloAnno;
                                @endphp 
                            @endif
                        @endif
                        
                        @if($car_compitable->version == "0")
                            @php
                                $version_name = "N/A";  
                            @endphp
                        @else 
                            @if($car_compitable->version == "all")
                                @php
                                    $version_name = "All Version";  
                                @endphp
                            @else
                                @php 
                                    $version_details = \App\Version::get_version($car_compitable->version); 
                                    $version_name = $version_details->Versione." >> ".$version_details->ModelloCodice;
                                @endphp 
                            @endif
                        @endif

                        @if($car_compitable->all_group == "1")
                            @php
                                $group = "All Group";  
                            @endphp
                        @elseif($car_compitable->group == "0")
                            @php
                                $group = "N/A";  
                            @endphp
                        @else
                            @php 
                                $group_details_new = \App\Products_group::get_group_first($car_compitable->group);
                                $group = $group_details_new['group_name'];
                            @endphp 
                        @endif

                        @if($car_compitable->all_sub_group == "1")
                            @php
                                $sub_group = "All Sub Group";  
                            @endphp
                        @elseif($car_compitable->sub_group == "0")
                            @php
                                $sub_group = "N/A";  
                            @endphp
                        @else
                            @php 
                                $sub_group_details_new = \App\Products_group::get_group_first($car_compitable->sub_group);
                                $sub_group = $sub_group_details_new['group_name'];
                            @endphp 
                        @endif

                        @if($car_compitable->all_item == "1")
                            @php
                                $item = "All Items";  
                            @endphp
                        @elseif($car_compitable->item == "0")
                            @php
                                $item = "N/A";  
                            @endphp
                        @else
                            @php 
                                $item_details = \App\ProductsGroupsItem::get_group_item($car_compitable->item);
                                $item = $item_details['item'];
                            @endphp 
                        @endif
                        @if(!empty($car_compitable->item_number))
                          @php $item_number = $car_compitable->item_number; @endphp
                        @else
                           @php $item_number = "N/A";  @endphp
                        @endif
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $maker_name ." / ". $model_name ." / ". $version_name. " / ". $group . " / " .$sub_group." / ".$item. " / ".$item_number }} </td>
                            <td>{{ !empty($car_compitable->k_time) ? $car_compitable->k_time : "N/A" }}</td>
                            <td>{{ $car_compitable->our_time ??  "N/A" }}</td>
                            <td>
                                <a href='javascript::void()' data-id="{{ $car_compitable->id }}" data-toggle="tooltip" data-placement="top" title="Delete" class="btn btn-danger btn-sm remove_car_compatible" ><i class="fa fa-trash"></i></a>
                                <a href='#' data-id="{{ $car_compitable->id }}" data-toggle="tooltip" data-placement="top" title="Edit" class="btn btn-primary btn-sm edit_car_compatible" ><i class="fa fa-edit"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                          <td colspan="5">Car Compatible Not Available</td>
                        </tr>
                    @endforelse
                    @else 
                    <tr>
                        <td colspan="5">Car Compatible Not Available</td>
                    </tr>
                    @endif
                </tbody>
            </table>
      </div>
</div>
</div>
<!--Edit Modal script start-->
<div class="modal" id="edit_car_compatible_kromeda_time">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i>
                </button>
                <h4 class="modal-title" id="myModalLabel">Edit Assemble Time </h4>
                <hr />
            </div>
            <!-- Modal body -->
            <div class="card-body" id="respnse_msg_page">
               <form id="edit_car_compatible_kromeda_time_form" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <span id="title_err"></span>
                            <label>Our Assembly Time  &nbsp;<span class="text-danger">*</span></label>
                            <input type="hidden" readonly="readonly" class="form-control"  name="car_compatible_id" id="car_compatible_id" />
                            <input type="text" class="form-control" placeholder="Our Assembly Time" name="our_assemble_time" id="our_assemble_time" />
                        </div>
                        <div class="form-group">
                            <span id="title_err"></span>
                            <label>Kromeda Time  &nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="Kromeda Time" name="kromeda_time" id="kromeda_time" />
                        </div>
                        <div class="form-group">
                            <span id="title_err"></span>
                            <label>Kromeda Products Item  &nbsp;<span class="text-danger">*</span></label>
                              <input type="text" name="item_number"  placeholder="Item Number" id="item_number_edit" class="form-control"  />
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-check form-check-inline">
                                <button type="button" id="save_assemble_time" class="btn bg-blue ml-3">Save<i class="icon-paperplane ml-2"></i></button>
                            </div>
                        </div>
                    </form>
            </div>
            <div id="response_err"></div>
        </div>
        <div class="modal-footer"></div>
    </div>
</div>
<!--End-->
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
<script src="{{ asset('validateJS/car_compatible.js') }}"></script>
<script src="{{ url('global_assets/js/demo_pages/form_multiselect.js') }}"></script>
<script src="{{ url('global_assets/js/plugins/forms/selects/bootstrap_multiselect.js') }}">
</script>
 <script src="{{ asset('validateJS/special_conditions_cars.js') }}"></script>
 <script src="{{ asset('validateJS/custom_spare_parts.js') }}"></script>
<!-- <script src="{{ url('validateJS/products_groups.js') }}"></script> -->
<!--<script src="{{ asset('validateJS/custom_product.js') }}"></script>-->
<script src="{{ url('validateJS/products.js') }}"></script>
<link href="{{ url('cdn/css/croppie.css') }}" />
<script>
/*End*/
$(document).ready(function(e){
    var c_group = $('#group_n1').val();
    var c_sub_group = $('#custom_n2_value').val();
    var c_sub_group_type = $('#custom_n2_type').val();
    var custom_sub_group = $('#custom_n2_cat').val();
    var c_n3_id = $('#custom_n3_group').val();
    var c_language = $('html').attr('lang');
    var c_type = 2;
    get_n2_custom_category(c_group, c_sub_group, c_language, c_type, c_sub_group_type, custom_sub_group);
    custom_n3_category(custom_sub_group, c_n3_id, c_language, c_type);
    /*Edit */
     $(document).on('click','#save_assemble_time',function(e){
        e.preventDefault();
        var button = $(this);
        html_content = button.html();
        our_assemble_time = $("#our_assemble_time").val();
        kromeda_time = $('#edit_car_compatible_kromeda_time_form #kromeda_time').val();
        button.html('Please wait <i class="icon-spinner2 spinner"></i>');
        $.ajax({
            url: base_url+"/spare_products/save_assemble_timing",
            type: "GET",        
            data: {our_time:$("#our_assemble_time").val() , car_compatible_id:$("#car_compatible_id").val(), item_number:$("#item_number_edit").val() , kromeda_time:kromeda_time},
            success: function(data){
               $('.close').click();
               button.html(html_content);
               parseJson = jQuery.parseJSON(data);
                $("#msg_response_popup").modal('show');
                $("#msg_response").html(parseJson.msg); 
            },
            error: function(xhr, error){
                $("#msg_response_popup").modal('show');
                $("#msg_response").html('<div class="notice notice-danger"><strong>Wrong , </strong> Something Went wrong please try again  .</div>');
            },
            complete:function(e , xhr , setting){
              $("#msg_response_popup").modal('show');
              $("#msg_response").html(parseJson.msg);
            }
        }); 
    });
    /*Edit Car compatible Script start*/
    $(document).on('click','.edit_car_compatible',function(e){
      var button = $(this);
      html_content = button.html();
      e.preventDefault();
      id =  $(this).data('id');
      if(id != ""){
        button.html('<i class="icon-spinner2 spinner"></i>');
         $.ajax({
            url: base_url+"/spare_products/get_compatible_details",
            type: "GET",        
            data: {id:id},
            success: function(data){
               button.html(html_content);
               parseJson = jQuery.parseJSON(data);
               $("#car_compatible_id").val(id);
               $("#our_assemble_time").val(parseJson.response.our_time);
               $("#edit_car_compatible_kromeda_time #kromeda_time").val(parseJson.response.k_time);
                $("#item_number_edit").val(parseJson.response.item_number);
            },
            error: function(xhr, error){
                $("#msg_response_popup").modal('show');
                $("#msg_response").html(parseJson.msg);
            },
            complete:function(e , xhr , setting){
               if(e.status == 200){
                 $("#edit_car_compatible_kromeda_time").modal({
                    backdrop: 'static',
                    keyboard: false,
                 }); 
                 }
            }
          });   
        } 
    });
    /*End*/
     $(document).on('click' , '#custom_car_compatible' , function(e){
        e.preventDefault();
        $('#msg_response').html(" ");
        $("err_response").html(" ");
        $('#custom_car_compatible').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
        var makers = $('.makers').val();
        var models = $('.models').val();
        var versions = $('.versions').val();
        var groups = $('.groups').val();
        var sub_groups = $('.sub_category').val();
        var items = $('.items').val();
        var product_id = $('#custom_product_id').val();
        var our_time = $('.assemble_time').val();
        var item_number = $('#item_number').val();
        var kromeda_time = $('#tab-carcompatible .assemble_kromeda_time').val();
        var form_data = new FormData();
        form_data.append('makers', makers);
        form_data.append('models', models);
        form_data.append('versions', versions);
        form_data.append('groups', groups);
        form_data.append('sub_groups', sub_groups);
        form_data.append('items', items);
        form_data.append('product_id', product_id);
        form_data.append('our_time', our_time);
        form_data.append('k_time', kromeda_time);
        form_data.append('item_number' , item_number)
        console.log(form_data);
        $.ajax({
            url: base_url+"/spare_products/add_car_compatible",
            type: "POST",        
            data: form_data,
            contentType: false,
            cache: false,
            processData:false,  
            success: function(data){
                errorString = '';
                $('#custom_car_compatible').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
                var parseJson = jQuery.parseJSON(data); 
                if(parseJson.status == 200){    
                    $("#msg_response_popup").modal({
                        backdrop:'static',
                        keyboard:false,
                    });
                    $("#msg_response").html(parseJson.msg);
                    setTimeout(function(){ location.reload(); } , 1000);
                }  
                if(parseJson.status == 100){    
                    $("#msg_response_popup").modal({
                        backdrop:'static',
                        keyboard:false,
                    });
                    $("#msg_response").html(parseJson.msg);
                }   
            } , 
            error: function(xhr, error){
                $('#service_group_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
                $("#response_msg").html(parseJson.msg);
            }
        });
    });
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
    /*Edit New products custom products script start*/
    $(document).on('submit','#edit_new_custom_products_by_admin',function(e){
        $('#response').html(" ");
        $("err_response").html(" ");
        $('#edit_products_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
        e.preventDefault();
        $.ajax({
            url: base_url+"/spare_products/edit_custom_products",
            type: "POST",        
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData:false,  
            success: function(data){
                errorString = '';
                $('#edit_products_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
                var parseJson = jQuery.parseJSON(data); 
                if(parseJson.status == 200){    
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                    var url = base_url+"/products/list_custom_products";
                    setTimeout(function(){ window.location.href = url; } , 1000);
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
                $('#edit_products_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
                $("#response_msg").html(parseJson.msg);
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