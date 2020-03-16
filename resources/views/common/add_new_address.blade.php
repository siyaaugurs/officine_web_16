@extends('layouts.master_layouts')
@section('content')
<style>
#myMap {  height: 350px; width: 680px;}
</style>
<div class="content">
	<input type="hidden" name="page" id="page" value="{{ $page }}" />
	<input type="hidden" name="page" id="page_type" value="{{ $page_type }}" />
	<div class="card" id="workshop_details_section">
		<div class="card-header bg-light header-elements-inline">
			<h6 class="card-title">Add Address Details</h6>
		</div>
        <div class="card-body">
            <form id="workshop_adrs_form" autocomplete="off">
                @csrf
                <input type="hidden" id="workshop_id" name="workshop_id" value="{{ $workshop_id }}">
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label>@lang('messages.RegisteredOffice')&nbsp;<span class="text-danger">*</span></label>
                        <textarea type="text" class="form-control" placeholder="@lang('messages.RegisteredOfficeAddress')" name="address_1" id="address_1" required></textarea>
                        <span id="registered_office_err"></span>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-12">
                        <label>Zip Code &nbsp;<span class="text-danger">*</span>&nbsp;</label>
                        <input type="text" name="zip_code" id="zip_code" class="form-control" value="" />
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-6">
                        <label>@lang('messages.Latitude') &nbsp;<span class="text-danger">*</span>&nbsp;</label>
                        <input type="text" name="latitude" id="latitude" class="form-control" value="" />
                    </div>
                    <div class="col-sm-6">
                        <label>@lang('messages.Longitude') &nbsp;<span class="text-danger">*</span>&nbsp;</label>
                        <input type="text" name="longitude" id="longitude" class="form-control" value="" />
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
					<button type="submit" id="submit_address" class="btn bg-blue ml-3">@lang('messages.Submit') <i class="icon-paperplane ml-2"></i></button>
				</div>
            </form>
        </div>
	</div> 
</div>  
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
 var latitude = '', longitude = '' , myLatlng = '';
 var searchInput = 'address_1';
 $(document).ready(function(e) {
	var page = $('#page').val();
    var map;
    var marker; 
    /* var lat = $('#latitude').val();
    var long = $('#longitude').val();
    myLatlng = new google.maps.LatLng(lat,long);
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
                    console.log(results[0])
                    $('#latitude,#longitude').show();
                    //$('#address_1').val(results[0].formatted_address);
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
</script>
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
	<div class="d-flex">
		<div class="breadcrumb">
			<a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang('messages.Home')</a>
			<span class="breadcrumb-item active"> Add Address Details </span>
		</div>
		<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
	</div>
</div>
@stop
@push('scripts')
  <script type="text/javascript" src="{{ url('validateJS/date/jquery-ui.css') }}"></script>
  <script type="text/javascript" src="{{ url('validateJS/date/jquery-ui.js') }}"></script>
  <!-- <script type="text/javascript" src="{{ url('validateJS/common.js') }}"></script> -->
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD7OIFvK1-udIFDgZwvY7FVTFHMHipNy6Y">
</script>
<script>
    $(document).ready(function(e) {
        $(document).on('submit', '#edit_address_details_form', function(e) {
            $('#msg_response').html(" ");
            $("err_response").html(" ");
            $('#edit_address_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
            e.preventDefault();
            $.ajax({
                url: base_url + "/commonAjax/add_workshop_adrs",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    errorString = '';
                    $('#edit_address_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled', false);
                    var parseJson = jQuery.parseJSON(data);
                    if (parseJson.status == 200) {
                        $("#msg_response_popup").modal('show');
                        $("#msg_response").html(parseJson.msg);
                        setTimeout(function() { window.location.href = base_url + "/add_address_details" }, 1000);
                    }
                    if (parseJson.status == 400) {
                        $.each(parseJson.error, function(key, value) {
                            errorString += '<div class="notice notice-danger"><strong>Wrong , </strong>' + value + ' .</div>';
                        });
                        $('#err_response').html(errorString);
                    }
                    if (parseJson.status == 100) {
                        $("#msg_response_popup").modal('show');
                        $("#msg_response").html(parseJson.msg);
                        setTimeout(function() { location.reload(); }, 1000);
                    }
                },
                error: function(xhr, error) {
                    $('#edit_address_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled', false);
                    $("#response_msg").html(parseJson.msg);
                }
            });
        });
        $(document).on('submit', '#workshop_adrs_form', function(e) {

$('#response_workshop_adrs').html(" ");

$('#submit_address').html('Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);

e.preventDefault();

$.ajax({

    url: base_url + "/commonAjax/add_workshop_adrs",

    type: "POST",

    data: new FormData(this),

    contentType: false,

    cache: false,

    processData: false,

    success: function(data) {
        console.log(data)
            //$('#response_workshop_adrs').html(data);

        var parseJson = jQuery.parseJSON(data);

        $('#submit_address').html(' Save <i class="icon-paperplane ml-2"></i>').attr('disabled', false);
        console.log(parseJson.status)
        var url = base_url+"/add_address_details";
        if (parseJson.status == 100) {

            $("#response_workshop_adrs").html(parseJson.msg);

            $('#workshop_address_section').load(document.URL + ' #workshop_address_section');

            $('#workshop_adrs_form')[0].reset();

        }

        if (parseJson.status == 200) {

            $("#msg_response_popup").modal('show');
            $("#msg_response").html(parseJson.msg);
            setTimeout(function(){ window.location.href = url; } , 1000);

        }

        if (parseJson.status == 400) {

            $.each(parsedJson.error, function(key, value) {

                errorString += '<div class="notice notice-success"><strong>Success , </strong>' + value + ' .</div>';

            });

            $('#response_workshop_adrs').html(errorString);

        }

    }

});

});
    })
</script>

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
@endpush

