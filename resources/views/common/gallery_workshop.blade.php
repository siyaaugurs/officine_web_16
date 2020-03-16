@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="content">

				<!-- Page length options -->
				<div class="content">
                <!-- Image grid -->
				<div class="card">
			      @if(Session::has('msg'))
                    {!! Session::get('msg') !!}
                  @endif
                  <div class="card-body">
			                @if($workshop_details != null)	
                              <form  method="post" action='{{ url("vendor/upload_workshop_gallery") }}' enctype="multipart/form-data">
                                    @csrf
									<div class="row">
								     <div class="col-md-sm form-group">
                                       <label>Browse Image <span class="text-danger">*</span></label>
                                         <input type="hidden" name="workshop_id" id="workshop_id" value="{{ $workshop_details->id }}" />
									     <input type="file" name="gallery_image[]" id="gallery_image" multiple="multiple" />
								     </div>
                                     <div class="col-sm-4 form-group">
                                        <button type="submit" id="workshop_sbmt" class="btn bg-blue ml-3" style="margin-top:20px;">Submit <i class="icon-paperplane ml-2"></i></button>
								     </div>
                                    </div>
                                </form>
                            @else
                              <h1>Content is hide</h1>
                            @endif    
							</div>
		        </div>

			   <div id="workShopImage">	
                <div class="row" id="">
					@forelse($gallery_image as $images)
                    <div class="col-sm-6 col-lg-3">
						<div class="card">
							<div class="card-img-actions m-1">
								<img class="card-img img-fluid" src="{{ $images->image_url}}" alt="" />
								<div class="card-img-actions-overlay card-img">
									<a href="{{ $images->image_url }}" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round" data-popup="lightbox" rel="group">
										<i class="icon-plus3"></i>
									</a>
									<a href='{{ url("vendor_ajax/delete_image/$images->id") }}' data-imageid="{{ $images->id }}" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2 image_delete">
										<i class="icon-trash"></i>
									</a>
								</div>
							</div>
						</div>
					</div>
                    @empty
                      <div class="col-sm-6 col-lg-3">
						<div class="card">
							<div class="card-img-actions m-1">
								No Image Found !!!
							</div>
						</div>
					</div>
                    @endforelse
				</div>
               </div> 
			</div>
				<!-- /page length options -->
			</div>
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="index.html" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
							<a href="internationalization_fallback.html" class="breadcrumb-item">Vendor </a>
							<span class="breadcrumb-item active"> Workshops Gallery</span>
                            <span class="breadcrumb-item active"> {{ $workshop_details->title }}</span>
						</div>
						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>
					<div class="header-elements d-none">
						<div class="breadcrumb justify-content-center">
							<a href="#" class="breadcrumb-elements-item">
								<i class="icon-comment-discussion mr-2"></i>
								Support
							</a>
						</div>
					</div>
				</div>
@stop
@push('scripts')
<script src='{{ url("validateJS/vendors.js") }}'></script>
	<script src="{{ url('global_assets/js/plugins/media/fancybox.min.js')}}"></script>
	<script src="{{ url('global_assets/js/demo_pages/gallery.js')}}"></script>
@endpush


