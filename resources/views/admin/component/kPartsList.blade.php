<table class="table">
            <thead>
                <tr>
                    <th>@lang('messages.SN')</th>
                    <th>@lang('messages.ad_part_id')</th>
                    <th>@lang('messages.idVoce')</th>
                    <th>@lang('messages.Voce_ENG')</th>
                    <th>@lang('messages.ap_ENG')</th>
                    <th>@lang('messages.ds_ENG')</th>
                     <th>Part list</th>
                </tr>
            </thead>
            <tbody>
            @php   $sn = 0; @endphp
             @forelse ($kPartsList as $list)
               @php   $sn++; @endphp
                <tr>
                    <td>{{ $sn }}</td>
                    <td>{{ !empty($list->ad_part_id) ? $list->ad_part_id : "N/A" }}</td>
                     <td>{{ !empty($list->idVoce) ? $list->idVoce : "N/A" }}</td>
                    <td>{{ !empty($list->Voce_ENG) ? $list->Voce_ENG : "N/A" }}</td>
                     <td>{{ !empty($list->ap_ENG) ? $list->ap_ENG : "N/A" }}</td>
                    <td>{{ !empty($list->ds_ENG) ? $list->ds_ENG : "N/A" }}</td>
                     <td><a data-toggle="tooltip" data-placement="top" title="" data-original-title="Part list" href='javascript::void(0)' class="btn btn-primary get_mot_part_list" data-id="{{ $list->id }}">
                      <i class="fa fa-list"></i></a></td>
                </tr>
            @empty
                <tr>
                <td colspan="5">Record not Found !!!</td>
                </tr>
            @endforelse
            </tbody>
        </table>