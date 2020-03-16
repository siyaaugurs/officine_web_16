@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
@if(Session::has('msg'))
{!! Session::get('msg') !!}
@endif
<div class="card">
   <div class="card-header bg-light header-elements-inline">
      <h6 class="card-title">@lang('messages.SelectCategory') </h6>
      <div class="header-elements">
         
      </div>
   </div>
   <div class="card-body">
      <form action="{{ url('vendor/add_user_workshop_cat') }}" method="post">
         @csrf
         @if($unregistered_cat->count() > 0)
         <h5 style="font-size:16px; font-weight:800;">@lang('messages.UnsubscribedCategories')</h5>
         <div class="row">
            @foreach($unregistered_cat as $un_cat)
            <div class="col-sm-4" style="padding:10px;">
               <div class="d-flex justify-content-between align-items-center">
                  <div class="form-check form-check-inline">
                     <label class="form-check-label">
                     <input type="checkbox" class="form-control-styled paid_status" name="work_shop_paid[]" value="{{ $un_cat->id }}" data-fouc>
                     {{ $un_cat->main_cat_name }}
                     </label>
                  </div>
               </div>
               <!-- <div class="d-flex justify-content-between align-items-center"  style="margin-top:5px;">
                  <div class="form-check form-check-inline">
                     <label class="form-check-label">
                     <input type="checkbox" class="form-control-styled" name="subscribe_quotes[]" value="{{ $un_cat->id }}" data-fouc>
                     For Quotes Subscribe 
                     </label>
                  </div>
               </div> -->
            </div>
            @endforeach
         </div>
         @endif
         @if($users_registered_cat != FALSE)
         <h2 style="font-size:16px; margin-top:30px; font-weight:800;">@lang('messages.SubscribedCategories')</h2>
         <div class="row" style="margin-top:15px;">
            @foreach($users_registered_cat as $cat)
            <div class="col-sm-4" style="padding:10px;">
               <div class="d-flex justify-content-between align-items-center">
                  <div class="form-check form-check-inline">
                     <label class="form-check-label">
                     <input type="checkbox" class="form-control-styled paid_status" name="work_shop_paid[]" value="{{ $cat->id }}" checked data-fouc>
                     {{ $cat->main_cat_name }}
                     </label>
                  </div>
               </div>
               <!-- <div class="d-flex justify-content-between align-items-center" style="margin-top:5px;">
                  <div class="form-check form-check-inline">
                     <label class="form-check-label">
                     <input type="checkbox" class="form-control-styled" name="subscribe_quotes[]" value="{{ $cat->id }}"  data-fouc <?php if(!empty($cat->for_quotes))  echo "checked"; ?>>
                     For Quotes Subscribe
                     </label>
                  </div>
               </div> -->
            </div>
            @endforeach
         </div>
         @endif
         <div class="form-group" style="margin-top:20px;">
            <button type="submit" id="workshop_sbmt" class="btn bg-blue ml-3">@lang('messages.Submit') <i class="icon-paperplane ml-2"></i></button>
         </div>
      </form>
   </div>
</div>
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
   <div class="d-flex">
      <div class="breadcrumb">
         <a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> @lang('messages.Home')</a>
         <span class="breadcrumb-item active"> @lang('messages.SelectCategory') </span>
      </div>
      <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
   </div>
   <!--
      <div class="header-elements d-none">
      
      	<div class="breadcrumb justify-content-center">
      
      		<a href="#" class="breadcrumb-elements-item">
      
      			<i class="icon-comment-discussion mr-2"></i>
      
      			Support
      
      		</a>
      
      
      
      		<div class="breadcrumb-elements-item dropdown p-0">
      
      			<a href="#" class="breadcrumb-elements-item dropdown-toggle" data-toggle="dropdown">
      
      				<i class="icon-gear mr-2"></i>
      
      				Settings
      
      			</a>
      
      
      
      			<div class="dropdown-menu dropdown-menu-right">
      
      				<a href="#" class="dropdown-item"><i class="icon-user-lock"></i> Account security</a>
      
      				<a href="#" class="dropdown-item"><i class="icon-statistics"></i> Analytics</a>
      
      				<a href="#" class="dropdown-item"><i class="icon-accessibility"></i> Accessibility</a>
      
      				<div class="dropdown-divider"></div>
      
      				<a href="#" class="dropdown-item"><i class="icon-gear"></i> All settings</a>
      
      			</div>
      
      		</div>
      
      	</div>
      
      </div>
      
      -->
</div>
@stop
@push('scripts')
<script src="{{ url('validateJS/date/jquery-ui.css') }}"></script>
<script src="{{ url('validateJS/date/jquery-ui.js') }}"></script>
<script src="{{ url('validateJS/vendors.js') }}"></script>
<script src="{{ url('global_assets/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
<script src="{{ url('global_assets/js/demo_pages/form_multiselect.js') }}"></script>
@endpush