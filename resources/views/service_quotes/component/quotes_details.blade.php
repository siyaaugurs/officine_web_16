@if($quotes_details != NULL)
                    <table class="table">
						<tr>
							<th>Requested Date</th>
							<td><?php echo $quotes_details->service_booking_date; ?></td>
						</tr>
						<tr>
							<th>Customer Name</th>
							<td><?php if(!empty($quotes_details->customer_fname)) echo $quotes_details->customer_fname; if(!empty($quotes_details->customer_lname)) echo " ".$quotes_details->customer_lname; ?></td>
						</tr>
						<tr>
							<th>Customer Email</th>
							<td><?php if(!empty($quotes_details->customer_email)) echo $quotes_details->customer_email; ?></td>
						</tr>
						<tr>
							<th>Customer Mobile </th>
							<td><?php if(!empty($quotes_details->customer_mobile)) echo $quotes_details->customer_mobile; ?></td>
						</tr>
                        <tr>
							<th>Workshop Name </th>
							<td><?php if(!empty($quotes_details->workshop_company_name)) echo $quotes_details->workshop_company_name; ?></td>
						</tr>
                        <tr>
							<th>Workshop Mobile </th>
							<td><?php if(!empty($quotes_details->customer_mobile)) echo $quotes_details->customer_mobile; ?></td>
						</tr>
                        <tr>
							<th>Workshop Email </th>
							<td><?php if(!empty($quotes_details->workshop_email)) echo $quotes_details->workshop_email; ?></td>
						</tr>
						<tr>
						  <th>Images</th>
						  <td>
						    @if($quotes_details->image != NULL)
							   @foreach($quotes_details->image as $image)
							     <img class="card-img img-fluid" style="width:100px; margin:10px;" src="<?php echo $image->image_url; ?>" alt="">  
							   @endforeach
                            @else
							   No Image available !!!    
							@endif
						  </td>
						</tr>
                        <tr>
							<th>Quote Status </th>
							<td>
							<?php
							  if(!empty($quotes_details->status)){
						         if($quotes_details->status == "P"){
								     ?>
									 <a href="javascript::void();" data-serviceid="<?php if(!empty($quotes_details->id))echo $quotes_details->id; ?>" class="btn btn-warning change_service_status" data-status="D">Pending&nbsp <i class="fa fa-toggle-off"></i></a>
									 <?php
								   }
								 else{
								     ?>
									 <a href="javascript::void();" data-serviceid="<?php if(!empty($quotes_details->id))echo $quotes_details->id; ?>" class="btn btn-success change_service_status" data-status="P">Dispatch&nbsp <i class="fa fa-toggle-on"></i></a>
									 <?php
								   }  
							   }
						    ?>
							</td>
						</tr>
					</table>

@else
  <div class="notice notice-danger"><strong>Wrong , </strong> Something went wrong please try again  .</div>
@endif