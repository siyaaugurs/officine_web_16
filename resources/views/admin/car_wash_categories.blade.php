@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
    <!-- Page length options -->
    @if(Session::has('msg'))
      {!! session::get('msg') !!}
    @endif
<div class="row" style="margin-bottom:10px;">
  <div class="col-sm-12">
    <a href='#' id="show_car_wash_modal" class="btn btn-primary" style="color:white;">Add New Category &nbsp;<span class="glyphicon glyphicon-plus"></span></a>
  </div>
</div>
<div class="card">
        <div class="card-header bg-light header-elements-inline">
            <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;@lang('messages.CategoryList')</h6>
        </div>
        <table class="table datatable-show-all">
            <thead>
                <tr>
                    <th>@lang('messages.SN')</th>
                    <th>@lang('messages.Image')</th>
                    <th>@lang('messages.Category')</th>
                    <th>@lang('messages.Description')</th>
                    <th class="text-center">@lang('messages.Actions')</th>
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
                      <img src="{{url(storage/products_image/no_image.jpg)}}" style="height:50px;" />
                    @endif</td>
                    <td>@if(!empty($cat->category_name)){{ $cat->category_name }} @endif</td>
                      <td>@if(!empty($cat->description)){{ $cat->description }} @endif</td>
                    <td class="text-center"> 
                      <a  data-toggle="tooltip" data-catid="<?php echo $cat->id; ?>" data-placement="top" title="Edit" href='' class="btn btn-primary edit_cat"><i class="fa fa-edit"></i></a>
                      
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
          {{ $category_list->links() }}
        @endif
    </div>
<!--Browse Multiple image -->
@include('admin.component.category_common')
<!--End-->    
<!--Edit Car washing Category script start -->
<div class="modal" id="edit_car_washing_category_popup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Edit Car Wash Category</h4>
                <hr />
            </div>
            <!-- Modal body -->
            <form id="edit_car_wash_cat">
               <input type="hidden" name="edit_category_id" id="edit_cat_id" />	
              <div class="modal-body">
          <div class="form-group">
                                       <label>@lang('messages.CategoryName')&nbsp;<span class="text-danger">*</span></label>
									   <input type="text" class="form-control" placeholder="@lang('messages.CategoryName')" name="category_name" id="category_name" value="{{ old('category_name') }}" required="required"  />
                                       <span id="start_date_err"></span>
								     </div>
          <div class="form-group">
                                       <label>@lang('messages.Description')&nbsp;<span class="text-danger">*</span></label>
									   <textarea class="form-control" name="description" id="description" placeholder="@lang('messages.decription')"></textarea>
								     </div>
			 <div class="form-group">
               <label></label>
			</div>					     
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
                   <th>Price <span class="text-danger">* Hourly rate .</span></th>
                   <th>	   <input type="text" class="form-control" placeholder="@lang('messages.price')" name="small_price" id="small_price" required="required"  /></th>
                   <th>	   <input type="text" id="average_price" class="form-control" placeholder="@lang('messages.price')" name="average_price" required="required"  /></th>
                   <th>	   <input type="text" class="form-control" placeholder="@lang('messages.price')" name="big_price" id="big_price" required="required"  /></th>
                 </tr>
                 <tr>
                   <th>Time <span class="text-danger">* In minutes .</span><span class="text-danger">* in minutes .</span></th>
                   <th>	   <input type="text" class="form-control" placeholder="@lang('messages.time')" name="small_time" id="small_time" required="required"  /></th>
                   <th>	   <input type="text" class="form-control" placeholder="@lang('messages.time')" name="average_time" id="average_time" required="required"  /></th>
                   <th>	   <input type="text" class="form-control" placeholder="@lang('messages.time')" name="big_time" id="big_time" required="required"  /></th>
                 </tr> 
               </tbody>
             </table>
          <span id="edit_err_response"></span>                           
          <div class="form-group">  
             <button type="submit" id="edit_car_wash_cat_btn" class="btn btn-success">@lang('messages.Save') &nbsp;<i class="icon-paperplane ml-2"></i></button>
            </div>
        </div>
             </form>
        </div>
        <div class="modal-footer">       
        </div>
    </div>
</div>
<!--End-->

<!--Add Car washing Category script start -->
<div class="modal" id="add_car_washing_category_popup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Add New Car Wash Category</h4>
                <hr />
            </div>
            <!-- Modal body -->
            <form id="add_car_wash_cat">	
              <div class="modal-body">
          <div class="form-group">
                                       <label>@lang('messages.CategoryName')&nbsp;<span class="text-danger">*</span></label>
									   <input type="text" class="form-control" placeholder="@lang('messages.CategoryName')" name="category_name" id="category_name" value="{{ old('category_name') }}" required="required"  />
                                       <span id="start_date_err"></span>
								     </div>
          <div class="form-group">
                                       <label>@lang('messages.Description')&nbsp;<span class="text-danger">*</span></label>
									   <textarea class="form-control" name="description" id="description" placeholder="@lang('messages.decription')"  ></textarea>
								     </div>
            
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
                   <th>Price  <span class="text-danger">* Hourly rate .</span></th>
                   <th>	   <input type="text" class="form-control" placeholder="@lang('messages.price')" name="small_price" required="required"  /></th>
                   <th>	   <input type="text" class="form-control" placeholder="@lang('messages.price')" name="average_price" required="required"  /></th>
                   <th>	   <input type="text" class="form-control" placeholder="@lang('messages.price')" name="big_price" required="required"  /></th>
                 </tr>
                 <tr>
                   <th>Time <span class="text-danger">* in minutes .</span></th>
                   <th>	   <input type="text" class="form-control" placeholder="@lang('messages.time')" name="small_time" required="required"  /></th>
                   <th>	   <input type="text" class="form-control" placeholder="@lang('messages.time')" name="average_time" required="required"  /></th>
                   <th>	   <input type="text" class="form-control" placeholder="@lang('messages.time')" name="big_time" required="required"  /></th>
                 </tr> 
               </tbody>
             </table>
          <div class="form-group">
                                       <label>@lang('messages.BrowseImage') <span class="text-danger">*</span></label>
									    <input type="hidden"  name="cat_file_name_copy" id="cat_file_name_copy" value=""/>
                                       <input type="file" class="form-control" placeholder="@lang('messages.CategoryName')" name="cat_file_name[]" id="cat_file_name" required="required" multiple="multiple" />
                                       <span id="start_date_err"></span>
								     </div>
								     
          <span id="err_response"></span>                           
          <div class="form-group">  
             <button type="submit" id="add_car_wash_cat_btn" class="btn btn-success">@lang('messages.Save') &nbsp;<i class="icon-paperplane ml-2"></i></button>
            </div>
        </div>
             </form>
        </div>
        <div class="modal-footer">       
        </div>
    </div>
</div>
<!--End-->
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
                    <div class="d-flex">
                        <div class="breadcrumb">
                            <a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>@lang('messages.Home') </a>
                            <a href="#" class="breadcrumb-item">@lang('messages.Admin') </a>
                            <span class="breadcrumb-item active"> @lang('messages.CategoryList') </span>
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


