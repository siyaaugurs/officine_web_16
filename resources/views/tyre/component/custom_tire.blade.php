
<table class="table table-bordered">
    <tr>
        <th>S No.</th>
        <th>Image</th>
        <th>Ean Number</th>
        <th>Tyre</th>
        <th>Description</th>
        <th>Match Code</th>
        <th>Manufacturer</th>
        <th>Actions</th>
    </tr>
    @forelse($get_tyres_list as $tyre)
        <tr>
            <td><?php echo $loop->iteration; ?></td>
            <td>
            <?php 
			if(!empty($tyre->imageUrl)){
			    ?>
				 <img src="<?php echo $tyre->imageUrl; ?>" style="width:50px;">
				<?php
			   }
			 else{
			     ?>
				  <img src="http://officine.augurstech.com/officineTop/public/storage/products_image/no_image.jpg" class="img img-thumbnail" style="height:50px;">
                
				 <?php
			   }  
			?>
            </td>
            <td><?php if(!empty($tyre->ean_number)) echo $tyre->ean_number; ?> </td>
            <td><?php if(!empty($tyre->description)) echo $tyre->description; ?> </td>
            <td><?php  if(!empty($tyre->description)) echo $tyre->description; ?></td>
            <td><?php  if(!empty($tyre->matchcode)) echo $tyre->matchcode; ?></td>
            <td><?php if(!empty($tyre->manufacturer_description)) echo $tyre->manufacturer_description; ?></td>
            <td>&nbsp;&nbsp;<a data-tyreid="{{ $tyre->id }}"                                                          href="javascript::void()" class="btn btn-warning tyreinfo"><span class="fa fa-info-circle">&nbsp;Info</span></a></td>
        </tr>
    @empty
        <tr>  
            <td colspan="7">No record found !!!</td>
        </tr>
    @endforelse
</table>