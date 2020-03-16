<div class="card" id="user_data_body" style="overflow:auto">
        <div class="card-header bg-light header-elements-inline">
          <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;@lang('messages.productsList')</h6>
        </div>
         <table class="table table-bordered">
            <thead>
                <tr>
                    <th>@lang('messages.SN')</th>
                    <th>@lang('messages.ProductImage')</th>
                    <th>@lang('messages.ProductBrand')</th>
                    <th>Type</th>
                    <th>@lang('messages.ProductItem')</th>
                    <th>@lang('messages.Description')</th>
                    <th>@lang('messages.DescriptionOur')</th>
                    <th>@lang('messages.Price')</th>
                    <th>@lang('messages.SellerPrice')</th>
                    <th>@lang('messages.Quantity')</th>
                    <th>@lang('messages.Status')</th>
                    <th class="text-center">@lang('messages.Actions')</th>
                </tr>
            </thead>
            <tbody id="products_list_body">
               @forelse($products as $product)
                  @php 
                  $p_id = encrypt($product->id);
                  $product->pd_quantity = NULL;
			      $product->pd_status = 'A';
                  $product->image = sHelper::get_product_image($product->id);
                  $product_details = sHelper::get_products_details($product);
                    if($product_details != NULL){
                       $product->pd_quantity =  $product_details->products_quantiuty; 
                       $product->pd_status =  $product_details->products_status; 
                     }
                  @endphp
               <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
					@if(!empty($product->image))
                      <img src="<?php echo $product->image; ?>" class="img img-thumbnail" style="height:50px;"  />
                    @else
                    @endif
                    </td>
                    <td>{{ !empty($product->listino) ? $product->listino : "N/A" }} </td>
                     <th>{{ !empty($product->listino) ? $product->tipo : "N/A" }} </th>
                    <td>{{!empty($product->products_name) ? $product->products_name : "N/A" }}</td>
                    <td>{{ !empty($product->kromeda_description) ? substr($product->kromeda_description , 0 , 25) : "N/A" }}</td>
                    <td>{{ !empty($product->our_products_description) ? substr($product->our_products_description , 0 , 25) : "N/A" }}</td>
                    <td>{{ $product->price ?? "N/A" }}</td>
                     <td>&euro;{{ $product->seller_price ?? "0" }}</td>
                    <td>{{ $product->pd_quantity ?? "0" }}</td>
                    <td>
                      <?php
						if(!empty($product->products_status)){
						   if($product->products_status == "P"){
							   ?>
							   <a href="#" data-productsid="<?php if(!empty($product->id))echo $product->id; ?>" class="change_products_status" data-type='1' data-status="A"><i class="fa fa-toggle-off"></i></a>
							   <?php
							 }
						   else if($product->products_status == "A"){
							   ?>
							   <a href="#" data-type='1'  class="change_products_status" data-productsid="<?php if(!empty($product->id))echo $product->id; ?>" data-status="P"><i class="fa fa-toggle-on"></i></a>
							   <?php
							 }	 
						 }
						?>
                    </td>
                    <td><a href='{{ url("products/add_new_products/$p_id") }}' class="btn btn-primary btn-sm"  data-toggle="tooltip" data-placement="top" title="Edit">
                            <i class="glyphicon glyphicon-edit"></i>
                        </a>&nbsp;&nbsp;
                        <a href='javascript::void()' data-productsid="{{ $product->id }}" class="btn btn-warning btn-sm get_products_details"  data-toggle="tooltip" data-placement="top" title="View Details">
                            <i class="fa fa-info-circle"></i>
                        </a>&nbsp;&nbsp;
                        <a href='#' data-productsid="{{ $product->id }}" data-toggle="tooltip" data-placement="top" title="View All N3 category" data-productno="{{ $product->products_name }}" class="btn btn-info btn-sm get_all_n3_category">
                            <i class="glyphicon glyphicon-eye-open"></i>
                        </a>
                    </td>
                </tr>
               @empty
                <tr>
                    <td colspan="5">No Products Available</td>
                </tr>    
               @endforelse
            </tbody>
        </table>
    </div>
