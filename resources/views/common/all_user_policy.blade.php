@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
@if($edit_condition->count() == 0)
<div class="card" id="history_special_condition" >
<div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;All User Policy</h6>
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
			<td><a href="{{url("user_policies/all_user_policy/$terms_conditions->id")}}" class="btn btn-primary edit_pfu"> <i class="fa fa-eye"></i> </a></td>
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
	@foreach($edit_condition as $edit_conditions)
		<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">{{ucfirst($edit_conditions->title)}}</span> </h4>
		<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
	<div class="card-body">
            @csrf
			<input type="hidden" name="id" id="id" value="{{$edit_conditions->id}}">
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group" style="font-size: 20px;">
                      <strong>{{ucfirst($edit_conditions->title)}} </strong>               
                </div> 
              </div>
			  </div>
			 <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
						<p style="font-size: 18px;">{{ strip_tags($edit_conditions->terms_conditions_detail)}}</p>
                </div>
				</div>
			   </div>
			   @endforeach             
	</div>
	</div>
@endif



  <script src="{{ url('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
    <script src="{{ url('/vendor/unisharp/laravel-ckeditor/adapters/jquery.js') }}"></script>
    <script>
        $('textarea').ckeditor();
         $('.textarea').ckeditor(); // if class is prefered.
		  
    </script>

@endsection
@section('breadcrum')
	
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