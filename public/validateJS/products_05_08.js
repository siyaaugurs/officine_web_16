/*$.fn.get_alert = (this) => {
  alert("yes");
  return this;
}*/
/*Get And save Services time script start*/
function get_and_save_services_time(){
  $("#car_maintinance_body").html(" ");
   version_id = $("#version_id").val();
   var language = $('html').attr('lang');
   $.ajax({
	url:base_url+"/car_maintinance/save_services_time_id",
	method:"GET",
	data:{version_id:version_id , language:language},
	complete:function(e , xhr , settings){
	    $("#item_repair_time_id").empty();
		 if(e.status == 200){
		     var parseJson = jQuery.parseJSON(e.responseText); 
			  if(parseJson.status == 200){
			     $.ajax({
					url:base_url+"/car_maintinance/get_services_time_id",
					method:"GET",
					data:{version_id:version_id , language:language},
					complete:function(e , xhr , settings){
						 if(e.status == 200){
							 var parseJson = jQuery.parseJSON(e.responseText); 
							  if(parseJson.status == 200){
								 times_id = $("#item_repair_time_id");
								 times_id.append( $('<option>' ,{value:0}).text('Select Time id'));
								  $.each(parseJson.response , function(index , value){
								   times_id.append( $('<option>' , {value:value.id}).text(value.repair_times_id))
								  });
								}
						   }
					  }
				   });   
			    }
		   }
	  }
   });
}
/*End*/

/*get groups items */
   function get_groups_item(){
      group_id = $("#group_item").val();
	  var language = $('html').attr('lang');
	  if(group_id != 0 && group_id != ""){
		$.ajax({
			url:base_url+"/products_category/get_groups_items_database",
			method:"GET",
			data:{group_id:group_id , language:language},
			complete:function(e , xhr , settings){
				$("#item_id").empty();
				if(e.status == 200){
					var parseJson = jQuery.parseJSON(e.responseText); 
					console.log(parseJson);
					if(parseJson.status == 200){
						$("#item_id").append($('<option>' ,{value:0 }).text('--Select--Item--'));
						$.each(parseJson.response , function(index , value){
						front_rear = '';
						left_right = '';
						
							if(value.front_rear == ""){
								front_rear = '';
							}
							else{
							front_rear = value.front_rear; 
							}   
							if(value.left_right == ""){
								left_right = "";
							}
							else{
							left_right = value.left_right;
							}  
							
						var text_name = value.item+" "+ front_rear +"  "+left_right;
						$("#item_id").append($('<option>' ,{value:value.id }).text(text_name));
							$("#preloader").hide();
						});
					}
					if(parseJson.status == 404){
					    	$("#item_id").append($('<option>' ,{value:0 }).text('No Category Items Available '));
					}
					if(parseJson.status == 100){
						//alert("Something Went Wrong please try again ");
					}	
				}
			    },
			error: function(xhr, error){
				$("#preloader").hide();
			}
		});   
		}
	}
/*End*/
function get_and_save_products_item(group_id){
	$("#preloader").show();
	var language = $('html').attr('lang');
	if(group_id != ""){
	   $.ajax({
	   url:base_url+"/save_products_item_05_08",
	   method:"GET",
	   data:{group_id:group_id , language:language},
	   complete:function(e , xhr , setting){
		  if(e.status == 200){
			   $.ajax({
					 url:base_url+"/get_products_item_05_08",
					 method:"GET",
					 data:{group_id:group_id , language:language},
					 complete:function(e , xhr , settings){
					     $("#preloader").hide();
						$("#item_id").empty();
						if(e.status == 200){
						  var parseJson = jQuery.parseJSON(e.responseText); 
						  if(parseJson.status == 200){
							 $("#item_id").append($('<option>' ,{value:0 }).text('--Select--Item--'));
							  $.each(parseJson.response , function(index , value){
							    front_rear = '';
								left_right = '';
								
								 if(value.front_rear == ""){
								     front_rear = '';
								   }
								 else{
								    front_rear = value.front_rear; 
								   }   
								  if(value.left_right == ""){
								     left_right = "";
								   }
								  else{
								    left_right = value.left_right;
								  }  
								   
								var text_name = value.item+" "+ front_rear +"  "+left_right;
								$("#item_id").append($('<option>' ,{value:value.id }).text(text_name));
								 $("#preloader").hide();
							  });
							}
						  if(parseJson.status == 100){
							  //alert("Something Went Wrong please try again ");
							}
						  if(parseJson.status == 400){
							  $("#item_id").append($('<option>' ,{value:0}).text('No category available !!!'));
							  $("#preloader").hide(); 
							}		
						}
					},
					error: function(xhr, error){
					  $("#preloader").hide();
					}
                  });   
			}
	   },
	   error: function(xhr, error){
		 $("#preloader").hide();
	   }   
	  });
	}
}	 
	/*End*/


/*function get_and_save_products_item(){
	$('#item_id').empty();
	//$("#preloader").show();
	group_id = $("#group_item").val();
	var_arr = group_id.split('/');
	//var language = $('html').attr('lang');
	if(group_id != ""){
	   $.ajax({
	   url:base_url+"/get_and_save_products_item",
	   method:"GET",
	   data:{group_id:group_id , type:type},
	   complete:function(e , xhr , setting){
		 $("#preloader").hide();
		  if(e.status == 200){
			  parseJson = jQuery.parseJSON(e.responseText);
			  if(parseJson.status == 200){
				$("#item_id").append($('<option>' ,{value:0 }).text('Select Item'));
				$.each(parseJson.response , function(index , value){
					console.log(value);
					front_rear = '';
					left_right = '';
						  if(value.ap == ""){
							front_rear = '';
						}
						else{
						front_rear = value.ap; 
						}   
						if(value.ds == ""){
							left_right = "";
						}
						else{
						left_right = value.ds;
						}  
					var text_name = value.Voce+" "+ front_rear +"  "+left_right;
					item_val = var_arr[0]+"/"+value.idVoce;
					$("#item_id").append($('<option>' ,{value:item_val}).text(text_name));
						$("#preloader").hide();
					});
			  }
			 if(parseJson.status == 400){
				$("#item_id").append($('<option>' ,{value:0 }).text('No Record found !!!'));
			  }
			 if(parseJson.status == 100){
				$("#msg_response").html(parseJson.msg);
				$("#msg_response_popup").modal('show');
			  }  
			}
	   },
	   error: function(xhr, error){
		 $("#preloader").hide();
	   }   
	  });
	}
}	 */
/*End*/
/*Append Groups in select*/
function append_groups(data){
	var parseJson = jQuery.parseJSON(data);
	if(parseJson.status == 200){
		var html_content = '';
		 let texts = {} ;
		  $.each(parseJson.response , function(index , value){
			if(value.parent_id == 0){
			   texts[value.id] = {id:value.id,text:value.group_name,childrens:[]}
			  }
		  });
		  $.each(parseJson.response , function(index , value){
			if(value.parent_id != 0){
				  texts[value.parent_id]['childrens'].push({id:value.id,text:value.group_name});
			  }
		  });
		  $("#group_item").empty();
		  $.each(texts, function(i, value) {
			  $("#group_item").append($('<option>' , {value:value.id}).text(`${value.text}`))
			  if(value.childrens.length) {
				  $.each(value.childrens, function(i, child) {
					  $("#group_item").append($('<option>' , {value:child.id}).text(`${value.text} >> ${child.text}`))
				  })
			  }
		  })
		  
	   } 
	  $("#group_item").prepend("<option value='0' selected>Select category</option>");
	  if(parseJson.status == 100){
		  $("#group_item").html("<option>--No--Group--Available--</option>");
		  $("#group_item_inventory").html("<option>--No--Group--Available--</option>");
	   }
}
/**Exnd */
/*products category list get api and save*/
 function group_sub_group_save_1(makers_id ,models ,car_version_id , language){
	$("#preloader").show(); 
	$.ajax({
			url:base_url+"/products_category/get_and_save_group",
			method:"GET",
			data:{makers_id:makers_id ,models:models, car_version_id:car_version_id ,language:language},
			complete:function(e, xhr, settings){
			    if(e.status == 200){
					$("#preloader").hide();  
				}
			 },
			error: function(xhr, error){
		     $("#preloader").hide();
		     
		    }
		});
   }
/*End*/

/*Get Group and sub group save*/ 
   function group_sub_group_save(makers_id ,models ,car_version_id , language){
	  $("#preloader").show(); 
	$.ajax({
			url:base_url+"/products_category/get_and_save_group",
			method:"GET",
			data:{makers_id:makers_id ,models:models, car_version_id:car_version_id ,language:language},
			complete:function(e, xhr, settings){
			    if(e.status == 200){
					$("#group_item").html('<option value="0">--First--Select--Car--Version--</option>')
				     $.ajax({
                         url:base_url+"/products_category/get_part_category_database_new",
                         method:"GET",
                         data:{makers_id:makers_id , models:models , car_version_id:car_version_id , language:language},
                        complete:function(e , xhr , settings){
							var parseJson = jQuery.parseJSON(e.responseText);
							if(parseJson.status == 200){
								$("#group_item").html(parseJson.response);
							    $("#preloader").hide(); 
							  }
							if(parseJson.status == 100){
		                       $("#group_item").html("<option>--No--Group--Available--</option>");
		                        $("#group_item_inventory").html("<option>--No--Group--Available--</option>");
	                           }  
							//console.log(e.responseText);
							//append_groups(e.responseText);
						},
						error: function(xhr, error){
						$("#preloader").hide();
						}
                     });
				}
			 },
			error: function(xhr, error){
		     $("#preloader").hide();
		    }
		});
   }
/*End*/
/*save products*/
function save_others_cross_products(group_item_id){
     if(group_item_id != "" && group_item_id != 0){
	     $.ajax({
			 url:base_url+"/products_category/get_and_save_otherCrossProducts",
			 method:"GET",
			 data:{group_item_id:group_item_id},
			 complete:function(e , xhr ,setting){
				$("#preloader").hide();
				if(e.status == 200){
					 $("#preloader").hide();
					 //$("#user_data_body").html(e.responseText);
				  }
			 },
			 error: function(xhr, error){
			   $("#preloader").hide();
			 }
		 });
	  }
	 else{
	    alert("Please Select correct items");
	  } 
}
/*End*/
/*Get products items script sstart */
function get_cross_other_cross_products(group_item_id){
	if(group_item_id != "" && group_item_id != 0){
		$("#preloader").show();
		$.ajax({
			url:base_url+"/save_cross_and_otherCrossProducts05_08",
			method:"GET",
			data:{group_item_id:group_item_id},
			complete:function(e , xhr ,setting){
			   $("#preloader").hide();
			   if(e.status == 200){
				  console.log(e.responseText);  
				 }
			},
			error: function(xhr, error){
			  $("#preloader").hide();
			}
		});
	 }
	else{
	   //alert("Please Select correct items");
	 }
}
/*End*/

/*save_get_other_cross_products*/
function save_get_other_cross_products(group_item_id){
	if(group_item_id != "" && group_item_id != 0){
		 $.ajax({
			 url:base_url+"/products_category/get_and_save_otherCrossProducts",
			 method:"GET",
			 data:{group_item_id:group_item_id},
			 complete:function(e , xhr ,setting){
				$("#preloader").hide();
				if(e.status == 200){
					$.ajax({
						 url:base_url+"/products_ajax/get_products_inventory",
						 method:"GET",
						 data:{group_item_id:group_item_id},
						 complete:function(e , xhr ,setting){
							 var parseJson = jQuery.parseJSON(e.responseText);
							$("#preloader").hide();
							if(parseJson.status == 200){
								var html_content = '';
								html_content += '<option hidden="hidden">--Select--Product--<option>'; 
								$.each(parseJson.response , function(index , value){
									html_content += '<option value="'+value.id+'">'+ value.listino +'</option>';      
									});
								$("#inventory_product").html(html_content);
								$("#preloader").hide(); 
								 //$("#inventory_product").html(parseJson.response);
							}
							if(parseJson.status == 100){
								$("#inventory_product").html("<option>--No--Products--Available--</option>");
							}
						 },
						 error: function(xhr, error){
						   $("#preloader").hide();
						 }
					 });
					 //$("#preloader").hide();
					 //$("#user_data_body").html(e.responseText);
				  }
			 },
			 error: function(xhr, error){
			   $("#preloader").hide();
			 }
		 });
	  }
	 else{
		alert("Please Select correct items");
	  }
}
/*End*/
$(document).ready(function(e) {

	
/*Get Products getCross and other Cross API*/
$(document).on('change','#item_id',function(){
   //$("#preloader").show();
   group_item_id = $("#item_id").val();
   action = $(this).data('action');
   if(action == "get_items_new"){
	  get_cross_other_cross_products(group_item_id);
   }
   if(action == "save_get_products"){
      save_get_other_cross_products(group_item_id); 
   }

   /* else{
       save_others_cross_products(group_item_id);
   } */
   
});
/*End*/    
    /*Search Products by products id*/
$(document).on('click','#search_products_by_id',function(e){
	  $("#preloader").show();
	  e.preventDefault();
	  products_id = $("#products_id").val();
	  var language = $('html').attr('lang');
	  if(products_id != ""){
			$.ajax({
			 url:base_url+"/products_category/search_products_by_id",
			 method:"GET",
			 data:{products_id:products_id , language:language},
			 complete:function(e , xhr ,setting){
				if(e.status == 200){
					 $("#preloader").hide();
					 $("#user_data_body").html(e.responseText);
				  }
				
			 },
			 error: function(xhr, error){
			   $("#preloader").hide();
			 }
			});
		}
		else{
		    $("#preloader").hide();
		  alert("please enter the products id !!!")
		}
	});
/*End*/	
/*Add Products On Select Group and sub Group*/
/*	$(document).on('change','#group_item',function(e){
	  $("#preloader").show();
	  group_id = $("#group_item").val();
	  var language = $('html').attr('lang');
	  if(group_id != ""){
		 $.ajax({
		 url:base_url+"/products_category/get_and_save_products",
		 method:"GET",
		 data:{group_id:group_id , language:language},
		 success:function(data){
			  $("#preloader").hide();
		 },
		 error: function(xhr, error){
		   $("#preloader").hide();
		 }
     });
		}
	}); */
	/*End*/    
    
 /*Open Sub Category popup btn script strat---sarita */
$(document).on('click' ,'.sub_category_btn' ,function(e){
	e.preventDefault();
	var category_id = $(this).data('categoryid');
	if(category_id != ""){
	    $("#group_id").val(category_id);
			$.ajax({
			url: base_url+"/product/get_sub_category",
			method: "GET",
			data: {categoryId:category_id},
			success: function(data){
				// console.log(data);
				$('#sub_category').html(data);
				$('#view_sub_category').modal('show');
			}
	  });
	}	
});
/*End* */       


/*Change Products Status*/
	$(document).on('click','.change_products_assemble_status',function(e){
		e.preventDefault();
		$this = $(this);
	    var products_id = $(this).data('productsid');
		var products_status = $(this).data('status');
		var type = $(this).data('type');
		if(products_id != ""){
		   $.ajax({
			 url:base_url+"/product/change_products_assemble_status",
			 method:"GET",
			 data:{products_id:products_id , products_status:products_status},
			 success:function(data){
				  if(products_status == "P"){
				     $this.html("<i class='fa fa-toggle-off'></i>").data('status' , 'A');
					 
				  }
				else if(products_status == "A"){
				    $this.html("<i class='fa fa-toggle-on'></i>").data('status' , 'P');;
				  }  
			 }
		 });
		  }
	});
	/*End*/
    /*Change Products Status*/
	$(document).on('click','.change_products_status',function(e){
		e.preventDefault();
		$this = $(this);
		console.log($this);
	    var products_id = $(this).data('productsid');
		var products_status = $(this).data('status');
		var type = $(this).data('type');
		if(products_id != ""){
		   $.ajax({
			 url:base_url+"/product/change_products_status",
			 method:"GET",
			 data:{products_id:products_id , products_status:products_status},
			 success:function(data){
			    if(type == 2){
				 if(products_status == "P"){
				     $this.removeClass('btn btn-success').addClass('btn btn-warning').html("Save in draft &nbsp <i class='fa fa-toggle-off'></i>").data('status' , 'A');
					 
				  }
				else if(products_status == "A"){
				    $this.removeClass('btn btn-warning').addClass('btn btn-success').html("Publish &nbsp <i class='fa fa-toggle-on'></i>").data('status' , 'P');;
				  }  
				 }
				if(type == 1){
				  if(products_status == "P"){
				     $this.html("<i class='fa fa-toggle-off'></i>").data('status' , 'A');
					 
				  }
				else if(products_status == "A"){
				    $this.html("<i class='fa fa-toggle-on'></i>").data('status' , 'P');;
				  }  
				 } 
				
			 }
		 });
		  }
	});
	
	/*End*/
	$(document).on('click','.get_products_details',function(e){
	    e.preventDefault();
	  var products_id = $(this).data('productsid');
		if(products_id != ""){
		   $.ajax({
			 url:base_url+"/product/get_products_details",
			 method:"GET",
			 data:{products_id:products_id},
			 success:function(data){
				$("#products_response").html(data);
				$("#products_details_modal").modal({
				  backdrop:'static',
				  keyboard:false
				});
				/*$('#search_parts_group').html('Search &nbsp;<span class="glyphicon glyphicon-search"></span>').attr('disabled' , true);
			   $("#user_data_body").html(data);*/
			 }
		 });
		  }
	});  
	
	$(document).on('click', '.get_all_n3_category', function(e){
        e.preventDefault();
        var products_id = $(this).data('productsid');
        if(products_id != ""){
		   	$.ajax({
			 	url:base_url+"/product/get_product_n3_category",
			 	method:"GET",
			 	data:{products_id:products_id},
			 	success:function(data){
					$("#n3_products_response").html(data);
					$("#get_all_n3_modal_popup").modal('show');
			 	}
		 	});
	  	}
    });
/* Add Inventory Products */
	$(document).on('submit','#products_invent_form',function(e){
		e.preventDefault();
		var checkBox = document.getElementById("tax");
		var text = document.getElementById("tax_hide_show");
		var assemble = document.getElementById("assemble_product");
		if (checkBox.checked == true){
			var tax = 1;
			var tax_value = $('#tax_amount').val();
			if(tax_value == ''){
				alert('Please enter Tax Amount');
				return false;
			}
		} else {
			var tax = 0;
			var tax_value = '';
		}
		if(assemble.checked == true){
			var product_ass = 1;
		} else {
			var product_ass = 0;
		}
		var form_data = new FormData(this);
		form_data.append('tax', tax);
		form_data.append('tax_value', tax_value);
		form_data.append('product_ass', product_ass);
		$.ajax({
				url: base_url+"/seller_ajax/add_invent_products",
				type: "POST",        
				data: form_data,
				contentType: false,
				cache: false,
				processData:false,  
				success: function(data){
					var parseJson = jQuery.parseJSON(data); 
					if(parseJson.status == 200){	
						$("#products_invent_form")[0].reset();
						$("#msg_response_popup").modal('show');
						$("#msg_response").html(parseJson.msg);
					} else {
						$("#response_msg").html(parseJson.msg);
					}	 
				}
		});
	});
	/* End */    	
/*Search Products by group*/
function image_name(image_source){
  if(image_source != ""){
	  image = "<img src='"+image_source+"' height='50' widht='50' />"
	  //image = $('<image>').attr('src',image_source).css(['height':50 , 'width':50]); 
	}
  else{
	source =   "http://officine.augurstech.com/officineTop/public/storage/products_image/no_image.jpg"; 
	   image = "<img src='"+source+"' height='50' widht='50' />"
	 //image = $('<image>',{src:source});
	}	
 return image;
}

function replace_comman_with_dot(price_source){
  price_real = price_source.replace(',', '.');
  return price_real;
}


/*Search products by item number*/
$(document).on('click','.item_number_btn',function(e){
   e.preventDefault();
	//$('#search_products_by_group').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
   var href = $(this).attr('href');
  $("#final_products_response").load(href, function(responseTxt, statusTxt, xhr){
    if(statusTxt == "success")
      
    if(statusTxt == "error")
      alert("Error: " + xhr.status + ": " + xhr.statusText);
  });
 });
/*End*/

$(document).on('click','#search_products_by_group',function(e){
   e.preventDefault();
	$('#search_products_by_group').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
   var item_id = $("#item_id").val(); 
  if(item_id != "" &&  item_id != 0){
	e.preventDefault();
    $.ajax({
		 url:base_url+"/search_products_05_08",
		 method:"GET",
		 data:{item_id:item_id},
		 complete:function(e , xhr){
			 console.log(e);
			 $('#search_products_by_group').html('Search &nbsp;<span class="glyphicon glyphicon-search"></span>').attr('disabled' , true);
			 if(e.status == 200){
			   $("#user_data_body").html(e.responseText);
			  }
		 }
     });
	}
	else{
      alert("Please select all required fields !!!");
	}
 });
/*$(document).on('click','#search_products_by_group',function(e){
	$('#search_products_by_group').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
   var item_id = $("#item_id").val(); 
  if(item_id != "" &&  item_id != 0){
	e.preventDefault();
    $.ajax({
		 url:base_url+"/products_ajax/search_productsBy_group",
		 method:"GET",
		 data:{item_id:item_id},
		 complete:function(e , xhr){
			 console.log(e);
			 $('#search_products_by_group').html('Search &nbsp;<span class="glyphicon glyphicon-search"></span>').attr('disabled' , true);
			 if(e.status == 200){
			   $("#user_data_body").html(e.responseText);
			  }
		 }
     });
	}
	else{
      alert("Please select all required fields !!!");
	}
 });*/
 /*$(document).on('click','#search_products_by_group',function(e){
	$('#search_products_by_group').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
   var makers_id = $("#car_makers").val();
   var models = $("#car_models").val();
   var car_version_id = $(".car_version_group").val(); 
   var groupid = $("#group_item").val();  
   e.preventDefault();
    $.ajax({
		 url:base_url+"/products_ajax/search_productsBy_group",
		 method:"GET",
		 data:{makers_id:makers_id , models:models , car_version_id:car_version_id , groupid:groupid},
		 success:function(data){
			$('#search_products_by_group').html('Search &nbsp;<span class="glyphicon glyphicon-search"></span>').attr('disabled' , true);
		   $("#user_data_body").html(data);
		 }
     });
 });*/
 /*End*/		
/*Search group item*/
 $(document).on('click','#search_parts_group',function(e){
	$('#search_parts_group').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
   var makers_id = $("#car_makers").val();
   var models = $("#car_models").val();
   var car_version_id = $("#version_id").val(); 
   var language = $('html').attr('lang');  
   e.preventDefault();
   if(makers_id != 0 && models != 0 && car_version_id != 0){
    $.ajax({
		 url:base_url+"/products_ajax/search_products_group",
		 method:"GET",
		 data:{makers_id:makers_id , models:models , car_version_id:car_version_id,language:language},
		 success:function(data){
		   $('#search_parts_group').html('Search &nbsp;<span class="glyphicon glyphicon-search"></span>').attr('disabled' , true);
		   $("#user_data_body").html(data);
		 },
		 error : function(xhr , error){
			$('#search_parts_group').html('Search &nbsp;<span class="glyphicon glyphicon-search"></span>').attr('disabled' , true);
			alert('Something went wrong please try again .');
		 }
     });
   }
   else{
	  alert('Something went wrong please try again .');
	 }     
 });
 /*End*/		
/*Remove Products image*/
   $(document).on('click','.remove_products_image' , function(e){
		e.preventDefault();
		var image_id = $(this).data('imageid');
        var con = confirm("Are you sure want to delete?");
        if(con == true) {
		   $.ajax({
			 url:base_url+"/products_ajax/remove_products_image",
			 method:"GET",
			 data:{image_id:image_id},
			 success:function(data){
			   $('#image_grid_section').load(document.URL + ' #image_grid_section'); 
			 }
     }); 
        } 
	});
 /*End*/	
 
/*Add custom group name start*/
  	/*group_images_form script start*/
	$(document).on('submit','#add_new_group',function(e){
		$('#msg_response').html(" ");
		$('#new_group_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
		e.preventDefault();
			 $.ajax({
					url: base_url+"/products_ajax/add_group_name",
					type: "POST",        
					data: new FormData(this),
					contentType: false,
					cache: false,
					processData:false,  
					success: function(data){
					$('#new_group_btn').html('Save &nbsp;<i class="icon-paperplane ml-2"></i>').attr('disabled' , false);
					var errorString = '';
					var parseJson = jQuery.parseJSON(data);
						if(parseJson.status == 400){
							$.each(parseJson.error, function(key , value) {
								errorString += '<div class="notice notice-danger"><strong>Note , </strong>'+ value+' .</div>';
							});
							$('#response_coupon').html(errorString); 	
						}
						if(parseJson.status == 200){
							$(".close").click();
							$("#msg_response_popup").modal('show');
							$("#msg_response").html(parseJson.msg);
							$("#add_new_group")[0].reset();
						}
						if(parseJson.status == 100){
							$("#msg_response_popup").modal('show');
							$("#msg_response").html(parseJson.msg);
						}
					},
					error: function(xhr, error){
					$('#new_group_btn').html('Save &nbsp;<i class="icon-paperplane ml-2"></i>').attr('disabled' , false);
					 $("#msg_response_popup").modal('show');
					 $('#msg_response').html('<div class="notice notice-danger"><strong>Wrong , </strong>Something Went Wrong please try again  .</div>');
                    }	
					
			});
	 });
	 /*End*/
  /*End*/    
 /* Add New Group add_group_name_btn */
	$(document).on('click' , '.add_group_name_btn' ,function(e) {
	    	e.preventDefault();
		var markers_id = $('#car_makers').val();
		var models_id = $('#car_models').val();
		var version_id = $('#car_version').val();
		if(markers_id != 0){
		   if(models_id != 0){
			  if(version_id != 0){
				  $("#marker_id").val(markers_id);
			      $("#models_id").val(models_id);
			      $("#version_id").val(version_id);
			      $("#add_group_name_popup").modal('show');
				} 
			  else{
				 alert("Please Select  version name !!!");
				}	
			 }
		   else{
			 alert("Please Select model name !!!");
			 }	 
		}
		else{
		alert("Please Select makers name !!!");
		}
	});
	/*End */	    
  /*Remove Group script start*/
   $(document).on('click','.delete_group' , function(e){
		e.preventDefault();
		var url = $(this).attr('href');
        var con = confirm("Are you sure want to delete?");
        if(con == true) {
		    window.location.href = url;
        } 
	});
  /*End*/	    
    
/* Remove Group Image*/
$(document).on('click' , '.remove_group_image' ,function(e){
	e.preventDefault();
	var image_id = $(this).data('imageid');
    var group_id = $(this).data('groupid');
	var con = confirm("Are you sure want to delete ?");
    if(con == true) {
		$.ajax({
		   url: base_url+"/products_ajax/remove_group_image",
		   type: 'GET',
		   data: {imageId:image_id},
			success: function(response){
				show_group_image(group_id);
			}
		});
    } 
});
/* End */
/*Open Group Image upload popup btn script strat */
function show_group_image(group_id){
    if(group_id != ""){
	    $("#group_id").val(group_id);
			$.ajax({
			url: base_url+"/products_ajax/get_group_image",
			method: "GET",
			data: {groupId:group_id},
			success: function(data){
			  $('#image_result').html(data);
			  $('#add_group_image_popup').modal('show');
			  $("#add_group_image_popup").modal('show');
			}
	  });
	}	
}
$(document).on('click' ,'.add_group_image_btn' ,function(e){
	e.preventDefault();
	var group_id = $(this).data('groupid');
	show_group_image(group_id);
});
/*End* */
    	
    
    /* Remove Group Image*/
    $(document).on('click' , '.remove_image' ,function(){
        var image_id = $(this).data('imageid');
        var el = this;
        var result = confirm("Are you sure want to delete?");
        if(result == true) {
            if(result){
                $.ajax({
                   url: base_url+"/products_ajax/remove_group_image",
                   type: 'GET',
                   data: {imageId:image_id},
                    success: function(response){
                        $(el).closest('.thumbnail').css('background','tomato');
                        $(el).closest('.thumbnail').fadeOut(800, function(){ 
                            $(this).remove();
                        });
                    }
                });
            }
        } else {
            return false;
        }
    });
    /* End */
/*group_images_form script start*/
	$(document).on('submit','#group_images_form',function(e){
		$('#msg_response').html(" ");
		$("#err_response").html(" ");
		var group_id = $("#group_id").val();
		$('#save_group_image').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
		$('#err_response').html(""); 
		e.preventDefault();
			 $.ajax({
					 url: base_url+"/products_ajax/add_group_images",
					 type: "POST",        
					 data: new FormData(this),
					 contentType: false,
					 cache: false,
					 processData:false,  
					 success: function(data){
					  var errorString = '';
					  var parseJson = jQuery.parseJSON(data);
					  	//show_group_image(group_id);
					  $('#save_group_image').html(' Save &nbsp; <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
						 if(parseJson.status == 400){
							  $.each(parseJson.error, function(key , value) {
								errorString += '<div class="notice notice-success"><strong>Success , </strong>'+ value+' .</div>';
							  });
							  $('#err_response').html(errorString); 	
						 }
						 if(parseJson.status == 200){
							  $(".close").click();
							  $("#msg_response_popup").modal('show');
							  $("#msg_response").html(parseJson.msg);
							  $("#group_images_form")[0].reset();
						 }
						 if(parseJson.status == 100){
							$("#err_response").html(parseJson.msg);
						 }
						}	
			});
	 });
	 /*End*/

/*multiple_images_form script start*/
	$(document).on('submit','#multiple_images_form',function(e){
	 $('#response_about_pakages').html(" ");
	 e.preventDefault();
		  $.ajax({
				  url: base_url+"/products_ajax/add_images",
				  type: "POST",        
				  data: new FormData(this),
				  contentType: false,
				  cache: false,
				  processData:false,  
			 	 success: function(data){
					var errorString = '';
					var parseJson = jQuery.parseJSON(data);
					$('#multiple_img').html(' Save &nbsp;<i class="icon-paperplane ml-2"></i> ').attr('disabled' , false);
                    if(parseJson.status == 400){
                        $.each(parseJson.error, function(key , value) {
                            errorString += '<div class="notice notice-success"><strong>Success , </strong>'+ value+' .</div>';
                        });
                        $('#response_coupon').html(errorString); 	
                    }
                    if(parseJson.status == 200){
                        $("#msg_response_popup").modal('show');
                        $("#msg_response").html(parseJson.msg);
                        $(".close").click();
                        $("#multiple_images_form")[0].reset();
                    }
                    if(parseJson.status == 100){
                        $("#msg_response_popup").modal('show');
                        $("#msg_response").html(parseJson.msg);
                    }
                }	
		 });
  });
  /*End*/


	/*Edit Products Form Script start */
	$(document).on('submit','#edit_products_by_admin',function(e){
		//$('#msg_response').html(" ");
		 //$('#add_products_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
			 e.preventDefault();
			 $.ajax({
					 url: base_url+"/products_ajax/edit_products_by_admin",
					 type: "POST",        
					 data: new FormData(this),
					 contentType: false,
					 cache: false,
					 processData:false,  
					 success: function(data){
					     var parseJson = jQuery.parseJSON(data); 
					     $('#add_products_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
					   var errorString = '';
                       if(parseJson.status == 400){
				          $.each(parseJson.error, function(key , value) {
					         errorString += '<div class="notice notice-danger"><strong>Success , </strong>'+ value+' .</div>';
				          });
				          $("#msg_response_popup").modal('show');
						  $('#msg_response').html(errorString);
                         }
					   if(parseJson.status == 200){
						  $("#msg_response_popup").modal('show');
						  $('#msg_response').html(parseJson.msg);
				          //$('#edit_products_by_admin')[0].reset();
			             }
					  if(parseJson.status == 100){
						 $("#msg_response_popup").modal('show');
						 $('#msg_response').html(parseJson.msg);
					   }
					},
					error: function(xhr, error){
					 $('#add_products_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
					 $("#msg_response_popup").modal('show');
					 $('#msg_response').html('<div class="notice notice-danger"><strong>Wrong , </strong>Something Went Wrong please try again  .</div>');
                    }
			});
	 });
	/*End */

  /*Add dproducts group script start*/
    $(document).on('submit','#add_group_form',function(e){
	  	 $("#preloader").show();
	  	$('#msg_response_popup').html(" ");
		$('#add_coupon_group_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
		var data_arr = [];
		$("#group_table > tbody > tr").each(function(index, element) {
            if( $(this).find(".group_name").is(':checked')){
			      var item_description = $(this).find('.group_name').data('value');
			      data_arr.push({group:item_description}); 
				} 
        });
		    var car_makers = $("#car_makers").val();
			 var car_models = $("#car_models").val();
			 var car_version = $(".car_version_group").val();
			  var language = $('html').attr('lang');
			 console.log(data_arr);
			 e.preventDefault();
			 $.ajax({
					 url: base_url+"/products_ajax/save_group",
					 type: "POST",        
					 data: {groups:data_arr , car_makers:car_makers , car_models:car_models , car_version:car_version , group_item:data_arr ,language:language },
					 success: function(data){
					      $("#preloader").hide();
						get_groups();
						$('#add_coupon_group_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
						$("#add_products_group_pop_msg").modal('show');
				        $("#msg_response").html('<div class="notice notice-success"><strong>Success </strong> Group add successfully   !!! </div>');
					},
					error: function(xhr, error){
					      $("#preloader").hide();
					 $("#add_products_group_pop_msg").modal('show');
                     $('#add_coupon_group_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
				     $("#msg_response").html('<div class="notice notice-danger"><strong>Wrong  </strong>  Something went wrong please try again  !!! </div>');
                   }

			});
	 });
  /*End*/	
  /*Save  Products by ADMIN*/
    $(document).on('submit','#add_products_by_admin',function(e){
		$("#preloader").show();
		$('#msg_response').html(" ");
		$('#products_save_button').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
		var data_arr = [];
		$("#products_table > tbody > tr").each(function(index, element) {
            if( $(this).find(".item_id").is(':checked')){
			   var item_description = $(this).find('.item_id').data('value');
			   data_arr.push({item_description:item_description}); 
			  } 
        });
		     var car_makers = $("#car_makers").val();
			 var car_models = $("#car_models").val();
			 var car_version = $(".car_version_group").val();
			 var group_item = $("#group_item").val();
			 var language = $('html').attr('lang'); 
			e.preventDefault();
			 $.ajax({
					  //url: base_url+"/products_ajax/save_products",
					 url: base_url+"/products_category/save_groups_items",
					 type: "POST",        
					 data: {item_details:data_arr , car_makers:car_makers , car_models:car_models , car_version:car_version , group_id:group_item , language:language},
					 success: function(data){
					    var parseJson = jQuery.parseJSON(data);	 
					    console.log(parseJson.status);
					   $('#products_save_button').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
					   $("#preloader").hide();
					   if(parseJson.status == 100){
					     $('#products_save_button').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
						 $("#msg_response_popup").modal('show');
				         $("#msg_response").html('<div class="notice notice-danger"><strong> Wrong </strong> Something Went Wrong !!! </div>');
					   }  
					
						if(parseJson.status == 200){
						  get_products();
						  $("#msg_response_popup").modal('show');
				          $("#msg_response").html('<div class="notice notice-success"><strong>Success </strong> Products Add Successful !!! </div>');
						 } 
					 },
					error: function(xhr, error){
					     $("#preloader").hide();
						 $('#products_save_button').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
						 $("#msg_response_popup").modal('show');
				       $("#msg_response").html('<div class="notice notice-danger"><strong> Wrong </strong> Something Went Wrong !!! </div>');
						
					}	
			});
	 });
  /*End*/	
  /*Get Cars model name on makers name change script start*/
  $(document).on('change','#car_makers',function(){
    $("#preloader").show();
     var makers_id = $("#car_makers").val();
     if( makers_id != ""){
	    $.ajax({
          url:base_url+"/model",
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
			$("#car_models").html(html_content); 
		     $("#version_id").empty();
		     $("#version_id").append($('<option>',{value:0}).text('--Select--Car--Model--first--'));
             }
          },
		error: function(xhr, error){
		  $("#preloader").hide();
		}
      });
	 }	   
  });
  /*End*/
  /*Get Cars model name on makers name change script start*/
    $(document).on('change','#car_models',function(){
    var model_value = $("#car_models").val();
    $("#preloader").show();
    if( model_value != ""){
     $.ajax({
         url:base_url+"/version/",
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
                $("#version_id").empty();
		        $("#version_id").append($('<option>',{value:0}).text('--Select--Car--Model--first--'));    
               $("#car_version").html(html_content); 
			   $(".car_version_group").html(html_content);
			} 
         },
			error: function(xhr, error){
		     $("#preloader").hide();
		    }
     });
    }	   
 });
 /*End*/
  /*Print Group script start*/ 
  /*Select All Group*/
    $(document).on('click','#select_all_group',function(){
	  if( $("#select_all_group").is(':checked') ){
		  $(".group_name").prop('checked' , true);
		}
	  else{
		  $(".group_name").prop('checked' ,false); 
		}	
	});
  /*End*/
    /*Print Group section in add_products start*/
	  function print_group_name(data){
	    var parseJson = jQuery.parseJSON(data);
		        table = $('<table>',{class:'table table-striped',id:'group_table'})
				   table_head = $('<thead>').append(
				   	$('<tr>').append(
					$('<th>').append(
						$('<input>' ,{type:'checkbox',id:'select_all_group',class:'group_name'}), $('<lable>').text(' Select All')
						),
					$('<th>').text('Category name')
					)
				   )
				   table_body = $('<tbody>');
               
                 if(parseJson.response.length != 0){
					$.each(parseJson.response , function(index , value){
						input = $('<input>',{type:'checkbox',name:'group_name',class:'group_name'}).data('value',value)
						tr = $('<tr>').append(
							$('<td>').append(input),
							$('<td>').text(value.Gruppo)
						)
						table_body.append(tr) 
					});
				   }
				  else{
				      tr = $('<tr>').append(
							$('<td colspan="2">').append("No Category Available !!!")
							)
					 table_body.append(tr)	
				   }  
				
				   table.append(
				   	table_head,
					table_body
				   )
				 $("#group_item_table").html(table);
	  }
	/*End*/
   /*Get Group start*/
    function get_groups(){
        $("#preloader").show();
	    var car_version_id = $(".car_version_group").val();
		var makers_id = $("#car_makers").val();
        var models = $("#car_models").val();
      	var language = $('html').attr('lang');
		if( car_version_id != ""){
		$.ajax({
			url:base_url+"/products_ajax/get_part_category",
			method:"GET",
			data:{makers_id:makers_id ,models:models, car_version_id:car_version_id ,language:language},
			success:function(data){
			    $("#preloader").hide();
				print_group_name(data); 
			},
        error: function(xhr, error){
		 $("#preloader").hide();
		}
		});
		}		
     }  
   /*End*/

/*Get save groups and sub group for mapping */
  function group_sub_group_save_mapping(makers_id ,models ,car_version_id , language){
     $("#preloader").show(); 
	 $.ajax({
			url:base_url+"/products_categories/get_and_save_group",
			method:"GET",
			data:{makers_id:makers_id ,models:models, car_version_id:car_version_id ,language:language},
			complete:function(e, xhr, settings){
			    if(e.status == 200){
					$("#preloader").hide(); 
	 				//$("#group_item").html('<option value="0">--First--Select--Car--Version--</option>')
				    console.log(e);
				}
			 },
			error: function(xhr, error){
		     $("#preloader").hide();
		    }
		});
  }
/*End*/

/*Get Group and sub group save*/ 
   function group_sub_group_save_new(makers_id ,models ,car_version_id , language){
   	$("#preloader").show(); 
   	$(".groups").empty();
	$.ajax({
			url:base_url+"/save_groups_subgroups_05_08",
			method:"GET",
			dataType:"json",
			data:{makers:makers_id ,model:models ,  version:car_version_id ,language:language},
		    complete: function(e , xhr){
			   $.ajax({
                    url:base_url+"/get_groups_05_08",
                    method:"GET",
                    data:{car_version_id:car_version_id , language:language},
                    complete:function(e , xhr , settings){
						var parseJson = jQuery.parseJSON(e.responseText);
						if(parseJson.status == 200){
							$("#group_item").html(parseJson.response);
							$("#preloader").hide(); 
						  }
						if(parseJson.status == 100){
						   $("#group_item").html("<option>--No--Group--Available--</option>");
							$("#preloader").hide();
						   }  
						},
						error: function(xhr, error){
						$("#preloader").hide();
						}
                     }); 
			},
			error: function(xhr, error){
		     $("#preloader").hide();
		    }
		});
   }
/*End*/
/*Get N2 script start*/
 $(document).on('change','.groups_n1',function(e){
    group_id = $(this).val();
	var language = $('html').attr('lang');
	$("#preloader").show(); 
	 $.ajax({
			url:base_url+"/save_sub_groups_04_09",
			method:"GET",
			data:{group_id:group_id , language:language},
		    complete: function(e , xhr){
			  if(e.status == 200){
			  	console.log(e)
				 var parseJson = jQuery.parseJSON(e.status);
				  if(parseJson == 200){
					 $.ajax({
                          url:base_url+"/get_sub_groups_04_09",
                          method:"GET",
                          data:{group_id:group_id , language:language},
                          complete:function(e , xhr , settings){
						    var parseJson = jQuery.parseJSON(e.responseText);
							if(parseJson.status == 200){
								$("#group_n2").html(parseJson.response);
								$("#preloader").hide(); 
							  }
							if(parseJson.status == 100){
							   $("#group_n2").html("<option>--No--Group--Available--</option>");
								$("#preloader").hide();
							 } 
						},
						error: function(xhr, error){
						$("#preloader").hide();
						}
                      });
					} 
				  if(parseJson == 100){
					  $("#msg_response").html(parseJson.msg);
				      $("#msg_response_popup").modal('show');
					}	
				}
			},
			error: function(xhr, error){
		     $("#preloader").hide();
		    }
		});
 }); 
/*End*/	
/*Get and save n1*/
 function  get_and_saven1(makers , model , version , language){
	 $("#preloader").show(); 
     $.ajax({
			url:base_url+"/save_groups_04_09",
			method:"GET",
			data:{makers:makers ,model:model,version:version ,language:language},
		    complete: function(e , xhr){
			  if(e.status == 200){
				 var parseJson = jQuery.parseJSON(e.responseText);
				  if(parseJson.status == 200){
					 $.ajax({
                          url:base_url+"/get_groups_04_09",
                          method:"GET",
                          data:{version:version , language:language},
                          complete:function(e , xhr , settings){
						    var parseJson = jQuery.parseJSON(e.responseText);
							if(parseJson.status == 200){
								$("#group_n1").html(parseJson.response);
								$("#preloader").hide(); 
							  }
							if(parseJson.status == 100){
							   $("#group_n1").html("<option>--No--Group--Available--</option>");
								$("#preloader").hide();
							 } 
						},
						error: function(xhr, error){
						$("#preloader").hide();
						}
                      });
					} 
				  if(parseJson.status == 100){
					  $("#msg_response").html(parseJson.msg);
				      $("#msg_response_popup").modal('show');
					}	
				}
			},
			error: function(xhr, error){
		     $("#preloader").hide();
		    }
		});
  }
/*End*/
 $(document).on('change','.car_version_group',function(){
     var action = $(this).data('action');
	 var car_version_id = $(".car_version_group").val();
	 var makers_id = $("#car_makers").val();
     var models = $("#car_models").val();
     var language = $('html').attr('lang');
     if(action == "get_and_save_n1"){
	      get_and_saven1(makers_id , models , car_version_id , language);
	   }
	 if(action == "get_and_save_for_mapping"){
	     group_sub_group_save_mapping(makers_id ,models ,car_version_id , language);
	   }
	 if(action == "get_and_save"){
		group_sub_group_save_new(makers_id ,models ,car_version_id , language);  
	  }
	 if(action == "get_and_save_1"){
	     group_sub_group_save_1(makers_id ,models ,car_version_id , language);
	  } 
	 else if(action == "get_group"){
	    get_groups();	   
	  } 
	else if(action == "get_and_save_services_time"){
	    get_and_save_services_time();
	  }   
	  else if(action == "get_and_save_all_category"){
			group_sub_group_save_all(makers_id ,models ,car_version_id , language); 
		}
 });
 
 /*End*/ 
 /*Get Active Group script start*/
  /*
  $(document).on('change','#car_version',function(){
    $("#preloader").show();     
    var car_version_id = $("#car_version").val();
	var language = $('html').attr('lang');
    if( car_version_id != ""){
     $.ajax({
         url:base_url+"/products_ajax/get_part_category_database",
         method:"GET",
         data:{car_version_id:car_version_id , language:language},
         success:function(data){
            $("#preloader").hide(); 
			var parseJson = jQuery.parseJSON(data);
			 if(parseJson.status == 200){
              var html_content = '';
			   html_content += '<option hidden="hidden">--Select--Group--Item--</option>'; 
              $.each(parseJson.response , function(index , value){
				   html_content += '<option value="'+value.id+'">'+ value.group_name +'</option>';      
                });
              $("#group_item").html(html_content); 
              $("#group_item_inventory").html(html_content); 
             } 
			if(parseJson.status == 100){
			    $("#group_item").html("<option>--No--Group--Available--</option>");
			    $("#group_item_inventory").html("<option>--No--Group--Available--</option>");
			 }
         } ,
        error: function(xhr, error){
		 $("#preloader").hide();
		}
     });
    }	   
 }); */
 /*End*/
 

 /*Get Active Group script start*/
 /*Get API kromeda Group */
 function get_and_save_category_group(){
    var makers_id = $("#car_makers").val();
    var models = $("#car_models").val();
	var car_version_id = $("#car_version").val();
	var language = $('html').attr('lang');
	if(makers_id != "" || models != "" || car_version_id != ""){
	   group_sub_group_save(makers_id ,models ,car_version_id , language);  
	 }
 }
 /*End*/
  $(document).on('change','#car_version',function(){
    $("#preloader").show();
	var makers_id = $("#car_makers").val();
    var models = $("#car_models").val();
	var car_version_id = $("#car_version").val();
	var language = $('html').attr('lang');
	 get_and_save_category_group();
    if( car_version_id != ""){
     $.ajax({
         url:base_url+"/products_ajax/get_part_category_database",
         method:"GET",
         data:{makers_id:makers_id , models:models , car_version_id:car_version_id , language:language},
         success:function(data){
            $("#preloader").hide(); 
			var parseJson = jQuery.parseJSON(data);
			 if(parseJson.status == 200){
              var html_content = '';
			   let texts = {} ;
                $.each(parseJson.response , function(index , value){
				  if(value.parent_id == 0){
					 texts[value.id] = {id:value.id,text:value.group_name,childrens:[]}
					}
				});
				
				$.each(parseJson.response , function(index , value){
				  if(value.parent_id != 0){
						texts[value.parent_id]['childrens'].push({id:value.id,text:value.group_name});
					}
                });
				
				$("#group_item").empty();
				$.each(texts, function(i, value) {
					$("#group_item").append($('<option>' , {value:value.id}).text(`${value.text}`))
					if(value.childrens.length) {
						$.each(value.childrens, function(i, child) {
							$("#group_item").append($('<option>' , {value:child.id}).text(`${value.text} >> ${child.text}`))
						})
					}
				})
				
             } 
			$("#group_item").prepend("<option value='0' selected>Select category</option>");
			if(parseJson.status == 100){
			  $("#group_item").html("<option>--No--Group--Available--</option>");
			  $("#group_item_inventory").html("<option>--No--Group--Available--</option>");
			 }
         },
		error: function(xhr, error){
		 $("#preloader").hide();
		}
     });
    }	   
 });
 /*End*/
 
  /*Get Active sub Group script start*/
   /*Print products in */
     function print_products_in_table(data){
	    var parseJson = jQuery.parseJSON(data);
			 if(parseJson.status == 200){
               var html_content = '';
				   table = $('<table>',{class:'table table-striped',id:'products_table'})
				   table_head = $('<thead>').append(
				   	$('<tr>').append(
						$('<th>').text('Select Item'),
						$('<th>').text('Item'),
						$('<th>').text('Front Rear'),
						$('<th>').text('Left Right')
					)
				   )
				   table_body = $('<tbody>');
			  	if(parseJson.response['length'] != 0){
                   $.each(parseJson.response , function(index , value){
    					 if(value.ap == "")
    					    fr = 'N/A';
    					 else
    					    fr = value.ap;
    						
    					 if(value.ds == "") 
    					    lr = 'N/A';
    					 else
    					    lr = value.ds;
    					input = $('<input>',{type:'checkbox',name:'item_id',class:'item_id'}).data('value',value);
    					
    					input.on('change', function() {
							checkedCount = table.find('input[type=checkbox]:checked').length
							if(checkedCount > 5) {
								$(this).prop('checked',false);
								alert("At least five products add in one time .")
								
							}
						});
						
    					tr = $('<tr>').append(
    						$('<td>').append(input),
    						$('<td>').text(value.Voce),
    						$('<td>').text(fr),
    						$('<td>').text(lr)
    					)
    					table_body.append(tr) 
                    });
			  	  }
			   else{
                   tr = $('<tr>').append(
						$('<td colspan="2">').append("No Products Available")
					)
					table_body.append(tr) 
			 	 }	
				   table.append(
				   	table_head,
					table_body
				   )
				 $("#item_table").html(table);  
			 }
	  }
   /*End*/
    /*get Products script start*/
  function get_products(){
	$("#preloader").show();
	var group_id = $("#group_item").val();
	var language = $('html').attr('lang');
	var version_id = $(".car_version_group").val();
    if( group_id != ""){
     $.ajax({
         url:base_url+"/products_ajax/get_group_item",
         method:"GET",
         data:{group_id:group_id , language:language , version_id:version_id},
         success:function(data){
			 print_products_in_table(data);
			 $("#preloader").hide();
         },
		 error: function(xhr, error){
		   $("#preloader").hide();
		 }
     });
	}  
  }
  
  
   /*Find Products And save*/
   	/*Add Products On Select Group and sub Group*/
	function get_products_and_save(){
	 // $("#preloader").show();
	  group_id = $("#group_item").val();
	  var language = $('html').attr('lang');
	  if(group_id != ""){
		 $.ajax({
		 url:base_url+"/products_category/get_and_save_products",
		 method:"GET",
		 data:{group_id:group_id , language:language},
		 success:function(data){
		   $("#preloader").hide();
		 },
		 error: function(xhr, error){
		   $("#preloader").hide();
		 }
     });
		}
	}
	/*End*/
/*save and get groups item via n2 */
   $(document).on('change','.groups',function(){
	   var sub_groups_id = $(this).val();
       get_and_save_products_item(sub_groups_id);
   });
/*End*/
$()
   /*End*/
   $(document).on('change','#group_item',function(){
	  action = $(this).data('action');
	  if(action == "get_and_save_products"){
		  get_products_and_save();
		}
	  else if(action == "get_products_list"){
		   get_products();
		}
	  else if(action == "get_and_save_products_item"){
		//var type = $('option:selected', this).attr('data-type');
		  get_and_save_products_item();
		}
	   else if(action == "get_groups_item"){
		  get_groups_item();
		} 		
    });
 /*End*/
 
 
 function group_sub_group_save_all(makers_id ,models ,car_version_id , language){
		$("#preloader").show(); 
		$(".groups").empty();
		$.ajax({
			url:base_url+"/save_groups_subgroups_05_08",
			method:"GET",
			dataType:"json",
			data:{makers:makers_id ,model:models ,  version:car_version_id ,language:language},
			complete: function(e , xhr){
				$.ajax({
					url:base_url+"/get_all_groups_05_08",
					method:"GET",
					data:{car_version_id:car_version_id , language:language},
					complete:function(e , xhr , settings){
						var parseJson = jQuery.parseJSON(e.responseText);
						if(parseJson.status == 200){
							$("#group_item").html(parseJson.response);
							$("#preloader").hide(); 
						}
						if(parseJson.status == 100){
							$("#group_item").html("<option>--No--Group--Available--</option>");
							$("#preloader").hide();
							}  
						},
						error: function(xhr, error){
						$("#preloader").hide();
						}
					}); 
			},
			error: function(xhr, error){
			$("#preloader").hide();
			}
		});
	}
 
});