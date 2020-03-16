@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
@if(Session::has('msg'))
    {!! session::get('msg') !!}
@endif
<style> .container{ padding:15px;} </style>
<div class="row" style="margin-bottom:10px;">
    <div class="col-sm-12">
        <a href="javascript::void();" class="btn btn-warning" id="import_export_product_brand" style="color:white;">Import / Export Brand Files&nbsp;<span class="fa fa-file-excel-o"></span></a>&nbsp;&nbsp;&nbsp;
    </div>
</div>
<div class="card">
    <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;Brand Logo List</h6>
        <a href='#' class="btn btn-success" id="add_custom_brand_logo" style="color:white; float:right;" >Add New Brand&nbsp;&nbsp;<span class="glyphicon glyphicon-plus"></span></a>
    </div>
	<div class="card-body" id="" style="overflow:auto;">
        <table class="table" id="brand_logo">
            <thead>
                <tr>
                    <th>@lang('messages.SN')</th>
                    <th>@lang('messages.Image')</th>
                    <th>Brand Type</th>
                    <th>Brand Name</th>
                    <th>@lang('messages.Actions')</th>
                </tr>
            </thead>
            <tbody>
                @forelse($brand_logo_details as $brand)
                    <tr>
                        <td>{{  $loop->iteration }}</td>
                        @if(!empty($brand->image_url))
                            <td> <img src="<?php echo $brand->image_url; ?>" class="img-thumbnail" style="max-width:200px;height:60px"> </td>
                        @else 
                            <td> <img src="<?php echo $image; ?>" class="img-thumbnail" style="max-width:200px;height:60px"> </td>
                        @endif
                        <td>
                        @if(array_key_exists($brand->brand_type , $brand_type_arr))
                           {{ $brand_type_arr[$brand->brand_type] }}
                        @else
                           "N/A"
                        @endif
                        </td>
                        <td>{{  $brand->brand_name }}</td>
                        <td>
                            <a href="#" data-toggle="tooltip" data-placement="top" title="Upload Brand Logo" class="btn btn-primary upload_brand_logo_image btn-sm" data-brandtype="{{ $brand->brand_type }}" data-brandname="<?php if(!empty($brand->brand_name)) echo $brand->brand_name; ?>" data-brandid="{{ $brand->id }}"><i class="fa fa-picture-o"></i></a>&nbsp;&nbsp;
                            <a href='#' data-toggle="tooltip" data-placement="top" title="Delete" class="btn btn-danger btn-sm delete_brand_logo" data-brandid="{{ $brand->id }}" ><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9">No Brand Logo Avilable</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="modal" id="add_brand_logo_image">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Upload Brand Logo</h4>
                <hr />
            </div>
            <div id="err_response"></div>
            <form id="upload_brand_logo_image_form">
                @csrf
                <input type="hidden" name="brand_id" id="brand_id" value="" readonly="readonly" />
                <div class="modal-body">
                     <div class="row">
                        <div class="col-md-12 form-group">
                            <label>Brand Type&nbsp;<span class="text-danger">*</span></label>
                            <select type="text"  class="form-control" placeholder="Brand Type" name="brand_type" id="brand_logo_type" required="required">
                               <option value="0">Select Brands Name</option> 
                               @if(count($brand_type_arr) > 0)
                                @foreach($brand_type_arr as $key=>$value)
                                   <option value="{{ $key }}">{{ $value }}</option> 
                                @endforeach
                               @else
                                 <option value="0">No Type available !!!</option> 
                               @endif
                            </select>    
                            <span id="start_date_err"></span>
                        </div>
                    </div>
                     <div class="row">
                        <div class="col-md-12 form-group">
                            <label>Brand Name&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="Brand Name" name="brand_name" id="brand_name" required="required"  />
                            <span id="start_date_err"></span>
                        </div>
                    </div>
                     
                    <div class="control-group" id="fields">Browse Image</label>
                        <div class="controls">
                            <div class="entry input-group col-xs-3">
                                <input class="btn btn-primary" name="images" type="file" accept=".jpg,.png," require>
                                <span class="input-group-btn">
                                &nbsp;&nbsp;
                               
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top:15px;">
                        <div class="col-md-12 form-group">
                             <button class="btn btn-success btn-add" type="submit" id="save_brand_logo_btn">
                                Save
                                <span class="glyphicon glyphicon-plus"></span>
                                </button>
                        </div>
                    </div>
                </div>
            </form>
            <div id="n3_image_response"></div>
        </div>
        <div class="modal-footer">       
        </div>
    </div>
</div>
<div class="modal" id="add_custom_brand_popup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Add New Brand</h4>
                <hr />
            </div>
            <form id="add_new_brand_form" >
                <input type="hidden" value="" name="category_id" id="edit_category_id" />
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label>Brand Type&nbsp;<span class="text-danger">*</span></label>
                            <select type="text"  class="form-control" placeholder="Brand Type" name="brand_type" id="brand_type" required="required">
                                <option value="0">Select Brands Name</option> 
                                @if(count($brand_type_arr) > 0)
                                    @foreach($brand_type_arr as $key=>$value)
                                        <option value="{{ $key }}">{{ $value }}</option> 
                                    @endforeach
                                @else
                                    <option value="0">No Type available !!!</option> 
                                @endif
                            </select>    
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label>Brand Name&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="Brand Name" name="brand_name" id="brand_name" required="required"  />
                            <span id="start_date_err"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                        <label>Add Brand Logo&nbsp;</label>
                        <input type="file"  name="images" id="brand_logo_image" placeholder="Brand Logo" class="form-control" accept=".jpg,.png,"/>
                        <span id="start_date_err"></span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="form-check form-check-inline">
                            <button type="submit" id="custom_brand_btn" class="btn btn-success ml-3" >@lang('messages.Submit') <i class="icon-paperplane ml-2"></i></button>
                        </div>
                    </div>
				</div>
			</form>
        </div>
        <div class="modal-footer">       
        </div>
    </div>
</div>
<div class="modal" id="import_export_brand_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Import / Export Brands List</h4>
                <hr />
            </div>
            <div class="card-body">
                <a href="<?php echo url('export/product_brand_list_export') ?>" class="btn btn-warning"><i class="fa fa-download"></i>&nbsp;Export Brands List</a>
                <hr />
                <h3 style="font-weight:600;">Import Brands Excel Files </h3> 
                <form id="import_brand_product_file">
                @csrf
                  <div class="control-group" id="fields">
                        <span id="rim_msg_response"></span>
                        <label class="control-label" for="field1">
                            Browse  Brands Excel Files
                        </label>
                        <div class="controls">
                            <div class="entry input-group col-xs-3">
                                <input class="btn btn-primary" name="brand_product_file" type="file"  required>
                                <span class="input-group-btn">
                        &nbsp;&nbsp;
                        <button class="btn btn-success btn-add" type="submit" id="btand_btn">
                            Import Brand File
                            <span class="glyphicon glyphicon-import"></span>
                        </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
                <br>
                 <div class="row" style="margin-top:15px;">
                  <div class="col-sm-12">
                    <p style="color:#F00; font-weight:600; font-size:18px;">Type = 1 (For Spare parts brands )</p>
                    <p style="color:#F00; font-weight:600; font-size:18px;">Type = 2 (For Tyre brands)</p>
                    <p style="color:#F00; font-weight:600; font-size:18px;">Type = 3 (For Rim brands)</p>
                 </div>
               </div>
            </div>
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
            <span class="breadcrumb-item active"> Brand Logo </span>
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
<script src="{{ asset('validateJS/import_export.js') }}"></script>
<script>
$(document).ready(function() {
    $('#brand_logo').DataTable( {
    } );
} );
</script>
<script>
    $(document).ready(function(e) {
        $(document).on('click', '.delete_brand_logo', function(e){
		    e.preventDefault();
            var brand_id = $(this).data('brandid');
            var con = confirm("Are you sure want to delete?");
            var url = base_url+"/products/remove_brand_logo/"+brand_id;
            if(con == true) {
                window.location.href = url;
            } else {
                return false;
            }
        });

        $(document).on('click', '.upload_brand_logo_image', function(e){
            e.preventDefault();
            var brand_id = $(this).data('brandid');
            var brand_name = $(this).data('brandname');
            var brand_type = $(this).data('brandtype');
            
            if(brand_id != ""){
                $("#brand_id").val(brand_id);
                $("#upload_brand_logo_image_form #brand_name").val(brand_name);
                $('#brand_logo_type').find("option[value='"+ brand_type +"']").attr('selected','selected');
                
                $('#add_brand_logo_image').modal({
                    backdrop:'static',
                    keyboard:false,
                });
            }
        });

        $(document).on('click', '#add_custom_brand_logo', function(e){
		    e.preventDefault();
            $('#add_custom_brand_popup').modal({
                backdrop:'static',
                keyboard:false,
            });
        });

        $(document).on('submit','#upload_brand_logo_image_form',function(e){
            $('#msg_response').html(" ");
            $("#err_response").html(" ");
           //$('#save_brand_logo_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
            $('#err_response').html(""); 
            e.preventDefault();
            $.ajax({
                url: base_url+"/products_ajax/upload_brand_logo",
                type: "POST",        
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData:false,  
                success: function(data){
                    var errorString = '';
                    var parseJson = jQuery.parseJSON(data);
                    $('#save_brand_logo_btn').html(' Save &nbsp; <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
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
                        setTimeout(function(){ location.reload(); } , 1000);
                    }
                    if(parseJson.status == 100){
                        $("#err_response").html(parseJson.msg);
                    }
                }	
            });
        });

        $(document).on('submit','#add_new_brand_form',function(e){
            $('#msg_response').html(" ");
            $("#err_response").html(" ");
            // $('#custom_brand_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
            $('#err_response').html(""); 
            e.preventDefault();
            $.ajax({
                url: base_url+"/products_ajax/add_new_brand",
                type: "POST",        
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData:false,  
                success: function(data){
                    var errorString = '';
                    var parseJson = jQuery.parseJSON(data);
                    // $('#custom_brand_btn').html(' Save &nbsp; <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
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
                        setTimeout(function(){ location.reload(); } , 1000);
                    }
                    if(parseJson.status == 100){
                        $("#err_response").html(parseJson.msg);
                    }
                }	
            });
        });
    });
</script>
@endpush

