@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="card">
<div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-plus"></i>&nbsp;@lang('messages.AddNewProducts_item')</h6>
        </div>
@if(Session::has('msg'))
  {!!  Session::get("msg") !!}
@endif          
			                <div class="card-body">
                                <form id="add_products_by_admin">
                                 @csrf
                                 <div class="form-group">
								   <button type="submit" class="btn btn-success" style="float:;" id="products_save_button">Save&nbsp;<span class="glyphicon glyphicon-plus"></span></button>
									 &nbsp;
							    	<a href='{{ url("products/products_list") }}' target="_blank" class="btn btn-primary" style="color:white;" id="view_all_group">Category Item List &nbsp;<span class="glyphicon glyphicon-list"></span></a> </div>
                                 	<div class="form-group">
										<label>Select Makers</label>
										  <select class="form-control" name="car_makers" id="car_makers">
                                          <option hidden="hidden">@lang('messages.selectMaker')</option>
                                          @foreach($cars__makers_category as $makers)
                                            <option value="{{ $makers->idMarca }}">{{ $makers->Marca }}</option>
                                          @endforeach 
                                         </select>
                                        <span id="title_err"></span>
									</div>
                                    <div class="form-group">
										<label>Select Model</label>
										  <select class="form-control" name="car_models" id="car_models">
                        <option value="0">@lang('messages.firstSelectMakers')</option>
                      </select>
                      			</div>
									<div class="form-group">
										<label>Select Version  </label>
										  <select class="form-control car_version_group" name="car_version" data-action="get_and_save">
                        <option value="0">@lang('messages.firstSelectModels')</option>
                      </select>
                      <span id="title_err"></span>
									</div>
                                    <div class="form-group">
										<label>Select Group item &nbsp; <!--<a href="#" data-markerid="{{ $makers->Marca }}" class="btn btn-success btn btn-sm add_group_name_btn" id="add_new_group">
                    <span class="glyphicon glyphicon-plus"></span>
                </a>--></label>
									 <select class="form-control" name="group_item" id="group_item" data-action="get_products_list">
                                        <option value="0">@lang('messages.firstSelectVersion')</option>
                                     </select>
                      			</div>
                                    <div id="item_table">
                      			    </div>									
								</form>
							</div>
		                </div>
		                <div class="modal" id="add_group_name_popup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Add New Group</h4>
                <hr />
            </div>
            <!-- Modal body -->
            <form id="add_new_group">
                @csrf
                <input type="hidden" name="marker_id" id="marker_id" value="" />
                <input type="hidden" name="models_id" id="models_id" value="" />
                <input type="hidden" name="version_id" id="version_id" value="" />
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Enter Group Name &nbsp;<span class="text-danger">*</span></label>
                                        <input type="text"  class="form-control" require id="group_name" name="group_name" placeholder="@lang('messages.Groupname')" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">  
                                <button type="submit" id="new_group_btn" class="btn btn-success"  id="">Save &nbsp;<i class="icon-paperplane ml-2"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div id="response_about_pakages"></div>
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
							<a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
							<a href="#" class="breadcrumb-item">@lang('messages.Admin') </a>
							<span class="breadcrumb-item active">@lang('messages.AddNewProducts')</span>
						</div>
						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>
				</div>
@stop
@push('scripts')
  <script src="{{ url('validateJS/products.js') }}"></script>
@endpush

