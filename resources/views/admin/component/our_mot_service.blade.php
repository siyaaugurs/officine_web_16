<table class="table">
            <thead>
                <tr>
                    <th>@lang('messages.SN')</th>
                    <th>Service Name</th>
                    <th>Description</th>
                    <th>Service Km.</th>
                    <th>Month</th>
                    <th colspan="2">@lang('messages.Actions')</th>
                </tr>
            </thead>
            <tbody>
            @php   $sn = 0; @endphp
             @forelse ($our_mot_services as $service)
               @php   $sn++; @endphp
                @php 
                    $id = encrypt($service->id);
                @endphp
                <tr>
                    <td>{{ $sn }}</td>
                     <th>{{ !empty($service->service_name) ? $service->service_name : "N/A" }}</th>
                    <th>{{ !empty($service->service_description) ? $service->service_description : "N/A" }}</th>
                    <th>{{ !empty($service->service_km) ? $service->service_km : "N/A" }}</th>
                    <th>{{ !empty($service->month) ? $service->month : "N/A" }}</th>
                    <td class="text-center" colspan="2">
                      <a data-id="<?php if(!empty($service->id))echo $service->id; ?>" href='javascript::void(0)' data-toggle="tooltip" data-placement="top" title="Info" class="btn btn-warning our_mot_service_info btn-sm"><i class="fa fa-info-circle"></i></a>

                      <a href="#" data-toggle="tooltip" data-placement="top" title="Show category" data-id="<?php if(!empty($service->id))echo $service->id; ?>" class="btn btn-primary our_mot_service_category btn-sm" ><i class="fa fa-list"></i></a>

                      <a href='{{ url("admin/edit_mot_test_services/$id") }}' target="_blank" data-toggle="tooltip" data-placement="top" title="Edit" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>

                      <a href="#" data-toggle="tooltip" data-motid="{{ $service->id }}" data-placement="top" title="Delete" class="btn btn-danger btn-sm delete_mot_services"><i class="fa fa-trash"></i></a>
                    </td>
                       
                </tr>
            @empty
                <tr>
                <td colspan="5">No record found</td>
                </tr>
            @endforelse
            </tbody>
        </table>