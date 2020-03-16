$(document).ready(function(e) {
    $(document).on('click', '.remove_car_compatible', function(e){
        alert();
        e.preventDefault();
        var car_compatible_id = $(this).data('id');
        var con = confirm("Are you sure want to Delete ?");
        if(con == true){
            var url = base_url+"/products/remove_car_compatible/"+car_compatible_id;
            setTimeout(function(){ window.location.href = url; } , 1000);
        } else {
            return false;
        }
    });
/*Get n3 details script start*/
$(document).on('click', '.edit_n3_category', function(e){
    e.preventDefault();
    var n3_category_id = $(this).data('n3categoryid');
    if(n3_category_id != ""){
        $.ajax({
            url:base_url+"/products_ajax/get_n3_category",
            method:"GET",
            data:{n3_category_id:n3_category_id},
            success: function(data){
            var parseJson = jQuery.parseJSON(data);
            console.log(parseJson)
			if (parseJson.status == 200) {
				$("#kromeda_edit_n3_category_form #category_id").val(parseJson.response.id);
                // $("#kromeda_edit_n3_category_form #category_n1").val(parseJson.group_id);
                $('#kromeda_edit_n3_category_form #category_n1').find("option[value='"+ parseJson.n1_category.parent_id +"']").attr('selected','selected');
				$("#kromeda_edit_n3_category_form #sub_category").html( $('<option>' ,{value:parseJson.n1_category.id}).text(parseJson.n1_category.group_name));
				$("#sub_category_n3").val(parseJson.response.item);
                $('#kromeda_edit_n3_category_form #front_rare').find("option[value='"+ parseJson.response.front_rear +"']").attr('selected','selected');
                $('#kromeda_edit_n3_category_form #left_right').find("option[value='"+ parseJson.response.left_right +"']").attr('selected','selected');
			    $("#kromeda_edit_n3_category_form #description").val(parseJson.description);
		    	$("#kromeda_edit_n3_category_form #n3_priority").val(parseJson.priority);
				$("#kromeda_edit_n3_modal_popup").modal({
				    backdrop:'static',
					keyboard:false
				 });
			}
		}
        });
    } 
});
/*End*/	
/*Add n3 sub category script start*/
/*Upload manage n3 category image script start*/
  $(document).on('submit','#n3_category_images_form',function(e){
		$('#msg_response').html(" ");
		$("#err_response").html(" ");
		var group_id = $("#products_item_id").val();
     	$('#save_n3_group_image').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
		$('#err_response').html(""); 
		e.preventDefault();
            $.ajax({
					 url: base_url+"/products_ajax/add_n3_group_images",
					 type: "POST",        
					 data: new FormData(this),
					 contentType: false,
					 cache: false,
					 processData:false,  
					 success: function(data){
					  var errorString = '';
					  var parseJson = jQuery.parseJSON(data);
					  $('#save_n3_group_image').html(' Save &nbsp; <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
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
							  $("#n3_category_images_form")[0].reset();
						 }
						 if(parseJson.status == 100){
							$("#err_response").html(parseJson.msg);
						 }
						}	
			});
 	});
 /*Image Management End*/  
  
$(document).on('submit', '#kromeda_edit_n3_category_form', function(e){
        $('#msg_response').html(" ");
		$('#edit_kromeda_n3_category_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
        e.preventDefault();
        $.ajax({
            url: base_url+"/products_ajax/edit_kromeda_n3_category",
            type: "POST",        
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData:false,  
            success: function(data){
            $('#edit_kromeda_n3_category_btn').html('Save &nbsp;<i class="icon-paperplane ml-2"></i>').attr('disabled' , false);
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
                    setTimeout(function(){ location.reload(); } , 1000);
                }
                if(parseJson.status == 100){
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                }
            },
            error: function(xhr, error){
                $('#add_n3_category_btn').html('Save &nbsp;<i class="icon-paperplane ml-2"></i>').attr('disabled' , false);
                $("#msg_response_popup").modal('show');
                $('#msg_response').html('<div class="notice notice-danger"><strong>Wrong , </strong>Something Went Wrong please try again  .</div>');
            }	
                
        });
    });
/*End*/
/*Add n3 sub category script start*/
$(document).on('submit', '#add_n3_category_form', function(e){
        $('#msg_response').html(" ");
		$('#add_n3_category_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
        e.preventDefault();
        $.ajax({
            url: base_url+"/products_ajax/add_n3_category",
            type: "POST",        
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData:false,  
            success: function(data){
            $('#add_n3_category_btn').html('Save &nbsp;<i class="icon-paperplane ml-2"></i>').attr('disabled' , false);
                var errorString = '';
                var parseJson = jQuery.parseJSON(data);
                if(parseJson.status == 200){
                    $(".close").click();
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                    setTimeout(function(){ location.reload(); } , 1000);
                }
                if(parseJson.status == 100){
                    $("#msg_response_popup").modal('show');
                    $("#msg_response").html(parseJson.msg);
                }
            },
            error: function(xhr, error){
                $('#add_n3_category_btn').html('Save &nbsp;<i class="icon-paperplane ml-2"></i>').attr('disabled' , false);
                $("#msg_response_popup").modal('show');
                $('#msg_response').html('<div class="notice notice-danger"><strong>Wrong , </strong>Something Went Wrong please try again  .</div>');
            }	
                
        });
    });/*End*/
/*Check N3 priority script start*/
 $(document).on('blur','.priority_all',function(){
   var priority = $(this);
      priority.next().html(' ');
      priority_val = priority.val()
      if(priority_val != ""){
          $.ajax({ 
                url:base_url+"/products_group/check_priorities",
                method:"GET",
                data:{priority_val:priority_val , table:'products_groups_items'},
                success:function(data){
                    if(e.responseText == 1){
                        // $("#priority_btn").attr('disabled' , true);
                        priority.next().html('This priority already taken !!!');
                    }
                    else{
                        // $("#priority_btn").attr('disabled' , false); 
                    }
             
                 },
            });
      }
 });
/*End*/
/*Group and Sub group priority*/
 $(document).on('blur','.priority',function(){
   var priority = $(this);
      type = priority.data('type');
      priority.next().html(' ');
      priority_val = priority.val()
      if(priority_val != ""){
          $.ajax({ 
                url:base_url+"/products_group/check_priority",
                method:"GET",
                data:{priority_val:priority_val ,type:type},
                complete:function(e, xhr, settings){
                    if(e.responseText == 1){
                        // $("#add_n1_category_btn").attr('disabled' , true);
                        // $("#add_n2_category_btn").attr('disabled' , true);
                        priority.next().html('This priority already taken !!!');
                    }
                    else{
                        // $("#add_n1_category_btn").attr('disabled' , false); 
                        // $("#add_n2_category_btn").attr('disabled' , false); 
                    }
             
                 },
            });
      }
 });
/*End*/	
/*get n2 script start*/
   /*Add n3modal popup script start script */
   $(document).on('click', '#add_custom_n3_category', function(e){
        e.preventDefault();
        $("#add_n3_modal_popup").modal({
            backdrop:'static',
            keyboard:false,
        });
    });
   /*End*/ 
   /*Get products sub category script start*/
	$(document).on('change', '#category_n1', function(e){
		var language = $('html').attr('lang');
		group_id = $(this).val();
		 $(".sub_groups").empty(); 
        $.ajax({
				url:base_url+"/products_group/get_sub_groups",
				method:"GET",
				data:{group_id:group_id , language:language},
				success:function(data){
				   $("#preloader").hide();
					var parseJson = jQuery.parseJSON(data);
					console.log(parseJson.status)
					var html_content = '';
					if(parseJson.status == 200){
						html_content += '<option value="0">--Select--Sub--Category--</option>'; 
						$.each(parseJson.response , function(index , value){
							html_content += '<option value="'+value.id+'">'+ value.group_name +'</option>';      
						});
					    $(".sub_groups").html(html_content); 	
					}
					if(parseJson.status == 404){
					  $(".sub_groups").html($('<option>' ,{value:0}).text('No sub category available !!!')); 	
					}
				 
					
				},
				error: function(xhr, error){
					$("#preloader").hide();
				}
			}); 
    });  
	/*End*/	
/*End*/	
 /*Get N3 Script start*/
 $(document).on('change','.sub_category',function(e){
	group_id = $(this).val();
	$(".items").empty();
	var language = $('html').attr('lang');
	if(group_id != ""){
		if(group_id != "all") {
			$("#preloader").show();
			$.ajax({
				url:base_url+"/save_products_item_05_08",
				method:"GET",
				data:{group_id:group_id , language:language},
				complete:function(e , xhr , setting){
					if(e.status == 200){
						$.ajax({
							url:base_url+"/products_group/get_sub_category_n3",
							method:"GET",
							data:{group_id:group_id , language:language},
							complete:function(e , xhr , settings){
							if(e.status == 200){
								var parseJson = jQuery.parseJSON(e.responseText); 
								if(parseJson.status == 200){
									$(".items").append($('<option>' ,{value:0 }).text('--Select--Item--').attr('hidden', 'hidden'));
									$(".items").append($('<option>' ,{value:'all' }).text('All Category items'));
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
									$(".items").append($('<option>' ,{value:value.id }).text(text_name));
										$("#preloader").hide();
									});
								}
								if(parseJson.status == 100){
									alert("Something Went Wrong please try again ");
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
		} else {
			$("#preloader").hide();
			html_content = '<option value="0">--Select--Item--</option>'; 
			html_content += '<option value="all">All Category items</option>'; 
			$(".items").html(html_content);
		}
	} 
});
/*Add N2 categorpy popup open script start*/
   $(document).on('submit', '#add_n2_category_form', function(e) {
            $('#msg_response').html(" ");
            $('#add_n2_category_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
            e.preventDefault();
            $.ajax({
                url: base_url + "/products_ajax/add_n2_category",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    $('#add_n2_category_btn').html('Save &nbsp;<i class="icon-paperplane ml-2"></i>').attr('disabled', false);
                    var errorString = '';
                    var parseJson = jQuery.parseJSON(data);
                    if (parseJson.status == 400) {
                        $.each(parseJson.error, function(key, value) {
                            errorString += '<div class="notice notice-danger"><strong>Note , </strong>' + value + ' .</div>';
                        });
                        $('#response_coupon').html(errorString);
                    }

                    if (parseJson.status == 200) {
                        $(".close").click();
                        $("#msg_response_popup").modal('show');
                        $("#msg_response").html(parseJson.msg);
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }
                    if (parseJson.status == 100) {
                        $("#msg_response_popup").modal('show');
                        $("#msg_response").html(parseJson.msg);
                    }
                },
                error: function(xhr, error) {
                    $('#new_group_btn').html('Save &nbsp;<i class="icon-paperplane ml-2"></i>').attr('disabled', false);
                    $("#msg_response_popup").modal('show');
                    $('#msg_response').html('<div class="notice notice-danger"><strong>Wrong , </strong>Something Went Wrong please try again  .</div>');
                }
            });

        });

 $(document).on('click', '#add_sub_group', function(e) {
	e.preventDefault();
	$('#n2_category_id').val("");
	$('#n2_group_name').find("option[value='0']").attr('selected', 'selected');
	$("#sub_group_name").val("");
	$("#n2_description").val("");
	$("#n2_priority").val("");
	$("#add_sub_group_modal_popup").modal({
		backdrop: 'static',
		keyboard: false,
	});
});
/*End*/

/*Edit N1 scdript start*/
$(document).on('submit', '#edit_category_form', function(e) {
            $('#msg_response').html(" ");
            //$('#edit_category_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
            e.preventDefault();
            $.ajax({
                url: base_url + "/products_ajax/edit_category_details",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    $('#edit_category_btn').html('Save &nbsp;<i class="icon-paperplane ml-2"></i>').attr('disabled', false);
                    var errorString = '';
                    var parseJson = jQuery.parseJSON(data);
                    if (parseJson.status == 400) {
                        $.each(parseJson.error, function(key, value) {

                            errorString += '<div class="notice notice-danger"><strong>Note , </strong>' + value + ' .</div>';

                        });
                        $('#response_coupon').html(errorString);
                    }

                    if (parseJson.status == 200) {
                        $(".close").click();
                        $("#msg_response_popup").modal('show');
                        $("#msg_response").html(parseJson.msg);
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }

                    if (parseJson.status == 100) {
                        $("#msg_response_popup").modal('show');

                        $("#msg_response").html(parseJson.msg);
                    }
                },
                error: function(xhr, error) {
                    $('#edit_category_btn').html('Save &nbsp;<i class="icon-paperplane ml-2"></i>').attr('disabled', false);

                    $("#msg_response_popup").modal('show');

                    $('#msg_response').html('<div class="notice notice-danger"><strong>Wrong , </strong>Something Went Wrong please try again  .</div>');

                }

            });

        });
/*End*/

/*Add N1 script start*/
$(document).on('submit', '#add_n1_category_form', function(e) {
            $('#msg_response').html(" ");
            $('#add_n1_category_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
            e.preventDefault();
            $.ajax({
                url: base_url + "/products_ajax/add_n1_category",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    $('#add_n1_category_btn').html('Save &nbsp;<i class="icon-paperplane ml-2"></i>').attr('disabled', false);
                    var errorString = '';
                    var parseJson = jQuery.parseJSON(data);
                    if (parseJson.status == 400) {
                        $.each(parseJson.error, function(key, value) {
                            errorString += '<div class="notice notice-danger"><strong>Note , </strong>' + value + ' .</div>';
                        });
                        $('#response_coupon').html(errorString);
                    }
                    if (parseJson.status == 200) {
                        $(".close").click();
                        $("#msg_response_popup").modal('show');
                        $("#msg_response").html(parseJson.msg);
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }
                    if (parseJson.status == 100) {
                        $("#msg_response_popup").modal('show');
                        $("#msg_response").html(parseJson.msg);
                    }
                },
                error: function(xhr, error) {
                    $('#new_group_btn').html('Save &nbsp;<i class="icon-paperplane ml-2"></i>').attr('disabled', false);
                    $("#msg_response_popup").modal('show');
                    $('#msg_response').html('<div class="notice notice-danger"><strong>Wrong , </strong>Something Went Wrong please try again  .</div>');

                }

            });

        });
/*End*/

 /*End*/	
 $(document).on('change','.groups',function(e){
  var version = $('#version_id').val();
  category_id = $(this).val();
  var language = $('html').attr('lang');
  $("#sub_groups").empty();
  $("#items").html( $('<option>' , {value:0}).text('First Select Sub category'));
  if(category_id != "all"){
	$.ajax({
		url:base_url+"/products_group/get_sub_category_n2",
		method:"GET",
		data:{version:version ,category_id:category_id ,language:language},
		success:function(data){
		   var parseJson = jQuery.parseJSON(data);
		   if(parseJson.status == 200){
			   $("#sub_groups").html(parseJson.response);
			   //console.log(parseJson.response);
			 }
		}
	   });
	}
  else{
	   $("#sub_groups").html( $('<option>' , {value:'all'}).text('All Sub category')); 
	   $("#items").html( $('<option>' , {value:'all'}).text('All Sub category (N3)')); 
	}	
  
      });
  /*Edit category script start*/
  $(document).on('click', '.edit_category', function(e) {
    e.preventDefault();
    $this = $(this);
    $(".priority").val(" ");
    var category_id = $(this).data('categoryid');
    var category_type = $(this).data('categorytype');
    var category_name = $(this).data('categoryname');
    var description = $(this).data('description');
    var priority = $(this).data('priority');
    $('#edit_category_form #category_id').val(category_id);
    $('#category_type').val(category_type);
    $('#edit_group_name').val(category_name);
    $('#category_description').val(description);
    $("#edit_category_detils").modal({
        backdrop: 'static',
        keyboard: false,
    });
    $(".priority").val(priority);          
});
  /*End*/    
});