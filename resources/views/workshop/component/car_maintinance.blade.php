<table class="table datatable-show-all">
            <thead>
                <tr>
                    <th>@lang('messages.SN')</th>
                    <th>@lang('messages.Item')</th>
                    <th>@lang('messages.ap')</th>
                    <th>@lang('messages.ds')</th>
                    <th>@lang('messages.Services')</th>
                  <!--   <th>@lang('messages.Time')</th> -->
                    <th>Hourly Cost</th>
                  <!--   <th>@lang('messages.Price')</th> -->
                    <th>Max Appointment</th>                  
                    <th>@lang('messages.Info')</th>
                    <th>@lang('messages.language')</th>
                    <th>@lang('messages.Actions')</th>
                </tr>
            </thead>
            <tbody>
            @php   $sn = 0; @endphp
             @forelse ($car_maintinance_service_list as $details)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ !empty($details->item) ? $details->item : "N/A" }}</td>
                    <td>{{ !empty($details->front_rear) ? $details->front_rear : "N/A" }}</td>
                    <td>{{ !empty($details->left_right) ? $details->left_right : "N/A" }}</td>
                    <td>{{ !empty($details->description) ? $details->description : "N/A" }}</td>
                    <!-- <td>{{ !empty($details->service_average_time) ? $details->service_average_time : "N/A" }}</td> -->
                    <td>&euro;&nbsp;{{ !empty($details->hourly_cost) ? $details->hourly_cost : "N/A" }}</td>
                   <!--  <td class="text-center">&euro; &nbsp;{{ !empty($details->price) ? $details->price : 'N/A' }}</td> -->
                    <td>{{ !empty($details->max_appointment) ? $details->max_appointment : 'N/A' }}</td>
                    <td>{{ !empty($details->id_info) ? $details->id_info : "N/A" }}</td>
                    <td>{{ !empty($details->language) ? $details->language : "N/A" }}</td>
                    <td><a href="#" data-maintainanceid="{{ $details->id }}" class="btn btn-primary btn-sm edit_car_maintinance_details"><i class="glyphicon glyphicon-edit"></i></a></td>
                </tr>
            @empty
                <tr>
                <td colspan="5">Records Not Founds !!!</td>
                </tr>
            @endforelse
            </tbody>
        </table>