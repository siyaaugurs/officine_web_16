$(document).ready(function(e) {

    /*Set As Default */
    $(document).on('change', ".spare_service_group", function(e) {
        var service_id = $(this).data('serviceid');
        if ($(this).prop('checked')) {
            var status = 1;
        } else {
            var status = 2;
        }
        $.ajax({
            url: base_url + "/spare_products/set_default_service_group",
            method: "GET",
            data: { service_id: service_id, status: status },
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

    /*Remove Spare Group Script Start*/

    $(document).on('click', '#de_selected_group', function(e) {

        e.preventDefault();

        records = [];

        $("#example tr").each(function() {
            if ($(this).find('.group_id').is(':checked')) {
                records.push({ group_id: $(this).find('.group_id').val() });

            }

        });
        console.log(records)
        $('#de_selected_group').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
        var con = confirm("Are you sure want to Remove ?");
        if (con == true) {
            $.ajax({

                url: base_url + "/spare_products/remove_group_list",

                method: "GET",

                data: { records: records },

                complete: function(e, xhr) {

                    $('#de_selected_group').html('Remove &nbsp;<span class="glyphicon glyphicon-trash"></span>').attr('disabled', false);

                    if (e.status == 200) {

                        var parseJson = jQuery.parseJSON(e.responseText);

                        if (parseJson.status == 200) {

                            $("#msg_response_popup").modal('show');

                            $("#msg_response").html(parseJson.msg);

                            setTimeout(function() { location.reload(); }, 1000);

                        } else {

                            $("#msg_response_popup").modal('show');

                            $("#msg_response").html(parseJson.msg);

                        }

                    }

                    /*if(e.status == 200){

                    	$("#list_spare_items").html(e.responseText);

                    }*/

                }

            });
        } else {
            $('#de_selected_group').html('Remove &nbsp;<span class="glyphicon glyphicon-trash"></span>').attr('disabled', false);
            return false;
        }

    });

    /*End*/

    /* Search Spare Item List*/

    $(document).on('click', '#search_spare_group_item', function(e) {

        $('#search_spare_group_item').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);

        e.preventDefault();

        var main_cat_item = $('#main_cat').val();

        var language = $('html').attr('lang');

        $.ajax({

            url: base_url + "/spare_products/search_spare_item_list",

            method: "GET",

            data: { main_cat_id: main_cat_item, language: language },

            complete: function(e, xhr) {

                $('#search_spare_group_item').html('Search <span class="glyphicon glyphicon-search"></span>').attr('disabled', false);

                if (e.status == 200) {
                    $("#user_data_body").html(e.responseText);

                }

            }

        });

    });

    /* End*/

    /*Add Service Group Script Start */

    $(document).on('click', '#add_service_group', function(e) {

        e.preventDefault();

        $("#myModalLabel").html('Add Service Group');

        $("#add_service_group_form")[0].reset();

        $("#add_new_service_group").modal('show');

    });

    /*End */



    /*Add Service Group Script Start */

    $(document).on('submit', '#add_service_group_form', function(e) {

        $('#msg_response').html(" ");

        $("err_response").html(" ");

        $('#service_group_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);

        e.preventDefault();

        $.ajax({

            url: base_url + "/spare_products/add_spare_group",

            type: "POST",

            data: new FormData(this),

            contentType: false,

            cache: false,

            processData: false,

            success: function(data) {

                errorString = '';

                $('#service_group_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled', false);

                var parseJson = jQuery.parseJSON(data);

                if (parseJson.status == 200) {

                    $("#add_service_group_form")[0].reset();

                    $("#add_new_service_group").modal('hide');

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

                    $("#add_service_group_form")[0].reset();

                    $("#add_new_service_group").modal('hide');

                    $("#msg_response_popup").modal('show');

                    $("#msg_response").html(parseJson.msg);

                }

            },

            error: function(xhr, error) {

                $('#service_group_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled', false);

                $("#response_msg").html(parseJson.msg);

            }

        });

    });

    /*End */

    /*Change Spare group Status */

    $(document).on('click', '.change_spare_group_status', function(e) {

        $("#spare_group_id").val("");

        $("#spare_group_name").val("");

        $("#description").val("");

        $('#status').find("option[value='0']").attr('selected', false);

        $('#priority').find("option[value='0']").attr('selected', false);

        e.preventDefault();

        var $this = $(this);

        var spare_group_id = $(this).data('spareid');

        var status = $(this).data('status');

        var con = confirm("Are you sure want to Change Status ?");

        if (con == true) {

            $.ajax({

                url: base_url + "/spare_products/change_spare_group_status",

                type: "GET",

                data: { status: status, spareId: spare_group_id },

                success: function(data) {

                    if (status == 'A') {

                        $this.html(" <i class='fa fa-toggle-on'></i>").data('status', "P");



                    }

                    if (status == 'P') {

                        $this.html(" <i class='fa fa-toggle-off'></i>").data('status', 'A');



                    }

                }

            });

        } else {

            return false;

        }

    });

    /*End */

    /*Edit Spare Service group */

    $(document).on('click', '.edit_spare_group', function(e) {

        e.preventDefault();

        $('#spare_group_id').val(" ");

        var $this = $(this);

        var spare_id = $(this).data('spareid');

        $.ajax({

            url: base_url + "/spare_products/get_spare_details",

            method: "GET",

            data: { spareId: spare_id },

            success: function(data) {

                // console.log(data);

                var parseJson = jQuery.parseJSON(data);

                if (parseJson.status == 200) {

                    $("#spare_group_id").val(parseJson.response.id);

                    $("#spare_group_name").val(parseJson.response.main_cat_name);

                    $("#description").val(parseJson.response.description);

                    $('#status').find("option[value='" + parseJson.response.status + "']").attr('selected', 'selected');

                    $('#priority').find("option[value='" + parseJson.response.priority + "']").attr('selected', 'selected');

                    $("#myModalLabel").html('Edit Service Group');

                    $("#add_new_service_group").modal('show');

                }

            }

        });



    });

    /*End */

    /* Search Group Item Script Start */

    $(document).on('click', '#search_group_item', function(e) {

        e.preventDefault();

        var group_item_id = $(".car_version_group").val();

        var language = $('html').attr('lang');

        if (group_item_id != 0) {

            $('#search_group_item').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);

            $.ajax({

                url: base_url + "/admin_ajax/search_group_item",

                method: "GET",

                data: { group_item_id: group_item_id, language: language },

                complete: function(e, xhr) {

                    $('#search_group_item').html('Search <span class="glyphicon glyphicon-search"></span>').attr('disabled', false);

                    console.log(e);

                    if (e.status == 200) {

                        $("#group_mapping_list").html(e.responseText);

                    }

                }

            });

        }

    });

    /* End */

    /* Check all checkbox on select all */

    $(document).on('click', '#all_select', function() {

        if ($(this).is(':checked')) {

            $('.group_id').prop("checked", true);

        } else {

            $('.group_id').prop("checked", false);

        }

    });

    /*End */



});