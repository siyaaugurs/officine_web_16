  function calculate_price(hourly_rate , time){
	   return hourly_rate * time; 
	}
  function check_correct_data(type = null , time = null){
	  var hourly_rate = $('#hourly_rate').val();
	  $("#hourly_rate_err").html("");
	  if(hourly_rate != ""){
		 var sub_total_price = calculate_price(hourly_rate , time)
		  if(type == 1){
			$("#small_price").val(sub_total_price);
			}
		  else if(type == 2){
			$("#average_price").val(sub_total_price);
			}
		   else if(type == 3){
			 $("#big_price").val(sub_total_price);
			}
		   $("#hourly_rate_err").html(" ");
		   //$("#add_services_btn_copy").attr('disabled' , false);			
		 }
	  else{
		$("#hourly_rate_err").html("Please first enter the price service hourly rate .");    		//$("#add_services_btn_copy").attr('disabled' , true);
		}	
   }
$(document).ready(function(e) {
   
     /*Add car wash revision Services Modal Popup */
    $(document).on('click','#add_car_revision_details',function(e){
        e.preventDefault();
        $("#add_car_revision_details").modal('show');
    });
    /*End */
		  // $("#add_services_btn_copy").attr('disabled' , true);
		   /*Add Discount daily in all*/
		    $(document).on('change','#special_time_slot_type',function(){
		       time_slot_type = $(this).val();
			   if(time_slot_type == 1){
				   $('.special_slot').find('option[value="1"]').attr('selected','selected');
				 }
			   else{
				    $('.special_slot').find('option[value="1"]').attr('selected', false);
				 }	 
			});
		  /*End*/
/*Add Car Revision details script start*/
$(document).on('click','#car_revision_details',function(e){
     e.preventDefault();
  $("#add_car_revision_details_popup").modal('show');
});	

$(document).on('click', '#add_car_revision_details_btn', function(e) {
          var btn_html = $("#add_car_revision_details_btn").html();
          $('#add_car_revision_details_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
          e.preventDefault();
          max_appointment = $("#max_appointment").val();
          price = $("#price").val();
          $.ajax({
              url: base_url + "/car_revision/workshop_car_revision_details",
              type: "GET",
              data: { max_appointment: max_appointment, price: price },
              complete: function(e, xhr, setting) {
                  $('#add_car_revision_details_btn').html(btn_html).attr('disabled', false);
                  var errorString = '';
                  if (e.status == 200) {
                      var parseJson = jQuery.parseJSON(e.responseText);
                      if (parseJson.status == 200) {
                          $("#add_services_form")[0].reset();
                          $('.close').click();
                          $("#msg_response_popup").modal('show');
                          $("#msg_response").html(parseJson.msg);
                          setTimeout(function() { location.reload(); }, 1000);
                      }
                      if (parseJson.status == 400) {
                          $.each(parseJson.error, function(key, value) {
                              errorString += '<div class="notice notice-danger"><strong>Wrong , </strong>' + value + ' .</div>';
                          });
                          $("#msg_response_popup").modal('show');
                          $('#msg_response').html(errorString);
                      }
                      if (parseJson.status == 100) {
                          $("#response_err").html(parseJson.msg);
                      }
                  }
              },
              error: function(xhr, error) {
                  $('#add_services_btn_copy').html(btn_html).attr('disabled', false);
                  $("#response_err").html('<div class="notice notice-danger"><strong>Wrong </strong>Something Wrong , please try again . !!! </div>');
              }
          });
      });

/*End*/		  
		  
/*Add car wash Workshop details */
$(document).on('click', '#add_services_btn_copy', function(e) {
          var btn_html = $("#add_services_btn_copy").html();
          $('#add_services_btn_copy').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
          e.preventDefault()
          hourly_rate = $("#hourly_rate").val();
          max_appointment = $("#max_appointment").val();
          $.ajax({
              url: base_url + "/car_wash/workshop_carwash_details",
              type: "GET",
              data: { hourly_rate: hourly_rate, max_appointment: max_appointment },
              complete: function(e, xhr, setting) {
                  $('#add_services_btn_copy').html(btn_html).attr('disabled', false);
                  errorString = '';
                  if (e.status == 200) {
                      var parseJson = jQuery.parseJSON(e.responseText);
                      if (parseJson.status == 200) {
                          $("#add_services_form")[0].reset();
                          $('.close').click();
                          $("#msg_response_popup").modal('show');
                          $("#msg_response").html(parseJson.msg);
                          setTimeout(function() { location.reload(); }, 1000);
                      }
                      if (parseJson.status == 100) {
                          $("#response_err").html(parseJson.msg);
                      }
                      if (parseJson.status == 400) {
                          $.each(parseJson.error, function(key, value) {
                              errorString += '<div class="notice notice-danger"><strong>Wrong , </strong>' + value + ' .</div>';
                          });
                          $("#msg_response_popup").modal('show');
                          $('#msg_response').html(errorString);
                      }
                  }
              },
              error: function(xhr, error) {
                  $('#add_services_btn_copy').html(btn_html).attr('disabled', false);
                  $("#response_err").html('<div class="notice notice-danger"><strong>Wrong </strong>Something Wrong , please try again . !!! </div>');
              }
          });
      });	
	/*Edit Service Details */
	$(document).on('click', '.edit_service_details', function(e){
		e.preventDefault();
		var service_id = $(this).data('serviceid');
		size = $(this).data('size');
		$("#washing_hourly_rate").val("");
		$("#washing_max_appointment").val("");
		$("#washing_service_id").val("");
		 $('#car_size').val("");
		if(service_id != 0){
			$.ajax({
			   url: base_url+"/car_wash/get_service_details",
			   type: "GET",        
			   data: {service_id:service_id , size:size},
			   	success: function(data){
					var parseJson = jQuery.parseJSON(data);
					if(parseJson.status == 200){
						$("#washing_hourly_rate").val(parseJson.response.hourly_rate);
						$("#washing_max_appointment").val(parseJson.response.max_appointment);
					}
					$('#car_size').val(size);
					$('#washing_service_id').val(service_id);
					$("#edit_service_details_popup").modal({
					                                      backdrop:'static' , 
														  keyboard:false 
														  });
			   	}
		   	});
		}
	});
	/*End */
	$(document).on('submit', '#edit_services_form', function(e) {
          $('#response').html(" ");
          $("err_response").html(" ");
          $('#edit_services_btn_copy').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
          e.preventDefault();
          $.ajax({
              url: base_url + "/car_wash/edit_service_details",
              type: "POST",
              data: new FormData(this),
              contentType: false,
              cache: false,
              processData: false,
              success: function(data) {
                  errorString = '';
                  $('#edit_services_btn_copy').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled', false);
                  var parseJson = jQuery.parseJSON(data);
                  if (parseJson.status == 200) {
                      $(".close").click();
                      $("#msg_response_popup").modal('show');
                      $("#msg_response").html(parseJson.msg);
                      setTimeout(function() { location.reload(); }, 1000);
                  }
                  if (parseJson.status == 400) {
                      $.each(parseJson.error, function(key, value) {
                          errorString += '<div class="notice notice-danger"><strong>Wrong , </strong>' + value + ' .</div>';
                      });
                      $("#msg_response_popup").modal('show');
                      $('#msg_response').html(errorString);
                  }
                  if (parseJson.status == 100) {
                      $("#response").html(parseJson.msg);
                  }
              }
          });
      });
/*End*/		  
	 /*Post Add services script start*/
	 /* $(document).on('submit','#add_services_form',function(e){
		 e.preventDefault();
		 category_id = $("#category_id").val();
		 hourly_rate = $("#hourly_rate").val();
		 var btn_html = $("#add_services_btn_copy").html();
		 $('#add_services_btn_copy').html('Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
		 dataArr = [];
		 small_car_size = {'car_size':1 , 'price':$("#small_price").val() , 'time':$("#small_time").val() , 'appointment':$("#max_appointment").val()} 
		
		 average_car_size = {'car_size':2 , 'price':$("#average_price").val() , 'time':$("#average_time").val(),'appointment':$("#max_appointment").val()} 
		
		 big_car_size = {'car_size':3 , 'price':$("#big_price").val() , 'time':$("#big_time").val() , 'appointment':$("#max_appointment").val()} 
		dataArr.push(small_car_size);
		dataArr.push(average_car_size);
		dataArr.push(big_car_size);
		 $.ajax({
				url: base_url+"/services/add_car_wash_services",
				type: "POST",        
				data: {category_id:category_id , about_services:$("#about_services").val(),hourly_rate:hourly_rate,dataArr:dataArr},
				complete: function(e , xhr , setting){
					$('#add_services_btn_copy').html(btn_html).attr('disabled' , false);
				   if(e.status == 200){
						var parseJson = jQuery.parseJSON(e.responseText);
						if(parseJson.status == 200){
							  $("#add_services_form")[0].reset();	
							  $('.close').click();
							  $("#msg_response_popup").modal('show');
							  $("#msg_response").html(parseJson.msg); 
						  }
						 if(parseJson.status == 100){
							  $("#response_err").html(parseJson.msg); 
						  }  
					 }
				},
				error: function(xhr, error){
				   $('#add_services_btn_copy').html(btn_html).attr('disabled' , false);
				   $("#response_err").html('<div class="notice notice-danger"><strong>Wrong </strong>Something Wrong , please try again . !!! </div>');
				  }
			});
		
	  });*/
  /*End*/ 
 $(document).on('change','#washing_category',function(){
	category_id = $(this).val();
	 $('#big_time').val('');
	 $("#average_time").val('');
	 $("#small_time").val('');
	 $("#small_price").val('');
	 $("#average_price").val('');									    
	 $("#big_price").val('');
	if(category_id != 0){
		 $.ajax({
			url: base_url+"/commonAjax/getTimePrice",
			type: "GET",        
			data: {category_id:category_id},
			complete: function(e , xhr , setting){
				var parseJson = jQuery.parseJSON(e.responseText);
				if(e.status == 200){
				  
					   if(parseJson.status == 200){
							 $('#big_time').val(parseJson.response.big_time);
							 $("#average_time").val(parseJson.response.average_time);
							 $("#small_time").val(parseJson.response.small_time);
							 $("#small_price").val(parseJson.response.small_price);
							 $("#average_price").val(parseJson.response.average_price);									 $("#big_price").val(parseJson.response.big_price);
						  }
					   //console.log(parse_json.response);
				  }
			}
		});
	  }
 });  
  /*Edit CAr Revision Services */
   $(document).on('click','.edit_car_revision_services',function(e){
		e.preventDefault();
		var service_id = $(this).data('id');
		var price = $(this).data('price');
		var appointment = $(this).data('appointment');
		$(".card-body #service_id").val(service_id);
		$(".card-body #service_price").val( price );
		$(".card-body #service_appointment").val( appointment );
		$("#edit_services").modal('show');
	});
   /*End */
   /*Submit Car Revision Service form */
	$(document).on('submit', '#edit_revision_services_form', function(e){
		e.preventDefault();
		$('#msg_response').html(" ");
		$("err_response").html(" ");
		$('#add_service_details_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
		$.ajax({
			url: base_url+"/vendor/edit_car_revision_services",
			type: "POST",        
			data: new FormData(this),
			contentType: false,
			cache: false,
			processData:false,  
			success: function(data){
				errorString = '';
				$('#add_service_details_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
		 	   var parseJson = jQuery.parseJSON(data); 
				if(parseJson.status == 200){
					$("#edit_revision_services_form")[0].reset();	
					$("#edit_services").modal('hide');
					$("#msg_response_popup").modal('show');
					$("#msg_response").html(parseJson.msg);
					setTimeout(function(){ location.reload(); } , 1000);
				}  
				if(parseJson.status == 400){
					$.each(parseJson.error, function(key , value) {
					  errorString += '<div class="notice notice-danger"><strong>Wrong , </strong>'+ value+' .</div>';
					});
				  	$("#msg_response_popup").modal('show');
					$("#msg_response").html(errorString); 	
			   	}
				if(parseJson.status == 100){
					$("#add_service_group_form")[0].reset();	
					$("#add_new_service_group").modal('hide');
					$("#msg_response_popup").modal('show');
					$("#msg_response").html(parseJson.msg);
				}	 
			} , 
			error: function(xhr, error){
				$('#add_service_details_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
				$("#response_msg").html(parseJson.msg);
			}
		});
	})
   /*End */
});
