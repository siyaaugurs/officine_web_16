<?php 
//echo "<pre>";
//print_r($category);exit;
?>
<table class="table table-border">
  <thead>
    <tr>
        <th>S.No</th>
        <th>Category</th>
        <th>Sub Category</th>
        <th>Action</th>
    </tr>
  </thead>
  <tbody>
    @forelse($sub_category as $sub_category)
	  <tr>
		<td><?php echo $loop->iteration;?></td>
		<td><?php echo $category->group_name;?></td>
		<td><?php echo $sub_category->group_name; ?></td>
		<td>
        <a href="<?php echo url("products/remove_group/$sub_category->id"); ?>" data-pid="<?php echo $sub_category->id; ?>" class="btn btn-danger" ><i class="fa fa-trash" ></i></a>&nbsp;
        <a href="#" data-toggle="tooltip" data-placement="top" title="Add Images" data-groupid="<?php echo $sub_category->id; ?>" class="btn btn-primary add_group_image_btn btn-sm" ><i class="fa fa-picture-o"></i></a>&nbsp;
        <a href="#" data-toggle="tooltip" data-placement="top" title="Edit" data-categoryname="<?php echo $sub_category->group_name; ?>" data-description="<?php echo $sub_category->description; ?>" data-categorytype="<?php echo $sub_category->type; ?>" data-groupid="<?php echo $sub_category->id; ?>" class="btn btn-primary edit_sub_group_details btn-sm" ><i class="fa fa-edit"></i></a>
        </td>
	  </tr>
    @empty
      <tr>
		<td colspan="4">
           @lang('messages.noSubcategoryAvailable')
        </td>
	  </tr>
    @endforelse                                
  </tbody>
</table>