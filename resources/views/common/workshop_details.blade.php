@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<style>	
.icn-sm {
    width: 30px;
    height: 30px;
    line-height: 26px;
    padding: 0;
    text-align: center;
    border-radius: 6px;
}

.icn-sm:hover {
    box-shadow: none !important;
}

.green-bdr {
    border: 2px solid #4caf50;
    background: #4caf50;
    color: #fff;
}

.red-bdr {
    border: 2px solid red;
    background: #ff4040;
    color: #fff;
}
.modal-header
{
	background: #324148;
    color: #fff;
}
.text-white
{
	color:#fff;
	opacity:1 !important;
}

.card-body .card-img {
    border-radius: .1875rem;
    object-fit: contain;
    height: 160px;
}

.m-n-20 {
    margin-bottom: 20px;
}

.icn-sm i {
    font-size: 18px;
}
.trash { color:rgb(209, 91, 71); }
.flag { color:rgb(248, 148, 6); }
.panel-body { padding:0px; }
.panel-footer .pagination { margin: 0; }
.panel .glyphicon,.list-group-item .glyphicon { margin-right:5px; }
.panel-body .radio, .checkbox { display:inline-block;margin:0px; }
.panel-body input[type=checkbox]:checked + label { text-decoration: line-through;color: rgb(128, 144, 160); }
.list-group-item:hover, a.list-group-item:focus {text-decoration: none;background-color: rgb(245, 245, 245);}
.list-group { margin-bottom:0px; }
</style>
<div class="content">
      @if(Session::has('msg'))
        {!! Session::get('msg') !!}
      @endif
<div class="row" style="margin-bottom:15px;">
    <div class="col-sm-12">
      @if($users_profile->roll_id)
        @if(!empty($workshop_details->status))
          <a href="#" id="approve_disapproove" data-status="0" class="btn btn-danger approve_disapproove" onclick="return confirm('Are you sure want to disapprove?')">Disapprove</a>
        @else
         <a href="#" id="approve_disapproove" data-status="1"  class="btn btn-success approve_disapproove" onclick="return confirm('Are you sure want to approve?')">Approve</a>
        @endif 
         
        
      @endif
    </div>
  </div>
  <div class="row">
                    <div class="col-md-12">
						<!--<button type="button" name="button" id="" class="btn btn-success">Approve</button>-->
                        <div class="card" id="workshop_details_section">
                            <div class="card-header bg-light header-elements-inline">
								<h6 class="card-title">Workshop Details</h6>
								<div class="header-elements">
									<div class="list-icons">
				                		<a class="list-icons-item" data-action="collapse"></a>
				                		<a class="list-icons-item" data-action="remove"></a>
				                	</div>
			                	</div>
							</div>
                            <div class="card-body">
						<ul class="media-list media-chat-scrollable mb-3">
							<li class="media text-muted">
                             @if($workshop_details->users_id == Auth::user()->id) 
                                <button type="button" class="btn btn-success popup_btn" data-modalname="category_popup">	<i class="icon-plus3"></i>&nbsp;Add New Category</button>
                              @endif 
                             </li>
                         @forelse($users_categories as $cat)
							<li class="media">
								<div class="mr-3">
                                  {{ $loop->iteration ."." }}
                                </div>
								<div class="media-body">
									<div class="media-title d-flex flex-nowrap">
										<a class="font-weight-semibold mr-3">
                                            {{ $cat->category_name }} 
                                        </a>
										<span class="font-size-sm text-muted text-nowrap ml-auto">
                                         @if($workshop_details->users_id == Auth::user()->id) 
                                      <a data-cateid="{{ $cat->id }}" href="#" class="ml-3 icn-sm red-bdr delete_category">
                              <i class="icon-x icon-2x"></i>
                               </a>
                                     @endif
                                        </span>
									</div>
								</div>
							</li>
                         @empty
                            <li class="media">
								<div class="media-body">
									<div class="media-title d-flex flex-nowrap">
										<a  class="font-weight-semibold mr-3">No category available !!!</a>
										<span class="font-size-sm text-muted text-nowrap ml-auto">2:03 pm <a href="#"><i class="icon-pin-alt font-size-base text-muted ml-2"></i></a></span>
									</div>
									
								</div>
							</li>
                         @endforelse   
						</ul>
					</div>
						</div>
                        <div class="card" id="about_workshop_section">
                            <div class="card-header bg-light header-elements-inline">
								<h6 class="card-title">About Workshop </h6>
								<div class="header-elements">
									<div class="list-icons">
				                		<a class="list-icons-item" data-action="collapse"></a>
				                		<a class="list-icons-item" data-action="remove"></a>
				                	</div>
			                	</div>
							</div>
							<div class="card-body">
                               <h2>About Business
                               	@if($workshop_details->users_id == Auth::user()->id) 
                                 <a href="#" class="ml-3 icn-sm green-bdr popup_btn" data-modalname="about_business_popup">
                               	<i class="icon-pencil"></i>
                               </a>
                                @endif 
                               </h2>
                               @if($workshop_details != NULL)	
                                {!! $workshop_details->description !!}
							   @else
                                 <h2>No content Available</h2>
                               @endif   
                            </div>
						</div>
                         <div class="card" id="workshop__mobile_section">
                            <div class="card-header bg-light header-elements-inline">
								<h6 class="card-title">Contact Details</h6>
								<div class="header-elements">
									<div class="list-icons">
				                		<a class="list-icons-item" data-action="collapse"></a>
				                		<a class="list-icons-item" data-action="remove"></a>
				                	</div>
			                	</div>
							</div>
							<div class="card-body">
                            <ul class="media-list media-chat-scrollable mb-3">
							<li class="media text-muted">
                             @if($workshop_details->users_id == Auth::user()->id) 
                                <button type="button" class="btn btn-success popup_btn" data-modalname="mobile_popup">	<i class="icon-plus3"></i>&nbsp;Add Mobile Number</button>
                              @endif 
                             </li>
                         @forelse($get_workshop_mobile as $mobile)
							<li class="media">
								<div class="mr-3">
                                  {{ $loop->iteration ."." }}
                                </div>
								<div class="media-body">
									<div class="media-title d-flex flex-nowrap">
										<a class="font-weight-semibold mr-3">
                                            {{ $mobile->mobile }} 
                                        </a>
										<span class="font-size-sm text-muted text-nowrap ml-auto">
                                         @if($mobile->users_id == Auth::user()->id) 
                                      <a data-mobileid="{{ $mobile->id }}" href="#" class="ml-3 icn-sm red-bdr delete_mobile">
                              <i class="icon-x icon-2x"></i>
                               </a>
                                     @endif
                                        </span>
									</div>
								</div>
							</li>
                         @empty
                            <li class="media">
								<div class="media-body">
									<div class="media-title d-flex flex-nowrap">
										<a class="font-weight-semibold mr-3">No mobile number available !!!</a>
									</div>
								</div>
							</li>
                         @endforelse   
						</ul>
                            </div>
						</div>    
                        <!---Workshop Gallery section start-->
                        <div class="card">
						  @if($workshop_details != NULL)	
                            <div class="card-header bg-light header-elements-inline">
								<h6 class="card-title">Service Gallery </h6>
								<div class="header-elements">
									<div class="list-icons">
				                		<a class="list-icons-item" data-action="collapse"></a>
				                		<a class="list-icons-item" data-action="remove"></a>
				                	</div>
			                	</div>
							</div>
							<div class="card-body">
                             @if($workshop_details->users_id == Auth::user()->id)  
                               <form  method="post" action='{{ url("vendor/upload_workshop_gallery") }}' enctype="multipart/form-data">
                                    @csrf
									<div class="row m-b-20">
								     <div class="col-md-10 form-group">
                                       <label>Browse multiple Image <span class="text-danger">*</span></label>
                                         <input type="hidden" name="workshop_id" id="workshop_id" value="{{ $workshop_details->id }}" />
									     <input type="file" class="form-control" name="gallery_image[]" id="gallery_image" multiple="multiple" required />
								     </div>
                                     <div class="col-sm-2 form-group">
                                        <button type="submit" id="workshop_sbmt" class="btn bg-blue ml-3" style="    margin-top: 28px;float: right;">Submit <i class="icon-paperplane ml-2"></i></button>
								     </div>
                                    </div>
                                </form>
                             @endif   
                              <div id="workShopImage">  
                              	  <div class="row">
							   @forelse($gallery_image as $images)
                                <div class="col-sm-4 col-md-3 col-lg-3">
									<div class="card">
										<div class="card-img-actions m-1">
											<img class="card-img img-fluid" src="{{ $images->image_url}}" alt="" />
											<div class="card-img-actions-overlay card-img">
												<a href="{{ $images->image_url }}" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round" data-popup="lightbox" rel="group">
													<i class="icon-plus3"></i>
												</a>
											@if($workshop_details->users_id == Auth::user()->id)
                                                <a href='{{ url("vendor_ajax/delete_image/$images->id") }}' data-imageid="{{ $images->id }}" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2 image_delete">
													<i class="icon-trash"></i>
												</a>
											@endif
                                            </div>
										</div>
									</div>
								</div>
							   
                               @empty
                                
                                <div class="col-sm-12 col-lg-12">
								No Image Found !!!
				            	</div>
				            	</div>
                               @endforelse
                              </div> 
							</div>
                          @else
                            <h2>No content Available</h2>
                          @endif 
						</div>
                        <!--End-->
					</div>
				</div>
</div>
<!--Add Mobile number modal popup script start-->
<div class="modal" id="mobile_popup">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                    <h4 class="modal-title" id="myModalLabel"><i class="text-white icon-profile mr-3 icon-1x"></i> Add Contact Details </h4>
                    <hr />
                  </div>
                  <!-- Modal body -->
						<form id="add_contact_form" autocomplete="off">	
                        <div class="modal-body">
                        <div class="row">
								     <div class="col-md-12 form-group">
                                       <label>Mobile Number <span class="text-danger">*</span></label>
									   <input type="text" class="form-control"  name="mobile" id="mobile" placeholder="Mobile Number" required="required" onkeyup="mobileNumberValidate(this.value , 'Mobile' , 'email_err_msg' ,'add_mobile_number')" />
                                       <span id="email_err_msg"></span>
								     </div>
                                    </div>
                          <div class="row">
							 <div class="col-md-6">
                                  <div class="form-group">  
                                     <button type="button" class="btn btn-success"  id="add_mobile_number">Save &nbsp;<i class="icon-paperplane ml-2"></i></button>
                                  </div>
                              </div>
                          </div>
						</div>
                      </form>
                    <div id="response_mobilr_add"></div>   
                  </div>
                  <div class="modal-footer">       
                </div>
              </div>
            </div>
<!--End--->
<!--Add Hour modal popup-->
<div class="modal" id="hours_popup">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                    <h4 class="modal-title" id="myModalLabel">Add hours </h4>
                    <hr />
                  </div>
                  <!-- Modal body -->
						<div class="modal-body">
							@if($all_weekly_day != FALSE)
                             <form id="weekly_schedule_form">
                             <input type="hidden" class="form-control" name="workshop_id" required="required" value="{{ $workshop_details->id ?? ''}}" />
                                @csrf
                              @foreach($all_weekly_day as $days)
                               <div class="row form-group chk_hours_listing">
							    <div class="col-sm-10 col-md-11 col-lg-11">
                                    <div class="d-flex justify-content-between align-items-center">
										<div class="form-check form-check-inline">
											<label class="form-check-label">
												<input type="checkbox" class="form-control-styled weekly_days" id="d{{$days->id}}" name="week_days[]" value="{{$days->id}}"  data-fouc>
												{{ $days->name }}
											</label>
										</div>
									</div>
                                </div>
                                 <div class="col-sm-2 col-md-1 col-lg-1">
                                 	<p>Closed</p>
                                 </div>
                                <div class="col-sm-12 gap-margin main_div" id="hour_sectiond{{$days->id}}" style="display:none;">
										<div class="timing_row" id="timingrow{{$days->id}}"> 
                                          <div class="row more_row" id="add_more_timing_section" style="margin-top:15px;">
										    <div class="col-sm-5">
										    	<input type="text" class="form-control" placeholder="Select Start timing" name="first_timing[]" id='start_time'  />
										    </div>
										    <div class="col-sm-5">
										    	<input type="text" class="form-control" placeholder="Select End timing" name="second_timing[]" id="end_time"  />
										    </div>
                                            <div class="col-sm-2">
										    	<a href="#" class="ml-3 btn btn-danger icn-sm red-bdr remove_more_timing">
                              <i class="icon-x icon-2x"></i>
                               </a>
										    </div>
										</div>
                                          <div class="row add_more_timing_section_copy more_row"  style="margin-top:15px; display:none;">
										    <div class="col-sm-5">
										    	<input type="text" class="form-control" placeholder="Select Start timing" name="first_timing_1[]" id='start_time'  />
										    </div>
										    <div class="col-sm-5">
										    	<input type="text" class="form-control" placeholder="Select End timing" name="second_timing_1[]" id="end_time"  />
										    </div>
                                            <div class="col-sm-2">
										    	<a href="#" class="ml-3 btn btn-danger icn-sm red-bdr remove_more_timing">
                              <i class="icon-x icon-2x"></i>
                               </a>
										    </div>
										</div>
                                        </div>
									   <a style="margin-top:15px;" href="#" class="add_more_time_btn" id="add_more_btn{{$days->id}}" data-days="{{ $days->id }}">Add more hours</a>
                                       <div class="row" style="margin-left:15px;">
											<div class="col-md-12">
												<div class="d-flex justify-content-between align-items-center">
													<div class="form-check form-check-inline">
														<label class="form-check-label">
															<input type="checkbox" class="form-control-styled day_24_checkbox" name="day_24[]" value="1_{{$days->id}}" id="day_24_{{$days->id}}" data-crowid="{{$days->id}}"  data-fouc>
															Open 24 hours
														</label>
													</div>
												</div>
											</div>
										</div>
								</div>
                      		</div>
                              @endforeach
                              <div class="row">
							 <div class="col-md-6">
                                  <div class="form-group">  
                                     <button type="submit" class="btn btn-success"  id="add_workshop_timing">Save &nbsp;<i class="icon-paperplane ml-2"></i></button>
                                  </div>
                              </div>
                          </div>
                             </form>
                            <div id="response_timing"></div> 
                            @else
                              <h1>day is not available .</h1>
                            @endif
                            
                      	</div>
                  </div>
              </div>
</div>
<!--End-->
<!--About Business modal popup script start-->
<div class="modal" id="about_business_popup">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                    <h4 class="modal-title" id="myModalLabel"><i class="text-white icon-profile mr-3 icon-1x"></i> Edit about workshop details </h4>
                    <hr />
                  </div>
                  <!-- Modal body -->
						<form id="edit_about_workshop_form">	
                        <div class="modal-body">
                        <div class="row">
								     <div class="col-md-12 form-group">
                                     <input type="hidden" class="form-control" name="workshop_id" required="required" value="{{ $workshop_details->id ?? ''}}" />
                                       <label>About Workshop <span class="text-danger">*</span></label>
									   <textarea class="form-control" rows="5" name="about_workshop" id="about_workshop" placeholder="About Workshop" required="required"><?php if(!empty($workshop_details->description)){ $about = str_replace("<br />"  , '' , $workshop_details->description); echo $about; } ; ?></textarea>
								     </div>
                                    </div>
                          <div class="row">
							 <div class="col-md-6">
                                  <div class="form-group">  
                                     <button type="button" class="btn btn-success"  id="edit_about_workshop_btn">Save &nbsp;<i class="icon-paperplane ml-2"></i></button>
                                  </div>
                              </div>
                          </div>
						</div>
                      </form>
                    <div id="response_about_workshop"></div>   
                  </div>
                  <div class="modal-footer">       
                </div>
              </div>
            </div>
<!--End-->
<!--Add new category modal popup-->
<div class="modal" id="category_popup">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                    <h4 class="modal-title" id="myModalLabel"><i class="text-white icon-profile mr-3 icon-1x"></i> Add New Category  </h4>
                    <hr />
                  </div>
                  <!-- Modal body -->
						<form id="add_category_form">	
                         @csrf
                        <div class="modal-body">
                          <div class="row ">
							 <div class="col-md-12">
                                  <div class="form-group">
										 <input type="hidden" class="form-control" name="workshop_id" required="required" id="workshop_id" value="{{ $workshop_details->id ?? ''}}" />
                                        <label>Select  Category </label>
										  <select class="form-control multiselect" name="category[]"  multiple="multiple" data-fouc>
                                            {!! $parent_category !!} 
                                         </select>
                                        <span id="title_err"></span>
									</div>
                              </div>
                          </div>
                          <div class="row">
							 <div class="col-md-6">
                                  <div class="form-group">  
                                     <button type="submit" class="btn btn-success" name="submit_address" id="submit_category">Save &nbsp;<i class="icon-paperplane ml-2"></i></button>
                                  </div>
                              </div>
                          </div>
						</div>
                      </form>
                    <div id="response_add_category"></div>   
                  </div>
                  <div class="modal-footer">       
                </div>
              </div>
            </div>
<!--End-->
<!--Address popup script start-->
<div class="modal" id="addrs_popup">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                    <h4 class="modal-title" id="myModalLabel">
                    <span id="change_heading">
                    <i class="text-white icon-profile mr-3 icon-1x"></i> Add New </span> Address  </h4>
                    <hr />
                  </div>
                  <!-- Modal body -->
						<form id="workshop_adrs_form">	
                         @csrf
                        <div class="modal-body">
                         <div id="hidden_item"></div>
                          <div class="row ">
							 <div class="col-md-12">
                                  <div class="form-group">  
                                    <label>Address 1&nbsp;<span class="text-danger">*</span></label>
									 <input type="hidden" class="form-control" name="workshop_id" required="required" value="{{ $workshop_details->id ?? ''}}" />
                                     <input type="text" class="form-control" placeholder="Address 1" name="address_1" id="address_1" required="required" />
                                  </div>
                                  <div class="form-group">  
                                    <label>Address 2&nbsp;<span class="text-danger">*</span></label>
									 <input type="text" class="form-control" placeholder="Address 2" name="address_2" id="address_2" required="required" />
                                  </div>
                                  <div class="form-group">  
                                    <label>Zip Code&nbsp;<span class="text-danger">*</span></label>
									 <input type="text" class="form-control" placeholder="Zip Code" name="zip_code" id="zip_code" required="required" />
                                  </div>
                              </div>
                          </div>
                          <div class="row">
							 <div class="col-md-12">
                              <div class="">
                                      <input type="hidden" id="country_edit_id" value="">
                                      <input type="hidden" id="country_edit_name" value="">
                                      <label>Country &nbsp;<span class="text-danger">*</span>&nbsp;</label>
                                       <select class="form-control country" name="country" id="country_1">
                                        <option value="0">--Select-- Country--Name</option>
                                       </select>
                              </div>
                             </div> 
                          </div>
                          <div class="row" style="margin-top:15px; margin-bottom:15px;">
							 <div class="col-sm-6">
                               <div class="">
                                      <label>State &nbsp;<span class="text-danger">*</span>&nbsp;</label>
                                      <select class="form-control state" name="state" id="state">
                                         @if(!empty($bank_details->state_id))
                                        <option value="<?php echo $bank_details->state_id."@".$bank_details->state_name; ?>">{{ $bank_details->state_name }}</option>
                                        @else
                                         <option value="0">--Select--State--Name--</option>
                                        @endif
                                        
                                       </select>
                               </div>
                             </div>
                             <div class="col-sm-6">
                                 <div class="">        
                                       <label>City &nbsp;<span class="text-danger">*</span>&nbsp;</label>
                                       <select class="form-control cities" name="city" id="city">
                                       @if(!empty($bank_details->state_id))
                                        <option value="<?php echo $bank_details->city_id."@".$bank_details->city_name; ?>">{{ $bank_details->city_name }}</option>
                                        @else
                                         <option value="0">--Select--City--Name--</option>
                                        @endif
                                        
                                        
                                       </select>
                                  </div>     
                              </div>
                          </div>
                          <div class="row">
							 <div class="col-md-6">
                                  <div class="form-group">  
                                    <label>Latitude <span class="text-danger">*</span></label>
									 <input type="text" class="form-control" placeholder="Latitude" name="latitude" id="latitude" required="required" />
                                  </div>
                              </div>
                             <div class="col-md-6">
                                  <div class="form-group">  
                                    <label>Longitude <span class="text-danger">*</span></label>
									 <input type="text" class="form-control" placeholder="Longitude " name="longitude" id="longitude" required="required" />
                                  </div>
                              </div>
                          </div>
                          <div class="row">
							 <div class="col-md-6">
                                  <div class="form-group">  
                                     <button type="submit" class="btn btn-success" name="submit_address" id="submit_address">Add&nbsp;<i class="icon-paperplane ml-2"></i></button>
                                  </div>
                              </div>
                          </div>
						</div>
                      </form>
                    <div id="response_workshop_adrs"></div>   
                  </div>
                  <div class="modal-footer">       
                </div>
              </div>
            </div>
<!--End-->
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
							<span class="breadcrumb-item active"> Workshop Details </span>
						</div>
						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>
					<!--<div class="header-elements d-none">
						<div class="breadcrumb justify-content-center">
							<a href="#" class="breadcrumb-elements-item">
								<i class="icon-comment-discussion mr-2"></i>
								Support
							</a>
						</div>
					</div>-->
				</div>
@stop
@push('scripts')
<script src="{{ url('validateJS/admin.js') }}"></script>
  <script src='{{ url("validateJS/vendors.js") }}'></script>
	<script src="{{ url('global_assets/js/plugins/media/fancybox.min.js')}}"></script>
	<script src="{{ url('global_assets/js/demo_pages/gallery.js')}}"></script>
<script src="{{ url('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
 <script src="{{ url('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>
  <script src="{{ url('global_assets/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
  <script src="{{ url('global_assets/js/demo_pages/form_multiselect.js') }}"></script>
@endpush


