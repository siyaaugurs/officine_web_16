@if($tyre != NULL)
 <table class="table">
                           <tr>
                             <th>Tyre Type</th>
                             <td>{{ !empty($tyre->type) ? sHelper::get_tyre_type($tyre->type) : "N/A" }}</td>
                           </tr>
                           <tr>
                             <th>Tyre Image</th>
                             <td>
                               @if(!empty($tyre->tyre_resp->imageUrl))
                                 @if(!is_object($tyre->tyre_resp->imageUrl))
                                 <img src="<?php echo $tyre->tyre_resp->imageUrl; ?>" height="50px;">
                                 @endif
                               @else
                                 <img src="http://services.officinetop.com/public/storage/products_image/no_image.jpg" height="50px;">
                               @endif
                             
                              </td>
                           </tr>
                            <tr>
                             <th>Price</th>
                             <td>
							 <?php if(!empty($tyre->tyre_resp->price)){ 
							          if(!is_object($tyre->tyre_resp->price)) 
									     echo $tyre->tyre_resp->price;
								  }?></td>
                           </tr>
                           <tr>
                             <th>Tyre Label Url</th>
                             <td>
                               @if(!empty($tyre->tyre_details_response->tyreLabelUrl))
                                 @if(!is_object($tyre->tyre_details_response->tyreLabelUrl))
                                 <img src="<?php echo $tyre->tyre_details_response->tyreLabelUrl; ?>" height="50px;">
                                 @endif
                               @else
                                 <img src="http://services.officinetop.com/public/storage/products_image/no_image.jpg" height="50px;">
                               @endif
                             
                             </td>
                           </tr>
                           <tr>
                             <th>Tyre Ean_Number</th>
                             <td>
							   <?php if(!empty($tyre->tyre_resp->ean_number)){
								    if(!is_object($tyre->tyre_resp->ean_number)){
									   echo $tyre->tyre_resp->ean_number;
									 }
								}?>
                             </td>
                           </tr>
                           <tr>
                             <th>Match Code</th>
                             <td>
                             <?php if(!empty($tyre->tyre_resp->matchcode)){
								    if(!is_object($tyre->tyre_resp->matchcode)){
									   echo $tyre->tyre_resp->matchcode;
									 }
								}?>
                             </td>
                           </tr>
                           <tr>
                             <th>Manufacturer Description</th>
                             <td>
                             <?php if(!empty($tyre->tyre_resp->manufacturer_description)){ if(!is_object($tyre->tyre_resp->manufacturer_description)){ echo $tyre->tyre_resp->manufacturer_description; } else echo "N/A"; }?>
                            </td>
                           </tr>
                           <tr>
                             <th>Description</th>
                             <td>
                               <?php if(!empty($tyre->tyre_resp->description)){ if(!is_object($tyre->tyre_resp->description)){ echo $tyre->tyre_resp->description; } else echo "N/A"; }?>
                             </td>
                           </tr>
                           <tr>
                             <th>Description 1</th>
                             <td>
                               <?php if(!empty($tyre->tyre_resp->description1)){ if(!is_object($tyre->tyre_resp->description1)){ echo $tyre->tyre_resp->description1; } else echo "N/A"; }?>
                            </td>
                           </tr>
                          
                        </table>
@else
   <div class="notice notice-danger"><strong>Wrong </strong> Tyre Content not available .!!! </div>
@endif                        