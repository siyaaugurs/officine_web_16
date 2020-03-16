<table class="table table-bordered">
    <thead>
        <tr>
            <th>@lang('messages.SN')</th>
            <th>@lang('messages.ServiceName')</th>
            <th>@lang('messages.Description')</th>
            <th>Service Km.</th>
            <th>Month</th>
            <th>Max. Appointment</th>
            <th>Hourly Rate</th>
            <th>Price</th>
            <th>@lang('messages.Actions')</th>
        </tr>
    </thead>
    <tbody> 
        @forelse($mot_services as $services)
            <tr>
                <td>{{  $loop->iteration }}</td>
                <td>{{  $services->service_name }}</td>
                <td>{{  $services->service_description ?? "N/A" }}</td>
                <td>{{  $services->service_km ?? "N/A" }}</td>
                <td>{{  $services->month ?? "N/A" }}</td>
                <td>{{  $services->max_appointment ?? "N/A"}}</td>
                <td>&euro; &nbsp;{{  $services->hourly_cost ?? "N/A"}}</td>
                <td>&euro; &nbsp;{{  $services->service_price ?? "0"}}</td>
                <td>
                    <a href="#" data-serviceid="<?php echo $services->id; ?>" data-toggle="tooltip" data-placement="top" title="Edit services details" data-type="<?= $services->type ?>"  class="btn btn-primary btn-sm edit_mot_service_details"><span class="glyphicon glyphicon-edit"></span></a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8">No Service Details Found</td>
            </tr>
        @endforelse
    </tbody>
</table>