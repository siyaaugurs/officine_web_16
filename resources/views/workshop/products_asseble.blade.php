@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
    <!-- Page length options -->
    @if(Session::has('msg'))
      {!! session::get('msg') !!}
    @endif
    <div class="card">
        <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;@lang('messages.AssembleProductsList')</h6>
        </div>
        
       <div class="card-body">
        <form  action="{{ url('workshop/products_asseble') }}" method="POST">
          @csrf
          <div class="form-group">
				<button type="submit" class="btn btn-success" style="float:;" >Save&nbsp; <span class="glyphicon glyphicon-plus"></span></button>
                                   &nbsp;
				<a href='{{ url("workshop/products_assemble_list") }}' class="btn btn-primary" style="color:white;" id="view_all_group">View all &nbsp;<span class="glyphicon glyphicon-list"></span></a>
</div>
        @include('workshop.component.products_asseble' , ['products'=>$products])
    </form> 
       </div>
    </div>
    <!-- /page length options -->
<div class="modal" id="products_assemble_details_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Products Details</h4>
                <hr />
            </div>
            <!-- Modal body -->
            <div id="products_response"></div>
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
            <a href="{{ url('/') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
            <a href="#" class="breadcrumb-item">Admin </a>
            <a href="#" class="breadcrumb-item">Products </a>
            <span class="breadcrumb-item active">Assemble Products List </span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop
@push('scripts')
<script>
$(document).ready(function(e){
   $(document).on('click','#select_all',function(){
       if( $(this).is(':checked') ){
           $('.products').prop('checked' , true);
       }
       else{
          $('.products').prop('checked' , false); 
       }
   });
});
    
</script>
<script src="{{ asset('validateJS/admin.js') }}"></script>
<script src="{{ url('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ url('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>
@endpush


