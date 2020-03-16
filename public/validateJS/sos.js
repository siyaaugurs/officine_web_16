function show_sos_image(cat_id){
    if(cat_id != ""){
        $("#category_id").val(cat_id);
        $.ajax({
            url: base_url+"/sos_ajax/get_sos_image",
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
        $('#add_sos_category_popup').modal('show');
    });
    /*End */

    /*Submit sos category form */
    $(document).on('submit','#add_sos_category_form',function(e){
        $('#response').html(" ");
		$("err_response").html(" ");
// 		$('#add_sos_category_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
        e.preventDefault();
        $.ajax({
            url: base_url+"/sos_ajax/add_sos_category",
            type: "POST",        
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData:false,  
            success: function(data){
                errorString = '';
                // $('#add_sos_category_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
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
    $(document).on('click','.edit_sos_category',function(e){
        e.preventDefault();
        $('#sos_category_id').val(" ");
		var $this = $(this);
		var category_id = $(this).data('categoryid');
        $.ajax({
			url: base_url+"/sos_ajax/get_category_details",
			method: "GET",
			data: {categoryId:category_id},
			success: function(data){
				var parseJson = jQuery.parseJSON(data);
				if (parseJson.status == 200) {
					$("#sos_category_id").val(parseJson.response.id);
					$("#category_name").val(parseJson.response.category_name);
					$("#description").val(parseJson.response.description);
					$('#status').find("option[value='"+ parseJson.response.status +"']").attr('selected','selected');
                    $('#priority').find("option[value='"+ parseJson.response.priority +"']").attr('selected','selected');
                    $("#categotry_image").val(parseJson.response.cat_images);
					$("#myModalLabel").html('Edit SOS Category');
					$("#add_sos_category_popup").modal('show');
				}
			}
        });
    });
    /*End */

    /*Change SoS category status */
    $(document).on('click','.change_sos_category_status',function(e){
        e.preventDefault();
        var $this = $(this);
        var status = $(this).data('status');
        var category_id = $(this).data('categoryid');
        var con = confirm("Are you sure want to Change Status ?");
        if(con == true) {
            $.ajax({
				url: base_url+"/sos_ajax/change_sos_category_status",
				type: "GET",        
				data:{status:status , categoryId:category_id},
				success: function(data){
					 if(status == 1){
                        $this.html(" <i class='fa fa-toggle-on'></i>").data('status' , 0);
                        
                    }
					if(status == 0){
                        $this.html(" <i class='fa fa-toggle-off'></i>").data('status' , 1);
                        
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
                url: base_url+"/sos_ajax/remove_image",
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
		// $('#save_image').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
		var category_id = $("#category_id").val();
		e.preventDefault();
        $.ajax({
            url: base_url+"/sos_ajax/upload_category_image",
            type: "POST",        
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData:false,  
            success: function(data){
                // $('#save_image').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
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
                // $('#save_image').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
                $("#response_msg").html(parseJson.msg);
            }
        });
    });
    /*End */
});