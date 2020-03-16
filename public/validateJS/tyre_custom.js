$(document).ready(function(e) {
    /*Tyre image remove script start*/
    $(document).on('click', '.remove_tyre_image', function(e) {
        con = confirm("Are you sure want to remove this image !!!");
        var tyres_image = $(this);
        var image_id = tyres_image.data('imageid');
        if (con == true) {
            $.ajax({
                url: base_url + "/tyre24_ajax/remove_tyre_image",
                method: "GET",
                data: { image_id: image_id },
                success: function(data) {
                    if (data != 200) {
                        $('#msg_response').html(data);
                        $("#msg_response_popup").modal('show');
                    } else {
                        tyres_image.closest('.tyre_grid_col').remove();
                        //$(".tyre_grid_col").
                    }
                }
            });
        }
    });
    /*End*/
    /*Save TYre24  Tyre details script start*/
    $(document).on('submit', '#edit_tyre_details_by_admin', function(e) {
        $('#msg_response').html(" ");
        $('#edit_tyre_details').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
        e.preventDefault();
        $.ajax({
            url: base_url + "/tyre24_ajax/save_tyre24_details",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                var parseJson = jQuery.parseJSON(data);
                errorString = '';
                if (parseJson.status == 400) {
                    $.each(parseJson.error, function(key, value) {
                        errorString += '<div class="notice notice-info"><strong>Note , </strong>' + value + ' .</div>';
                    });
                    $('#msg_response').html(errorString);
                }
                if (parseJson.status == 200) {
                    $('#msg_response').html(parseJson.msg);
                    setTimeout(function() { location.reload(); }, 1000);
                }
            },
            error: function(xhr, error) {
                $('#msg_response').html('<div class="notice notice-danger"><strong>Wrong </strong> Something Went Wrong !!! </div>');
            },
            complete: function(xhr, setting) {
                $("#msg_response_popup").modal('show');
                $('#edit_tyre_details').html('Save&nbsp; <span class="glyphicon glyphicon-plus"></span>').attr('disabled', false);
            }
        });
    });

    /*End*/
    /*Search Tyre from database on search*/
    $(document).on('click', '#search_tyre_from_database', function(e) {
        var diameter_measurement = $("#diameter_measurement").val();
        var aspect_ratio_measurement = $("#aspect_ratio_measurement").val();
        var with_measurement = $("#with_measurement").val();
        if (with_measurement != "" && aspect_ratio_measurement != "" && diameter_measurement != "") {
            $("#preloader").show();
            $.ajax({
                url: base_url + "/tyre_ajax/get_tyre_from_database",
                beforeSend: function() {
                    $("#tire_ColWrap").empty();
                },
                method: "GET",
                data: { with_measurement: with_measurement, aspect_ratio_measurement: aspect_ratio_measurement, diameter_measurement: diameter_measurement },
                success: function(data) {
                    $("#preloader").hide();
                    $('#tire_ColWrap').html(data);
                },
                error: function(xhr, error) {
                    $("#preloader").hide();
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html('<div class="notice notice-danger"><strong>Wrong </strong> Something Went Wrong please try again !!! </div>');
                }
            });
        }
    });
    /*End*/

    /*On model Select */
    $(document).on('change', '#diameter_measurement', function() {
        var diameter_measurement = $("#diameter_measurement").val();
        var aspect_ratio_measurement = $("#aspect_ratio_measurement").val();
        var with_measurement = $("#with_measurement").val();
        if (with_measurement != "" && aspect_ratio_measurement != "" && diameter_measurement != "") {
            $("#preloader").show();
            $.ajax({
                url: base_url + "/tyre_ajax/get_tyres",
                method: "GET",
                data: { with_measurement: with_measurement, aspect_ratio_measurement: aspect_ratio_measurement, diameter_measurement: diameter_measurement },
                success: function(data) {
                    var parseJson = jQuery.parseJSON(data);
                    if (parseJson.status == 100) {
                        $("#msg_response_popup").modal('show');
                        $("#msg_response").html(parseJson.msg);
                    }
                },
                error: function(xhr, error) {
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html('<div class="notice notice-danger"><strong>Wrong </strong> Something Went Wrong please try again !!! </div>');
                },
                complete: function() {
                    $("#preloader").hide();
                }
            });
        }
    });
    /*End*/
    /*Get Cars model name on makers name change script start*/
    $(document).on('change', '#car_makers', function() {
        $("#preloader").show();
        var makers_id = $("#car_makers").val();
        if (makers_id != "") {
            $.ajax({
                url: base_url + "/products_ajax/get_model_name",
                method: "GET",
                data: { makers_id: makers_id },
                success: function(data) {
                    $("#preloader").hide();
                    var parseJson = jQuery.parseJSON(data);
                    var html_content = '';
                    if (parseJson.status == 200) {
                        html_content += '<option value="0">--Select--Car--Model--</option>';
                        $.each(parseJson.response, function(index, value) {
                            var value_model = value.idModello + "/" + value.ModelloAnno;
                            html_content += '<option value="' + value_model + '">' + value.Modello + " >> " + value.ModelloAnno + '</option>';
                        });
                        $("#car_models").html(html_content);
                    }
                },
                error: function(xhr, error) {
                    $("#preloader").hide();
                }
            });
        }
    });
    /*End*/

    /*Search Tyre from database on search*/
    $(document).on('click', '#search_tyre_en_number_from_database', function(e) {
        var en_number = $("#ean_number").val();
        if (en_number != "") {
            //$("#preloader").show();
            $.ajax({
                url: base_url + "/tyre_ajax/get_tyre_en_number_from_database",
                beforeSend: function() {
                    $("#tire_ColWrap").empty();
                },
                method: "GET",
                data: { en_number: en_number },
                success: function(data) {
                    //$("#preloader").hide();
                    $('#tire_ColWrap').html(data);
                },
                error: function(xhr, error) {
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html('<div class="notice notice-danger"><strong>Wrong </strong> Something Went Wrong please try again !!! </div>');
                }
            });
        }
    });
    /*End*/
    /*Search Tyre  from database on Speed Index and Tyre Type search*/
    $(document).on('click', '#search_tyre_load_index_from_database', function(e) {
        var load_index = $("#tyre_load_index").val();
        var tyre_type = $("#tyre_type_value").val();
        if (load_index != "" && tyre_type != "") {
            $("#preloader").show();
            $.ajax({
                url: base_url + "/tyre_ajax/get_tyre_by_load_index",
                beforeSend: function() {
                    $("#tire_ColWrap").empty();
                },
                method: "GET",
                data: { load_index: load_index, tyre_type: tyre_type },
                success: function(data) {
                    $("#preloader").hide();
                    $('#tire_ColWrap').html(data);
                },
                error: function(xhr, error) {
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html('<div class="notice notice-danger"><strong>Wrong </strong> Something Went Wrong please try again !!! </div>');
                }
            });
        } else {
            $("#msg_response_popup").modal('show');
            $("#msg_response").html('<div class="notice notice-danger"><strong>Wrong </strong> Please Select All Required Fields !!! </div>');
        }
    });
    /*End*/
});