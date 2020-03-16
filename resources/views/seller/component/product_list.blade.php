<table class="table" id="inventory_product">
    <thead>
        <tr>
            <th>@lang('messages.SN')</th>
            <th>@lang('messages.ProductItem')</th>
            <th>Product EAN</th>
            <th>@lang('messages.Price')</th>
            <th>@lang('messages.Quantity')</th>
            <th>@lang('messages.StockWarning')</th>
            <th>@lang('messages.Status')</th>
            <th class="text-center">@lang('messages.Actions')</th>
        </tr>
    </thead>
    <tbody>
        @forelse($products as $product)
        @php 
            $p_id = encrypt($product->id);
        @endphp
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $product->product_new_product_name }}</td>
            <td>{{ $product->product_new_details_bar_code ?? "N/A" }}</td>
            <td>&euro;&nbsp;{{ $product->products_sale_price ?? "N/A" }}</td>
            <td>{{ $product->quantity ?? "N/A" }}</td>
            <td>{{ $product->stock_warning ?? "N/A" }}</td>
            <td>
            @if($product->status == "A")
                <a href="#" data-productsid="<?php if(!empty($product->id))echo $product->id; ?>" class="change_seller_products_status" data-status="P"><i class="fa fa-toggle-on"></i></a>
            @else
                <a href="#" data-productsid="<?php if(!empty($product->id))echo $product->id; ?>" class="change_seller_products_status" data-status="A"><i class="fa fa-toggle-off"></i></a>
            @endif
            </td>
            <td>
                <a href='{{ url("/seller/remove_inventry_product/$product->id") }}' onclick="return confirm('Are you sure want to delete?')"  class="btn btn-danger delete_invent_product"><i class="fa fa-trash"></i></a> &nbsp;&nbsp;&nbsp;
                <a href='{{ url("/seller/edit_inventory_product/$p_id") }}'  class="btn btn-primary Edit_invent_product"><i class="fa fa-edit"></i></a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="10">@lang('messages.NoProductsAvailable')</td>
        </tr>    
        @endforelse
    </tbody>
</table>