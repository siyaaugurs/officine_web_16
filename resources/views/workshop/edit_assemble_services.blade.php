@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<input type="hidden" name="category_id" id="category_id" value="{{ $assemble_services_details->category_id }}" />
<div class="content">
  @if(Session::has('msg'))
    {!! Session::get('msg') !!}
  @endif
    <div class="row">
        <div class="col-md-12">
            <div class="card" id="about_assemble_service_section">
                <div class="card-header bg-light header-elements-inline">
                    <h6 class="card-title">@lang('messages.AboutAssembleProduct') 
                    @if($assemble_services_details->users_id == Auth::user()->id)
                    <a href="#" class="ml-3 icn-sm green-bdr popup_btn" data-modalname="about_services_popup">
                    <i class="icon-pencil"></i>
                    </a>
                    @endif 
                    </h6>
                    <div class="header-elements">
                        <div class="list-icons">
                            <a class="list-icons-item" data-action="collapse"></a>
                            <a class="list-icons-item" data-action="remove"></a>
                            
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <h3 style="font-weight:800; font-size:14px;">About Product Assemble </h3>
                    @php $about_services = str_limit($assemble_services_details->about_services, 200); @endphp
                        @if($assemble_services_details != NULL)	
                        {!! $about_services !!}
                        @else
                            <h2>No content Available</h2>
                        @endif 
                    <h3 style="font-weight:800; font-size:14px;margin-top:15px;">Product Name </h3>
                        @if($assemble_services_details != NULL)	
                        {!! $assemble_services_details->listino !!}
                        @else
                            <h2>No content Available</h2>
                        @endif
                    <h3 style="font-weight:700; font-size:14px; margin-top:15px;">Service Average Time</h3>
                        @if($assemble_services_details != NULL)	
                        {!! $assemble_services_details->service_average_time !!} Minute 
                        @else
                            <h2>No content Available</h2>
                        @endif 
                </div>
            </div>
            <div class="card">
                <div class="card-header bg-light header-elements-inline">
                    <h6 class="card-title">@lang('messages.PackgesList') 
                        @if($assemble_services_details->users_id == Auth::user()->id)
                            <!-- <a href="#" class="ml-3 icn-sm green-bdr popup_btn" data-modalname="pakages_popup">
                            <i class="icon-pencil"></i>
                            </a> -->
                        @endif
                    </h6>
                    <div class="header-elements">
                        <div class="list-icons">
                            <a class="list-icons-item" data-action="collapse"></a>
                            <a class="list-icons-item" data-action="remove"></a>
                        </div>
                    </div>
                </div>             
                <div class="card-body" id="services_week_section">
                    <ul class="media-list media-chat-scrollable mb-3">
                        @forelse($users_services_days as $days_list)
                        <li class="media">
                        <div class="mr-3">
                            {{ $loop->iteration ."." }}
                        </div>
                        <div class="media-body">
                            <div class="media-title d-flex flex-nowrap">
                            <a href="#" class="font-weight-semibold mr-3">{{ $days_list->name  }}</a>
                            <span class="font-size-sm text-muted text-nowrap ml-auto"> 
                                <a data-servicedaysid="{{ $days_list->id }}" href="#" class="ml-3 icn-sm red-bdr delete_services_days">
                                <i class="icon-x icon-2x"></i>
                                </a>
                            </span>
                            </div>
                        <br>
                        <div class="card">
                            <table class="table datatable-show-all">
                            <thead>
                            <tr>
                                <th>@lang('messages.SN')</th>
                                <th>@lang('messages.StartTime')</th>
                                <th>@lang('messages.EndTime')</th>
                                <th>@lang('messages.Price')</th>
                                <th>@lang('messages.mxappointment')</th>
                                <th>Discount Type</th>
                                <th>Discount Price</th>
                                <th>Special Slot Type</th>
                                <th>Special Slot Date</th>
                                <th>@lang('messages.Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                            $package_data = sHelper::get_service_packages($days_list->id); 
                            @endphp 
                            @forelse($package_data as $timing_data)
                            <tr>
                                <td>{{ $loop->iteration ."." }}</td>
                                <td>
                                {{-- @if(!empty($timing_data->start_time))
                                    {{ $timing_data->start_time." - ".$timing_data->end_time." - ".$timing_data->price }}
                                @endif --}}
                                @if(!empty($timing_data->start_time))
                                    {{ $timing_data->start_time }}
                                @endif
                                </td>
                                <td>
                                @if(!empty($timing_data->start_time))
                                    {{ $timing_data->end_time }}
                                @endif
                                </td>
                                <td>
                                @if(!empty($timing_data->start_time))
                                    {{ $timing_data->price }}
                                @endif
                                </td>
                                <td>
                                @if(!empty($timing_data->start_time))
                                    {{ $timing_data->max_appointment }}
                                @endif
                                </td>
                                <td>
                                @if(!empty($timing_data->start_time))
                                    @if($timing_data->discount_type == 1)
                                         In % 
                                    @elseif( $timing_data->discount_type == 2)
                                        In Rs. 
                                    @endif
                                @endif
                                </td>
                                <td>
                                @if(!empty($timing_data->start_time))
                                    {{ $timing_data->discount }}
                                @endif
                                </td>
                                <td>
                                @if(!empty($timing_data->start_time))
                                    @if($timing_data->special_time_slot_type == 1)
                                         Daily 
                                    @elseif( $timing_data->special_time_slot_type == 2)
                                        Weekly 
                                    @elseif($timing_data->special_time_slot_type == 3)
                                          Monthly 
                                    @endif
                                @endif
                                </td>
                                <td>
                                @if(!empty($timing_data->start_time))
                                    {{ $timing_data->monthly_date ?? "N/A" }}
                                @endif
                                </td>
                                <td><a href='#' data-packagesid="{{ $timing_data->id }}" data-serviceweeklydays="{{ $days_list->id }}" class="btn btn-danger delete_pakages">Delete</a>&nbsp;
                                <!-- <a href='#' data-packagesid="{{ $timing_data->id }}" data-serviceweeklydays="{{ $days_list->id }}" class="btn btn-primary add_coupon_popup_btn" data-modalname="add_coupon_popup" data-timeslot_id="{{ $timing_data->id }}">Add Coupons</a> -->
                                </td>
                                @empty
                                <tr>
                                <td colspan="5">@lang('messages.NoPackagesAvailable')</td>
                                </tr>
                                @endforelse
                            </tr>
                            </tbody>
                        </table>
                        </div>
                        </div>
                        </li>
                        @empty
                        <li class="media">
                        <div class="media-body">
                            <div class="media-title d-flex flex-nowrap">
                            <a class="font-weight-semibold mr-3">@lang('messages.NoHourListAvailable')</a>
                            </div>
                        </div>
                        </li>
                        @endforelse   
                    </ul>
                </div>
            </div>
            <div class="card">
                <div class="card-header bg-light header-elements-inline">
                    <h6 class="card-title">Service Gallery Manage  </h6>
                    <div class="header-elements">
                        <div class="list-icons">
                            <a class="list-icons-item" data-action="collapse"></a>
                            <a class="list-icons-item" data-action="remove"></a>
                        </div>
                    </div>
                </div>             
                <div class="card-body">
                    <div class="row">
                        <form id="uplolad_service_images">
                            <input type="hidden" name="service_id" value="{{ $assemble_services_details->id }}" id="category_id">
                            <div class="control-group" id="fields">
                                <label class="control-label" for="field1">
                                    Browse Multiple Image
                                </label>
                                <div class="controls">
                                    <div class="entry input-group col-xs-3">
                                        <input class="btn btn-primary" name="gallery_image[]" type="file" multiple="multiple">
                                        <span class="input-group-btn">&nbsp;&nbsp;
                                            <button class="btn btn-success btn-add" type="submit" id="save_image">Save &nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-plus"></span></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="row" id="response_msg"></div>
                    <div class="row" style="margin-top:10px;" id="image_grid_section">
                        @forelse($images_arr as $images)
                        <div class="col-sm-4 col-md-3 col-lg-3">
                            <div class="card">
                                <div class="card-img-actions m-1">
                                    <img class="card-img img-fluid" src="{{ $images->image_url}}" alt="" />
                                    <div class="card-img-actions-overlay card-img">
                                        <a href="{{ $images->image_url }}" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round" data-popup="lightbox" rel="group">
                                            <i class="icon-plus3"></i>
                                        </a>
                                        
                                        <a href='{{ url("vendor_ajax/delete_image/$images->id") }}' data-imageid="{{ $images->id }}" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2 remove_service_image">
                                            <i class="icon-trash"></i>
                                        </a>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--About Business modal popup script start-->
<div class="modal" id="about_services_popup">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
        <h4 class="modal-title" id="myModalLabel"><i class="text-white icon-profile mr-3 icon-1x"></i> @lang('messages.EditAboutServiceDetails') </h4>
        <hr />
      </div>
      <!-- Modal body -->
      <form id="edit_assemble_services_form">	
      <input type="hidden" value="{{ $assemble_services_details->id }}" id="service_id">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12 form-group">
              <label>Select Makers</label>
              <select class="form-control" name="car_makers" id="car_makers">
                    <option>--Select-- Makers--Name--</option>
                    @foreach($cars__makers_category as $makers)
                    <option value="{{ $makers->idMarca }}">{{ $makers->Marca }}</option>
                    @endforeach 
                </select>
            </div>
            <div class="col-md-12 form-group">
                    <label>@lang('messages.SelectModel')</label>
                    <select class="form-control" name="car_models" id="car_models">
                        <option>--First--Select--Makers--Name--</option>
                    </select>                                
                </div>
                <div class="col-md-12 form-group">
                <label>@lang('messages.SelectVersion')</label>
                    <select class="form-control car_version_group" name="car_version" id="car_version">
                        <option>--First--Select--Model--Name--</option>
                    </select>                                
                </div>
                <div class="col-md-12 form-group">
                    <label>@lang('messages.SelectGroupItem')</label>
                    <select class="form-control" name="car_group_version" id="group_item" data-action="get_and_save_products_item">
                        <option value="0">@lang('messages.firstSelectVersion')</option>
                    </select> 
                </div>
                <div class="col-md-12 form-group">
                    <label>Select Item </label>
                    <select class="form-control" name="item_id" id="item_id" data-action="save_get_products">
                        <option value="0">@lang('messages.firstSelectGroupItem')</option>
                    </select>
                </div>
                <div class="col-md-12 form-group">
                    <label>@lang('messages.SelectProduct') </label>
                    <select class="form-control" name="inventory_product" id="inventory_product">
                        <option value="{{ $assemble_services_details->products_id }}">{{ $assemble_services_details->listino }}</option>
                    </select>
                </div>
            <div class="col-md-12 form-group">
                <label>Service Average Timing <span class="text-danger">(in minute) *</span></label>
                <!-- <input type="text" name="service_average_time" id="service_average_time" value="@if(!empty($assemble_services_details->service_average_time)){{ $assemble_services_details->service_average_time }} @endif" class="form-control"  placeholder="Service Average Time" required="required"/> -->
                <input type="number" name="service_average_time" id="service_average_time" value="@if(!empty($assemble_services_details->service_average_time)){{ $assemble_services_details->service_average_time }}@endif" class="form-control" placeholder="Service Average Time" required="required">
            </div>
            
            
            <div class="col-md-12 form-group">
              <input type="hidden" class="form-control" id="services_id" name="services_id" required="required" value="{{ $assemble_services_details->id }}" />
              <label>@lang('messages.AboutServices') <span class="text-danger">*</span></label>
              <textarea class="form-control" rows="5" name="about_services" id="about_services" placeholder="@lang('messages.AboutServices')" required="required"><?php if(!empty($assemble_services_details->about_services)){ $about = str_replace("<br />"  , '' , $assemble_services_details->about_services); echo $about; } ; ?></textarea>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
            <div class="form-group">  
             <button type="button" class="btn btn-success"  id="edit_assemble_btn">@lang('messages.Save') &nbsp;<i class="icon-paperplane ml-2"></i></button>
            </div>
            </div>
          </div>
        </div>
      </form>
      <div id="response_about_services"></div>   
    </div>
    <div class="modal-footer">       
    </div>
  </div>
</div>
<!--End-->

<div class="modal" id="add_coupon_popup">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
        <h4 class="modal-title" id="myModalLabel">Edit Pakages </h4>
        <hr />
      </div>
      <!--service coupon Modal body -->
      <form id="service_coupon_form">
        @csrf
    <input type="hidden" name="package_id" id="package_id" />    
		<!-- <input type="" value="<?php if(!empty($coupons_details)) echo $coupons_details->id; ?>" name="edit_id" /> -->
    <input type="hidden" name="service_id" id="service_id" value="<?php if(!empty($users_services_details->id)) echo $users_services_details->id; ?>">
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-12" id="edit_response">
                <div class="form-group">
                    <label>Select  Category </label>
                        <select class="form-control multiselect" name="coupon_type" >
                            <option value="1" <?php if(!empty($coupons_details)){ if($coupons_details->coupon_type == 1) echo "selected"; }?>>Coupon</option>
                            <option value="2" <?php if(!empty($coupons_details)){ if($coupons_details->coupon_type == 2) echo "selected"; }?>>Group Coupon</option>
                        </select>
                        <span id="title_err"></span>
                </div>
                <div class="form-group">
                    <label>Title&nbsp;<span class="text-danger">*</span></label>
                    <input type="text" name="coupon_title" id="coupon_title" class="form-control" placeholder="@lang('messages.couponTitle')" required="required" value="<?php if(!empty($coupons_details)) echo $coupons_details->coupon_title; ?>" />
                    <span id="title_err"></span>
                </div>
                <div class="row form-group" id="">
                    <div class="col-sm-6">
                        <label>Quantity&nbsp;<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="coupon_quantity" placeholder="@lang('messages.Quantity')" value="<?php if(!empty($coupons_details)) echo $coupons_details->coupon_quantity; ?>" min="0" required="required"/>
                    </div>
                    <div class="col-sm-6">
                        <label>Per User (Allowed Quantity)&nbsp;<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="per_user_allot" placeholder="@lang('messages.AllowedUserQuantity')" value="<?php if(!empty($coupons_details)) echo $coupons_details->per_user_allot; ?>" min="1" max="4" required="required"/>
                    </div>
                </div>
                <div class="row form-group" id="">
                    <div class="col-sm-6">
                        <label>Launching Date&nbsp;<span class="text-danger">*</span></label>
                        <input type="text" id="datecupan" class="form-control datepicker" name="launching_date" placeholder="@lang('messages.StartDate')" value="<?php if(!empty($coupons_details)) echo $coupons_details->launching_date; ?>" readonly="readonly" required="required"/>
                    </div>
                    <div class="col-sm-6">
                        <label>Closed On&nbsp;<span class="text-danger">*</span></label>
                        <input type="text" class="form-control datepicker" name="closed_date" placeholder="@lang('messages.ClosingDate')" id="datecupan1" value="<?php if(!empty($coupons_details)) echo $coupons_details->closed_date; ?>" readonly="readonly" required="required"/>
                    </div>
                </div>
                <div class="row form-group" id="">
                    <div class="col-sm-6">
                        <label>Available Date&nbsp;<span class="text-danger">*</span></label>
                        <input type="text" class="form-control datepicker" name="avail_date" placeholder="@lang('messages.AvailableDate')" id="avail_date" value="<?php if(!empty($coupons_details)) echo $coupons_details->avail_date; ?>" readonly="readonly"  required="required"/>
                    </div>
                    <div class="col-sm-6">
                        <label>Expiry Date<span class="text-danger">*</span></label>
                        <input type="text" class="form-control datepicker" id="cpoupon_expiry_date" name="avail_close_date" placeholder="@lang('messages.ExpiryDate')" value="<?php if(!empty($coupons_details)) echo $coupons_details->avail_close_date; ?>"  readonly="readonly" required="required"/>
                    </div>
                </div>
                <div class="row form-group" id="">
                    <div class="col-sm-6">
                        <label>select offer type</label>
                        <select class="form-control multiselect" name="offer_type" >
                            <option value="1" <?php if(!empty($coupons_details)){ if($coupons_details->offer_type == 1) echo "selected"; }?> >In percentage</option>
                            <option value="2" <?php if(!empty($coupons_details)){ if($coupons_details->offer_type == 2) echo "selected"; }?> >In rate</option>
                        </select>
                        <!-- <input type="text" class="form-control datepicker" name="avail_date" placeholder="@lang('messages.AvailableDate')" id="avail_date" value="<?php if(!empty($coupons_details)) echo $coupons_details->avail_date; ?>" readonly="readonly"  required="required"/> -->
                    </div>
                    <div class="col-sm-6">
                        <label>Amount <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="amount" placeholder="@lang('messages.Amount')" value="<?php if(!empty($coupons_details)) echo $coupons_details->amount; ?>" min="0" required="required"/>
                    </div>
                </div>
                <div class="col-md-12 form-group">
                    @if(empty($coupons_details->coupon_image))
                        <img id="coupon_image" src="{{ asset('storage/coupon/default.png') }}" alt="" height="150" width="150">
                    @else
                        <img id="coupon_image" src="{{ asset('storage/coupon_image/'.$coupons_details->coupon_image) }}" alt="" height="150" width="150">
                    @endif
                    <span class="btn btn-default btn-file">
                        Browse… <input type="file" class="form-control form-group" id="imgInp" name="coupon_image">
                    </span>
                    <img id='coupon_image'/>
                </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6">
            <div class="form-group">  
              <button type="submit" class="btn btn-success"  id="">Save &nbsp;<i class="icon-paperplane ml-2"></i></button>
            </div>
            </div>
          </div>
        </div>
      </form>
      <div id="response_about_pakages"></div>
    </div>
    <div class="modal-footer">       
    </div>
  </div>
</div>
<!--End-->

<!--Add pakages modal popup-->
<div class="modal" id="pakages_popup">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
        <h4 class="modal-title" id="myModalLabel">Edit Pakages </h4>
        <hr />
      </div>
      <!-- Modal body -->
      <form id="edit_packages_form">
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-12" id="edit_response"></div>
          </div>
          @forelse($service_days as $pakages_days)
          <div class="day-row">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check form-check-inline service_days">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-control-styled weekly_days" name="week_days[]" value="{{ $pakages_days->common_weekly_days_id}}"   data-fouc onclick="check_rows_data(1)">
                            {{ $pakages_days->name }}
                        </label>
                    </div>
                </div>
                <div class="add_fields" style="display:none">
                    <div class="row">
                      <div class="col-sm-12 err_msg" id="date_err" ></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>@lang('messages.StartDate')&nbsp;<span class="text-danger">*</span></label>
                                    <input type="text"  class="form-control" id="start_time" name="start_time[]" placeholder="@lang('messages.StartDate')" onblur="check_rows_data(2)"  />
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>@lang('messages.EndDate')&nbsp;<span class="text-danger">*</span></label>
                                    <input type="text"  class="form-control" id="end_time" name="end_time[]" placeholder="@lang('messages.EndDate')" onblur="check_rows_data(3)" />
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>@lang('messages.Price')&nbsp;<span class="text-danger">*</span></label>
                                <input type="number" name="services_price[]" id="price"  placeholder="@lang('messages.Price')" onblur="check_rows_data(4)" class="form-control"/>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                            <label>@lang('messages.mxappointment')&nbsp;<span class="text-danger">*</span></label>
                                <input type="number" name="maximum_appointment[]" id="maximum_appointment"  class="form-control"  placeholder="Maximum Appointment" />
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group pt-4">
                                <button type="button" class="btn btn-success add_btn">
                                    <i class="icon-plus3"></i>&nbsp;@lang('messages.AddMore')
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          @empty
          @endforelse
          <div class="row">
            <div class="col-md-6">
            <div class="form-group">  
              <button type="submit" class="btn btn-success"  id="add_services_btn">@lang('messages.Save') &nbsp;<i class="icon-paperplane ml-2"></i></button>
            </div>
            </div>
          </div>
        </div>
      </form>
      <div id="response_about_pakages"></div>
    </div>
    <div class="modal-footer">       
    </div>
  </div>
</div>
<!--End--->
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang('messages.Home')</a>
            <span class="breadcrumb-item active"> @lang('messages.ServiceDetails') </span>
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
<script src='{{ url("validateJS/products.js") }}'></script>
<script src="{{ url('validateJS/assemble_service.js') }}"></script>
<script src="{{ url('global_assets/js/plugins/media/fancybox.min.js')}}"></script>
<script src="{{ url('global_assets/js/demo_pages/gallery.js')}}"></script>
<script src="{{ url('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>
<script src="{{ url('validateJS/services.js') }}"></script> 
<script src="{{ url('validateJS/date/jquery-ui.css') }}"></script>
<script src="{{ url('validateJS/date/jquery-ui.js') }}"></script>
<script>
	$('#start_time, #end_time ').datetimepicker({
		format: 'HH:mm:ss'
	});
</script>
<script>
    $(document).ready(function(e){
       $(document).on('click','.add_coupon_popup_btn',function(e){
           e.preventDefault();
           $("#add_coupon_popup").modal('show');
       });
        
    });
</script>
  <script>
    $(document).ready(function(e){
        $(".add_btn").click(function(){
            let $clone = $(this).closest('.row').clone();     
            $(this).closest('.row').after($clone);
            $(this).closest('.row').next().find('.add_btn').remove();
            $(this).closest('.row').next().find('.col-sm-3:last-child .form-group').html('<button type="button" class="btn btn-danger remove_add_fields"><i class="icon-x"></i>&nbsp;Remove</button>')
                    
        })
        $(document).on('click', '.remove_add_fields', function(){
            $(this).closest('.row').remove();
        })
        $(".service_days input[type='checkbox']").on('click', function(){
            // $(this).closest('.add_fields').remove();
            if($(this).is(':checked')){
                // alert();
                $(this).closest('.d-flex').next('.add_fields').slideDown();
                console.log( $(this).closest('.d-flex').next('.add_fields'));
            }
            else{
                $(this).closest('.d-flex').next('.add_fields').slideUp();
            }
        })        
    })
  </script>
@endpush


