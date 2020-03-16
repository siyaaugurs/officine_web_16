
		<table class="table datatable-show-all" >
			<thead>
				<tr>
					<th>SN.</th>
					<th>Feedback By</th>
					<th>Rating</th>
					<th>Comment</th>
					<th>Status</th>
					<th>posted at</th>
					<th class="text-center">Actions</th>
				</tr>
			</thead>
			<tbody>
				@forelse ($all_feedback as $feedback)	
				<tr>
					<td>{{ $loop->iteration }}</td>
					<th><span class="badge badge-success">{{ $feedback->f_name ?? "" }}&nbsp;&nbsp;{{ $feedback->l_name ?? "" }}</span></th>
					<td>{{ $feedback->rating }}</td>
					<td>{{ $feedback->comments ?? " N/A " }}</td>
					@if($feedback->is_deleted == 1)
					<td><span class="badge badge-danger">Deleted</span></td>
					@else
					<td><span class="badge badge-primary">Active</span></td>
					@endif
					<td>{{ $feedback->created_at}}</td>
					<td class="text-center">
						<div class="list-icons pull-center">
							<div class="dropdown">
								<a href="#" class="list-icons-item" data-toggle="dropdown">
									<i class="icon-menu9"></i>
								</a>
								<div class="dropdown-menu dropdown-menu-right">
								@if($feedback->is_deleted != 1)	
									<a class="dropdown-item change_feed_status" data-status='1' data-rowid="{{ $feedback->id }}"><i class="icon-pencil5 mr-3"></i>Delete</a>
								    <a class="dropdown-item <?php if(!empty($all_feedback->products_id)) echo "view_workshop_feedback"; else echo "view_seller_feedback"; ?>" data-status='1' data-feedbackid="{{ $feedback->id }}"><i class="icon-pencil5 mr-3"></i>View Detail</a>
								@else 
									<a class="dropdown-item change_feed_status" data-status='0' data-rowid="{{ $feedback->id }}"><i class="icon-pencil5 mr-3"></i>Active</a>
								@endif	
								</div>
							</div>
						</div>
					</td>
				</tr>
										@empty
											<tr>
					<td colspan="5">Workshop Not Available</td>
				</tr>
										@endforelse  
			</tbody>
			</table>
							

