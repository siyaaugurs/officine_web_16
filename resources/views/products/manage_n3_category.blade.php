@extends('layouts.master_layouts')
@section('content')
<style>
.container{ padding:15px; }
 </style>
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="card" style="margin-bottom:10px;">
    <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-filter"></i>&nbsp;@lang('messages.Filter')</h6>
    </div>
    <div class="content">
	    <div id="filter-panel">
            <div class="panel panel-default">
                <div class="panel-body">
                   <div class="row">
                      <div class="col-sm-6">
                        <div class="form-group">
                            <select class="form-control" name="car_makers" id="car_makers">
                               <option value="0">@lang('messages.selectMaker')</option>
                                 @foreach($cars__makers_category as $makers)
                                   <option value="@if(!empty($makers->idMarca)){{$makers->idMarca}}@endif">@if(!empty($makers->Marca)){{ $makers->Marca }} @endif</option>
                                 @endforeach 
                            </select>                                
                        </div> 
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                            <select class="form-control" name="car_models" id="car_models">
                                 <option value="0">@lang('messages.firstSelectMakers')</option>
                            </select>                                
                        </div>
                      </div>
                   </div>
                   <div class="row">
                      <div class="col-sm-6">
                        <div class="form-group">
                            <select class="form-control car_version_group" name="car_version" data-action="get_and_save_n1">
                                <option value="0">@lang('messages.firstSelectModels')</option>
                            </select>                                
                        </div> 
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                            <select class="form-control groups_n1" name="car_group_version" id="group_n1" data-action="get_and_save_products_item">
                              <option value="0">@lang('messages.firstSelectVersion')</option>
                            </select>                                
                        </div>
                      </div>
                   </div>
                   <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                            <select class="form-control groups" name="car_group" id="group_n2" data-action="get_and_save_products_item">
                              <option value="0">@lang('messages.firstSelectVersion')</option>
                            </select>                                
                        </div>
                      </div>
                   </div>
                   <div class="row">
                      <div class="col-sm-6">
                        <div class="form-group">
                          <a href='#' id="search_n3_category" class="btn btn-warning" style="color:white;">Search &nbsp;<span class="glyphicon glyphicon-search"></span></a>                                 
                        </div>
                      </div>
                   </div>
                </div>
            </div>
        </div>  
    </div>
</div>
    <!-- Page length options -->
    @if(Session::has('msg'))
      {!! session::get('msg') !!}
    @endif
    <div id="user_data_body">
      
    </div>
    <div id="final_products_response"></div>
<!----Edit N3 Category for krmeda script start--->

<div class="modal" id="kromeda_edit_n3_modal_popup">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i>
				</button>
				<h4 class="modal-title" id="myModalLabel">Add N3 Category </h4>
				<hr />
			</div>
			<!-- Modal body -->
            <div class="card-body">
               <form id="kromeda_edit_n3_category_form" autocomplete="off">
                        @csrf
                        <input type="hidden" name="k_category_group_id" id="k_category_group_id" value="">
                        <input type="hidden" name="k_groups_item_id" id="k_groups_item_id" >
                        <div class="form-group">
                            <span id="title_err"></span>
                            <label>@lang('messages.GroupItem')&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="@lang('messages.GroupItem')" name="group_name" id="kromeda_group_name" required="required" value="" />
                        </div>
                        <div class="form-group">
                            <span id="title_err"></span>
                            <label>@lang('messages.FrontRare')&nbsp;<span class="text-danger">*</span></label>
                            <select name="front_rare" id="kromeda_front_rare" class="form-control">
                                <option value=" ">Select</option>
                                <option value="front">Front</option>
                                <option value="rear">Rare</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <span id="title_err"></span>
                            <label>@lang('messages.LeftRight')&nbsp;<span class="text-danger">*</span></label>
                            <select name="left_right" id="kromeda_left_right" class="form-control">
                                <option value=" ">Select</option>
                                <option value="lh.">Left</option>
                                <option value="rh.">Right</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <span id="title_err"></span>
                            <label>@lang('messages.Description')&nbsp;<span class="text-danger">*</span></label>
                            <textarea name="description" id="kromeda_description" class="form-control" placeholder="@lang('messages.Description')"></textarea>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-check form-check-inline">
                                <button type="submit" id="edit_kromeda_n3_category_btn" class="btn bg-blue ml-3">@lang('messages.Submit')<i class="icon-paperplane ml-2"></i></button>
                            </div>
                        </div>
                    </form>
            </div>
			<div id="response_err"></div>
		</div>
		<div class="modal-footer"></div>
	</div>
</div>
<!--End-->
<div class="modal" id="add_n3_modal_popup">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i>
				</button>
				<h4 class="modal-title" id="myModalLabel">Add N3 Category </h4>
				<hr />
			</div>
			<!-- Modal body -->
            <div class="card-body">
               <form id="add_n3_category_form" autocomplete="off">
                        @csrf
                        <input type="hidden" name="category_group_id" id="category_group_id" value="">
                        <input type="hidden" name="n3_category_id" id="n3_category_id" value="">
                        <div class="form-group">
                            <span id="title_err"></span>
                            <label>@lang('messages.GroupItem')&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="@lang('messages.GroupItem')" name="group_name" id="group_name" required="required" value="" />
                        </div>
                        <div class="form-group">
                            <span id="title_err"></span>
                            <label>@lang('messages.FrontRare')&nbsp;<span class="text-danger">*</span></label>
                            <select name="front_rare" id="front_rare" class="form-control">
                                <option value=" ">Select</option>
                                <option value="front">Front</option>
                                <option value="rear">Rare</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <span id="title_err"></span>
                            <label>@lang('messages.LeftRight')&nbsp;<span class="text-danger">*</span></label>
                            <select name="left_right" id="left_right" class="form-control">
                                <option value=" ">Select</option>
                                <option value="lh.">Left</option>
                                <option value="rh.">Right</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <span id="title_err"></span>
                            <label>@lang('messages.Description')&nbsp;<span class="text-danger">*</span></label>
                            <textarea name="description" id="description" class="form-control" placeholder="@lang('messages.Description')"></textarea>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-check form-check-inline">
                                <button type="submit" id="add_n3_category_btn" class="btn bg-blue ml-3">@lang('messages.Submit')<i class="icon-paperplane ml-2"></i></button>
                            </div>
                        </div>
                    </form>
            </div>
			<div id="response_err"></div>
		</div>
		<div class="modal-footer"></div>
	</div>
</div>

<div class="modal" id="add_group_image_popup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Upload Category Images</h4>
                <hr />
            </div>
            <div id="err_response"></div>
            <form id="n3_category_images_form">
                @csrf
                <input type="hidden" name="group_id" id="group_id" value="" readonly="readonly" />
                <div class="modal-body">
                    <div class="control-group" id="fields">Browse Multiple Image</label>
                        <div class="controls">
                            <div class="entry input-group col-xs-3">
                                <input class="btn btn-primary" name="images[]" type="file" multiple="multiple" accept=".jpg,.png," require>
                                <span class="input-group-btn">
                                &nbsp;&nbsp;
                                <button class="btn btn-success btn-add" type="submit" id="save_n3_group_image">
                                Save
                                <span class="glyphicon glyphicon-plus"></span>
                                </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div id="image_result"></div>
        </div>
        <div class="modal-footer">       
        </div>
    </div>
</div>
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
            <a href="{{ url('/') }}" class="breadcrumb-item">Admin </a>
            <a href="#" class="breadcrumb-item">Products </a>
            <span class="breadcrumb-item active">N3 Category</span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop
@push('scripts')
 <script src="{{ url('validateJS/products_05_08.js') }}"></script>
<link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" />
<script src="{{ asset('validateJS/admin.js') }}"></script>
<script src="{{ url('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ url('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>
<script>
$(document).ready(function(e) {
    $('#example').DataTable( {
    } );
    $(document).on('click', '#search_n3_category', function(e){
        e.preventDefault();
        $('#search_n3_category').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
        var group_id = $("#group_n2").val(); 
        if(group_id != "" &&  group_id != 0){
            $.ajax({
                url:base_url+"/search_n3_category_05_08",
                method:"GET",
                data:{group_id:group_id},
                complete:function(e , xhr){
                    console.log(e);
                    $('#search_n3_category').html('Search &nbsp;<span class="glyphicon glyphicon-search"></span>').attr('disabled' , true);
                    if(e.status == 200){
                        $("#user_data_body").html(e.responseText);
                    }
                }
            });
        } else {
            alert("Please select all required fields !!!");
        }
    });
    $(document).on('click', '#add_custom_n3_category', function(e){
        e.preventDefault();
        //$('#group_n1').val("");
        var group_id = $("#group_n2").val(); 
        $('#category_group_id').val(group_id);
        $("#add_n3_modal_popup").modal({
            backdrop:'static',
            keyboard:false,
        });
    });

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
   $(document).on('click', '.edit_n3_category', function(e){
        e.preventDefault();
        var n3_category_id = $(this).data('n3categoryid');
        var type = $(this).data('type');
        var group_id = $("#group_n2").val(); 
        if(n3_category_id != ""){
                $.ajax({
                    url:base_url+"/products_ajax/get_n3_category",
                    method:"GET",
                    data:{n3_category_id:n3_category_id},
                    success: function(data){
                    var parseJson = jQuery.parseJSON(data);
                    if (parseJson.status == 200) {
                        if(type == 2) {
                            $("#n3_category_id").val(parseJson.response.id);
                            $("#category_group_id").val(group_id);
                            $("#group_name").val(parseJson.response.item);
                            $('#front_rare').find("option[value='"+ parseJson.response.front_rear +"']").attr('selected','selected');
                            $('#left_right').find("option[value='"+ parseJson.response.left_right +"']").attr('selected','selected');
                            $("#description").val(parseJson.response.our_description);
                            $("#myModalLabel").html('Edit N3 Category');
                            $("#add_n3_modal_popup").modal('show');
                        } else {
                            $("#k_groups_item_id").val(parseJson.response.id);
                            $("#k_category_group_id").val(group_id);
                            $("#kromeda_group_name").val(parseJson.response.item);
                            $('#kromeda_front_rare').find("option[value='"+ parseJson.response.front_rear +"']").attr('selected','selected');
                            $('#kromeda_left_right').find("option[value='"+ parseJson.response.left_right +"']").attr('selected','selected');
                            $("#kromeda_description").val(parseJson.response.our_description);
                            $("#kromeda_edit_n3_modal_popup").modal('show');
                        }
                    }
                }
                });
            } 
    });
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
    $(document).on('click', '.n3_category_image', function(e){
        e.preventDefault();
        var n3_category_id = $(this).data('n3categoryid');
        show_group_image(n3_category_id);
    });

    function show_group_image(n3_category_id){
        if(n3_category_id != ""){
            $("#group_id").val(n3_category_id);
                $.ajax({
                url: base_url+"/products_ajax/get_n3_group_image",
                method: "GET",
                data: {groupId:n3_category_id},
                success: function(data){
                $('#image_result').html(data);
                $('#add_group_image_popup').modal('show');
                }
        });
        }	
    }

    $(document).on('submit','#n3_category_images_form',function(e){
		$('#msg_response').html(" ");
		$("#err_response").html(" ");
		var group_id = $("#group_id").val();
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
							  $("#group_images_form")[0].reset();
						 }
						 if(parseJson.status == 100){
							$("#err_response").html(parseJson.msg);
						 }
						}	
			});
 	});

    $(document).on('click', '.delete_n3_category', function(e){
        e.preventDefault();
        var n3_category_id = $(this).data('n3categoryid');
        var con = confirm('Are You Sure Want to Delete ?');
        if(con == true){
            var url = base_url+"/products/delete_n3_category/"+n3_category_id;
            setTimeout(function(){ window.location.href = url; } , 1000);
        } else {
            return false;
        }
    });
} );
</script>
@endpush


