<table class="table main-table">
   <thead>
      <th> Category </th>
      <th>Description </th>
      <th> Status</th>
      <th>  Action</th>
   </thead>
   @forelse($categories as $group)
   <tr class="li-parent">
      <td colspan="4">
         <span class="li-child-toggler">
         <span class="expand_group" data-groupid="<?= $group->id; ?>"><i class="glyphicon glyphicon-plus"></i></span>&nbsp;&nbsp;
         {{ !empty($group->group_name) ? $group->group_name : "N/A" }}
         </span>
         <span class="right-description">
         {{ !empty($group->description) ? $group->description : "N/A" }}
         </span>
         <span class="right-status">
         @if($group->status == 'A')
         <a href="#" data-toggle="tooltip" data-placement="top" title="Change Status" data-groupid="<?= $group->id; ?>" data-status="P" class="change_group_status btn-sm"><i class="fa fa-toggle-on"></i>
         </a>
         @else 
         <a href="#" data-toggle="tooltip" data-placement="top" title="Change Status" data-groupid="<?= $group->id; ?>" data-status="A" class="change_group_status btn-sm"><i class="fa fa-toggle-off"></i>
         </a>
         @endif
         </span>
         <span class="right-action-btns">
            <a href="<?= url("products/remove_group/$group->id"); ?>" data-toggle="tooltip" data-placement="top" title="Delete" class="btn btn-danger delete_group btn-sm"><i class="fa fa-trash" ></i></a>
            <a href="#" data-toggle="tooltip" data-placement="top" title="Add Images" data-groupid="<?php echo $group->id; ?>" class="btn btn-primary add_group_image_btn btn-sm" ><i class="fa fa-picture-o"></i></a>
            <!-- <a href="#" data-toggle="tooltip" data-placement="top" title="View Sub Category" data-categoryid="<?php echo $group->id; ?>" class="btn btn-primary sub_category_btn btn-sm"><i class="fa fa-list"></i></a>
               --> 
            <a href="#" data-toggle="tooltip" data-placement="top" title="Edit" data-description="<?php echo $group->description; ?>" data-priority="<?php echo $group->priority; ?>" data-categoryname="<?php echo  $group->group_name; ?>" data-categorytype="<?php echo $group->type; ?>" data-categoryid="<?php echo $group->id; ?>" class="btn btn-primary edit_category btn-sm"><i class="fa fa-edit"></i>
            </a>
         </span>
      </td>
   </tr>
   @empty 
   <tr>
     <td class="danger">No Record found</td>
   </tr>
   @endforelse
</table>