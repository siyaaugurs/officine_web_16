<table class="table table-bordered">
    <thead>
        <tr>
            <th>SN.</th>
            <th>Services</th>
            <th>Car Size</th>
            <th>Service Average time <span class="text-danget" style="color:#F00;">(in hour)</span></th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    @forelse($listed_services_list as $services)
        @php $enc_type_s_id = encrypt($services->id); @endphp
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $services->category_name }}</td>
            <td>@if(!empty($services->car_size)){{ sHelper::get_car_size($services->car_size) }} @endif</td>
            <td>{{ $services->service_average_time }} </td>
            <td>
                <a href="#" data-serviceid = "<?php echo $services->id; ?>" class="btn btn-danger remove_services"><span class="glyphicon glyphicon-trash"></span></a>&nbsp;
                <a href='{{ url("vendor/view_services/$enc_type_s_id") }}' class="btn btn-primary"><span class="fa fa-eye"></span></a>
                <a href='#' data-toggle="tooltip" data-placement="top" title="Manage Time Slot" class="btn btn-primary manage_time_slot" data-serviceid="<?php echo $services->id; ?>"><span class="fa fa-clock-o"></span></a>
            </td>
        </tr>
    @empty
        <tr>    <td colspan="5" style="text-align:center">No Records Found</td></tr>
    @endforelse
    </tbody>
</table>  