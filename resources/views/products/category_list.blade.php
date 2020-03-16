@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
@if(session::has('msg'))
  {!! Session::get('msg') !!}
@endif
 <style> .container{ padding:15px;} </style>
<div class="row" style="margin-bottom:10px;">
  <div class="col-sm-12">
    <!--<a href='{{ url("products/add_group") }}' class="btn btn-primary" style="color:white;">Add New Category &nbsp;<span class="glyphicon glyphicon-plus"></span></a>&nbsp;&nbsp;&nbsp;
    -->
    <a href="#" class="btn btn-primary" id="add_group" style="color:white;">Add New category &nbsp;<span class="glyphicon glyphicon-plus"></span></a>&nbsp;&nbsp;&nbsp;
    <a href="#" class="btn btn-primary" id="add_sub_group" style="color:white;">Add New Sub-Category &nbsp;<span class="glyphicon glyphicon-plus"></span></a>&nbsp;&nbsp;&nbsp;
    <a href='{{ url("products/manage_n3_category") }}' class="btn btn-primary" style="color:white;">Add New Sub-Category (N3) &nbsp;<span class="glyphicon glyphicon-plus"></span></a>
  </div>
</div>
<div class="card" style="margin-bottom:10px;" >
    <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-filter"></i>&nbsp;@lang('messages.Filter')</h6>
        </div>
    <div class="container">
	    <div id="filter-panel">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form class="form-inline" role="form">
                        <div class="form-group">
                            <select class="form-control" name="car_makers" id="car_makers">
                                <option value="0">@lang('messages.selectMaker')</option>
                                 @foreach($cars__makers_category as $makers)
                                   <option value="{{ $makers->idMarca }}">{{ $makers->Marca }}</option>
                                 @endforeach 
                            </select>                                
                        </div> &nbsp;&nbsp;&nbsp;
                        <div class="form-group">
                            <select class="form-control" name="car_models" id="car_models">
                                 <option value="0">@lang('messages.firstSelectMakers')</option>
                            </select>                                
                        </div>&nbsp;&nbsp;&nbsp;
                        <div class="form-group">
                            <select class="form-control car_version_group" name="car_version" id="version_id" data-action="get_and_save_1">
                               <option value="0">@lang('messages.firstSelectModels')</option>
                            </select>                                
                        </div>&nbsp;&nbsp;&nbsp;
                        <div class="form-group">
                          <a href='#' id="search_parts_group" class="btn btn-warning" style="color:white;">Search &nbsp;<span class="glyphicon glyphicon-search"></span></a>                                 
                        </div>
                    </form>
                </div>
            </div>
        </div>       
  </div>
</div>
<div class="card">
<div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-filter"></i>&nbsp;@lang('messages.CategoryList')</h6>
        </div>
	<div class="card-body" id="user_data_body">
        @include('products.component.category_list' , ['group_list'=>$group_list])
        <div class="row" style="margin-top:20px;">
          <div class="col-sm-12">
           @if($group_list->count() > 0) 
             {{ $group_list->links() }}
           @endif 
          </div>
        </div>
    </div>
</div>
<div class="modal" id="view_sub_category">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Sub Category</h4>
                <hr />
            </div>
            <div id="err_response"></div>
            <div id="sub_category">

            </div>
        </div>
        <div class="modal-footer">       
        </div>
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
            <!-- Modal body -->
            <form id="group_images_form">
                @csrf
                <input type="hidden" name="group_id" id="group_id" value="" readonly="readonly" />
                <div class="modal-body">
                   <div class="control-group" id="fields">
          <label class="control-label" for="field1">
           Browse Multiple Image
          </label>
          <div class="controls">
              <div class="entry input-group col-xs-3">
                <input class="btn btn-primary" name="images[]" type="file" multiple="multiple" accept=".jpg,.png," require>
                <span class="input-group-btn">
                 &nbsp;&nbsp;
                 <button class="btn btn-success btn-add" type="submit" id="save_group_image">
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

<div class="modal" id="add_group_modal_popup">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i>
				</button>
				<h4 class="modal-title" id="myModalLabel">Add Group </h4>
				<hr />
			</div>
			<!-- Modal body -->
            <div class="card-body">
               <form id="add_n1_category_form" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <span id="title_err"></span>
                            <label>@lang('messages.GroupName')&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="@lang('messages.GroupName')" name="group_name" id="group_name" required="required" value="" />
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-check form-check-inline">
                                <button type="submit" id="add_n1_category_btn" class="btn bg-blue ml-3">@lang('messages.Submit')<i class="icon-paperplane ml-2"></i></button>
                            </div>
                        </div>
                    </form>
            </div>
			<div id="response_err"></div>
		</div>
		<div class="modal-footer"></div>
	</div>
</div>

<div class="modal" id="add_sub_group_modal_popup">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i>
				</button>
				<h4 class="modal-title" id="myModalLabel">Add Sub Group </h4>
				<hr />
			</div>
			<!-- Modal body -->
            <div class="card-body">
               <form id="add_n2_category_form" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <span id="title_err"></span>
                            <label>@lang('messages.GroupName')&nbsp;(N1 Category)<span class="text-danger">*</span></label>
                            <select name="group_name" id="group_name" class="form-control">
                                <option value="0" hidden="hidden">Select Group Name</option>
                                @foreach($n1_category_list as $category)
                                    <option value="{{ $category->id }}">{{ $category->group_name }}</option>
                                @endforeach
                                @foreach($n1_custom_category_list as $custom_category)
                                    <option value="{{ $custom_category->id }}">{{ $custom_category->group_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <span id="title_err"></span>
                            <label>@lang('messages.SubGroupName')&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="@lang('messages.SubGroupName')" name="sub_group_name" id="sub_group_name" required="required" value="" />
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-check form-check-inline">
                                <button type="submit" id="add_n2_category_btn" class="btn bg-blue ml-3">@lang('messages.Submit')<i class="icon-paperplane ml-2"></i></button>
                            </div>
                        </div>
                    </form>
            </div>
			<div id="response_err"></div>
		</div>
		<div class="modal-footer"></div>
	</div>
</div>
<div class="modal" id="edit_category_detils">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i>
				</button>
				<h4 class="modal-title" id="myModalLabel">Edit Category Details </h4>
				<hr />
			</div>
			<!-- Modal body -->
            <div class="card-body">
               <form id="edit_category_form" autocomplete="off">
                        @csrf
                        <input type="hidden" name="category_type" id="category_type" value="">
                        <input type="hidden" name="category_id" id="category_id" value="">
                        <div class="form-group">
                            <span id="title_err"></span>
                            <label>@lang('messages.GroupName')&nbsp;(N1 Category)<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="@lang('messages.GroupName')" name="edit_group_name" id="edit_group_name" required="required" value="" />
                        </div>
                        <div class="form-group">
                            <span id="title_err"></span>
                            <label>@lang('messages.Description')&nbsp;<span class="text-danger">*</span></label>
                            <textarea class="form-control" name="description" id="category_description" placeholder="@lang('messages.Description')"></textarea>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-check form-check-inline">
                                <button type="submit" id="edit_category_btn" class="btn bg-blue ml-3">@lang('messages.Submit')<i class="icon-paperplane ml-2"></i></button>
                            </div>
                        </div>
                    </form>
            </div>
			<div id="response_err"></div>
		</div>
		<div class="modal-footer"></div>
	</div>
</div>
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
	<div class="d-flex">
		<div class="breadcrumb">
			<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
			<a href="{{ url('/') }}" class="breadcrumb-item">Admin </a>
            <a href="{{ url('products/products_list') }}" class="breadcrumb-item">Products </a>
            <span class="breadcrumb-item active">Category List</span>
		</div>
		<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
	</div>
</div>
@stop
@push('scripts')
<link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" />

<script>
$(document).ready(function(e) {
    $('[data-toggle="tooltip"]').tooltip(); 

    $('#category_list tbody tr').each(function(){
        $(this).find('td').eq(4).on('dblclick', function(){
            $(this).prop('contenteditable', true);
            
        });
        $(this).find('td').eq(4).on('blur', function(){
            $(this).prop('contenteditable', false);
            var category_name = $(this).text();
            var category_id = $(this).parent().find('.category_id').val();
            $.ajax({
                url: "<?php echo url("product/edit_group_name"); ?>",
                type: 'GET',
                data: {categoryId:category_id,categoryName:category_name},
            });
            
        })
    });
    $(document).on('click', '#add_group', function(e){
        e.preventDefault();
        $("#add_group_modal_popup").modal('show');
    });
    
    $(document).on('click', '#add_sub_group', function(e){
        e.preventDefault();
        $("#add_sub_group_modal_popup").modal('show');
    });
    $(document).on('submit', '#add_n1_category_form', function(e){
        $('#msg_response').html(" ");
		$('#add_n1_category_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
        e.preventDefault();
        $.ajax({
            url: base_url+"/products_ajax/add_n1_category",
            type: "POST",        
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData:false,  
            success: function(data){
            $('#add_n1_category_btn').html('Save &nbsp;<i class="icon-paperplane ml-2"></i>').attr('disabled' , false);
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
            $('#new_group_btn').html('Save &nbsp;<i class="icon-paperplane ml-2"></i>').attr('disabled' , false);
                $("#msg_response_popup").modal('show');
                $('#msg_response').html('<div class="notice notice-danger"><strong>Wrong , </strong>Something Went Wrong please try again  .</div>');
            }	
                
        });
    });

    $(document).on('submit', '#add_n2_category_form', function(e){
        $('#msg_response').html(" ");
		$('#add_n2_category_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
        e.preventDefault();
        $.ajax({
            url: base_url+"/products_ajax/add_n2_category",
            type: "POST",        
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData:false,  
            success: function(data){
            $('#add_n2_category_btn').html('Save &nbsp;<i class="icon-paperplane ml-2"></i>').attr('disabled' , false);
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
            $('#new_group_btn').html('Save &nbsp;<i class="icon-paperplane ml-2"></i>').attr('disabled' , false);
                $("#msg_response_popup").modal('show');
                $('#msg_response').html('<div class="notice notice-danger"><strong>Wrong , </strong>Something Went Wrong please try again  .</div>');
            }	
                
        });
    });
    $(document).on('click', '.edit_category', function(e){
        e.preventDefault();
        $this = $(this);
	    var category_id = $(this).data('categoryid');
        var category_type = $(this).data('categorytype');
        var category_name = $(this).data('categoryname');
        var description = $(this).data('description');
        $('#category_id').val(category_id);
        $('#category_type').val(category_type);
        $('#edit_group_name').val(category_name);
        $('#category_description').val(description);
        $("#edit_category_detils").modal('show');
    });
     $(document).on('submit', '#edit_category_form', function(e){
        $('#msg_response').html(" ");
		$('#edit_category_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
        e.preventDefault();
        $.ajax({
            url: base_url+"/products_ajax/edit_category_details",
            type: "POST",        
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData:false,  
            success: function(data){
            $('#edit_category_btn').html('Save &nbsp;<i class="icon-paperplane ml-2"></i>').attr('disabled' , false);
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
            $('#edit_category_btn').html('Save &nbsp;<i class="icon-paperplane ml-2"></i>').attr('disabled' , false);
                $("#msg_response_popup").modal('show');
                $('#msg_response').html('<div class="notice notice-danger"><strong>Wrong , </strong>Something Went Wrong please try again  .</div>');
            }	
                
        });
    });
    $(document).on('click', '.edit_sub_group_details', function(e){
        e.preventDefault();
        $this = $(this);
        var sub_category_id = $(this).data('groupid');
        var category_type = $(this).data('categorytype');
        var category_name = $(this).data('categoryname');
        var description = $(this).data('description');
        $('#category_id').val(sub_category_id);
        $('#category_type').val(category_type);
        $('#edit_group_name').val(category_name);
        $('#category_description').val(description);
        $("#view_sub_category").modal('hide');
        $("#edit_category_detils").modal('show');
    });
} );
</script>
 <script src="{{ url('validateJS/products.js') }}"></script>
  <script src="{{ url('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
  <script src="{{ url('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>
@endpush

