<table class="table">
            <thead>
                <tr>
                    <th>@lang('messages.SN')</th>
                    <th>@lang('messages.GroupSequence')</th>
                    <th>@lang('messages.GroupName')</th>
                    <th>@lang('messages.sort_sequence')</th>
                    <th>@lang('messages.operation_id')</th>
                    <th>@lang('messages.operation_description')</th>
                    <th>@lang('messages.operation_action')</th>
                    <th>@lang('messages.service_note')</th>
                    <th>@lang('messages.at_additional_charge')</th>
                    <th>@lang('messages.part_description')</th>
                    <th>@lang('messages.ad_part_id')</th>
                    <th>@lang('messages.kr_parts_count')</th>
                </tr>
            </thead>
            <tbody>
            @php   $sn = 0; @endphp
            @if($interval_operation != FALSE) 
             @forelse ($interval_operation as $interval)
               @php   $sn++; @endphp
                <tr>
                    <td>{{ $sn }}</td>
                    <td>{{ $interval->group_sequence ?? "N/A" }}</td>
                    <td>{{ $interval->group_name ?? "N/A" }}</td>
                    <td>{{ $interval->sort_sequence ?? "N/A" }}</td>
                    <td>{{ $interval->operation_id ?? "N/A" }}</td>
                    <td>{{ $interval->operation_description ?? "N/A" }}</td>
                    <td>{{ $interval->operation_action ?? "N/A" }}</td>
                    <td>{{ $interval->service_note ?? "N/A" }}</td>
                    <td>{{ $interval->at_additional_charge ?? "N/A" }}</td>
                   <td>{{ !empty($interval->part_description) ? $interval->part_description :  "N/A" }}</td>
                    <td>{{ !empty($interval->ad_part_id) ? $interval->ad_part_id : "N/A" }}</td>
                    <td>{{ !empty($interval->kr_parts_count) ? $interval->kr_parts_count : "N/A" }}</td>
                </tr>
             @empty
                <tr>
                <td>No operation available </td>
                </tr>
             @endforelse
            @else
              <tr>
                <td>No operation available </td>
              </tr>
            @endif
            </tbody>
        </table>