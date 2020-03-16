<table class="table datatable-show-all dataTable no-footer" id="group_table">
    <thead>
        <tr>
            <th> <input type="checkbox"  id="all_select" > </th>
            <th>@lang('messages.SN')</th>
            <!--<th>@lang('messages.Version')</th>-->
            <th>@lang('messages.GroupName')</th>
            <th>@lang('messages.Language')</th>
        </tr>
    </thead>
    <tbody>
        @forelse($products_groups as $group_items)
            <tr>
                <td><input type="checkbox" class="group_id" value="{{ $group_items->group_id }}" data-versionid="<?php if(!empty($group_items->car_version)) echo $group_items->car_version; ?>"  data-lang="{{ $group_items->language }} "> </td>
                <td>{{ $loop->iteration }}</td>
                <td>{{ !empty($group_items->category) ? $group_items->category : "" }} >> @if(!empty($group_items->group_name)){{ $group_items->group_name }} @else {{ "N/A" }} @endif</td>
                <td>{{ $group_items->language }}</td>
            </tr>
        @empty
         <tr>
                <td colspan="3">No record found !!!</td>
                
            </tr>
        @endforelse
    </tbody>
</table>
