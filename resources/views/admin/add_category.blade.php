@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="card">      
    <div class="card-header bg-light header-elements-inline">
		<h6 class="card-title" style="font-weight:600;"><i class="icon-cart"></i>&nbsp;&nbsp;@lang('messages.AddCategory')</h6>
	</div>
			                <div class="card-body">
			                	<form action="{{ url('master/add_category')}}" method="post" enctype="multipart/form-data">
                                 @if(Session::has('msg'))
                                 {!!  Session::get("msg") !!}
                                @endif
                                    @csrf
									<div class="form-group">
										<label>@lang('messages.SelectCategoryType')</label>
										  <select class="form-control" name="category_type" id="category_type">
                                           <option value="1">@lang('messages.ProductsType')</option>
                                           <option value="2">@lang('messages.ServiceType')</option>
                                         </select>
                                        <span id="title_err"></span>
									</div>
                                    <div class="form-group">
										<label>@lang('messages.ParentCategory') </label>
										  <select class="form-control" name="parent_category" id="parent_category">
                                           <option value="0">@lang('messages.SelectParentCategory')</option>
                                            {!! $parent_category !!} 
                                         </select>
                                        <span id="title_err"></span>
									</div>
									<div class="row">
								     <div class="col-md-12 form-group">
                                       <label>@lang('messages.CategoryName')<span class="text-danger">*</span></label>
									   <input type="text" class="form-control" placeholder="@lang('messages.CategoryName')" name="category_name" id="category_name" value="{{ old('category_name') }}" required="required"  />
                                       <span id="start_date_err"></span>
								     </div>
                                    </div>
                                    <div class="row">
								     <div class="col-md-12 form-group">
                                       <label>@lang('messages.Description')&nbsp;<span class="text-danger">*</span></label>
									   <textarea class="form-control" name="description" id="description" placeholder="@lang('messages.decription')"></textarea>
                                       <span id="start_date_err"></span>
								     </div>
                                    </div>
                                    <div class="row">
								     <div class="col-md-12 form-group">
                                       <label>@lang('messages.BrowseImage') <span class="text-danger">*</span></label>
									    <input type="hidden"  name="cat_file_name_copy" id="cat_file_name_copy" value=""/>
                                       <input type="file" class="form-control" placeholder="@lang('messages.CategoryName')" name="cat_file_name[]" id="cat_file_name" required="required" multiple="multiple" />
                                       <span id="start_date_err"></span>
								     </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
										<div class="form-check form-check-inline">
											<button type="submit" id="category_name" class="btn bg-blue ml-3">@lang('messages.Submit') <i class="icon-paperplane ml-2"></i></button>
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
							<a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang('messages.Home')</a>
							<a href="#" class="breadcrumb-item">@lang('messages.Admin') </a>
							<span class="breadcrumb-item active">@lang('messages.AddCategory') </span>
						</div>
						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>
				</div>
@stop
@push('scripts')
  <script src="{{ url('validateJS/admin.js') }}"></script>
  <script src="{{ url('validateJS/date/jquery-ui.css') }}"></script>
  <script src="{{ url('validateJS/date/jquery-ui.js') }}"></script>
@endpush

