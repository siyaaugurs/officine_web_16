<div class="card">
    <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;@lang('messages.MotServiceIntervalsList')</h6>
        <a href='#' class="btn btn-primary" id="add_new_interval" style="color:white; float:right;display:none" >Add New Interval&nbsp;<span class="glyphicon glyphicon-plus"></span></a>
       <!-- <a href='{{ url("products/add_group") }}' class="btn btn-primary" style="color:white;">Add New Category &nbsp;<span class="glyphicon glyphicon-plus"></span></a>-->
        </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>@lang('messages.SN')</th>
                    <th>Description</th>
                    <th>@lang('messages.Additional')</th>
                    <th>@lang('messages.SortOrder')</th>
                    <th>@lang('messages.ServiceKms')</th>
                    <th>@lang('messages.serviceMonths')</th>
                    <th colspan="2">@lang('messages.Actions')</th>
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
                    <td>{{ $interval->sort_order ?? "N/A" }}</td>
                    <td>{{ $interval->service_kms ?? "N/A" }}</td>
                    <td>{{ $interval->service_months ?? "N/A" }}</td>
                    <td class="text-center" colspan="2">
                      <a data-id="<?php if(!empty($interval->id))echo $interval->id; ?>" href='javascript::void(0)' class="btn btn-warning interval_info">Info</a>
                      <a href='{{ url("admin/mot_services_operation/$interval->id") }}' target="_blank" class="btn btn-primary">Opration</a>
                    </td>
                       
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
