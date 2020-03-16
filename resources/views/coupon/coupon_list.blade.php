@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="content">
	<div class="card">
	@if(Session::has('msg'))
      {!! session::get('msg') !!}
    @endif
		<div class="card-header bg-light header-elements-inline">
			<h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;Coupon List</h6>
		</div>
		<table class="table datatable-show-all">
			<thead>
				<tr>
					<th>SN.</th>
				<!--/*	<th>Image</th>*/-->
                	<th>On Discount Type</th>
					<th>Coupon Title</th>
					<th>Coupon Type</th>
					<th>Coupon Quantity</th>
					<th>Status</th>
					<th class="text-center" colspan="2">Actions</th>
				</tr>
			</thead>
			<tbody>
				@forelse ($coupons as $coupon)	
				<tr>
					<td>{{ $loop->iteration }}</td>
                    <td>{{ $on_discount_arr[$coupon->discount_condition] }}</td>
					<!--@if(file_exists("storage/coupon_image/$coupon->coupon_image"))
					<td><img src = "{{ URL::asset('storage/coupon_image/'.$coupon->coupon_image) }}" hight = "50" width = "50"></td>
					@else
					<td><img src = "{{ URL::asset('storage/coupon/def_coupon.jpeg') }}" hight = "50" width = "50"></td>
					@endif-->
					<td>{{ $coupon->coupon_title }}</td>
					<th>{{ $coupon_type_arr[$coupon->coupon_type] }}</th>
					<td>{{ $coupon->coupon_quantity }}</td>
					@if(!empty($coupon->status))
						<td><span class="badge badge-success">Active</span></td>
					@else
						<td><span class="badge badge-danger">Pending Approoval</span></td>
					@endif
					@php $decrypt_id  = encrypt($coupon->id); @endphp
					<td colspan="2"> 
						<a href="javascript::void()" class="btn btn-info coupon_info" data-couponid="{{ $coupon->id }}">&nbsp;Info</a>
                        <a href='{{ url("coupon/edit_coupon/$decrypt_id") }}' class="btn btn-primary">Edit</a>
						<a href='javascript::void()' data-couponid="{{ $coupon->id }}" class="btn btn-danger delete_coupon">Delete</a>
					</td>
				</tr>
				@empty
				<tr>
					<td colspan="5">Coupon Not Available</td>
				</tr>
				@endforelse  
			</tbody>
		</table>
      
	</div>
      {{ $coupons->links() }}
</div>
<!--Coupon info modal popup script start--->
<div class="modal" id="coupon_info_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>

                <h4 class="modal-title" id="myModalLabel">Coupon Info</h4>
                <hr />
            </div>
            <!-- Modal body -->
            <div id="coupon_info_response"></div>
        </div>
        <div class="modal-footer">       
        </div>
    </div>

</div>
<!--End-->
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
            <a href="{{ url('/') }}" class="breadcrumb-item">Admin </a>
							<span class="breadcrumb-item"> Coupons </span>
                            <span class="breadcrumb-item active"> Coupon List </span>
						</div>
						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>
				</div>
@stop
@push('scripts')
<script src="{{ url('validateJS/coupon.js') }}"></script>
<script>
$(document).ready(function(e) {
  $(document).on('click','.coupon_info',function(e){
     coupon_id = $(this).data('couponid');
	 if(coupon_id != ""){
	     $.ajax({
			url: base_url+"/coupon_ajax/coupon_info",
			method: "GET",
			data: {coupon_id:coupon_id},
			success: function(data){
			   $("#coupon_info_response").html(data);
			   $('#coupon_info_modal').modal({
				   backdrop:'static',
				   keyboard:false
				});
			}
		  }); 
	   }
  });  
});
</script>
  
<script src="{{ url('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ url('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>
 @endpush


