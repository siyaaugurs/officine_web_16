function get_modals(makers_id, model_id, type) {
    if (makers_id != "" && makers_id != 0) {
        if (makers_id != 1) {
            $("#preloader").show();
            $.ajax({
                url: base_url + "/products_ajax/get_model_name",
                method: "GET",
                data: { makers_id: makers_id },
                success: function(data) {
                    var parseJson = jQuery.parseJSON(data);
                    var html_content = '';
                    if (parseJson.status == 200) {
                        html_content += '<option value="0" hidden="hidden">--Select--Car--Model--</option>';
                        // html_content += '<option value="1">All Models</option>';
                        $.each(parseJson.response, function(index, value) {
                            var value_model = value.idModello + "/" + value.ModelloAnno;
                            html_content += '<option value="' + value_model + '">' + value.Modello + " >> " + value.ModelloAnno + '</option>';
                        });
                        if (type == 2 && model_id != "") {
                            $(".models").html(html_content).find("option[value='" + model_id + "']").attr('selected', 'selected');
                            $("#preloader").hide();
                        } else {
                            $(".models").html(html_content);
                            $("#preloader").hide();
                        }

                    }
                },
                error: function(xhr, error) {
                    $("#preloader").hide();
                }
            });
        } else {
            $("#preloader").hide();
            html_content = '<option value="" hidden="hidden">--Select--Car--Model--</option>';
            html_content += '<option value="1">All Models</option>';
            if (type == 2 && model_id == 1) {
                $(".models").html(html_content).find("option[value='" + model_id + "']").attr('selected', 'selected');
            } else {
                $(".models").html(html_content);
            }
        }
    } else {
        html_content = '<option value="" hidden="hidden">--Select--Car--Maker--First--</option>';
        $(".models").html(html_content);
    }
}

function get_versions_details(model_value, version_id, type) {
    if (model_value != "") {
        if (model_value != 1) {
            $("#preloader").show();
            $.ajax({
                url: base_url + "/products_ajax/get_version_name",
                method: "GET",
                data: { model_value: model_value },
                success: function(data) {
                    // $("#preloader").hide();
                    var parseJson = jQuery.parseJSON(data);
                    var html_content = '';
                    if (parseJson.status == 100) {
                        $("#msg_response_popup").modal('show');
                        $("#msg_response").html(parseJson.msg);
                    }
                    if (parseJson.status == 400) {
                        html_content += '<option value="0">No Version Available </option>';
                    }
                    if (parseJson.status == 200) {
                        html_content += '<option value="0" hidden="hidden">--Select--Car--version--</option>';
                        // html_content += '<option value="all">All Versions</option>';
                        var version_name = '';
                        $.each(parseJson.response, function(index, value) {
                            version_name = value.Versione + " , " + value.Motore + " , " + value.ModelloCodice + " , " + value.idVeicolo + " , " + value.Body + " , " + value.Cm3;
                            html_content += '<option value="' + value.idVeicolo + '">' + version_name + '</option>';
                        });
                    }
                    if (type == 2 && version_id != "") {
                        $(".versions").html(html_content).find("option[value='" + version_id + "']").attr('selected', 'selected');
                        $("#preloader").hide();
                    } else {
                        $(".versions").html(html_content);
                        $("#preloader").hide();
                    }
                },
                error: function(xhr, error) {
                    $("#preloader").hide();
                }
            });
        } else {
            $("#preloader").hide();
            html_content = '<option value="0" hidden="hidden">--Select--Car--version--</option>';
            // html_content += '<option value="all">All Versions</option>';
            if (type == 2 && version_id == "all") {
                $(".versions").html(html_content).find("option[value='" + version_id + "']").attr('selected', 'selected');
            } else {
                $(".versions").html(html_content);
            }
        }
    } else {
        $("#preloader").hide();
        html_content = '<option value="" hidden="hidden">--Select--Car--Model--First--</option>';
        $(".versions").html(html_content);
    }
}
/*Get Car version script start*/

$(document).on('change', '.models', function() {
    var model_value = $(this).val();
    $("#preloader").show();
    var type = 1;
    var version_id = "";
    get_versions_details(model_value, version_id, type);

});
/*End*/
$(document).on('change', '.makers', function() {
    $("#preloader").show();
    var makers_id = $(this).val();
    var type = 1;
    var model_id = "";
    get_modals(makers_id, model_id, type);

});
$(document).on('change', '.versions', function(e) {
    version = $(this).val();
    var language = $('html').attr('lang');
    action = $(this).data('action');
    if (action == "get_n1_category") {
        get_n1_category(version, language);
    }
});

$(document).on('change', '.groups', function(e) {
    group = $(this).val();
    console.log(group)
    var language = $('html').attr('lang');
    action = $(this).data('action');
    if (action == "get_n2_category") {
        get_n2_category(group, language);
    }
    if (action == "get_sub_category") {
        get_n2_category(group, language);
    }

});

$(document).on('change', '.sub_groups', function(e) {
    $("#preloader").show();
    group_id = $(this).val();
    $(".items").empty();
    var language = $('html').attr('lang');
    if (group_id != "") {
        if (group_id != "all") {
            $.ajax({
                url: base_url + "/save_products_item_05_08",
                method: "GET",
                data: { group_id: group_id, language: language },
                complete: function(e, xhr, setting) {
                    if (e.status == 200) {
                        $.ajax({
                            url: base_url + "/get_products_item_05_08",
                            method: "GET",
                            data: { group_id: group_id, language: language },
                            complete: function(e, xhr, settings) {
                                if (e.status == 200) {
                                    var parseJson = jQuery.parseJSON(e.responseText);
                                    if (parseJson.status == 200) {
                                        $(".items").append($('<option>', { value: 0 }).text('--Select--Item--'));
                                        $(".items").append($('<option>', { value: 'all' }).text('All Category items'));
                                        $.each(parseJson.response, function(index, value) {
                                            front_rear = '';
                                            left_right = '';

                                            if (value.front_rear == "") {
                                                front_rear = '';
                                            } else {
                                                front_rear = value.front_rear;
                                            }
                                            if (value.left_right == "") {
                                                left_right = "";
                                            } else {
                                                left_right = value.left_right;
                                            }

                                            var text_name = value.item + " " + front_rear + "  " + left_right;
                                            $(".items").append($('<option>', { value: value.id }).text(text_name));
                                            $("#preloader").hide();
                                        });
                                    }
                                    if (parseJson.status == 100) {
                                        $("#preloader").hide();
                                        alert("Something Went Wrong please try again ");
                                    }
                                }
                            },
                            error: function(xhr, error) {
                                $("#preloader").hide();
                            }
                        });
                    }
                },
                error: function(xhr, error) {
                    $("#preloader").hide();
                }
            });
        } else {
            $("#preloader").hide();
            html_content = '<option value="0">--Select--Item--</option>';
            html_content += '<option value="all">All Category items</option>';
            $(".items").html(html_content);
        }
    }
});

function get_n2_category(group, language) {
    if (group != "") {
        if (group != "all") {
            $.ajax({
                url: base_url + "/get_category_n2",
                method: "GET",
                data: { group: group, language: language },
                success: function(data) {
                    $("#preloader").hide();
                    var parseJson = jQuery.parseJSON(data);
                    var html_content = '';
                    if (parseJson.status == 200) {
                        html_content += '<option value="0" hidden="hidden">--Select--Car--Category--</option>';
                        html_content += '<option value="all">All Sub Category</option>';
                        $.each(parseJson.response, function(index, value) {
                            html_content += '<option value="' + value.id + '">' + value.group_name + '</option>';
                        });
                        $(".sub_groups").html(html_content);
                    }
                },
                error: function(xhr, error) {
                    $("#preloader").hide();
                }
            });
        } else {
            $("#preloader").hide();
            html_content = '<option value="0" hidden="hidden">--Select--Car--Category--</option>';
            html_content += '<option value="all">All Sub Category</option>';
            $(".sub_groups").html(html_content);
        }
    }
}

function get_n1_category(version, language) {
    if (version != 1) {
        $.ajax({
            url: base_url + "/get_category_n1",
            method: "GET",
            data: { version: version, language: language },
            success: function(data) {
                $("#preloader").hide();
                var parseJson = jQuery.parseJSON(data);
                var html_content = '';
                if (parseJson.status == 200) {
                    html_content += '<option value="0">--Select--Car--Category--</option>';
                    html_content += '<option value="all">All Category</option>';
                    $.each(parseJson.response, function(index, value) {
                        html_content += '<option value="' + value.id + '">' + value.group_name + '</option>';
                    });
                    $(".groups").html(html_content);
                }
            },
            error: function(xhr, error) {
                $("#preloader").hide();
            }
        });
    } else {
        // $("#preloader").hide();
        // html_content = '<option value="0">--Select--Car--Category--</option>'; 
        // html_content += '<option value="all">All Category</option>'; 
        // $(".groups").html(html_content); 
        $.ajax({
            url: base_url + "/get_all_n1_category",
            method: "GET",
            data: { language: language },
            success: function(data) {
                $("#preloader").hide();
                var parseJson = jQuery.parseJSON(data);
                var html_content = '';
                if (parseJson.status == 200) {
                    html_content += '<option value="all">All Category</option>';
                    $.each(parseJson.response, function(index, value) {
                        html_content += '<option value="' + value.id + '">' + value.group_name + '</option>';
                    });
                    $(".groups").html(html_content);
                }
            },
            error: function(xhr, error) {
                $("#preloader").hide();
            }
        });
    }
}