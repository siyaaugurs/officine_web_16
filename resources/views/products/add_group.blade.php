@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="card">
<div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-plus"></i>&nbsp;@lang('messages.AddNewCategory')</h6>
        </div>
@if(Session::has('msg'))
  {!!  Session::get("msg") !!}
@endif          
			                <div class="card-body">
			                	<form id="add_group_form">
                                 @csrf
                                 <div class="form-group">
								                   <button type="submit" class="btn btn-success" style="float:;" id="add_coupon_group_btn">Save&nbsp; <span class="glyphicon glyphicon-plus"></span></button>
                                   &nbsp;
												 <a href='{{ url("products/category_list") }}' class="btn btn-primary" style="color:white;" id="view_all_group">View all category &nbsp;<span class="glyphicon glyphicon-list"></span></a>
                                   
												 </div>
                                 	<div class="form-group">
										<label>Select Makers</label>
										  <select class="form-control" name="car_makers" id="car_makers">
                                          <option hidden="hidden">--Select-- Makers--Name--</option>
                                          @foreach($cars__makers_category as $makers)
                                            <option value="{{ $makers->idMarca }}">{{ $makers->Marca }}</option>
                                          @endforeach 
                                         </select>
                                        <span id="title_err"></span>
									</div>
                                    <div class="form-group">
										<label>Select Model Name</label>
										  <select class="form-control" name="car_models" id="car_models">
                        <option value="0">--First--Select--Makers--Name--</option>
                      </select>
                      			</div>
									<div class="form-group">
										<label>Select Version  </label>
										  <select class="form-control car_version_group" name="car_version"  data-action="get_group">
                        <option value="0">--First--Select--Model--Name--</option>
                      </select>
									</div>
                                    <div id="group_item_table">
                      			    </div>									
								</form>
							</div>
		                </div>
<div class="modal" id="add_products_group_pop_msg">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                    <h4 class="modal-title" id="myModalLabel"><i class="text-white icon-megaphone mr-3 icon-2x"></i> Message  </h4>
                    <hr />
                  </div>
                  <!-- Modal body -->
                        <div class="modal-body">
                          <div class="row ">
							 <div class="col-md-12">
                                  <div id="msg_response"></div>  
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
							<a href="{{ url('products/products_list') }}" class="breadcrumb-item">Products </a>
							<span class="breadcrumb-item active">Add New Category</span>
						</div>
						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>
				</div>
@stop
@push('scripts')
  <script src="{{ url('validateJS/products.js') }}"></script>
@endpush

