<div class="card" id="user_data_body" style="overflow:auto">
        <div class="card-header bg-light header-elements-inline">
          <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;N3 Categoy List</h6>
          <a href='#' id="add_custom_n3_category" class="btn btn-primary" style="color:white;">@lang('messages.AddNewN3Category')&nbsp;<span class="glyphicon glyphicon-plus"></span></a></div>
         <table class="table table-bordered">
            <thead>
                <tr>
                    <th>@lang('messages.SN')</th>
                    <th>@lang('messages.ItemName')(N3 Category)</th>
                    <th>@lang('messages.Description')</th>
                    <th class="text-center">@lang('messages.Actions')</th>
                </tr>
            </thead>
            <tbody id="products_list_body">
               @forelse($group_item as $group_items)
               <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ !empty($group_items->item) ? $group_items->item : "N/A" }} >> {{ !empty($group_items->front_rear) ? $group_items->front_rear : "N/A" }} >> {{ !empty($group_items->left_right) ? $group_items->left_right : "N/A" }} </td>
                    <td>{{ !empty($group_items->our_description) ? $group_items->our_description : "N/A" }} </td>
                    <td>
                        <a href="#" class="btn btn-primary btn-sm edit_n3_category" data-n3categoryid="{{ $group_items->id }}" data-type="{{ $group_items->type }}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;&nbsp;
                        <a href="#" class="btn btn-primary btn-sm n3_category_image" data-n3categoryid="{{ $group_items->id }}"><i class="fa fa-picture-o"></i></a>&nbsp;&nbsp;&nbsp;
                        <a href="#" class="btn btn-danger btn-sm delete_n3_category" data-n3categoryid="{{ $group_items->id }}"><i class="fa fa-trash"></i></a>
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
