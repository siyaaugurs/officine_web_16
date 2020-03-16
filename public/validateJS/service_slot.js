$(document).ready(function(e) {
    /*Manage Time Slot Script Start */
	$(document).on('click','.manage_time_slot',function(e){
        e.preventDefault();
        var service_id = $(this).data('serviceid');
		car_size = $(this).data('carsize');
        $(".modal-content #slot_service_id").val(service_id);
		$(".modal-content #service_car_size").val(car_size);
        $("#manage_time_slot").modal('show');
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
   /*Submit Time Slot script start */
    $(document).on('submit','#time_slots_form',function(e){
        $('#response_add_category').html(" ");
		$('#add_services_btn').html('Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
        daysData = []
        $('#time_slots_form .day-row').each(function() {
            day_id = $(this).find('.weekly_days').val();
            checked = $(this).find('.weekly_days').prop('checked');
            records = [];
            $(this).find('.add_fields > .row').each(function() {
                records.push({start_time:$(this).find('#start_time').val() , end_time:$(this).find('#end_time').val()})
            })
            daysData.push({day:day_id,selected:checked,records:records})
            console.log(daysData);
        })
        var service_id = $("#slot_service_id").val(); 
		var car_size = $("#service_car_size").val(); 
        e.preventDefault();
        $.ajax({
			url: base_url+"/car_wash/time_slot",
			type: "POST",        
			data: {daysData:daysData , service_id:service_id , car_size:car_size },
			dataType: 'json',
			success: function(data){
				$('#add_services_btn').html(' Submit &nbsp;<i class="icon-paperplane ml-2"></i> ').attr('disabled' , false);
				$("#msg_response_popup").modal('show');
				$("#msg_response").html(data.msg); 
				if(data.status == 200){
                    $("#time_slots_form")[0].reset();	
                    $("#manage_time_slot").modal('hide');
				}
			}	,
			error: function(xhr, error){
				$('#add_services_btn').html(' Submit &nbsp;<i class="icon-paperplane ml-2"></i> ').attr('disabled' , false);
				$("#msg_response_popup").modal('show');
				$("#msg_response").html('<div class="notice notice-danger"><strong>Wrong </strong>Something Wrong , please try again . !!! </div>');
			}
      	});
    });
    /*End */
    /*End */
    /*Add Services Modal Popup */
    
    $(document).on('click','#add_services',function(e){
       e.preventDefault();
	    $.ajax({
			   url: base_url+"/car_wash/get_washing_details",
			   type: "GET",        
			   	success: function(data){
					var parseJson = jQuery.parseJSON(data);
					if(parseJson.status == 200){
						$("#hourly_rate").val(parseJson.response.hourly_rate);
						$("#max_appointment").val(parseJson.response.maximum_appointment);
					}
					$("#add_car_washing_services").modal({
						backdrop: 'static',
						keyboard: false,
					});
			   	}
		   	});
    });
    
    
    /*End */
     /*Add car wash revision Services Modal Popup */
    $(document).on('click','#add_car_revision_details',function(e){
        e.preventDefault();
        $("#add_car_revision_details").modal('show');
    });
    /*End */
});