@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<!--Workshop address-->
<div class="card" id="workshop_address_section">
    <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title">Workshop Address</h6>
        <div class="header-elements">
            <div class="list-icons">
                <!-- <a class="list-icons-item" data-action="collapse"></a> -->
                <!-- <a class="list-icons-item" data-action="remove"></a> -->
            </div>
        </div>
    </div>
    <div class="card-body">
        <ul class="media-list media-chat-scrollable mb-3">
            <li class="media text-muted">
                <a href='{{ url("/add_new_address") }}' id="add_more_addrs_details" class="btn btn-success"> <i class="icon-plus3"></i>&nbsp;Add New Address</a>
            </li>
            @forelse($address_list as $adrs_list)
            <li class="media">
                <div class="mr-3">
                    {{ $loop->iteration ."." }}
                </div>
                <div class="media-body">
                    <div class="media-title d-flex flex-nowrap">
                        <a class="font-weight-semibold mr-3">
                            {{ ucfirst($adrs_list->address_1) ?? ''}}
                            <!-- {{ ucfirst($adrs_list->address_3) ?? ' '}}
                            {{ ucfirst($adrs_list->landmark) ?? ' '}}
                            {{ ucfirst($adrs_list->zip_code)." ," ?? ' '}}
                            {{ ucfirst($adrs_list->country_name)."," ?? ' '}}
                            {{ ucfirst($adrs_list->state_name)." ," ?? ' '}}
                            {{ ucfirst($adrs_list->city_name) ?? ' '}} -->
                        </a>
                        <span class="font-size-sm text-muted text-nowrap ml-auto">
                            <a data-adrsid="{{$adrs_list->id}}" href='{{ url("/edit_address_details/$adrs_list->id") }}' class="ml-3 icn-sm blue-bdr" target="_blank">
                                <i class="icon-pencil5 icon-2x"></i>
                            </a>
                            <!-- <a data-adrsid="{{$adrs_list->id}}" href="#" class="ml-3 icn-sm blue-bdr edit_address">
                                <i class="icon-pencil5 icon-2x"></i>
                            </a> -->
                            &nbsp;&nbsp;
                            <a data-adrsid="{{$adrs_list->id}}" href="#" class="icn-sm red-bdr remove_adrs">
                                <i class="icon-x icon-2x"></i>
                            </a>
                        </span>
                    </div>
                </div>
            </li>
            @empty
            <li class="media">
                <div class="media-body">
                    <div class="media-title d-flex flex-nowrap">
                        <a class="font-weight-semibold mr-3">No Address available , please add new address</a>
                    </div>
                </div>
            </li>
            @endforelse
        </ul>
    </div>
</div>
<!--End-->
<!--Address popup script start-->
<div class="modal" id="addrs_popup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                        class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span id="change_heading">
                        <i class="text-white icon-profile mr-3 icon-1x"></i> Add New </span> Address </h4>
                <hr />
            </div>
            <!-- Modal body -->
            <form id="workshop_adrs_form">
                @csrf
                <div class="modal-body">
                    <div id="hidden_item">
                        </div>
                    <input type="hidden" id="edit_address_id" name="edit_address_id" value="" readonly />
                    <!-- <div class="row">
                        <div class="col-sm-12">
                            <a href="javascript::void()" class="popup_btn" data-modalname="map_popup">@lang('messages.PickYourAddress') </a>
                        </div>
                    </div> -->
                    <div class="row ">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Address&nbsp;<span class="text-danger">*</span></label>
                                <input type="hidden" class="form-control" name="workshop_id" required="required"
                                    value="{{ $workshop_details->id ?? ''}}" id="workshop_id" />
                                <!-- <input type="text" class="form-control" placeholder="Address" name="address_1" id="address_1" required="required" /> -->
                                <textarea type="text" class="form-control" placeholder="@lang('messages.RegisteredOfficeAddress')" name="address_1" id="registered_office" required></textarea>
                            </div>
                            <!-- <div class="form-group">
                                <label>Address 2&nbsp;</label>
                                <input type="text" class="form-control" placeholder="Address 2" name="address_2"
                                    id="address_2" />
                            </div> -->
                            <div class="form-group">
                                <label>Zip Code&nbsp;<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="Zip Code" name="zip_code"
                                    id="zip_code" required="required" />
                            </div>
                        </div>
                    </div>
                    <!-- <div class="row">
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
                                    <option
                                        value="<?php echo $bank_details->state_id."@".$bank_details->state_name; ?>">
                                        {{ $bank_details->state_name }}</option>
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
                                    <option value="<?php echo $bank_details->city_id."@".$bank_details->city_name; ?>">
                                        {{ $bank_details->city_name }}</option>
                                    @else
                                    <option value="0">--Select--City--Name--</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div> -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Latitude</label>
                                <input type="text" class="form-control" placeholder="Latitude" name="latitude"
                                    id="latitude" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Longitude </label>
                                <input type="text" class="form-control" placeholder="Longitude " name="longitude"
                                    id="longitude" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <button type="submit" class="btn btn-success" name="submit_address"
                                    id="submit_address">Add&nbsp;<i class="icon-paperplane ml-2"></i></button>
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
<!-- @include('common.component.map') -->
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
 $(document).ready(function() {
	var page = $('#page').val();
    var map;
    var marker; 

    var autocomplete;
    autocomplete = new google.maps.places.Autocomplete((document.getElementById(searchInput)), {
        types: ['geocode'],
    });
	
    google.maps.event.addListener(autocomplete, 'place_changed', function () {
        var near_place = autocomplete.getPlace();
        console.log(near_place);
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
    /*if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position){
            latitude = position.coords.latitude;
            longitude = position.coords.longitude
            myLatlng = new google.maps.LatLng(latitude , longitude);
        });
    } else { 
        alert("Geolocation is not supported by this browser.");
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
    
});
</script>

@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home </a>
            <span class="breadcrumb-item active"> Add Address Details </span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop
@push('scripts')
<script src="{{ url('validateJS/admin.js') }}"></script>
<script src="{{ url('validateJS/date/jquery-ui.css') }}"></script>
<script src="{{ url('validateJS/date/jquery-ui.js') }}"></script>
<script src="{{ url('validateJS/vendors.js') }}"></script>

</script>
@endpush
