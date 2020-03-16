function save_mot_services(version_id){
  $("#preloader").show();  
  $("#service_shedule").empty();
   version_id = $("#version_id").val();
   var language = $('html').attr('lang');
   $.ajax({
			url:base_url+"/mot_services/save_services",
			method:"GET",
			data:{version_id:version_id , language:language},
			complete:function(e, xhr, settings){
				$("#preloader").hide();  
			    if(e.status == 200){
					var parseJson = jQuery.parseJSON(e.responseText);  
					if(parseJson.status == 200){
					    	$("#service_shedule").append($('<option>' ,{value:0 }).text('--Select--Service--Schedule--'));
					    $.each(parseJson.response , function(index , value){
					        if(value.service_schedule_description != ""){
							     text = value.service_schedule_id +"( "+value.service_schedule_description+" ) ";
							   }
							else{
							    text = value.service_schedule_id
							  }
					    	$("#service_shedule").append($('<option>' ,{value:value.id}).text(text));
						});	  
					  }
					if(parseJson.status == 404){
					    $("#service_shedule").append($('<option>' ,{value:0}).text('--No--Record--Found--')); 
					  }  
				}
			 },
			error: function(xhr, error){
				$("#service_shedule").append($('<option>' ,{value:0}).text('--No--Record--Found--')); 
		      $("#preloader").hide();
		    }
		});
}
/*Get and save service interval script start */
function get_save_service_interval(){
	$("#preloader").show();  
   service_shedule = $("#service_shedule").val();
   var language = $('html').attr('lang');
    $.ajax({
			url:base_url+"/mot_services/save_services_interval",
			method:"GET",
			data:{service_shedule:service_shedule , language:language},
			complete:function(e, xhr, settings){
				$("#preloader").hide();  
			 },
			error: function(xhr, error){
			  $("#service_shedule").append($('<option>' ,{value:0}).text('--No--Record--Found--')); 
		      $("#preloader").hide();
		    }
		});
}
/*End*/
$(document).ready(function(e) {
/*Set time script start*/
  $(document).on('submit','#our_mot_services',function(e){
		$('#our_mot_services_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
		e.preventDefault();
		$.ajax({
				url: base_url+"/mot_services/add_our_mot_services",
				type: "POST",        
				data: new FormData(this),
				contentType: false,
				cache: false,
				processData:false,  
				success: function(data){
				  var parseJson = jQuery.parseJSON(data); 
					errorString = '';
					if(parseJson.status == 400){
						$.each(parseJson.error, function(key , value) {
							errorString += '<div class="notice notice-danger"><strong>Note , </strong>'+ value+' .</div>';
						});
						$('#err_response').html(errorString); 
					 }
					if(parseJson.status == 200){
					   $("#msg_response_popup").modal('show');
					   $("#msg_response").html(parseJson.msg);
					   setTimeout(function(){ location.reload(); } , 1000); 
					 }
					if(parseJson.status == 100){
					   $('#err_response').html(errorString); 
					 } 
				},
				error: function(xhr, error){
				   $('#err_response').html('<div class="notice notice-danger"><strong>Wrong </strong>Something Wrong , please try again . !!! </div>'); 
				},
				complete: function(e,xhr , setting){
					$('#our_mot_services_btn').html('Submit <i class="icon-paperplane ml-2"></i>').attr('disabled' , false);
				}	
		});
	});
/*End*/	
    
    $(document).on('change','#version_id',function(e){
		e.preventDefault();	
		var version_id = $('#version_id').val();
		if(version_id == 0) {
			$('#new_service_schedule').css("display", "none");
		} else {
			$('#new_service_schedule').css("display", "block");
		}
	});
	$(document).on('click', '#new_service_schedule', function(e){
		e.preventDefault();	
		var version_id = $('#version_id').val();
		if(version_id != 0) {
			$('#add_new_service_schedule').modal('show');
		} 
	});
	$(document).on('change','#service_shedule',function(e){
		e.preventDefault();	
		var service_shedule = $('#service_shedule').val();
		action = $(this).data('action');
		if(action == "get_save_interval"){
			 get_save_service_interval();
		  }
		
		if(service_shedule == 0) {
			$('#add_new_interval').css("display", "none");
		} else {
			$('#add_new_interval').css("display", "block");
		}
	});
	$(document).on('click', '#add_new_interval', function(e){
		e.preventDefault();	
		var service_shedule = $('#service_shedule').val();
		if(service_shedule != 0) {
			$('#add_new_mot_interval').modal('show');
		} 
	});
	$(document).on('click', '#add_interval_operation', function(e){
		e.preventDefault();	
			$('#add_new_operation_interval').modal('show');
	});
   $(document).on('change','#version_id',function(e){
      action = $(this).data('action');
        version_id = $(this).val();
	  if(action == "save_mot_service"){
		   save_mot_services(version_id);
		}
   });
   
  $(document).on('click','#search_mot_services',function(e){
	$("#preloader").show();  
	 e.preventDefault();
	 service_schedule_id = $("#service_shedule").val();
	 var language = $('html').attr('lang');
	 if(service_schedule_id != 0){
	     $.ajax({
				url:base_url+"/mot_services/get_services_interval",
				method:"GET",
				data:{service_schedule_id:service_schedule_id , language:language},
				complete:function(e, xhr, settings){
					$("#preloader").hide();  
					if(e.status == 200){
					   $("#mot_interval_body").html(e.responseText);
					}
				 },
				error: function(xhr, error){
				  $("#mot_interval_body").html("No record found");
				  $("#preloader").hide();
				}
			});
	   }
  });
 /* $(document).on('change','#service_shedule',function(){
     action = $(this).data('action');
	 if(action == "get_save_interval"){
	      get_save_service_interval();
	   }
  });*/
  
});