$(document).ready(function(e) {
  $(document).on('click','.remove_car_compatible',function(){
    compatible = $(this);
	compatible.html('<i class="icon-spinner2 spinner"></i>');
	compatible_id = compatible.data('id');
	if(compatible_id != ""){
	$.ajax({
			url: base_url+"/spare_products/remove_car_compatible",
			type: "GET",        
			data: {compatible_id:compatible_id},
			success: function(data){
			  compatible.html('<i class="fa fa-trash"></i>');
			   if(data == 200){
				  compatible.closest('tr').remove(); 
				 }
			},
			error: function(xhr, error){
				$("#msg_response_popup").modal('show');
				$("#msg_response").html('<div class="notice notice-danger"><strong>Wrong , </strong> Something Went wrong please try again  .</div>');
			},
        }); 
	  }
		
  });  
});