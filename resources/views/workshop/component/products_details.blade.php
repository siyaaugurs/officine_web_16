<table class="table">
                           <tr>
                             <th>Brand Name</th>
                             <td><?php echo $products_details->listino." (".$products_details->kromeda_products_id." )"; ?></td>
                           </tr>
                           <tr>
                             <th>Makers / Model / Version</th>
                             <td><?php echo (!empty($maker_name->Marca) ? $maker_name->Marca :  "N/A")."/".(!empty($model_name->Modello) ? $model_name->Modello : "N/A") ."/".$versions->Versione; ?></td>
                           </tr>
                           <tr>
                             <th>Group Name</th>
                             <td><?php echo !empty($products_details->group_name) ?  $products_details->group_name : "N/A"; ; ?></td>
                           </tr>
                           <tr>
                             <th>Kromeda Description</th>
                             <td><?php if(!empty($products_details->kromeda_description)) echo $products_details->kromeda_description; else echo "N/A" ?></td>
                           </tr>
                           <tr>
                             <th>Products Description</th>
                             <td>
							 <?php if(!empty($products_details->products_description)) echo $products_details->products_description; else echo "N/A" ?>
							 </td>
                           </tr>
                           <tr>
                             <th>Front Rear / Left Rear</th>
                             <th><?php if(!empty($products_details->front_rear)) echo $products_details->front_rear; else echo "N/A" ?> / <?php if(!empty($products_details->left_right)) echo $products_details->left_right; else echo "N/A" ?></th>
                           </tr>
                           <tr>
                             <th>Kromeda Price</th>
                             <td><?php if(!empty($products_details->price)) echo $products_details->price; else echo "N/A" ?></td>
                           </tr>
                           <tr>
                             <th>Seller Price</th>
                             <td><?php if(!empty($products_details->seller_price)) echo $products_details->seller_price; else echo "N/A" ?></td>
                           </tr>
                           <tr>
                             <th>Products Quantity</th>
                             <td><?php if(!empty($products_details->products_quantiuty)) echo $products_details->products_quantiuty; else echo "N/A" ?></td>
                           </tr>
                           <tr>
                             <th>In Stock</th>
                             <td><?php if(!empty($products_details->products_quantiuty)) echo $products_details->products_quantiuty; else echo "N/A" ?></td>
                           </tr>
                           <tr>
                             <th>Tax Value</th>
                             <td><?php if(!empty($products_details->tax_value)) echo $products_details->tax_value; else echo "N/A" ?></td>
                           </tr>
                           <tr>
                             <th>Products Status </th>
                             <td>
							  <?php if(!empty($products_details->products_status)){
								       if($products_details->products_status == "P"){
										   echo "Save in draft"; 
										 } 
										elseif($products_details->products_status == "A"){
										   echo "Publish"; 
										 } 
									 }
							        else echo "N/A" ?>
                             </td>
                           </tr>
                           <tr>
                             <th>Unit</th>
                             <td><?php if(!empty($products_details->unit)) echo $products_details->unit; else echo "N/A" ?></td>
                           </tr>
                           <tr>
                             <th>Assemble Status</th>
                             <td>
							  <?php if(!empty($products_details->assemble_status)){
								       if($products_details->assemble_status == "Y"){
										   echo "Yes"; 
										 } 
										elseif($products_details->assemble_status == "N"){
										   echo "Not"; 
										 } 
									 }
							        else echo "N/A" ?>
                             </td>
                           </tr>
                            <tr>
                             <th>Status</th>
                             <td>
                              <?php
                                if(!empty($products_details->products_status)){
								   if($products_details->products_status == "P"){
									  echo "Save in draft";
									 }
								   else if($products_details->products_status == "A"){
									   echo "Publish";
									 }	 
								 }
							  ?>
                             </td>
                           </tr>
                        </table>