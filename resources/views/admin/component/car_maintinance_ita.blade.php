<table class="table">
            <thead>
                <tr>
                    <th>@lang('messages.SN')</th>
                    <th>@lang('messages.Item')</th>
                   <th>@lang('messages.ap')</th>
                    <th>@lang('messages.ds')</th>
                    <th>@lang('messages.Services')</th>
                    <th>@lang('messages.Time')</th>
                    <th>@lang('messages.Info')</th>
                </tr>
            </thead>
            <tbody>
            @php   $sn = 0; @endphp
             @forelse ($service_time as $details)
               @php   $sn++; @endphp
                <tr>
                    <td>{{ $sn }}</td>
                    <td>{{ $details->Voce }}</td>
                    <td>{{ $details->ap ?? "N/A" }}</td>
                    <td>{{ $details->ds ?? "N/A" }}</td>
                    <td>{{ $details->action_description ?? "N/A" }}</td>
                    <td>@php 
                          $time = sHelper::replace_comman_with_dot($details->time_hrs) 
                       @endphp
                       {{ $time }}
                    </td>
                    <td>{{ $details->id_info ?? "N/A" }}</td>
                </tr>
            @empty
                <tr>
                <td colspan="5">Records Not Founds !!!</td>
                </tr>
            @endforelse
            </tbody>
        </table>