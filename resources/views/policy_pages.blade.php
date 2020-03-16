@extends('layouts.external_master')
  @section('page_conotent')
  <div class="page-content">
		{{-- Main content --}}
		<div class="content-wrapper">
        <div class=" container">
			{{-- Content area --}}
<div class="card Privacy-Box" id="add_maintenance_special_condition " >
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
        </div>
        <div class="footer-wrp">@include('layouts.footer')	</div>
        
        	
	</div>
</div>

<style>

.Privacy-Box{ margin-top:100px; min-height:400px; height:auto;}
.footer-wrp{ 
	position: absolute;
    bottom: 0;
    left: 0;
    right: 0;}


</style>
	
@endsection	