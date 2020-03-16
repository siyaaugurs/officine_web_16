<style>
 .browse_image{ cursor: pointer;}} 
</style>
@if($ticket_detail != NULL)	 
					<div class="card-body" id="message_body">
						@if($ticket_detail->messages->count() > 0)
							<ul class="media-list media-chat media-chat-scrollable mb-3" id="chatlist">
										<!-- <li class="media content-divider justify-content-center text-muted mx-0">Monday, Feb 10</li> -->
								@forelse($ticket_detail->messages as $messages)	
										@if($messages->sender_id != Auth::user()->id)
								        <li class="media">
											<div class="mr-3">
											<!-- 	<a href="../../../../global_assets/images/demo/images/3.png"> -->
													<img src="{{ sHelper::image_url(2 , $messages->profile_image) }}" class="rounded-circle" width="40" height="40" alt="">
											<!-- 	</a> -->
											</div>
		
											<div class="media-body">
												<div class="media-chat-item">
												  <?php 
												      $response = sHelper::get_support_msg($messages->type , $messages->messages); 
												       echo trim($response , "''"); 
												  ?>
												</div>
												<div class="font-size-sm text-muted mt-2">{{ sHelper::date_format_for_database($messages->created_at , 1) }}</div>
											</div>
										</li>
										@elseif($messages->sender_id == Auth::user()->id)
										<li class="media media-chat-item-reverse">
											<div class="media-body">
												<div class="media-chat-item"> 
													<?php 
												      $response = sHelper::get_support_msg($messages->type , $messages->messages); 
												       echo trim($response , "''"); 
												  ?></div>
												<div class="font-size-sm text-muted mt-2">{{ sHelper::date_format_for_database($messages->created_at , 1) }}</div>
											</div>
											<div class="ml-3">
												<!-- <a href="../../../../global_assets/images/demo/images/3.png"> -->
													<img src="{{ sHelper::image_url(2 , $messages->profile_image) }}" class="rounded-circle" width="40" height="40" alt="">
												<!-- </a> -->
											</div>
										</li>
										@endif
                                       
								@empty
								@endforelse		
								</ul>
						@endif   
						<form id="support_messages">     
							<input type="hidden" name="support_ticket_id" value="<?php if(!empty($ticket_detail->id))echo $ticket_detail->id; ?>">
							<textarea name="message" id="message" class="form-control mb-3" rows="3" cols="1" placeholder="Enter your message..." ></textarea>
							<div class="d-flex align-items-center">
									<div class="list-icons list-icons-extended">
										<label class="browse_image" data-popup="tooltip" data-container="body" title="" data-original-title="Browse Images">
											<input type="file" name="images[]" id="files"  style="width: 0px;height: 0px;overflow: hidden;" /> 
											<i class="icon-file-picture"></i> Browse Image
										</label>
									</div>
									<button type="submit" id="send_support_btn" class="btn bg-teal-400 btn-labeled btn-labeled-right ml-auto"><b><i class="icon-paperplane"></i></b> Send</button>
								</div>
                       </form>
					</div>
					@else
					'<div class="notice notice-danger"><strong>Wrong , </strong> Somehting Went Wrong please try again  !!!.</div>';
					@endif