@extends('layouts.master_layouts')
@section('content')
<!-- Page length options -->
@if(Session::has('msg'))
  {!! session::get('msg') !!}
@endif
<input type="hidden" name="page" id="page" value="{{ $page }}" />
@if($edit_condition->count() == 0 )
<div class="card" id="add_maintenance_special_condition" >
    <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="icon-cart"></i>&nbsp;&nbsp;User Policy</h6>
    </div>
	<div class="card-body">
        <form id="" action="{{ url('user_policy/terms_condition')}}" method="post" autocomplete="off">
            @csrf
            <div class="row">
				<input type="hidden" name="id" id="id" value="">
              <div class="col-sm-12">
                <div class="form-group">
				<label>Title&nbsp;<span class="text-danger">*</span></label>
                       <input type="text" name="title" id="title" class="form-control">                      
                </div> 
              </div>
			  </div>
			 <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  	<label>Discription&nbsp;<span class="text-danger">*</span></label> 
						<textarea class="textarea" name="terms_conditions_detail" id="terms_conditions_detail"></textarea>
                </div>
				</div>
			   </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" id="" class="btn bg-blue ml-3">Submit <i class="icon-paperplane ml-2"></i></button>
                    </div>
                </div>
        </form>
	</div>
</div>
<div class="card" id="history_special_condition" >
<div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;All Terms Conditions</h6>
    </div>
	<div class="card-body" style="overflow: auto">
		<table class="table table-bordered">
            <thead>
               <tr>
                    <th colspan="9"></th>
                </tr>
                <tr>
                    <th>@lang('messages.SN')</th>
                    <th>title</th>
                    <th>@lang('messages.decription')</th>
                    <th class="text-center">@lang('messages.Actions')</th>
                </tr>
            </thead>
            <tbody>
			@forelse($terms_condition as $terms_conditions)
			<tr>
			<td>{{ $terms_conditions->id}}</td>
			<td>{{ $terms_conditions->title}}</td>
			<td>{{ strip_tags($terms_conditions->terms_conditions_detail)}}</td>
			<td><a href="{{url("user_policy/terms_condition/$terms_conditions->id")}}" class="btn btn-primary edit_pfu"> <i class="fa fa-edit"></i> </a></td>
			</tr>
			@empty
			<tr>
			   <td colspan="6">@lang('messages.NoRecordFound')</td>
			</tr>
			@endforelse
            </tbody>
        </table>
	</div>
</div>
@endif
@if($edit_condition->count() > 0)
<div class="card" id="add_maintenance_special_condition" >
    <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="icon-cart"></i>&nbsp;&nbsp;TERMS AND CONDITIONS</h6>
    </div>
	<div class="card-body">
        <form id="" action="{{ url('user_policy/terms_condition')}}" method="post" autocomplete="off">
            @csrf
			@foreach($edit_condition as $edit_conditions)
			<input type="hidden" name="id" id="id" value="{{$edit_conditions->id}}">
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">

				<label>Title&nbsp;<span class="text-danger">*</span></label>
                       <input type="text" name="title" value="{{$edit_conditions->title}}" id="title" class="form-control">                      
                </div> 
              </div>
			  </div>
			 <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  	<label>Discription&nbsp;<span class="text-danger">*</span></label> 
						<textarea class="textarea" value="{{$edit_conditions->terms_conditions_detail}}" name="terms_conditions_detail" id="terms_conditions_detail">{{$edit_conditions->terms_conditions_detail}}</textarea>
                </div>
				</div>
			   </div>
			   @endforeach
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" id="" class="btn bg-blue ml-3">Submit <i class="icon-paperplane ml-2"></i></button>
                    </div>
                </div>
        </form>
	</div>
</div>
@endif




<div class="modal fade" id="modalRegisterForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h4 class="modal-title  bg-blue font-weight-bold">Terms And Conditions</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
	  <div class="modal-body mx-3">
	     <input type="text" name="id" id="id" class="form-control">     
              <div class="md-form mb-4">
                <div class="form-group">
				<label>Title&nbsp;<span class="text-danger">*</span></label>
                       <input type="text" name="title" id="title" class="form-control">                      
                </div> 
              </div>
             <div class="md-form mb-4">
                <div class="form-group">
                  	<label>Discription&nbsp;<span class="text-danger">*</span></label> 
						<textarea class="textarea" name="terms_conditions_detail" id="terms_conditions_detail"></textarea>
                </div>
				</div>
				</div>
      <div class="modal-footer d-flex justify-content-center">
             <button type="submit" id="" class="btn bg-blue ml-3">Submit <i class="icon-paperplane ml-2"></i></button>
      </div>
    </div>
  </div>
</div>


  <script src="{{ url('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
    <script src="{{ url('/vendor/unisharp/laravel-ckeditor/adapters/jquery.js') }}"></script>
    <script>
        $('textarea').ckeditor();
         $('.textarea').ckeditor(); // if class is prefered.
		  
    </script>

@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
            <a href="{{ url('/') }}" class="breadcrumb-item">Admin </a>
            <span class="breadcrumb-item active"> User Policy </span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop
@push('scripts')
<script src="{{ url('global_assets/js/demo_pages/form_multiselect.js') }}"></script>
<script src="{{ url('global_assets/js/plugins/forms/selects/bootstrap_multiselect.js') }}">
</script>
<script src="{{ asset('validateJS/special_conditions_cars.js') }}"></script>
<script src="{{ url('validateJS/vendors.js') }}"></script>
<script src="{{ url('validateJS/special_condition.js') }}"></script>
<script src="{{ url('validateJS/date/jquery-ui.css') }}"></script>
<script src="{{ url('validateJS/date/jquery-ui.js') }}"></script>

@endpush