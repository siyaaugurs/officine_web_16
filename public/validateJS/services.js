function check_rows_data(action , discount_type = null){
   $(".err_msg").html(" ");	
   var weekly_days_count = $('.weekly_days:checked').length;
   $("#response_result").html(" ");
   if(weekly_days_count >  0){
	   $(".day-row").each(function(index, element) {
	        //$(this).find('.weekly_days').is(':checked');
	      if( $(this).find('.weekly_days').is(':checked') ){
			   $(this).find('.add_fields > .row').each(function(index, element) {
                  var start_date = $(this).find('#start_time').val();
				  var end_date = $(this).find('#end_time').val();
				  if(start_date != ""){
					  if(end_date != ""){
						if(start_date < end_date){
						   $("#add_services_btn").attr('disabled' , false); 
						    return false;
						  }
						 else{
						   $("#add_services_btn").attr('disabled' , true);
						  }  
					   }
					  else{
						 $("#add_services_btn").attr('disabled' , true); 
					     return false;
					   } 
					}
				  else{
					  $("#add_services_btn").attr('disabled' , true); 
					  return false;
					}	
               });
	         }
	   });
	 }
    else{
	   $("#response_result").html('<div class="notice notice-danger"><strong>Wrong , </strong> please select any one days .</div>');
	   return false;
	 }	 
}

/*function check_rows_data(action , discount_type = null){
   $(".err_msg").html(" ");	
   var weekly_days_count = $('.weekly_days:checked').length;
   $("#response_result").html(" ");
   if(weekly_days_count >  0){
	   $(".day-row").each(function(index, element) {
	        //$(this).find('.weekly_days').is(':checked');
	      if( $(this).find('.weekly_days').is(':checked') ){
			   $(this).find('.add_fields > .row').each(function(index, element) {
                  var start_date = $(this).find('#start_time').val();
				  var end_date = $(this).find('#end_time').val();
				  var price = $(this).find('#price').val();
				  if(start_date != ""){
					  if(end_date != ""){
						if(start_date < end_date){
						   if(discount_type != null){
							  if(discount_type != 0){
								var discount_price = $(this).find('#discount').val();
								if(discount_price != ""){
								  	$("#add_services_btn").attr('disabled' , false);
								  }
								else{
							      $("#add_services_btn").attr('disabled' , true);
								  }  
							  } 
							  else{
								$("#add_services_btn").attr('disabled' , true); 
							    return false;
							  }
							} 
							 else{
								$("#add_services_btn").attr('disabled' , false); 
							    return false;
							  } 
						  }
						 else{
						   $("#add_services_btn").attr('disabled' , true);
						  }  
					   }
					  else{
						 $("#add_services_btn").attr('disabled' , true); 
					     return false;
					   } 
					}
				  else{
					  $("#add_services_btn").attr('disabled' , true); 
					  return false;
					}	
               });
	         }
	   });
	 }
    else{
	   $("#response_result").html('<div class="notice notice-danger"><strong>Wrong , </strong> please select any one days .</div>');
	   return false;
	 }	 
}
*/

$(document).ready(function(e) {
    /*Remove Service Script start*/
	$(document).on('click','.remove_services',function(e){
	   	e.preventDefault();
		 $this = $(this);
		 var serviceid = $(this).data('serviceid');
		 var con  = confirm("Are you sure want to remove this services .");
		 if(con == true){
				$.ajax({
				url: base_url+"/services/remove_services",
				type: "GET",        
				data:{serviceid:serviceid},
				 success: function(data){
				   if(data == 1){
					   location.reload();
					   //$this.parent().parent().find('tr').remove();
					  }
				 } , 
				 error : function(xhr , error){
				    alert("Something went wrong please try again !!! ")
				 }
			 });
		 }
	});
	/*End */
    	/*edit_packages_form script start*/
	$(document).on('submit','#service_coupon_form',function(e){
	   // alert("yes");
   	$('#response_about_pakages').html(" ");
		$('#coupon_sbmt').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
		e.preventDefault();
			 $.ajax({
					 url: base_url+"/service_ajax/add_service_coupon",
					 type: "POST",        
					 data: new FormData(this),
					 contentType: false,
					 cache: false,
					 processData:false,  
					 success: function(data){
              var errorString = '';
              var parseJson = jQuery.parseJSON(data);
				    $('#coupon_sbmt').html(' Save &nbsp;<i class="icon-paperplane ml-2"></i> ').attr('disabled' , false);
				   	    if(parseJson.status == 400){
							 $.each(parseJson.error, function(key , value) {
							   errorString += '<div class="notice notice-success"><strong>Success , </strong>'+ value+' .</div>';
							 });
							 $('#response_about_pakages').html(errorString); 	
						 }
						 if(parseJson.status == 200){
							$("#msg_response_popup").modal('show');
							$("#msg_response").html(parseJson.msg);
							$(".close").click();
							$("#service_coupon_form")[0].reset();
						}
						 if(parseJson.status == 100){
							$("#msg_response_popup").modal('show');
						   $("#msg_response").html(parseJson.msg);
						 }  
                }	
			});
	 });
	 /*End*/
    
	/*Remove Service Image script start*/
	  $(document).on('click','.remove_service_image',function(e){
      e.preventDefault();
	  var con = confirm("Are you sure want to delete this image");
	  if(con == true){
		var delete_id = $(this).data('imageid');
		  $.ajax({
			 url: base_url+"/services/remove_service_image",
			 type: "GET",        
			 data:{delete_id:delete_id},
			 success: function(data){
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
	/*End*/
	   /*uPLOAD cATEGORY iMAGES*/
      $(document).on('submit','#uplolad_car_wash_service_images',function(e){
		$('#response_msg').html(" ");
		$('#save_image').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
		e.preventDefault();
			 $.ajax({
					 url: base_url+"/service_ajax/upload_category_image",
					 type: "POST",        
					 data: new FormData(this),
					 contentType: false,
					 cache: false,
					 processData:false,  
					 success: function(data){
						$('#save_image').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
						var parseJson = jQuery.parseJSON(data); 
						if(parseJson.status == 200){
						   $("#uplolad_car_wash_service_images")[0].reset();	
						   $("#msg_response_popup").modal('show');
						   $("#msg_response").html(parseJson.msg);
						  $('#image_grid_section').load(document.URL + ' #image_grid_section');		
						  }
						else{
							$("#response_msg").html(parseJson.msg);
						  }	
					 } , 
					 error: function(xhr, error){
                        $('#save_image').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
					  }
			});
	 });
	/*End*/
	/**Remove Package script start */
	$(document).on('click','.delete_pakages',function(e){
		e.preventDefault();
		 var packagesid = $(this).data('packagesid');
		 var service_weekly_days_id = $(this).data('serviceweeklydays');
		 var con  = confirm("Are you sure want to remove this package .");
		 if(con == true){
				$.ajax({
				url: base_url+"/services/remove_packages",
				type: "GET",        
				data:{packagesid:packagesid , service_weekly_days_id:service_weekly_days_id},
				 success: function(data){
				   var parseJson = jQuery.parseJSON(data)
					if(parseJson.status == 200){
					  $('#services_week_section').load(document.URL + ' #services_week_section');  
					   }
					 $("#msg_response_popup").modal('show');
					 $("#msg_response").html(parseJson.msg);
				 }
			 });
		 }
	});
	
	/**End */
	/*Remove services  Weekly days */
	 $(document).on('click','.delete_services_days',function(e){
	   e.preventDefault();
       var services_days_id = $(this).data('servicedaysid');
	   console.log(services_days_id);
	    var con  = confirm("Are you sure want to remove this .");
	    if(con == true){
		     $.ajax({
			   url: base_url+"/services/remove_services_days",
			   type: "GET",        
			   data:{services_days_id:services_days_id},
			   success: function(data){
				 var parseJson = jQuery.parseJSON(data);
				 if(parseJson.status == 200){
					$('#services_week_section').load(document.URL + ' #services_week_section');  
					}
					$("#msg_response_popup").modal('show');
					$("#msg_response").html(parseJson.msg); 
				 }
		    });
		  }
   });
	/*End*/
	/*Edit About Services*/
	/*Save About workshop vscript start*/
     $(document).on('click','#edit_about_services_btn',function(e){
	    e.preventDefault();
		var con = confirm("Are you sure want to edit .");
		if(con == true){
		  $('#edit_about_services_btn').html('Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
		  var about_services = $("#about_services").val();
		  var services_id = $("#services_id").val();
		  //var average_time = $("#service_average_time").val();
		  //  var car_size = $("#car_size").val();
			if(about_services != "" || services_id != ""){
				$.ajax({
				  url:base_url+"/services/edit_about_services",
				  type:"GET",
				  data:{about_services:about_services , services_id:services_id },
				  success: function(data){
					$(".close").click();
					$('#edit_about_services_btn').html('Save &nbsp;<i class="icon-paperplane ml-2"></i>').attr('disabled' , false);  
					$('#about_service_section').load(document.URL + ' #about_service_section');
					$("#msg_response_popup").modal('show');
	            	$("#msg_response").html(data);
			  }
				});  
			  }
		  }
	});
  /*End*/
	/*End*/
   /**Services add custom validation */
	if(check_rows_data() == false){
	   $("#add_services_btn").attr('disabled' , true); 
	 }
  /*End */
  
  /*Edit Services package*/
  $(document).on('submit','#edit_packages_form',function(e){
	 $('#add_services_btn').html('Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
    daysData = []
    $('#edit_packages_form .day-row').each(function() {
      day_id = $(this).find('.weekly_days').val();
      checked = $(this).find('.weekly_days').prop('checked');
      records = [];
      $(this).find('.add_fields > .row').each(function() {
        records.push({start_time:$(this).find('#start_time').val(),end_time:$(this).find('#end_time').val(),price:$(this).find('#price').val()
         , max_appointment:$(this).find('#maximum_appointment').val() })
      })
      daysData.push({day:day_id,selected:checked,records:records})
    })
    var service_id = $("#services_id").val();
	var category_id = $("#category_id").val();
    e.preventDefault();
       $.ajax({
           url: base_url+"/services/edit_services",
           type: "POST",        
           data: {daysData:daysData , service_id:service_id , category_id:category_id},
           dataType: 'json',
          success: function(data){
            $('#add_services_btn').html('Save &nbsp;<i class="icon-paperplane ml-2"></i> ').attr('disabled' , false);
			$(".close").click();
            $("#msg_response_popup").modal('show');
            $("#msg_response").html(data.msg); 
			if(data.status == 200){
			    setTimeout(function(){ location.reload(); } , 1000); 
			   }
		  }	,
          error: function(xhr, error){
            $('#add_services_btn').html('Save &nbsp;<i class="icon-paperplane ml-2"></i> ').attr('disabled' , false);
            $("#edit_response").html('<div class="notice notice-danger"><strong>Wrong </strong>Something Wrong , please try again . !!! </div>');
          }
      });
  });
  /*End*/
  /*Add Services Script start*/
    /*$(document).on('submit','#add_services_form',function(e){
      $('#response_add_category').html(" ");
      $('#add_services_btn').html('Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
    daysData = []
    $('#add_services_form .day-row').each(function() {
      day_id = $(this).find('.weekly_days').val();
      checked = $(this).find('.weekly_days').prop('checked');
      records = [];
      $(this).find('.add_fields > .row').each(function() {
        records.push({start_time:$(this).find('#start_time').val(),end_time:$(this).find('#end_time').val(),price:$(this).find('#price').val() ,  max_appointment:$(this).find('#maximum_appointment').val()})
      })
      daysData.push({day:day_id,selected:checked,records:records})
    })
   var about_data = $("#about_services").val();
   var service_id = $("#service_id").val(); 
   var service_average_time = $("#service_average_time").val();
   var car_size = $("#car_size").val();
    
    e.preventDefault();
       $.ajax({
           url: base_url+"/services/add_services",
           type: "POST",        
           data: {daysData:daysData , about_data:about_data , service_id:service_id , service_average_time:service_average_time , car_size:car_size},
           dataType: 'json',
           success: function(data){
            $('#add_services_btn').html(' Submit &nbsp;<i class="icon-paperplane ml-2"></i> ').attr('disabled' , false);
            $("#msg_response_popup").modal('show');
            $("#msg_response").html(data.msg); 
		      if(data.status == 200){
			    setTimeout(function(){ location.reload(); } , 1000); 
			   }
          }	,
          error: function(xhr, error){
            $('#add_services_btn').html(' Submit &nbsp;<i class="icon-paperplane ml-2"></i> ').attr('disabled' , false);
			$("#msg_response_popup").modal('show');
            $("#msg_response").html('<div class="notice notice-danger"><strong>Wrong </strong>Something Wrong , please try again . !!! </div>');
          }
      });
  });*/
  /*End*/  
  /* Add Services Script start*/
  /*	$(document).on('submit','#add_services_form',function(e){
		$('#response_add_category').html(" ");
		$('#add_services_btn_copy').html('Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
		e.preventDefault();
		$.ajax({
			url: base_url+"/services/add_services_new",
			type: "POST",   
			data: new FormData(this),
			contentType: false,
			cache: false,
			processData:false,    
			success: function(data){
				$('#add_services_btn_copy').html(' Submit &nbsp;<i class="icon-paperplane ml-2"></i> ').attr('disabled' , false);
				var parseJson = jQuery.parseJSON(data);
				if(parseJson.status == 200){
					$("#add_services_form")[0].reset();
					$("#add_car_washing_services").modal('hide');
					$("#msg_response_popup").modal('show');
					$("#msg_response").html(parseJson.msg);
					setTimeout(function(){ location.reload(); } , 1000);
				}
				if(parseJson.status == 100) {
					$("#add_services_form")[0].reset();
					$("#add_car_washing_services").modal('hide');
					$("#msg_response_popup").modal('show');
					$("#msg_response").html(parseJson.msg);
				}	
			} , 
			error: function(xhr, error){
				$('#add_services_btn_copy').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
				$("#response_msg").html(parseJson.msg);
			}
      	});
	});*/
  /*End*/
  /*Search By Category Script Start */
	$(document).on('change','#search_washing_category',function(e){
		var category_id = $("#search_washing_category").val(); 
			e.preventDefault();
			$.ajax({
				url:base_url+"/workshop_ajax/search_by_category",
				method:"GET",
				data:{category_id:category_id},
				complete:function(e , xhr){
					console.log(e);
					// $('#search_products_by_group').html('Search &nbsp;<span class="glyphicon glyphicon-search"></span>').attr('disabled' , true);
					if(e.status == 200){
						$("#user_data_body").html(e.responseText);
					}
				}
			});
	});
	/*End */
});