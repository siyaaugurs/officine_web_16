/*Get and save model lfunction script start*/

function get_models(makers_id){

  $("#preloader").show();

  $.ajax({

          url:base_url+"/products_ajax/get_model_name",

          method:"GET",

          data:{makers_id:makers_id},

          success:function(data){

             $("#preloader").hide();

             var parseJson = jQuery.parseJSON(data);

			 var html_content = '';

			 if(parseJson.status == 200){

			  html_content += '<option value="0" hidden="hidden">--Select--Car--Model--</option>'; 

			  $.each(parseJson.response , function(index , value){

			    var value_model = value.idModello+"/"+value.ModelloAnno;

			     html_content += '<option value="'+value_model+'">'+ value.Modello +" >> "+ value.ModelloAnno +'</option>';    

			   });

			 $(".models").html(html_content); 

		     $(".versions").empty();

		     $(".versions").append($('<option>',{value:0}).text('--Select--Car--Model--first--'));

             }

          },

		error: function(xhr, error){

		  $("#preloader").hide();

		}

      });

}

/*End*/

/*Get All version*/

function get_versions(model){

  $("#preloader").show();

   $.ajax({

         url:base_url+"/products_ajax/get_version_name",

         method:"GET",

         data:{model_value:model},

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

               $(".versions").html(html_content); 

			} 

         },

		error: function(xhr, error){

		 $("#preloader").hide();

		}

     }); 

}

/*End*/

/*Get Groups script Start*/

  function get_groups(maker ,model ,  versions , language){

    $("#preloader").show();

   	$.ajax({

         url:base_url+"/products_group/save_groups",

         method:"GET",

         data:{model_value:model , versions:versions ,maker:maker , language:language},

         success:function(data){

            $("#preloader").hide();

			 var parseJson = jQuery.parseJSON(data);

				if(parseJson.status == 100){
					$("#preloader").hide();

					// $("#msg_response_popup").modal('show');

					// $("#msg_response").html(parseJson.msg);

				} 

			    groups = $('.groups');

				groups.empty();

				groups.append( $('<option>' , {value:0}).text('Select Category'))

				if(parseJson.status == 200){

				   $.each(parseJson.response , function(index , value){

				      groups.append( $('<option>' ,{value:value.id}).text(value.group_name))

				   });

				   

				} 

         },

		error: function(xhr, error){

		 $("#preloader").hide();

		}

     }); 

  }

/*End*/

/*Get Sub Groups script Start*/

  function get_sub_groups(group_id){

      $.ajax({

         url:base_url+"/products_group/save_sub_groups",

         method:"GET",

         data:{group_id:group_id},

         success:function(data){

            $("#preloader").hide();

			 var parseJson = jQuery.parseJSON(data);

				if(parseJson.status == 100){

					// $("#msg_response_popup").modal('show');

					// $("#msg_response").html(parseJson.msg);

				} 

			    sub_groups = $('.sub_category');

				sub_groups.empty();

				sub_groups.append($('<option>' , {value:0}).text('Select Sub Category').attr('hidden', 'hidden'))

				if(parseJson.status == 200){

				   $.each(parseJson.response , function(index , value){

				    sub_groups.append( $('<option>' ,{value:value.id}).text(value.group_name))

				   });

				}

				if(parseJson.status == 404){

				  sub_groups.append( $('<option>' , {value:0}).text('No Sub category available !!!'))

				} 

         },

		error: function(xhr, error){

		 $("#preloader").hide();

		}

     });

  }

/*End*/

/*Get N3 category */

function get_n3_categories(version , sub_group_id , language){

  $.ajax({

         url:base_url+"/products_group/save_n3_category",

         method:"GET",

         data:{sub_group_id:sub_group_id , version:version , language:language},

         success:function(data){

            $("#preloader").hide();

			 var parseJson = jQuery.parseJSON(data);

				if(parseJson.status == 100){

					// $("#msg_response_popup").modal('show');

					// $("#msg_response").html(parseJson.msg);

				} 

			    items = $('.items');

				items.empty();

				items.append($('<option>' , {value:0}).text('Select Sub Category (N3)').attr('hidden', 'hidden'))

				if(parseJson.status == 200){

				  items.append(parseJson.response); 

				}

				if(parseJson.status == 404){

				  sub_groups.append( $('<option>' , {value:0}).text('No Sub category available !!!'))

				} 

         },

		error: function(xhr, error){

		 $("#preloader").hide();

		}

     }); 

}

/*End*/

$(document).ready(function(e) {

 /*Get All N3 Category*/

 $(document).on('change','.sub_category',function(e){

      sub_group_id = $(this).val();

	  var action = $(this).data('action');

	  version = $(".versions").val();

	  var language = $('html').attr('lang');

	  if(action == "get_n3_category" && sub_group_id != ""){

		  get_n3_categories(version , sub_group_id , language);

	   }  

   });

 /*End*/	

 /*Get All sub groups*/

  $(document).on('change','.groups',function(e){

      group_id = $(this).val();

	  var action = $(this).data('action');

	  if(action == "get_sub_category" && group_id != ""){

		  get_sub_groups(group_id);

	   }  

   });

 /*End*/

 /*Get All groups category script start*/

   $(document).on('change','.versions',function(e){

      var versions = $(this).val();

	  var language = $('html').attr('lang');

	  var action = $(this).data('action');

	  var maker = $('.makers').val();

	  var model = $('.models').val();

	  if(action == "get_groups" && model != ""){

		  get_groups(maker ,model ,  versions , language);

		}  

   });

 /*End*/	

 /*Get Version script start*/

   $(document).on('change','.models',function(e){

      var model = $(this).val();

	  var action = $(this).data('action');

	  if(action == "get_versions" && model != ""){

		  get_versions(model);

		}  

   }); 

  /*End*/

  /*Get Models*/

  $(document).on('change','.makers',function(e){

     var makers_id = $(this).val();

	 var action  = $(this).data('action');

	 if( makers_id != "" && action == "get_models"){

	   get_models(makers_id);

	 }	   

  });

/*End*/



  

});