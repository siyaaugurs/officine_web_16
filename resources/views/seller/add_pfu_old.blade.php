@extends('layouts.master_layouts')

@section('content')

<style>

.panel-heading h6 {

	display: inline-block;

}

.panel-primary .panel-heading {

	color: #1e91cf;

	border-color: #96d0f0;

	background: white;

}

.panel-default .panel-heading {

	color: #4c4d5a;

	border-color: #dcdcdc;

	background: #f6f6f6;

	text-shadow: 0 -1px 0 rgba(50,50,50,0);

    height: 44px;

    border-bottom:1px solid #ddd;

}

.panel .panel-heading {

    padding: 10px;

}

.panel-default {

	border: 1px solid #dcdcdc;

	border-top: 1px solid #dcdcdc;

}

</style>

<div class="content">

    <div class="card">

        <div class="panel panel-default">

            <div class="panel-heading">

                <h6 class="panel-title"><i class="fa fa-shopping-cart"></i>&nbsp;ADD PFU</h6>

            </div>

		 @if ($msg = Session::get('msg'))

		<div class="alert alert-danger alert-block">

		<button type="button" class="close" data-dismiss="alert">×</button>	

			<strong>{{ $msg }}</strong>

		</div>

		@endif

        <div class="card-body">

            <form id="" action="{{ url('seller/add_pfu_detail')}}" method="post">

                @csrf

                <div class="row form-group" id="">

                        <label>Select Category</label>

                        <select class="form-control" name="category" required>

								<option value="">Select Category</option>

                                @foreach($pfu_category as $pfu)

                                <option value="@if(!empty($pfu->id)){{ $pfu->id }} @endif">@if(!empty($pfu->category)){{ $pfu->category }} @endif</option>

                                @endforeach 

                        </select>
                    </div> 
                <div class="row form-group" id="">
					 <label>Add Money</label>
                        <input type="text" id="add_money"  name="add_money" required class="form-control">
                </div>
                <div class="row form-group" id="">
					 <label>Number of Days </label>
                        <input type="number" id="no_of_days"  name="no_of_days" required class="form-control">
                </div>
                    <div class="row form-group"> 

                        <button type="submit" class="btn bg-blue ml-3">Submit <i class="icon-paperplane ml-2"></i></button>

                    </div>

            </form>

        </div>

    </div>

</div>



<div class="card">

        <div class="card-header bg-light header-elements-inline">

            <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;PFU</h6>

        </div>

<table class="table datatable-show-all dataTable no-footer">

            <thead>

                <tr>

                    <th>Category</th>

					<th>Add Money</th>

                </tr>

            </thead>

			

            <tbody>

			@if(!empty($get_tyre_user_pfu))

                @forelse($get_tyre_user_pfu as $get_tyre_user)

                    <tr>

                        <td>{{ $get_tyre_user->category }}</td>

						<td>{{ $get_tyre_user->add_money }}</td>



                    </tr>

                @empty

				@endif

                <tr>

                   <td colspan="6">@lang('messages.NoRecordFound')</td>

                </tr>

                @endforelse

            </tbody>

        </table>

        <div class="row" style="margin-top:20px;">

            <div class="col-sm-12">

               

            </div>

        </div>

    </div>







@endsection

@section('breadcrum')

<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">

    <div class="d-flex">

        <div class="breadcrumb">

            <a href="../vendors/index.html" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>

            <a href="../vendors/internationalization_fallback.html" class="breadcrumb-item">Seller</a>

            <span class="breadcrumb-item active"> products</span>

        </div>

        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>

    </div>   

</div>

@stop

@push('script')

<link href='{{ url("cdn/css/croppie.css") }}' />

@endpush



@push('custom_script')

<script src="{{ url('cdn/js/croppie.js') }}"></script>

@endpush

@push('scripts')

<script src="{{ asset('validateJS/admin.js') }}"></script>

<script src="{{ asset('validateJS/seller.js') }}"></script>

<script src="{{ asset('validateJS/products.js') }}"></script>

<script src="{{ url('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>

<script src="{{ url('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>

<script>

    $(document).ready(function(){

        $(document).on('click' , '#tax' , function(){

            var checkBox = document.getElementById("tax");

            var text = document.getElementById("tax_hide_show");

            if (checkBox.checked == true){

                text.style.display = "block";

            } else {

                text.style.display = "none";

            }

        })

    })

</script>

@endpush