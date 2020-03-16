$(document).ready(function(e) {

    $(document).on('click', '#add_tyre_type_btn', function(e) {
        e.preventDefault();
        $("#tyre_type_id").val("");
        $("#tyre_type_name").val("");
        $(".tyre_type_code_div").remove();
        $("#type_code").val("");
        $("#myModalLabel").html('Add Tyre Type');
        $('#add_tyre_measure_popup').modal({
            backdrop: 'static',
            keyboard: false,
        });
    });

    /*Add tyre type measurement */
    $(document).on('submit', '#add_new_tyre_type_measurement', function(e) {
        $('#response').html(" ");
        $("err_response").html(" ");
        $('#add_custom_tyre_type_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
        e.preventDefault();
        $.ajax({
            url: base_url + "/tyre_ajax/add_type_type_measurement",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                errorString = '';
                $('#add_custom_tyre_type_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled', false);
                var parseJson = jQuery.parseJSON(data);
                if (parseJson.status == 200) {
                    $(".close").click();
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                    setTimeout(function() { location.reload() }, 1000);
                }
                if (parseJson.status == 400) {
                    $.each(parseJson.error, function(key, value) {
                        errorString += '<div class="notice notice-danger"><strong>Wrong , </strong>' + value + ' .</div>';
                    });
                    $('#response').html(errorString);
                }
                if (parseJson.status == 100) {
                    $("#response").html(parseJson.msg);
                }
            },
            error: function(xhr, error) {

            }
        });
    });
    /*End */

    $(document).on('click', '.edit_tyre_type_measure', function(e) {
        e.preventDefault();
        var measure_id = $(this).data('measureid');
        var measure_type = $(this).data('measuretype');
        if (measure_id != "") {
            $.ajax({
                url: base_url + "/tyre_ajax/get_tyre_measure_details",
                method: "GET",
                data: { measure_id: measure_id, measure_type: measure_type },
                success: function(data) {
                    var parseJson = jQuery.parseJSON(data);
                    var errorString = '';
                    var html_content = '';
                    var type_code = jQuery.parseJSON(parseJson.response.code);
                    if (parseJson.status == 200) {
                        $("#tyre_type_id").val(parseJson.response.id);
                        $("#tyre_type_name").val(parseJson.response.name);
                        $.each(type_code, function(key, value) {
                            console.log(key)
                            if (key == 0) {
                                errorString += '<input type="text" class="form-control" placeholder="Type Code" id="type_code" name="type_code[]" required="required" value="' + value + '"><div class="input-group-append "><span class="btn btn-success input-group-text" id="append_code_type">Add More</span></div>'
                            } else {
                                html_content += '<div class="input-group mb-3 tyre_type_code_div" style="margin-top:10px;">' +
                                    '<input type="text" class="form-control" placeholder="Type Code" name="type_code[]" required="required" value="' + value + '">' +
                                    '<div class="input-group-append ">' +
                                    '<span class="btn btn-danger remove_type_code_div">X</span>' +
                                    '</div>' +
                                    '</div>';
                            }
                        });
                        $('#tyre_code_div').html(errorString);
                        $('#tyre_code_div').after().append(html_content);
                        $("#myModalLabel").html('Edit Tyre Type');
                        $('#add_tyre_measure_popup').modal({
                            backdrop: 'static',
                            keyboard: false,
                        });
                    }
                }

            });
        }
    });

    $(document).on('click', '.delete_tyre_type_measure', function(e) {
        e.preventDefault();
        var $this = $(this);
        var measure_id = $(this).data('measureid');
        var con = confirm("Are you sure want to remove ?");
        if (con == true) {
            $.ajax({
                url: base_url + "/tyre_ajax/delete_tyre_measure",
                method: "GET",
                data: { measure_id: measure_id },
                success: function(data) {
                    var parseJson = jQuery.parseJSON(data);
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson[0].msg);
                    setTimeout(function() { location.reload(); }, 1000);
                }
            });
        } else {
            return false;
        }
    });
    $(document).on('click', '#add_season_type_btn', function(e) {
        e.preventDefault();
        $("#season_type_id").val("");
        $("#season_type_name").val("");
        $("#season_code").val("");
        $("#myModalLabel").html('Add Season Type');
        $('#add_season_measure_popup').modal({
            backdrop: 'static',
            keyboard: false,
        });
    });
    /*Add Season type measurement */
    $(document).on('submit', '#add_new_season_measurement', function(e) {
        $('#response').html(" ");
        $("err_response").html(" ");
        $('#add_custom_season_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
        e.preventDefault();
        $.ajax({
            url: base_url + "/tyre_ajax/add_season_type_measurement",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                errorString = '';
                $('#add_custom_season_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled', false);
                var parseJson = jQuery.parseJSON(data);
                if (parseJson.status == 200) {
                    $(".close").click();
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                    setTimeout(function() { location.reload() }, 1000);
                }
                if (parseJson.status == 400) {
                    $.each(parseJson.error, function(key, value) {
                        errorString += '<div class="notice notice-danger"><strong>Wrong , </strong>' + value + ' .</div>';
                    });
                    $('#response').html(errorString);
                }
                if (parseJson.status == 100) {
                    $("#response").html(parseJson.msg);
                }
            },
            error: function(xhr, error) {

            }
        });
    });
    /*End */

    $(document).on('click', '.edit_season_type_measure', function(e) {
        e.preventDefault();
        var measure_id = $(this).data('measureid');
        var measure_type = $(this).data('measuretype');
        if (measure_id != "") {
            $.ajax({
                url: base_url + "/tyre_ajax/get_tyre_measure_details",
                method: "GET",
                data: { measure_id: measure_id, measure_type: measure_type },
                success: function(data) {
                    var parseJson = jQuery.parseJSON(data);
                    if (parseJson.status == 200) {
                        $("#season_type_id").val(parseJson.response.id);
                        $("#season_type_name").val(parseJson.response.name);
                        $("#season_code").val(parseJson.response.code2);
                        $("#myModalLabel").html('Edit Season Type');
                        $('#add_season_measure_popup').modal({
                            backdrop: 'static',
                            keyboard: false,
                        });
                    }
                }

            });
        }
    });

    $(document).on('click', '#aspect_ratio_btn', function(e) {
        e.preventDefault();
        $('#aspect_measure_popup').modal({
            backdrop: 'static',
            keyboard: false,
        });
    });

    /*Add Aspect Ratio measurement */
    $(document).on('submit', '#add_new_aspect_measurement', function(e) {
        $('#response').html(" ");
        $("err_response").html(" ");
        $('#add_tyre_aspect_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
        e.preventDefault();
        $.ajax({
            url: base_url + "/tyre_ajax/add_aspect_type_measurement",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                errorString = '';
                $('#add_tyre_aspect_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled', false);
                var parseJson = jQuery.parseJSON(data);
                if (parseJson.status == 200) {
                    $(".close").click();
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                    setTimeout(function() { location.reload() }, 1000);
                }
                if (parseJson.status == 400) {
                    $.each(parseJson.error, function(key, value) {
                        errorString += '<div class="notice notice-danger"><strong>Wrong , </strong>' + value + ' .</div>';
                    });
                    $('#response').html(errorString);
                }
                if (parseJson.status == 100) {
                    $("#response").html(parseJson.msg);
                }
            },
            error: function(xhr, error) {

            }
        });
    });
    /*End */

    $(document).on('click', '.edit_aspect_ratio_measure', function(e) {
        e.preventDefault();
        var measure_id = $(this).data('measureid');
        var measure_type = $(this).data('measuretype');
        if (measure_id != "") {
            $.ajax({
                url: base_url + "/tyre_ajax/get_tyre_measure_details",
                method: "GET",
                data: { measure_id: measure_id, measure_type: measure_type },
                success: function(data) {
                    var parseJson = jQuery.parseJSON(data);
                    if (parseJson.status == 200) {
                        $("#aspect_ratio_id").val(parseJson.response.id);
                        $("#aspect__value").val(parseJson.response.value);
                        $('#edit_aspect_measure_popup').modal({
                            backdrop: 'static',
                            keyboard: false,
                        });
                    }
                }

            });
        }
    });

    /*Add Aspect Ratio measurement */
    $(document).on('submit', '#edit_new_aspect_measurement', function(e) {
        $('#response').html(" ");
        $("err_response").html(" ");
        $('#edit_tyre_aspect_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
        e.preventDefault();
        $.ajax({
            url: base_url + "/tyre_ajax/edit_aspect_type_measurement",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                errorString = '';
                $('#edit_tyre_aspect_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled', false);
                var parseJson = jQuery.parseJSON(data);
                if (parseJson.status == 200) {
                    $(".close").click();
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                    setTimeout(function() { location.reload() }, 1000);
                }
                if (parseJson.status == 400) {
                    $.each(parseJson.error, function(key, value) {
                        errorString += '<div class="notice notice-danger"><strong>Wrong , </strong>' + value + ' .</div>';
                    });
                    $('#response').html(errorString);
                }
                if (parseJson.status == 100) {
                    $("#response").html(parseJson.msg);
                }
            },
            error: function(xhr, error) {

            }
        });
    });
    /*End */

    $(document).on('click', '#speed_index_btn', function(e) {
        e.preventDefault();
        $('#speed_index_measure_popup').modal({
            backdrop: 'static',
            keyboard: false,
        });
    });

    /*Add Aspect Ratio measurement */
    $(document).on('submit', '#add_new_speed_index_measurement', function(e) {
        $('#response').html(" ");
        $("err_response").html(" ");
        $('#add_tyre_speed_index_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
        e.preventDefault();
        $.ajax({
            url: base_url + "/tyre_ajax/add_speed_index_measurement",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                errorString = '';
                $('#add_tyre_speed_index_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled', false);
                var parseJson = jQuery.parseJSON(data);
                if (parseJson.status == 200) {
                    $(".close").click();
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                    setTimeout(function() { location.reload() }, 1000);
                }
                if (parseJson.status == 400) {
                    $.each(parseJson.error, function(key, value) {
                        errorString += '<div class="notice notice-danger"><strong>Wrong , </strong>' + value + ' .</div>';
                    });
                    $('#response').html(errorString);
                }
                if (parseJson.status == 100) {
                    $("#response").html(parseJson.msg);
                }
            },
            error: function(xhr, error) {

            }
        });
    });
    /*End */

    $(document).on('click', '.edit_speed_index_measure', function(e) {
        e.preventDefault();
        var measure_id = $(this).data('measureid');
        var measure_type = $(this).data('measuretype');
        if (measure_id != "") {
            $.ajax({
                url: base_url + "/tyre_ajax/get_tyre_measure_details",
                method: "GET",
                data: { measure_id: measure_id, measure_type: measure_type },
                success: function(data) {
                    var parseJson = jQuery.parseJSON(data);
                    if (parseJson.status == 200) {
                        $("#tyre_speed_index_id").val(parseJson.response.id);
                        $("#tyre_speed_index_value").val(parseJson.response.name);
                        $('#edit_speed_index_measure_popup').modal({
                            backdrop: 'static',
                            keyboard: false,
                        });
                    }
                }

            });
        }
    });

    $(document).on('submit', '#edit_speed_index_measurement_form', function(e) {
        $('#response').html(" ");
        $("err_response").html(" ");
        $('#edit_tyre_speed_index_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
        e.preventDefault();
        $.ajax({
            url: base_url + "/tyre_ajax/edit_speed_index_measurement",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                errorString = '';
                $('#edit_tyre_speed_index_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled', false);
                var parseJson = jQuery.parseJSON(data);
                if (parseJson.status == 200) {
                    $(".close").click();
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                    setTimeout(function() { location.reload() }, 1000);
                }
                if (parseJson.status == 400) {
                    $.each(parseJson.error, function(key, value) {
                        errorString += '<div class="notice notice-danger"><strong>Wrong , </strong>' + value + ' .</div>';
                    });
                    $('#response').html(errorString);
                }
                if (parseJson.status == 100) {
                    $("#response").html(parseJson.msg);
                }
            },
            error: function(xhr, error) {

            }
        });
    });

    $(document).on('click', '#add_diameter_btn', function(e) {
        e.preventDefault();
        $('#tyre_diameter_measure_popup').modal({
            backdrop: 'static',
            keyboard: false,
        });
    });

    /*Add Diameter measurement */
    $(document).on('submit', '#add_new_diameter_index_measurement', function(e) {
        $('#response').html(" ");
        $("err_response").html(" ");
        $('#add_diameter_index_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
        e.preventDefault();
        $.ajax({
            url: base_url + "/tyre_ajax/add_diameter_measurement",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                errorString = '';
                $('#add_diameter_index_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled', false);
                var parseJson = jQuery.parseJSON(data);
                if (parseJson.status == 200) {
                    $(".close").click();
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                    setTimeout(function() { location.reload() }, 1000);
                }
                if (parseJson.status == 400) {
                    $.each(parseJson.error, function(key, value) {
                        errorString += '<div class="notice notice-danger"><strong>Wrong , </strong>' + value + ' .</div>';
                    });
                    $('#response').html(errorString);
                }
                if (parseJson.status == 100) {
                    $("#response").html(parseJson.msg);
                }
            },
            error: function(xhr, error) {

            }
        });
    });
    /*End */

    $(document).on('click', '.edit_diameter_index_measure', function(e) {
        e.preventDefault();
        var measure_id = $(this).data('measureid');
        var measure_type = $(this).data('measuretype');
        if (measure_id != "") {
            $.ajax({
                url: base_url + "/tyre_ajax/get_tyre_measure_details",
                method: "GET",
                data: { measure_id: measure_id, measure_type: measure_type },
                success: function(data) {
                    var parseJson = jQuery.parseJSON(data);
                    if (parseJson.status == 200) {
                        $("#speed_index_id").val(parseJson.response.id);
                        $("#tyre_diametere_value").val(parseJson.response.value);
                        $('#edit_tyre_diameter_measure_popup').modal({
                            backdrop: 'static',
                            keyboard: false,
                        });
                    }
                }

            });
        }
    });
    $(document).on('submit', '#edit_new_diameter_index_measurement', function(e) {
        $('#response').html(" ");
        $("err_response").html(" ");
        $('#edit_tyre_diameter_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
        e.preventDefault();
        $.ajax({
            url: base_url + "/tyre_ajax/edit_diameter_measurement",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                errorString = '';
                $('#edit_tyre_diameter_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled', false);
                var parseJson = jQuery.parseJSON(data);
                if (parseJson.status == 200) {
                    $(".close").click();
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                    setTimeout(function() { location.reload() }, 1000);
                }
                if (parseJson.status == 400) {
                    $.each(parseJson.error, function(key, value) {
                        errorString += '<div class="notice notice-danger"><strong>Wrong , </strong>' + value + ' .</div>';
                    });
                    $('#response').html(errorString);
                }
                if (parseJson.status == 100) {
                    $("#response").html(parseJson.msg);
                }
            },
            error: function(xhr, error) {

            }
        });
    });

    $(document).on('click', '#add_width_btn', function(e) {
        e.preventDefault();
        $('#tyre_width_measure_popup').modal({
            backdrop: 'static',
            keyboard: false,
        });
    });

    /*Add Diameter measurement */
    $(document).on('submit', '#add_new_width_index_measurement', function(e) {
        $('#response').html(" ");
        $("err_response").html(" ");
        $('#add_width_index_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
        e.preventDefault();
        $.ajax({
            url: base_url + "/tyre_ajax/add_width_measurement",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                errorString = '';
                $('#add_width_index_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled', false);
                var parseJson = jQuery.parseJSON(data);
                if (parseJson.status == 200) {
                    $(".close").click();
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                    setTimeout(function() { location.reload() }, 1000);
                }
                if (parseJson.status == 400) {
                    $.each(parseJson.error, function(key, value) {
                        errorString += '<div class="notice notice-danger"><strong>Wrong , </strong>' + value + ' .</div>';
                    });
                    $('#response').html(errorString);
                }
                if (parseJson.status == 100) {
                    $("#response").html(parseJson.msg);
                }
            },
            error: function(xhr, error) {

            }
        });
    });
    /*End */

    $(document).on('click', '.edit_width_index_measure', function(e) {
        e.preventDefault();
        var measure_id = $(this).data('measureid');
        var measure_type = $(this).data('measuretype');
        if (measure_id != "") {
            $.ajax({
                url: base_url + "/tyre_ajax/get_tyre_measure_details",
                method: "GET",
                data: { measure_id: measure_id, measure_type: measure_type },
                success: function(data) {
                    var parseJson = jQuery.parseJSON(data);
                    if (parseJson.status == 200) {
                        $("#tyre_width_id").val(parseJson.response.id);
                        $("#tyre_width_value").val(parseJson.response.value);
                        $('#edit_tyre_width_measure_popup').modal({
                            backdrop: 'static',
                            keyboard: false,
                        });
                    }
                }

            });
        }
    });

    $(document).on('submit', '#edit_new_width_index_measurement', function(e) {
        $('#response').html(" ");
        $("err_response").html(" ");
        $('#edit_width_diameter_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
        e.preventDefault();
        $.ajax({
            url: base_url + "/tyre_ajax/edit_width_measurement",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                errorString = '';
                $('#edit_width_diameter_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled', false);
                var parseJson = jQuery.parseJSON(data);
                if (parseJson.status == 200) {
                    $(".close").click();
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                    setTimeout(function() { location.reload() }, 1000);
                }
                if (parseJson.status == 400) {
                    $.each(parseJson.error, function(key, value) {
                        errorString += '<div class="notice notice-danger"><strong>Wrong , </strong>' + value + ' .</div>';
                    });
                    $('#response').html(errorString);
                }
                if (parseJson.status == 100) {
                    $("#response").html(parseJson.msg);
                }
            },
            error: function(xhr, error) {

            }
        });
    });
});