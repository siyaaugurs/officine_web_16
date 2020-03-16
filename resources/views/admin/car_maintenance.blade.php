@extends('layouts.master_layouts')

@section('content')

<input type="hidden" name="page" id="page" value="{{ $page }}" />

@if(session::has('msg'))

  {!! Session::get('msg') !!}

@endif

<style> .container{ padding:15px;} </style>

<div class="card" style="margin-bottom:10px;" >

    <div class="card-header bg-light header-elements-inline">

        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-filter"></i>&nbsp;@lang('messages.Filter')</h6>

    </div>

    <div class="content">

	    <div id="filter-panel">

            <div class="panel panel-default">

                <div class="panel-body">

                    <div class="row">

                        <div class="col-sm-6">

                            <div class="form-group">

                                <select class="form-control" name="car_makers" id="car_makers">

                                <option value="0">@lang('messages.selectMaker')</option>

                                    @foreach($cars__makers_category as $makers)

                                    <option value="@if(!empty($makers->idMarca)){{ $makers->idMarca }} @endif">@if(!empty($makers->Marca)){{ $makers->Marca }} @endif</option>

                                    @endforeach 

                                </select>                                

                            </div> 

                        </div>

                        <div class="col-sm-6">

                            <div class="form-group">

                                <select class="form-control" name="car_models" id="car_models">

                                    <option value="0">@lang('messages.firstSelectMakers')</option>

                                </select>                                

                            </div>

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-sm-6">

                            <div class="form-group">

                                <select class="form-control car_version_group" id="version_id" name="car_version" data-action="get_and_save_services_time">

                                    <option value="0">@lang('messages.firstSelectModels')</option>

                                </select>                                

                            </div> 

                        </div> 

                         <div class="col-sm-6" style="display:none;" id="times_id_div">

                            <div class="form-group">

                                <select class="form-control" name="hourly_time"  id="item_repair_time_id" data-action="save_and_get_item_repair_times">

                                <option value="0">@lang('messages.firstSelectVersion')</option>

                                </select>                                

                            </div>

                        </div> 

                    </div>

                    <div class="row">

                        <div class="col-sm-12">

                            <div class="form-group">

                            <a href='#' id="search_car_maintinance_services" class="btn btn-warning" style="color:white;">Search &nbsp;<span class="glyphicon glyphicon-search"></span></a>         

                            </div>

                            <div class="form-group">

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div> 
        <div id="filter-panel">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form class="form-inline" role="form">
                        <div class="form-group">
                           <input type="text" name="item_name" id="item_name" placeholder="Item Name" class="form-control col-sm-12">                                 
                        </div>&nbsp;&nbsp;&nbsp;
                        <div class="form-group">
                          <a href='#' id="search_by_item_name" class="btn btn-warning" style="color:white;">Search &nbsp;<span class="glyphicon glyphicon-search"></span></a>                                 
                        </div>
                    </form>
                </div>
            </div>
        </div> 

    </div>

</div>

<!--<div class="card">

<div class="card-header bg-light header-elements-inline">

        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;Our Maintenance Services</h6>

       <!-- <a href='{{ url("products/add_group") }}' class="btn btn-primary" style="color:white;">Add New Category &nbsp;<span class="glyphicon glyphicon-plus"></span></a>-->

        <!-- <a href='#' class="btn btn-primary" id="add_new_service" style="color:white;">Add New Service &nbsp;<span class="glyphicon glyphicon-plus"></span></a>

        </div>

	<div class="card-body" id="">-->

       {{-- @include('admin.component.our_car_maintinance') --}}

    <!--</div>

</div>-->

<div class="card">

<div class="card-header bg-light header-elements-inline">

        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;Car Maintenance Services List</h6>

       <!-- <a href='{{ url("products/add_group") }}' class="btn btn-primary" style="color:white;">Add New Category &nbsp;<span class="glyphicon glyphicon-plus"></span></a>-->

          <a href='#' class="btn btn-primary" id="add_new_service" style="color:white;">Add New Service &nbsp;<span class="glyphicon glyphicon-plus"></span></a>

        </div>

	<div class="card-body" id="car_maintinance_ColWrap">

       @include('admin.component.car_maintinance')

       <div class="row" style="margin-top:10px;">

          <div class="col-sm-12">

             {{ $car_maintinance_service_list->links() }}

          </div>

        </div>

    </div>

</div>



<!--Add Services Details-->

<div class="modal" id="add_maintenance_service">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i>

				</button>

				<h4 class="modal-title" id="myModalLabel">Car Maintenance Service </h4>

				<hr />

			</div>

			<!-- Modal body -->

            <div class="card-body">

               <form id="add_maintenance_services_form" autocomplete="off">

                        @csrf

                        <div class="form-group">

                            <label>@lang('messages.ItemName')&nbsp;<span class="text-danger">*</span></label>

                            <input type="text" class="form-control" placeholder="@lang('messages.ItemName')" name="item_name" id="item_name" required="required" value="" />

                            <span id="title_err"></span>

                        </div>

                        <div class="form-group">

                            <label>@lang('messages.frontRare')&nbsp;<span class="text-danger">*</span></label>

                            <select name="front_rear" id="front_rear" class="form-control">

                                <option value="">Default</option>

                                <option value="front">Front</option>

                                <option value="rear">Rear</option>

                            </select>

                            <span id="title_err"></span>

                        </div>

                        <div class="form-group">

                            <label>@lang('messages.leftRight')&nbsp;<span class="text-danger">*</span></label>

                            <select name="left_right" id="left_right" class="form-control">
                                <option value="" hidden="hidden">--Select Option--</option>

                                <option value="">Default</option>

                                <option value="rh.">Right</option>

                                <option value="lh.">Left</option>

                            </select>

                        </div>

                        <div class="form-group">

                            <label>kromeda Description&nbsp;<span class="text-danger">*</span></label>

                            <textarea name="kromeda_description" id="kromeda_description" class="form-control" placeholder="Kromeda Description"></textarea>

                            <span id="title_err"></span>

                        </div>

                        <div class="form-group">

                            <label>Our Description&nbsp;</label>

                            <textarea name="our_description" id="our_description" class="form-control" placeholder="Our Description"></textarea>

                        </div>

                        <div class="form-group">

                            <label>Kromeda Time&nbsp;<span class="text-danger">*</span></label>

                            <input type="text" class="form-control" id="kromeda_time" name="kromeda_time" placeholder="Kromeda Time" />

                        </div>

                        <div class="form-group">

                            <label>Our Time&nbsp;</label>

                            <input type="text" class="form-control" id="our_time" name="our_time" placeholder="Our Time" />

                        </div>

                        <div class="form-group">

                            <label>@lang('messages.Info')&nbsp;<span class="text-danger">*</span></label>

                            <input type="text" class="form-control" id="info" name="info" placeholder="@lang('messages.Info')" value="" />

                        </div>

                        <div class="form-group">

                            <label>priority&nbsp;<span class="text-danger">*</span></label>

                            <input type="text" class="form-control add_priority" placeholder="Priority" name="priority" id="priority" required="required" data-type='add'/>

                            <span id="priority_err"></span>

                        </div>

                        <div class="form-group">

                            <label>@lang('messages.Language')&nbsp;<span class="text-danger">*</span></label>

                            <select name="language" id="language" class="form-control">

                                <option value="ENG">English</option>

                                <option value="IT">Italian</option>

                            </select>

                        </div>

                        <div class="d-flex justify-content-between align-items-center">

                            <div class="form-check form-check-inline">

                                <button type="submit" id="add_car_maintenance_details_btn" class="btn bg-blue ml-3">@lang('messages.Submit')<i class="icon-paperplane ml-2"></i></button>

                            </div>

                        </div>

                    </form>

            </div>

			<div id="response_err"></div>

		</div>

		<div class="modal-footer"></div>

	</div>

</div>

<!--End-->

<!--Edit Services Details-->

<div class="modal" id="edit_maintenance_service">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i>

				</button>

				<h4 class="modal-title" id="myModalLabel">Edit Car Maintenance Service </h4>

				<hr />

			</div>

			<!-- Modal body -->

            <div class="card-body">

               <form id="edit_maintenance_services_form" autocomplete="off">

                        @csrf

                        <input type="hidden" name="maintenance_id" id="maintenance_id" value="">
                        <input type="hidden" name="edit_maintainance_version" id="edit_maintainance_version" value="">

                        <div class="form-group">

                            <label>@lang('messages.ItemName')&nbsp;<span class="text-danger">*</span></label>

                            <input type="text" class="form-control" placeholder="@lang('messages.ItemName')" name="item_name" id="edit_item_name" required="required" />

                            <span id="title_err"></span>

                        </div>

                        <div class="form-group">

                            <label>@lang('messages.frontRare')&nbsp;<span class="text-danger">*</span></label>

                            <select name="front_rear" id="edit_front_rear" class="form-control">

                                <option value="front">Front</option>

                                <option value="rear">Rear</option>

                            </select>

                            <span id="title_err"></span>

                        </div>

                        <div class="form-group">

                            <label>@lang('messages.leftRight')&nbsp;<span class="text-danger">*</span></label>

                            <select name="left_right" id="edit_left_right" class="form-control">

                                <option value="rh.">Right</option>

                                <option value="lh.">Left</option>

                            </select>

                            <span id="title_err"></span>

                        </div>

                        <div class="form-group">

                            <label>kromeda Description&nbsp;<span class="text-danger">*</span></label>

                            <textarea name="kromeda_description" id="edit_kromeda_description" class="form-control" placeholder="Kromeda Description"></textarea>

                            <span id="title_err"></span>

                        </div>

                        <div class="form-group">

                            <label>Our Description&nbsp;</label>

                            <textarea name="our_description" id="edit_our_description" class="form-control" placeholder="Our Description"></textarea>

                        </div>
                        <div id="edit_k_time_Div">
                            <div class="form-group">

                                <label>Kromeda Time&nbsp;<span class="text-danger">*</span></label>

                                <input type="text" class="form-control" id="edit_kromeda_time" name="kromeda_time" placeholder="Kromeda Time" />

                            </div>

                            <div class="form-group">

                                <label>Our Time&nbsp;</label>

                                <input type="text" class="form-control" id="edit_our_time" name="our_time" placeholder="Our Time" />

                            </div>
                        </div>

                        <div class="form-group">

                            <label>@lang('messages.Info')&nbsp;<span class="text-danger">*</span></label>

                            <input type="text" class="form-control" id="edit_info" name="edit_info" placeholder="@lang('messages.Info')" value="" />

                        </div>

                        <div class="form-group">

                            <label>priority&nbsp;<span class="text-danger">*</span></label>

                            <input type="text" class="form-control priority edit_priority" placeholder="Priority" name="priority" id="priority" required="required" data-type='edit'/>

                            <span id="priority_err"></span>

                        </div>

                        <div class="form-group">

                            <label>@lang('messages.Language')&nbsp;<span class="text-danger">*</span></label>

                            <select name="language" id="edit_language" class="form-control">

                                <option value="ENG">English</option>

                                <option value="IT">Italian</option>

                            </select>

                        </div>

                        <div class="d-flex justify-content-between align-items-center">

                            <div class="form-check form-check-inline">

                                <button type="submit" id="edit_car_maintenance_details_btn" class="btn bg-blue ml-3">@lang('messages.Submit')<i class="icon-paperplane ml-2"></i></button>

                            </div>

                        </div>

                    </form>

            </div>

			<div id="response_err"></div>

		</div>

		<div class="modal-footer"></div>

	</div>

</div>

<!--End-->

@endsection

@section('breadcrum')

<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">

    <div class="d-flex">

        <div class="breadcrumb">

            <a href="{{ url('/') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>@lang('messages.Home')</a>
            <a href="{{ url('/') }}" class="breadcrumb-item">@lang('messages.Admin')</a>

            <span class="breadcrumb-item active">Car Maintenance  </span>

        </div>

        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>

    </div>

</div>

@stop

@push('scripts')

<script src="{{ url('validateJS/car_maintinance.js') }}"></script>

<link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" />

<script src="{{ url('validateJS/mot_service.js') }}"></script>

<script src="{{ url('validateJS/products.js') }}"></script>

<script src="{{ url('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>

<script src="{{ url('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>

<script>

    $(document).ready(function(e){

        $('[data-toggle="tooltip"]').tooltip(); 

    });

</script>

@endpush



