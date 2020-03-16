$(document).ready(function(e) {
    $(document).on('click', '.delete_special_condition', function(e) {
        e.preventDefault();
        delete_special_condition = $(this);
        con = confirm("Are you sure want to remove this special condition ");
        if (con == true) {
            window.location.href = delete_special_condition.attr('href');
        }
    });
    $(document).on('change', '#expiry_date', function(e) {
        var start_date = $('#start_date').val();
        var end_date = $('#expiry_date').val();
        if (new Date(start_date) > new Date(end_date)) {
            alert('Expiry Date is should be greater than Start Date !!!');
            $('#expiry_date').val("");
        }
    });

    /*Edit special  condition script start*/
    $(document).on('submit', '#edit_special_condition_form_tyre', function(e) {
        $('#response').html(" ");
        $("err_response").html(" ");
        $('#special_condition_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
        e.preventDefault();
        $.ajax({
            url: base_url + "/spacial_condition_ajax/edit_tyre_special_condition",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                errorString = '';
                $('#special_condition_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled', false);
                var parseJson = jQuery.parseJSON(data);
                if (parseJson.status == 200) {
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                    setTimeout(function() { location.reload(); }, 1000);
                }
                if (parseJson.status == 400) {
                    $.each(parseJson.error, function(key, value) {
                        errorString += '<div class="notice notice-danger"><strong>Note , </strong>' + value + ' .</div>';
                    });
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(errorString);
                }
                if (parseJson.status == 100) {
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                }
            }
        });
    });
    /*end*/
    /*Save Special Condition New*/
    $(document).on('submit', '#special_condition_form_tyre', function(e) {
        $('#response').html(" ");
        $("err_response").html(" ");
        $('#special_condition_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
        e.preventDefault();
        $.ajax({
            url: base_url + "/spacial_condition_ajax/save_tyre_special_condition",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                errorString = '';
                $('#special_condition_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled', false);
                var parseJson = jQuery.parseJSON(data);
                if (parseJson.status == 200) {
                    $(".close").click();
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                    setTimeout(function() { location.reload(); }, 1000);
                }
                if (parseJson.status == 400) {
                    $.each(parseJson.error, function(key, value) {
                        errorString += '<div class="notice notice-danger"><strong>Note , </strong>' + value + ' .</div>';
                    });
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(errorString);
                }
                if (parseJson.status == 100) {
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                }
            }
        });
    });
    /*End*/
    /*Add Special Conditions Servies */
    $(document).on('submit', '#assemble_special_condition_form', function(e) {
        $('#response').html(" ");
        $("err_response").html(" ");
        $('#assemble_special_condition_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
        e.preventDefault();

        $.ajax({

            url: base_url + "/spacial_condition_ajax/add_assemble_special_conditions",

            type: "POST",

            data: new FormData(this),

            contentType: false,

            cache: false,

            processData: false,

            success: function(data) {

                errorString = '';

                $('#assemble_special_condition_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled', false);

                var parseJson = jQuery.parseJSON(data);

                if (parseJson.status == 200) {

                    $(".close").click();

                    $("#msg_response_popup").modal('show');

                    $("#msg_response").html(parseJson.msg);

                    setTimeout(function() { location.reload(); }, 1000);

                }

                if (parseJson.status == 400) {

                    $.each(parseJson.error, function(key, value) {

                        errorString += '<div class="notice notice-danger"><strong>Note , </strong>' + value + ' .</div>';

                    });

                    $("#msg_response_popup").modal('show');

                    $("#msg_response").html(errorString);

                }

                if (parseJson.status == 100) {

                    $("#msg_response_popup").modal('show');

                    $("#msg_response").html(parseJson.msg);

                }

            }

        });

    });

    $(document).on('submit', '#special_condition_form', function(e) {

        $('#response').html(" ");

        $("err_response").html(" ");

        $('#special_condition_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);

        e.preventDefault();

        $.ajax({

            url: base_url + "/spacial_condition_ajax/add_special_conditions",

            type: "POST",

            data: new FormData(this),

            contentType: false,

            cache: false,

            processData: false,

            success: function(data) {

                errorString = '';

                $('#special_condition_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled', false);

                var parseJson = jQuery.parseJSON(data);

                if (parseJson.status == 200) {

                    $(".close").click();

                    $("#msg_response_popup").modal('show');

                    $("#msg_response").html(parseJson.msg);

                    setTimeout(function() { location.reload(); }, 1000);

                }

                if (parseJson.status == 400) {

                    $.each(parseJson.error, function(key, value) {

                        errorString += '<div class="notice notice-danger"><strong>Note , </strong>' + value + ' .</div>';

                    });

                    $("#msg_response_popup").modal('show');

                    $("#msg_response").html(errorString);

                }

                if (parseJson.status == 100) {

                    $("#msg_response_popup").modal('show');

                    $("#msg_response").html(parseJson.msg);

                }

            }

        });

    });

    /*End */



    /*Checkdays modal open popup*/

    $(document).on('click', '.check_days', function(e) {

        e.preventDefault();

        var service_id = $(this).data('id');

        if (service_id != "") {

            $.ajax({

                url: base_url + "/spacial_condition_ajax/get_selected_days",

                method: "GET",

                data: { service_id: service_id },

                success: function(data) {

                    $('#days_result').html(data);

                    $('#view_selected_service_days').modal('show');

                }

            });

        }

    });

    /*End*/



    /*Checkdays modal open popup*/

    $(document).on('click', '.delete_selected_days', function(e) {

        e.preventDefault();

        var days_id = $(this).data('dayid');

        var con = confirm("Are you sure want to Change Status ?");

        if (con == true) {

            $.ajax({

                url: base_url + "/spacial_condition_ajax/delete_days",

                method: "GET",

                data: { days_id: days_id },

                success: function(data) {

                    $('#view_selected_service_days').modal('hide');

                }

            });

        } else {

            return false;

        }

    });

    /*End*/

    /*Add Special Conditions Servies */

    $(document).on('submit', '#special_revision_condition_form', function(e) {

        $('#response').html(" ");

        $("err_response").html(" ");

        $('#revision_special_condition_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);

        e.preventDefault();

        $.ajax({

            url: base_url + "/spacial_condition_ajax/add_revision_special_conditions",

            type: "POST",

            data: new FormData(this),

            contentType: false,

            cache: false,

            processData: false,

            success: function(data) {

                errorString = '';

                $('#revision_special_condition_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled', false);

                var parseJson = jQuery.parseJSON(data);

                if (parseJson.status == 200) {

                    $(".close").click();

                    $("#msg_response_popup").modal('show');

                    $("#msg_response").html(parseJson.msg);

                    $("#special_revision_condition_form")[0].reset();

                    setTimeout(function() { location.reload(); }, 1000);

                }

                if (parseJson.status == 400) {

                    $.each(parseJson.error, function(key, value) {

                        errorString += '<div class="notice notice-danger"><strong>Note , </strong>' + value + ' .</div>';

                    });

                    $("#msg_response_popup").modal('show');

                    $("#msg_response").html(errorString);

                }

                if (parseJson.status == 100) {

                    $("#msg_response_popup").modal('show');

                    $("#msg_response").html(parseJson.msg);

                }

            }

        });

    });

    /*End */

    /*Checkcars modal open popup*/

    $(document).on('click', '.check_cars', function(e) {

        e.preventDefault();

        var service_id = $(this).data('id');

        if (service_id != "") {

            $.ajax({

                url: base_url + "/spacial_condition_ajax/get_selected_cars",

                method: "GET",

                data: { service_id: service_id },

                success: function(data) {

                    $('#cars_result').html(data);

                    $('#view_selected_service_cars').modal('show');

                }

            });

        }

    });

    /*End*/

    /*Checkdays modal open popup*/

    $(document).on('click', '.delete_selected_cars', function(e) {

        e.preventDefault();

        var cars_id = $(this).data('carid');

        var con = confirm("Are you sure want to Delete ?");

        if (con == true) {

            $.ajax({

                url: base_url + "/spacial_condition_ajax/delete_cars",

                method: "GET",

                data: { cars_id: cars_id },

                success: function(data) {

                    var parseJson = jQuery.parseJSON(data);

                    $('#view_selected_service_cars').modal('hide');

                    $("#msg_response_popup").modal('show');

                    $("#msg_response").html(parseJson.msg);

                }

            });

        } else {

            return false;

        }

    });

    /*End*/

    /*MAintenance Special Condition Script Start */

    $(document).on('submit', '#special_maintenance_condition_form', function(e) {

        $('#response').html(" ");

        $("err_response").html(" ");

        $('#maintenance_special_condition_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);

        e.preventDefault();

        $.ajax({

            url: base_url + "/spacial_condition_ajax/add_maintenance_special_conditions",

            type: "POST",

            data: new FormData(this),

            contentType: false,

            cache: false,

            processData: false,

            success: function(data) {

                errorString = '';

                $('#maintenance_special_condition_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled', false);

                var parseJson = jQuery.parseJSON(data);

                if (parseJson.status == 200) {

                    $(".close").click();

                    $("#msg_response_popup").modal('show');

                    $("#msg_response").html(parseJson.msg);

                    setTimeout(function() { location.reload(); }, 1000);

                }

                if (parseJson.status == 400) {

                    $.each(parseJson.error, function(key, value) {

                        errorString += '<div class="notice notice-danger"><strong>Note , </strong>' + value + ' .</div>';

                    });

                    $("#msg_response_popup").modal('show');

                    $("#msg_response").html(errorString);

                }

                if (parseJson.status == 100) {

                    $("#msg_response_popup").modal('show');

                    $("#msg_response").html(parseJson.msg);

                }

            }

        });

    });

    /*End */

    /*Get Services on Change */

    $(document).on('change', '#service_type', function(e) {

        $("#preloader").show();

        var service_type = $('#service_type').val();

        if (service_type != "") {

            $.ajax({

                url: base_url + "/spacial_condition_ajax/get_services",

                method: "GET",

                data: { service_type: service_type },

                success: function(data) {

                    $("#preloader").hide();

                    var parseJson = jQuery.parseJSON(data);

                    var html_content = '';

                    if (parseJson.status == 200) {

                        html_content += '<option value="0">All Services</option>';

                        var service_name = '';

                        $.each(parseJson.response, function(index, value) {

                            html_content += '<option value="' + value.id + '">' + value.services_name + '</option>';

                        });

                        $("#service_name").html(html_content);

                    }

                    if (parseJson.status == 400) {

                        html_content = '<option value="0">No Services Available </option>';

                        $("#service_name").html(html_content);

                    }

                },

                error: function(xhr, error) {

                    $("#preloader").hide();

                }

            });

        }

    });

    /*End */

    /*Get Services on Change */

    $(document).on('change', '#service_name', function(e) {

        var service_name = $('#service_name').val();

        if (service_name != "") {

            $.ajax({

                url: base_url + "/spacial_condition_ajax/get_time_arrives",

                method: "GET",

                data: { service_name: service_name },

                success: function(data) {

                    var parseJson = jQuery.parseJSON(data);

                    if (parseJson.status == 200) {

                        $("#time_arrives").val(parseJson.response.time_arrives_15_minutes);

                    }

                    if (parseJson.status == 400) {

                        html_content += '<option value="0">No Services Available </option>';

                    }

                }

            });

        }

    });

    /*End */

    /*Add Wrecker Special Conditions Servies */
    $(document).on('submit', '#special_wrecker_condition_form', function(e) {
        $('#response').html(" ");
        $("err_response").html(" ");
        $('#wrecker_special_condition_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
        e.preventDefault();
        $.ajax({
            url: base_url + "/spacial_condition_ajax/add_wrecker_special_conditions",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                errorString = '';
                $('#wrecker_special_condition_btn').html('Submit <i class="icon-paperplane ml-2"></i>').attr('disabled', false);
                var parseJson = jQuery.parseJSON(data);
                if (parseJson.status == 200) {
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                    setTimeout(function() { location.reload(); }, 1000);
                }
                if (parseJson.status == 400) {
                    $.each(parseJson.error, function(key, value) {
                        errorString += '<div class="notice notice-danger"><strong>Note , </strong>' + value + ' .</div>';
                    });
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(errorString);
                }
                if (parseJson.status == 100) {
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                }
            }

        });

    });
    /*End */

    $(document).on('submit', '#edit_special_condition_form', function(e) {

        e.preventDefault();

        $('#response').html(" ");

        $("err_response").html(" ");

        $('#edit_special_condition_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);

        e.preventDefault();

        $.ajax({

            url: base_url + "/spacial_condition_ajax/edit_special_condition",

            type: "POST",

            data: new FormData(this),

            contentType: false,

            cache: false,

            processData: false,

            success: function(data) {

                errorString = '';

                $('#edit_special_condition_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled', false);

                var parseJson = jQuery.parseJSON(data);

                if (parseJson.status == 200) {

                    $(".close").click();

                    $("#msg_response_popup").modal('show');

                    $("#msg_response").html(parseJson.msg);

                    var url = base_url + "/spacial_condition/washing";

                    setTimeout(function() { window.location.href = url; }, 1000);

                }

                if (parseJson.status == 400) {

                    $.each(parseJson.error, function(key, value) {

                        errorString += '<div class="notice notice-danger"><strong>Note , </strong>' + value + ' .</div>';

                    });

                    $("#msg_response_popup").modal('show');

                    $("#msg_response").html(errorString);

                }

                if (parseJson.status == 100) {

                    $("#response").html(parseJson.msg);

                }

            }

        });

    });

    $(document).on('submit', '#edit_revision_special_condition_form', function(e) {

        e.preventDefault();

        $('#response').html(" ");

        $("err_response").html(" ");

        $('#revision_special_condition_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);

        e.preventDefault();

        $.ajax({

            url: base_url + "/spacial_condition_ajax/edit_revision_special_condition",

            type: "POST",

            data: new FormData(this),

            contentType: false,

            cache: false,

            processData: false,

            success: function(data) {

                errorString = '';

                $('#revision_special_condition_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled', false);

                var parseJson = jQuery.parseJSON(data);

                if (parseJson.status == 200) {

                    $(".close").click();

                    $("#msg_response_popup").modal('show');

                    $("#msg_response").html(parseJson.msg);

                    var url = base_url + "/spacial_condition/revision";

                    setTimeout(function() { window.location.href = url; }, 1000);

                }

                if (parseJson.status == 400) {

                    $.each(parseJson.error, function(key, value) {

                        errorString += '<div class="notice notice-danger"><strong>Note , </strong>' + value + ' .</div>';

                    });

                    $("#msg_response_popup").modal('show');

                    $("#msg_response").html(errorString);

                }

                if (parseJson.status == 100) {

                    $("#response").html(parseJson.msg);

                }

            }

        });

    });

    $(document).on('submit', '#edit_maintenance_special_condition_form', function(e) {

        e.preventDefault();

        $('#response').html(" ");

        $("err_response").html(" ");

        $('#edit_maintenance_special_condition_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);

        e.preventDefault();

        $.ajax({

            url: base_url + "/spacial_condition_ajax/edit_maintenance_special_condition",

            type: "POST",

            data: new FormData(this),

            contentType: false,

            cache: false,

            processData: false,

            success: function(data) {

                errorString = '';

                $('#edit_maintenance_special_condition_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled', false);

                var parseJson = jQuery.parseJSON(data);

                if (parseJson.status == 200) {

                    $(".close").click();

                    $("#msg_response_popup").modal('show');

                    $("#msg_response").html(parseJson.msg);

                    var url = base_url + "/spacial_condition/car_maintenance";

                    setTimeout(function() { window.location.href = url; }, 1000);

                }

                if (parseJson.status == 400) {

                    $.each(parseJson.error, function(key, value) {

                        errorString += '<div class="notice notice-danger"><strong>Note , </strong>' + value + ' .</div>';

                    });

                    $("#msg_response_popup").modal('show');

                    $("#msg_response").html(errorString);

                }

                if (parseJson.status == 100) {

                    $("#response").html(parseJson.msg);

                }

            }

        });

    });



    $(document).on('submit', '#edit_special_wrecker_condition_form', function(e) {

        e.preventDefault();

        $('#response').html(" ");

        $("err_response").html(" ");

        $('#edit_wrecker_special_condition_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);

        e.preventDefault();

        $.ajax({

            url: base_url + "/spacial_condition_ajax/edit_wrecker_special_condition",

            type: "POST",

            data: new FormData(this),

            contentType: false,

            cache: false,

            processData: false,

            success: function(data) {

                errorString = '';

                $('#edit_wrecker_special_condition_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled', false);

                var parseJson = jQuery.parseJSON(data);

                if (parseJson.status == 200) {

                    $(".close").click();

                    $("#msg_response_popup").modal('show');

                    $("#msg_response").html(parseJson.msg);

                    var url = base_url + "/spacial_condition/wrecker_services";

                    setTimeout(function() { window.location.href = url; }, 1000);

                }

                if (parseJson.status == 400) {

                    $.each(parseJson.error, function(key, value) {

                        errorString += '<div class="notice notice-danger"><strong>Note , </strong>' + value + ' .</div>';

                    });

                    $("#msg_response_popup").modal('show');

                    $("#msg_response").html(errorString);

                }

                if (parseJson.status == 100) {

                    $("#response").html(parseJson.msg);

                }

            }

        });

    });

    $(document).on('submit', '#edit_assemble_special_condition_form', function(e) {

        $('#response').html(" ");

        $("err_response").html(" ");

        $('#edit_assemble_special_condition_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);

        e.preventDefault();

        $.ajax({

            url: base_url + "/spacial_condition_ajax/edit_assemble_special_conditions",

            type: "POST",

            data: new FormData(this),

            contentType: false,

            cache: false,

            processData: false,

            success: function(data) {

                errorString = '';

                $('#edit_assemble_special_condition_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled', false);

                var parseJson = jQuery.parseJSON(data);

                if (parseJson.status == 200) {

                    $(".close").click();

                    $("#msg_response_popup").modal('show');

                    $("#msg_response").html(parseJson.msg);

                    var url = base_url + "/spacial_condition/assemble_services";

                    setTimeout(function() { window.location.href = url; }, 1000);

                }

                if (parseJson.status == 400) {

                    $.each(parseJson.error, function(key, value) {

                        errorString += '<div class="notice notice-danger"><strong>Note , </strong>' + value + ' .</div>';

                    });

                    $("#msg_response_popup").modal('show');

                    $("#msg_response").html(errorString);

                }

                if (parseJson.status == 100) {

                    $("#msg_response_popup").modal('show');

                    $("#msg_response").html(parseJson.msg);

                }

            }

        });

    });
    $(document).on('submit', '#add_mot_special_condition_form', function(e) {
        $('#response').html(" ");
        $("err_response").html(" ");
        $('#add_mot_special_condition_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
        e.preventDefault();
        $.ajax({
            url: base_url + "/spacial_condition_ajax/add_mot_special_conditions",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                errorString = '';
                $('#add_mot_special_condition_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled', false);
                var parseJson = jQuery.parseJSON(data);
                if (parseJson.status == 200) {
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                    setTimeout(function() { location.reload(); }, 1000);
                }
                if (parseJson.status == 400) {
                    $.each(parseJson.error, function(key, value) {
                        errorString += '<div class="notice notice-danger"><strong>Note , </strong>' + value + ' .</div>';
                    });
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(errorString);
                }
                if (parseJson.status == 100) {
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                }
            }
        });
    });
    /*Add Request Quotes Special Conditions Servies */

    $(document).on('submit', '#special_request_Quotes_condition_form', function(e) {
        $('#response').html(" ");
        $("err_response").html(" ");
        $('#quotes_special_condition_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
        e.preventDefault();
        $.ajax({
            url: base_url + "/spacial_condition_ajax/add_request_quots_special_conditions",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                errorString = '';
                $('#quotes_special_condition_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled', false);
                var parseJson = jQuery.parseJSON(data);
                if (parseJson.status == 200) {
                    $(".close").click();
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                    $("#special_request_Quotes_condition_form")[0].reset();
                    setTimeout(function() { location.reload(); }, 1000);
                }
                if (parseJson.status == 400) {
                    $.each(parseJson.error, function(key, value) {
                        errorString += '<div class="notice notice-danger"><strong>Note , </strong>' + value + ' .</div>';
                    });
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(errorString);
                }
                if (parseJson.status == 100) {
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                }
            }
        });
    });
    /*End */

    /*Edit Request Quotes Special Condition */
    $(document).on('submit', '#edit_request_quotes_special_condition_form', function(e) {
        e.preventDefault();
        $('#response').html(" ");
        $("err_response").html(" ");
        $('#edit_request_special_condition_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
        e.preventDefault();
        $.ajax({
            url: base_url + "/spacial_condition_ajax/edit_quotes_special_condition",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                errorString = '';
                $('#edit_request_special_condition_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled', false);
                var parseJson = jQuery.parseJSON(data);
                if (parseJson.status == 200) {
                    $(".close").click();
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                    var url = base_url + "/spacial_condition/request_quots";
                    setTimeout(function() { window.location.href = url; }, 1000);
                }
                if (parseJson.status == 400) {
                    $.each(parseJson.error, function(key, value) {
                        errorString += '<div class="notice notice-danger"><strong>Note , </strong>' + value + ' .</div>';
                    });
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(errorString);
                }
                if (parseJson.status == 100) {
                    $("#response").html(parseJson.msg);
                }
            }
        });
    });
    /*End */

    /*Edit MOT Special Condition */
    $(document).on('submit', '#edit_mot_special_condition_form', function(e) {
        e.preventDefault();
        $('#response').html(" ");
        $("err_response").html(" ");
        $('#edit_mot_special_condition_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
        e.preventDefault();
        $.ajax({
            url: base_url + "/spacial_condition_ajax/edit_mot_special_condition",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                errorString = '';
                $('#edit_mot_special_condition_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled', false);
                var parseJson = jQuery.parseJSON(data);
                if (parseJson.status == 200) {
                    $(".close").click();
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                    var url = base_url + "/spacial_condition/mot_services";
                    setTimeout(function() { window.location.href = url; }, 1000);
                }
                if (parseJson.status == 400) {
                    $.each(parseJson.error, function(key, value) {
                        errorString += '<div class="notice notice-danger"><strong>Note , </strong>' + value + ' .</div>';
                    });
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(errorString);
                }
                if (parseJson.status == 100) {
                    $("#response").html(parseJson.msg);
                }
            }
        });
    });
    /*End */

});