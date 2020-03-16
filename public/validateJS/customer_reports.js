$(document).ready(function(e) {
/*Search users according to car123 script start*/	
 $(document).on('submit','#customer_search',function(e){
	$('#search_users_on_hold').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
		$("#user_data_body").html(" ");
		pagination_row = $("#pagination_row");
		e.preventDefault();
			$.ajax({
				url: base_url+"/customer_report/get_users",
				type: "POST",        
				data: new FormData(this),
				contentType: false,
				cache: false,
				processData:false,  
				success: function(data){
					pagination_row.empty();
					$('#search_users_on_hold').html('Search &nbsp;<span class="glyphicon glyphicon-search"></span>').attr('disabled' , false);
					$("#user_data_body").html(data); 	
				} 
			});

	   });
 /*End*/
});