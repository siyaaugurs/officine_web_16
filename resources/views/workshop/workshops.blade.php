@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="content">
				<!-- Page length options -->
				<div class="row">
                 @forelse ($workshops as $w_shop)	
			      <div class="col-md-4">
			    		<div class="card card-body border-top-danger">
							<div class="">
							 <a class="font-weight-semibold mr-3"> 
                              {!! sHelper::get_users_category($w_shop->id) !!}
                              </a>
                              <p class="mb-3 text-muted" style="margin-top:10px;">{{ $w_shop->title }}</p>
                              <p class="mb-3 text-muted">{!! substr($w_shop->description , 0 ,100 ) !!}</p>
							</div>

							<a href='{{ url("workshop_details/$w_shop->enctype_id") }}' class="form-control text-danger text-uppercase font-weight-semibold font-size-sm line-height-sm" >View Details</a>
						</div>
			    	</div>
                 @empty
                   <div class="col-md-4">
			    		<div class="card card-body border-top-danger">
							<div class="text-center" style="margin-bottom:15px;">
								<h3 class="m-0 font-weight-semibold">No Workshop Available !!!</h3>
							</div>
							<a href="{{ url('add_workshop') }}" class="form-control text-danger text-uppercase font-weight-semibold font-size-sm line-height-sm">Add New Workshop</a>
						</div>
			    	</div>
                 @endforelse     
			    </div>
                <!-- /page length options -->
			</div>
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
							<a href="#" class="breadcrumb-item">Workshop </a>
							<span class="breadcrumb-item active"> Workshops List</span>
						</div>
						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>
					<!--<div class="header-elements d-none">
						<div class="breadcrumb justify-content-center">
							<a href="#" class="breadcrumb-elements-item">
								<i class="icon-comment-discussion mr-2"></i>
								Support
							</a>
						</div>
					</div>-->
				</div>
@stop
@push('scripts')
<script src="{{ url('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
 <script src="{{ url('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>
@endpush


