$(document).ready(function(e) {
    /*Add maintainance details */
	$(document).on('click', '#car_maintinance_detils', function(e){
		e.preventDefault();
		$("#add_car_maintanance_details_popup").modal('show');
	});
	/*End */
	/*Submit car maintainance  Details*/
	$(document).on('click', '#add_car_maintainance_details_btn', function(e) {
        var btn_html = $("#add_car_maintainance_details_btn").html();
        $('#add_car_maintainance_details_btn').html('Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
        e.preventDefault()
        hourly_rate = $("#hourly_rate").val();
        max_appointment = $("#max_appointment").val();
        $.ajax({
            url: base_url + "/car_maintinance/workshop_car_maintainance_details",
            type: "GET",
            data: { hourly_rate: hourly_rate, max_appointment: max_appointment },
            complete: function(e, xhr, setting) {
                $('#add_car_maintainance_details_btn').html(btn_html).attr('disabled', false);
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
                        $("#msg_response").html(errorString);
                    }
                    if (parseJson.status == 100) {
                        $("#response_err").html(parseJson.msg);
                    }
                }
            },
            error: function(xhr, error) {
                // $('#add_services_btn_copy').html(btn_html).attr('disabled' , false);
                $("#response_err").html('<div class="notice notice-danger"><strong>Wrong </strong>Something Wrong , please try again . !!! </div>');
            }
        });
    });
	/*End */
    /*Add Selected Services  */
	$(document).on('submit','#add_selected_services',function(e){
        $('#response_add_category').html(" ");
        $('#add_services').html('Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
        serviceData = []
		records = [];
		$('#add_selected_services .services_row').each(function() {
			if($(this).find('#services').is(':checked')){
				records.push({service_price:$(this).find('#services').data('price'),service_name:$(this).find('#services').data('servicename'), service_id: $(this).find('#service_id').val() });
			}
		 })
	   
		var car_revision_booking_id = $("#car_revision_booking_id").val();
		var total_price = $("#total_price").val(); 
        e.preventDefault();
        $.ajax({
            url: base_url+"/vendor_ajax/add_selected_services",
            type: "POST",        
            data: {records:records , booking_id:car_revision_booking_id, total_price:total_price },
            dataType: 'json',
            success: function(data){
                $('#add_services').html(' Save &nbsp;<i class="icon-paperplane ml-2"></i> ').attr('disabled' , false);
                $("#msg_response_popup").modal('show');
                $("#msg_response").html(data.msg); 
            }	,
            error: function(xhr, error){
                $('#add_services').html(' Save &nbsp;<i class="icon-paperplane ml-2"></i> ').attr('disabled' , false);
                $("#msg_response_popup").modal('show');
                $("#msg_response").html('<div class="notice notice-danger"><strong>Wrong </strong>Something Wrong , please try again . !!! </div>');
            }
        });
    });
	/* End */
    /*Add Workshop Car Revision Category popup open*/
	$(document).on('click', '.calculate_total_price', function (e) {
		var $this = $(this);
		var price = $(this).data('price');
		var total_price = $('#total_price').val();
		if( $(this).is(':checked') ){
			subtotal_price =   parseInt(total_price) + parseInt(price);
			$('#total_price').val(subtotal_price);
		}
		else{
			subtotal_price2 =   parseInt(total_price) - parseInt(price);
			$('#total_price').val(subtotal_price2);
		}
	

	});
	/*End */
    /*Edit Car Revision Category Script Start */
	$(document).on('click', '.edit_car_rveision_category', function (e) {
		e.preventDefault();
			$("#category_name").val("");
			$("#price").val("");
		var $this = $(this);
		var category_id = $(this).data('categoryid'); 
		if (category_id != "") {
			$.ajax({
				url: base_url + "/vendor_ajax/get_workshop_category",
				type: "GET",
				data: {category_id: category_id},
				success: function (data) {
					var parseJson = jQuery.parseJSON(data);
					if (parseJson.status == 200) {
						$("#category_id").val(parseJson.response.id);
						$("#category_name").val(parseJson.response.category_name);
						$("#price").val(parseJson.response.price);
						$("#myModalLabel").html('Edit Service');
						$("#add_category_popup").modal('show');
					}
					
				}
			});
		}
		
	});
	/*End */
    /*Add Workshop Car Revision Category popup open*/
	$(document).on('click', '#add_car_revision_category', function (e) {
	    $("#category_name").val("");
			$("#price").val("");
			$("#myModalLabel").html('Add New Service');
		$("#add_category_popup").modal('show');
	});
	/*End */

	/*Add Workshop Car Revision Category Script Start*/
	$(document).on('submit', '#add_car_revision_category_form', function (e) {
		$('#car_revision_submit').html('Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
		e.preventDefault();
		$.ajax({
			url: base_url+"/vendor_ajax/add_car_revision_category",
			type: "POST",   
			data: new FormData(this),
			contentType: false,
			cache: false,
			processData:false,    
			success: function(data){
				$('#car_revision_submit').html(' Submit &nbsp;<i class="icon-paperplane ml-2"></i> ').attr('disabled' , false);
				var parseJson = jQuery.parseJSON(data);
				if(parseJson.status == 200){
					$("#add_car_revision_category_form")[0].reset();
					$("#add_category_popup").modal('hide');
					$("#msg_response_popup").modal('show');
					$("#msg_response").html(parseJson.msg);
					setTimeout(function(){ location.reload(); } , 1000);
				}
				if(parseJson.status == 100) {
					$("#add_car_revision_category_form")[0].reset();
					$("#add_category_popup").modal('hide');
					$("#msg_response_popup").modal('show');
					$("#msg_response").html(parseJson.msg);
				}	
			} , 
			error: function(xhr, error){
				$('#car_revision_submit').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
				$("#response_msg").html(parseJson.msg);
			}
      	});
	});
	/*End */
    
    /*Add off days script strat*/
	$(document).on('click','#add_off_date',function(e){
        var off_date = $(".off_date").val();
		if(off_date != ""){
		     $.ajax({
			  url: base_url+"/vendor_ajax/add_off_date",
			  type: "GET",        
			  data:{off_date:off_date},
				 success: function(data){
					var parseJson = jQuery.parseJSON(data); 
					if(parseJson.status == 200){
					   $('.close').click();	
					   $("#msg_response_popup").modal('show');
					   $("#msg_response").html(parseJson.msg);
			           $('#special_days_hour_section').load(document.URL + ' #special_days_hour_section');		
					  }
					else{
					    $("#response_details").html(parseJson.msg);
					  }	 
				 }
		    });
		  }
   });
	/*End*/
/*cpoupon avail expiry date  script start*/
   $(document).on('change','#cpoupon_expiry_date',function(){
	   $("#msg_response").html(" "); 
       var firstDate = $('#datecupan').val();
	   var secondDate = $("#datecupan1").val();
	   var coupon_avail_date = $("#avail_date").val();
	   var coupon_expiry_date = $("#cpoupon_expiry_date").val();
	   if(firstDate != "" && secondDate != "" && coupon_avail_date != ""){
		      if(new Date(coupon_expiry_date)  < new Date(coupon_avail_date) || new Date(coupon_expiry_date)  < new Date(firstDate)){
			   $("#msg_response_popup").modal('show');
			   $("#msg_response").html("<div class='notice notice-danger'> <strong> Note , </strong> choose correct date .</div>"); 
		       $("#coupon_sbmt").attr("disabled" , true);
			  }
			else{
			   $("#coupon_sbmt").attr("disabled" , false);
			  }   
		 }
	   else{
		  $("#msg_response_popup").modal('show'); 
		  $("#msg_response").html("<div class='notice notice-danger'> <strong> Note , </strong> Please fill all required field...</div>"); 
		  $("#coupon_sbmt").attr("disabled" , true);
		 }	 
   });
   /*End*/
   	
   $(document).on('change','#datecupan',function(){
	 $("#msg_response").html(" "); 
	 $("#coupon_sbmt").attr("disabled" , false);
	 var firstDate = $('#datecupan').val();
	 if(new Date(firstDate)  < new Date() ){
		$("#msg_response_popup").modal('show');
		$("#msg_response").html("<div class='notice notice-danger'> <strong> Note , </strong> Please choose correct launching date...</div>");
		$("#coupon_sbmt").attr("disabled" , true);
	   }
   });
   	
   
   $(document).on('change','#datecupan1',function(){
	  $("#msg_response").html(" "); 
      var firstDate = $('#datecupan').val();
	  var secondDate = $("#datecupan1").val();
	  if(new Date(firstDate)  > new Date() ){
		 if(firstDate == ""){
			$("#msg_response_popup").modal('show');
			$("#msg_response").html("<div class='notice notice-danger'> <strong> Note , </strong> Please choose coupon lanching date...</div>"); 
			$("#coupon_sbmt").attr("disabled" , true);
			}
		  else{
			 if(new Date(firstDate) >= new Date(secondDate)){
			   $("#msg_response_popup").modal('show');	 
			   $("#msg_response").html("<div class='notice notice-danger'> <strong> Note , </strong> Please choose closed date correct ...</div>");
				$("#coupon_sbmt").attr("disabled" , true);
			   }
			 else
			   $("#coupon_sbmt").attr("disabled" , false);
			}	
		}
	  else{
		  $("#msg_response_popup").modal('show');
		  $("#msg_response").html("<div class='notice notice-danger'> <strong> Note , </strong> Please choose correct launching date...</div>");
		  $("#coupon_sbmt").attr("disabled" , true);
		}	
   });
   
   /*choose coupon Avail date script start*/
     $(document).on('change','#avail_date',function(){
	    $("#msg_response").html(" "); 
	    var firstDate = $('#datecupan').val();
	    var secondDate = $("#datecupan1").val();
		var cpoupon_avail_date = $(this).val();
		if(firstDate != "" && secondDate != "" ){
			 if(new Date(cpoupon_avail_date) < new Date(firstDate) || new Date(cpoupon_avail_date) > new Date(secondDate)){
				  $("#msg_response_popup").modal('show');	 
				  $("#msg_response").html("<div class='notice notice-danger'> <strong> Note , </strong> Please choose correct coupon avail date  ,choose coupon avail date also lanching date between closed on...</div>");
				  $("#coupon_sbmt").attr("disabled" , true);
			  }
			 else{
			    $("#coupon_sbmt").attr("disabled" , false);
			 } 
		  }
		else{
			$("#msg_response_popup").modal('show');
		    $("#msg_response").html("<div class='notice notice-danger'> <strong> Note , </strong> Please select launching date and closed date...</div>");
			$("#save_coupon").attr("disabled" , true);
		  }  
	 });
   /*End*/	
	
/*coupon form submit script start*/
	$(document).on('submit','#coupon_form',function(e){
		$('#response_coupon').html(" ");
		$('#coupon_sbmt').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
		e.preventDefault();
			 $.ajax({
					 url: base_url+"/coupon/add_coupon",
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
							 $('#response_coupon').html(errorString); 	
						 }
						 if(parseJson.status == 200){
							$("#msg_response_popup").modal('show');
							$("#msg_response").html(parseJson.msg);
							$("#coupon_form")[0].reset();
						 }
						 if(parseJson.status == 100){
							$("#msg_response_popup").modal('show');
						   $("#msg_response").html(parseJson.msg);
						 } 
			}	
			});
	 });
	/*End*/
  /*workshop add contact number script start*/
   $(document).on('click','#add_mobile_number',function(e){
        var mobile_number = $("#mobile").val();
	    var workshop_id = $("#workshop_id").val();
		if(workshop_id != ""){
		     $.ajax({
			  url: base_url+"/vendor_ajax/add_mobile_number",
			  type: "GET",        
			  data:{workshop_id:workshop_id , mobile_number:mobile_number},
				 success: function(data){
					 var parseJson = jQuery.parseJSON(data)
					 if(parseJson.status == 200){
						 $('#workshop__mobile_section').load(document.URL + ' #workshop__mobile_section');
						 $("#add_contact_form")[0].reset();					                       }
					  $("#response_mobilr_add").html(parseJson.msg);	 
				 }
		    });
		  }
   });
 /*End*/
 /*Remove Mobile number script start*/
   $(document).on('click','.delete_mobile',function(e){
	   e.preventDefault();
        var row_id = $(this).data('mobileid');
	    var con  = confirm("Are you sure want to remove this mobile number .");
	    if(con == true){
		     $.ajax({
			 url: base_url+"/vendor_ajax/remove_mobile_number",
			 type: "GET",        
			 data:{row_id:row_id},
			 success: function(data){
				var parseJson = jQuery.parseJSON(data)
                 if(parseJson.status == 200){
					 $('#workshop__mobile_section').load(document.URL + ' #workshop__mobile_section');  
 			       }
				  $("#msg_response_popup").modal('show');
				  $("#msg_response").html(parseJson.msg);
			 }
		    });
		  }
   });
 /*End*/
  /*Remove Days timing script start*/
   $(document).on('click','.delete_daystiming',function(e){
	   e.preventDefault();
        var workshop_user_day_id = $(this).data('daysid');
	    var con  = confirm("Are you sure want to remove this timing .");
	    if(con == true){
		     $.ajax({
			 url: base_url+"/vendor_ajax/remove_days_timing",
			 type: "GET",        
			 data:{workshop_user_day_id:workshop_user_day_id},
			 success: function(data){
				 var parseJson = jQuery.parseJSON(data)
                 if(parseJson.status == 200){
					 $('#days_hour_section').load(document.URL + ' #days_hour_section');  
 			       }
				  $("#msg_response_popup").modal('show');
				  $("#msg_response").html(parseJson.msg);
			 }
		    });
		 }
   });
 /*End*/
 
 /*Add more button scdript start*/
  $(document).on('click','.remove_more_timing',function(e){
      var row_id = $(this).data('rowid');
	  e.preventDefault();
      $(this).parent().parent().hide();
      $(this).parent().parent().hide();
	  $("#add_more_btn"+row_id).show();
  });
   
  $(document).on('click','.add_more_time_btn',function(e){ 
     e.preventDefault();
     $(".add_more_timing_section_copy").show('slow');
	 $(".add_more_time_btn").hide();
	 //var days = $(this).data('days');
     //$("#timingrow"+days).prepend(html_content);
  });
  $(document).on('click','.day_24_checkbox',function(){
     var row_id = $(this).data('crowid');
	 if($("#day_24_"+row_id).is(':checked')){
	      $('#timingrow'+row_id).hide('slow');
	      $("#add_more_btn"+row_id).hide('slow');
		  
		}
	 else{
	    $('#timingrow'+row_id).show('slow');
		$("#add_more_btn"+row_id).show('hide');
	    }
	 
	 
	 //alert(row_id);
  });
 /*End*/
 /*Repeat all Week days script start*/
   $(document).on('click','#repeat_all',function(){
	   var real_value = $(this).val();
	   var repeat_all_value = parseInt(real_value) + 1;
	    if( $(".whole_days"+real_value).is(':checked') ){
		    for (i = repeat_all_value; i < 8; i++) { 
				$(".whole_days"+i).prop('checked','checked');
				$(".whole_days"+i).parent().addClass('checked');
            }
		  }
		else{
		   for (i = repeat_all_value; i < 8; i++) { 
				$(".whole_days"+i).prop('checked',false);
				$(".whole_days"+i).parent().removeClass('checked');
            }
		  }  
	   //alert(repeat_all_value);
	   //var timing_row_html_content = $(this).parent().parent().find('#timingrow'+repeat_all_value).html();
	   //var timing_row_html_content = $(this).parent().parent().parent().parent().parent().siblings().html();
	    var first_time  = $(".first_timing_1"+real_value).val();
		var second_time = $(".second_timing_1"+real_value).val();
		var start_time = $(".start_time"+real_value).val();
		var end_time = $(".end_time"+real_value).val();
	    
	   if($(this).is(':checked')){
	      for (i = repeat_all_value; i < 8; i++) { 
            $("#d"+i).prop('checked' , true);
	        $("#d"+i).parent().addClass('checked');
			$(".first_timing_1"+i).val(first_time);
		    $(".second_timing_1"+i).val(second_time);
		    $(".start_time"+i).val(start_time);
		    $(".end_time"+i).val(end_time);
          }
	    }
	   else{
	       for (i = repeat_all_value; i < 8; i++) { 
             $("#d"+i).prop('checked', false);
	         $("#d"+i).parent().removeClass('checked');
			 $(".first_timing_1"+i).val("");
		     $(".second_timing_1"+i).val("");
		     $(".start_time"+i).val("");
		     $(".end_time"+i).val("");
          }
	     } 
   });
 /*End*/
 /*Add Weekly script start*/
   $(document).on('submit','#weekly_schedule_form',function(e){
	 $('#response_timing').html(" ");
	 $('#add_workshop_timing').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
	 e.preventDefault();
      $.ajax({
          url: base_url+"/vendor_ajax/add_workshop_timing",
          type: "POST",        
          data: new FormData(this),
          contentType: false,
          cache: false,
          processData:false,  
          success: function(data){
			 $('#add_workshop_timing').html(' Save &nbsp;<i class="icon-paperplane ml-2"></i> ').attr('disabled' , false);
			  $('#response_timing').html('<div class="notice notice-success"><strong>Success </strong> Record save  successful  !!! </div>'); 	
              $('#days_hour_section').load(document.URL + ' #days_hour_section');  
		 },
		 error: function(xhr, error){
			$('#add_workshop_timing').html(' Save &nbsp;<i class="icon-paperplane ml-2"></i> ').attr('disabled' , false);
            $('#response_timing').html('<div class="notice notice-danger"><strong>Note </strong> Something Went Wrong  !!! </div>'); 
          }
     });
  });
 /*End*/
 
 /*Close Days Section*/
   $(document).on('click','.close_section',function(e){
      e.preventDefault();
	  var close_section = $(this).data("closesectionid");
	  var id = $(this).data('cid');
	   $("#"+close_section).hide('slow');
	   //$('input[type=checkbox] #d'+id).attr('checked' , false);
       $("#d"+id).attr('checked',false);
   });
 /*End*/
 <!--hours list--> 
  $(document).on('click','.weekly_days',function(e){
	 var days_checkbox_id = this.id;
	 if($("#"+days_checkbox_id).is(':checked')){
	      $("#hour_section"+days_checkbox_id).show('slow');
	    }
	 else{
	     $("#hour_section"+days_checkbox_id).hide('slow');
	    } 
  }); 

  /*Date and time picker script start*/
  $( function() {
    $( "#datepicker" ).datepicker();
  } );
  $( function() {
    $( "#datepicker1" ).datepicker();
  } );
 $(function () {
	$('#datetimepicker3').datetimepicker({
		format: 'LT'
	});
  });
/*End*/

 /*Delete Images in table*/
   $(document).on('click','.image_delete',function(e){
      e.preventDefault();
	  var con = confirm("Are you sure want to delete this image");
	  if(con == true){
		var delete_id = $(this).data('imageid');
		  $.ajax({
			 url: base_url+"/vendor_ajax/remove_workshop_image",
			 type: "GET",        
			 data:{delete_id:delete_id},
			 success: function(data){
				if(data == 101){
				   $("#msg_response_popup").modal('show');
				   $("#msg_response").html('<div class="notice notice-danger"><strong>Wrong </strong> Something went wrong , please try again !!! </div>');
				  }
				else{
				     $('#workShopImage').load(document.URL + ' #workShopImage'); 
				  }  
			 }
		  });
		}
   });
 /*End*/	
 <!--Paid Amount Show and hide script start--> 
  $(document).on('click','.paid_status',function(e){
	 if($(".paid_status").is(':checked')){
	      $("#paid_amount_div").show('slow');
	    }
	 else{
	     $("#paid_amount_div").hide('slow');
	   } 
  }); 
 <!--End-->
 <!--Address Show and hide script start-->
  $(document).on('click','.address',function(e){
	 if($(".address").is(':checked')){
	    $("#address_div").show('slow');
	   }
	 else{
	   $("#address_div").hide('slow');
	  } 
  });
 <!--End-->  
 /*Add Workshop Script Start*/
    $(document).on('submit','#workshop_form',function(e){
     $('#response').html(" ");
	 $('#workshop_sbmt').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
	 e.preventDefault();
      $.ajax({
          url: base_url+"/vendor_ajax/add_workshop",
          type: "POST",        
          data: new FormData(this),
          contentType: false,
          cache: false,
          processData:false,  
          success: function(data){
			var parseJson = jQuery.parseJSON(data)
			var errorString = '';
            $('#workshop_sbmt').html(' Submit <i class="icon-paperplane ml-2"></i>').attr('disabled' , false);	
            if(parseJson.status == 400){
				  $.each(parseJson.error, function(key , value) {
					errorString += '<div class="notice notice-success"><strong>Success , </strong>'+ value+' .</div>';
				   });
				 $("#msg_response_popup").modal('show');
				 $("#msg_response").html(errorString);  
				 
              } 
			if(parseJson.status == 200){
			    $('#workshop_form')[0].reset();
                setTimeout(function(){ window.location.href = parseJson.url; } , 1000);
			  }
			// $("#response").html(parseJson.msg); 
         }	
     });
  });
 /*End*/ 
  /*Add Workshop Script Start*/
    $(document).on('submit','#edit_workshop_form',function(e){
     $('#response').html(" ");
	 $('#workshop_sbmt').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
	 e.preventDefault();
      $.ajax({
          url: base_url+"/vendor_ajax/edit_workshop",
          type: "POST",        
          data: new FormData(this),
          contentType: false,
          cache: false,
          processData:false,  
          success: function(data){
			 $('#workshop_sbmt').html(' Submit <i class="icon-paperplane ml-2"></i>').attr('disabled' , false);	
			 var parseJson = jQuery.parseJSON(data)
             if(parseJson.status == 200){
			    $('#edit_workshop_form')[0].reset();
			  }
			 $("#response").html(parseJson.msg); 
         }	
     });
  });
 /*End*/ 
});