<table class="table datatable-show-all dataTable no-footer">
    <thead>
        <tr>
            <th>S No.</th>
            <th>Tyre ItemId</th>
            <th>Tyre EAN Number</th>
            <th>Seller Price</th>
            <th>Quantity</th>
            <th>Stock Warning</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($tyre_inventory as $tyre)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $tyre->Tyre24_itemId ?? "N / A" }}</td>
                <td>{{ $tyre->Tyre24_ean_number ?? "N/A" }}</td>
                <td>&euro;&nbsp;{{ $tyre->seller_price ?? "N/A" }}</td>
                <td>{{ $tyre->quantity ?? "N/A" }}</td>
                <td>{{ $tyre->stock_warning ?? "N/A" }}</td>
                <td>
                @if($tyre->status == "A")
                    <a href="#" data-tyreid="<?php if(!empty($tyre->id))echo $tyre->id; ?>" class="change_seller_tyre_status" data-status="P"><i class="fa fa-toggle-on"></i></a>
                @else
                    <a href="#" data-tyreid="<?php if(!empty($tyre->id))echo $tyre->id; ?>" class="change_seller_tyre_status" data-status="A"><i class="fa fa-toggle-off"></i></a>
                @endif
                </td>
                <td>
                    <a href='{{ url("/seller/remove_inventry_tyre/$tyre->id") }}' onclick="return confirm('Are you sure want to delete?')"  class="btn btn-danger delete_invent_tyre"><i class="fa fa-trash"></i></a> &nbsp;&nbsp;&nbsp;
                    <a href='#' data-tyreid="{{ $tyre->id }}"  class="btn btn-primary edit_invent_tyre"><i class="fa fa-edit"></i></a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="10">@lang('messages.NoProductsAvailable')</td>
            </tr>    
        @endforelse
    </tbody>
</table>