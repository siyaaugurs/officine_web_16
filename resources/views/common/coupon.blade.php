@extends('layouts.master_layouts')
@section('content')
<?php 
$coupon_arr = [1=>"Coupon" , 2=>"Coupon Group"];
?>
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="content">
	<div class="card">
	@if(Session::has('msg'))
      {!! session::get('msg') !!}
    @endif
		<table class="table datatable-show-all">
			<thead>
				<tr>
					<th>SN.</th>
					<th>Image</th>
					<th>Coupon Title</th>
					<th>Coupon Type</th>
					<th>Coupon Quantity</th>
					<th>Status</th>
					<th class="text-center">Actions</th>
				</tr>
			</thead>
			<tbody>
				@forelse ($coupons as $coupon)	
				<tr>
					<td>{{ $loop->iteration }}</td>
					@if(Auth::user()->id == $coupon->id)
					@endif  
					@if(file_exists("storage/coupon_image/$coupon->coupon_image"))
					<td><img src = "{{ URL::asset('storage/coupon_image/'.$coupon->coupon_image) }}" hight = "50" width = "50"></td>
					@else
					<td><img src = "{{ URL::asset('storage/coupon/def_coupon.jpeg') }}" hight = "50" width = "50"></td>
					@endif
					<td>{{ $coupon->coupon_title }}</td>
					<th>{{ $coupon_arr[$coupon->coupon_type] }}</th>
					<td>{{ $coupon->coupon_quantity }}</td>
					@if(!empty($coupon->status))
						<td><span class="badge badge-success">Active</span></td>
					@else
						<td><span class="badge badge-danger">Pending Approoval</span></td>
					@endif
					@php $decrypt_id  = encrypt($coupon->id); @endphp
					<td> 
						<a href='{{ url("coupons/$decrypt_id") }}' class="btn btn-primary">Edit</a>
						<a href='{{ url("coupons/delete_coupon/$coupon->id") }}' class="btn btn-danger delete_coupon">Delete</a>
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
</div>
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
							<span class="breadcrumb-item active"> Coupons </span>
						</div>
						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>
				</div>
@stop
@push('scripts')
<script src="{{ url('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
 <script src="{{ url('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>
 <script>
 /*delete coupon*/
 
	$(document).ready(function(e){
		$(document).on('click','.delete_coupon',function(e){
			e.preventDefault();
			var href = $(this).attr('href');
			var con = confirm("Are you sure want to delete this coupon ?");
			if(con == true){
				window.location.href = href;
			}
		});
	});

	/*End*/
 </script>
@endpush


