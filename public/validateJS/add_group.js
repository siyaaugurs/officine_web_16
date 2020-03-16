$(document).ready(function(e) {
	
	/*Set As Default */
	$(document).on('change', ".tyre_group", function (e) {
		var group_id = $(this).data('group_id');
		if ($(this).prop('checked')) {
			var status = 1;
		} else {
			var status = 2;
		}
		$.ajax({
			url: base_url+"/tyre_ajax/set_default_tyre_group",
			method: "GET",
			data: {group_id:group_id, status:status},
			success: function(data){
				console.log(data);
				var parseJson = jQuery.parseJSON(data);
				if(parseJson.status == 200) {
					$('#success_message').fadeIn().html(parseJson.msg);
					setTimeout(function() {
						$('#success_message').fadeOut("slow");
					}, 2000 );
				} else {
					$("#msg_response_popup").modal('show');
					$("#msg_response").html(parseJson.msg);
				}
			}

	    });
	});
	/*End */

    /*Add Group Script Start */
	$(document).on('click','#add_group',function(e){
		e.preventDefault();
		$("#group_id").val(" ");
		$("#myModalLabel").html('Add Group');
		$("#add_group_form")[0].reset();
		$("#add_new_group").modal('show');
	});
    /*End */

    /*Add Service Group Script Start */
	$(document).on('submit','#add_group_form',function(e){
		$('#msg_response').html(" ");
		$("err_response").html(" ");
		$('#group_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
		e.preventDefault();
		$.ajax({
			url: base_url+"/tyre_ajax/add_group",
			type: "POST",        
			data: new FormData(this),
			contentType: false,
			cache: false,
			processData:false,  
			success: function(data){
				errorString = '';
				$('#group_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
				var parseJson = jQuery.parseJSON(data); 
				if(parseJson.status == 200){
					$("#add_group_form")[0].reset();	
					$("#add_new_group").modal('hide');
					$("#msg_response_popup").modal('show');
					$("#msg_response").html(parseJson.msg);
					setTimeout(function(){ location.reload(); } , 1000);
				}  
				if(parseJson.status == 400){
					$.each(parseJson.error, function(key , value) {
					    errorString += '<div class="notice notice-danger"><strong>Wrong , </strong>'+ value+' .</div>';
					});
				  	$('#err_response').html(errorString); 	
			   	}
				if(parseJson.status == 100){
					$("#add_group_form")[0].reset();	
					$("#add_new_group").modal('hide');
					$("#msg_response_popup").modal('show');
					$("#msg_response").html(parseJson.msg);
				}	 
			} , 
			error: function(xhr, error){
				$('#group_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
				$("#response_msg").html(parseJson.msg);
			}
		});
	});

    /*End */

    /*Change Spare group Status */
    $(document).on('click','.change_group_status',function(e){
		$("#group_id").val("");
		$("#group_name").val("");
		$("#description").val("");
		$("#group_priority").val("");
		$("#service_time").val("");
        e.preventDefault();
        var $this = $(this);
        var group_id = $(this).data('group_id');
        var status = $(this).data('status');
        var con = confirm("Are you sure want to Change Status ?");
        if(con == true) {
            $.ajax({
				url: base_url+"/tyre_ajax/change_group_status",
				type: "GET",        
				data:{status:status , group_id:group_id},
				success: function(data){
					if(status == '0'){
                        $this.html(" <i class='fa fa-toggle-on'></i>").data('status' , "1");
                    }
					if(status == '1'){
                        $this.html(" <i class='fa fa-toggle-off'></i>").data('status' , '0');
                    } 
				}
			});
        } else {
            return false;
        }
    });
    /*End */

    /*Edit Service group */ 
    $(document).on('click','.edit_group',function(e){
		e.preventDefault();
		$('#group_id').val(" ");
		var $this = $(this);
		var group_id = $(this).data('group_id');
		$.ajax({
			url: base_url+"/tyre_ajax/get_group_details",
			method: "GET",
			data: {group_id:group_id},
			success: function(data){
				// console.log(data);
				var parseJson = jQuery.parseJSON(data);
				if (parseJson.status == 200) {
					$("#group_id").val(parseJson.response.id);
					$("#group_name").val(parseJson.response.category_name);
					$("#description").val(parseJson.response.description);
					$("#group_priority").val(parseJson.response.priority);
					$("#service_time").val(parseJson.response.time);
					$("#range_from").val(parseJson.response.range_from);
					$("#range_to").val(parseJson.response.range_to);
					$("#myModalLabel").html('Edit Group');
					$("#add_new_group").modal('show');
				}
			}
		});
    });
	/*End */
    /*Delete tyre group */
    $(document).on('click','.delete_group',function(e){
        e.preventDefault();
		$('#group_id').val(" ");
		var $this = $(this);
        var group_id = $(this).data('group_id');
        var con = confirm("Are you sure want to remove this group ?");
        if(con == true) {
            $.ajax({
				url: base_url+"/tyre_ajax/delete_group",
				method: "GET",
				data: {group_id:group_id},
				success: function(data){
                    var parseJson = jQuery.parseJSON(data);
                    $("#msg_response_popup").modal('show');
					$("#msg_response").html(parseJson[0].msg);
					setTimeout(function(){ location.reload(); } , 1000);
				}
		    });
        } else {
            return false;
        }
    })
	/*End */
	//Show modal on edit button click of workshop tyre24
	$(document).on('click','.edit_workshop_tyre24_group_services',function(e){
		e.preventDefault();
		var group_id = $(this).data('id');
		var hourly_rate = $(this).data('hourly_rate');
		var max_appointment = $(this).data('max_appointment');
		$(".card-body #group_id").val(group_id);
		$(".card-body #workshop_tyre24_hourly_rate").val( hourly_rate );
		$(".card-body #workshop_tyre24_max_appointment").val( max_appointment );
		$("#edit_workshop_tyre24_services").modal('show');
	});
	/*Submit Workshop Tyre24 group price add/edit form */
	$(document).on('submit', '#edit_workshop_tyre24_group_form', function(e){
		e.preventDefault();
		$('#msg_response').html(" ");
		$("err_response").html(" ");
		$('#edit_workshop_tyre24_service_price_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
		$.ajax({
			url: base_url+"/vendor/edit_workshop_tyre24_group_details",
			type: "POST",        
			data: new FormData(this),
			contentType: false,
			cache: false,
			processData:false,  
			success: function(data){
				errorString = '';
				$('#edit_workshop_tyre24_service_price_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
		 	   var parseJson = jQuery.parseJSON(data); 
				if(parseJson.status == 200){
					$("#edit_workshop_tyre24_group_form")[0].reset();	
					$("#edit_workshop_tyre24_services").modal('hide');
					$("#msg_response_popup").modal('show');
					$("#msg_response").html(parseJson.msg);
					setTimeout(function(){ location.reload(); } , 1000);
				}  
				if(parseJson.status == 400){
					$.each(parseJson.error, function(key , value) {
					  errorString += '<div class="notice notice-danger"><strong>Wrong , </strong>'+ value +' .</div>';
					});
				  	$("#msg_response_popup").modal('show');
					$('#msg_response').html(errorString);
			   	}
				if(parseJson.status == 100){
					$("#msg_response_popup").modal('show');
					$("#msg_response").html(parseJson.msg);
				}	 
			} , 
			error: function(xhr, error){
				$('#edit_workshop_tyre24_service_price_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
				$("#response_msg").html(parseJson.msg);
			}
		});
	})
   /*End */
   /*Add Workshop Tyre24 group price add form*/
	$(document).on('click','#workshop_tyre24_group_details',function(e){
		e.preventDefault();
		$("#add_workshop_tyre24_group_details_popup").modal('show');
	});	
	$(document).on('click','#add_workshop_tyre24_group_details_btn',function(e){
		var btn_html = $("#add_workshop_tyre24_group_details_btn").html();
		$('#add_workshop_tyre24_group_details_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
		e.preventDefault();
		max_appointment = $("#max_appointment").val();
		hourly_rate = $("#hourly_rate").val();
		$.ajax({
			url: base_url+"/tyre_ajax/workshop_tyre24_group_details",
			type: "GET",        
			data: { max_appointment:max_appointment, hourly_rate: hourly_rate},
			complete: function(e , xhr , setting){
				$('#add_workshop_tyre24_group_details_btn').html(btn_html).attr('disabled' , false);
				var errorString = '';
				if(e.status == 200){
					var parseJson = jQuery.parseJSON(e.responseText);
					if(parseJson.status == 200){
						$("#add_services_form")[0].reset();	
						$('.close').click();
						$("#msg_response_popup").modal('show');
						$("#msg_response").html(parseJson.msg);
						setTimeout(function(){ location.reload(); } , 1000);
					}
					if(parseJson.status == 400){
						$.each(parseJson.error, function(key , value) {
						var msg = value[0];
						errorString += '<div class="notice notice-danger"><strong>Wrong , </strong>'+ msg +' .</div>';
						});
						$("#msg_response_popup").modal('show');
					  	$('#msg_response').html(errorString);  	
				   	}
					if(parseJson.status == 100){
						$("#msg_response_popup").modal('show');
						$("#msg_response").html(parseJson.msg);
					}  
				}
			},
			error: function(xhr, error){
				$('#add_services_btn_copy').html(btn_html).attr('disabled' , false);
				$("#response_err").html('<div class="notice notice-danger"><strong>Wrong </strong>Something Wrong , please try again . !!! </div>');
			}
		});
	});
   /*End */
});

