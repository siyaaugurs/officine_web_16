@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="content">
    <!-- Page length options -->
    <div class="card" id="user_data_body" style="overflow:auto">
        <table class="table datatable-show-all">
            <thead>
                <tr>
                    <th>S No.</th>
                    <th>Customer Name</th>
                    <th>Product Order Id</th>
                    <th>Product Name</th>
                    <th>Product Descriptions</th>
                    <th>Coupan Id</th>
                    <th>Price</th>
                    <th>Discount</th>
                    <th>Total Price</th>
                    <th>Created At</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($order as $orders)	
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $orders->f_name ?? "N/A" }}</td>
                        <td>{{ $orders->products_orders_id ?? "N/A" }}</td>
                        <td>{{ $orders->product_name ?? "N/A" }}</td>
                        <td>{{ $orders->product_description ?? "N/A " }}</td>
                        <td>{{ $orders->coupons_id ?? "N/A"}}</td>
                        <td>{{ $orders->price ?? "N/A" }}</td>
                        <td>{{ $orders->discount }}</td>
                        <td>{{ $orders->total_price }}</td>
                        <td>{{ $orders->created_at }}</td>
                        @if($orders->status == "P") 
                            <td> <span class="badge badge-danger">Pending</span></td>
                        @elseif($orders->status == "A") 
                            <td> <span class="badge badge-success">Approved</span></td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">Product Description Not Available</td>
                    </tr>
                @endforelse     
            </tbody>
        </table>     
    </div>
    <!-- /page length options -->
</div>
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="{{ url('/') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
            <a href="#" class="breadcrumb-item">Admin </a>
            <span class="breadcrumb-item active"> Order List </span>
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
        </div>
    </div>
    -->
</div>
@stop
@push('scripts')
<script src="{{ asset('validateJS/admin.js') }}"></script>
<script src="{{ url('validateJS/vendors.js') }}"></script>
<script src="{{ url('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ url('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>
@endpush


