<table class="table table-bordered">
            <thead>
                <tr>
                    @if($checkbox == 1)
                    <th><input type="checkbox" id="select_all" name="all_products_check" /></th>
                    @endif
                    <th>@lang('messages.SN')</th>
                    <th>@lang('messages.ProductImage')</th>
                    <th>@lang('messages.ProductBrand')</th>
                    <th>@lang('messages.ProductItem')</th>
                    <th>@lang('messages.Description')</th>
                    <th>@lang('messages.DescriptionOur')</th>
                    <th>@lang('messages.Price')</th>
                    <th>@lang('messages.SellerPrice')</th>
                     <th>@lang('messages.Status')</th>
                    <th class="text-center">@lang('messages.Actions')</th>
                </tr>
            </thead>
            <tbody>
               @forelse($products as $product)
                  @php 
                  $p_id = encrypt($product->id);
                  $product->image = sHelper::get_product_image($product->id);
                  @endphp
               <tr>
                   @if($checkbox == 1)
                    <td><input type="checkbox" class="products" name="products[]" value="{{ $product->id }}"  /></td>
                     @endif
                    <td>{{ $loop->iteration }}</td>
                    <td>
					@if(!empty($product->image))
                      <img src="<?php echo $product->image; ?>" class="img img-thumbnail" style="height:50px;"  />
                    @else
                    @endif
                    </td>
                    <td>@if(!empty($product->products_name)){{ $product->products_name ?? "N/A" }} @endif </td>
                    <td>@if(!empty($product->kromeda_products_id)){{ $product->kromeda_products_id ?? "N/A" }} @endif</td>
                    <td>@if(!empty($product->kromeda_description)){{ substr($product->kromeda_description , 0 , 25) ?? "N/A" }} @endif</td>
                    <td>@if(!empty($product->our_products_description)){{ substr($product->our_products_description , 0 , 25) ?? "N/A" }} @endif</td>
                    <td>{{ $product->price ?? "N/A" }}</td>
                    <td>{{ $product->seller_price ?? "N/A" }}</td>
                    <td>
                      <?php
						if(!empty($product->pa_status)){
						   if($product->pa_status == "A"){
							   ?>
							   <a href="#" data-productsid="<?php if(!empty($product->id))echo $product->id; ?>" class="change_products_assemble_status" data-type='1' data-status="P"><i class="fa fa-toggle-on"></i></a>
							   <?php
							 }
						   else if($product->pa_status == "P"){
							   ?>
							   <a href="#" data-type='1'  class="change_products_assemble_status" data-productsid="<?php if(!empty($product->id))echo $product->id; ?>" data-status="A"><i class="fa fa-toggle-off"></i></a>
							   <?php
							 }	 
						 }
						?>
                    </td>
                    <td>
                    &nbsp;<a href='#' data-productsid="{{ $product->id }}" class="btn btn-info get_assemble_products_details">
                        <i class="fa fa-eye"></i>
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