$(document).ready(function(e) {
    /* Assemble product select Script Start */
    $(document).on('change','#group_item',function(){
        $("#preloader").show();     
        var group_id = $("#group_item").val();
        var language = $('html').attr('lang');
        if( group_id != ""){
            $.ajax({
                url:base_url+"/assemble_products/get_product",
                method:"GET",
                data:{groupId:group_id , language:language},
                success:function(data){
                    $("#preloader").hide(); 
                    var parseJson = jQuery.parseJSON(data);
                    if(parseJson.status == 200){
                        var html_content = '';
                        html_content += '<option hidden="hidden">--Select--Group--Item--<option>'; 
                        $.each(parseJson.response , function(index , value){
                            html_content += '<option value="'+value.id+'">'+ value.group_name +'</option>';      
                        });
                        $("#products_id").html(html_content); 
                    } 
                    if(parseJson.status == 100){
                        $("#products_id").html("<option>--No--Group--Available--</option>");
                    }
                } 
            });
        }	   
    });
    /*End */
});