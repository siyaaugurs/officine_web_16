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
                    <!--<th>Product Name</th>-->
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Status</th>
                    <th>posted at </th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($all_feedback as $feedback)	
                @php $product_name = \App\ProductsNew::get_feedback_product($feedback->products_id) @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <th><span class="badge badge-success">{{ $feedback->f_name ?? "" }}&nbsp;&nbsp;{{ $feedback->l_name ?? "" }}</span></th>
                    <td>{{ $feedback->rating }}</td>
                    <td>{{ $feedback->comments ?? " N/A " }}</td>
                    @if($feedback->is_deleted == 1)
                    <td><span class="badge badge-success">Is deleted</span></td>
                    @else
                    <td><span class="badge badge-primary">Active</span></td>
                    @endif
                    <td>{{ $feedback->created_at}}</td>
                    <td class="text-center">
                        <div class="list-icons pull-right">
                            <div class="dropdown">
                                <a href="#" class="list-icons-item" data-toggle="dropdown">
                                    <i class="icon-menu9"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="#" data-type="seller" data-feedbackid="{{ $feedback->id }}" class="dropdown-item view_seller_feedback"><i class="icon-pencil5 mr-3"></i>View details</a>
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
        <div class="row" style="margin-top:20px;">
            <div class="col-sm-12">
            {{ $all_feedback->links() }}
        </div>
        </div>
    </div>
</div>
<!-- <div class="modal" id="view_feedback_popup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Feedback Details</h4>
                <hr />
            </div>
            <div id="feedback_result">
                <table id="datatable" class="table table-bordered">
                    <tbody>
                        <tr>
                            <td width="210px">Posted By</td>
                            <td id="f_name" class="text-left"></td>
                        </tr>
                         <tr>
                            <td>Feedback Comments</td>
                            <td id="comments" class="text-left"></td>
                        </tr>
                        <tr>
                            <td>Feedback Status</td>
                            <td id="status" class="text-left"></td>
                        </tr>
                        <tr>
                            <td>Posted At</td>
                            <td id="created_at" class="text-left"></td>
                        </tr>
                        <tr>
                            <td>Images</td>
                            <td id="image_name" class="text-left">
                                
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="response_about_pakages"></div>
        </div>
        <div class="modal-footer"></div>
    </div>
</div> -->
@include('common.component.feedback_popup')
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="../vendors/index.html" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
            <a href="../vendors/internationalization_fallback.html" class="breadcrumb-item">Seller</a>
            <span class="breadcrumb-item active">Feedback</span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>   
</div>
@stop
@push('scripts')
<script src="{{ asset('validateJS/seller.js') }}"></script>
<script src="{{ asset('validateJS/common.js') }}"></script>
<script src="{{ url('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
 <script src="{{ url('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>
@endpush


