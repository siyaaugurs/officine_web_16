@if($coupon_detail != NULL)
 <table class="table">
                           <tr>
                             <th>Coupon Type</th>
                             <td>{{ $coupon_type_arr[$coupon_detail->coupon_type] }}</td>
                           </tr>
                           @if($coupon_detail->coupon_type == 2)
                           <tr>
                             <th>Number of user in coupon group</th>
                             <td>{{ $coupon_detail->users_in_group }}</td>
                           </tr>
                           @endif
                           <tr>
                             <th>Coupon Title</th>
                             <td>{{ !empty($coupon_detail->coupon_title) ?  $coupon_detail->coupon_title : "N/A" }}</td>
                           </tr>
                           <tr>
                             <th>Coupon Avail Quantity</th>
                             <td>{{ !empty($coupon_detail->coupon_quantity) ?  $coupon_detail->coupon_quantity : "N/A" }}</td>
                           </tr>
                            <tr>
                             <th>Per User Alloted (Quantity)</th>
                             <td>{{ !empty($coupon_detail->per_user_allot) ?  $coupon_detail->per_user_allot : "N/A" }}</td>
                           </tr>
                           <tr>
                             <th>Launching / Closed Date</th>
                             <td>{{ date('d-m-Y',  strtotime($coupon_detail->launching_date)) . "/ ". date('d-m-Y',  strtotime($coupon_detail->closed_date))}}</td>
                           </tr>
                           <tr>
                             <th>Available / Expiry Date</th>
                             <td>{{ date('d-m-Y',  strtotime($coupon_detail->avail_date)) . "/ ". date('d-m-Y',  strtotime($coupon_detail->avail_close_date))}}</td>
                           </tr>
                           <tr>
                             <th>Offer Type</th>
                             <td>
							 <?php 
							 if(!empty($coupon_detail->offer_type)){
								    if($coupon_detail->offer_type == 1) echo "In Percentage";
								    if($coupon_detail->offer_type == 2) echo "In Amount";
								    
								 } ?></td>
                           </tr>
                           <tr>
                             <th>Amount</th>
                             <td>
                              <?php if(!empty($coupon_detail->amount)){ echo $coupon_detail->amount; } ?>
                            </td>
                           </tr>
                           <tr>
                             <th>Discount Type</th>
                             <td>
                               <?php if(!empty($coupon_detail->discount_condition)){ echo $on_discount_arr[$coupon_detail->discount_condition]; }?>
                             </td>
                           </tr>
                           @if($coupon_detail->discount_condition == 2)
                           <tr>
                             <th>Special Condition</th>
                             <td>{{ $coupon_details->shipping_amount }}</t>
                           </tr>
                           @elseif($coupon_detail->discount_condition == 3)
                           <tr>
                             <th>Product Type / Product Id</th>
                             <td>
                               {{ \serviceHelper::get_parts($coupon_details->product_type) }}
                              /
                               {{ $coupon_details->product_product_id }}
                             </td>
                           </tr>
                           @elseif($coupon_detail->discount_condition == 4)
                           <tr>
                             <th>Services </th>
                             <td>
                               <?php 
							     $service_detail = sHelper::get_main_category($coupon_details->services_id);
								 if(!empty($service_detail['service'])){
							          echo $service_detail['service'];
								   }
							   ?> 
                              </td>
                           </tr>
                           @elseif($coupon_detail->discount_condition == 5)
                             <?php 
							  $service_arr = sHelper::get_main_category($coupon_details->services_id , $coupon_details->service_category_id);
							  ?>
                             <tr>
                             <th>Services </th>
                             <td>
                                <?php 
							    if(!empty($service_arr['service'])){
							      echo $service_arr['service'];
								}
							 ?>
                              </td>
                           </tr>
							 <tr>
                             <th>Services Category</th>
                             <td>
                             <?php 
							    if(!empty($service_arr['service_category'])){
							      echo $service_arr['service_category'];
								}
							 ?>
                              </td>
                           </tr>
                           @elseif($coupon_detail->discount_condition == 6) 
                              <tr>
                             <th>Brand</th>
                             <td>
                             <?php 
							   $brand_detail = sHelper::get_brand($coupon_details->brand);
							   if($brand_detail != NULL)
								  echo $brand_detail->brand_name;
							   else echo "N/A";
							  ?>
                              </td>
                           </tr>
                           @endif 
                        </table>
@else
   <div class="notice notice-danger"><strong>Wrong </strong> Tyre Content not available .!!! </div>
@endif                        