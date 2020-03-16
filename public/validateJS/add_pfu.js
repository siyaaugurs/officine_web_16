$(document).ready(function(e) {
    /*Set As Default */
    $(document).on('change', ".tyre_pfu", function(e) {
        var pfu_id = $(this).data('pfu_id');
        if ($(this).prop('checked')) {
            var status = 1;
        } else {
            var status = 2;
        }
        $.ajax({
            url: base_url + "/tyre_ajax/set_default_tyre_pfu",
            method: "GET",
            data: { pfu_id: pfu_id, status: status },
            success: function(data) {
                console.log(data);
                var parseJson = jQuery.parseJSON(data);
                if (parseJson.status == 200) {
                    $('#success_message').fadeIn().html(parseJson.msg);
                    setTimeout(function() {
                        $('#success_message').fadeOut("slow");
                    }, 2000);
                } else {
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                }
            }

        });
    });
    /*End */

    /*Add pfu Script Start */
    $(document).on('click', '#add_pfu', function(e) {
        e.preventDefault();
        $("#myModalLabel").html('Add pfu');
        $("#add_pfu_form")[0].reset();
        $('#add_new_pfu').modal({
            backdrop: 'static',
            keyboard: false,
        });
    });
    /*End */

    /*Add Service pfu Script Start */
    $(document).on('submit', '#add_pfu_form', function(e) {
        $('#msg_response').html(" ");
        $("err_response").html(" ");
        $('#pfu_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
        e.preventDefault();
        $.ajax({
            url: base_url + "/tyre_ajax/add_pfu",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                errorString = '';
                $('#pfu_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled', false);
                var parseJson = jQuery.parseJSON(data);
                if (parseJson.status == 200) {
                    $("#add_pfu_form")[0].reset();
                    $("#add_new_pfu").modal('hide');
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                    setTimeout(function() { location.reload(); }, 1000);
                }
                if (parseJson.status == 400) {
                    $.each(parseJson.error, function(key, value) {
                        errorString += '<div class="notice notice-danger"><strong>Wrong , </strong>' + value + ' .</div>';
                    });
                    $('#err_response').html(errorString);
                }
                if (parseJson.status == 100) {
                    $("#add_pfu_form")[0].reset();
                    $("#add_new_pfu").modal('hide');
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                }
            },
            error: function(xhr, error) {
                $('#pfu_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled', false);
                $("#response_msg").html(parseJson.msg);
            }
        });
    });

    /*End */

    /*Change Spare pfu Status */
    $(document).on('click', '.change_pfu_status', function(e) {
        $("#pfu_id").val("");
        $("#pfu_name").val("");
        $("#description").val("");
        $("#pfu_priority").val("");
        $("#service_time").val("");
        e.preventDefault();
        var $this = $(this);
        var pfu_id = $(this).data('pfu_id');
        var status = $(this).data('status');
        var con = confirm("Are you sure want to Change Status ?");
        if (con == true) {
            $.ajax({
                url: base_url + "/tyre_ajax/change_pfu_status",
                type: "GET",
                data: { status: status, pfu_id: pfu_id },
                success: function(data) {
                    if (status == '0') {
                        $this.html(" <i class='fa fa-toggle-on'></i>").data('status', "1");
                    }
                    if (status == '1') {
                        $this.html(" <i class='fa fa-toggle-off'></i>").data('status', '0');
                    }
                }
            });
        } else {
            return false;
        }
    });
    /*End */

    /*Edit Service pfu */
    $(document).on('click', '.edit_pfu', function(e) {
        e.preventDefault();
        $('#pfu_id').val(" ");
        var $this = $(this);
        var pfu_id = $(this).data('pfu_id');
        $.ajax({
            url: base_url + "/tyre_ajax/get_pfu_details",
            method: "GET",
            data: { pfu_id: pfu_id },
            success: function(data) {
                var parseJson = jQuery.parseJSON(data);
                if (parseJson.status == 200) {
                    $("#pfu_id").val(parseJson.response.id);
                    $("#tyre_type").val(parseJson.response.tyre_type);
                    $("#tyre_type_description").val(parseJson.response.tyre_type_description);
                    $("#tyre_type_description_for_seller").val(parseJson.response.tyre_type_description_for_seller);
                    $("#tyre_type_description_for_customer").val(parseJson.response.tyre_type_description_for_customer);
                    $("#category").val(parseJson.response.category);
                    $("#category_description").val(parseJson.response.category_description);
                    $("#admin_price").val(parseJson.response.admin_price);
                    $("#user_pfu").val(parseJson.response.user_pfu);
                    $("#description").val(parseJson.response.tyre_type_description);
                    $("#tyre_class").val(parseJson.response.tyre_class);
                    $("#vehicles").val(parseJson.response.vehicles);
                    $("#weights_of_tyres_to").val(parseJson.response.weights_of_tyres_to);
                    $("#weights_of_tyres_from").val(parseJson.response.weights_of_tyres_from);
                    $("#myModalLabel").html('Edit pfu');
                    $('#add_new_pfu').modal({
                        backdrop: 'static',
                        keyboard: false,
                    });
                }
            }
        });
    });
    /*End */
    /*Delete tyre pfu */
    $(document).on('click', '.delete_pfu', function(e) {
            e.preventDefault();
            $('#pfu_id').val(" ");
            var $this = $(this);
            var pfu_id = $(this).data('pfu_id');
            var con = confirm("Are you sure want to remove this pfu ?");
            if (con == true) {
                $.ajax({
                    url: base_url + "/tyre_ajax/delete_pfu",
                    method: "GET",
                    data: { pfu_id: pfu_id },
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
        })
        /*End */
        //Show modal on edit button click of workshop tyre24
    $(document).on('click', '.edit_workshop_tyre24_pfu_services', function(e) {
        e.preventDefault();
        var pfu_id = $(this).data('id');
        var hourly_rate = $(this).data('hourly_rate');
        var delivery_days = $(this).data('delivery_days');
        var pfu = $(this).data('pfu');
        var max_appointment = $(this).data('max_appointment');
        $(".card-body #pfu_id").val(pfu_id);
        $(".card-body #workshop_tyre24_hourly_rate").val(hourly_rate);
        $(".card-body #workshop_tyre24_max_appointment").val(max_appointment);
        $(".card-body #workshop_tyre24_delivery_days").val(delivery_days);
        $(".card-body #workshop_tyre24_PFU").val(pfu);

        $("#edit_workshop_tyre24_services").modal('show');
    });
    /*Submit Workshop Tyre24 pfu price add/edit form */
    $(document).on('submit', '#edit_workshop_tyre24_pfu_form', function(e) {
            e.preventDefault();
            $('#msg_response').html(" ");
            $("err_response").html(" ");
            $('#edit_workshop_tyre24_service_price_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
            $.ajax({
                url: base_url + "/vendor/edit_workshop_tyre24_pfu_details",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    errorString = '';
                    $('#edit_workshop_tyre24_service_price_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled', false);
                    var parseJson = jQuery.parseJSON(data);
                    if (parseJson.status == 200) {
                        $("#edit_workshop_tyre24_pfu_form")[0].reset();
                        $("#edit_workshop_tyre24_services").modal('hide');
                        $("#msg_response_popup").modal('show');
                        $("#msg_response").html(parseJson.msg);
                        setTimeout(function() { location.reload(); }, 1000);
                    }
                    if (parseJson.status == 400) {
                        $.each(parseJson.error, function(key, value) {
                            errorString += '<div class="notice notice-danger"><strong>Wrong , </strong>' + value + ' .</div>';
                        });
                        $('#err_response').html(errorString);
                    }
                    if (parseJson.status == 100) {
                        $("#add_service_pfu_form")[0].reset();
                        $("#add_new_service_pfu").modal('hide');
                        $("#msg_response_popup").modal('show');
                        $("#msg_response").html(parseJson.msg);
                    }
                },
                error: function(xhr, error) {
                    $('#edit_workshop_tyre24_service_price_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled', false);
                    $("#response_msg").html(parseJson.msg);
                }
            });
        })
        /*End */
        /*Add Workshop Tyre24 pfu price add form*/
    $(document).on('click', '#workshop_tyre24_pfu_details', function(e) {
        e.preventDefault();
        $("#add_workshop_tyre24_pfu_details_popup").modal('show');
    });
    $(document).on('click', '#add_workshop_tyre24_pfu_details_btn', function(e) {
        var btn_html = $("#add_workshop_tyre24_pfu_details_btn").html();
        e.preventDefault();
        max_appointment = $("#max_appointment").val();
        hourly_rate = $("#hourly_rate").val();
        $.ajax({
            url: base_url + "/tyre_ajax/workshop_tyre24_pfu_details",
            type: "GET",
            data: { max_appointment: max_appointment, hourly_rate: hourly_rate },
            complete: function(e, xhr, setting) {
                $('#add_services_btn_copy').html(btn_html).attr('disabled', false);
                if (e.status == 200) {
                    var parseJson = jQuery.parseJSON(e.responseText);
                    if (parseJson.status == 200) {
                        $("#add_services_form")[0].reset();
                        $('.close').click();
                        $("#msg_response_popup").modal('show');
                        $("#msg_response").html(parseJson.msg);
                        setTimeout(function() { location.reload(); }, 1000);
                    }
                    if (parseJson.status == 100) {
                        $("#response_err").html(parseJson.msg);
                    }
                }
            },
            error: function(xhr, error) {
                $('#add_services_btn_copy').html(btn_html).attr('disabled', false);
                $("#response_err").html('<div class="notice notice-danger"><strong>Wrong </strong>Something Wrong , please try again . !!! </div>');
            }
        });
    });
    /*End */
    $(document).on('click', '.delete_admin_pfu', function(e) {
        e.preventDefault();
        $('#pfu_id').val(" ");
        var $this = $(this);
        var pfu_id = $(this).data('pfuid');
        var con = confirm("Are you sure want to remove this PFU ?");
        if (con == true) {
            $.ajax({
                url: base_url + "/tyre_ajax/delete_admin_pfu",
                method: "GET",
                data: { pfu_id: pfu_id },
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
});