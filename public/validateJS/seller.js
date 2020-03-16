$(document).ready(function(e) {

    /*Change Seller Product Status */

    $(document).on('click', '.change_seller_products_status', function(e) {

        e.preventDefault();

        var $this = $(this);

        var status = $(this).data('status');

        var product_id = $(this).data('productsid');

        var con = confirm("Are you sure want to Change Status ?");

        if (con == true) {

            $.ajax({

                url: base_url + "/seller_ajax/change_saller_product_status",

                type: "GET",

                data: { status: status, productId: product_id },

                success: function(data) {

                    if (status == "A") {

                        $this.html(" <i class='fa fa-toggle-on'></i>").data('status', 'P');

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

    /* Chnage order status script */

    $(document).on('click', '.change_order_status_seller', function() {

        var $this = $(this);

        var status = $(this).data('status');

        var order_id = $(this).data('orderid');

        var con = confirm("Are you sure want to Change Status ?");

        if (con == true) {

            $.ajax({

                url: base_url + "/seller_ajax/change_order_status",

                type: "GET",

                data: { status: status, orderId_id: order_id },

                success: function(data) {

                    console.log(status);

                    //alert(status);

                    if (status == "I") {

                        $("#order_status").text('In Process');

                    }

                    if (status == "D") {

                        $("#order_status").text('Dispatched');

                    }

                    if (status == "IN") {

                        $("#order_status").text('Intransit');

                    }

                    if (status == "DE") {

                        $("#order_status").text('Delievred');

                    }

                }

            });

        }

    });

    /* End */

    /* view product order script start */

    $(document).on('click', '.get_seller_order_details', function(e) {

        e.preventDefault();

        var order_id = $(this).data('orderid');

        if (order_id != "") {

            $.ajax({

                url: base_url + "/seller_ajax/view_seller_order",

                type: "GET",

                data: { orderId: order_id },

                success: function(data) {

                    // console.log(data);

                    $("#seller_order_response").html(data);

                    $("#view_seller_order").modal('show');

                }

            });

        }

    });

    /* End */

    /* view product description script start */

    $(document).on('click', '.view_seller_product_description', function(e) {

        e.preventDefault();

        var order_id = $(this).data('orderid');

        if (order_id != "") {

            $.ajax({

                url: base_url + "/seller_ajax/view_seller_product_description",

                type: "GET",

                data: { orderId: order_id },

                success: function(data) {

                    // console.log(data);

                    $("#seller_description_response").html(data);

                    $("#view_seller_product_description").modal('show');

                }

            });

        }

    });

    /* End */

    /* view product description script start */

    $(document).on('click', '.change_seller_order_status', function(e) {

        var $this = $(this);

        var status = $(this).data('status');

        var order_id = $(this).data('orderid');

        if (status == "P") {

            var con = confirm("Are you sure want to Change Status ?");

            if (con == true) {

                $.ajax({

                    url: base_url + "/seller_ajax/change_seller_order_status",

                    type: "GET",

                    data: { status: status, orderId_id: order_id },

                    success: function(data) {

                        if (status == "P") {

                            $this.data('status', 'I').text('In Processing');

                        }

                    }

                });

            }

        }

    });

    /* End */

    /* View Seller Feedback btn script strat */

    $(document).on('click', '.view_feedback', function() {

        var no_image = base_url + ('/storage/no_image.jpg');

        var feedback_id = $(this).data('feedbackid');

        $.ajax({

            url: base_url + "/seller_ajax/view_feedback",

            method: "GET",

            data: { feedbackId: feedback_id },

            success: function(result) {

                // console.log(result[0]);

                var result = result[0];

                $('#datatable tbody tr td.text-left').text('');

                $.each(result, function(key, data) {

                    if (key == 'image_name') {

                        if (data != null) {

                            $.each(data, function(i, s) {

                                var img = '<img id="download" src="' + s + '" class="img-responsive img-thumbnail" style="height:80px; width:80px;" />';

                                $('#' + key).html(img);

                            })

                        } else {

                            var img = '<img id="download" src="' + no_image + '" class="img-responsive img-thumbnail" style="height:80px; width:80px;" />';

                            $('#' + key).html(img);

                        }

                    } else {

                        $('#' + key).text(data);

                    }

                });

            }

        });

        $('#view_feedback_popup').modal('show');

    });

    /*End* */



    /*Search Inventory Products by group*/

    $(document).on('click', '#search_invent_products_by_group', function(e) {

        $('#search_parts_group').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);

        var makers_id = $("#car_makers").val();

        var models = $("#car_models").val();

        var car_version_id = $("#car_version").val();

        var groupid = $("#group_item").val();

        e.preventDefault();

        $.ajax({

            url: base_url + "/seller_ajax/search_invent_productsBy_group",

            method: "GET",

            data: { makers_id: makers_id, models: models, car_version_id: car_version_id, groupid: groupid },

            success: function(data) {

                $('#search_parts_group').html('Search &nbsp;<span class="glyphicon glyphicon-search"></span>').attr('disabled', true);

                $("#user_data_body").html(data);

            }

        });

    });

    /*End*/



    /* Edit Product Inventory Script Code */

    $(document).on('submit', '#edit_products_invent_form', function(e) {
        e.preventDefault();
        $('#edit_product_sbmt').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
        $.ajax({
            url: base_url + "/seller_ajax/edit_invent_product",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                var parseJson = jQuery.parseJSON(data);
                $('#edit_product_sbmt').html(' Save <i class="fa fa-plus"></i>').attr('disabled', false);
                if (parseJson.status == 200) {
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                    setTimeout(function() { location.reload(); }, 1000);
                }
                if (parseJson.status == 100) {
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                }
            }
        });
    });

    /* End */
    $(document).on('click', '#search_invent_products', function(e) {
        var item_number = $("#item_number").val();
        var ean_number = $("#ean_number").val();
        if (item_number != "" || ean_number != "") {
            $("#preloader").show();
            $.ajax({
                url: base_url + "/seller_ajax/get_product_invent_search",
                beforeSend: function() {
                    $("#inventory_ColWrap").empty();
                },
                method: "GET",
                data: { item_number: item_number, ean_number: ean_number },
                success: function(data) {
                    $("#preloader").hide();
                    $('#inventory_ColWrap').html(data);
                },
                error: function(xhr, error) {
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html('<div class="notice notice-danger"><strong>Wrong </strong> Something Went Wrong please try again !!! </div>');
                }
            });
        } else {
            $("#msg_response_popup").modal('show');
            $("#msg_response").html('<div class="notice notice-danger"><strong>Wrong </strong> Please Fill At least one Field !!! </div>');
        }
    });

    $(document).on('click', '#import_export_product_inventory', function(e) {
        e.preventDefault();
        $("#import_export_product_inventory_modal").modal({
            backdrop: 'static',
            keyboard: false,
        });
    });
    $(document).on('submit', '#import_spare_invent_file', function(e) {
        $('#tyre_msg_response').html(" ");
        $('#import_spare_invent_btn').html('Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
        e.preventDefault();
        $.ajax({
            url: base_url + "/import/import_spare_invent",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            complete: function(e, xhr, setting) {
                if (e.status == 200) {
                    $("#tyre_msg_response").html('<div class="notice notice-success"><strong> Success , </strong>Record Import Successfully .</div>');
                    setTimeout(function() { location.reload(); }, 1000);
                }
                $('#import_spare_invent_btn').html('Import Spare Inventory <span class="glyphicon glyphicon-import"></span>').attr('disabled', false);
            }
        });
    });
    $(document).on('click', '#add_seller_tyre_pfu', function(e) {
        e.preventDefault();
        $("#seller_pfu_id").val("");
        $("#seller_price").val("");
        $("#tyre_class").val("");
        $("#description").val("");
        $("#add_seller_pfu_modal").modal({
            backdrop: 'static',
            keyboard: false,
        });
    });
    $(document).on('submit', '#add_seller_pfu_form', function(e) {
        e.preventDefault();
        $('#seller_pfu_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
        $.ajax({
            url: base_url + "/seller_ajax/add_seller_tyre_pfu",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                var parseJson = jQuery.parseJSON(data);
                $('#seller_pfu_btn').html(' Save <i class="fa fa-plus"></i>').attr('disabled', false);
                if (parseJson.status == 200) {
                    $(".close").click();
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                    setTimeout(function() { location.reload(); }, 1000);
                }
                if (parseJson.status == 100) {
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                }
                if (parseJson.status == 400) {
                    $.each(parseJson.error, function(key, value) {
                        errorString += '<div class="notice notice-danger"><strong>Wrong , </strong>' + value + ' .</div>';
                    });
                    $('#err_response').html(errorString);

                }
            }
        });
    });

    $(document).on('click', '.edit_seller_pfu', function(e) {
        e.preventDefault();
        var pfu_id = $(this).data('sellerpfu_id');
        $.ajax({
            url: base_url + "/seller_ajax/get_pfu_details",
            method: "GET",
            data: { pfu_id: pfu_id },
            success: function(data) {
                var parseJson = jQuery.parseJSON(data);
                if (parseJson.status == 200) {
                    $("#seller_pfu_id").val(parseJson.response.id);
                    $("#seller_price").val(parseJson.response.price);
                    $("#tyre_class").val(parseJson.response.tyre_class);
                    $("#description").val(parseJson.response.description);
                    $("#myModalLabel").html('Edit PFU Details');
                    $('#add_seller_pfu_modal').modal({
                        backdrop: 'static',
                        keyboard: false,
                    });
                }
            }
        });
    });

    $(document).on('click', '.delete_seller_pfu', function(e) {
        e.preventDefault();
        var pfu_id = $(this).data('sellerpfu_id');
        var con = confirm("Are you sure want to remove this pfu ?");
        if (con == true) {
            $.ajax({
                url: base_url + "/seller_ajax/delete_pfu",
                method: "GET",
                data: { pfu_id: pfu_id },
                success: function(data) {
                    var parseJson = jQuery.parseJSON(data);
                    if (parseJson.status == 200) {
                        $("#msg_response_popup").modal('show');
                        $("#msg_response").html(parseJson.msg);
                        setTimeout(function() { location.reload(); }, 1000);
                    }
                }
            });
        } else {
            return false;
        }
    });

    $(document).on('click', '#add_tyre_inventory', function(e) {
        e.preventDefault();
        $("#seller_tyre_invent_id").val("");
        $("#seller_tyre_invent_type").val("1");
        $("#invent_quantity").val("");
        $("#ean_number").val("");
        $("#item_number").val("");
        $("#price").val("");
        $("#quantity").val("");
        $("#stock_warning").val("");
        $('#status').find("option[value='A']").attr('selected', 'selected');
        $("#tyreModalLabel").html('Add Tyre Inventory');
        $("#add_tyre_inventory_modal").modal({
            backdrop: 'static',
            keyboard: false,
        });
    });

    $(document).on('submit', '#add_seller_tyre_inventory', function(e) {
        e.preventDefault();
        $('#seller_tyre_invent_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
        $.ajax({
            url: base_url + "/seller_ajax/add_seller_tyre_inventory",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                var parseJson = jQuery.parseJSON(data);
                $('#seller_tyre_invent_btn').html(' Save <i class="fa fa-plus"></i>').attr('disabled', false);
                console.log(parseJson)
                if (parseJson.status == 200) {
                    $(".close").click();
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                    setTimeout(function() { location.reload(); }, 1000);
                }
                if (parseJson.status == 100) {
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                }
                if (parseJson.status == 400) {
                    $.each(parseJson.error, function(key, value) {
                        errorString += '<div class="notice notice-danger"><strong>Wrong , </strong>' + value + ' .</div>';
                    });
                    $('#err_response').html(errorString);

                }
            }
        });
    });

    $(document).on('click', '.change_seller_tyre_status', function(e) {
        e.preventDefault();
        var $this = $(this);
        var status = $(this).data('status');
        var tyre_id = $(this).data('tyreid');
        var con = confirm("Are you sure want to Change Status ?");
        if (con == true) {
            $.ajax({
                url: base_url + "/seller_ajax/change_saller_tyre_status",
                type: "GET",
                data: { status: status, tyre_id: tyre_id },
                success: function(data) {
                    if (status == "A") {
                        $this.html(" <i class='fa fa-toggle-on'></i>").data('status', 'P');
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

    $(document).on('click', '.edit_invent_tyre', function(e) {
        e.preventDefault();
        var tyre_id = $(this).data('tyreid');
        $.ajax({
            url: base_url + "/seller_ajax/get_tyre_inventory_details",
            method: "GET",
            data: { tyre_id: tyre_id },
            success: function(data) {
                var parseJson = jQuery.parseJSON(data);
                if (parseJson.status == 200) {
                    $("#seller_tyre_invent_type").val("2");
                    $("#seller_tyre_invent_id").val(parseJson.response.id);
                    $("#invent_quantity").val(parseJson.response.quantity);
                    $("#ean_number").val(parseJson.response.Tyre24_ean_number);
                    $("#item_number").val(parseJson.response.Tyre24_itemId);
                    $("#price").val(parseJson.response.seller_price);
                    $("#quantity").val(parseJson.response.quantity);
                    $("#stock_warning").val(parseJson.response.stock_warning);
                    $('#status').find("option[value='" + parseJson.response.status + "']").attr('selected', 'selected');
                    $("#tyreModalLabel").html('Edit Tyre Inventory');
                    $('#add_tyre_inventory_modal').modal({
                        backdrop: 'static',
                        keyboard: false,
                    });
                }
            }
        });
    });
    $(document).on('click', '#search_invent_tyre', function(e) {
        e.preventDefault();
        var item_number = $("#tyre_item_number").val();
        var ean_number = $("#tyre_ean_number").val();
        if (item_number != "" || ean_number != "") {
            $("#preloader").show();
            $.ajax({
                url: base_url + "/seller_ajax/get_tyre_invent_search",
                beforeSend: function() {
                    $("#inventory_ColWrap").empty();
                },
                method: "GET",
                data: { item_number: item_number, ean_number: ean_number },
                success: function(data) {
                    $("#preloader").hide();
                    $('#inventory_ColWrap').html(data);
                },
                error: function(xhr, error) {
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html('<div class="notice notice-danger"><strong>Wrong </strong> Something Went Wrong please try again !!! </div>');
                }
            });
        } else {
            $("#msg_response_popup").modal('show');
            $("#msg_response").html('<div class="notice notice-danger"><strong>Wrong </strong> Please Fill At least one Field !!! </div>');
        }
    });

    $(document).on('click', '#import_export_tyre_inventory', function(e) {
        e.preventDefault();
        $('#import_export_tyre_inventory_modal').modal({
            backdrop: 'static',
            keyboard: false,
        });
    });
    $(document).on('submit', '#import_tyre_invent_file', function(e) {
        $('#tyre_msg_response').html(" ");
        $('#import_tyre_invent_btn').html('Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
        e.preventDefault();
        $.ajax({
            url: base_url + "/import/import_tyre_invent_tire",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            complete: function(e, xhr, setting) {
                if (e.status == 200) {
                    $("#tyre_msg_response").html('<div class="notice notice-success"><strong> Success , </strong>Record Import Successfully .</div>');
                    setTimeout(function() { location.reload(); }, 1000);
                }
                $('#import_tyre_invent_btn').html('Import Tyre <span class="glyphicon glyphicon-import"></span>').attr('disabled', false);
            }
        });
    });
});