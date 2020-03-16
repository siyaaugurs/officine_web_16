function append_services(more_coupon_section , for_subcategory){
$.ajax({
			url: base_url+"/coupon_ajax/get_all_services",
			method: "GET",
			success: function(data){
			  var parseJson = jQuery.parseJSON(data);
			  if(parseJson.status == 200){
				more_coupon_section.append( $('<div>' , {id:'services_div' , class:'col-sm-12'}).append( $('<label>').text('Select Service') , $('<select>' ,{name:'services' ,id:'services' , class:'form-control'}).attr('data-category' , for_subcategory) ) );
				 select_services = $('#services'); 
				 select_services.append( $('<option>' ,{value:0}).text('--Select--Service--') )
				 $.each(parseJson.response , function(index , value){
					select_services.append( $('<option>' ,{value:value.id}).text(value.main_cat_name) )   
				  });
				}	
			}
	    });
}

function append_product_dropdown(more_coupon_section , type){
    more_coupon_section.append( $('<div>',{id:'product_type_div' , class:'col-sm-12'}).append( $('<label>').text('Select Product Type')  , $('<select>' ,{class:'form-control' , id:'product_type' , name:'product_type'}).data('type' , type).append( $('<option>' , {value:0}).text('Select Product Type') , $('<option>' , {value:1}).text('Spare Parts') ,  $('<option>' , {value:2}).text('Tyre') , $('<option>' , {value:3}).text('Rim') )));
}

$(document).ready(function(e) {
/*For Coupon group*/
$(document).on('change','#coupon_group',function(){
		    coupon_type = $(this).val();
			number_of_user_in_group = $('#number_of_user_in_group');
			if(coupon_type == 2){
			    $('#number_of_users_group_div').show();
			  }
			else{
			    $('#number_of_users_group_div').hide();
			  }  
		});
/*End*/		
/*Delete coupon script start */
  $(document).on('click','.delete_coupon',function(e){
	 coupon_row = $(this);
	 con = confirm('Are you sure want to remove this coupon !!!'); 
	 if( con == true){
		coupon_id = coupon_row.data('couponid');
		$.ajax({
		url: base_url+"/coupon_ajax/remove_coupon",
		method: "GET",
		data: {coupon_id:coupon_id},
		success: function(data){
		  if(data == 200){
			  coupon_row.closest('tr').remove(); 
			}
		  else{
			 $("#msg_response_popup").modal('show');
			 $('#response').html('<div class="notice notice-danger"><strong>Wrong </strong> Something went wrong , please try again !!! </div>'); 	  
			}	
		}
	  }); 
	 }
  });
/*End*/	
 /*Save Coupons script start*/
   	$(document).on('submit','#coupon_form',function(e){
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
				  var parseJson = jQuery.parseJSON(data); 
				  $('#coupon_sbmt').html('Submit <i class="icon-paperplane ml-2"></i>').attr('disabled' , false);
					$("#msg_response_popup").modal('show');
					if(parseJson.status == 200){
						$("#msg_response").html(parseJson.msg);
						setTimeout(function(){ location.reload(); } , 1000);
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

 /*End*/	
  $(document).on('change','#select_dscount_on_products_service',function(e){
    discount_on_product_service = $(this);
	value = discount_on_product_service.val();
	more_coupon_section = $('#more_coupon_section'); 
	product_type = {1:'Spare Parts' , 2:'Tyre' , 3:'Rim'};
	if(value == 1){
	    more_coupon_section.empty();
	  }
	else if(value == 2){
	   more_coupon_section.empty();	
	   more_coupon_section.append( $('<div>' , {id:'on_total_siping_amount_div' , class:'col-sm-12'}).append( $('<label>').text('On Total Sipping Amount') , $('<input>' , {class:'form-control' ,id:'on_total_siping_amount' ,name:'shipping_amount' , placeholder:'On Total Sipping Amount' , name:'on_total__amount'})));    
	  }
	else if(value == 3){
	    more_coupon_section.empty();
	    append_product_dropdown(more_coupon_section , 1)
	  }  
	else if( value == 4){
	     more_coupon_section.empty();
		 $('.part_number_div').remove();
		 append_services(more_coupon_section , 1);
	  } 
	else if(value == 5){
		$('.part_number_div').remove();
	    more_coupon_section.empty(); 
		append_services(more_coupon_section , 2); 
	  } 
	else if(value == 6){
	    $('.part_number_div').remove();
	    more_coupon_section.empty(); 
	    append_product_dropdown(more_coupon_section , 2);
	  }  	    
  }); 
  
  /*Event on select service*/
   $(document).on('change','#services' ,function(e){
      var service = $(this);
	  $('#service_sub_category_div').empty();
	  var parent_div = service.closest('#services_div');
	  service_id = service.val();
	  if( service.data('category')  == 2){
		  $.ajax({
			url: base_url+"/coupon_ajax/get_service_category",
			method: "GET",
			data:{service_id:service_id},
			success:function(data){
			  var parseJson = jQuery.parseJSON(data);
			   parent_div.after( $('<div>' ,{id:'service_sub_category_div' , class:'col-sm-12'}).css('margin-top' , 15).append( $('<div>').append( $('<label>').text('Select Service Category') ,  $('<select>' , {class:'form-control' ,id:'service_sub_category' , name:'service_sub_category'}) ) ));
			   service_sub_category = $("#service_sub_category");  
			  
			   if(parseJson.status == 200){
				 service_sub_category.append( $('<option>' ,{value:0}).text('--Select--Service--Category--') );
				 $.each(parseJson.response , function(index , value){
					 service_sub_category.append( $('<option>' ,{value:value.id}).text(value.category_name) )   
				  });
				}
				if(parseJson.status == 300){
					service_sub_category.append(parseJson.response);
				}
			}
	    }); 
		}
   });
  /*End*/
  
  /*Event on product type script start*/
    $(document).on('change','#product_type',function(e){
	  var product_type = $(this);
	  product_type_value = product_type.val();
	  products_type = product_type.data('type');
	  if(products_type == 1){
		  parent_div = product_type.closest('div #more_coupon_section'); 
		  //alert(product_type_value);
		  if(product_type_value  != 0){
			   $('.part_number_div').remove();
			  if(product_type_value == 1){
				  //$('.part_number_div').remove();
				  parent_div.after( $('<div>' ,{id:'product_item_id', class:'row form-group part_number_div'}).append( $('<div>' , {class:'col-sm-12'}).append( $('<label>').text('Enter Product Item Number') ,  $('<input>' , {id:'product_item' ,name:'product_item_id', class:'form-control' , placeholder:'Product Item Number'})) ));
				}
			  else if(product_type_value == 2){
					//$('.part_number_div').remove();
					parent_div.after( $('<div>' ,{id:'tyre_id_div' , class:'row form-group part_number_div'}).append( $('<div>' , {class:'col-sm-12'}).append( $('<label>').text('Enter Tyre Item Number') ,  $('<input>' , {id:'tyre_id' , name:'product_item_id', class:'form-control' , placeholder:'Tyre Item Number'})) ));
				}
			  else if(product_type_value == 3){
				   //$('#tyre_id_div').remove();
				   parent_div.after( $('<div>' ,{id:'rim_id_div' , class:'row form-group part_number_div'}).append( $('<div>' , {class:'col-sm-12'}).append( $('<label>').text('Enter Rim Item Number') ,  $('<input>' , {id:'rim_id' ,name:'product_item_id', class:'form-control' , placeholder:'Rim Item Number'})) ));
				}		
			} 
		  else{
			   $('.part_number_div').remove();
			  /* $('#tyre_id_div').remove();
			   $('#rim_id_div').remove();*/
			}	
		}
	  else{
		$("#parts_brand_div").remove();  
		parent_product_brand = product_type.closest('#product_type_div');
		$.ajax({
			url: base_url+"/coupon_ajax/get_product_brand",
			method: "GET",
			data:{brand_type:product_type_value},
			success:function(data){
			  var parseJson = jQuery.parseJSON(data);
			  if(parseJson.status == 200){
				parent_product_brand.after( $('<div>' ,{id:'parts_brand_div' , class:'col-sm-12'}).css('margin-top' , 15).append( $('<div>').append( $('<label>').text('Select Brands') ,  $('<select>' , {class:'form-control' ,id:'brands' , name:'brand'}) ) ));
				console.log(data); 
				brands = $("#brands"); 
				brands.append( $('<option>' ,{value:0}).text('--Select--Brand--') );
				  $.each(parseJson.response , function(index , value){
					brands.append( $('<option>' ,{value:value.brand_name}).text(value.brand_name) )   
				  }); 
				}	
			}
	    }); 
	  }
	})
  /*End*/
});