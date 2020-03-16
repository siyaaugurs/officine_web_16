@extends('layouts.master_layouts') @section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" /> @if(session::has('msg')) {!! Session::get('msg') !!} @endif
<style>
.container { padding: 15px; }
</style>
<div class="row" style="margin-bottom:10px;">
    <div class="col-sm-12">
        <!--<a href='{{ url("products/add_group") }}' class="btn btn-primary" style="color:white;">Add New Category &nbsp;<span class="glyphicon glyphicon-plus"></span></a>&nbsp;&nbsp;&nbsp;
         -->
        <a href="#" class="btn btn-primary" id="add_group" style="color:white;">Add New category &nbsp;<span class="glyphicon glyphicon-plus"></span></a>&nbsp;&nbsp;&nbsp;
        <a href="#" class="btn btn-primary" id="add_sub_group" style="color:white;">Add New Sub-Category &nbsp;<span class="glyphicon glyphicon-plus"></span></a>&nbsp;&nbsp;&nbsp;
        <!-- <a href='{{ url("products/manage_n3_category") }}' class="btn btn-primary" style="color:white;">Add New Sub-Category (N3) &nbsp;<span class="glyphicon glyphicon-plus"></span></a> -->
        <a href="javascript::void();" class="btn btn-primary" id="add_custom_n3_category" style="color:white;">Add New Sub-Category (N3) &nbsp;<span class="glyphicon glyphicon-plus"></span></a>&nbsp;&nbsp;&nbsp;
        <a href="javascript::void();" class="btn btn-warning" id="export_group" style="color:white;">Export Category Files&nbsp;<span class="glyphicon glyphicon-plus"></span></a>&nbsp;&nbsp;&nbsp;
        <a href="javascript::void();" class="btn btn-warning" id="import_sample_format" style="color:white;">Import Categories&nbsp;<span class="glyphicon glyphicon-plus"></span></a>&nbsp;&nbsp;&nbsp;
    </div>
</div>
<div class="card" style="margin-bottom:10px;">
    <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-filter"></i>&nbsp;@lang('messages.Filter')</h6>
    </div>
    <div class="container">
        <div id="filter-panel">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form class="form-inline" role="form">
                        <div class="form-group">
                            <select class="form-control" name="car_makers" id="car_makers">
                                <option value="0">@lang('messages.selectMaker')</option>
                                @foreach($cars__makers_category as $makers)
                                <option value="{{ $makers->idMarca }}">{{ $makers->Marca }}</option>
                                @endforeach
                            </select>
                        </div>
                        &nbsp;&nbsp;&nbsp;
                        <div class="form-group">
                            <select class="form-control" name="car_models" id="car_models">
                                <option value="0">@lang('messages.firstSelectMakers')</option>
                            </select>
                        </div>
                        &nbsp;&nbsp;&nbsp;
                        <div class="form-group">
                            <select class="form-control car_version_group" name="car_version" id="version_id" data-action="get_and_save_1">
                                <option value="0">@lang('messages.firstSelectModels')</option>
                            </select>
                        </div>
                        &nbsp;&nbsp;&nbsp;
                        <div class="form-group">
                            <a href='#' id="search_parts_group" class="btn btn-warning" style="color:white;">Search &nbsp;<span class="glyphicon glyphicon-search"></span></a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .main-table .li-childs,
    .main-table .li-subchilds {
        display: none;
    }
    
    .main-table .li-child-toggler {
        /*padding: .75rem 1.25rem;
        display: block;*/
        background-color: #FFF;
		float:left; 
		width:25%;
		       
    }
    
    .main-table .li-subchild-toggler {
       /* padding: .75rem 1.25rem;
        display: block;*/
		float:left; 
		width:25%;
    }
    
    .main-table .li-child-toggler .glyphicon,
    .main-table .li-subchild-toggler .glyphicon {
        cursor: pointer;
    }
    
    .main-table .li-childs {
        margin: 0;
    }
    
    .main-table .p-0 {
        padding: 0 !important;
    }
    
    .main-table .m-0 {
        margin: 0 !important;
    }
    
    .main-table .pr-25 {
        padding-right: 25px !important;
    }
    
    .main-table .pl-25 {
        padding-left: 25px !important;
    }
    
    .right-action-btns {
        float: left;
        padding: 6px 7px;
    }

    .right-description {
        float: left;
    padding: 6px 17px;
    width: 30%;
    text-align: left;
    }

    .right-status {
        float: left;
		width: 24%;
        padding: 6px 7px;
    }
    
    .main-table {}
    
    .active-parent.li-child-toggler {
        background-color: rgba(0, 0, 0, .2);
    }
    
    table.table.li-childs {
        background-color: rgba(0, 0, 0, 0.1);
    }
    
    table.table.li-subchilds {
        background-color: #f5f5f5;
    }
    .show_class{ display:block;}
    .loading {
        position: relative;
    }
    .loading > * {
        filter: blur(5px);
    }
    .loading:after {
        content: 'Loading...';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-content: center;
        text-indent: 20px;
        background: rgba(0,0,0,0.2);
        color: white;
        font-weight: bold;
        font-size: 120%;
        letter-spacing: 2px;
        text-shadow: 0 0 5px black;
    }
	table.table.li-childs td{
		 padding-left: 15px !important;
    	 padding-top: 10px !important;
		}
	
	
</style>
<div class="card">
    <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-filter"></i>&nbsp;@lang('messages.CategoryList')</h6>
    </div>
    <div class="card-body" id="user_data_body">
         @include('products.component.category_list_new') 
        <div class="row" style="margin-top:20px;">
            <div class="col-sm-12">
               {{--  @if($group_list->count() > 0) {{ $group_list->links() }} @endif   --}}

            </div>
        </div>
    </div>
</div>

<!--Import Sample Format-->
<div class="modal" id="import_sample_format_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Import Categories </h4>
                <hr />
            </div>
            <div class="card-body">
                <!-- <a href="<?php echo url('export/sample_category') ?>" class="btn btn-warning"><i class="fa fa-download"></i>&nbsp;Export Excel For Category</a>
                <a href="<?php echo url('export/sample_sub_category') ?>" class="btn btn-warning"><i class="fa fa-download"></i>&nbsp; Export Excel For Sub Category</a> -->
                <!-- <hr /> -->
                <h3 style="font-weight:600;">Import Excel Files </h3> 
                <form id="import_category_form">
                @csrf
                    <div class="row" style="margin-top:15px;">
                 <div class="col-sm-12">
                    <p style="color:#F00; font-weight:600; font-size:18px;">Type = 1 (For Kromeda Data)</p>
                    <p style="color:#F00; font-weight:600; font-size:18px;">Type = 2 (For Our Custom Data)</p>
                 </div>
               </div>
                    <div class="control-group">
                        <label class="control-label" for="field1" style="margin: 6px;">
                            Select Category Type
                        </label>
                        <select class="form-control" name="type" id="" >
                            <option>Select Category </option>
                            <option value="1">Import Excel For Category </option>
                            <option value="2">Import Excel For Sub Category </option>
                            <option value="3">Import Excel For N3 Category </option>
						</select>
                    </div>
                    <div class="control-group" id="fields" style="margin-top:15px;">
                        <span id="rim_msg_response"></span>
                        <label class="control-label" for="field1">
                            Browse Files
                        </label>
                        <div class="controls">
                            <div class="entry input-group col-xs-3">
                                <input class="btn btn-primary" name="category_file" type="file"  required>
                                <span class="input-group-btn">
                                    &nbsp;&nbsp;
                                    <button class="btn btn-success btn-add" type="submit" id="add_category_import_btn">
                                        Import
                                        <span class="glyphicon glyphicon-plus "></span>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal-footer">
        </div>
    </div>
</div>
<!--End-->
<!--Export All data modal popup start--->
<div class="modal" id="export_groups_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Export Files</h4>
                <hr />
            </div>
            <div class="card-body">
               <div class="row">
                 <div class="col-sm-6">
                   <a href="<?php echo url('export/category') ?>" class="btn btn-warning"><i class="fa fa-download"></i>&nbsp;Export Excel For Category</a>
                 </div>
                 <div class="col-sm-6">
                    <a href="<?php echo url('export/sub_category') ?>" class="btn btn-warning"><i class="fa fa-download"></i>&nbsp; Export Excel For Sub Category</a>
                 </div>
               </div>
               <div class="row">
                 <div class="col-sm-6">
                    <a href="<?php echo url('export/n3_category') ?>" class="btn btn-warning" style="margin-top:15px;"><i class="fa fa-download"></i>&nbsp; Export Excel For n3 Category</a>
                 </div>
               </div>
               <div class="row" style="margin-top:15px;">
                 <div class="col-sm-12">
                    <p style="color:#F00; font-weight:600; font-size:18px;">Type = 1 (For Kromeda Data)</p>
                    <p style="color:#F00; font-weight:600; font-size:18px;">Type = 2 (For Our Custom Data)</p>
                 </div>
               </div> 
            </div>
        </div>
        <div class="modal-footer">
        </div>
    </div>
</div>
<!--End-->


<!--Upload N1 category image script start-->
<div class="modal" id="add_group_image_popup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Upload Category Images </h4>
                <hr />
            </div>
            <div id="err_response"></div>
            <!-- Modal body -->
            <form id="group_images_form">
                @csrf
                <input type="hidden" name="group_id" id="group_id" value="" readonly="readonly" />
                <div class="modal-body">
                    <div class="control-group" id="fields">
                        <label class="control-label" for="field1">
                            Browse Multiple Image
                        </label>
                        <div class="controls">
                            <div class="entry input-group col-xs-3">
                                <input class="btn btn-primary" name="images[]" type="file" multiple="multiple" accept=".jpg,.png," require>
                                <span class="input-group-btn">
                        &nbsp;&nbsp;
                        <button class="btn btn-success btn-add" type="submit" id="save_group_image">
                        Save
                        <span class="glyphicon glyphicon-plus"></span>
                                </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div id="image_result"></div>
        </div>
        <div class="modal-footer">
        </div>
    </div>
</div>
<!--End--->
<!--Add Group Modal popup script start-->
<div class="modal" id="add_group_modal_popup">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i>
				</button>
				<h4 class="modal-title" id="myModalLabel">Add New category </h4>
				<hr />
			</div>
			<!-- Modal body -->
            <div class="card-body">
               <form id="add_n1_category_form" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <span id="title_err"></span>
                            <label>@lang('messages.CategoryName')&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="@lang('messages.CategoryName')" name="group_name" id="group_name" required="required" value="" />
                        </div>
                        <div class="form-group">
                            <span id="title_err"></span>
                            <label>Description&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="description" name="description" id="description" required="required" value="" />
                        </div>
                        <div class="form-group">
                            <label>priority&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control priority" placeholder="Priority" name="priority" id="priority" required="required" data-type='groups'/>
                            <span id="priority_err"></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-check form-check-inline">
                                <button type="submit" id="add_n1_category_btn" class="btn bg-blue ml-3">@lang('messages.Submit')<i class="icon-paperplane ml-2"></i></button>
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
<!--Add Sub Groups modal popup script start-->
<div class="modal" id="add_sub_group_modal_popup">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i>
				</button>
				<h4 class="modal-title" id="myModalLabel">Add Sub Category (N2) </h4>
				<hr />
			</div>
			<!-- Modal body -->
            <div class="card-body">
               <form id="add_n2_category_form" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <span id="title_err"></span>
                            <label>Select category &nbsp;(N1 Category)<span class="text-danger">*</span></label>
                            <select name="group_name" id="n1_category" class="form-control">
                                <option value="0" hidden="hidden">Select Category</option>
                                @forelse($category_for_listing as $category)
                                  <option value="{{ $category->id }}">{{ $category->group_name }}</option>
                                @empty
                                  <option value="0">No Category found !!!</option>
                                @endforelse
                                
                            </select>
                        </div>
                        <div class="form-group">
                            <span id="title_err"></span>
                            <label>Select Service Group&nbsp;<span class="text-danger">*</span></label>
                            <select name="service_group" id="service_group" class="form-control">
                                <option value="0" hidden="hidden">--Select Service Group--</option>
                                @forelse($private_service_group as $service_group)
                                    <option value="{{ $service_group->id }}">{{ $service_group->main_cat_name }}</option>
                                @empty
                                    <option value="0">--No Service Group Avilable--</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="form-group">
                            <span id="title_err"></span>
                            <label>Sub Category Name&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="@lang('messages.SubGroupName')" name="sub_group_name" id="sub_group_name" required="required" value="" />
                        </div>
                        <div class="form-group">
                            <span id="title_err"></span>
                            <label>Description&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="description" name="description" id="n2_description" required="required" value="" />
                        </div>
                        <div class="form-group">
                            <label>priority&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control priority" placeholder="Priority" name="priority" id="n2_priority" required="required" data-type='subgroups'/>
                            <span id="priority_err"></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-check form-check-inline">
                                <button type="submit" id="add_n2_category_btn" class="btn bg-blue ml-3">@lang('messages.Submit')<i class="icon-paperplane ml-2"></i></button>
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
<!--Add N3 category modal script strat-->
<div class="modal" id="add_n3_modal_popup">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i>
				</button>
				<h4 class="modal-title" id="myModalLabel">Add N3 Category</h4>
				<hr />
			</div>
			<!-- Modal body -->
            <div class="card-body">
               <form id="add_n3_category_form" autocomplete="off">
                        @csrf
                       <div class="form-group">
                            <label>Select category &nbsp;(N1 Category)<span class="text-danger">*</span></label>
                            <select name="group_name" id="category_n1" class="form-control">
                                <option value="0" hidden="hidden">Select Category Name</option>
                                @forelse($category_for_listing as $category)
                                    <option value="{{ $category->id }}">{{ $category->group_name }}</option>
                                @empty
                                   <option value="0">No category available !!!</option>                                @endforelse
                            </select>
                        </div>
                       <div class="form-group">
                            <label>Select  Sub category &nbsp;(N2 Category)<span class="text-danger">*</span></label>
                            <select name="sub_category" id="sub_category" class="form-control sub_groups">
                             <option value="0" hidden="hidden">--Select--category--name--</option>
                              
                            </select>
                        </div> 
                        <div class="form-group">
                            <span id="title_err"></span>
                            <label>Sub Category (N3 )&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="Sub Category (N3 )" name="sub_category_n3" id="group_name" required="required" />
                        </div>
                        <div class="form-group">
                            <span id="title_err"></span>
                            <label>@lang('messages.FrontRare')&nbsp;<span class="text-danger">*</span></label>
                            <select name="front_rare" id="front_rare" class="form-control">
                                <option value=" ">Select</option>
                                <option value="front">Front</option>
                                <option value="rear">Rare</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <span id="title_err"></span>
                            <label>@lang('messages.LeftRight')&nbsp;<span class="text-danger">*</span></label>
                            <select name="left_right" id="left_right" class="form-control">
                                <option value=" ">Select</option>
                                <option value="lh.">Left</option>
                                <option value="rh.">Right</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('messages.Description')&nbsp;<span class="text-danger">*</span></label>
                            <textarea name="description" id="description" class="form-control" placeholder="@lang('messages.Description')"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Priority&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control priority_all" placeholder="Priority" name="priority" id="n3_priority" required="required"/>
                            <span id="priority_err"></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-check form-check-inline">
                                <button type="submit" id="add_n3_category_btn" class="btn bg-blue ml-3 priority_btn">Save&nbsp;<i class="icon-paperplane ml-2"></i></button>
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
<!--Edit N3 category modal script start-->
<div class="modal" id="kromeda_edit_n3_modal_popup">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i>
				</button>
				<h4 class="modal-title" id="myModalLabel">Edit N3 Category (Edit) </h4>
				<hr />
			</div>
			<!-- Modal body -->
            <div class="card-body">
               <form id="kromeda_edit_n3_category_form" autocomplete="off">
                        @csrf
                      <input type="hidden" class="form-control"  name="edit_n3_category_id" id="category_id" required="required"/>
                       <div class="form-group">
                            <label>Select category &nbsp;(N1 Category)<span class="text-danger">*</span></label>
                            <select name="group_name" id="category_n1" class="form-control">
                                <option value="0" hidden="hidden">Select Category Name</option>
                                  @forelse($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->group_name }}</option>
                                  @empty
                                   <option value="0">No category available !!!</option>                                  @endforelse
                            </select>
                        </div>
                       <div class="form-group">
                            <label>Select  Sub category &nbsp;(N2 Category)<span class="text-danger">*</span></label>
                            <select name="sub_category" id="sub_category" class="form-control sub_groups">
                             <option value="0" hidden="hidden">--Select--category--name--</option>
                              
                            </select>
                        </div> 
                        <div class="form-group">
                            <span id="title_err"></span>
                            <label>Sub Category (N3 )&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="Sub Category (N3 )" name="sub_category_n3" id="sub_category_n3" required="required" />
                        </div>
                        <div class="form-group">
                            <span id="title_err"></span>
                            <label>@lang('messages.FrontRare')&nbsp;<span class="text-danger">*</span></label>
                            <select name="front_rare" id="front_rare" class="form-control">
                                <option value="0" hidden="hidden">Select</option>
                                <option value="front">Front</option>
                                <option value="rear">Rare</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <span id="title_err"></span>
                            <label>@lang('messages.LeftRight')&nbsp;<span class="text-danger">*</span></label>
                            <select name="left_right" id="left_right" class="form-control">
                                <option value="0">Select</option>
                                <option value="lh.">Left</option>
                                <option value="rh.">Right</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('messages.Description')&nbsp;<span class="text-danger">*</span></label>
                            <textarea name="description" id="description" class="form-control" placeholder="@lang('messages.Description')"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Priority&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control priority_all" placeholder="Priority" name="priority" id="n3_priority" required="required"/>
                            <span id="priority_err"></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-check form-check-inline">
                                <button type="submit" id="add_n3_category_btn" class="btn bg-blue ml-3 priority_btn">Save&nbsp;<i class="icon-paperplane ml-2"></i></button>
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
<div class="modal" id="edit_category_detils">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i>
                </button>
                <h4 class="modal-title" id="myModalLabel">Edit Category Details </h4>
                <hr />
            </div>
            <!-- Modal body -->
            <div class="card-body">
                <form id="edit_category_form" autocomplete="off">
                    @csrf
                    <input type="hidden" name="category_type" id="category_type" value="">
                    <input type="hidden" name="category_id" id="category_id" value="">
                    <div class="form-group">
                        <span id="title_err"></span>
                        <label>@lang('messages.GroupName')&nbsp;(N1 Category)<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="@lang('messages.GroupName')" name="edit_group_name" id="edit_group_name" required="required" value="" />
                    </div>
                    <div class="form-group">
                        <span id="title_err"></span>
                        <label>@lang('messages.Description')&nbsp;<span class="text-danger">*</span></label>
                        <textarea class="form-control" name="description" id="category_description" placeholder="@lang('messages.Description')"></textarea>
                    </div>
                    <div class="form-group">
                            <label>priority&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control priority" placeholder="Priority" name="priority" id="priority" required="required" data-type='groups'/>
                            <span id="priority_err"></span>
                        </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="form-check form-check-inline">
                            <button type="submit" id="edit_category_btn" class="btn bg-blue ml-3">@lang('messages.Submit')<i class="icon-paperplane ml-2"></i></button>
                        </div>
                    </div>
                </form>
            </div>
            <div id="response_err"></div>
        </div>
        <div class="modal-footer"></div>
    </div>
</div>
<!--Edit N2 category list-->
<div class="modal" id="edit_n2_category_details">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i>
				</button>
				<h4 class="modal-title" id="myModalLabel">Edit Sub Category</h4>
				<hr />
			</div>
			<!-- Modal body -->
            <div class="card-body">
               <form id="edit_n2_category_form" autocomplete="off">
                        @csrf
                        <input type="hidden" name="edit_n2_category_id" id="edit_n2_category_id">
                        <div class="form-group">
                            <span id="title_err"></span>
                            <label>@lang('messages.GroupName')&nbsp;(N1 Category)<span class="text-danger">*</span></label>
                            <select name="groups" id="groups" class="form-control">
                                <option value="0" hidden="hidden">Select Group Name</option>
                                  @forelse($categories as $category)
                                  <!--   <option value="{{ $category->id }}">{{ $category->group_name }}</option> -->
                                    <option value="{{ $category->id }}" <?php if(!empty($category->group_id)) { ?> data-groupid="{{ !empty($category->group_id) ? $category->group_id : '' }}" <?php }?> >{{ $category->group_name }}</option>

                                  @empty
                                   <option value="0">No category available !!!</option>                                  @endforelse
                            </select>
                        </div>
                        <div class="form-group" style="display:none" id="display_service_div">
                            <span id="title_err"></span>
                            <label>Service Group<span class="text-danger">*</span></label>
                            <select name="service_group" id="edit_service_group" class="form-control">
                                <option value="0" hidden="hidden">--Select Service Group--</option>
                                @forelse($private_service_group as $service_group)
                                    <option value="{{ $service_group->id }}">{{ $service_group->main_cat_name }}</option>
                                @empty
                                    <option value="0">--No Service Group Avilable--</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="form-group">
                            <span id="title_err"></span>
                            <label>@lang('messages.SubGroupName')&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="@lang('messages.SubGroupName')" name="edit_sub_group_name" id="edit_sub_group_name" required="required" value="" />
                        </div>
                        <div class="form-group">
                            <span id="title_err"></span>
                            <label>Description&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="description" name="description" id="edit_n2_description" required="required" value="" />
                        </div>
                        <div class="form-group">
                            <label>priority&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control priority" placeholder="Priority" name="priority" id="edit_n2_priority" required="required" data-type='subgroups'/>
                            <span id="priority_err"></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-check form-check-inline">
                                <button type="submit" id="edit_n2_category_btn" class="btn bg-blue ml-3">@lang('messages.Submit')<i class="icon-paperplane ml-2"></i></button>
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
<!---N3 Category Image popup script start-->
<div class="modal" id="add_group_image_n3_popup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Upload Category (N3) Images</h4>
                <hr />
            </div>
            <div id="err_response"></div>
            <form id="n3_category_images_form">
                @csrf
                <input type="hidden" name="products_item_id" id="products_item_id" value="" readonly="readonly" />
                <div class="modal-body">
                    <div class="control-group" id="fields">Browse Multiple Image</label>
                        <div class="controls">
                            <div class="entry input-group col-xs-3">
                                <input class="btn btn-primary" name="images[]" type="file" multiple="multiple" accept=".jpg,.png," require>
                                <span class="input-group-btn">
                                &nbsp;&nbsp;
                                <button class="btn btn-success btn-add" type="submit" id="save_n3_group_image">
                                Save
                                <span class="glyphicon glyphicon-plus"></span>
                                </button>
                                </span>
                            </div>
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
<!--End-->
@endsection @section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
            <a href="{{ url('/') }}" class="breadcrumb-item">Admin </a>
            <a href="{{ url('products/products_list') }}" class="breadcrumb-item">Products </a>
            <span class="breadcrumb-item active">Category List</span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop @push('scripts')
<link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" />
<script>
    $(document).ready(function(e) {
        /*Open import export popup script start*/
        $(document).on('click','#import_sample_format',function(){
            $("#import_sample_format_modal").modal({
                backdrop: 'static',
                keyboard: false,
            });
        });
        $(document).on('submit', '#import_category_form', function(e) {
            $('#rim_msg_response').html(" ");
            $('#add_category_import_btn').html('Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
            e.preventDefault();
            $.ajax({
                url: base_url + "/import/import_n3_category",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                complete: function(e, xhr, setting) {
                    if (e.status == 200) {
                        $("#rim_msg_response").html('<div class="notice notice-success"><strong> Success , </strong>Record Import Successfully .</div>');
                        setTimeout(function() { location.reload(); }, 1000);
                    }
                    $('#add_category_import_btn').html('Import Categories <span class="glyphicon glyphicon-import"></span>').attr('disabled', false);
                }
            });
        });
  /*End*/ 
  /*Open import export popup script start*/
  $(document).on('click','#export_group',function(){
      $("#export_groups_modal").modal({
                                backdrop: 'static',
                                keyboard: false,
                            });
  });
  /*End*/  
});
</script>
<script>
 function show_products_item_image(n3_category_id){
        if(n3_category_id != ""){
            $("#group_id").val(n3_category_id);
                $.ajax({
                url: base_url+"/products_ajax/get_n3_group_image",
                method: "GET",
                data: {groupId:n3_category_id},
                success: function(data){
                  $('#n3_image_response').html(data);
                  $('#add_group_image_n3_popup').modal({
                     backdrop:'static',
                     keyboard:false,
                  });
                }
        });
        }	
    }
$(document).ready(function(){
    $(document).on('click', '.delete_n3_category', function(e){
        e.preventDefault();
        var n3_category_id = $(this).data('n3categoryid');
        var url = base_url + "/products/remove_n3_category/"+n3_category_id;
        var con = confirm('Are you sure want to delete ?');
        if(con == true) {
            window.location.href = url;
        } else {
            return false ;
        }
    });
  
 /*Manage n3 */
 $(document).on('click', '.n3_category_image_products_item', function(e){
        e.preventDefault();
        var n3_category_id = $(this).data('n3categoryid');
        $("#products_item_id").val(n3_category_id);
        show_products_item_image(n3_category_id);
    });
 /*End*/   
 /*Save and get n3 */
$(document).on('click','.expand_sub_groups',function(){
    //$("#preloader").show();
    group_id = $(this).data('subgroupid');
    var language = $('html').attr('lang');
    let td = $(this).closest('td'),
    icon = $(this).children('.glyphicon');
    //let td = $(this).parent().sibling().find('table li-subchilds m-0');

    if(td.children('table.li-subchilds').length) {
        let table = td.children('table.li-subchilds');
        icon.removeClass('glyphicon-minus glyphicon-plus');
        if(table.is(':visible')) {
            table.hide();
            icon.addClass('glyphicon-plus');
            $(this).parent().removeClass('active-parent');
        } else {
            table.show();
            icon.addClass('glyphicon-minus');
            $(this).parent().addClass('active-parent');
        }
    } else {
        $.ajax({
            url:base_url+"/save_products_item_05_08",
            method:"GET",
            data:{group_id:group_id ,language:language},
            beforeSend: () => { td.addClass('loading') },
            success:function(data){
                parseJson = jQuery.parseJSON(data);
                if(parseJson.status == 200){
                    $.ajax({
                        url:base_url+"/products_group/get_n3_category",
                        method:"GET",
                        data:{group_id:group_id ,language:language},
                        beforeSend: () => { td.addClass('loading') },
                        success:function(n3_data){
                            n3_response =  jQuery.parseJSON(n3_data);
                            table = $('<table>' , {class:'table li-subchilds m-0' , style:'display:table'})
                            table_body = $('<tbody>');
                            front_rear = "";
                            left_right = "";
                            if(n3_response.status == 200){
                            if(n3_response.response.length != 0){
                                $.each(n3_response.response , function(index , value){
                                    a1 = $('<a>' , {class:'btn btn-danger delete_n3_category btn-sm'}).attr('href' ,'javascript::void()').data('n3categoryid' , value.id).append('<i class="fa fa-trash" ></i>').css('margin-left' , '10px')
                                    a2 = $('<a>' , {class:'btn btn-primary n3_category_image_products_item btn-sm'}).attr('href' , 'javascript::void()').data('n3categoryid' , value.id).append('<i class="fa fa-picture-o"></i>').css('margin-left' , '10px')
                                   a3 = $('<a>' , {class:'btn btn-primary edit_n3_category btn-sm'}).attr('href' , '#').data('n3categoryid' , value.id).append('<i class="fa fa-edit"></i>').css('margin-left' , '10px')
                                    if(value.our_description == null) {
                                            n3_description = $('<span>' , {class:'right-description'}).text("N/A")
                                    } else {
                                        n3_description = $('<span>' , {class:'right-description'}).text(value.our_description)
                                    }
                                    if(value.status == "P") {
                                        n3_status = $('<a>' , {class:' change_n3_category_status'}).attr('href' , '#').data('n3categoryid' , value.id).data('status' , 'A').append('<i class="fa fa-toggle-off"></i>')
                                    } else {
                                        n3_status = $('<a>' , {class:' change_n3_category_status'}).attr('href' , '#').data('n3categoryid' , value.id).data('status' , 'P').append('<i class="fa fa-toggle-on"></i>')
                                    }
                                   span = $('<span>' , {class:'right-action-btns'}).append(a1 , a2, a3) 
                                   n3_category_status = $('<span>' , {class:'right-status'}).append(n3_status ) 
                                        /*new table append code*/
                                    if(value.front_rear != ""){ front_rear  = value.front_rear;  }  
                                    else{ front_rear = "N/A"; }   
                                    if(value.left_right != ""){ left_right  = value.left_right;  }  
                                    else{ left_right = "N/A"; }   
                                    n3_category_name = value.item+" "+front_rear+" "+left_right;     
                                    span_content = $('<span>' , {class:'li-subchild-toggler'}).text(n3_category_name)
                                    tr = $('<tr>').append(
                                        $('<td>' ,{class:'p-0'}).append( span_content, n3_description, n3_category_status,span )
                                    )
                                    table_body.append(tr)
                                });
                            } 
                            else{
                                tr = $('<tr>').append(
                                        $('<td>' ,{class:'p-0'}).text('No Category Available !!!')
                                    )
                                table_body.append(tr)    
                            }
                            table.append(table_body);
                            td.append(table).removeClass('loading');

                            icon.removeClass('glyphicon-plus').addClass('glyphicon-minus')
                            }
                            else if(n3_response.status == 404){
                                td.removeClass('loading');
                                $("#msg_response_popup").modal('show');
							    $("#msg_response").html('<div class="notice notice-danger"><strong>Note , </strong> No Products item (N3) available   .</div>'); 
                            }
                            else{
                                td.removeClass('loading');
                                $("#msg_response_popup").modal('show');
							    $("#msg_response").html('<div class="notice notice-danger"><strong>Note , </strong> Something Went Wrong , Please try again !!!  .</div>'); 
                            }
                        },
                        error:function(){
                            alert("Somehting Went wrong , please try again !!!")
                             td.removeClass('loading');
                        },
                    });
                }
                },
            error: function(xhr, error){
                $("#preloader").hide();
                $("#preloader").hide();
            },
        });
    }
}); 
 /*End*/  
/*$(document).on('click', '.n3_category_image', function(e){
	e.preventDefault();
	var n3_category_id = $(this).data('n3categoryid');
	show_group_image(n3_category_id);
});*/
    
   /* function show_group_image(n3_category_id){
        if(n3_category_id != ""){
            $("#product_group_item_id").val(n3_category_id);
                $.ajax({
                url: base_url+"/products_ajax/get_n3_group_image",
                method: "GET",
                data: {groupId:n3_category_id},
                success: function(data){
                $('#n3_image_result').html(data);
                $('#add_n3_category_image_popup').modal('show');
                }
        });
        }	
    }*/


   /* $(document).on('submit','#n3_category_images_form',function(e){
		$('#msg_response').html(" ");
		$("#err_response").html(" ");
		var group_id = $("#group_id").val();
		$('#save_n3_group_image').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
		$('#err_response').html(""); 
		e.preventDefault();
        $.ajax({
            url: base_url+"/products_ajax/add_n3_group_images",
            type: "POST",        
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData:false,  
            success: function(data){
            var errorString = '';
            var parseJson = jQuery.parseJSON(data);
            $('#save_n3_group_image').html(' Save &nbsp; <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
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
                    $("#n3_category_images_form")[0].reset();
                }
                if(parseJson.status == 100){
                $("#err_response").html(parseJson.msg);
                }
            }	
        });
 	});
	*/
 /*Expand Groups script start */
    $(document).on('click','.expand_group',function(){
        //$("#preloader").show();
        let td = $(this).closest('td'),
        siblingRows = td.parent().siblings(),
        group_id = $(this).data('groupid'),
        language = $('html').attr('lang'),
        icon = $(this).children('.glyphicon');
        // siblingRows.find('.li-child-toggler.active-parent').removeClass('active-parent').end()
        // .find('table.li-childs, table.li-subchilds').hide().end()
        // .find('.glyphicon').removeClass('glyphicon-minus').addClass('glyphicon-plus').end()
        // .find('.active-parent').removeClass('active-parent')
        if(td.children('table.li-childs').length) {
            let table = td.children('table.li-childs');
            icon.removeClass('glyphicon-minus glyphicon-plus');
            if(table.is(':visible')) {
                table.hide();
                icon.addClass('glyphicon-plus');
                $(this).parent().removeClass('active-parent');
            } else {
                table.show();
                icon.addClass('glyphicon-minus');
                $(this).parent().addClass('active-parent');
            }
        } else {
            $.ajax({
                url:base_url+"/save_sub_groups_04_09",
                method:"GET",
                data:{group_id:group_id ,language:language},
                beforeSend: () => td.addClass('loading'),
                complete:function(e, xhr, settings){
                    if(e.status == 200){
                        $.ajax({
                            url:base_url+"/products_group/get_sub_groups",
                            method:"GET",
                            data:{group_id:group_id ,language:language},
                            complete:function(e, xhr, settings){
                                if(e.status == 200){
                                    parsejson_response = jQuery.parseJSON(e.responseText);
                                    table = $('<table>',{class:'table li-childs', style:'display:table'})
                                    tbody = $('<tbody>');
                                    if(parsejson_response.status == 200){
                                     console.log(parsejson_response.response);
                                        if (parsejson_response.response.length != 0 ){
                                            $.each(parsejson_response.response , function(index , value){
                                                a1 = $('<a>' , {class:'btn btn-danger delete_group btn-sm'}).attr('href' , base_url+'/products/remove_group/'+value.id).append('<i class="fa fa-trash" ></i>').css('margin-left' , '10px')
                                                a2 = $('<a>' , {class:'btn btn-primary add_group_image_btn btn-sm'}).attr('href' , base_url+'/products/remove_group/'+value.id).data('groupid' , value.id).append('<i class="fa fa-picture-o"></i>').css('margin-left' , '10px')
                                                a3 = $('<a>' , {class:'btn btn-primary edit_n2_category btn-sm'}).attr('href' , '#').data('n2categoryid' , value.id).append('<i class="fa fa-edit"></i>').css('margin-left' , '10px')
                                                if(value.description == null) {
                                                    description = $('<span>' , {class:'right-description'}).text("N/A")
                                                } else {
                                                    description = $('<span>' , {class:'right-description'}).text(value.description)
                                                }
                                                if(value.status == "A") {
                                                    group_status = $('<a>' , {class:' change_group_status'}).attr('href' , '#').data('groupid' , value.id).data('status' , 'P').append('<i class="fa fa-toggle-on"></i>')
                                                } else {
                                                    group_status = $('<a>' , {class:' change_group_status'}).attr('href' , '#').data('groupid' , value.id).data('status' , 'A').append('<i class="fa fa-toggle-off"></i>')
                                                }
                                                
                                                span = $('<span>' , {class:'right-action-btns'}).append(a1 , a2 , a3 ) 
                                                sub_group_status = $('<span>' , {class:'right-status'}).append(group_status ) 
                                                collapse_button = $('<span>' , {class:'expand_sub_groups'}).append('<i class="glyphicon glyphicon-plus"></i>').data('subgroupid' , value.id) ,     
                                                span_content = $('<span>' , {class:'li-subchild-toggler'}).text(value.group_name).prepend(collapse_button)   
                                                tr = $('<tr>').append(
                                                        $('<td>' , {class:'p-0'}).append( span_content,description, sub_group_status, span)
                                                    )
                                                tbody.append(tr)
                                            });
                                        }
                                        else {
                                            tr = $('<tr>').append(
                                                $('<td>').text('No category available !!!')
                                            )
                                        }
                                        td.append(table.append(tbody)).removeClass('loading');
                                       icon.removeClass('glyphicon-plus').addClass('glyphicon-minus') 
                                    }  
                                    else if(parsejson_response.status == 100){
                                          td.removeClass('loading');
                                          $("#msg_response_popup").modal('show');
							              $("#msg_response").html('<div class="notice notice-danger"><strong>Note , </strong> No Sub category (N2) available   .</div>'); 
                                    }
                                    else if(parsejson_response.status == 404){
                                          td.removeClass('loading');
                                          $("#msg_response_popup").modal('show');
							              $("#msg_response").html(parsejson_response.msg); 
                                    }
                                    else{
                                        td.removeClass('loading');
                                        $("#msg_response_popup").modal('show');
                                        $("#msg_response").html('<div class="notice notice-danger"><strong>Note , </strong> Something Went Wrong , Please try again !!!  .</div>'); 
                                    }
                                   
                                }
                            },
                            error: function(xhr, error){
                                $("#preloader").hide();
                            }   
                        });
                    }
                    },
                error: function(xhr, error){
                    $("#preloader").hide();
                }
            });
        }
    });
/*End */
});
</script>
<script type="text/javascript">
    $(function() {
        // $('.li-child-toggler .glyphicon').on('click', function() {
        //     if ($(this).closest('.li-child-toggler').next('.li-childs').is(':visible'))
        //         return false;
        //     $('.main-table').find('.glyphicon').removeClass('glyphicon-minus').addClass('glyphicon-plus');
        //     $('.main-table').find('.li-child-toggler').removeClass('active-parent');
        //     $('.main-table').find('.li-childs').slideUp();

        //     $('.main-table').find('.li-subchilds').slideUp();

        //     $(this).toggleClass('glyphicon-minus glyphicon-plus');
        //     $(this).closest('.li-child-toggler').addClass('active-parent');

        //     $(this).closest('.li-child-toggler').next('.li-childs').delay(300).slideToggle();

        // })

        $('.li-subchild-toggler .glyphicon').on('click', function() {

            if ($(this).closest('.li-subchild-toggler').next('.li-subchilds').is(':visible'))

                return false;

            $('.main-table').find('.glyphicon').removeClass('glyphicon-minus').addClass('glyphicon-plus');
            $('.main-table').find('.li-child-toggler').removeClass('active-parent');
            $(this).closest('.li-childs').find('.li-subchilds').slideUp();

            $(this).closest('.li-parent').find('.li-child-toggler .glyphicon').addClass('glyphicon-minus').removeClass('glyphicon-plus');
            $(this).closest('.li-parent').find('.li-child-toggler').addClass('active-parent');
            $(this).addClass('glyphicon-minus').removeClass('glyphicon-plus');

            $(this).closest('.li-subchild-toggler').next('.li-subchilds').delay(300).slideToggle();

        })

    })
</script>
<script>
    $(document).ready(function(e) {

        $('[data-toggle="tooltip"]').tooltip();
        
        $(document).on('click', '.delete_n3_category', function(e){
            e.preventDefault();
            var n3_category_id = $(this).data('n3categoryid');
            var url = base_url + "/products/remove_n3_category/"+n3_category_id;
            var con = confirm('Are you sure want to delete ?');
            if(con == true) {
                window.location.href = url;
            } else {
                return false ;
            }
        });
		
        $(document).on('click', '.edit_n2_category', function(e){
			e.preventDefault();
			var n2_category_id = $(this).data('n2categoryid');
            if(n2_category_id) {
                $.ajax({
                    url: base_url+"/products_ajax/get_n2_category_details",
                    method: "GET",
                    data: {n2_category_id:n2_category_id},
                    success: function(data){
                        var parseJson = jQuery.parseJSON(data);
                        console.log(parseJson.n1_group_id);
						 console.log(parseJson.n1_id);
						 console.log(parseJson);
						if(parseJson.status == 200){
                            if(parseJson.response.products_groups_group_id == 0 || parseJson.response.products_groups_group_id == null){
                                console.log(parseJson.n1_group_id);
                                console.log(parseJson.n1_id);
                               if(parseJson.n1_group_id == null){
								   $('#edit_n2_category_form #groups').find("option[value='"+ parseJson.n1_id +"']").attr('selected','selected'); 
								 }
							   if(parseJson.n1_group_id == null){
								  $('#edit_n2_category_form #groups').find("option[data-groupid='"+ parseJson.n1_group_id +"']").attr('selected','selected');  
								 }	 
                            } else {
                                $('#edit_n2_category_form #groups').find("option[data-groupid='"+ parseJson.response.products_groups_group_id +"']").attr('selected','selected');
                            }
                            if(parseJson.response.type == 2){
                                $('#display_service_div').show();
                                $('#edit_n2_category_form #edit_service_group').find("option[value='"+parseJson.service_group+"']").attr('selected','selected'); 
                            } else {
                                $('#display_service_div').hide();
                                $('#edit_n2_category_form #edit_service_group').find("option[value='0']").attr('selected',false); 
                            }
                            $('#edit_n2_category_id').val(parseJson.response.id);
                            $("#edit_sub_group_name").val(parseJson.response.group_name);
                            $("#edit_n2_description").val(parseJson.description);
                            $("#edit_n2_priority").val(parseJson.priority);
							$("#edit_n2_category_details").modal({
                                backdrop: 'static',
                                keyboard: false,
                            });
                        }
                    }
                });
            }
        });

        $(document).on('submit', '#edit_n2_category_form', function(e){
            $('#msg_response').html(" ");
            $("#err_response").html(" ");
            $('#edit_n2_category_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
            e.preventDefault();
            $.ajax({
                url: base_url+"/products_ajax/edit_n2_category_details",
                type: "POST",        
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData:false,  
                success: function(data){
                    var errorString = '';
                    var parseJson = jQuery.parseJSON(data);
                    $('#edit_n2_category_btn').html(' Save &nbsp; <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
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
        $('#category_list tbody tr').each(function() {
            $(this).find('td').eq(4).on('dblclick', function() {
                $(this).prop('contenteditable', true);
            });
            $(this).find('td').eq(4).on('blur', function() {
                $(this).prop('contenteditable', false);
                var category_name = $(this).text();
                var category_id = $(this).parent().find('.category_id').val();
                $.ajax({
                    url: "<?php echo url("product/edit_group_name "); ?>",
                    type: 'GET',
                    data: {
                        categoryId: category_id,
                        categoryName: category_name
                    },
                });

            })

        });
       
        $(document).on('click', '#add_group', function(e) {
		  e.preventDefault();
          $("#add_group_modal_popup").modal({
			backdrop: 'static',
			keyboard: false,
		   });
        });
        /*Get N2 behalf n1 and version*/

        /*$(document).on('click','.shown2',function(){

   	     var table_row = $(this);

   		 varsion = $(this).data('version'); 

   		 categoryId = $(this).data('groupid'); 

   		  $.ajax({

                   url: "<?php echo url("product_new/get_sub_category"); ?>",

                   type: 'GET',

                   data: {varsion:varsion,categoryId:categoryId},

   				success: function(data){

   				   table_row.append(data).after();

   				}

               });

   	  });*/
        /*End*/
        

      

        $(document).on('click', '.edit_sub_group_details', function(e) {
            e.preventDefault();
            $this = $(this);
            var sub_category_id = $(this).data('groupid');
            var category_type = $(this).data('categorytype');
            var category_name = $(this).data('categoryname');
            var description = $(this).data('description');
            $('#category_id').val(sub_category_id);
            $('#category_type').val(category_type);
            $('#edit_group_name').val(category_name);
            $('#category_description').val(description);
            $("#view_sub_category").modal('hide');
            $("#edit_category_detils").modal({
                backdrop: 'static',
                keyboard: false,
            });
        });
        $(document).on('click', '.change_group_status', function(e){
            e.preventDefault();
            var $this = $(this);
            var status = $(this).data('status');
            var group_id = $(this).data('groupid');
            var con = confirm("Are you sure want to Change Status ?");
            if(con == true) {
                $.ajax({
                    url: base_url+"/product/change_product_group_status",
                    type: "GET",        
                    data:{status:status , group_id:group_id},
                    success: function(data){
                        if(status == 'P'){
                            $this.html(" <i class='fa fa-toggle-off'></i>").data('status' , 'A');
                        }
                        if(status == 'A'){
                            $this.html(" <i class='fa fa-toggle-on'></i>").data('status' , 'P');
                        } 
                    }
                });
            } else {
                return false;
            }
        });
        $(document).on('click', '.change_n3_category_status', function(e){
            e.preventDefault();
            var $this = $(this);
            var status = $(this).data('status');
            var group_item_id = $(this).data('n3categoryid');
            var con = confirm("Are you sure want to Change Status ?");
            if(con == true) {
                $.ajax({
                    url: base_url+"/product/change_group_item_status",
                    type: "GET",        
                    data:{status:status , item_id:group_item_id},
                    success: function(data){
                        if(status == 'P'){
                            $this.html(" <i class='fa fa-toggle-off'></i>").data('status' , 'A');
                        }
                        if(status == 'A'){
                            $this.html(" <i class='fa fa-toggle-on'></i>").data('status' , 'P');
                        } 
                    }
                });
            } else {
                return false;
            }
        });
    });
</script>
<script src="{{ asset('validateJS/products_groups.js') }}"></script>
<script src="{{ url('validateJS/products.js') }}"></script>
<script src="{{ url('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ url('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>
@endpush