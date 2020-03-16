
<table class="table">
						<tr>
							<th>Product Name</th>
							<td><?php echo $products_details->products_name; ?></td>
						</tr>
						<tr>
							<th>Makers / Model / Version</th>
							<td>
								<?php echo  (!empty($products_details->maker) ? $products_details->maker :  "N/A"); ?> /
								<?php echo  (!empty($products_details->model) ? $products_details->model : "N/A"); ?> /
								<?php echo  (!empty($products_details->version) ? $products_details->version : "N/A"); ?>
								<?php
								/* if($maker_name == "All Makers") {
									echo "All Makers";
								} else {
									echo  (!empty($maker_name->Marca) ? $maker_name->Marca :  "N/A");
								}
								?>/<?php
								if($model_name == "All Models") {
									echo "All Models";
								} else {
									echo  (!empty($model_name->Modello) ? $model_name->Modello : "N/A");
								}
								?>/
								<?php
								if($versions == "All Versions") {
									echo "All Versions";
								} else {
									echo  $versions->Versione;
								} */
							?></td>
						</tr>
						<tr>
							<th>N1 Category / N2 Category / N3 Category</th>
							<td>
								<?php echo  (!empty($products_details->categories['product_category_n1_name']) ? $products_details->categories['product_category_n1_name'] :  "N/A" ); ?> /
								<?php echo  (!empty($products_details->categories['product_category_n2_name']) ? $products_details->categories['product_category_n2_name'] :  "N/A"); ?> /
								<?php echo  (!empty($products_details->item) ? $products_details->item.' '.$products_details->front_rear.' '.$products_details->left_right :  "N/A"); ?> 
							</td>
						</tr>
						<tr>
							<th>Our Description</th>
							<td><?php if(!empty($products_details->our_products_description)) echo $products_details->our_products_description; else echo "N/A" ?></td>
						</tr>
						<tr>
							<th>Kromeda Price</th>
							<td><?php if(!empty($products_details->price)) echo $products_details->price; else echo "N/A" ?></td>
						</tr>
						<tr>
							<th>Seller Price</th>
							<td>&euro;&nbsp;<?php if(!empty($products_details->seller_price)) echo $products_details->seller_price; else echo "N/A" ?></td>
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
						<!--<tr>
							<th>Status</th>
							<td>
							<?php
							if(!empty($products_details->products_status)){
								if($products_details->products_status == "P"){
									?>
									<a href="#" data-type='2' data-productsid="<?php if(!empty($products_details->id))echo $products_details->id; ?>" class="btn btn-warning change_products_status" data-status="A">Save in draft&nbsp <i class="fa fa-toggle-on"></i></a>
									<?php
									}
								else if($products_details->products_status == "A"){
									?>
									<a href="#" data-type='2' class="btn btn-success change_products_status" data-productsid="<?php if(!empty($products_details->id))echo $products_details->id; ?>" data-status="P">Publish&nbsp;<i class="fa fa-toggle-on"></i></a>
									<?php
									}	 
								}
							?>
							</td>
						</tr>-->
					</table>