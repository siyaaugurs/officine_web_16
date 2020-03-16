@extends('layouts.master_layouts')
@section('content')

<input type="hidden" name="page" id="page" value="{{ $page }}" />

<input type="hidden" name="category_id" id="category_id" value="<?php if(!empty($category_id))echo $category_id; ?>"  readonly="readonly" />

<input type="hidden" name="car_size" id="car_size" value="<?php if(!empty($car_size))echo $car_size; ?>" readonly="readonly" />
<style>
.colWrap{ margin-top:15px; }

</style>

<div class="content">

  @if(Session::has('msg'))

    {!! Session::get('msg') !!}

  @endif

  <div class="row">

    <div class="col-md-12">

      <div class="card" id="about_service_section">

          <div class="card-header bg-light header-elements-inline">

                <h6 class="card-title">@lang('messages.AboutServices') 

               @if($users_services_details != NULL) 

                @if($users_services_details->users_id == Auth::user()->id)

                  <a href="#" id="add_services" class="ml-3 icn-sm green-bdr" data-modalname="about_services_popup">

                   <i class="icon-pencil"></i>

                  </a>

                @endif 

               @endif 

                </h6>

                <div class="header-elements">
                    <div class="list-icons">
                    </div>
                </div>
            </div>
           <div class="card-body">

               <div class="row">

                 <div class="col-sm-12">

                   @if(!empty($category_details != NULL))	

                     <h3 style="font-weight:800; font-size:14px;">Service </h3>

                    {!! $category_details->category_name !!}

                   @else

                    <h2>No content Available</h2>

                   @endif

                 </div>

               </div>

               <div class="row colWrap">

                 <div class="col-sm-12">

                   @if(!empty($category_details != NULL))	

                     <h3 style="font-weight:800; font-size:14px;">Service Description </h3>

                    {!! $category_details->description !!}

                   @else

                    <h6>No content Available</h6>

                   @endif

                 </div>

               </div>

               <div class="row colWrap">

                 <div class="col-sm-12">

                   @if(!empty($category_details != NULL))	

                     <h3 style="font-weight:800; font-size:14px;">Time </h3>

                     {{ $service_average_time ? $service_average_time : 'N/A' }}

                   @else

                    <h2>No content Available</h2>

                   @endif

                 </div>

               </div>

               <div class="row colWrap">

                 <div class="col-sm-12">

                   <h3 style="font-weight:800; font-size:14px;"> Hourly Cost</h3>

                    @if(!empty($service_details != NULL))	

                      @if(!empty($service_details->hourly_rate))

                       {{ $service_details->hourly_rate }}

                      @endif

                    @else

                       <h6>N/A</h6>

                    @endif

                 </div>

               </div>

               <div class="row colWrap">

                 <div class="col-sm-12">

                   <h3 style="font-weight:800; font-size:14px;">Price </h3>

                   @if(!empty($price != NULL))
                    &euro;&nbsp;{{ $price }}
                   @else
                    <h6>N/A</h6>
                   @endif

                 </div>

               </div>

               <div class="row colWrap">

                 <div class="col-sm-12">

                   <h3 style="font-weight:800; font-size:14px;">Max Appointment </h3>

                    @if(!empty($service_details != NULL)) 

                      @if(!empty($service_details->maximum_appointment))

                       {{ $service_details->maximum_appointment }}

                      @endif

                    @else

                     <h6>N/A</h6>

                    @endif

                 </div>

               </div>

                <h3 style="font-weight:700; font-size:14px; margin-top:15px;">Car size</h3>
                @if($car_size)	
                  {{ sHelper::get_car_size($car_size) }}  
                @else
                   <h6>N/A</h6>
                @endif 

            </div>

        </div>
      <!--<div class="card">

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
          <div class="row" id="response_msg"></div>

          <div class="row" style="margin-top:10px;" id="image_grid_section">

           @if($images_arr != FALSE) 

            @foreach($images_arr as $images)

               <div class="col-sm-4 col-md-3 col-lg-3">

									<div class="card">

										<div class="card-img-actions m-1">

											<img class="card-img img-fluid" src="{{ $images->image_url}}" alt="" />

											<div class="card-img-actions-overlay card-img">

												<a href="{{ $images->image_url }}" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round" data-popup="lightbox" rel="group">

													<i class="icon-plus3"></i>

												</a>

												

                                                <!-- <a href='{{ url("vendor_ajax/delete_image/$images->id") }}' data-imageid="{{ $images->id }}" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2 remove_service_image">

													<i class="icon-trash"></i>

												</a> -->

												

                                            </div>

										</div>

									</div>

								</div>

            @endforeach

           @endif 

        </div>

        </div>

      </div>-->
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

      <form id="edit_about_services_form">	

        <div class="modal-body">

          <div class="row">

            <!-- <div class="col-md-12 form-group">

              <label>Service Average Timing <span class="text-danger">(in minute) *</span></label>

              <input type="text" name="service_average_time" id="service_average_time" value="@if(!empty($users_services_details->service_average_time)){{ $users_services_details->service_average_time }} @endif" class="form-control"  placeholder="Service Average Time" required="required"/>

            </div> -->

            <!-- <div class="col-md-12 form-group">

              <label>Car Size <span class="text-danger"> *</span></label>

              <select class="form-control" name="car_size" id="car_size">

                         <option hidden="hidden">--Select--Car--Size--</option>

                         <option value="1">Small</option>

                         <option value="2">Average </option>

                         <option value="3">Big</option>

                      </select>

            </div> -->

            

            <div class="col-md-12 form-group">

              <input type="hidden" class="form-control" id="services_id" name="services_id" required="required" value="" />

              <label>@lang('messages.AboutServices') <span class="text-danger">*</span></label>

              <textarea class="form-control" rows="5" name="about_services" id="about_services" placeholder="@lang('messages.AboutServices')" required="required"></textarea>

            </div>

          </div>

          <div class="row">

            <div class="col-md-6">

            <div class="form-group">  

             <button type="button" class="btn btn-success"  id="edit_about_services_btn">@lang('messages.Save') &nbsp;<i class="icon-paperplane ml-2"></i></button>

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

    <input type="hidden" name="service_package_id" id="service_package_id" value=""/>

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

				</div>

                <div class="modal" id="add_car_washing_services">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i>

				</button>

				<h4 class="modal-title" id="myModalLabel">Car Wash details </h4>

				<hr />

			</div>

			<!-- Modal body -->

            <div class="card-body">

                    <form id="add_services_form" autocomplete="off">

                        @csrf

                        <div class="form-group">

                            <label>@lang('messages.HourlyRate')&nbsp;<span class="text-danger">*</span></label>

                            <input type="number" class="form-control" placeholder="@lang('messages.HourlyRate')" name="hourly_rate" id="hourly_rate" required="required" min="1" max="1000" value="{{ !empty($service_details->hourly_rate) ? $service_details->hourly_rate : ''}}">

                               <span class="text-danger" id="hourly_rate_err"></span>

                        </div>

                        <div class="form-group">

                            <label>@lang('messages.maxAppointment')&nbsp;<span class="text-danger">*</span></label>

                            <input type="text" class="form-control" placeholder="@lang('messages.maxAppointment')" name="max_appointment" id="max_appointment" required="required" value="{{ !empty($service_details->maximum_appointment) ? $service_details->maximum_appointment : ''}}" />

                            <span id="title_err"></span>

                        </div>

                        <!--<div class="row form-group">

                        <div class="col-sm-12">

                                <label>Service Average Timing <span class="text-danger">(in minute) *</span></label>

                                <input type="number" name="service_average_time" id="service_average_time" value="" class="form-control"  placeholder="Service Average Time" required="required" min="0" max="1000"/>

                                </div>

                            </div>

                        <div class="row form-group">

                        <div class="col-sm-12">

                                <label>Select Car size&nbsp;<span class="text-danger">*</span></label>

                                <select class="form-control" name="car_size" id="car_size">

                                    <option hidden="hidden">--Select--Car--Size--</option>

                                    <option value="1">Small</option>

                                    <option value="2">Average </option>

                                    <option value="3">Big</option>

                                </select>

                                </div>

                            </div> -->       

                        <div class="d-flex justify-content-between align-items-center">

                            <div class="form-check form-check-inline">

                                 <button type="submit" id="add_services_btn_copy" class="btn bg-blue ml-3">@lang('messages.Submit')<i class="icon-paperplane ml-2"></i></button>

                                <!--<button type="submit" id="add_services_btn_copy" class="btn bg-blue ml-3">@lang('messages.Submit')<i class="icon-paperplane ml-2"></i></button>-->

                            </div>

                        </div>

                    </form>

            </div>

			<div id="response_err"></div>

		</div>

		<div class="modal-footer"></div>

	</div>

</div>

@stop

@push('scripts')

  <script src='{{ url("validateJS/car_wash.js") }}'></script>

  <script src="{{ url('validateJS/admin.js') }}"></script>

   <script src="{{ url('validateJS/services.js') }}"></script> 

    <script src="{{ url('validateJS/service_slot.js') }}"></script> 

  <script src='{{ url("validateJS/vendors.js") }}'></script>

	<script src="{{ url('global_assets/js/plugins/media/fancybox.min.js')}}"></script>

	<script src="{{ url('global_assets/js/demo_pages/gallery.js')}}"></script>

  <script src="{{ url('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>

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

            $this = $(this);

            var package_id = $(this).data('packagesid');

            $("#service_package_id").val(package_id);

            $("#add_coupon_popup").modal('show');

        });

        

    });

</script>

@endpush





