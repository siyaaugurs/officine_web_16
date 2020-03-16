<table class="table table-bordered">
            <thead>
                <!-- <tr>
                    <th colspan="4"></th>
                    <th colspan="3">
                        Weight Type 1 Cost
                    </th>
                    <th colspan="3">
                        Weight Type 2 Cost
                    </th>
                    <th colspan="2"></th>
                </tr> -->
                <tr>
                    <th>@lang('messages.SN')</th>
                    <th>@lang('messages.Image')</th>
                    <th>@lang('messages.ServiceName')</th>
                    <!-- <th>@lang('messages.ServiceType')</th> -->
                    <!-- <th>@lang('messages.TimeArrives')&nbsp;(in minutes)</th>
                    <th>@lang('messages.CallPrice')</th>
                    <th>@lang('messages.HourlyCost')</th>
                    <th>@lang('messages.CostPerKm')</th>
                    <th>@lang('messages.CallPrice')</th>
                    <th>@lang('messages.HourlyCost')</th>
                    <th>@lang('messages.CostPerKm')</th> -->
                    <!-- <th>@lang('messages.Status')</th> -->
                    <th>@lang('messages.Description')</th>
                    <th>@lang('messages.Actions')</th>
                </tr>
            </thead>
            <tbody> 
                @forelse($wrecker_services as $services)
                    <tr>
                        <td>{{  $loop->iteration }}</td>
                        <td> <img src="<?= $services->service_image_url; ?>" class="img-thumbnail" style="max-width:200px;height:60px"> </td>
                        <td>{{  $services->services_name }}</td>
                        <td>{{  $services->description ?? "N/A" }}</td>
                        
                        <!-- <td>
                            @if($services->status == 'A')
                                <a href="#" data-serviceid="{{ $services->id }}" data-status="P" class="change_workshop_wrecker_status"> <i class="fa fa-toggle-off"></i> </a>
                            @elseif($services->wracker_service_type == 'P')
                                <a href="#" data-serviceid="{{ $services->id }}" data-status="A" class="change_workshop_wrecker_status"> <i class="fa fa-toggle-on"></i> </a>
                            @endif
                        </td> -->
                        <td>
                            <a href="#" data-toggle="tooltip" data-placement="top" title="Edit" class="btn btn-primary btn-sm view_wrecker_service_details" data-serviceid="{{ $services->id }}"><i class="glyphicon glyphicon-eye-open"></i></a>
                            <a href="#" data-toggle="tooltip" data-placement="top" title="Edit" class="btn btn-primary btn-sm edit_wrecker_details" data-serviceid="{{ $services->id }}" data-servicname="{{ $services->services_name }}"><i class="glyphicon glyphicon-edit"></i></a>
                        </td>
                    </tr>
                @empty
                  <tr>
                        <td colspan="11">No Service Details Found</td>
                  </tr>
                @endforelse
            </tbody>
        </table>