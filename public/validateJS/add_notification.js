$(document).ready(function(e) {
	
	/*Set As Default */
	$(document).on('change', ".tyre_pfu", function (e) {
		var pfu_id = $(this).data('pfu_id');
		if ($(this).prop('checked')) {
			var status = 1;
		} else {
			var status = 2;
		}
		$.ajax({
			url: base_url+"/tyre_ajax/set_default_tyre_pfu",
			method: "GET",
			data: {pfu_id:pfu_id, status:status},
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

    /*Add pfu Script Start */
	$(document).on('click','#add_pfu',function(e){
		e.preventDefault();
		$("#myModalLabel").html('Add Notification');
		$("#add_pfu_form")[0].reset();
		$("#edit_image").remove();
		$("#add_new_pfu").modal('show');
	});
    /*End */

    /*Add Service pfu Script Start */
	$(document).on('submit','#add_pfu_form',function(e){
		$('#msg_response').html(" ");
		$("err_response").html(" ");
		$('#pfu_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
		e.preventDefault();
		$.ajax({
			url: base_url+"/admin_ajax/add_notification",
			type: "POST",        
			data: new FormData(this),
			contentType: false,
			cache: false,
			processData:false,  
			success: function(data){
				errorString = '';
				$('#pfu_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
				var parseJson = jQuery.parseJSON(data); 
				if(parseJson.status == 200){
					$("#add_pfu_form")[0].reset();	
					$("#add_new_pfu").modal('hide');
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
					$("#add_pfu_form")[0].reset();	
					$("#add_new_pfu").modal('hide');
					$("#msg_response_popup").modal('show');
					$("#msg_response").html(parseJson.msg);
				}	 
			} , 
			error: function(xhr, error){
				$('#pfu_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
				$("#response_msg").html(parseJson.msg);
			}
		});
	});

    /*End */

  

    /*Edit Service pfu */ 
    $(document).on('click','.edit_pfu',function(e){
		e.preventDefault();
		$('#id').val(" ");
		var $this = $(this);
		var id = $(this).data('id');
		$.ajax({
			url: base_url+"/admin_ajax/edit_notification_details",
			method: "GET",
			data: {id:id},
			success: function(data){
				// console.log(data);
				var parseJson = jQuery.parseJSON(data);
				if (parseJson.status == 200) {
					$("#id").val(parseJson.response.id);
					$("#notification_type").val(parseJson.response.notification_type);
					$("#target_user").val(parseJson.response.target_user);
					$("#title").val(parseJson.response.title);
					$("#subject").val(parseJson.response.subject);
					$("#content").val(parseJson.response.content);
					$("#url").val(parseJson.response.url);
					
					//$("#file").val(parseJson.response.file);
					if(parseJson.response.file)
					{
					$("#edit_image").attr('src',parseJson.response.file_url);
					}
					$("#file_url").val(parseJson.response.file_url);
					$("#myModalLabel").html('Edit Notification');
					$("#add_new_pfu").modal('show');
				}
				
			}
		});
    });
	/*End */
    /*Delete tyre pfu */
    $(document).on('click','.delete_notification',function(e){
        e.preventDefault();
		$('#id').val(" ");
		var $this = $(this);
        var id = $(this).data('id');
        var con = confirm("Are you sure want to remove this Notifications ?");
        if(con == true) {
            $.ajax({
				url: base_url+"/admin_ajax/delete_notification",
				method: "GET",
				data: {id:id},
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

});

