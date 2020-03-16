@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
@if(session::has('msg'))
  {!! Session::get('msg') !!}
@endif
<style> .container{ padding:15px;} </style>
<div class="row" style="margin-bottom:10px;">
    <div class="col-sm-12">
    <a href="javascript::void();" class="btn btn-warning" id="export_rims" style="color:white;">Export Rim Files&nbsp;<span class="fa fa-file-excel-o"></span></a>
    &nbsp;&nbsp;&nbsp;
    <a href="javascript::void();" class="btn btn-warning" id="import_export_rim_sample_format" style="color:white;">Import / Export Rim Files&nbsp;<span class="fa fa-file-excel-o"></span></a>&nbsp;&nbsp;&nbsp;
    </div>
</div>
<div class="card" style="margin-bottom:10px;" >
    <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-filter"></i>&nbsp;@lang('messages.Filter')</h6>
    </div>
    <div class="content">
	    <div id="filter-panel">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <select class="form-control" name="car_makers" id="car_makers">
                                <option value="0">@lang('messages.selectMaker')</option>
                                    @foreach($cars__makers_category as $makers)
                                    <option value="@if(!empty($makers->idMarca)){{ $makers->idMarca }} @endif">@if(!empty($makers->Marca)){{ $makers->Marca }} @endif</option>

                                    @endforeach 
                                </select>                                
                            </div> 
                        </div>
                        <!--<div class="col-sm-6">
                            <div class="form-group">
                                <select class="form-control" name="rim_type" id="rim_type">
                                    <option value="0">@lang('messages.firstSelectMakers')</option>
                                </select>                                
                            </div>
                        </div>-->
                    </div>
                    <!--<div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <select class="form-control" name="rim_workship_rim_type" id="rim_workship_rim_type">
                                    <option value="0">Select Rim Workship Rim Type </option>
                                </select>                                
                            </div> 
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <select class="form-control" name="rim_type" id="rim_type">
                                    <option value="0">@lang('messages.firstSelectMakers')</option>
                                </select>                                
                            </div>
                        </div>
                    </div>-->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                            <a href='javascript::void()' id="search_rim_from_database" class="btn btn-warning" style="color:white;">Search Rim &nbsp;<span class="glyphicon glyphicon-search"></span></a>         
                            </div>
                            <div class="form-group">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>  
    </div>
</div>
<div class="card">
<div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;Rim  List</h6>
          <!-- <a href='#' class="btn btn-primary" id="add_new_service" style="color:white;">Add New Service &nbsp;<span class="glyphicon glyphicon-plus"></span></a> -->
        </div>
	<div class="card-body" id="rim_ColWrap" style="overflow:auto;">
       @include('rim.component.rim_list') 
       <div class="row" style="margin-top:10px;">
          <div class="col-sm-12">
             {{ $get_rims->links() }}
          </div>
        </div>
    </div>
</div>
<!--Import Sample Format-->
<div class="modal" id="import_export_rim_sample_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Import / Export Rim</h4>
                <hr />
            </div>
            <div class="card-body">
                <a href="<?php echo url('export/rim_list_sample') ?>" class="btn btn-warning"><i class="fa fa-download"></i>&nbsp;Export Sample Excel File For Rim</a>
                <hr />
                <h3 style="font-weight:600;">Import Rim Excel Files </h3> 
                <form id="import_rim_file" name="import_rim_file">
                @csrf
                  <div class="control-group" id="fields">
                        <span id="rim_msg_response"></span>
                        <label class="control-label" for="field1">
                            Browse Rim Excel Files
                        </label>
                        <div class="controls">
                            <div class="entry input-group col-xs-3">
                                <input class="btn btn-primary" name="tire_file" type="file"  accept=".csv" required>
                                <span class="input-group-btn">
                        &nbsp;&nbsp;
                        <button class="btn btn-success btn-add" type="submit" id="rim_import">
                            Import Rim File
                            <span class="glyphicon glyphicon-import"></span>
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
<div class="modal" id="export_rim_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Export Rim in csv file </h4>
                <hr />
            </div>
            <div class="card-body">
                <a href="<?php echo url('export/rim_list') ?>" class="btn btn-warning"><i class="fa fa-download"></i>&nbsp;Export Excel For Rim</a>
            </div>
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
            <a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
            <a href="{{ url('/') }}" class="breadcrumb-item">Admin </a>
            <span class="breadcrumb-item active">Rim Management</span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>

</div>
@stop
@push('scripts')
<link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" />
<script src="{{ url('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ url('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>
<script src="{{ url('validateJS/rim_management.js') }}"></script>
<script src="{{ asset('validateJS/import_export.js') }}"></script>
<script>
$(document).ready(function(e) {
  $(document).on('click','.riminfo',function(e){
    tyre = $(this);
	rim_id = tyre.data('rimid');
	//tyre.html('<i class="icon-spinner2 spinner"></i> Please wait ');
	  if(rim != ""){
		 $.ajax({
			url: base_url+"/rim_ajax/get_rim_info",
			method: "GET",
			data: {rim:rim_id},
			success: function(data){
			  //tyre.html('<span class="fa fa-info-circle">&nbsp;Info');
			  $('#tyre_response').html(data);
			  $('#tyre_detail_modal').modal({
				  backdrop:'static',
				  keyboard:false,
			  });
			  
			}
	     }); 
		}
	  	
  });  
});
</script>
@endpush



