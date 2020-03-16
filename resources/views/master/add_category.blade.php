@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="card">
			                <div class="card-body">
			                	<form action="{{ url('master/add_category')}}" method="post">
                                    @csrf
									<div class="form-group">
										<label>Select Category Type</label>
										  <select class="form-control" name="country" id="country">
                                           <option value="1">Products type</option>
                                           <option value="2">Service type</option>
                                         </select>
                                        <span id="title_err"></span>
									</div>
                                    <div class="form-group">
										<label>Parent category </label>
										  <select class="form-control" name="country" id="country">
                                           <option value="1">Products type</option>
                                           <option value="2">Service type</option>
                                         </select>
                                        <span id="title_err"></span>
									</div>
									<div class="row">
								     <div class="col-md-12 form-group">
                                       <label>Category Name <span class="text-danger">*</span></label>
									   <input type="text" class="form-control" placeholder="Category name" name="category_name" id="category_name" value="{{ old('category_name') }}" required="required" />
                                       <span id="start_date_err"></span>
								     </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
										<div class="form-check form-check-inline">
											<button type="submit" id="workshop_sbmt" class="btn bg-blue ml-3">Submit <i class="icon-paperplane ml-2"></i></button>
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
							<a href="index.html" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
							<a href="internationalization_fallback.html" class="breadcrumb-item">Vendor </a>
							<span class="breadcrumb-item active">Add Category </span>
						</div>
						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>
				</div>
@stop
@push('scripts')
  <script src="{{ url('validateJS/date/jquery-ui.css') }}"></script>
  <script src="{{ url('validateJS/date/jquery-ui.js') }}"></script>
@endpush

