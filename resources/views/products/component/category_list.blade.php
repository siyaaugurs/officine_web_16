<table class="table" id="category_list">
        <thead>
          <tr>
                    <th>@lang('messages.SN')</th>
                    <th>@lang('messages.Makers')</th>
                    <th>@lang('messages.Model')</th>
                    <th>@lang('messages.Version')</th>
                    <th>@lang('messages.CategoryName')</th>
                    <th >@lang('messages.Actions')</th>
                </tr>
        </thead>
        <tbody>
           @forelse($group_list as $group)
             @php
              $maker_name = kRomedaHelper::get_maker_name($group->car_makers);
              $model_name = kRomedaHelper::get_model_name($group->car_makers , $group->car_model);
              $versions = kRomedaHelper::get_version_name($group->car_model , $group->car_version); 
             @endphp
             <tr>
                 <input type="hidden" class="category_id" value="<?php echo $group->id; ?>">
                    <td>{{ $loop->iteration }}</td>
                    <td><?php if(!empty($maker_name->Marca)) echo $maker_name->Marca; else echo "N/A"; ?></td>
                    <td><?php if(!empty($model_name->Modello)) echo $model_name->Modello; else echo "N/A"; ?></td>
                    <td><?php if(!empty($versions->Versione)) echo $versions->Versione; else{ echo "N/A"; } ?></td>
                    <td><?php echo $group->group_name; ?></td>
                    <td style="width: 210px">
                        <a href="<?php echo url("products/remove_group/$group->id"); ?>" data-toggle="tooltip" data-placement="top" title="Delete" class="btn btn-danger delete_group btn-sm"><i class="fa fa-trash" ></i></a>
                        <a href="#" data-toggle="tooltip" data-placement="top" title="Add Images" data-groupid="<?php echo $group->id; ?>" class="btn btn-primary add_group_image_btn btn-sm" ><i class="fa fa-picture-o"></i></a>
                        <a href="#" data-toggle="tooltip" data-placement="top" title="
                        View Sub Category" data-categoryid="<?php echo $group->id; ?>" class="btn btn-primary sub_category_btn btn-sm"><i class="fa fa-list"></i></a>&nbsp;
                        <a href="#" data-toggle="tooltip" data-placement="top" title="
                        Edit" data-description="<?php echo $group->description; ?>" data-categoryname="<?php echo $group->group_name; ?>" data-categorytype="<?php echo $group->type; ?>" data-categoryid="<?php echo $group->id; ?>" class="btn btn-primary edit_category btn-sm"><i class="fa fa-edit"></i></a>
                    </td>
                </tr>
           @empty
            <tr>
                    <td colspan="5">No Category Available !!!</td>
                   
                </tr>
           @endforelse
        </tbody>
        </table>
