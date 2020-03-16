$(document).ready(function(e) {
   
	$(document).on('click', '.edit_car_maintinance_details', function(e){
		e.preventDefault();
		var car_maintainance_id = $(this).data('maintainanceid');
		if(car_maintainance_id) {
			$.ajax({
				url: base_url+"/car_maintinance/get_car_maintainance_details",
				type: "GET",        
				data: {car_maintainance_id:car_maintainance_id},
				success: function(data){
					var parseJson = jQuery.parseJSON(data);
					if(parseJson.status == 200){
						$('#items_repairs_servicestimes_id').val(car_maintainance_id);
						$("#car_maintainance_hourly_rate").val(parseJson.hourly_cost);
						$("#car_maintainance_max_appointment").val(parseJson.max_appointment);
						$("#edit_car_maintainance_details").modal('show');
					}
				}
			});
		}
	});
	$(document).on('submit', '#edit_car_maintainance_service_details', function(e){
		$('#response').html(" ");
		$("err_response").html(" ");
		$('#edit_car_maintainance_details_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
		e.preventDefault();
		$.ajax({
			url: base_url+"/car_maintinance/edit_maintainance_service_details",
			type: "POST",        
			data: new FormData(this),
			contentType: false,
			cache: false,
			processData:false,  
			success: function(data){
				errorString = '';
				$('#edit_car_maintainance_details_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
				var parseJson = jQuery.parseJSON(data); 
				if(parseJson.status == 200){
					$(".close").click();	
					$("#msg_response_popup").modal('show');
					$("#msg_response").html(parseJson.msg);
					setTimeout(function(){ location.reload(); } , 1000);
				}  
				if (parseJson.status == 400) {
                    $.each(parseJson.error, function(key, value) {
                        errorString += '<div class="notice notice-danger"><strong>Wrong , </strong>' + value + ' .</div>';
                    });
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(errorString);
                }
				if(parseJson.status == 100){
					$("#response").html(parseJson.msg);
				}	 
			} 
		});
	});
    $(document).on('click', '#add_new_service', function(e){
		e.preventDefault();
		/* var version_id = $('#version_id').val();
		$('#maintainance_version').val(version_id);
		if(version_id == "" || version_id == 0) {
			$('#k_time_div').hide();
		} else {
			$('#k_time_div').show();
		} */
		$("#add_maintenance_service").modal('show');
	});
	$(document).on('submit', '#add_maintenance_services_form', function(e){
		$('#response').html(" ");
		$("err_response").html(" ");
		$('#add_car_maintenance_details_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
		e.preventDefault();
		$.ajax({
			url: base_url+"/car_maintinance/add_maintenance_services",
			type: "POST",        
			data: new FormData(this),
			contentType: false,
			cache: false,
			processData:false,  
			success: function(data){
				errorString = '';
				$('#add_car_maintenance_details_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
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
					$("#response").html(parseJson.msg);
				}	 
			} 
		});
	});
/*Search Car Maintinance script start*/

$(document).on('click','#search_car_maintinance_services',function(e){
	e.preventDefault();
	var service_time_id = $("#item_repair_time_id").val();
	var language = $('html').attr('lang');
	var version_id = $("#version_id").val();
	if(service_time_id != ""){
	   $("#preloader").show();
	   $.ajax({
		 url:base_url+"/car_maintinance/search_item_services",
		 method:"GET",
		 data:{service_time_id:service_time_id  , version_id:version_id , language:language},
		 complete:function(e , xhr , settings){
			$("#car_maintinance_ColWrap").empty();
		    if(e.status == 200){
			    $("#preloader").hide();
				$("#car_maintinance_ColWrap").html(e.responseText);
			  }
		 } ,
		error: function(xhr, error){
			$("#preloader").hide();
				//$('#new_group_btn').html('Save &nbsp;<i class="icon-paperplane ml-2"></i>').attr('disabled' , false);
				$("#msg_response_popup").modal('show');
				$('#msg_response').html('<div class="notice notice-danger"><strong>Wrong , </strong>Something Went Wrong please try again  .</div>');
			}
	 });
	  }
});
/*End */	
 /*Change status script start*/
 $(document).on('click','.change_item_service_status',function(e){
	e.preventDefault();
	$this = $(this);
	var service_item_id = $(this).data('serviceitemid');
	var status = $(this).data('status');
	if(service_item_id != ""){
	   $.ajax({
		 url:base_url+"/car_maintinance/change_item_service_status",
		 method:"GET",
		 data:{service_item_id:service_item_id , status:status},
		 success:function(data){
			  if(status == "P"){
				 $this.html("<i class='fa fa-toggle-off'></i>").data('status' , 'A');
				 
			  }
			else if(status == "A"){
				$this.html("<i class='fa fa-toggle-on'></i>").data('status' , 'P');;
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
  /*save and get products items services times*/
    $(document).on('change','#item_repair_time_id',function(e){
	  e.preventDefault();
	  times_id = $(this).val();
	  var language = $('html').attr('lang');
	  if(times_id != 0){
	     $("#preloader").show();
	     $.ajax({
			url:base_url+"/car_maintinance/save_services_time",
			method:'GET',
			data:{times_id:times_id , language:language},
			complete:function(e , xhr , settings){
                if(e.status == 200){
					$("#preloader").hide();	
				}
			},
			error: function(xhr, error){
				$("#preloader").hide();
				 //$('#new_group_btn').html('Save &nbsp;<i class="icon-paperplane ml-2"></i>').attr('disabled' , false);
				 $("#msg_response_popup").modal('show');
				 $('#msg_response').html('<div class="notice notice-danger"><strong>Wrong , </strong>Something Went Wrong please try again  .</div>');
				}	
			 
		 });	     
		}
	});
  /*End*/  
  
    $(document).on('click', '.edit_maintenance_service', function(e){
		e.preventDefault();
		$('#priority_err').html('');
		$this = $(this);
		var service_id = $(this).data('serviceitemid');
		$.ajax({
			url: base_url+"/car_maintinance/get_maintenance_details",
			method: "GET",
			data: {serviceId:service_id},
			success: function(data){
				var parseJson = jQuery.parseJSON(data);
				if (parseJson.status == 200) {
					var version_id = $('#version_id').val();
					$('#edit_maintainance_version').val(version_id);
					if(version_id == "" || version_id == 0) {
						$('#edit_k_time_Div').hide();
					} else {
						$('#edit_k_time_Div').show();
					}
					$("#maintenance_id").val(parseJson.response.id);
					$("#edit_item_name").val(parseJson.response.item);
					$('#edit_front_rear').find("option[value='"+ parseJson.response.front_rear +"']").attr('selected','selected');
					$('#edit_left_right').find("option[value='"+ parseJson.response.left_right +"']").attr('selected','selected');
					$("#edit_kromeda_description").html(parseJson.response.action_description);
					$("#edit_our_description").html(parseJson.response.our_description);
					$("#edit_kromeda_time").val(parseJson.response.time_hrs);
					$("#edit_our_time").val(parseJson.response.our_time);
					$("#edit_info").val(parseJson.response.id_info);
					$(".edit_priority").val(parseJson.response.priority);
					$('#edit_language').find("option[value='"+ parseJson.response.language +"']").attr('selected','selected');
					$("#edit_maintenance_service").modal({
						backdrop:'static',
						keyboard:false
					});
				}
			}
		});
	});
	$(document).on('submit', '#edit_maintenance_services_form', function(e){
		$('#response').html(" ");
		$("err_response").html(" ");
		$('#edit_car_maintenance_details_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
		e.preventDefault();
		$.ajax({
			url: base_url+"/car_maintinance/edit_maintenance_services",
			type: "POST",        
			data: new FormData(this),
			contentType: false,
			cache: false,
			processData:false,  
			success: function(data){
				errorString = '';
				$('#edit_car_maintenance_details_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
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
					$("#response").html(parseJson.msg);
				}	 
			} 
		});
	});
	$(document).on('click', '#search_by_item_name', function(e) {
        $("#preloader").show();
        e.preventDefault();
        item_name = $("#item_name").val();
        var language = $('html').attr('lang');
        if (item_name != "") {
            $.ajax({
                url: base_url + "/car_maintinance/search_by_item",
                method: "GET",
                data: { item_name: item_name, language: language },
                complete: function(e, xhr, setting) {
                    console.log(e.responseText)
                    if (e.status == 200) {
                        $("#preloader").hide();
                        $("#car_maintinance_ColWrap").html(e.responseText);
                    }
                },
                error: function(xhr, error) {
                    $("#preloader").hide();
                }
            });
        } else {
            alert("please enter the Item Name !!!")
        }
    });

});