<table class="table datatable-show-all dataTable no-footer" id="list_spare_items">
    <thead>
        <tr>
          
            <th>@lang('messages.SN')</th>
            <th>@lang('messages.ServiceGroup')</th>
            <th>@lang('messages.GroupItems')</th>
            <th>@lang('messages.Language')</th>
        </tr>
    </thead>
    <tbody>
        @forelse($spare_items as $group_items)
            <tr>
               
                <td>{{ $loop->iteration }}</td>
                <td>{{ $group_items->main_cat_name }}</td>
                <td>{{ $group_items->group_name }}</td>
                <td>{{ $group_items->language }}</td>
            </tr>
        @empty
         <tr>
                <td colspan="3">No record found !!!</td>
                
            </tr>
        @endforelse
    </tbody>
</table>
