@extends('layouts.master_layouts')
@section('content')
<style>
#myMap {  height: 350px; width: 680px;}
</style>
<div class="content">
	<input type="hidden" name="page" id="page" value="{{ $page }}" />
	<input type="hidden" name="page" id="page_type" value="{{ $page_type }}" />
	<div class="tab_here mb-3">
		<ul class="nav nav-pills m-b-10" id="pills-tab" role="tablist">
			<li class="nav-item">
				<a class="nav-link <?php if($page == "add_business_details") echo "active"; ?>"  href='{{ url("add_business_details")}}'>@lang('messages.BusinessDetails')</a>
			</li>
			<li class="nav-item">
				<a class="nav-link  <?php if($page == "bank_details") echo "active"; ?>" href='{{ url("bank_details")}}'>@lang('messages.BankDetails')</a>
			</li>
		</ul>
	</div>
	@if($business_details == NULL || $fill_form == TRUE)    
	 <div class="card">
		<div class="card-header bg-light header-elements-inline">
			<h6 class="card-title" style="font-weight:600;"><i class="fa fa-info-circle"></i>&nbsp;&nbsp;@lang('messages.AddBusinessDetails')</h6>
		</div>
		<div class="card-body">
			<form id="business_details_form">
				@csrf
				<div class="form-group">
					<label>@lang('messages.OwnerName')&nbsp;<span class="text-danger">*</span></label>
					<input type="text" name="owner_name" id="owner_name" class="form-control" placeholder="@lang('messages.OwnerName')" value="{{ $business_details->owner_name ?? '' }}" required  />
					<span id="owner_name_err"></span>
				</div>
				<div class="row">
					<div class="col-md-12 form-group">
						<label>@lang('messages.BusinessName')&nbsp;<span class="text-danger">*</span></label>
						<input type="text" class="form-control" placeholder="@lang('messages.BusinessName')" name="business_name" id="business_name" value="{{ $business_details->business_name ?? '' }}" required />
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 form-group">
						<label>@lang('messages.AboutBusiness')&nbsp;<span class="text-danger">*</span></label>
						<textarea type="text" class="form-control" placeholder="@lang('messages.AboutBusiness')" name="about_business" id="about_business" required>{{ $business_details->about_business ?? '' }}</textarea>
						<span id="about_business_err"></span>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 form-group">
						<label>@lang('messages.FiscalCode')&nbsp;<span class="text-danger">*</span></label>
						<input type="text" class="form-control" placeholder="@lang('messages.FiscalCode')" name="fiscal_code" id="fiscal_code" value="{{ $business_details->fiscal_code ?? '' }}" required="required" />
					</div>
					<div class="col-md-6 form-group">
						<label>@lang('messages.VATNumber')&nbsp;<span class="text-danger">*</span></label>
						<input type="text" class="form-control" placeholder="@lang('messages.VATNumber')" name="vat_number" id="vat_number" value="{{ $business_details->vat_number ?? '' }}" required="required"  />
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 form-group">
						<label>@lang('messages.SDIrecipientCode')&nbsp;<span class="text-danger">*</span></label>
						<input type="text" class="form-control" placeholder="@lang('messages.SDIrecipientCode')" name="sdi_recipient_code" id="sdi_recipient_code" value="{{ $business_details->sdi_recipient_code ?? '' }}" required="required" />
					</div>
					<div class="col-md-6 form-group">
						<label>@lang('messages.PEC')&nbsp;<span class="text-danger">*</span></label>
						<input type="email" class="form-control" placeholder="@lang('messages.example@xyx.com')" name="pec" id="pec" value="{{ $business_details->pec ?? '' }}" required="required"  />
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 form-group">
						<label>@lang('messages.RegistrationProof')&nbsp;<span class="text-danger">*</span>&nbsp; @if(!empty($business_details->registration_proof)) <a target="_blank" href='{{ asset("storage/business_details/$business_details->registration_proof") }}'>@lang('messages.ClickToView')</a> @endif
						</label>
						<input type="hidden" name="registration_proof_copy" value="{{ $business_details->address_proof ?? ''}}" />
						<input type="file" class="form-control" placeholder="@lang('messages.BrowseRegistrationProof')" name="registration_proof" id='registration_proof' />
						<span id="registration_proof_err"></span>
					</div>
						<div class="col-md-6 form-group">
						<label>@lang('messages.AddressProof')&nbsp;<span class="text-danger">*</span>&nbsp; @if(!empty($business_details->address_proof))<a target="_blank" href='{{ asset("storage/business_details/$business_details->address_proof") }}'>@lang('messages.ClickToView')</a> @endif</label>
						<input type="hidden" name="address_proof_copy" value="{{ $business_details->address_proof ?? ''}}" />
						<input type="file" class="form-control" placeholder="BrowseAddressProof" name="address_proof" id='address_proof'   />
						<span id="title_err"></span>
					</div>
				</div>
				<div class="row form-group">
					<div class="col-sm-12">
						<label>@lang('messages.PostalCode')&nbsp;<span class="text-danger">*</span>&nbsp;</label>
						<input type="text" class="form-control" placeholder="@lang('messages.PostalCode')" name="postal_code" id="postal_code" value="{{ $business_details->postal_code ?? '' }}" required/>
					</div>
					<input type="hidden" id="country_edit_id" value="@if(!empty($business_details->country_id)){{ $business_details->country_id }} @endif">
					<input type="hidden" id="country_edit_name" value="@if(!empty($business_details->country_name)){{ $business_details->country_name }} @endif">
					<!-- <div class="col-sm-6">
						<label>@lang('messages.Country') &nbsp;<span class="text-danger">*</span>&nbsp;</label>
						<select class="form-control country" name="country" id="country_1">
							@if(!empty($business_details->country_id))
							<option value="<?php echo $business_details->country_id."@".$business_details->country_name; ?>">{{ $business_details->country_name }}</option>
							@endif
							<option value="0">@lang('messages.SelectCountryName')</option>
						</select>
					</div> -->
				</div>
				<!-- <div class="row form-group">
					<div class="col-sm-6">
						<label>@lang('messages.Province') &nbsp;<span class="text-danger">*</span>&nbsp;</label>
						<select class="form-control state" name="state" id="state">
							@if(!empty($business_details->state_id))
								<option value="<?php echo $business_details->state_id."@".$business_details->state_name; ?>">{{ $business_details->state_name }}</option>
							@else
								<option value="0">@lang('messages.SelectStateName')</option>
							@endif
						</select>
					</div>
					<div class="col-sm-6">
						<label>@lang('messages.City') &nbsp;<span class="text-danger">*</span>&nbsp;</label>
						<select class="form-control cities" name="city" id="city">
							@if(!empty($business_details->state_id))
								<option value="<?php echo $business_details->city_id."@".$business_details->city_name; ?>">{{ $business_details->city_name }}</option>
							@else
								<option value="0">@lang('messages.SelectCityName')</option>
							@endif
						</select>
					</div>
				</div> -->
				<div class="row">
					<div class="col-md-12 form-group">
						<label>@lang('messages.RegisteredOffice')&nbsp;<span class="text-danger">*</span> <!-- <a href="" class="popup_btn" data-modalname="map_popup">@lang('messages.PickYourAddress') </a> --></label>
						<textarea type="text" class="form-control" placeholder="@lang('messages.RegisteredOfficeAddress')" name="registered_office" id="registered_office" required>{{ $business_details->registered_office ?? '' }}</textarea>
						<span id="registered_office_err"></span>
					</div>
				</div>
                <div class="row form-group">
					<div class="col-sm-6">
						<label>@lang('messages.Latitude') &nbsp;<span class="text-danger">*</span>&nbsp;</label>
						<input type="text" name="latitude" id="latitude" class="form-control" value="{{ $business_details->latitude ?? '' }}" />
					</div>
					<div class="col-sm-6">
						<label>@lang('messages.Longitude') &nbsp;<span class="text-danger">*</span>&nbsp;</label>
						<input type="text" name="longitude" id="longitude" class="form-control" value="{{ $business_details->langitude ?? '' }}" />
					</div>
				</div>
                @if($fill_form == FALSE) 
                    <div class="row form-group">
						<div class="col-sm-3">
							<div class="d-flex justify-content-between align-items-center">
								<div class="form-check form-check-inline">
									<label class="form-check-label">
										<input type="checkbox" class="form-control-styled term_condition" name="term_condition" value="1" data-fouc required="required">
										@lang('messages.IAcceptAllTermAndCondition')  .
									</label>
								</div>
							</div>
						</div>
					</div>
                @endif 
                <div id="response"></div>
				<div class="d-flex justify-content-between align-items-center">
					<button type="submit" id="business_details_sbmt" class="btn bg-blue ml-3">@lang('messages.Submit') <i class="icon-paperplane ml-2"></i></button>
				</div>
			</form>
		</div>                      
	</div>
	@else
	 <div class="card" id="workshop_details_section">
		<div class="card-header bg-light header-elements-inline">
			<h6 class="card-title">@lang('messages.BusinessDetails') &nbsp;<a href="{{ url('add_business_details/edit') }}" class="ml-3 icn-sm green-bdr">
			<i class="icon-pencil"></i>
			</a></h6>
			<div class="header-elements">
				<div class="list-icons">
					<a class="list-icons-item" data-action="collapse"></a>
					<a class="list-icons-item" data-action="remove"></a>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-body" id="days_hour_section">
				<ul class="media-list media-chat-scrollable mb-3">
					<li class="media">
						<div class="mr-3">
							1.
						</div>
						<div class="media-body">
							<div class="media-title d-flex flex-nowrap">
								<a class="font-weight-semibold mr-3">@lang('messages.OwnerName')</a>
								<span class="font-size-sm text-muted text-nowrap ml-auto"> 
								</span>
							</div>
							{{ $business_details->owner_name ?? $users_profile->f_name." ".$users_profile->l_name }}
						</div>
					</li>
					<li class="media">
						<div class="mr-3">
							2.
						</div>
						<div class="media-body">
							<div class="media-title d-flex flex-nowrap">
								<a class="font-weight-semibold mr-3">@lang('messages.BusinessName')</a>
								<span class="font-size-sm text-muted text-nowrap ml-auto"> 
								</span>
							</div>
							{{ $business_details->business_name ?? "Not mentioned" }}
						</div>
					</li>
					<li class="media">
						<div class="mr-3">
							3.
						</div>
						<div class="media-body">
							<div class="media-title d-flex flex-nowrap">
								<a class="font-weight-semibold mr-3">@lang('messages.RegistrationProof')</a>
								<span class="font-size-sm text-muted text-nowrap ml-auto"> 
								</span>
							</div>
								@if(!empty($business_details->registration_proof) || !empty($business_details->address_proof))
								@php 
									$reg_proof = $business_details->registration_proof;
									$adrs_proof = $business_details->address_proof;
								@endphp  
								@else 
								@php 
									$reg_proof = "";
									$adrs_proof = "";
								@endphp 
								@endif
							<a target="_blank" href='{{ asset("storage/business_details/$reg_proof") }}'>{{ $business_details->registration_proof ?? "Not mentioned" }}</a>
						</div>
					</li>
					<li class="media">
						<div class="mr-3">
							4.
						</div>
						<div class="media-body">
							<div class="media-title d-flex flex-nowrap">
								<a class="font-weight-semibold mr-3">@lang('messages.AddressProof')</a>
								<span class="font-size-sm text-muted text-nowrap ml-auto"> 
								</span>
							</div>
								<a target="_blank" href='{{ asset("storage/business_details/$adrs_proof") }}'>{{ $business_details->address_proof ?? "Not mentioned" }}</a> 
						</div>
					</li>
					<li class="media">
						<div class="mr-3">
							5.
						</div>
						<div class="media-body">
							<div class="media-title d-flex flex-nowrap">
								<a class="font-weight-semibold mr-3">@lang('messages.Address')</a>
								<span class="font-size-sm text-muted text-nowrap ml-auto"> 
								</span>
							</div>
							{{ $business_details->registered_office ?? " " }}
							{{--
							{{ $business_details->address_1 ?? "N/A  ," }}
							{{ $business_details->address_2 ?? "N/A  ," }}
							{{ $business_details->address_3 ?? "N/A  ," }}
							{{ $business_details->landmark ?? "N/A " }} --}}
						</div>
					</li>
					<li class="media">
						<div class="mr-3">
							6.
						</div>
						<div class="media-body">
							<div class="media-title d-flex flex-nowrap">
								<a class="font-weight-semibold mr-3">@lang('messages.AboutBusiness')</a>
								<span class="font-size-sm text-muted text-nowrap ml-auto"> 
								</span>
							</div>
							{{ $business_details->about_business ?? "N/A " }}
						</div>
					</li>
					<li class="media">
						<div class="mr-3">
							7.
						</div>
						<div class="media-body">
							<div class="media-title d-flex flex-nowrap">
								<a class="font-weight-semibold mr-3">@lang('messages.FiscalCode')</a>
								<span class="font-size-sm text-muted text-nowrap ml-auto"> 
								</span>
							</div>
							{{ $business_details->fiscal_code ?? "N/A " }}
						</div>
					</li>
					<li class="media">
						<div class="mr-3">
							8.
						</div>
						<div class="media-body">
							<div class="media-title d-flex flex-nowrap">
								<a class="font-weight-semibold mr-3">@lang('messages.VATNumber') </a>
								<span class="font-size-sm text-muted text-nowrap ml-auto"> 
								</span>
							</div>
							{{ $business_details->vat_number ?? "N/A " }}
						</div>
					</li>
					<li class="media">
						<div class="mr-3">
							9.
						</div>
						<div class="media-body">
							<div class="media-title d-flex flex-nowrap">
								<a class="font-weight-semibold mr-3">@lang('messages.SDIRecipientCode')</a>
								<span class="font-size-sm text-muted text-nowrap ml-auto"> 
								</span>
							</div>
							{{ $business_details->sdi_recipient_code ?? "N/A " }}
						</div>
					</li>
					<li class="media">
						<div class="mr-3">
							10.
						</div>
						<div class="media-body">
							<div class="media-title d-flex flex-nowrap">
								<a class="font-weight-semibold mr-3">@lang('messages.PEC') </a>
								<span class="font-size-sm text-muted text-nowrap ml-auto"> 
								</span>
							</div>
							{{ $business_details->pec ?? "N/A " }}
						</div>
					</li>
					<li class="media">
						<div class="mr-3">
							11.
						</div>
						<div class="media-body">
							<div class="media-title d-flex flex-nowrap">
								<a class="font-weight-semibold mr-3">@lang('messages.PostalCode')</a>
								<span class="font-size-sm text-muted text-nowrap ml-auto"> 
								</span>
							</div>
							{{ $business_details->postal_code ?? "N/A " }}
						</div>
					</li>
					<!-- <li class="media">
						<div class="mr-3">
							11.
						</div>
						<div class="media-body">
							<div class="media-title d-flex flex-nowrap">
								<a class="font-weight-semibold mr-3">@lang('messages.Country') / @lang('messages.Province') / @lang('messages.City')</a>
								<span class="font-size-sm text-muted text-nowrap ml-auto"> 
								</span>
							</div>
							{{ $business_details->country_name ?? "N/A " }} /
							{{ $business_details->state_name ?? "N/A " }} /
							{{ $business_details->city_name ?? "N/A " }} 
						</div>
					</li> -->
					
				</ul>
			</div>
		</div>    
	</div>   
    @endif
</div>  
{{--@include('common.component.map') --}}
<div class="modal" id="map_popup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">@lang('messages.PickYourLocation') </h4>
                <hr />
            </div>
            <div class="modal-body">
                <div class="row" style="height:400px;">
                    <div class="col-md-12 form-group" id="myMap">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=AIzaSyD7OIFvK1-udIFDgZwvY7FVTFHMHipNy6Y"></script>
<script type="text/javascript"> 
 var latitude = '', longitude = '' , myLatlng;
 var searchInput = 'registered_office';
 $(document).ready(function(e) {
	var page = $('#page').val();
    var page_type = $('#page_type').val();
    var map;
    var marker;
    if(page_type == "Edit") {
		var lat = $('#latitude').val();
        var long = $('#longitude').val();
        myLatlng = new google.maps.LatLng(lat,long);
    } 
	if(page_type == "Add"){
		/* if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(function(position){
				latitude = position.coords.latitude;
				longitude = position.coords.longitude
	 	        myLatlng = new google.maps.LatLng(latitude , longitude);
			});
		} else { 
			alert("Geolocation is not supported by this browser.");
		} */
		var lat = $('#latitude').val("");
        var long = $('#longitude').val("");
	}
    /* var geocoder = new google.maps.Geocoder();
	var infowindow = new google.maps.InfoWindow();
    function initialize(){
        var mapOptions = {
			zoom: 7,
			center: myLatlng,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
    	map = new google.maps.Map(document.getElementById("myMap"), mapOptions);
		marker = new google.maps.Marker({
			map: map,
			position: myLatlng,
			draggable: true 
		}); 
		geocoder.geocode({'latLng': myLatlng }, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				if (results[0]) {
					$('#latitude,#longitude').show();
					$('#address_1').val(results[0].formatted_address);
					infowindow.setContent(results[0].formatted_address);
						infowindow.open(map, marker);
				}
			}
		});
		google.maps.event.addListener(marker, 'dragend', function() {
			geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					if (results[0]) {
						console.log(results)
						$('#registered_office').val(results[0].formatted_address);
						$('#address_1').val(results[0].formatted_address);
						$('#latitude').val(marker.getPosition().lat());
						$('#longitude').val(marker.getPosition().lng());
						for (var i = 0; i < results[0].address_components.length; i++) {
							for (var j = 0; j < results[0].address_components[i].types.length; j++) {
								if (results[0].address_components[i].types[j] == "postal_code") {
									$('#zip_code').val(results[0].address_components[i].long_name);
								}
							}
						}
						infowindow.setContent(results[0].formatted_address);
						infowindow.open(map, marker);
					}
				}
			});
		});
	}
	google.maps.event.addDomListener(window, 'load', initialize); */
    var autocomplete;
    autocomplete = new google.maps.places.Autocomplete((document.getElementById(searchInput)), {
        types: ['geocode'],
    });
	
    google.maps.event.addListener(autocomplete, 'place_changed', function () {
		var near_place = autocomplete.getPlace();
		
        document.getElementById('latitude').value = near_place.geometry.location.lat();
		document.getElementById('longitude').value = near_place.geometry.location.lng();
		for (var i = 0; i < near_place.address_components.length; i++) {
			for (var j = 0; j < near_place.address_components[i].types.length; j++) {
				if (near_place.address_components[i].types[j] == "postal_code") {
					$('#postal_code').val(near_place.address_components[i].long_name);
				}
			}
		}
	});
	
	$(document).on('change', '#'+searchInput, function () {
		
		document.getElementById('latitude').value = '';
		document.getElementById('longitude').value = '';
		document.getElementById('postal_code').value = '';
	});
});
</script>
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
	<div class="d-flex">
		<div class="breadcrumb">
			<a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang('messages.Home')</a>
			<span class="breadcrumb-item active"> {{ $page_name_bread }} @lang('messages.BusinessDetails') </span>
		</div>
		<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
	</div>
</div>
@stop
@push('scripts')
  <script type="text/javascript" src="{{ url('validateJS/date/jquery-ui.css') }}"></script>
  <script type="text/javascript" src="{{ url('validateJS/date/jquery-ui.js') }}"></script>
 <!--  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD7OIFvK1-udIFDgZwvY7FVTFHMHipNy6Y">
</script> -->
 <script>
  /*Date and time picker script start*/
  $( function() {
    $( "#datepicker" ).datepicker();
  } );
  $( function() {
    $( "#datepicker1" ).datepicker();
  } );
 $(function () {
	$('#datetimepicker3').datetimepicker({
		format: 'LT'
	});
  });
  </script>
  <script>
$(document).ready(function(e) {
	getLocation();
	$("#get_geo_location").click();
		function getLocation() {
		
    }
	function showPosition(position) {
		$('#latitude').val(position.coords.latitude);
		$('#longitude').val(position.coords.longitude);
		alert("Send");
	}

});
</script>
@endpush

