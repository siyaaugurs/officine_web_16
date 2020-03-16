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
					</table>

@else
  <div class="notice notice-danger"><strong>Wrong , </strong> Something went wrong please try again  .</div>
@endif