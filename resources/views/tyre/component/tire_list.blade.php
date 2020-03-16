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
    @forelse($get_tyres_list as $tire)
      <?php  
       $decode_tyre_response = json_decode($tire->tyre_response);
	   if(is_string($decode_tyre_response->description1)){
          $description1 = $decode_tyre_response->description1;  
        }    
       else{
           $description1 = "N/A";  
       }
      ?>
        <tr>
            <td><?php echo $loop->iteration; ?></td>
            <td>
            <?php 
			if(!empty($decode_tyre_response->imageUrl)){
                $image = serviceHelper::set_tyre_image($decode_tyre_response->imageUrl);
			    ?>
				 <img src="<?php echo $image; ?>" style="width:50px;">
				<?php
			   }
			 else{
			     ?>
				  <img src="http://officine.augurstech.com/officineTop/public/storage/products_image/no_image.jpg" class="img img-thumbnail" style="height:50px;">
                
				 <?php
			   }  
			?>
            </td>
            <td><?php if(!is_object($decode_tyre_response->ean_number)) if(!empty($decode_tyre_response->ean_number)) echo $decode_tyre_response->ean_number; ?> </td>
            <td><?php if(!is_object($decode_tyre_response->ean_number)) if(!empty($description1)) echo $description1; ?> </td>
            <td><?php if(!is_object($decode_tyre_response->ean_number)) if(!empty($decode_tyre_response->description)) if(is_string($decode_tyre_response->description)) echo $decode_tyre_response->description; ?></td>
            <td><?php if(!is_object($decode_tyre_response->ean_number)) if(!empty($decode_tyre_response->matchcode)) echo $decode_tyre_response->matchcode; ?></td>
            <td><?php if(!is_object($decode_tyre_response->ean_number)) if(!empty($decode_tyre_response->manufacturer_description)) echo $decode_tyre_response->manufacturer_description; ?></td>
            <td><a style="margin:5px;" href='{{  url("tyre24/edit_tyre/$tire->id") }}' class="btn btn-primary btn-sm"><span class="fa fa-edit">&nbsp;Edit</span></a>
               <a data-tyreid="{{ $tire->id }}" href="javascript::void()" class="btn btn-warning tyreinfo btn-sm"><span class="fa fa-info-circle">&nbsp;Info</span></a></td>
        </tr>
    @empty
        <tr>  
            <td colspan="7">No record found !!!</td>
        </tr>
    @endforelse
</table>