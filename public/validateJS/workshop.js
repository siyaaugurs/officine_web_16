$(document).ready(function(e) {

    /*View Service Details Script code Starts*/

	$(document).on('click' , '.view_booking_service' , function(e){

        e.preventDefault();

		var service_id = $(this).data('serviceid');

		if(service_id != "") {

			$.ajax({

				url: base_url+"/workshop_ajax/view_service_details",

				type: "GET",        

				data:{serviceId:service_id},

				success: function(data){

					$("#service_response").html(data);

					$("#view_service_detail").modal('show');

				} 

			});

		}

	});

    /*End */

    /*Chnage Service Booking Status */

	$(document).on('click', '.change_services_status', function(e) {
        e.preventDefault();
        var $this = $(this);
        var service_id = $(this).data('id');
        var status = $(this).data('status');
        if (status == "C") {
            var con = confirm("Are you sure want to Complete JOB ?");
            if (con == true) {
                $.ajax({
                    url: base_url + "/workshop_ajax/change_service_status",
                    type: "GET",
                    data: { status: status, service_id: service_id },
                    success: function(data) {
                        if (status == "C") {
                            $this.data('status', 'D').removeClass('btn btn-danger badge badge-success').html('<span class="btn btn-info">Job Completed</span>');
                            $this.closest('tr').find('td').eq(4).find('span').removeClass('badge-success').addClass('badge-info').text('Job Completed');
                        }
                    }
                });
            }
        }
    });

	/*End */

});