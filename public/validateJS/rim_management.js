$(document).ready(function(e){
/*Rim image remove script start */
$(document).on('click','.remove_rim_image',function(e){
	 con = confirm("Are you sure want to remove this image !!!");
	 var image = $(this);
     var image_id = image.data('imageid');
	 if(con == true){
	    $.ajax({
			url:base_url+"/rim_ajax/remove_image",
			method:"GET",
			data:{image_id:image_id},
			success:function(data){
			   if(data != 200){
					$('#msg_response').html(data);
					$("#msg_response_popup").modal('show');   
				 }
			   else{
				   image.closest('.image_grid_col').remove();
				   //$(".tyre_grid_col").
				 }	 
			}
	    });
	   }
  });
/*End*/	
  /*Get Rim Script Start*/
   $(document).on('click' , '#search_rim_from_database' , function(e){
     var maker_name = $("#car_makers").val();	
    if(maker_name != ""){
      //$("#preloader").show();
      $.ajax({
        url:base_url+"/rim_ajax/get_rim_from_database",
        beforeSend:function(){
          $("#rim_ColWrap").empty(); 
        },
        method:"GET",
        data:{maker_name:maker_name},
        success:function(data){
          <!-- $("#preloader").hide();
            $('#rim_ColWrap').html(data);
        },
        error: function(xhr, error){
          $("#msg_response_popup").modal('show');
          $("#msg_response").html('<div class="notice notice-danger"><strong>Wrong </strong> Something Went Wrong please try again !!! </div>');
        }
     });
    }  
  });
  /*End*/	
   /*Get getRimWorkmanshipForRimType script start */
   $(document).on('change' , '#rim_type' , function(){
    rim_type = $(this).val();
    car_makers = $("#car_makers").val();
    if(rim_type != ""){
        $.ajax({
            url:base_url+"/rim_ajax/get_rim_workmanship_for_rim_type",
            method:"GET",
            data:{rim_type:rim_type , maker:car_makers},
            success:function(data){
               parseJson = jQuery.parseJSON(data);
			   rim_workship_rim_type = $("#rim_workship_rim_type").empty();
			   rim_workship_rim_type.append($('<option>' , {value:0}).text("Select Rim Workship Rim Type "));
			   if(parseJson.status == 200){
				   $.each(parseJson.response , function(index , value){
				       rim_workship_rim_type.append($('<option>' , {value:value.rimWorkmanshipForRimTypeResponse}).text(value.rimWorkmanshipForRimTypeResponse)); 
				   });
				 }
			   if(parseJson.status == 100){
				   $("#msg_response_popup").modal('show');
                   $("#msg_response").html(parseJson.msg); 
				 }
            },
            error: function(xhr, error){
                $("#preloader").hide();
                $("#msg_response_popup").modal('show');
                $("#msg_response").html('<div class="notice notice-danger notice-sm"><strong> Wrong </strong>something went wrong please try again  !!! </div>');
            }

        });
    }
 });
   /*End*/ 
   /*Get get_rim_type_for_manufacturer script Start*/
     $(document).on('change' , '#car_makers' , function(){
        maker_name = $(this).val();
        if(maker_name != ""){
              $("#preloader").show();
		    $.ajax({
                //url:base_url+"/rim_ajax/get_rim_type_manufacturar",
                url:base_url+"/rim_ajax/get_rim",
			    method:"GET",
                data:{maker_name:maker_name},
                success:function(data){
                   $("#preloader").hide();
				   parseJson = jQuery.parseJSON(data);
                   if(parseJson.status == 200){
                       rim_type = $("#rim_type").empty();
                       rim_type.append($('<option>' , {value:0}).text("Select Rim Type"));
                       rim_type_response = jQuery.parseJSON(parseJson.response);
                       console.log(rim_type_response)
                        $.each(rim_type_response.rimType, function(index , value){
                            rim_type.append($('<option>' , {value:value}).text(value));
                        }); 
                   }
                },
                error: function(xhr, error){
                    $("#preloader").hide();
                    $("#msg_response_popup").modal('show');
					$("#msg_response").html('<div class="notice notice-danger notice-sm"><strong> Wrong </strong>something went wrong please try again  !!! </div>');
                }
            });
        }
     });
   /*End*/
    /*Save tyre  details script start*/
  	$(document).on('submit','#edit_rim_details_by_admin',function(e){
		$('#msg_response').html(" ");
		$('#edit_tyre_details').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
		e.preventDefault();
		$.ajax({
			url: base_url+"/rim_ajax/save_rim_details",
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
						errorString += '<div class="notice notice-info"><strong>Note , </strong>'+ value+' .</div>';
					});
					$('#msg_response').html(errorString); 	
		  		}
		  		if(parseJson.status == 200){
					$('#msg_response').html(parseJson.msg);
					//setTimeout(function(){ location.reload(); } , 1000);
				}
			},
			error: function(xhr, error){
				$('#msg_response').html('<div class="notice notice-danger"><strong>Wrong </strong> Something Went Wrong !!! </div>'); 
			},
			complete:function(xhr , setting ){
				$("#msg_response_popup").modal('show');
				$('#edit_tyre_details').html('Save&nbsp; <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
			}						
		});
 	});

	/*End*/
})