$(document).ready(function(e) {
    /*Common Import Script Start*/
    $(document).on('submit', '#import_form', function(e) {
        $('#import_file_response').html(" ");
        $('#import_services').html('Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
        e.preventDefault();
        $.ajax({
            url: base_url + "/import/service_import",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            complete: function(e, xhr, setting) {
                $('#import_services').html('Import <span class="glyphicon glyphicon-import"></span>').attr('disabled', false);
                if (e.status == 200) {
                    $("#import_response").html('<div class="notice notice-success"><strong> Success , </strong>Record Import Successfully .</div>');
                    setTimeout(function() { location.reload(); }, 1000);
                }
            }
        });
    });
    $(document).on('submit', '#import_brand_product_file', function(e) {
        $('#rim_msg_response').html(" ");
        $('#btand_btn').html('Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
        e.preventDefault();
        $.ajax({
            url: base_url + "/import/brand_product_import",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            complete: function(e, xhr, setting) {
                if (e.status == 200) {
                    $("#rim_msg_response").html('<div class="notice notice-success"><strong> Success , </strong>Record Import Successfully .</div>');
                    setTimeout(function() { location.reload(); }, 1000);
                }
                $('#btand_btn').html('Import Spare File   <span class="glyphicon glyphicon-import"></span>').attr('disabled', false);
            }
        });
    });
    /*End*/
    /*Import Export Common file script start*/
    /*OPen modal for car maintinance Service Script Start*/
    /* $(document).on('click','#car_maintinance_import',function(e){
    	e.preventDefault(); 
    	maintinance_services_import = $(this);
    	service_id = wracker_services_import.data('serviceid');
    	export_btn = $('#export_btn');
    	$("#service_id").val(service_id);
    	url = base_url+"/export/services/"+service_id;
    	export_btn.attr('href' , url).html('<i class="fa fa-download"></i>&nbsp;Export Wracker Services');
        $("#import_export_common_modal").modal({
    						  backdrop: 'static',
    						  keyboard: false,
    						  });
     }); */
    /*End*/
    $(document).on('click', '#wracker_services_import , #car_maintinance_import', function() {
        wracker_services_import = $(this);
        service_id = wracker_services_import.data('serviceid');
        export_btn = $('#export_btn');
        $("#service_id").val(service_id);
        url = base_url + "/export/services/" + service_id;
        export_btn.attr('href', url).html('<i class="fa fa-download"></i>&nbsp;Export Services');
        $("#import_export_common_modal").modal({
            backdrop: 'static',
            keyboard: false,
        });
    });
    /*End*/
    /*Import Export tyre service group*/
    /*TYre services import script start*/
    $(document).on('submit', '#import_tyre_service_details', function(e) {
        $('#import_file_response').html(" ");
        $('#import_services').html('Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
        e.preventDefault();
        $.ajax({
            url: base_url + "/import/import_tyre_service",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            complete: function(e, xhr, setting) {
                $('#import_services').html('Import <span class="glyphicon glyphicon-import"></span>').attr('disabled', false);
                if (e.status == 200) {
                    $("#import_file_response").html('<div class="notice notice-success"><strong> Success , </strong>Record Import Successfully .</div>');
                    setTimeout(function() { location.reload(); }, 1000);
                }
            }
        });
    });
    /*End*/
    $(document).on('click', '#tyre_import_export', function() {
        $("#import_export_tyre_modal").modal({
            backdrop: 'static',
            keyboard: false,
        });
    });
    $(document).on('click', '#import_export_product_brand', function() {
        $("#import_export_brand_modal").modal({
            backdrop: 'static',
            keyboard: false,
        });
    });
    /*End*/

    /*Import car assemble script start*/
    /*Import Assemble script start*/
    $(document).on('submit', '#import_car_assemble_file_form', function(e) {
        $('#import_file_response').html(" ");
        $('#import_services').html('Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
        e.preventDefault();
        $.ajax({
            url: base_url + "/import/import_assemble_service",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            complete: function(e, xhr, setting) {
                $('#import_services').html('Import <span class="glyphicon glyphicon-import"></span>').attr('disabled', false);
                if (e.status == 200) {
                    $("#import_file_response").html('<div class="notice notice-success"><strong> Success , </strong>Record Import Successfully .</div>');
                    setTimeout(function() { location.reload(); }, 1000);
                }
            }
        });
    });
    /*End*/
    $(document).on('click', '#car_assemble_import_export', function() {
        $("#import_export_car_assemble_modal").modal({
            backdrop: 'static',
            keyboard: false,
        });
    });
    /*End*/

    /*Car Revision script start*/
    /*Import car revision script start*/
    $(document).on('submit', '#import_car_revision_file_form', function(e) {
        $('#car_revision_msg_response').html(" ");
        $('#import_car_revision').html('Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
        e.preventDefault();
        $.ajax({
            url: base_url + "/import/import_car_revision_service_details",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            complete: function(e, xhr, setting) {
                $('#import_car_revision').html('Import <span class="glyphicon glyphicon-import"></span>').attr('disabled', false);
                if (e.status == 200) {
                    $("#car_revision_msg_response").html('<div class="notice notice-success"><strong> Success , </strong>Record Import Successfully .</div>');
                    setTimeout(function() { location.reload(); }, 1000);
                }
            }
        });
    });
    /*End*/
    $(document).on('click', '#car_revision_import_export', function() {
        $("#import_export_modal").modal({
            backdrop: 'static',
            keyboard: false,
        });
    });
    /*End*/
    /*Import Export car wasing script start*/
    $(document).on('click', '#car_washing_import_export', function() {
        $("#import_export_car_washing_modal").modal({
            backdrop: 'static',
            keyboard: false,
        });
    });
    /*Import Car Washing Services script Start*/
    $(document).on('submit', '#import_car_washing_file', function(e) {
        $('#car_wasing_import_response').html(" ");
        $('#import_car_washing').html('Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
        e.preventDefault();
        $.ajax({
            url: base_url + "/import/import_car_wash_service_details",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            complete: function(e, xhr, setting) {
                if (e.status == 200) {
                    $('#import_car_washing').html('Save <i class="fa fa-plus"></i>').attr('disabled', false);
                    $("#tyre_msg_response").html('<div class="notice notice-success"><strong> Success , </strong>Record Import Successfully .</div>');
                    setTimeout(function() { location.reload(); }, 1000);
                }
                $('#rim_import').html('Record Insert Save successful !!!<span class="glyphicon glyphicon-import"></span>').attr('disabled', false);
            }
        });
    });
    /*End*/
    /*End*/
    /*Import Export Spare products script start*/
    /*Import Spare product script start*/
    $(document).on('submit', '#import_spare_product_file', function(e) {
        $('#rim_msg_response').html(" ");
        $('#c_spare_btn').html('Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
        e.preventDefault();
        $.ajax({
            url: base_url + "/import/custom_import_spare_parts",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            complete: function(e, xhr, setting) {
                if (e.status == 200) {
                    $("#rim_msg_response").html('<div class="notice notice-success"><strong> Success , </strong>Record Import Successfully .</div>');
                    setTimeout(function() { location.reload(); }, 1000);
                }
                $('#c_spare_btn').html('Import Spare File   <span class="glyphicon glyphicon-import"></span>').attr('disabled', false);
            }
        });
    });
    /*End*/
    $(document).on('click', '#import_export_spare_sample_format', function() {
        $("#import_export_spare_sample_modal").modal({
            backdrop: 'static',
            keyboard: false,
        });
    });
    /*End*/
    $(document).on('click', '#import_export_kromeda_spare', function() {
        $("#import_export_spare_sample_modal").modal({
            backdrop: 'static',
            keyboard: false,
        });
    });
    $(document).on('submit', '#import_kromeda_spare_product_file', function(e) {
        $('#rim_msg_response').html(" ");
        $('#k_spare_btn').html('Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
        e.preventDefault();
        $.ajax({
            url: base_url + "/import/import_kromeda_spare_parts",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            complete: function(e, xhr, setting) {
                if (e.status == 200) {
                    $("#rim_msg_response").html('<div class="notice notice-success"><strong> Success , </strong>Record Import Successfully .</div>');
                    setTimeout(function() { location.reload(); }, 1000);
                }
                $('#k_spare_btn').html('Import Spare File   <span class="glyphicon glyphicon-import"></span>').attr('disabled', false);
            }
        });
    });
    /*tire export modal open script start*/
    $(document).on('click', '#export_spare_products', function() {
        $("#export_spare_modal").modal({
            backdrop: 'static',
            keyboard: false,
        });
    });
    /*End*/
    /*End*/
    /*Rim Import Export script Start*/
    /*Import File Script Start*/
    $(document).on('submit', '#import_rim_file', function(e) {
        $('#rim_msg_response').html(" ");
        $('#rim_import').html('Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
        e.preventDefault();
        $.ajax({
            url: base_url + "/import/import_rim",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            complete: function(e, xhr, setting) {
                if (e.status == 200) {
                    $("#rim_msg_response").html('<div class="notice notice-success"><strong> Success , </strong>Record Import Successfully .</div>');
                    setTimeout(function() { location.reload(); }, 1000);
                }
                $('#rim_import').html('Import Rim File   <span class="glyphicon glyphicon-import"></span>').attr('disabled', false);
            }
        });
    });
    /*End*/
    $(document).on('click', '#import_export_rim_sample_format', function() {
        $("#import_export_rim_sample_modal").modal({
            backdrop: 'static',
            keyboard: false,
        });
    });
    /*End*/
    /*tire export modal open script start*/
    $(document).on('click', '#export_rims', function() {
        $("#export_rim_modal").modal({
            backdrop: 'static',
            keyboard: false,
        });
    });
    /*End*/

    /*Import tire files script start*/
    $(document).on('submit', '#import_tire_file', function(e) {
        $('#tyre_msg_response').html(" ");
        $('#import_tyre').html('Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
        e.preventDefault();
        $.ajax({
            url: base_url + "/import/import_tire",
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
                $('#import_tyre').html('Import Tyre <span class="glyphicon glyphicon-import"></span>').attr('disabled', false);
            }
        });
    });
    /*End*/
    $("#tire_file").change(function() {
        var selectedText = $("#tire_file").val();
        var extension = selectedText.substr((selectedText.lastIndexOf('.') + 1));
        if (extension == "csv" || extension == "xlsx") {
            $('#import_tyre').attr('disabled', false);
        } else {
            $("#tire_file").focus();
            alert("Please choose a .csv or .xlsx file");
            $('#import_tyre').attr('disabled', true);
            return;
        }

    });
    /*Open modal for tyre mport export */
    $(document).on('click', '#import_export_tyre_sample_format', function() {
        $("#import_export_tyre_sample_modal").modal({
            backdrop: 'static',
            keyboard: false,
        });
    });
    /*End*/
    /*tire export modal open script start*/
    $(document).on('click', '#export_tyres', function() {
        $("#export_tyre_modal").modal({
            backdrop: 'static',
            keyboard: false,
        });
    });
    /*Exit*/
    /*Import category form script start*/
    $(document).on('submit', '#import_category_form', function(e) {
        //alert('sdfsf');
        //$('#response_add_category').html(" ");
        //$('#add_services_btn').html('Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
        e.preventDefault();
        $.ajax({
            url: base_url + "/import/import_n3_category",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                setTimeout(function() { location.reload(); }, 1000);
                //console.log(data); 
            }
        });
    });
    /*End*/
});