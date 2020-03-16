@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="content">
    <div class="card">
        <table class="table datatable-show-all">
            <thead>
                <tr>
                    <th>SN.</th>
                    <th>Feedback By</th>
                    <th>Services</th>
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Status</th>
                    <th>posted at </th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($all_feedback as $feedback)
                @php $services = \App\Category::get_feedback_category($feedback->category_id) @endphp	
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <th><span class="badge badge-success">{{ $feedback->f_name ?? "" }}&nbsp;&nbsp;{{ $feedback->l_name ?? "" }}</span></th>
                    <td>{{ $services->category_name ?? "N/A" }}</td>
                    <td>{{ $feedback->rating }}</td>
                    <td>{{ $feedback->comments ?? " N/A " }}</td>
                    @if($feedback->is_deleted == 1)
                    <td><span class="badge badge-success">Is deleted</span></td>
                    @else
                    <td><span class="badge badge-primary">Active</span></td>
                    @endif
                    <td>{{ $feedback->created_at}}</td>
                    <td>
                        <div class="list-icons pull-right">
                            <div class="dropdown">
                                <a href="#" class="list-icons-item" data-toggle="dropdown">
                                    <i class="icon-menu9"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="#" data-type="workshop" data-feedbackid="{{ $feedback->id }}" class="dropdown-item view_workshop_feedback"><i class="icon-pencil5 mr-3"></i>View details</a>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">Feedback Not Available</td>
                </tr>
                @endforelse  
            </tbody>
        </table>
        <div class="row" style="margin-top:20px;">
            <div class="col-sm-12">
            {{ $all_feedback->links() }}
        </div>
        </div>
    </div>
</div>
@include('common.component.feedback_popup')
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="../vendors/index.html" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
            <a href="../vendors/internationalization_fallback.html" class="breadcrumb-item">Workshop</a>
            <span class="breadcrumb-item active">Feedback</span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>   
</div>
@stop
@push('scripts')
<script src="{{ url('validateJS/vendors.js') }}"></script>
<script src="{{ asset('validateJS/common.js') }}"></script>
<script src="{{ url('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
 <script src="{{ url('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>
@endpush


