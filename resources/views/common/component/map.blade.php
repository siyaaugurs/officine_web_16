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
<script type="text/javascript"> 
    var page = $('#page').val();
    var page_type = $('#page_type').val();
    var lat = $('#latitude').val();
    var long = $('#longitude').val();
    var map;
    var marker;
    if((page == "add_business_details" && page_type == "Edit") || (page == "edit_address_details" && page_type == "Edit")) {
        var myLatlng = new google.maps.LatLng(lat,long);
    } else {
        var myLatlng = new google.maps.LatLng(41.871941,12.567380);
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
                $('#registered_office').val(results[0].formatted_address);
                $('#address_1').val(results[0].formatted_address);
                $('#latitude').val(marker.getPosition().lat());
                $('#longitude').val(marker.getPosition().lng());
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
google.maps.event.addDomListener(window, 'load', initialize);
</script>