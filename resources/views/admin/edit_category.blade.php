@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
 <div class="col-md-12">
      <div class="row">
      <div class="control-group" id="fields">
          <div class="controls">
              <div class="entry input-group col-xs-3">
                <span class="input-group-btn">
                 <button class="btn btn-success add_more_image" type="button">
                     Add More Images  <span class="glyphicon glyphicon-plus"></span>
                </button>
                </span>
              </div>
           
          </div>
          
        </div>
      </div>
    </div>
                         
<div class="card" style="margin-top:10px; display:none;" id="image_section" >          
	<div class="card-body">
		<div class="row">
         <form id="edit_category_image">
          <input type="hidden" name="edit_id" value="{{ $category_details->id ?? '' }}" id="category_id">
         <div class="control-group" id="fields">
          <label class="control-label" for="field1">
           Browse Multiple Image
          </label>
          <div class="controls">
              <div class="entry input-group col-xs-3">
                <input class="btn btn-primary" name="cat_file_name[]" type="file" multiple="multiple">
                <span class="input-group-btn">
                 &nbsp;&nbsp;
                 <button class="btn btn-success btn-add" type="submit" id="save_image">
                   Save
                   <span class="glyphicon glyphicon-plus"></span>
                </button>
                </span>
              </div>
          </div>
        </div>
         </form>
        </div>
        <div class="row" id="response_msg"></div>
        <div class="row" style="margin-top:10px;" id="image_grid_section">
            @forelse($images_arr as $images)
               <div class="col-sm-4 col-md-3 col-lg-3">
									<div class="card">
										<div class="card-img-actions m-1">
											<img class="card-img img-fluid" src="{{ $images->image_url}}" alt="" />
											<div class="card-img-actions-overlay card-img">
												<a href="{{ $images->image_url }}" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round" data-popup="lightbox" rel="group">
													<i class="icon-plus3"></i>
												</a>
                                                <a href='{{ url("vendor_ajax/delete_image/$images->id") }}' data-imageid="{{ $images->id }}" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2 remove_delete">
													<i class="icon-trash"></i>
												</a>
                                            </div>
										</div>
									</div>
								</div>
            @empty
             <div class="">
           
             </div>
           @endforelse
        </div>
	</div>
</div>
<div class="card" style="margin-top:10px;">          
			                <div class="card-body">
			                	<form action="{{ url('master/edit_category')}}" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="edit_id" value="{{ $category_details->id ?? '' }}">
                                 @if(Session::has('msg'))
                                 {!!  Session::get("msg") !!}
                                 @endif
                                 @if ($errors->any())
                                  <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
                                 @endif
                                    @csrf
									<div class="form-group">
										<label>Select Category Type</label>
										  <select class="form-control" name="category_type" id="category_type">
                                           <option value="1">Products type</option>
                                           <option value="2">Service type</option>
                                         </select>
                                        <span id="title_err"></span>
									</div>
                                    <div class="form-group">
										<label>Parent category </label>
										  <select class="form-control" name="parent_category" id="parent_category">
                                           <option value="0">Select Parent category</option>
                                            {!! $parent_category !!} 
                                         </select>
                                        <span id="title_err"></span>
									</div>
									<div class="row">
								     <div class="col-md-12 form-group">
                                       <label>Category Name <span class="text-danger">*</span></label>
									   <input type="text" class="form-control" placeholder="Category name" name="category_name" id="category_name" value="{{ $category_details->category_name ?? '' }}" required="required" />
                                       <span id="start_date_err"></span>
								     </div>
                                    </div>
                                    
                                    <div class="row">
								     <div class="col-md-12 form-group">
                                       <label>Description &nbsp;<span class="text-danger">*</span></label>
									   <textarea class="form-control" name="description" id="description" placeholder="decription">{{ $category_details->description ?? '' }}</textarea>
                                       <span id="start_date_err"></span>
								     </div>
                                    </div>
                                    @if(!empty($category_details->cat_images))
                                    <div class="row">
								     <div class="col-md-12 form-group">
                                       <img src='{{ url("storage/category/$category_details->cat_images") }}' style="height:100px;">
								     </div>
                                    </div>
                                    @endif
                                    <div class="d-flex justify-content-between align-items-center">
										<div class="form-check form-check-inline">
											<button type="submit" id="category_name" class="btn bg-blue ml-3">Submit <i class="icon-paperplane ml-2"></i></button>
										</div>
									</div>
								</form>
							</div>
		                </div>
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
							<a href="#" class="breadcrumb-item">Admin </a>
							<span class="breadcrumb-item active">Edit Category </span>
						</div>
						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>
				</div>
@stop
@push('scripts')
  <script src="{{ url('validateJS/admin.js') }}"></script>
  <script src="{{ url('validateJS/date/jquery-ui.css') }}"></script>
  <script src="{{ url('validateJS/date/jquery-ui.js') }}"></script>
  <script src="{{ url('validateJS/vendors.js') }}"></script>
@endpush

