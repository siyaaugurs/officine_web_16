$(document).ready(function(e) {
    /*Products serach by group by in assemble section*/
 $(document).on('click','#search_products_asseble',function(e){
	//$('#search_parts_group').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
   var groupid = $("#group_item").val();  
   var group_item_id = $("#item_id").val();
  if(groupid != 0 && group_item_id != 0){
	e.preventDefault();
    $.ajax({
		 url:base_url+"/products_ajax/search_products_asseble",
		 method:"GET",
		 data:{groupid:groupid , group_item_id:group_item_id},
		 success:function(data){
			$('#search_parts_group').html('Search &nbsp;<span class="glyphicon glyphicon-search"></span>').attr('disabled' , true);
		     $("#user_data_body").html(data);
		   // $("#row_paging_table").hide();
		 }
     });
	}
 });
 /*End*/
   /*Edit Services package*/
//   $(document).on('submit','#assemble_products_service_form',function(e){
// 	 //$('#add_services_btn').html('Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
//     daysData = []
//     $('#assemble_products_service_form .day-row').each(function() {
//       day_id = $(this).find('.weekly_days').val();
//       checked = $(this).find('.weekly_days').prop('checked');
//       records = [];
//       /*$(this).find('.add_fields > .row').each(function() {
//         records.push({start_time:$(this).find('#start_time').val(),end_time:$(this).find('#end_time').val(),price:$(this).find('#price').val(),  max_appointment:$(this).find('#maximum_appointment').val()})
//       })*/
//         $(this).find('.add_fields > .row').each(function() {
//             records.push({start_time:$(this).find('#start_time').val(),end_time:$(this).find('#end_time').val(),price:$(this).find('#price').val(),  max_appointment:$(this).find('#maximum_appointment').val()})
//         })
//       daysData.push({day:day_id,selected:checked,records:records})
//     })
	
//     var products_id = $("#products_id").val();
// 	var service_average_time = $("#service_average_time").val();
// 	var about_services = $("#about_services").val();
//     e.preventDefault();
//       $.ajax({
//           url: base_url+"/assemble_services/add_services",
//           type: "POST",        
//           data: {daysData:daysData , service_average_time:service_average_time , products_id:products_id , about_services:about_services},
//           dataType: 'json',
//           success: function(data){
//             $('#add_services_btn').html('Save &nbsp;<i class="icon-paperplane ml-2"></i> ').attr('disabled' , false);
// 			$(".close").click();
//             $("#msg_response_popup").modal('show');
//             $("#msg_response").html(data.msg); 
// 			if(data.status == 200){
// 			    setTimeout(function(){ location.reload(); } , 1000); 
// 			   }
// 		  }	,
//           error: function(xhr, error){
//             $('#add_services_btn').html('Save &nbsp;<i class="icon-paperplane ml-2"></i> ').attr('disabled' , false);
//             $("#edit_response").html('<div class="notice notice-danger"><strong>Wrong </strong>Something Wrong , please try again . !!! </div>');
//           }
//       });
//   });
  /*End*/  
     /*Add Assemble Services Modal Popup */
    $(document).on('click','#add_assemble_services',function(e){
        e.preventDefault();
        $("#add_assemble_service").modal('show');
    });
    /*End */
    
    /* Add Assemble Service Script Start*/
    $(document).on('submit', '#assemble_products_service_form', function (e) {
        $('#response_add_category').html(" ");
		$('#add_assemble_services_btn').html('Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
		e.preventDefault();
		$.ajax({
			url: base_url+"/assemble_services/add_assemble_services",
			type: "POST",   
			data: new FormData(this),
			contentType: false,
			cache: false,
			processData:false,    
			success: function(data){
				$('#add_assemble_services_btn').html(' Submit &nbsp;<i class="icon-paperplane ml-2"></i> ').attr('disabled' , false);
				var parseJson = jQuery.parseJSON(data);
				if(parseJson.status == 200){
					$("#assemble_products_service_form")[0].reset();
					$("#add_assemble_service").modal('hide');
					$("#msg_response_popup").modal('show');
					$("#msg_response").html(parseJson.msg);
					setTimeout(function(){ location.reload(); } , 1000);
				}
				if(parseJson.status == 100) {
					$("#assemble_products_service_form")[0].reset();
					$("#add_assemble_service").modal('hide');
					$("#msg_response_popup").modal('show');
					$("#msg_response").html(parseJson.msg);
				}	
			} , 
			error: function(xhr, error){
				$('#add_assemble_services_btn').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
				$("#response_msg").html(parseJson.msg);
			}
      	});
    });
    /* End */
    /*Manage Time Slot Script Start */
	$(document).on('click','.manage_assemble_time_slot',function(e){
        e.preventDefault();
        var service_id = $(this).data('serviceid');
        $(".modal-content #slot_service_id").val( service_id );
        $("#manage_assble_time_slot").modal('show');
	});
  /*End */
  /*On Change Time Slot Script Start */
    $(document).on('change','.special_slot',function(e){
        e.preventDefault();
        var slot_type = $(this).val();
        if(slot_type == 3) {
            $(this).closest('.col-sm-4').next('.slot').css("display", "block");
        }
        if(slot_type == 1) {
            $(this).closest('.col-sm-4').next('.slot').css("display", "none");
        }
        if(slot_type == 2) {
            $(this).closest('.col-sm-4').next('.slot').css("display", "none");
        }
    });
    /*End */
    /*Submit Time Slot script start */
    $(document).on('submit','#time_assemble_slots_form',function(e){
        $('#response_add_category').html(" ");
        $('#add_asmble_time_slot').html('Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
        daysData = []
        $('#time_assemble_slots_form .day-row').each(function() {
            day_id = $(this).find('.weekly_days').val();
            checked = $(this).find('.weekly_days').prop('checked');
            records = [];
            $(this).find('.add_fields > .row').each(function() {
                records.push({start_time:$(this).find('#start_time').val(),end_time:$(this).find('#end_time').val(),price:$(this).find('#price').val() ,  max_appointment:$(this).find('#maximum_appointment').val(), discount_type:$(this).find('#discount_type').val(), discount:$("#discount").val(), special_time_slot_type:$(this).find('#special_time_slot_type').val(), monthly_date:$(this).find('.monthly_date').val() })
            })
            daysData.push({day:day_id,selected:checked,records:records})
            // console.log(daysData);
        })
        var service_id = $("#slot_service_id").val(); 
        e.preventDefault();
        $.ajax({
            url: base_url+"/assemble_services/add_assemble_time_slot",
            type: "POST",        
            data: {daysData:daysData , service_id:service_id },
            dataType: 'json',
            success: function(data){
                $('#add_asmble_time_slot').html(' Submit &nbsp;<i class="icon-paperplane ml-2"></i> ').attr('disabled' , false);
                $("#msg_response_popup").modal('show');
                $("#msg_response").html(data.msg); 
                if(data.status == 200){
                    $("#time_assemble_slots_form")[0].reset();	
                    $("#manage_assble_time_slot").modal('hide');
                }
            }	,
            error: function(xhr, error){
                $('#add_asmble_time_slot').html(' Submit &nbsp;<i class="icon-paperplane ml-2"></i> ').attr('disabled' , false);
                $("#msg_response_popup").modal('show');
                $("#msg_response").html('<div class="notice notice-danger"><strong>Wrong </strong>Something Wrong , please try again . !!! </div>');
            }
        });
    });
/*End */
/*Save About workshop vscript start*/
	$(document).on('click','#edit_assemble_btn',function(e){
		e.preventDefault();
		var con = confirm("Are you sure want to edit .");
		if(con == true){
		$('#edit_assemble_btn').html('Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
			var product_id = $("#inventory_product").val();
			var average_time = $("#service_average_time").val();
			var about_services = $("#about_services").val();
			var services_id = $("#service_id").val();
			if(about_services != "" || services_id != ""){
				$.ajax({
					url:base_url+"/assemble_services/edit_assemble_services",
					type:"POST",
					data:{about_services:about_services , services_id:services_id ,  average_time:average_time ,product_id:product_id},
					success: function(data){
						$(".close").click();
						$('#edit_assemble_btn').html('Save &nbsp;<i class="icon-paperplane ml-2"></i>').attr('disabled' , false);  
						$('#about_assemble_service_section').load(document.URL + ' #about_assemble_service_section');
						$("#msg_response_popup").modal('show');
						$("#msg_response").html(data);
					}
				});  
			}
		}
	});
	/*End*/
});