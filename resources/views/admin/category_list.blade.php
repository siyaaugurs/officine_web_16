@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="content">
    <!-- Page length options -->
    @if(Session::has('msg'))
      {!! session::get('msg') !!}
    @endif
    <div class="card">
        <div class="card-header bg-light header-elements-inline">
            <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;@lang('messages.ServiceList')</h6>
             <a href='#' class="btn btn-primary popup_btn" id="add_services" style="color:white; float:right;" data-modalname="add_category_popup">Add New Services&nbsp;<span class="glyphicon glyphicon-plus"></span></a>
        </div>
        <table class="table datatable-show-all">
            <thead>
                <tr>
                    <th>SN.</th>
                    <th>Image</th>
                    <th>Services</th>
                    <th>Description</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($category_list as $cat)
               <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                    @if(!empty($cat->cat_image_url))
                     <img src="{{ $cat->cat_image_url }}" style="height:50px;" />
                    @else
                      <img src="{{url('storage/products_image/no_image.jpg')}}" style="height:50px;" />
                    @endif</td>
                    <td>@if(!empty($cat->category_name)){{ $cat->category_name }} @endif</td>
                      <td>@if(!empty($cat->description)){{ $cat->description }} @endif</td>
                    <td class="text-center"> 
                      <a  data-toggle="tooltip" data-catid="<?php echo $cat->id; ?>" data-placement="top" title="Edit" href='' class="btn btn-primary edit_category_btn popup_btn" data-modalname="edit_category_popup"><i class="fa fa-edit"></i></a>
                      
                      <a href="#" data-toggle="tooltip" data-placement="top" title="Add Images" data-catid="<?php echo $cat->id; ?>" class="btn btn-primary add_car_wash_image_btn btn-sm" ><i class="fa fa-picture-o"></i></a>
                     
                      <a  data-toggle="tooltip" data-placement="top" title="Remove Service" href='{{ url("master/delete_cat/$cat->id") }}' class="btn btn-danger delete_cat"><i class="fa fa-trash" ></i></a></td>
                </tr>
               @empty
               <tr>
                    <td colspan="5">@lang('messages.NoRecordFound')</td>
                </tr>  
               @endforelse
            </tbody>
        </table>
        @if($category_list->count() > 0)
        {{  $category_list->links() }}
        @endif
    </div>
    <!-- /page length options -->
</div>
<!--Edit Category script start-->
<div class="modal" id="edit_category_popup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Edit category</h4>
                <hr />
            </div>
            <!-- Modal body -->
            <form id="edit_category_form" >
               <input type="hidden" name="edit_id" id="edit_id" />
                <div class="modal-body">
                                    @csrf
									<div class="form-group">
										<label>@lang('messages.ParentCategory')&nbsp;<span class="text-danger">*</span></label>
										  <select class="form-control" name="parent_category" id="edit_parent_category">
                                           @forelse($parent_category as $cat)
                                             @if($cat->id == 1)
                                             <option value="{{ $cat->id }}" selected="selected">{{ $cat->main_cat_name }}</option>
                                             @endif
                                           @empty
                                             <option>@lang('messages.NoRecordFound')</option>
                                           @endforelse
                                         </select>
									</div>
									<div class="row">
								     <div class="col-md-12 form-group">
                                       <label>@lang('messages.CategoryName')&nbsp;<span class="text-danger">*</span></label>
									   <input type="text" class="form-control" placeholder="@lang('messages.CategoryName')" name="category_name" id="edit_category_name" value="{{ old('category_name') }}" required="required"  />
								     </div>
                                    </div>
                                    <div class="row form-group">
                            <div class="col-sm-12">
                                <table class="table table-bordered" style="margin-bottom:15px;">
                                    <thead>
                                        <tr>
                                            <th>Cars</th>
                                            <th>Small</th>
                                            <th>Average</th>
                                            <th>Big</th>
                                        </tr> 
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>Time&nbsp;<span class="text-danger">* In hour</span></th>
                                            <th><input type="text" class="form-control calculate_time" onkeyup="check_correct_data(1 , this.value)" placeholder="@lang('messages.time')" name="small_time" id="edit_small_time" required="required"   /></th>
                                            <th><input type="text" class="form-control calculate_time" onkeyup="check_correct_data(2 , this.value)" placeholder="@lang('messages.time')" name="average_time" id="edit_average_time" required="required" /></th>
                                            <th><input type="text" class="form-control calculate_time" onkeyup="check_correct_data(3 , this.value)" placeholder="@lang('messages.time')" name="big_time" onkeyup="check" id="edit_big_time" required="required" /></th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                                    <div class="row">
								     <div class="col-md-12 form-group">
                                       <label>@lang('messages.Description')&nbsp;<span class="text-danger">*</span></label>
									   <textarea class="form-control" name="description" id="edit_description" placeholder="@lang('messages.Description')"></textarea>
								     </div>
                                    </div>
                                    <span id="edit_response"></span>
                                    <div class="d-flex justify-content-between align-items-center">
										<div class="form-check form-check-inline">
											<button type="submit" id="edit_category_submit" class="btn bg-blue ml-3">@lang('messages.Submit') <i class="icon-paperplane ml-2"></i></button>
										</div>
									</div>
				</div>
			</form>
        </div>
        <div class="modal-footer">       
        </div>
    </div>
</div>
<!--End-->
<!--Add category popup modal-->
<div class="modal" id="add_category_popup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Add New Service</h4>
                <hr />
            </div>
            <!-- Modal body -->
            <form id="add_carwash_category_form" >
                <div class="modal-body">
                                    @csrf
									<div class="form-group">
										<label>@lang('messages.ParentCategory')&nbsp;<span class="text-danger">*</span> </label>
										  <select class="form-control" name="parent_category" id="parent_category">
                                           @forelse($parent_category as $cat)
                                              @if($cat->id == 1)
                                             <option value="{{ $cat->id }}" selected="selected">{{ $cat->main_cat_name }}</option>
                                             @endif
                                           @empty
                                             <option>@lang('messages.NoRecordFound')</option>
                                           @endforelse
                                         </select>
                                        <span id="title_err"></span>
									</div>
									<div class="row">
								     <div class="col-md-12 form-group">
                                       <label>@lang('messages.CategoryName')&nbsp;<span class="text-danger">*</span></label>
									   <input type="text" class="form-control" placeholder="@lang('messages.CategoryName')" name="category_name" id="category_name" value="{{ old('category_name') }}" required="required"  />
                                       <span id="start_date_err"></span>
								     </div>
                                    </div>
                                    <div class="row form-group">
                            <div class="col-sm-12">
                                <table class="table table-bordered" style="margin-bottom:15px;">
                                    <thead>
                                        <tr>
                                            <th>Cars</th>
                                            <th>Small</th>
                                            <th>Average</th>
                                            <th>Big</th>
                                        </tr> 
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>Time&nbsp;<span class="text-danger">* In hour</span></th>
                                            <th><input type="text" class="form-control calculate_time" onkeyup="check_correct_data(1 , this.value)" placeholder="@lang('messages.time')" name="small_time" id="small_time" required="required"   /></th>
                                            <th><input type="text" class="form-control calculate_time" onkeyup="check_correct_data(2 , this.value)" placeholder="@lang('messages.time')" name="average_time" id="average_time" required="required" /></th>
                                            <th><input type="text" class="form-control calculate_time" onkeyup="check_correct_data(3 , this.value)" placeholder="@lang('messages.time')" name="big_time" onkeyup="check" id="big_time" required="required" /></th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                                    <div class="row">
								     <div class="col-md-12 form-group">
                                       <label>@lang('messages.Description')&nbsp;<span class="text-danger">*</span></label>
									   <textarea class="form-control" name="description" id="description" placeholder="@lang('messages.Description')"></textarea>
                                       <span id="start_date_err"></span>
								     </div>
                                    </div>
                                    <div class="row">
								     <div class="col-md-12 form-group">
                                       <label>@lang('messages.BrowseImage')&nbsp;<span class="text-danger">*</span></label>
									    <input type="hidden"  name="cat_file_name_copy" id="cat_file_name_copy" value=""/>
                                       <input type="file" class="form-control" placeholder="@lang('messages.CategoryName')" name="cat_file_name[]" id="cat_file_name" required="required" multiple="multiple" />
								     </div>
                                    </div>
                                     <span id="add_response"></span>
                                    <span id="err_response"></span>
                                    <div class="d-flex justify-content-between align-items-center">
										<div class="form-check form-check-inline">
											<button type="submit" id="category_submit" class="btn bg-blue ml-3">@lang('messages.Submit') <i class="icon-paperplane ml-2"></i></button>
										</div>
									</div>
				</div>
			</form>
        </div>
        <div class="modal-footer">       
        </div>
    </div>
</div>
<!--End-->
@include('admin.component.category_common')
<script>
$(document).ready(function(e) {
  /*Edit Category form script start */
  $(document).on('submit','#edit_category_form',function(e){
     $('#msg_response').html(" ");
     $("#edit_response").html(" ");
     $('#edit_category_submit').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
		e.preventDefault();
			 $.ajax({
					 url: base_url+"/master/edit_new_category",
					 type: "POST",        
					 data: new FormData(this),
					 contentType: false,
					 cache: false,
					 processData:false,  
					 success: function(data){
						$('#edit_category_submit').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
						var parseJson = jQuery.parseJSON(data); 
						if(parseJson.status == 200){
						  $(".close").click();	
						   $("#edit_category_form")[0].reset();	
						   $("#msg_response_popup").modal('show');
               $("#msg_response").html(parseJson.msg);
               setTimeout(function(){ location.reload(); } , 1000);
						  }
						if(parseJson.status == 400){
							  $.each(parseJson.error, function(key , value) {
								errorString += '<div class="notice notice-success"><strong>Success , </strong>'+ value+' .</div>';
							  });
							$('#edit_response').html(errorString); 	
						 }  
						if(parseJson.status == 100){
							$("#edit_response").html(parseJson.msg);
						  }	 
					 } , 
					 error: function(xhr, error){
                        $('#category_submit').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
					   $("#response_msg").html(parseJson.msg);
          }
			});
  });  
  /*End*/	
  /*Get category data script start*/
    $(document).on('click','.edit_category_btn' , function(e){
	   cat_id = $(this).data('catid');
	   e.preventDefault();
	    if(cat_id != "undefined" || cat_id != ""){
	    $("#category_id").val(cat_id);
			$.ajax({
			url: base_url+"/master_agax/category_details",
			method: "GET",
			data: {category_id:cat_id},
			success: function(data){
			   var parseJson = jQuery.parseJSON(data); 
			   if(parseJson.status == 200){
				   $("#edit_id").val(parseJson.response.id);
				   $('#edit_parent_category').find('option[value="'+ parseJson.response.category_type +'"]').attr('selected','selected');
				   $("#edit_category_name").val(parseJson.response.category_name);
				   $("#edit_description").html(parseJson.response.description);
				   $("#edit_category_popup").show();
				   $("#edit_average_time").val(parseJson.response.average_time);
				   $("#edit_small_time").val(parseJson.response.small_time);
				   $("#edit_big_time").val(parseJson.response.big_time);
				   //console.log(parseJson.response);
				 }
			   if(parseJson.status == 100){
				    $("#msg_response_popup").modal('show');
					$("#msg_response").html(parseJson.msg);
				 }	 
			 
			 
			  //$('#image_result').html(data);
			  //$('#add_car_wash_image_popup').modal('show');
			}
	     });
	   }
	});
  /*End*/ 	
  $(document).on('submit','#add_carwash_category_form',function(e){
    $('#msg_response').html(" ");
    $("err_response").html(" ");
     $('#category_submit').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
		e.preventDefault();
			 $.ajax({
					 url: base_url+"/master/add_car_wash_category",
					 type: "POST",        
					 data: new FormData(this),
					 contentType: false,
					 cache: false,
					 processData:false,  
					 success: function(data){
						//console.log(data);
						$('#category_submit').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
						var parseJson = jQuery.parseJSON(data); 
						if(parseJson.status == 200){
						  $(".close").click();	
						   $("#add_carwash_category_form")[0].reset();	
						   $("#msg_response_popup").modal('show');
               $("#msg_response").html(parseJson.msg);
               setTimeout(function(){ location.reload() } ,1000);
						  }
						if(parseJson.status == 400){
							  $.each(parseJson.error, function(key , value) {
								errorString += '<div class="notice notice-danger"><strong>Wrong , </strong>'+ value+' .</div>';
							  });
							$('#add_response').html(errorString); 	
						 }  
						if(parseJson.status == 100){
							$("#add_response").html(parseJson.msg);
							
						  }	 
					 } , 
					 error: function(xhr, error){
                        $('#category_submit').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
					    $("#msg_response_popup").modal('show');
						$("#msg_response").html("<div class='notice notice-success'><strong>Wrong , </strong> Something Went wrong , please try again .</div>");
          }
			});
  });  
});
</script>
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
                    <div class="d-flex">
                        <div class="breadcrumb">
                            <a href="{{ url('/') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>@lang('messages.Home')</a>
            <a href="{{ url('/') }}" class="breadcrumb-item">@lang('messages.Admin')</a>
                            <span class="breadcrumb-item active"> Service List </span>
                        </div>
                        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
                    </div>
                    <!--<div class="header-elements d-none">
                        <div class="breadcrumb justify-content-center">
                            <a href="#" class="breadcrumb-elements-item">
                                <i class="icon-comment-discussion mr-2"></i>
                                Support
                            </a>
                        </div>
                    </div>-->
                </div>
@stop
@push('scripts')

<script src="{{ url('validateJS/admin.js') }}"></script>
@endpush


