<?php
//echo "<pre>";
//print_r($products);exit;
?>
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
                    <th>@lang('messages.Description')</th>
                    <th>@lang('messages.Price')</th>
                 
                </tr>
            </thead>
            <tbody id="products_list_body">
               @forelse($products_response as $product)
                  @php 
                  $p_id = encrypt($product->id);
                  $product->image = sHelper::get_mot_part_image($product->id);
                  @endphp
               <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
					@if(!empty($product->image))
                      <img src="<?php echo $product->image; ?>" class="img img-thumbnail" style="height:50px;"  />
                    @else
                    @endif
                    </td>
                    <td>{{ !empty($product->Listino) ? $product->Listino : "N/A" }} </td>
                    <td>{{!empty($product->CodiceArticolo) ? $product->CodiceArticolo: "N/A" }}</td>
                    <td>{{ !empty($product->Descrizione) ? substr($product->Descrizione , 0 , 25) : "N/A" }}</td>
                    <td>{{ $product->Prezzo ?? "N/A" }}</td>
                </tr>
               @empty
                <tr>
                    <td colspan="5">No Products Available</td>
                </tr>    
               @endforelse
            </tbody>
        </table>
    </div>
