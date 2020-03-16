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
        <a href="javascript::void();" class="btn btn-warning" id="import_export_tyre_sample_format" style="color:white;">Import / Export Tyres&nbsp;<span class="glyphicon glyphicon-plus"></span></a>&nbsp;&nbsp;&nbsp;
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
                        <div class="col-sm-6">
                            <div class="form-group">
                                <select class="form-control" name="with_measurement" id="with_measurement">
                                <option value="0">--select--width--</option>
                                    @forelse($tyre_measurement['width'] as $w)
                                    <option value="@if(!empty($w->value)){{ $w->value }} @endif">@if(!empty($w->value)){{ $w->value }} @endif</option>
                                    @empty
                                     <option value="0">No record found !!!</option>
                                    @endforelse 
                                </select>                                
                            </div> 
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <select class="form-control" name="aspect_ratio_measurement" id="aspect_ratio_measurement">
                               <option value="0">--select--aspect--ratio--</option>
                                    @forelse($tyre_measurement['aspect_ratio'] as $ar)
                                    <option value="@if(!empty($ar->value)){{ $ar->value }} @endif">@if(!empty($ar->value)){{ $ar->value }} @endif</option>
                                    @empty
                                    <option value="0">No record found !!!</option> 
                                    @endforelse 
                                </select>                                
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <select class="form-control" name="diameter_measurement" id="diameter_measurement">
                               <option value="0">--select--diameter--ratio--</option>
                                    @forelse($tyre_measurement['diameter'] as $d)
                                    <option value="@if(!empty($d->value)){{ $d->value }} @endif">@if(!empty($d->value)){{ $d->value }} @endif</option>
                                    @empty
                                    <option value="0">No record found !!!</option>
                                    @endforelse 
                                    
                                </select>                                
                            </div> 
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                            <a href='javascript::void()' id="search_tyre_from_database" class="btn btn-warning" style="color:white;">Search &nbsp;<span class="glyphicon glyphicon-search"></span></a>         
                            </div>

                            <div class="form-group">
                            </div>
                        </div>
                    </div>
					<hr />
                     <div class="row">
							<div class="col-sm-6">
                                   <select name="tyre_type" class="form-control" id="tyre_load_index">
                                    <option value="" hidden="hidden">--Select--Speed--Index--</option>
                                     <?php 
									   foreach($tyre_measurement['speed_index'] as $s_index){
										     ?>
											<option value="<?php if(!empty($s_index['name'])) echo $s_index['name']; ?>"><?php if(!empty($s_index['name'])) echo $s_index['name']; ?></option>
											 <?php
										  }
									 ?>
                                   </select>                          
							</div>
                            <div class="col-sm-6">
                                <select name="tyre_type" class="form-control" id="tyre_type_value">
                                    <option value="" hidden="hidden">--Select--Tyre--Type--</option>
                                    <?php foreach($tyre_measurement['tyre_type'] as $t_type){ ?>
                                        <option value="<?php if(!empty($t_type['id'])) echo $t_type['id']; ?>"><?php if(!empty($t_type['name'])) echo $t_type['name']; ?></option>
                                    <?php } ?>
                                </select>                          
							</div>	
				    </div>
                    <div class="row" style="margin-top:15px;">
					  <div class="col-sm-6">
							 <a href='javascript::void()' id="search_tyre_load_index_from_database" class="btn btn-warning" style="color:white;">Search &nbsp;<span class="glyphicon glyphicon-search"></span></a> 
							</div>
				    </div>
                    
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
	<div class="card-body" id="tire_ColWrap" style="overflow: auto;">
        @include('tyre.component.tire_list') 
       <div class="row" style="margin-top:10px;">
          <div class="col-sm-12">
             {{ $get_tyres_list->links() }}
          </div>
        </div>
    </div>
</div>
<!--Import Sample Format-->
<div class="modal" id="import_export_tyre_sample_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Import / Export Tyre</h4>
                <hr />
            </div>
            <div class="card-body">
                <a href="<?php echo url('export/tire_list') ?>" class="btn btn-warning"><i class="fa fa-download"></i>&nbsp;Export Sample Excel For Tyre</a>
                <hr />
                <h3 style="font-weight:600;">Import Excel Files </h3> 
                <form id="import_tire_file" name="import_tire_file">
                @csrf
                   <span id="tyre_msg_response"></span>
                  <div class="control-group" id="fields">
                        <label class="control-label" for="field1">
                            Browse Files
                        </label>
                        <div class="controls">
                            <div class="entry input-group col-xs-3">
                                <input class="btn btn-primary" name="tire_file"  id="tire_file" type="file"  accept=".csv" required>
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
<!--Export All data modal popup start--->
<div class="modal" id="export_tyre_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Export tyre in csv file </h4>
                <hr />
            </div>
            <div class="card-body">
                <a href="<?php echo url('export/tire_list') ?>" class="btn btn-warning"><i class="fa fa-download"></i>&nbsp;Export Excel For Tyre</a>
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
  $(document).on('click','.tyreinfo',function(e){
    tyre = $(this);
	tyre_value = tyre.data('tyreid');
	tyre.html('<i class="icon-spinner2 spinner"></i> Please wait ');
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



