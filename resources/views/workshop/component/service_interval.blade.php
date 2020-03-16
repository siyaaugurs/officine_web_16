<div class="card">
    <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;@lang('messages.MotServiceIntervalsList')</h6>
        </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>@lang('messages.SN')</th>
                    <th>Description</th>
                    <th>@lang('messages.Additional')</th>
                    <th>@lang('messages.ServiceKms')</th>
                    <th>@lang('messages.serviceMonths')</th>
                    <th>Standared Time</th>
                    <th>Hourly Rate</th>
                    <th>Max. Appointment</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            @php   $sn = 0; @endphp
             @forelse ($service_interval as $interval)
               @php   $sn++; @endphp
                <tr>
                    <td>{{ $sn }}</td>
                    <td>{{ $interval->interval_description_for_kms }}</td>
                    <td>{{ $interval->additional ?? "N/A" }}</td>
                    <td>{{ $interval->service_kms ?? "N/A" }}</td>
                    <td>{{ $interval->service_months ?? "N/A" }}</td>
                    <td>{{ $interval->standard_service_time_hrs ?? "N/A" }}</td>
                    <td>&euro; &nbsp;{{ $interval->hourly_cost ?? "N/A" }}</td>
                    <td>{{ $interval->max_appointment ?? "N/A" }}</td>
                    <td>&euro; &nbsp;{{ $interval->service_price ?? "N/A" }}</td>
                    <td><a href="#" data-serviceid="<?php echo $interval->id; ?>" data-type="<?= $interval->type ?>" data-toggle="tooltip" data-placement="top" title="Edit services details"  class="btn btn-primary btn-sm edit_mot_service_details"><span class="glyphicon glyphicon-edit"></span></a></td>
                </tr>
            @empty
                <tr>
                <td colspan="5">No record found</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
