function show_sos_image(cat_id){
    if(cat_id != ""){
        $("#category_id").val(cat_id);
        $.ajax({
            url: base_url+"/wrecker_ajax/get_sos_image",
            method: "GET",
            data: {category_id:cat_id},
            success: function(data){
              $('#image_result').html(data);
              $('#add_car_wash_image_popup').modal('show');
            }
        });
    }	
}
$(document).ready(function(e) {
    /*Show Add sos category modal popup */
    $(document).on('click','#add_new_sos_category',function(e){
        e.preventDefault();
        $("#wracker_service_id").val("");
		$("#service_name").val("");
		$("#time_per_km").val("");
		$("#loading_unloading").val("");
		$("#weight_type_1").val("");
        $("#weight_type_2").val("");
        $("#description").val("");
		$("#myModalLabel").html('Add Wrecker Services');
        $('#add_wracker_service_popup').modal('show');
    });
    /*End */

    /*Submit sos category form */
    $(document).on('submit','#add_wracker_service_form',function(e){
        $('#response').html(" ");
		$("err_response").html(" ");
		$('#add_wracker_service_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
        e.preventDefault();
        $.ajax({
            url: base_url+"/wrecker_ajax/add_wracker_service",
            type: "POST",        
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData:false,  
            success: function(data){
                errorString = '';
                $('#add_wracker_service_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
                var parseJson = jQuery.parseJSON(data); 
                if(parseJson.status == 400){
							$.each(parseJson.error, function(key , value) {
								errorString += '<div class="notice notice-danger"><strong>Note , </strong>'+ value+' .</div>';
							});
							 $("#msg_response_popup").modal('show');
                             $("#msg_response").html(errorString);	
						}
                if(parseJson.status == 200){
                    $(".close").click();	
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                    setTimeout(function(){ location.reload(); } , 1000);
                } 
                if(parseJson.status == 100){
                    $("#msg_response_popup").modal('show');
                    $("#response").html(parseJson.msg);
                }	 
            } 
        });
    });
    /*End */

    /*Edit SOS Category Form open */
    $(document).on('click','.edit_wracker_service',function(e){
        e.preventDefault();
        $('#wracker_service_id').val(" ");
		var $this = $(this);
        var category_id = $(this).data('categoryid');
        $.ajax({
			url: base_url+"/wrecker_ajax/get_category_details",
			method: "GET",
			data: {categoryId:category_id},
			success: function(data){
				var parseJson = jQuery.parseJSON(data);
				if (parseJson.status == 200) {
					$("#wracker_service_id").val(parseJson.response.id);
					$("#service_name").val(parseJson.response.services_name);
					$("#time_per_km").val(parseJson.response.time_per_km);
					$("#time_per_km").val(parseJson.response.time_per_km);
					$("#loading_unloading").val(parseJson.response.loading_unloading_time);
					$("#time_arrives").val(parseJson.response.time_arrives_15_minutes);
					$("#weight_type_1").val(parseJson.response.type_of_weight_1_2000);
                    $("#weight_type_2").val(parseJson.response.type_of_weight_2000_3000);
                    $("#description").val(parseJson.response.description);
                    $('#service_type').val(parseJson.response.wracker_service_type).attr('checked','checked');
					$("#myModalLabel").html('Edit Wracker Service');
					$("#add_wracker_service_popup").modal('show');
				}
			}
        });
    });
    /*End */

    /*Change SoS category status */
    $(document).on('click','.change_wracker_service_status',function(e){
        e.preventDefault();
        var $this = $(this);
        var status = $(this).data('status');
        var category_id = $(this).data('categoryid');
        var con = confirm("Are you sure want to Change Status ?");
        if(con == true) {
            $.ajax({
				url: base_url+"/wrecker_ajax/change_wracker_service_status",
				type: "GET",        
				data:{status:status , categoryId:category_id},
				success: function(data){
					 if(status == 'P'){
                        $this.html(" <i class='fa fa-toggle-off'></i>").data('status' , 'A');
                    }
					if(status == 'A'){
                        $this.html(" <i class='fa fa-toggle-on'></i>").data('status' , 'P');
                    } 
				}
			});
        } else {
            return false;
        }
    });
    /*End */
     /*Upload Multiple image Upload */
    $(document).on('click','.upload_multiple_images',function(e){
        e.preventDefault();
        var cat_id = $(this).data('categoryid');
        show_sos_image(cat_id)
    });	
    /*End */
    /*Delete Selected Images */
    $(document).on('click','.remove_sos_images',function(e){
        e.preventDefault();
        var con = confirm("Are you sure want to delete this image");
	    if(con == true){
            var delete_id = $(this).data('imageid');
            var category_id = $("#category_id").val();
            $.ajax({
                url: base_url+"/wrecker_ajax/remove_image",
                type: "GET",        
                data:{delete_id:delete_id , category_id:category_id},
                success: function(data){
                    show_sos_image(category_id);
                    var parseJson = jQuery.parseJSON(data);
                    if(parseJson.status == 100){
                        $("#msg_response_popup").modal('show');
                        $("#msg_response").html('<div class="notice notice-danger"><strong>Wrong </strong> Something went wrong , please try again !!! </div>');
                    }
                    else{
                        $('#image_grid_section').load(document.URL + ' #image_grid_section'); 
                    }  
                }
            });
		}
    });
    /*End */

    /*Submit multiple image form */
    $(document).on('submit','#edit_category_image',function(e){
		$('#response_msg').html(" ");
		$('#save_image').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
		var category_id = $("#category_id").val();
		e.preventDefault();
        $.ajax({
            url: base_url+"/wrecker_ajax/upload_category_image",
            type: "POST",        
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData:false,  
            success: function(data){
                $('#save_image').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
                var parseJson = jQuery.parseJSON(data); 
                if(parseJson.status == 200){
                    $(".close").click();
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                    $("#edit_category_image")[0].reset();
                } else {
                    $("#response_msg").html(parseJson.msg);
                }	 
            } , 
            error: function(xhr, error){
                $('#save_image').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
                $("#response_msg").html(parseJson.msg);
            }
        });
    });
    /*End */
    $(document).on('click', '#add_wrecker_services', function(e) {
        e.preventDefault();
        $('#wracker_service_popup').modal('show');
    })

    $(document).on('change', '.wrecker_type', function(e) {
        e.preventDefault();
        var $this = $(this);
        var service_type = this.options[this.selectedIndex].getAttribute('type');
        if(service_type == 2) {
            $('.wrecker_call_price').css('display', 'block');
        } else if(service_type == 1) {
            $('#call_price').val("");
            $('.wrecker_call_price').css('display', 'none');
        }
    });
    $(document).on('submit', '#add_workshop_wrecker_services_form', function(e) {
        $('#response').html(" ");
		$("err_response").html(" ");
		$('#add_wrecker_services_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
        e.preventDefault();
        $.ajax({
            url: base_url+"/wrecker_ajax/add_wrecker_service_details",
            type: "POST",        
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData:false,  
            success: function(data){
                errorString = '';
                $('#add_wrecker_services_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
                var parseJson = jQuery.parseJSON(data); 
                if(parseJson.status == 400){
                    $.each(parseJson.error, function(key , value) {
                        errorString += '<div class="notice notice-danger"><strong>Note , </strong>'+ value+' .</div>';
                    });
                        $("#msg_response_popup").modal('show');
                        $("#msg_response").html(errorString);	
                }
                if(parseJson.status == 200){
                    $(".close").click();	
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                    setTimeout(function(){ location.reload(); } , 1000);
                } 
                if(parseJson.status == 100){
                    $(".close").click();
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                    // $("#response").html(parseJson.msg);
                }	 
            } 
        });
    });
    $(document).on('click', '.change_workshop_wrecker_status', function(e) {
        e.preventDefault();
        var $this = $(this);
        var status = $(this).data('status');
        var service_id = $(this).data('serviceid');
        var con = confirm("Are you sure want to Change Status ?");
        if(con == true) {
            $.ajax({
				url: base_url+"/wrecker_ajax/change_workshop_wrecker_status",
				type: "GET",        
				data:{status:status , serviceId:service_id},
				success: function(data){
					 if(status == 'P'){
                        $this.html(" <i class='fa fa-toggle-on'></i>").data('status' , 'A');
                    }
					if(status == 'A'){
                        $this.html(" <i class='fa fa-toggle-off'></i>").data('status' , 'P');
                    } 
				}
			});
        } else {
            return false;
        }
    });

    $(document).on('click', '.edit_wrecker_details', function(e){
        e.preventDefault();
        var service_id = $(this).data('serviceid');
        var service_name = $(this).data('servicname');
        $('#wrecker_service_name').val(service_name);
        $('#wrecker_service_id').val(service_id);
        if(service_id) {
            $.ajax({
				url: base_url+"/wrecker_ajax/get_wrecker_service_details",
				type: "GET",        
				data:{serviceId:service_id},
				success: function(data){
                    var parseJson = jQuery.parseJSON(data);
                    console.log(parseJson.response);
                    if(parseJson.status == 200){
						$('#wrecker_service_id').val(parseJson.response.wracker_services_id);
						$("#service_time_arrives").val(parseJson.response.total_time_arrives);
						$("#service_hourly_rate").val(parseJson.response.hourly_cost);
						$("#servicecost_per_km").val(parseJson.response.cost_per_km);
						$("#service_max_appointment").val(parseJson.response.max_appointment);
						$("#service_service_call_price").val(parseJson.response.call_cost);
						$("#emergency_time_arrives").val(parseJson.response.e_total_time);
						$("#emergency_hourly_rate").val(parseJson.response.e_hourly_cost);
						$("#emergencycost_per_km").val(parseJson.response.e_cost_per_km);
						$("#emergency_service_call_price").val(parseJson.response.e_call_cost);
						$("#emergency_max_appointment").val(parseJson.response.e_max_appointment);
						$("#edit_wracker_service_popup").modal('show');
                    }
                    if(parseJson.status == 100) {
                        $('#wrecker_service_id').val(service_id);
						$("#service_time_arrives").val("");
						$("#service_hourly_rate").val("");
						$("#servicecost_per_km").val("");
						$("#service_max_appointment").val("");
						$("#emergency_time_arrives").val("");
						$("#emergency_hourly_rate").val("");
						$("#emergencycost_per_km").val("");
						$("#emergency_service_call_price").val("");
						$("#emergency_max_appointment").val("");
                        $("#service_service_call_price").val("");
                        $("#edit_wracker_service_popup").modal('show');
                    }
				}
			});
        }
    });
    
    $(document).on('click', '.view_wrecker_service_details', function(e){
        e.preventDefault();
        var service_id = $(this).data('serviceid');
        if(service_id) {
            $.ajax({
				url: base_url+"/wrecker_ajax/view_wrecker_service_details",
				type: "GET",        
				data:{serviceId:service_id},
				success: function(data){
                    $("#service_response").html(data);
                    $("#wrecker_service_details_modal").modal('show');
				}
			});
        }
    });
    
    $(document).on('submit', '#edit_workshop_wrecker_services_form', function(e) {
        $('#response').html(" ");
		$("err_response").html(" ");
		$('#edit_wrecker_services_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
        e.preventDefault();
        $.ajax({
            url: base_url+"/wrecker_ajax/edit_wrecker_service_details",
            type: "POST",        
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData:false,  
            success: function(data){
                errorString = '';
                $('#edit_wrecker_services_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
                var parseJson = jQuery.parseJSON(data); 
                if(parseJson.status == 400){
                    $.each(parseJson.error, function(key , value) {
                        errorString += '<div class="notice notice-danger"><strong>Note , </strong>'+ value+' .</div>';
                    });
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(errorString);	
                }
                if(parseJson.status == 200){
                    $(".close").click();	
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                    setTimeout(function(){ location.reload(); } , 1000);
                } 
                if(parseJson.status == 100){
                    $(".close").click();
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                }	 
            } 
        });
    });
});