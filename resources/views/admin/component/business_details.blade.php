<?php
	// echo "<pre>";
	// print_r($edit_status);exit;
?>
<style>
	#myMap {  height: 350px; width: 680px;}
</style>
<form id="business_details_form_admin">
	@csrf
		@if($edit_status != NULL)
		<input type="hidden" id="page_type" value="{{ $edit_status }}">
		@else
		@endif
	<div class="form-group">
		<label>@lang('messages.OwnerName')&nbsp;<span class="text-danger">*</span></label>
		<input type="hidden" name="workshop_id" id="workshop_id" class="form-control"  value="<?php if(!empty($workshop_id))echo $workshop_id; ?>" required  />
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
	<div class="row">
		<div class="col-md-12 form-group">
			<label>@lang('messages.RegisteredOffice')&nbsp;<span class="text-danger">*</span></label>
			<textarea type="text" class="form-control" placeholder="@lang('messages.RegisteredOfficeAddress')" name="registered_office" id="registered_office" required>{{ $business_details->registered_office ?? '' }}</textarea>
			<span id="registered_office_err"></span>
		</div>
	</div>
	<div class="row form-group">
		<div class="col-sm-12">
			<label>@lang('messages.PostalCode')&nbsp;<span class="text-danger">*</span>&nbsp;</label>
			<input type="text" class="form-control" placeholder="@lang('messages.PostalCode')" name="postal_code" id="postal_code" value="{{ $business_details->postal_code ?? '' }}" required/>
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
	<div id="response"></div>
	<div class="d-flex justify-content-between align-items-center">
		<button type="submit" id="business_details_sbmt" class="btn bg-blue ml-3">@lang('messages.Submit') <i class="icon-paperplane ml-2"></i></button>
	</div>
</form>

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
 var latitude = '', longitude = '' , myLatlng= '';

var searchInput = 'registered_office';
 $(document).ready(function(e) {
	var page = $('#page').val();
    var map;
    var marker; 
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
					$('#zip_code').val(near_place.address_components[i].long_name);
				}
			}
		}
	});
	
	$(document).on('change', '#'+searchInput, function () {
		document.getElementById('latitude').value = '';
		document.getElementById('longitude').value = '';
		document.getElementById('zip_code').value = '';
	});
    
});

 $(document).ready(function(e) {
    var page_type = $('#page_type').val();
    var map;
    var marker;
    if(page_type == "edit_business") {
		var lat = $('#latitude').val();
        var long = $('#longitude').val();
        myLatlng = new google.maps.LatLng(lat,long);
    } else {
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(function(position){
				latitude = position.coords.latitude;
				longitude = position.coords.longitude
	 	        myLatlng = new google.maps.LatLng(latitude , longitude);
			});
		} else { 
			alert("Geolocation is not supported by this browser.");
		}
	}
    var geocoder = new google.maps.Geocoder();
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
                infowindow.setContent(results[0].formatted_address);
                infowindow.open(map, marker);
            }
        }
    });
    google.maps.event.addListener(marker, 'dragend', function() {
        geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    $('#registered_office').val(results[0].formatted_address);
                    $('#latitude').val(marker.getPosition().lat());
                    $('#longitude').val(marker.getPosition().lng());
                    for (var i = 0; i < results[0].address_components.length; i++) {
                        for (var j = 0; j < results[0].address_components[i].types.length; j++) {
                            if (results[0].address_components[i].types[j] == "postal_code") {
                                $('#postal_code').val(results[0].address_components[i].long_name);
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
google.maps.event.addDomListener(window, 'load', initialize);
    
});
</script>