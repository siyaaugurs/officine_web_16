<table class="table datatable-show-all" id="example">
    <thead>
        <tr>
            <th> <input type="checkbox"  id="all_select" checked="checked"> </th>
            <th>@lang('messages.SN')</th>
            <th>@lang('messages.ServiceGroup')</th>
            <th>@lang('messages.GroupItems')</th>
            <th>@lang('messages.Language')</th>
        </tr>
    </thead>
    <tbody>
        @forelse($spare_items as $group_items)
            @php $group_details = sHelper::new_get_group_details($group_items->products_groups_group_id , $group_items->products_groups_id); @endphp
             @if($group_details != NULL)
                    @php $parent_group_details = sHelper::get_parent_groups_details($group_details->parent_id); @endphp
                        @if($parent_group_details != NULL)
                                    <tr>
                                        <td><input type="checkbox" class="group_id" value="{{ $group_items->id }}" checked="checked"> </td>

                                        <td>{{ $loop->iteration }}</td>

                                        <td>{{ $group_items->main_cat_name }}</td>

                                        <td>{{ !empty($parent_group_details->group_name) ? $parent_group_details->group_name : "N/A" }}{{ " >> ".$group_details->group_name }}</td>

                                        <td>{{ $group_details->language }}</td>
                                    </tr>
                        @endif
             @endif
        @empty
            <tr>
                <td colspan="3">No record found !!!</td>
            </tr>
        @endforelse
    </tbody>
</table>


