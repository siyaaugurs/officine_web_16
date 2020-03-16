function show_car_wash_image(cat_id){
    if(cat_id != ""){
	    $("#category_id").val(cat_id);
			$.ajax({
			url: base_url+"/master_agax/get_car_wash_image",
			method: "GET",
			data: {category_id:cat_id},
			success: function(data){
			  $('#image_result').html(data);
			  $('#add_car_wash_image_popup').modal({
				  backdrop:'static',
				  keyboard:false,
			  }
			  );
			}
	  });

	}	
}
$(document).ready(function(e) {/*Users Search Script start */
	$(document).on('click','#search_users_btn',function(e){
		e.preventDefault();
		$('#search_users_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
		var users_id = $("#users_id").val();
		$.ajax({
			url:base_url+"/admin_ajax/search_users",
			method:"GET",
			data:{usersId:users_id},
			complete:function(e , xhr){$('#search_users_btn').html('Save <span class="glyphicon glyphicon-search"></span>').attr('disabled' , false);
				console.log(e);
				if(e.status == 200){
					$("#user_data_body").html(e.responseText);
				}
			}
		});
	});
	/*End */

    	/*Edit  business Form by admin*/

	$(document).on('submit','#customer_details_form_admin',function(e){
		$('#response').html(" ");
		$("err_response").html(" ");
		$('#customer_details_sbmt').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);

		e.preventDefault();

			$.ajax({

				url: base_url+"/profile/edit_customer_details",

				type: "POST",        

				data: new FormData(this),

				contentType: false,

				cache: false,

				processData:false,  

				success: function(data){

					errorString = '';

					$('#customer_details_sbmt').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);

					var parseJson = jQuery.parseJSON(data); 

					if(parseJson.status == 200){

					$(".close").click();	

						$("#msg_response_popup").modal('show');

						$("#msg_response").html(parseJson.msg);

					}

					if(parseJson.status == 400){

						$.each(parseJson.error, function(key , value) {

							errorString += '<div class="notice notice-danger"><strong>Wrong , </strong>'+ value +' .</div>';

						});

						$('#response').html(errorString); 	

					}  

					if(parseJson.status == 100){

						$("#response").html(parseJson.msg);

					}	 

				} 

			});

	   });

/**End */

    /*Edit Bank Details script start*/

	$(document).on('submit','#bank_details_form_admin',function(e){

     $('#response_bank_details').html(" ");

	 $('#bank_details_sbmt').html('Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);

	 e.preventDefault();

      $.ajax({

          url: base_url+"/profile/edit_bank_details",

          type: "POST",        

          data: new FormData(this),

          contentType: false,

          cache: false,

          processData:false,  

          success: function(data){

            var errorString = '';

			var parseJson = jQuery.parseJSON(data);

			$('#bank_details_sbmt').html(' Submit <i class="icon-paperplane ml-2"></i>').attr('disabled' , false);	

			 if(parseJson.status == 400){

				  $.each(parseJson.error, function(key , value) {

					 errorString += '<div class="notice notice-danger"><strong> Wrong , </strong>'+ value+' .</div>';

				   });

				 $("#msg_response_popup").modal('show');

				 $("#msg_response").html(errorString);

              }

			 else if(parseJson.status == 200){

			    $("#msg_response_popup").modal('show');

				$("#msg_response").html(parseJson.msg);

				

				//setTimeout(function(){ window.location.href  = base_url+"/bank_details" } , 1000);

			  }

			 else if(parseJson.status == 100){

			    $("#msg_response_popup").modal('show');

				$("#msg_response").html(parseJson.msg);

			  } 

         }	

     });

  });

 /*End */

  	

/*Edit  business Form by admin*/

$(document).on('submit','#business_details_form_admin',function(e){

	$('#response').html(" ");

	$("err_response").html(" ");

 	$('#business_details_sbmt').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);

		  e.preventDefault();

			   $.ajax({

					   url: base_url+"/profile/edit_business_details",

					   type: "POST",        

					   data: new FormData(this),

					   contentType: false,

					   cache: false,

					   processData:false,  

					   success: function(data){

						  errorString = '';

						  //$('#business_details_sbmt').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);

						  var parseJson = jQuery.parseJSON(data); 

						  if(parseJson.status == 200){

							$(".close").click();	

							 //$("#business_details_form_admin")[0].reset();	

							 $("#msg_response_popup").modal('show');

							 $("#msg_response").html(parseJson.msg);
							 setTimeout(function(){ location.reload() } ,1000);

							}

						  if(parseJson.status == 400){

								   $.each(parseJson.error, function(key , value) {

								  errorString += '<div class="notice notice-danger"><strong>Wrong , </strong>'+ value +' .</div>';

								});

							  $('#response').html(errorString); 	

						   }  

						  if(parseJson.status == 100){

							  $("#response").html(parseJson.msg);

							}	 

					   } , 

					   error: function(xhr, error){

						  //$('#business_details_sbmt').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);

						 //$("#response_msg").html(parseJson.msg);

			}

			  });

	   });

/**End */

/*Edit Category Script start*/

$(document).on('submit','#edit_car_wash_cat',function(e){

  $('#msg_response').html(" ");

  $("err_response").html(" ");

  $('#edit_car_wash_cat_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);

		e.preventDefault();

			 $.ajax({

					 url: base_url+"/master/edit_category",

					 type: "POST",        

					 data: new FormData(this),

					 contentType: false,

					 cache: false,

					 processData:false,  

					 success: function(data){

					    errorString = '';

					    $('#edit_car_wash_cat_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);

						var parseJson = jQuery.parseJSON(data); 

						if(parseJson.status == 200){

						  $(".close").click();	

						   $("#edit_car_wash_cat")[0].reset();	

						   $("#msg_response_popup").modal('show');

						   $("#msg_response").html(parseJson.msg);

						  }

						if(parseJson.status == 400){

						   	  $.each(parseJson.error, function(key , value) {

								errorString += '<div class="notice notice-danger"><strong>Wrong , </strong>'+ value +' .</div>';

							  });

							$('#edit_err_response').html(errorString); 	

						 }  

						if(parseJson.status == 100){

							$("#edit_err_response").html(parseJson.msg);

						  }	 

					 } , 

					 error: function(xhr, error){

                        $('#add_car_wash_cat_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);

					   $("#response_msg").html(parseJson.msg);

          }

			});

	 });

/*End*/	 

/*Edit form data fetch script start*/	

$(document).on('click','.edit_cat',function(e){

  e.preventDefault();

  $('#edit_cat_id').val(" ");

  var cat_id = $(this).data('catid');

  $.ajax({

		url: base_url+"/master_agax/get_category_details",

		method: "GET",

		data: {category_id:cat_id},

		complete: function(e , xhr , setting){

		  $('#edit_cat_id').val(cat_id);

		   if(e.status == 200){

			 var parseJson = jQuery.parseJSON(e.responseText);

			 if(parseJson.status == 200){

				 $('#category_name').val(parseJson.response.category_name);

				 $('#description').html(parseJson.response.description);

				 $('#big_time').val(parseJson.response.big_time);

				 $("#average_time").val(parseJson.response.average_time);

				 $("#small_time").val(parseJson.response.small_time);

				 $("#small_price").val(parseJson.response.small_price);

				 $("#average_price").val(parseJson.response.average_price);

				 $("#big_price").val(parseJson.response.big_price);

				 $("#edit_car_washing_category_popup").modal('show');

			   }

			  if(parseJson.status == 100){

			      $("#msg_response_popup").modal('show');

				  $("#msg_response").html(parseJson.msg);

			   }  

			 }

		}

	  });

 });

 /*End*/

 $(document).on('click','.add_car_wash_image_btn',function(e){

  e.preventDefault();

  var cat_id = $(this).data('catid');

  show_car_wash_image(cat_id)

  //$("#add_car_wash_image_popup").modal('show');

});	

/*Add Car wash category script stra*/

$(document).on('submit','#add_car_wash_cat',function(e){

  $('#msg_response').html(" ");

  $("err_response").html(" ");

  $('#add_car_wash_cat_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);

		e.preventDefault();

			 $.ajax({

					 url: base_url+"/master/add_category",

					 type: "POST",        

					 data: new FormData(this),

					 contentType: false,

					 cache: false,

					 processData:false,  

					 success: function(data){

						$('#add_car_wash_cat_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);

						var parseJson = jQuery.parseJSON(data); 

						if(parseJson.status == 200){

						  $(".close").click();	

						   $("#add_car_wash_cat")[0].reset();	

						   $("#msg_response_popup").modal('show');

						   $("#msg_response").html(parseJson.msg);

						  }

						if(parseJson.status == 400){

							  $.each(parseJson.error, function(key , value) {

								errorString += '<div class="notice notice-danger"><strong>Wrong , </strong>'+ value+' .</div>';

							  });

							$('#err_response').html(errorString); 	

						 }  

						if(parseJson.status == 100){

							$("#err_response").html(parseJson.msg);

						  }	 

					 } , 

					 error: function(xhr, error){

                        $('#add_car_wash_cat_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);

					   $("#response_msg").html(parseJson.msg);

          }

			});

	 });

/*End*/	

/*Add car washing category script start */

$(document).on('click','#show_car_wash_modal',function(e){

  e.preventDefault();

  $("#add_car_washing_category_popup").modal('show');

});

/*End*/	

    /*View Service Details Script code Starts*/

	$(document).on('click' , '.view_booking_services' , function(e){

		e.preventDefault();

		var service_id = $(this).data('serviceid');

		if(service_id != "") {

			$.ajax({

				url: base_url+"/admin_ajax/view_service_details",

				type: "GET",        

				data:{serviceId:service_id},

				success: function(data){

					$("#service_response").html(data);

					$("#view_service_detail").modal('show');

				} 

			});

		}

	});

	/*End */

    	/* view Order script start */

	$(document).on('click' , '.get_order_details' , function(e){
		e.preventDefault();
		var order_id = $(this).data('orderid');
		if(order_id != "") {
			$.ajax({
				url: base_url+"/admin_ajax/view_order",
				type: "GET",        
				data:{orderId:order_id},
				success: function(data){
					// console.log(data);
					$("#order_response").html(data);
					$("#view_order").modal('show');
				} 
			});
		}
	});

	/* End */

	

	/* view Product description script start */

	$(document).on('click' , '.view_product_description' , function(e){

	    e.preventDefault();

		var product_order_id = $(this).data('orderid');

		if(product_order_id != "") {

			$.ajax({

				url: base_url+"/admin_ajax/view_product_description",

				type: "GET",        

				data:{orderId:product_order_id},

				success: function(data){

					// console.log(data);

					$("#description_response").html(data);

					$("#view_product_description").modal('show');

				} 

			});

		}

	});

	/* End */

/* Chnage order status script */

	$(document).on('click' , '.change_order_status' , function(e){

	    e.preventDefault();

		var $this = $(this);

		var status = $(this).data('status');

		var order_id = $(this).data('orderid');

		var con = confirm("Are you sure want to Change Status ?");

		if(con == true) {

			$.ajax({

				url: base_url+"/admin_ajax/change_order_status",

				type: "GET",        

				data:{status:status , orderId_id:order_id},

				success: function(data){

					console.log(status);

					//alert(status);

					if(status == "I") {

						$("#order_status").text('In Process');

					}

					if(status == "D") {

						$("#order_status").text('Dispatched');

					}

					if(status == "IN") {

						$("#order_status").text('Intransit');

					}

					if(status == "DE") {

						$("#order_status").text('Delievred');

					}
					setTimeout(function(){ location.reload(); } , 1000);

				}

			});

		}

	});

	/* End */

/* change status code of bank and company detail*/

$(document).on('click' , '.change_business_status' , function(){

		var $this = $(this);

		var status = $(this).data('status');

		var business_id = $(this).data('businessid');

			var con = confirm("Are you sure want to Change Status ?");

			if(con == true){

					$.ajax({

				url: base_url+"/admin_ajax/change_business_status",

				type: "GET",        

				data:{status:status , business_id:business_id},

				success: function(data){

								if(status == "P"){

						$this.data('status' , 'A').html('<i class="fa fa-toggle-on"></i>');

					}

					if(status == "A"){ 

						$this.data('status' , 'P').html('<i class="fa fa-toggle-off"></i>');

					}

							 }

			}); 

			}

	});	

	$(document).on('click' , '.change_bank_status' , function(){

		var $this = $(this);

		var status = $(this).data('status');

		var bank_id = $(this).data('bankid');

			var con = confirm("Are you sure want to Change Status ?");

			if(con == true){

					$.ajax({

				url: base_url+"/admin_ajax/change_bank_status",

				type: "GET",        

				data:{status:status , bank_id:bank_id},

				success: function(data){

					if(status == "P"){

							$this.data('status' , 'A').html('<i class="fa fa-toggle-on"></i>'); 

						}

						if(status == "A"){

							$this.data('status' , 'P').html('<i class="fa fa-toggle-off"></i>');

						}

							} 

				 }); 

			}

	});

/* End*/



/*Change Customer Status Script Start */

	$(document).on('click', '.change_customer_status', function(e){

		var $this = $(this);

		var status = $(this).data('status');

		var customer_id = $(this).data('customerid');

		var con = confirm("Are you sure want to Change Status ?");

		if(con == true){

			$.ajax({

				url: base_url+"/admin_ajax/change_customer_status",

				type: "GET",        

				data:{status:status , customer_id:customer_id},

				success: function(data){

					if(status == "B"){

						$this.data('status' , 'A').html('<i class="fa fa-toggle-on"></i>');

						//$this.removeClass("fa fa-toggle-off").addClass('fa fa-toggle-off'); 

					}

					if(status == "A"){

						$this.data('status' , 'B').html('<i class="fa fa-toggle-off"></i>');

						//$this.removeClass("fa fa-toggle-off").addClass('fa fa-toggle-off'); 

					}

				}

			}); 

		}

	})



	/*End */

/*Remove Category script start*/

$(document).on('click','.delete_cat',function(e){

  e.preventDefault();

  var con = confirm("Are you sure want to remove this category");

  if(con == true){

	  var href = $(this).attr('href');

	  window.location.href = href;

	}

});

/*End*/	

/*Remove Image*/

$(document).on('click','.remove_delete',function(e){

      e.preventDefault();

	  var con = confirm("Are you sure want to delete this image");

	  if(con == true){

		var delete_id = $(this).data('imageid');

		var category_id = $("#category_id").val();

		  $.ajax({

			 url: base_url+"/master_agax/remove_image",

			 type: "GET",        

			 data:{delete_id:delete_id , category_id:category_id},

			 success: function(data){

			      show_car_wash_image(category_id);

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

$(document).on('submit','#edit_category_image',function(e){

		$('#response_msg').html(" ");

		$('#save_image').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);

		var category_id = $("#category_id").val();

		e.preventDefault();

			 $.ajax({

					 url: base_url+"/master/upload_category_image",

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

							 //show_car_wash_image(category_id)

						    $("#edit_category_image")[0].reset();	

						     // $(".close").click();

						  }

						else{

							$("#response_msg").html(parseJson.msg);

						  }	 

					 } , 

					 error: function(xhr, error){

                        $('#save_image').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);

					   $("#response_msg").html(parseJson.msg);

          }

			});

	 });

/*eND*/	

/*slide down image script satr*/

$(document).on('click','.add_more_image',function(){

  $("#image_section").slideDown('slow');

});

/*End*/	

    

/*Feedbach status change */

$(document).on('click','.change_feed_status',function(e){

	var id = $(this).data('rowid');

	var status = $(this).data('status');



	if(id != ""){

		 $.ajax({

		url: base_url+"/admin_ajax/change_feed_status",

		type: "GET",        

		data:{id:id , status:status},

		 success: function(data){

			var parseJson = jQuery.parseJSON(data);

		   $("#msg_response").html(parseJson.msg);			

		   $("#msg_response_popup").modal('show');

			 setTimeout(function(){ location.reload(); } , 1000);

			 //$('.card').load(document.URL + ' .card');

			 /* var parseJson = jQuery.parseJSON(data)

			 if(parseJson.status == 200){

				 $("#add_contact_form")[0].reset();					                       

			 }

				$("#response_mobilr_add").html(parseJson.msg); */	 

		 }

		});

	}

});

/*Change category script start*/

  $(document).on('click','#approve_disapproove',function(e){

	 e.preventDefault();

	 var status = $(this).data('status');

	 var workshop_id = $('#workshop_id').val();

	   if(workshop_id != ""){

		   $.ajax({

				  url:base_url+"/commonAjax/change_status",

				  type:"GET",

				  data:{status:status , workshop_id:workshop_id},

				  success: function(data){

					var parseJson = jQuery.parseJSON(data);

					if(parseJson.status = 200){

					  location.reload();

					  /* if(status == 1){

						   $("#approve_disapproove").removeClass('btn btn-danger').addClass('btn btn-danger').html('Disapprove');   

						 }	

					   else if(status == 0){

						   $("#approve_disapproove").removeClass('btn btn-success').addClass('btn btn-danger');   

						 }	*/ 

						

						//location.reload();

					  }

					 else if(parseJson.status = 100){

					      $("#msg_response_popup").modal('show');

				          $("#msg_response").html(parseJson.msg);

					  } 

			      }

				});  

		  }

   });

  /*End*/ 

/*Change category script start*/

  $(document).on('change','#category_type',function(){

	  var category_type = $("#category_type").val();

     if(category_type){

				$.ajax({

				  url:base_url+"/commonAjax/change_category",

				  type:"GET",

				  data:{category_type:category_type},

				  success: function(data){

				    var parseJson = jQuery.parseJSON(data);

					if(parseJson.status = 200){

					  var html_con = '<option value="0" selected>Select Parent Category</option>';	

					  $("#parent_category").html(parseJson.result);

					  $("#parent_category").prepend(html_con);

					  }

					 else{

					  $("#parent_category").html('<option value="0">No category available</option>');

					  } 

			      }

				});  

			  }

   });

  /*End*/

  
  $(document).on('click','.roll_view_btn',function(e){
     e.preventDefault();
	 var roll_id = $(this).data('roll');

	 var _url =  $(this).attr('href');
	 section_changeurl_second(_url , "user_data_body")
  });
   function section_changeurl_second(url , fetch_get_element){

        window.history.pushState("data" ,"Title", url);

        $("#"+fetch_get_element ).load(url+" #"+ fetch_get_element , function(responseTxt, statusTxt, xhr){

        if(statusTxt == "success")

            //get_mat_profile_img("image");

            //alert(responseTxt);

        if(statusTxt == "error")

            alert("Error: " + xhr.status + ": " + xhr.statusText);

});  

}

});