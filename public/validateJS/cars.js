 /*Get Car version script start*/
 $(document).on('change','.models',function(){
    var model_value = $(this).val();
    $("#preloader").show();
    if( model_value != ""){
     $.ajax({
         url:base_url+"/products_ajax/get_version_name",
         method:"GET",
         data:{model_value:model_value},
         success:function(data){
            $("#preloader").hide();
			 var parseJson = jQuery.parseJSON(data);
			 var html_content = '';
			if(parseJson.status == 100){
				$("#msg_response_popup").modal('show');
				$("#msg_response").html(parseJson.msg);
			} 
			if(parseJson.status == 400){
				html_content += '<option value="0">No Version Available </option>'; 
			} 
			if(parseJson.status == 200){
               html_content += '<option value="0">--Select--Car--version--</option>'; 
               var version_name = '';
			   $.each(parseJson.response , function(index , value){
				 version_name = value.Versione+" , "+value.Motore+" , "+value.ModelloCodice+" , "+value.idVeicolo+" , "+value.Body+" , "+value.Cm3;
				 html_content += '<option value="'+value.idVeicolo+'">'+ version_name +'</option>';      
                });
            }
		   $(".versions").html(html_content); 
         },
			error: function(xhr, error){
		     $("#preloader").hide();
		    }
     });
    }	   
 });
 /*End*/
 $(document).on('change','.makers',function(){
     $("#preloader").show();
     var makers_id = $(this).val();
     if( makers_id != ""){
	    $.ajax({
		  url:base_url+"/products_ajax/get_model_name",
          method:"GET",
          data:{makers_id:makers_id},
          success:function(data){
			 $("#preloader").hide();
             var parseJson = jQuery.parseJSON(data);
			 var html_content = '';
			 if(parseJson.status == 200){
			  html_content += '<option value="0">--Select--Car--Model--</option>'; 
			  $.each(parseJson.response , function(index , value){
			    var value_model = value.idModello+"/"+value.ModelloAnno;
			     html_content += '<option value="'+value_model+'">'+ value.Modello +" >> "+ value.ModelloAnno +'</option>';      
			   });
			 $(".models").html(html_content); 
		   /*  $("#version_id").empty();
		     $("#version_id").append($('<option>',{value:0}).text('--Select--Car--Model--first--'));*/
             }
          },
		error: function(xhr, error){
		  $("#preloader").hide();
		}
      });
	 }	   
  });