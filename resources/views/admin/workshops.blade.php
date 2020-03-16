
@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="content">

				<!-- Page length options -->
				<div class="card">
					<table class="table datatable-show-all">
						<thead>
							<tr>
								<th>SN.</th>
                                <th>Craeted By</th>
								<th>Title</th>
								<th>Paid Amount</th>
								<th>Status</th>
                                <th colspan="2">created at</th>
								<th class="text-center">Actions</th>
							</tr>
						</thead>
						<tbody>
						  @forelse ($workshops as $w_shop)	
                            <tr>
								<td>{{ $loop->iteration }}</td>
                                @if(Auth::user()->id == $w_shop->users_id)
                                   <th><span class="badge badge-danger">You</span></th>
                                @else
                                 <th>{{ $w_shop->f_name." ".$w_shop->l_name }}</th>
                                @endif  
                                <td>{{ $w_shop->title }}</td>
								<td>{{ $w_shop->amount ?? " Free " }}</td>
                               
								<td><span class="badge badge-success">Active</span></td>
                                 <td>{{ $w_shop->created_at ?? " Free " }}</td>
                                <td class="text-center">
									<div class="list-icons pull-right">
										<div class="dropdown">
											<a href="#" class="list-icons-item" data-toggle="dropdown">
												<i class="icon-menu9"></i>
											</a>
											<div class="dropdown-menu dropdown-menu-right">
												<a href='{{ url("workshop_details/$w_shop->enctype_id") }}' class="dropdown-item" target="_blank"><i class="icon-pencil5 mr-3"></i>View details</a>
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
							<a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
							<a href="#" class="breadcrumb-item">Admin </a>
							<span class="breadcrumb-item active"> Workshops </span>
						</div>
						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>
					<!--/*/*<div class="header-elements d-none">
						<div class="breadcrumb justify-content-center">
							<a href="#" class="breadcrumb-elements-item">
								<i class="icon-comment-discussion mr-2"></i>
								Support
							</a>
						</div>
					</div>*/*/-->
				</div>
@stop
@push('scripts')
<script src="{{ url('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
 <script src="{{ url('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>
@endpush


