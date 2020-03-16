function get_n2_custom_category(group, sub_group, language, type, c_sub_group_type, sub_group_value) {
    $("#preloader").show();
    if (group != "") {
        if (group != "all") {
            $.ajax({
                url: base_url + "/get_category_n2",
                method: "GET",
                data: { group: group, language: language },
                success: function(data) {
                    var parseJson = jQuery.parseJSON(data);
                    var html_content = '';
                    if (parseJson.status == 200) {
                        html_content += '<option value="0" hidden="hidden">--Select--Car--Category--</option>';
                        html_content += '<option value="all">All Sub Category</option>';
                        $.each(parseJson.response, function(index, value) {
                            html_content += '<option value="' + value.id + '" data-groupid="'+value.group_id+'">' + value.group_name + '</option>';
                        });
                        if (type == 2 && sub_group != "" && c_sub_group_type == 1 ) {
                            $("#custom_sub_group").html(html_content).find("option[data-groupid='" + sub_group + "']").attr('selected', 'selected');
                            $("#preloader").hide();
                        } 
                        else if(type == 2 && sub_group_value != "" && c_sub_group_type == 2 ) {
                            $("#custom_sub_group").html(html_content).find("option[value='" + sub_group_value + "']").attr('selected', 'selected');
                            $("#preloader").hide();

                        }
                        else {
                            $("#custom_sub_group").html(html_content);
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
            html_content = '<option value="0" hidden="hidden">--Select--Car--Category--</option>';
            html_content += '<option value="all" data-groupid="all">All Sub Category</option>';
            if (type == 2 && sub_group == "") {
                $("#custom_sub_group").html(html_content).find("option[value='all']").attr('selected', 'selected');
            } else {
                $("#custom_sub_group").html(html_content);
            }
        }
    }
}

function custom_n3_category(group_id, n3_cat, language, type) {
    $("#preloader").show();
    $("#custom_items").empty();
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
                                var html_content = '';
                                if (e.status == 200) {
                                    var parseJson = jQuery.parseJSON(e.responseText);
                                    if (parseJson.status == 200) {
                                        $("#custom_items").append($('<option>', { value: 0 }).text('--Select--Item--'));
                                        $("#custom_items").append($('<option>', { value: 'all' }).text('All Category items'));
                                        $.each(parseJson.response, function(index, value) {
                                            front_rear = '';
                                            left_right = '';

                                            if (value.front_rear == null) {
                                                front_rear = "";
                                            } else {
                                                front_rear = value.front_rear;
                                            }
                                            if (value.left_right == null) {
                                                left_right = "";
                                            } else {
                                                left_right = value.left_right;
                                            }
                                            var text_name = value.item + " " + front_rear + " " + left_right;
                                            html_content += '<option value="' + value.id + '" >' + text_name + '</option>';
                                        });
                                        if (type == 2 && n3_cat != "") {
                                            $("#custom_items").html(html_content).find("option[value='" + n3_cat + "']").attr('selected', 'selected');
                                            $("#preloader").hide();
                                        } else {
                                            $("#custom_items").html(html_content);
                                            $("#preloader").hide();
                                        }
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
            if (type == 2 && n3_cat == "") {
                $("#custom_items").html(html_content).find("option[value='all']").attr('selected', 'selected');
            } else {
                $("#custom_items").html(html_content);
            }
        }
    }
}

$(document).ready(function(e) {
    $(document).on('change', '#group_n1', function(e) {
        var group = $('#group_n1').val();
        var language = $('html').attr('lang');
        var type = 1;
        var sub_group = "";
        var c_sub_group_type = "";
        var sub_group_value = "";
        get_n2_custom_category(group, sub_group, language, type, c_sub_group_type, sub_group_value);
    });

    $(document).on('change', '#custom_sub_group', function(e) {
        var group_id = $('#custom_sub_group').val();
        var language = $('html').attr('lang');
        var type = 1;
        var n3_cat = "";
        var sub_group_value = "";
        custom_n3_category(group_id, n3_cat, language, type)
    });
    

});