@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
@if(session::has('msg'))
  {!! Session::get('msg') !!}
@endif
<style> .container{ padding:15px;} </style>
<div class="content">
    <div class="card">
        <div class="card-header bg-light header-elements-inline">
            <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;Service List</h6>
            <a href='#' class="btn btn-primary" id="add_car_revision" style="color:white; float:right;" >Add new Service&nbsp;<span class="glyphicon glyphicon-plus"></span></a>
        </div>
        <table class="table datatable-show-all">
            <thead>
                <tr>
                    <th>@lang('messages.SN')</th>
                    <th>@lang('messages.CategoryImage')</th>
                    <th>@lang('messages.Services')</th>
                    <th>@lang('messages.Description')</th>
                    <th class="text-center">@lang('messages.Actions')</th>
                </tr>
            </thead>
            <!--<tbody>
                @forelse($category_list as $category)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if(!empty($category->category_name)){{ $category->category_name }} @endif
                    </td>
                    <td>&nbsp;@if(!empty($category->time)){{ $category->time }} @else {{ "N/A" }} @endif</td>
                    <td class="text-center"> 
                        <a href="#" class="btn btn-primary edit_car_rveision"  data-toggle="tooltip" data-categoryid="<?php echo $category->id ; ?>" data-placement="top" title="Edit"  ><i class="fa fa-edit"></i></a>
                        <a href='{{  url("master/delete_cat/$category->id") }}' data-toggle="tooltip" data-placement="top" title="Remove Category" href='#' class="btn btn-danger"><i class="fa fa-trash" ></i></a>
                    </td>
                </tr>
                @empty
                <tr>
                        <td colspan="5">@lang('messages.NoRecordFound')</td>
                    </tr>  
                @endforelse
            </tbody>-->
            <tbody>
                @forelse($category_list as $category)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                    @if(!empty($category->cat_images))
                        <img src="{{ $category->cat_image_url }}" class="img-thumbnail" style="width:125px;height:70px" />
                    @else
                        <img src="{{url('storage/products_image/no_image.jpg')}}" class="img-thumbnail" style="width:125px;height:70px"/>
                    @endif
                    </td>
                    <td>
                        @if(!empty($category->category_name)){{ $category->category_name }} @endif
                    </td>
                    <td>&nbsp;@if(!empty($category->description)){{ $category->description }} @else {{ "N/A" }} @endif</td>
                    <td class="text-center"> 
                        <a href="#" class="btn btn-primary btn-sm edit_car_rveision"  data-toggle="tooltip" data-categoryid="<?php echo $category->id ; ?>" data-placement="top" title="Edit"  ><i class="fa fa-edit"></i></a>&nbsp;

                        <a href='#' data-toggle="tooltip" data-placement="top" title="Upload Multiple Images"  class="btn btn-primary btn-sm upload_car_revision_image" data-categoryid="<?php echo $category->id ; ?>"><i class="fa fa-picture-o" ></i></a>&nbsp;
                        
                        <a href='{{  url("master/delete_cat/$category->id") }}' data-toggle="tooltip" data-placement="top" title="Remove Service" class="btn btn-danger btn-sm" ><i class="fa fa-trash" ></i></a>
                    </td>
                </tr>
                @empty
                <tr>
                        <td colspan="5">@lang('messages.NoRecordFound')</td>
                    </tr>  
                @endforelse
            </tbody>
        </table>
        <div class="row" style="margin-top:20px;">
          <div class="col-sm-12">
            @if($category_list->count() > 0)
             {{ $category_list->links() }}
            @endif 
          </div>
        </div>
    </div>
    <!-- /page length options -->
</div>
<!--Add category popup modal-->
<div class="modal" id="add_car_revision_services">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Add New Service</h4>
                <hr />
            </div>
            <!-- Modal body -->
            <form id="add_car_revision_service_form" >
                <input type="hidden" value="" name="category_id" id="edit_category_id" />
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label>Service Name&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="@lang('messages.ServiceName')" name="category_name" id="category_name" required="required"  />
                            <span id="start_date_err"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label>@lang('messages.Description')&nbsp;<span class="text-danger">*</span></label>
                            <textarea name="description" id="description" class="form-control" placeholder="@lang('messages.Description')"></textarea>
                            <span id="start_date_err"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                        <label>@lang('messages.BrowseImage')&nbsp;</label>
                        <input type="file"  name="cat_file_name[]" id="cat_file_name"class="form-control" multiple="multiple"/>
                        <span id="start_date_err"></span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="form-check form-check-inline">
                            <button type="submit" id="car_revision" class="btn bg-blue ml-3" >@lang('messages.Submit') <i class="icon-paperplane ml-2"></i></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">       
        </div>
    </div>
</div>
<!--End-->@include('admin.component.category_common')
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="{{ url('/') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>@lang('messages.Home')</a>
            <a href="{{ url('/') }}" class="breadcrumb-item">@lang('messages.Admin')</a>
            <span class="breadcrumb-item active">Car Revision  </span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop
@push('scripts')
<link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" />
<script src="{{ url('validateJS/products.js') }}"></script>
<script src="{{ url('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ url('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>
<script>
    function show_car_revision_image(cat_id){
        if(cat_id != ""){
            $("#category_id").val(cat_id);
            $.ajax({
                url: base_url+"/car_revision/get_car_revision_image",
                method: "GET",
                data: {category_id:cat_id},
                success: function(data){
                    $('#image_result').html(data);
                    $('#add_car_wash_image_popup').modal('show');
                }
            });
        }   
    }
    $(document).ready(function(e){
        $('[data-toggle="tooltip"]').tooltip(); 

        /*Add Workshop Car Revision Category popup open*/
        $(document).on('click', '#add_car_revision', function (e) {
            $("#category_name").val("");
            $("#price").val("");
            $("#myModalLabel").html('Add service');
            $("#add_car_revision_service_form")[0].reset();
            $("#add_car_revision_services").modal('show');
        });
        /*End */
        /*Add Workshop Car Revision Category Script Start*/
        $(document).on('submit', '#add_car_revision_service_form', function (e) {
            $('#car_revision').html('Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
            e.preventDefault();
            $.ajax({
                url: base_url+"/car_revision/add_car_revision_category",
                type: "POST",   
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData:false,    
                success: function(data){
                    $('#car_revision').html(' Submit &nbsp;<i class="icon-paperplane ml-2"></i> ').attr('disabled' , false);
                    var parseJson = jQuery.parseJSON(data);
                    var errorString = '';
                    if(parseJson.status == 400){
                            $.each(parseJson.error, function(key , value) {
                                errorString += '<div class="notice notice-danger"><strong>Note , </strong>'+ value+' .</div>';
                            });
                        $("#msg_response_popup").modal('show');
                        $("#msg_response").html(errorString);   
                    }
                    if(parseJson.status == 200){
                        $("#add_car_revision_service_form")[0].reset();
                        $("#add_car_revision_services").modal('hide');
                        $("#msg_response_popup").modal('show');
                        $("#msg_response").html(parseJson.msg);
                        setTimeout(function(){ location.reload(); } , 1000);
                    }
                    if(parseJson.status == 100) {
                        $("#add_car_revision_service_form")[0].reset();
                        $("#add_car_revision_services").modal('hide');
                        $("#msg_response_popup").modal('show');
                        $("#msg_response").html(parseJson.msg);
                    }   
                } , 
                error: function(xhr, error){
                    $('#car_revision').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
                    $("#response_msg").html(parseJson.msg);
                }
            });
        });
        /*End */
         /*Edit Car Revision Category Script Start */
        $(document).on('click', '.edit_car_rveision', function (e) {
            e.preventDefault();
            $("#category_name").val(" ");
            $("#price").val(" ");
            var $this = $(this);
            var category_id = $(this).data('categoryid'); 
            if (category_id != "") {
                $.ajax({
                    url: base_url + "/car_revision/get_category_detail",
                    type: "GET",
                    data: {category_id: category_id},
                    success: function (data) {
                        var parseJson = jQuery.parseJSON(data);
                        if (parseJson.status == 200) {
                            $("#edit_category_id").val(parseJson.response.id);
                            $("#category_name").val(parseJson.response.category_name);
                            $("#description").val(parseJson.response.description);
                            $('#priority').find("option[value='"+ parseJson.response.priority +"']").attr('selected','selected');
                            $("#myModalLabel").html('Edit Service');
                            $("#add_car_revision_services").modal('show');
                        }
                        
                    }
                });
            }
            
        });
        /*End */
        /*Upload Multiple Images */
        $(document).on('click', '.upload_car_revision_image', function(e){
            e.preventDefault();
            var cat_id = $(this).data('categoryid');
            show_car_revision_image(cat_id)
        });
        /*End */
         /*Delete Selected Car Revision Images */
        $(document).on('click','.remove_car_revision_images',function(e){
            e.preventDefault();
            var con = confirm("Are you sure want to delete this image");
            if(con == true){
                var delete_id = $(this).data('imageid');
                var category_id = $("#category_id").val();
                $.ajax({
                    url: base_url+"/car_revision/remove_car_revision_image",
                    type: "GET",        
                    data:{delete_id:delete_id , category_id:category_id},
                    success: function(data){
                        show_car_revision_image(category_id);
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
            var category_id = $("#category_id").val();
            e.preventDefault();
            $.ajax({
                url: base_url+"/car_revision/upload_car_revision_image",
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
    });
</script>
@endpush

