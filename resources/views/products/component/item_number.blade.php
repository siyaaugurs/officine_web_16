<div class="card" id="user_data_body" style="overflow:auto">
        <div class="card-header bg-light header-elements-inline">
          <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;Item Number</h6>
        </div>
         <table class="table table-bordered" id="products_list">
            <thead>
                <tr>
                    <th>@lang('messages.SN')</th>
                    <th>CodiceListino</th>
                    <th>Tipo</th>
                    <th>Listino</th>
                    <th>CodiceOE</th>
                    <th>Descrizione</th>
                    <th>CS</th>
                    <th>Prezzo</th>
                    <th>N</th>
                    <th>V</th>
                </tr>
            </thead>
            <tbody id="item_number_list_id">
               @forelse($items_numbers as $items_number)
               <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ !empty($items_number->CodiceListino) ? $items_number->CodiceListino : "N/A" }} </td>
                    <td>{{!empty($items_number->Tipo) ? $items_number->Tipo : "N/A" }}</td>
                    <td>{{ !empty($items_number->Listino) ? $items_number->Listino  : "N/A" }}</td>
                    <td><a class="item_number_btn" href='{{ url("search_products_by_item_number_05_08/$items_number->id") }}'>{{ !empty($items_number->CodiceOE) ? $items_number->CodiceOE : "N/A" }} </a></td>
                    <td>{{ !empty($items_number->Descrizione) ? $items_number->Descrizione  : "N/A" }}</td>
                    <td>{{ !empty($items_number->CS) ? $items_number->CS : "N/A" }}</td>
                    <td>{{ !empty($items_number->Prezzo) ? $items_number->Prezzo : "N/A" }}</td>
                    <td>{{ !empty($items_number->N) ? $items_number->N : "N/A" }}</td>
                    <td>{{ !empty($items_number->V) ? $items_number->V : "N/A" }}</td>
                </tr>
               @empty
                <tr>
                    <td colspan="5">No item number available</td>
                </tr>    
               @endforelse
            </tbody>
        </table>
    </div>

