@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="content">
    <!-- Page length options -->
    <div class="tab_here mb-3">
        <ul class="nav nav-pills m-b-10" id="pills-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="" data-toggle="pill" href="#" role="tab" aria-controls="" aria-selected="false">ALL</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="" data-toggle="pill" href="#" role="tab" aria-controls="" aria-selected="false">Vendor</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="" data-toggle="pill" href="#" role="tab" aria-controls="" aria-selected="false">Seller</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="" data-toggle="pill" href="#" role="tab" aria-controls="" aria-selected="true">Users</a>
            </li>
        </ul>
    </div>
    <div class="card">
        <table class="table datatable-show-all">
            <thead>
                <tr>
                    <th>SN.</th>
                    <th>Title</th>
                    <th>Start / End Date</th>
                    <th>Start / End Time</th>
                    <th>Paid Amount</th>
                    <th>Status</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($workshops as $w_shop)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $w_shop->title }}</td>
                    <td>{{ $w_shop->workshop_start_date." / ".$w_shop->workshop_end_date }}</td>
                    <td>{{ $w_shop->workshop_start_time." / ".$w_shop->workshop_end_time }}</td>
                    <td>{{ $w_shop->amount ?? " Free " }}</td>
                    <td><span class="badge badge-success">Active</span></td>
                    <td class="text-center">
                        <div class="list-icons">
                            <div class="dropdown">
                                <a href="#" class="list-icons-item" data-toggle="dropdown">
                                    <i class="icon-menu9"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href='{{ url("vendor/edit_workshop/$w_shop->enctype_id") }}' class="dropdown-item"><i class="icon-pencil5 mr-3"></i>Edit</a>
                                    <a href='{{ url("vendor/gallery_workshop/$w_shop->enctype_id") }}' class="dropdown-item"><i class="icon-images3 mr-3"></i>Gallery Manage</a>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                <td colspan="5">Workshop Not Available</td>
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
                            <a href="index.html" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
                            <a href="internationalization_fallback.html" class="breadcrumb-item">Vendor </a>
                            <span class="breadcrumb-item active"> Users List </span>
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
<script src="{{ url('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ url('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>
@endpush


