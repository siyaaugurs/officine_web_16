/*Get State*/
function get_state(countryArr , state_val = null){
	$(".state").empty().html('<option value="0">--Select--State--Name--</option>');
	$.ajax({
		url:base_url+"/commonAjax/get_state",
		type:"GET",
		data:{country_id:countryArr[0]},
		success: function(data){
		   var parseJson = jQuery.parseJSON(data);
		   var html_content = '<option value="0">Please--Select--State--</option>'; 
		   if(parseJson.status == 200){
			   $.each(parseJson.states , function(index , value){
				  html_content += '<option value="'+value.id+'@'+value.name+'">'+value.name +'</option>';
			   })
			 }
		   if(parseJson.status == 100){
			  html_content = '<option hidden="hidden"></option>'; 
			 } 
		   html_content += '';  
		   $(".state").html(html_content);; 
		   if(state_val != null){
			  $('.state').find("option[value='"+state_val+"']").attr('selected', 'selected');
			}
		}
	 });
}
/*End*/
/*Get Cities */
function get_cities(stateArr , city_val = null){
 $(".cities").empty().html('<option value="0">--Select--City--Name--</option>');	
	$.ajax({
		url:base_url+"/commonAjax/get_cities",
		type:"GET",
		data:{stateID:stateArr[0]},
		success: function(data){
		   var parseJson = jQuery.parseJSON(data);
		   var html_content = ''; 
		   if(parseJson.status == 200){
			   $.each(parseJson.cities , function(index , value){
				  html_content += '<option value="'+value.id+'@'+value.name+'">' + value.name +'</option>';
			   })
			 }
		   if(parseJson.status == 100){
				html_content = '<option value="0">No city available !!!</option>'; 
			 } 
		   if(parseJson.status == 500){
			  html_content = '<option value="0">No city available !!!</option>';    
			 }  
		   html_content += '';  
		   $(".cities").html(html_content); 
		   if(city_val != null){
			  $('.state').find("option[value='"+city_val+"']").attr('selected', 'selected');
			}
		}
	 });
}
/*End*/
/*Get Country script start*/
function get_country_name(get_country_name = null){
  $.ajax({
	     url:base_url+"/commonAjax/get_country",
		 success: function(data){
		    var parseJson = jQuery.parseJSON(data);
			var html_content = ''; 
			    html_content = '<option value="0">Select Country Name</option>'; 
			 if(parseJson.status == 200){
			    $.each(parseJson.countries , function(index , value){
			      html_content += '<option value="'+value.id+'@'+value.name+'">' + value.name +'</option>';
				})
			  } 
			if(parseJson.status == 100){
			     html_content = '<option hidden="hidden"></option>'; 
			  } 
			html_content += ''; 
			$(".country").html(html_content); 
			if(get_country_name != null){
			    $(".country").find("option[value='"+get_country_name +"']").attr('selected', 'selected'); 
			  }
		 }
	  });
  }
/*End*/

$(document).ready(function(e) {
    /*View Workshop Feedback Detail script Start */
	$(document).on('click', '.view_workshop_feedback', function (e) {
		var $this = $(this);
		var feedback_id = $(this).data('feedbackid');
		if(feedback_id != ""){
			$.ajax({
				url: base_url+"/commonAjax/get_workshop_feedback",
				method: "GET",
				data: {feedbackId:feedback_id},
				success: function(data){
					console.log(data);
					$('#feedback_result').html(data);
					$('#view_workshop_feedback_popup').modal('show');
				}
		  });
		}
	});
	/*End */

	/*View Seller Feedback Detail script Start */

	$(document).on('click', '.view_seller_feedback', function (e) {

		var $this = $(this);

		var feedback_id = $(this).data('feedbackid');

		if(feedback_id != ""){

			$.ajax({

				url: base_url+"/commonAjax/get_seller_feedback",

				method: "GET",

				data: {feedbackId:feedback_id},

				success: function(data){

					// console.log(data);

					$('#feedback_result').html(data);

					$('#view_workshop_feedback_popup').modal('show');

				}

		  });

		}

	});

	/*End */

    /*Select All scdript start*/

	  $(document).on('click','#select_all',function(){

	     if( $(this).is(':checked') ){

		     $(".all_select").prop('checked' ,true);

		   }

		 else{

		    $(".all_select").prop('checked' ,false);

		   }  

	  });

	/*End*/

	/*gET pRODUCTS details script strat*/

	$(document).on('click','.get_assemble_products_details',function(e){

	  e.preventDefault();

	  var products_id = $(this).data('productsid');

		if(products_id != ""){

		   $.ajax({

			 url:base_url+"/product/get_assemble_products_details",

			 method:"GET",

			 data:{products_id:products_id},

			 success:function(data){

				$("#products_response").html(data);

				$("#products_assemble_details_modal").modal('show');

				/*$('#search_parts_group').html('Search &nbsp;<span class="glyphicon glyphicon-search"></span>').attr('disabled' , true);

			   $("#user_data_body").html(data);*/

			 }

		 });

		  }

	}); 

	/*End*/
 /*Paid Amount Show and hide script start */ 
  var page = $("#page").val();
    $(document).on('click','#add_more_addrs',function(e){
		e.preventDefault();
		 //$(".country").attr('selected' , false).
		 $('.country').find(':selected').attr('selected' , false); 
		 $(".state").empty().html('<option value="0">--First--Select--Country--</option>');
		 $(".cities").empty().html('<option value="0">--First--Select--State--</option>');
		 $('#response_workshop_adrs').html(" ");
		 $("#hidden_item").html(" ");
		 $("#change_heading").html(' <i class="text-white icon-profile mr-3 icon-1x"></i> Add New ');
		 $('#workshop_adrs_form')[0].reset();
		 $("#addrs_popup").modal({
			 backdrop:'static',
			 keyboard:false,	 
		 });
  });
  
  /*edit Address script start*/
    $(document).on('click','.edit_address',function(e){
	  $("#preloader").show();
	   $("#hidden_item").html("");
	   e.preventDefault();
	   	var con = confirm("Are you sure want to edit this address");   
		var address_id = $(this).data('adrsid');
		if(con == true){
		    $.ajax({
				  url:base_url+"/commonAjax/get_aaddress_details",
				  type:"GET",
				  data:{address_id:address_id},
				  success: function(data){
					 $("#hidden_item").html('<input type="hidden" name="edit_address_id" value="'+address_id+'" readonly />');
					 var parseJson = jQuery.parseJSON(data);
					 if(parseJson.status == 200){
					     $("#change_heading").html('<i class="icon-pencil5 icon-1x"></i>&nbsp; Edit');
						  $("#submit_address").html('Save&nbsp;<i class="icon-paperplane ml-2"></i>');
						 var country_val = parseJson.result.country_id+"@"+parseJson.result.country_name;
						 //var for_county = '<option value="'+country_val.trim()+'" selected>'+parseJson.result.country_name+'</option>';
						  var state_val = parseJson.result.state_id+"@"+parseJson.result.state_name;
						  var city_val = parseJson.result.city_id+"@"+parseJson.result.city_name;
						  $("#address_1").val(parseJson.result.address_1);
						  $("#address_2").val(parseJson.result.address_2);
						  $("#zip_code").val(parseJson.result.zip_code);
						  var country_id = parseJson.result.country_id + '@' + parseJson.result.country_name;
						 countryArr = [parseJson.result.country_id , parseJson.result.country_name];
						 stateArr = [parseJson.result.state_id , parseJson.result.state_name];
                         $('.country').find("option[value='" + country_id + "']").attr('selected', 'selected');
						 /*Car all state for selected country*/
						 get_state(countryArr , state_val);
						 get_cities(stateArr , city_val);
						 /*End*/
						 $('#state').find("option[value='"+state_val+"']").attr('selected', 'selected');
						 $('#city').find("option[value='"+city_val+"']").attr('selected', 'selected');
						  $("#latitude").val(parseJson.result.latitude);
						  $("#longitude").val(parseJson.result.longitude);
						  $("#addrs_popup").modal({
							backdrop:'static',
							keyboard:false,
						  });
						  $("#preloader").hide();
					   }
					 if(parseJson.status == 100){
					    $("#msg_response_popup").modal('show');
					    $("#msg_response").html(data);
					   }
			        }
				}); 
		  } 
	});
  /*End*/
  /*Remove address script start*/
    $(document).on('click','.remove_adrs',function(e){
	    e.preventDefault();
		 //$('#edit_about_workshop_btn').html('Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
		var con = confirm("Are you sure want to delete this address");

		if(con == true){

		  var address_id = $(this).data('adrsid');

			if(address_id){

				$.ajax({

				  url:base_url+"/commonAjax/remove_address",

				  type:"GET",

				  data:{address_id:address_id},

				  success: function(data){

					 $("#msg_response_popup").modal('show');

					 $("#msg_response").html(data);

			        $('#workshop_address_section').load(document.URL + ' #workshop_address_section');				

			  }

				});  

			  }

		  }

	});

  /*End*/

  /*Save About workshop vscript start*/

     $(document).on('click','#edit_about_workshop_btn',function(e){

	    e.preventDefault();

		var con = confirm("Are you sure want to edit .");

		if(con == true){

		  $('#edit_about_workshop_btn').html('Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);

		  var about_workshop = $("#about_workshop").val();

		  var workshop_id = $("#workshop_id").val();

			if(about_workshop != "" || workshop_id != ""){

				$.ajax({

				  url:base_url+"/commonAjax/edit_about_workshop",

				  type:"GET",

				  data:{about_workshop:about_workshop , workshop_id:workshop_id},

				  success: function(data){

				    $(".close").click();

				    $("#response_about_workshop").html(data);

			        $('#about_workshop_section').load(document.URL + ' #about_workshop_section');				

				    $("#msg_response_popup").modal('show');

	            	$("#msg_response").html(data);				

			  }

				});  

			  }

		  }

	});

  /*End*/

  /*Delete category vscdript start*/

    $(document).on('click','.delete_category',function(e){

	    e.preventDefault();

		var con = confirm("Are you sure want to delete this category");

		if(con == true){

		  var category_id = $(this).data('cateid');

			if(category_id != ""){

				$.ajax({

				  url:base_url+"/commonAjax/delete_category",

				  type:"GET",

				  data:{category_id:category_id},

				  success: function(data){

					$("#msg_response_popup").modal('show');

					$("#msg_response").html(data);

			$('#workshop_details_section').load(document.URL + ' #workshop_details_section');				  }

				});  

			  }

		  }

	});

  /*End*/

  /*Add signle  category scdript start*/

    $(document).on('submit','#add_category_form',function(e){

      $('#response_add_category').html(" ");

	  $('#submit_category').html('Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);

	 e.preventDefault();

      $.ajax({

          url: base_url+"/commonAjax/add_users_category",

          type: "POST",        

          data: new FormData(this),

          contentType: false,

          cache: false,

          processData:false,  

          success: function(data){

			$('#response_add_category').html(data);

			var parseJson = jQuery.parseJSON(data);

			$('#submit_category').html('Save <i class="icon-paperplane ml-2"></i>').attr('disabled' , false);	

			  if(parseJson.status == 200){

				  $('#add_category_form')[0].reset();

				  $('#workshop_details_section').load(document.URL + ' #workshop_details_section');

			   }  

			 $("#response_add_category").html(parseJson.msg);  

         }	

     });

  });

  /*End*/

  /*Add Workshop address script start*/

$(document).on('submit', '#workshop_adrs_form', function(e) {

        $('#response_workshop_adrs').html(" ");

        $('#submit_address').html('Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);

        e.preventDefault();

        $.ajax({

            url: base_url + "/commonAjax/add_workshop_adrs",

            type: "POST",

            data: new FormData(this),

            contentType: false,

            cache: false,

            processData: false,

            success: function(data) {
                console.log(data)
                    //$('#response_workshop_adrs').html(data);

                var parseJson = jQuery.parseJSON(data);

                $('#submit_address').html(' Save <i class="icon-paperplane ml-2"></i>').attr('disabled', false);
                console.log(parseJson.status)
                if (parseJson.status == 100) {

                    $("#response_workshop_adrs").html(parseJson.msg);

                    $('#workshop_address_section').load(document.URL + ' #workshop_address_section');

                    $('#workshop_adrs_form')[0].reset();

                }

                if (parseJson.status == 200) {

                    $(".close").click();
                    //$("#response_workshop_adrs").html(parseJson.msg);
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                    setTimeout(function() { location.reload(); }, 1000);

                }

                if (parseJson.status == 400) {

                    $.each(parsedJson.error, function(key, value) {

                        errorString += '<div class="notice notice-success"><strong>Success , </strong>' + value + ' .</div>';

                    });

                    $('#response_workshop_adrs').html(errorString);

                }

            }

        });

    });

  /*End*/

  

  /*Add Address modal popup script start*/

    $(document).on('click','.popup_btn',function(e){

		e.preventDefault();

      var popup_name =$(this).data('modalname');

	  $("#"+popup_name).modal('show');

	});

  /*End*/

  

  /*Add Bank Details script start*/

	$(document).on('submit','#bank_details_form',function(e){

     $('#response_bank_details').html(" ");

	 $('#bank_details_sbmt').html('Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);

	 e.preventDefault();

      $.ajax({

          url: base_url+"/commonAjax/add_bank_details",

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

				setTimeout(function(){ window.location.href  = base_url+"/bank_details" } , 1000);

			  }

			 else if(parseJson.status == 100){

			    $("#msg_response_popup").modal('show');

				$("#msg_response").html(parseJson.msg);

			  } 

         }	

     });

  });

  /*End */

  /*add buisiness details script start*/

     $(document).on('submit','#business_details_form',function(e){

     $('#response').html(" ");

	 $('#business_details_sbmt').html('Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);

	 e.preventDefault();

      $.ajax({

          url: base_url+"/commonAjax/add_business_details",

          type: "POST",        

          data: new FormData(this),

          contentType: false,

          cache: false,

          processData:false,  

          success: function(data){

		    var errorString = '';

            var parseJson = jQuery.parseJSON(data);

			$('#business_details_sbmt').html(' Submit <i class="icon-paperplane ml-2"></i>').attr('disabled' , false);

				

			if(parseJson.status == 400){

				$.each(parseJson.error, function(key , value) {

				  errorString += '<div class="notice notice-danger"><strong> Note , </strong>'+ value+' .</div>';

				});

				 $("#msg_response_popup").modal('show');

				 $("#msg_response").html(errorString);

			  }

		  	else if(parseJson.status == 200){

			    $("#msg_response_popup").modal('show');

				$("#msg_response").html(parseJson.msg);

				setTimeout(function(){ window.location.href  = base_url+"/add_business_details" } , 1000);

			  }

			 else if(parseJson.status == 100){

			    $("#msg_response_popup").modal('show');

				$("#msg_response").html(parseJson.msg);

			  }  

         }	

     });

  });

  /*End*/

  /*Reset Password  script start*/	 

	 $(document).on('click','#resetPwdbtn' , function(e){

		 e.preventDefault();

	     $("#resetPwdbtn").html('Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled',true);  

	    $("#response").html(" ");

        var sendOn = $('#email').val();

		if(sendOn != ""){

			 $.ajax({

			  url: base_url +"/password/send_reset_notification",

			  data:{sendOn:sendOn},

			  type:"GET",

			  success:function(response){

				 //alert(response);

				 $("#resetPwdbtn").html("Reset&nbsp;<i class='icon-circle-right2 ml-2'></i>").attr('disabled',false);  

				 $("#response").html(response);

			  }

			});

		  }

		else{

			 $("#resetPwdbtn").html("Reset Password").attr('disabled' , false);  

			 $("#response").html('<div class="notice notice-danger"><strong>Wrong, </strong> Please Enter the valid Email / Mobile Number   !!! . </div>');

		  }  

  }); 
/*Reset Password  script End*/

 /*gET cOUNBTRY nAME sCRIPT sTART*/ 
  if(page == "edit_workshop" || page == "bank_details" || page == "company_profiles" || page == "add_address_details"){
	  $.ajax({
	     url:base_url+"/commonAjax/get_country",
		 success: function(data){
		    var parseJson = jQuery.parseJSON(data);
			var html_content = ''; 
			    html_content = '<option value="0">Select Country Name</option>'; 
		  if(page == "bank_details" || page == "add_address_details" || page == "company_profiles"){
			  var country_id = $("#country_edit_id").val();
			  var country_name = $("#country_edit_name").val();
			  if(country_id != "" && country_name != ""){
				  var c = country_id.trim()+"@"+country_name.trim();
				  html_content += '<option value="'+c+'" selected="selected">' + country_name +'</option>';
				}
			  if(parseJson.status == 200){
			    $.each(parseJson.countries , function(index , value){
			       html_content += '<option value="'+value.id+'@'+value.name+'">' + value.name +'</option>';
				})
			  }
			}
		  else{
			 if(parseJson.status == 200){
			    $.each(parseJson.countries , function(index , value){
			       html_content += '<option value='+value.id+'>' + value.name +'</option>';
				})
			  } 
			}		
			if(parseJson.status == 100){
			     html_content = '<option hidden="hidden"></option>'; 
			  } 
			html_content += ''; 
			$(".country").html(html_content); 
		 }
	  });

	}

 <!--End-->
 /*Get state name */
  $(document).on('change','.country',function(){ 
    var country_id = $("#country_1").val();
	var countryArr = country_id.split("@");
	get_state(countryArr);
 });
 /*End*/
 /*Get City by state id*/
    $(document).on('change','.state',function(){ 
     var state = $(".state").val();
	 var stateArr = state .split("@");
	 get_cities(stateArr)
 });

 /*End*/

 

 /*Address Show and hide script start
 $(document).on('change','#country',function(){ 
    var country_id = $("#country").val();
	$.ajax({
	     url:base_url+"/commonAjax/get_city",
		 type:"GET",
		 data:{country_id:country_id},
		 success: function(data){
			var parseJson = jQuery.parseJSON(data);
			var html_content = ''; 
			if(parseJson.status == 200){
			    $.each(parseJson.cities , function(index , value){
				   html_content += '<option value='+value.id+'>' + value.name +'</option>';
				})
			  }
			if(parseJson.status == 100){
			     html_content = '<option hidden="hidden"></option>'; 
			  } 
			html_content += '';  
			$("#city").html(html_content); 
		 }
	  });
 });
 End*/

 

 if(page == "edit_workshop"){
	 /*For Country name and city name*/
	  var country_id = $("#country_id").val()
	  var city_id = $("#city_id").val();

	  if(country_id != ""){

		  $.ajax({

			 url:base_url+"/commonAjax/get_country_name",

			 data:{country_id:country_id},

			 success: function(data){

				if(data != 101){

				   var html_content = '<option selected value='+country_id+'>' + data +'</option>';

				    $("#country").prepend(html_content);

				  }

			 }

		  });

		}

	  if(city_id != ""){

		  $.ajax({

			 url:base_url+"/commonAjax/get_city_name",

			 data:{city_id:city_id},

			 success: function(data){

				if(data != 101){

				   var html_content = '<option selected value='+city_id+'>' + data +'</option>';

				    $("#city").prepend(html_content);

				  }

			 }

		  });

		}	

	}

});



/*Proofile upload code script start*/

$(document).ready(function(e) {

/*Modal Popup show script start*/

  $(document).on('click','#profile_image',function(){

   event.preventDefault();

   $('#profile_pic_modal').modal('show');

  }); 

/*End*/	

$uploadCrop = $('#upload-demo').croppie({

    enableExif: true,

    viewport: {

        width: 200,

        height: 200,

        type: 'circle'

    },

    boundary: {

        width: 300,

        height: 300

    }

});

$('#upload').on('change', function () { 

	var reader = new FileReader();

    reader.onload = function (e) {

    	$uploadCrop.croppie('bind', {

    		url: e.target.result

    	}).then(function(){

    		console.log('jQuery bind complete');

    	});

    }

    reader.readAsDataURL(this.files[0]);

});



$('.upload-result').on('click', function (ev) {

	$uploadCrop.croppie('result', {

		type: 'canvas',

		size: 'viewport'

	}).then(function (resp) {
		//var url = base_url+"/commonAjax/post_profile_image";
		$.ajax({
			url: base_url+"/commonAjax/post_profile_image",
			type: "POST",
			data: {image:resp},
			success: function (data) {
				var parseJson = jQuery.parseJSON(data);
				if(parseJson.status == 100){
				    setTimeout(function(){ location.reload(); } , 1000);
				  }
				$("#response_result").html(parseJson.msg);  
			}
		});

	});

});



});

