$(document).ready(function(e) {
	
$(document).on('click','#add_advertising',function(e){
	e.preventDefault();
	$("#myModalLabel").html('Add Advertising');
	$("#add_advertising_form")[0].reset();
	//$("#edit_advertising").remove();
	$("#id").val(" ");
	$("#description").val(" ");
	$("#add_advertising_model").modal('show');	
});

 /* Add advertising*/
 
 $(document).on('submit','#add_advertising_form',function(e){
		$("#msg_response").html('');
		$("err_response").html('');
		$("#submit_advertising").html('please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
		e.preventDefault();
		$.ajax({
			url: base_url+"/admin_ajax/manage_advertising",
			type: "POST",
			contentType: false,
			data: new FormData(this),
			cache: false,
			processData: false,
			success:function(data){
				errorString = '';
				$("#submit_advertising").html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled', false);
				var parseJson =jQuery.parseJSON(data);
				if(parseJson.status == 200){
					//alert('aff')
					$("#add_advertising_form")[0].reset();
					$("#add_advertising_model").modal('hide');
					$("#msg_response_popup").modal('show');
					$("#msg_response").html(parseJson.msg);
					setTimeout(function(){ location.reload() } ,1000);
				}
				
			if(parseJson.status == 400){
					$.each(parseJson.error, function(key , value) {
					    errorString += '<div class="notice notice-danger"><strong>Wrong , </strong>'+ value+' .</div>';
					});
				  	$('#err_response').html(errorString); 	
			   	}
				if(parseJson.status == 100){
					$("#add_advertising_form")[0].reset();	
					$("#add_advertising_model").modal('hide');
					$("#msg_response_popup").modal('show');
					$("#msg_response").html(parseJson.msg);
				}	 
				
				},
				error: function(xhr ,error){
					$('#submit_advertising').html('save <spane class="glyphicon glyphicon-plus"></span>').attr('disabled',false);					
				}
		})	 
 });
 /*edit advertising*/
 $(document).on('click','.edit_advertising',function(e){
	 e.preventDefault();
	 $('#id').val(" ");
	 $("#description").html(" ");
	 var $edit_button = $(this);
	 var id = $(this).data('id');
	 $.ajax({
		 url:base_url+'/admin_ajax/edit_manage_details',
		 data:{id:id},
		 method:"GET",
		 success:function(data){	
			var parseJson = jQuery.parseJSON(data);
			if(parseJson.status == 200){
				$('#id').val(parseJson.response.id);
				$('#title').val(parseJson.response.title);
				$('#description').val(parseJson.response.description);
				$('#add_location').val(parseJson.response.add_location);
				$('#url').val(parseJson.response.url);
				//$("#edit_image").attr('src',parseJson.response.image_url);
			//	$("#image_url").val(parseJson.response.image_url);
				$("#myModalLabel").html('Edit advertising');
				$('#add_advertising_model').modal('show');				
			} 
		 }
	 });
 });
 /*delete advertisingn*/
$(document).on('click','.delete_advertising',function(e){
	 e.preventDefault();
	 $('#id').val(" ");
	 var $delete_button = $(this);
	 var id = $(this).data('id');
	 var con = confirm("are you sure want to delete this advertising ?");	
	 if(con == true){
		$.ajax({
			url:base_url+"/admin_ajax/delete_advertising",
			data:{id:id},
			method:"GET",
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
 });
  /*Change status script start*/
 $(document).on('click','.change_status',function(e){
	e.preventDefault();
	$this = $(this);
	var id = $(this).data('id');
	var status = $(this).data('status');
	if(id != ""){
	   $.ajax({
		 url:base_url+"/admin_ajax/change_status",
		 method:"GET",
		 data:{id:id , status:status},
		 success:function(data){
			  if(status == "1"){
				 $this.html("<i class='fa fa-toggle-on'></i>").data('status' , '0');	 
			  }
			else if(status == "0"){
				$this.html("<i class='fa fa-toggle-off'></i>").data('status' , '1');;
			  }  
		 },
		error: function(xhr, error){
		    	//$('#new_group_btn').html('Save &nbsp;<i class="icon-paperplane ml-2"></i>').attr('disabled' , false);
				$("#msg_response_popup").modal('show');
				$('#msg_response').html('<div class="notice notice-danger"><strong>Wrong , </strong>Something Went Wrong please try again  .</div>');
			}
	 });
	  }
});
 /*End*/



});

