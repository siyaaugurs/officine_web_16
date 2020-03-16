@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<style>
.form-pfu {
    margin-bottom: 1.25rem;
}
</style>
<div class="content">
    <!-- Page length options -->
    @if(Session::has('msg'))
      {!! session::get('msg') !!}
    @endif
	<div id="success_message" class="ajax_response" style="float:top"></div>
    <div class="card"  style="overflow:auto">
         <div class="card-header bg-light header-elements-inline">
            <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;Manage Advertising</h6>
            <a href='#' class="btn btn-primary" id="add_advertising" style="color:white; float:right;" >Add Manage Advertising &nbsp;<span class="glyphicon glyphicon-plus"></span></a>
        </div>

        <table class="table datatable-show-all dataTable no-footer">
            <thead>
                <tr>
                    <th>SN.</th>
					<th>Title</th>
					<th>Location of the Ads</th>
                    <th>Main Category</th>
					<th>Status</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
			
            <tbody>
			@forelse($manage_advertising_list as $advertising_list )
				<tr>
						<td>{{ $loop->iteration }}</td>
						<td>{{$advertising_list->title}}</td>
						<td>{{$advertising_list->add_location}}</td>
                        <td>{{$advertising_list->main_cat_name}}</td>
						<td>@if($advertising_list->status == 1)
							<a href="#" class="change_status" data-id="{{ $advertising_list->id }}" data-status="0" ><i class="fa fa-toggle-on"></i> </a>	
							@else
							  <a href="#" class="change_status" data-id="{{ $advertising_list->id }}" data-status="1"><i class="fa fa-toggle-off"></i>  </a> 
							@endif
						</td>
						<td>
                            <a href="#" class="btn btn-primary edit_advertising" data-id="{{ $advertising_list->id }}"> <i class="fa fa-edit"></i> </a>
							<a href="#" class="btn btn-danger delete_advertising" data-id="{{ $advertising_list->id }}"> <i class="fa fa-remove"></i> </a>
							<a href='#' data-toggle="tooltip" data-placement="top" title="Upload Multiple Images"  class="btn btn-primary btn-sm advertising_list" data-advertising="<?php echo $advertising_list->id ; ?>"><i class="fa fa-picture-o" ></i></a>&nbsp; 
                        </td>
                    </tr>
                @empty
                <tr>
                   <td colspan="6">@lang('messages.NoRecordFound')</td>
                </tr>
                @endforelse 
            </tbody>
        </table>
		<div class="row" style="margin-top:20px;">
            <div class="col-sm-12">
            </div>
        </div>
    </div>
    <!-- /page length options -->
</div>
<!--Add manage adver popup modal-->
<div class="modal" id="add_advertising_model">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel"></h4>
                <hr />
            </div>
            <!-- Modal body -->
            <form id="add_advertising_form">
                <input type="hidden" value="" name="id" id="id" />
                <div class="modal-body">
                    @csrf
                    <span id="add_response"></span>
                    <span id="err_response"></span>
					<div class="row">
                        <div class="col-md-12 form-pfu">
                            <label>Title&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="" name="title" id="title" required="required"  />
                            <span id="start_date_err"></span>
                        </div>
                    </div>
					<div class="row">
                        <div class="col-md-12 form-pfu">
                            <label>Description&nbsp;<span class="text-danger">*</span></label>
                            <textarea class="textarea1" rows="5" placeholder=""  id="description" name="description"> </textarea>
                             <span id="start_date_err"></span>
                        </div>
                    </div>
					<div class="row">
                        <div class="col-md-12 form-pfu">
                            <label>URL<!-- &nbsp;<span class="text-danger">*</span> --></label>
                            <input type="text" class="form-control" placeholder="" name="url" id="url"   />
                            <span id="start_date_err"></span>
                        </div>
                    </div>
					<div class="row">
                        <div class="col-md-12 form-pfu">
                            <label>Location of Ads&nbsp;<span class="text-danger">*</span></label>
							<select id="add_location" name="add_location" class="form-control" required="required">
                                <option value="All">All</option>
                                <option value="ABC">ABC</option>
                                <option value="CF">CF</option>
                                <option value="HOME">HOME</option>
							</select><span id="start_date_err"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-pfu">
                            <label>Add Service category&nbsp;<span class="text-danger">*</span></label>
							<select id="main_category_id" name="main_category_id" class="form-control" required="required">
                            @foreach($category_lists as $category_list)
                                <option value="{{$category_list->id}}">{{$category_list->main_cat_name}}</option>
                             @endforeach
							</select><span id="start_date_err"></span>
                        </div>
                    </div>
					<!-- <div class="row">
                        <div class="col-md-12 form-pfu">
                            <label>File Upload&nbsp;<span class="text-danger">*</span></label>
							<div class="col-md-12 form-pfu">
							<img class="card-img img-fluid" id="edit_image" src="" alt="" style="max-width: 13%;height: 49%"/>
							 </div>
                            <input type="file" class="form-control"  placeholder="" name="file" id="file" required="required"  />
                            <span id="start_date_err"></span>
                        </div>
                    </div> -->
                    <div class="d-flex justify-content-between align-items-center" style="margin: 10px;">
                        <div class="form-check form-check-inline">
                            <button type="submit" id="submit_advertising" class="btn bg-blue ml-3" >@lang('messages.Submit') <i class="icon-paperplane ml-2"></i></button>
                        </div>
                    </div>
				</div>
			</form>
        </div>
        <div class="modal-footer">       
        </div>
    </div>
    <script src="{{ url('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
    <script src="{{ url('/vendor/unisharp/laravel-ckeditor/adapters/jquery.js') }}"></script>
    <script>
        $('textarea1').ckeditor();
         $('.textarea1').ckeditor(); // if class is prefered.
		  
    </script>
</div>
<!--End-->
<div class="modal" id="add_car_wash_image_popup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Upload Images</h4>
                <hr />
            </div>
            <div id="err_response"></div>
            <!-- Modal body -->
            <form id="edit_category_image">
                @csrf
                <input type="hidden" name="advertising_id" id="advertising_id" value="" readonly="readonly" />
                <div class="modal-body">
                   <div class="control-group" id="fields">
          <label class="control-label" for="field1">
           Browse Multiple Image
          </label>
          <div class="controls">
              <div class="entry input-group col-xs-3">
                <input class="btn btn-primary" name="cat_file_name[]" type="file" multiple="multiple" accept=".jpg,.png," require>
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
<script>
  function show_car_revision_image(cat_id){
        if(cat_id != ""){
            $("#advertising_id").val(cat_id);
			//alert(cat_id)
            $.ajax({
                url: base_url+"/admin_ajax/get_advertising_image",
                method: "GET",
                data: {id:cat_id},
                success: function(data){
                    $('#image_result').html(data);
                    $('#add_car_wash_image_popup').modal('show');
                }
            });
        }	
    }
	
	   /*Upload Multiple Images */
        $(document).on('click', '.advertising_list', function(e){
            e.preventDefault();
            var cat_id = $(this).data('advertising');
            show_car_revision_image(cat_id)
        });
        /*End */
		
		 /*Delete Selected advertising Images */
        $(document).on('click','.remove_car_revision_images',function(e){
            e.preventDefault();
            var con = confirm("Are you sure want to delete this image");
            if(con == true){
                var delete_id = $(this).data('imageid');
                var advertising_id = $("#advertising_id").val();
                $.ajax({
                    url: base_url+"/admin_ajax/remove_advertising_image",
                    type: "GET",        
                    data:{delete_id:delete_id , advertising_id:advertising_id},
                    success: function(data){
                        show_car_revision_image(advertising_id);
                        var parseJson = jQuery.parseJSON(data);
                        if(parseJson.status == 100){
                            $("#msg_response_popup").modal('show');
                            $("#msg_response").html('<div class="notice notice-danger"><strong>Wrong </strong> Something went wrong , please try again !!! </div>');
                        }
                        else{
                            $('#image_grid_section').load(document.URL + ' #image_grid_section'); 
                        }  
                    }
                });
            }
        });
        /*End */
		
		   /*Submit multiple image form */
        $(document).on('submit','#edit_category_image',function(e){
            $('#response_msg').html(" ");
            $('#save_group_image').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
            var advertising_id = $("#advertising_id").val();
            e.preventDefault();
            $.ajax({
                url: base_url+"/admin_ajax/upload_advertising_image",
                type: "POST",        
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData:false,  
                success: function(data){
                    $('#save_group_image').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
                    var parseJson = jQuery.parseJSON(data); 
                    if(parseJson.status == 200){
                        $(".close").click();
                        $("#msg_response_popup").modal('show');
                        $("#msg_response").html(parseJson.msg);
                        $("#edit_category_image")[0].reset();
                    } else {
                        $("#response_msg").html(parseJson.msg);
                    }	 
                } , 
                error: function(xhr, error){
                    $('#save_group_image').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
                    $("#response_msg").html(parseJson.msg);
                }
            });
        });
        /*End */
</script>	
@endsection




@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="{{ url('/') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>@lang('messages.Home')</a>
            <a href="{{ url('/') }}" class="breadcrumb-item">@lang('messages.Admin')</a>
            <span class="breadcrumb-item active"> Notification </span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop


@push('scripts')
<script src="{{ url('validateJS/add_manage_advertising.js') }}"></script>
@endpush


