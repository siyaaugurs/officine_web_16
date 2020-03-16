@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
@if(session::has('msg'))
  {!! Session::get('msg') !!}
@endif
<style> .container{ padding:15px;} </style>
    <div class="row" style="margin-bottom:10px;">
    <div class="col-sm-12">
        <!-- <a href="javascript::void();" class="btn btn-warning" id="export_tyres" style="color:white;">Export Tyre Files&nbsp;<span class="glyphicon glyphicon-plus"></span></a>&nbsp;&nbsp;&nbsp; -->
        <a href="javascript::void();" class="btn btn-warning" id="import_export_custom_tyre_format" style="color:white;">Import / Export Custom Tyres&nbsp;<span class="glyphicon glyphicon-plus"></span></a>&nbsp;&nbsp;&nbsp;
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
                    <!--<div class="row">
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
                                <select class="form-control" name="car_models" id="car_models">                                    <option value="0">@lang('messages.firstSelectMakers')</option>
                                </select>                                
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                            <a href='#' id="search_tyre_from_database" class="btn btn-warning" style="color:white;">Search &nbsp;<span class="glyphicon glyphicon-search"></span></a>         
                            </div>

                            <div class="form-group">
                            </div>
                        </div>
                    </div>
					 <div class="row">
							<div class="col-sm-6">
                                   <select name="tyre_type" class="form-control">
                                    <option value="0">--Select--Speed--Index--</option>
                                     <?php 
									   foreach($speed_index as $s_index){
										     ?>
											<option value="<?php if(!empty($s_index['name'])) echo $s_index['name']; ?>"><?php if(!empty($s_index['code'])) echo $s_index['code']; ?></option>
											 <?php
										  }
									 ?>
                                   </select>                          
							</div>
                            <div class="col-sm-6">
                                   <select name="tyre_type" class="form-control">
                                    <option value="0">--Select--Tyre--Type--</option>
                                     <?php 
									   foreach($tyre_type as $t_type){
										     ?>
											<option value="<?php if(!empty($t_type['name'])) echo $t_type['name']; ?>"><?php if(!empty($t_type['name'])) echo $t_type['name']; ?></option>
											 <?php
										  }
									 ?>
                                   </select>                          
							</div>	
				    </div>
                    <div class="row" style="margin-top:15px;">
					  <div class="col-sm-6">
							 <a href='javascript::void()' id="search_tyre_en_number_from_database" class="btn btn-warning" style="color:white;">Search &nbsp;<span class="glyphicon glyphicon-search"></span></a> 
							</div>
				    </div>
                    -->
                     <div class="row" style="margin-top:15px;">
							<div class="col-sm-6">
								<div class="form-group">
                               <input type="text" class="form-control" name="ean_number" placeholder="EAN NUMBER" id="ean_number">                               
								</div>
							</div>	
							<div class="col-sm-6">
							 <a href='javascript::void()' id="search_tyre_en_number_from_database" class="btn btn-warning" style="color:white;">Search &nbsp;<span class="glyphicon glyphicon-search"></span></a> 
							</div>		
                    </div>
                </div>
            </div>
        </div>  
    </div>
</div>
<div class="card">
<div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;Tyre List</h6>
        </div>
	<div class="card-body" id="tire_ColWrap" style="overflow:auto;">
         @include('tyre.component.custom_tire') 
       <div class="row" style="margin-top:10px;">
          <div class="col-sm-12">
             {{-- $get_tyres_list->links() --}}
          </div>
        </div>
    </div>
</div>
<!--Import Sample Format-->
<div class="modal" id="import_export_custom_tyre_sample_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Import / Export Tyre</h4>
                <hr />
            </div>
            <div class="card-body">
                <a href="<?php echo url('export/list_of_custom_tire') ?>" class="btn btn-warning"><i class="fa fa-download"></i>&nbsp;Export Custom Tyre List</a>
                <hr />
                <h3 style="font-weight:600;">Import Excel Files </h3> 
                <form id="import_custom_tire_file" name="import_tire_file">
                @csrf
                   <span id="tyre_msg_response"></span>
                  <div class="control-group" id="fields">
                        <label class="control-label" for="field1">
                            Browse Files
                        </label>
                        <div class="controls">
                            <div class="entry input-group col-xs-3">
                                <input class="btn btn-primary" name="custom_tire_file"  id="tire_file" type="file"  accept=".csv" required>
                                <span class="input-group-btn">
                        &nbsp;&nbsp;
                        <button class="btn btn-success btn-add" type="submit" id="import_tyre">
                        Import  Tyre
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
<!--Tyre Details info script Start-->
<div class="modal" id="tyre_detail_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Products Details</h4>
                <hr />
            </div>
            <!-- Modal body -->
            <div id="tyre_response"></div>
        </div>
        <div class="modal-footer">       
        </div>
    </div>
</div>
<!---End-->
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
            <a href="{{ url('/') }}" class="breadcrumb-item">Admin </a>

            <span class="breadcrumb-item active">Tyre Management</span>

        </div>

        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>

    </div>

</div>
@stop
@push('scripts')
<link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" />
<script src="{{ url('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ url('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>
<script src="{{ url('validateJS/tyre_custom.js') }}"></script>
<script src="{{ asset('validateJS/import_export.js') }}"></script>
<script>
$(document).ready(function(e) {
	/*Import Custom tire files start*/
	  $(document).on('submit', '#import_custom_tire_file', function(e) {
       // $('#import_file_response').html(" ");
       // $('#import_tyre').html('Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled', true);
        e.preventDefault();
        $.ajax({
            url: base_url + "/import/import_custom_tire",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            complete: function(e, xhr, setting) {
               // $('#import_tyre').html('Import <span class="glyphicon glyphicon-import"></span>').attr('disabled', false);
               //  if (e.status == 200) {
               //      $("#tyre_msg_response").html('<div class="notice notice-success"><strong> Success , </strong>Record Import Successfully .</div>');
               //      setTimeout(function() { location.reload(); }, 1000);
               //  }
            }
        });
    });
	/*End*/
	/*Import Export tyre custom */
	  $(document).on('click', '#import_export_custom_tyre_format', function() {
        $("#import_export_custom_tyre_sample_modal").modal({
            backdrop: 'static',
            keyboard: false,
        });
    });
	/*End*/
  $(document).on('click','.tyreinfo',function(e){
    tyre = $(this);
	tyre_value = tyre.data('tyreid');
	tyre.html('<i class="icon-spinner2 spinner"></i> ');
	  if(tyre_value != ""){
		 $.ajax({
			url: base_url+"/tyre24_ajax/get_tyre_info",
			method: "GET",
			data: {tyre_value:tyre_value},
			success: function(data){
			  tyre.html('<span class="fa fa-info-circle">&nbsp;Info');
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



