@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="content">
    <!-- Page length options -->
    @if(Session::has('msg'))
      {!! session::get('msg') !!}
    @endif

<div class="card" style="margin-bottom:10px;">
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
                                <select class="form-control" name="main_cat" id="main_cat">
                                    <option value="0">@lang('messages.allServices')</option>
                                    @foreach($spare_groups as $spare_group)
                                    <option value="@if(!empty($spare_group->id)){{ $spare_group->id }} @endif">@if(!empty($spare_group->main_cat_name)){{ $spare_group->main_cat_name }} @endif</option>
                                    @endforeach 
                                </select>                                
                            </div> 
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                            <a href='#' id="search_spare_group_item" class="btn btn-warning" style="color:white;">Search &nbsp;<span class="glyphicon glyphicon-search"></span></a>                                 
                            </div>   
                        </div>
                    </div>
                </div>
            </div>
        </div>  
    </div>
</div>
    <div class="card">
    <form id="spare_groups_form">
        <div class="card-header bg-light header-elements-inline">
            <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;@lang('messages.SapreItemsList')</h6>
              <button type="button" class="btn btn-danger" id="de_selected_group" style="color:white; float:right;">Remove &nbsp;<span class="glyphicon glyphicon-trash"></span></button>
        </div>
       
        <form> 
    <div class="card"  id="user_data_body" style="overflow:auto">
            @include('admin.component.list_spare_items') 
           
        </div>
    </div>
    <!-- /page length options -->
</div>
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="{{ url('/') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>@lang('messages.Home')</a>
            <a href="{{ url('/') }}" class="breadcrumb-item">@lang('messages.Admin')</a>
            <a href="#" class="breadcrumb-item">@lang('messages.SpareGroups') </a>
            <span class="breadcrumb-item active"> @lang('messages.SapreItemsList') </span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop
@push('scripts')
<script src="{{ url('validateJS/admin.js') }}"></script>
<script src="{{ url('validateJS/spare_groups.js') }}"></script>
<script src="{{ url('validateJS/products.js') }}"></script>
<script src="{{ url('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script>
$(document).ready(function() {

    var table = $('#example').DataTable({ 
      'columnDefs': [{
         'targets': 0,
         'searchable':false,
         'orderable':false,
         'className': 'dt-body-center',
        //  'render': function (data, type, full, meta){
        //      return '<input type="checkbox" name="id[]" value="' 
        //         + $('<div/>').text(data).html() + '">';
        //  }
      }],
      'order': [1, 'asc']
   });

   // Handle click on "Select all" control
   $('#all_select').on('click', function(){
      // Check/uncheck all checkboxes in the table
      var rows = table.rows({ 'search': 'applied' }).nodes();
      $('input[type="checkbox"]', rows).prop('checked', this.checked);
   });


    // $('#example').DataTable();


} );
</script>

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css"></script>
<script src="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css"></script> -->

@endpush



