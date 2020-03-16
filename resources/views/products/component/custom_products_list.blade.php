<div class="card" id="user_data_body" style="overflow:auto">
        <div class="card-header bg-light header-elements-inline">
          <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;@lang('messages.productsList')</h6>
        </div>
         <table class="table table-bordered" id="products_list">
            <thead>
                <tr>
                    <th>@lang('messages.SN')</th>
                    <th>@lang('messages.ProductImage')</th>
                    <th>@lang('messages.ProductBrand')</th>
                    <th>@lang('messages.ProductItem')</th>
                    <th>@lang('messages.DescriptionOur')</th>
                    <!-- <th>@lang('messages.Price')</th> -->
                    <th>@lang('messages.SellerPrice')</th>
                    <th>@lang('messages.Quantity')</th>
                    <th>@lang('messages.Status')</th>
                    <th class="text-center">@lang('messages.Actions')</th>
                </tr>
            </thead>
            <tbody id="products_list_body">
                @forelse($custom_products as $product)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                      <img src="<?php if(!empty($product->image)) echo $product->image; ?>" class="img img-thumbnail" style="height:50px;"  />
                    </td>
                    <td>{{ !empty($product->listino) ? $product->listino : "N/A" }} </td>
                    <td>{{!empty($product->products_name) ? $product->products_name : "N/A" }}</td>
                    <td>{{ !empty($product->our_products_description) ? substr($product->our_products_description , 0 , 25) : "N/A" }}</td>
                    <!-- <td>{{ $product->price ?? "N/A" }}</td> -->
                     <td>&euro;&nbsp;{{ $product->seller_price ? $product->seller_price : "0" }}</td>
                    <td>{{ $product->products_quantiuty ?? "0" }}</td>
                    <td>
                      <?php
						if(!empty($product->products_status)){
                            if($product->products_status == "P"){
                                ?>
                                <a href="#" data-productsid="<?php if(!empty($product->id))echo $product->id; ?>" class="change_products_status" data-type='1' data-status="A">
                                    <i class="fa fa-toggle-off"></i></a>
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
                    <td>
                        <a href='{{ url("products/edit_custom_products/$product->p_id") }}' class="btn btn-primary btn-sm">
                            <i class="glyphicon glyphicon-edit"></i>
                        </a>
                        <a href="#" data-productsid="{{ $product->id }}" class="btn btn-info btn-sm get_custom_products_details">
                            <i class="glyphicon glyphicon-eye-open"></i>
                        </a>
                        <a href='#' class="btn btn-danger btn-sm delete_custom_product" data-productid="{{ $product->id }}" >
                            <i class="fa fa-trash"></i>
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
