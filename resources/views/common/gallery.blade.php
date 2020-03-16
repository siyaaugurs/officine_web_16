@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<style>
.colWrap{ margin-top:15px; }
</style>
<div class="content">
  @if(Session::has('msg'))
    {!! Session::get('msg') !!}
  @endif
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header bg-light header-elements-inline">
          <h6 class="card-title">Workshop Gallery Manage  </h6>
        </div>             
        <div class="card-body">
          <div class="row">
         <form id="add_gellery_form">
         <div class="control-group" id="fields">
          <label class="control-label" for="field1">
           Browse Multiple Image
          </label>
          <div class="controls">

              <div class="entry input-group col-xs-3">

                <input class="btn btn-primary" name="images[]"  type="file" multiple="multiple">

                <span class="input-group-btn">

                 &nbsp;&nbsp;

                 <button class="btn btn-success btn-add" type="submit" id="save_image">

                   Save

                   <span class="glyphicon glyphicon-plus"></span>

                </button>

                </span>

              </div>

          </div>

        </div>

         </form>

        </div>

        </div>

      </div>

    </div>

  </div>

 <div class="row" id="response_msg"></div>

          <div class="row" style="margin-top:10px;" id="image_grid_section">

           @if($images_arr != FALSE) 

            @foreach($images_arr as $images)

               <div class="col-sm-4 col-md-3 col-lg-3 image_grid">

									<div class="card">

										<div class="card-img-actions m-1">

											<img class="card-img img-fluid" src="{{ $images->image_url}}" alt="" />

											<div class="card-img-actions-overlay card-img">

												<a href="{{ $images->image_url }}" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round" data-popup="lightbox" rel="group">

													<i class="icon-plus3"></i>

												</a>
                                                <a href='{{ url("home/remove_images/$images->id") }}' data-imageid="{{ $images->id }}" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2 remove_service_image">

													<i class="icon-trash"></i>

												</a>

												

                                            </div>

										</div>

									</div>

								</div>

            @endforeach

           @endif 

        </div>
@stop

@push('scripts')

<script>
	/*Add Workshop Car Revision Category Script Start*/
	$(document).on('submit', '#add_gellery_form', function (e) {
		$('#save_image').html('Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
		e.preventDefault();
		$.ajax({
			url: base_url+"/home/gallery",
			type: "POST",   
			data: new FormData(this),
			contentType: false,
			cache: false,
			processData:false,    
			success: function(data){
				$('#save_image').html(' Submit &nbsp;<i class="icon-paperplane ml-2"></i> ').attr('disabled' , false);
				var parseJson = jQuery.parseJSON(data);
				if(parseJson.status == 200){
					//$("#add_car_revision_category_form")[0].reset();
					//$("#add_category_popup").modal('hide');
					$("#msg_response_popup").modal('show');
					$("#msg_response").html(parseJson.msg);
					setTimeout(function(){ location.reload(); } , 1000);
				}
				if(parseJson.status == 100) {
					$("#add_car_revision_category_form")[0].reset();
					$("#add_category_popup").modal('hide');
					$("#msg_response_popup").modal('show');
					$("#msg_response").html(parseJson.msg);
				}	
			} , 
			error: function(xhr, error){
				$('#car_revision_submit').html('Save <span class="glyphicon glyphicon-plus"></span>').attr('disabled' , false);
				$("#response_msg").html(parseJson.msg);
			}
      	});
	});
	/*End */
	</script>
  <script src='{{ url("validateJS/car_wash.js") }}'></script>

  <script src="{{ url('validateJS/admin.js') }}"></script>

   <script src="{{ url('validateJS/services.js') }}"></script> 

    <script src="{{ url('validateJS/service_slot.js') }}"></script> 

  <script src='{{ url("validateJS/vendors.js") }}'></script>

	<script src="{{ url('global_assets/js/plugins/media/fancybox.min.js')}}"></script>

	<script src="{{ url('global_assets/js/demo_pages/gallery.js')}}"></script>

  <script src="{{ url('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>

  <script src="{{ url('validateJS/date/jquery-ui.css') }}"></script>

  <script src="{{ url('validateJS/date/jquery-ui.js') }}"></script>

  <script>

	$('#start_time, #end_time ').datetimepicker({

		format: 'HH:mm:ss'

	});

</script>

<script>

    $(document).ready(function(e){

       $(document).on('click','.add_coupon_popup_btn',function(e){

            e.preventDefault();

            $this = $(this);

            var package_id = $(this).data('packagesid');

            $("#service_package_id").val(package_id);

            $("#add_coupon_popup").modal('show');

        });

        

    });

</script>

@endpush





