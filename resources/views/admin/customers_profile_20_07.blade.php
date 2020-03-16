@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="content">
    <div class="card" >
        <div class="card-header bg-light header-elements-inline">
            <h6 class="card-title">Customer Details &nbsp;
                @if($customer_detail != NULL)
                @if($customer_detail->users_status == 'B')         
                    <a href="#"  class="ml-3 icn-sm change_customer_status" data-status="{{ $customer_detail->users_status }}" data-customerid="{{ $customer_detail->id }}"><i class="fa fa-toggle-off"></i><a>         
                @else
                    <a href="#" class="ml-3 icn-sm change_customer_status" data-status="{{ $customer_detail->users_status }}" data-customerid="{{ $customer_detail->id }}"><i class="fa fa-toggle-on"></i><a>       
                @endif
                @endif
            </h6>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                    <a class="list-icons-item" data-action="remove"></a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body" id="days_hour_section">
                <ul class="media-list media-chat-scrollable mb-3">
                    <li class="media">
                        <div class="mr-3">
                            1.
                        </div>
                        <div class="media-body">
                            <div class="media-title d-flex flex-nowrap">
                                <a class="font-weight-semibold mr-3">Name</a>
                                <span class="font-size-sm text-muted text-nowrap ml-auto"> 
                                </span>
                            </div>
                                {{ $customer_detail->f_name." ".$customer_detail->l_name }}
                        </div>
                    </li>
                    <li class="media">
                        <div class="mr-3">
                            2.
                        </div>
                        <div class="media-body">
                            <div class="media-title d-flex flex-nowrap">
                                <a class="font-weight-semibold mr-3">Email</a>
                                <span class="font-size-sm text-muted text-nowrap ml-auto"> 
                                </span>
                            </div>
                            {{ $customer_detail->email }}
                        </div>
                    </li>
                    <li class="media">
                        <div class="mr-3">
                            3.
                        </div>
                        <div class="media-body">
                            <div class="media-title d-flex flex-nowrap">
                                <a class="font-weight-semibold mr-3">Mobile Number</a>
                                <span class="font-size-sm text-muted text-nowrap ml-auto"> 
                                </span>
                            </div>
                                {{ $customer_detail->mobile_number }}
                        </div>
                    </li>
                    <li class="media">
                        <div class="mr-3">
                            4.
                        </div>
                        <div class="media-body">
                            <div class="media-title d-flex flex-nowrap">
                                <a class="font-weight-semibold mr-3">Registration Date</a>
                                <span class="font-size-sm text-muted text-nowrap ml-auto"> 
                                </span>
                            </div>
                                {{ (date("Y-m-d", strtotime($customer_detail->created_at)))  }}
                        </div>
                    </li>
                    <li class="media">
                        <div class="mr-3">
                            5.
                        </div>
                        <div class="media-body">
                            <div class="media-title d-flex flex-nowrap">
                                <a class="font-weight-semibold mr-3">User Name</a>
                                <span class="font-size-sm text-muted text-nowrap ml-auto"> 
                                </span>
                            </div>
                                {{ $customer_detail->user_name }}
                        </div>
                    </li>
                    <li class="media">
                        <div class="mr-3">
                            6.
                        </div>
                        <div class="media-body">
                            <div class="media-title d-flex flex-nowrap">
                                <a class="font-weight-semibold mr-3">Profile</a>
                                <span class="font-size-sm text-muted text-nowrap ml-auto"> 
                                </span>
                            </div>
                            @if($customer_detail->profile_image != NULL)         
                                <img src="{{ URL::to('storage/').$customer_detail->profile_image }}">
                            @else
                                <img src="{{ URL::to('storage/user.png') }}" style="height:100px;width:100px">       
                            @endif
                        </div>
                    </li>
                </ul>
            </div>
        </div>    
    </div>
    <div class="card">
        <div class="card-header bg-light header-elements-inline">
            <h6 class="card-title">Virtual User Garage &nbsp;</h6>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                    <a class="list-icons-item" data-action="remove"></a>
                </div>
            </div>
        </div>
        <div class="card" style="overflow: auto">
            <table class="table " >
                <thead style="font-weight:bold">
                    <tr>
                        <td>S No.</td>
                        <td>image</td>
                        <td>Car Maker</td>
                        <td>Car Model</td>
                        <td>Car Version</td>
                        <td>Car Size Status</td>
                        <td>Cars /Km</td>
                        <td>Cars Km/Annually</td>
                        <td>Alloy Wheels</td>
                        <td>Number Plate</td>
                        <td>Creted On</td>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($garage_detail as $garage_details)
                        @php 
                            $maker_name = kRomedaHelper::get_maker_name($garage_details->carMakeName);
                            $model_name = kRomedaHelper::get_model_name($garage_details->carMakeName , $garage_details->carModelName);
                            $versions = kRomedaHelper::get_version_name($garage_details->carModelName , $garage_details->carVersion);
                        @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if($garage_details->image != "")   
                                <img src="{{ URL::to('carlogo').'/'.$garage_details->image }}" style="height:70px;width:70px;border-radius:50%">      
                                
                            @else
                                {{ "No Image Uploaded" }}
                            @endif
                            
                        </td>
                        <td>{{ $maker_name->Marca }}</td>
                        <td>{{ $model_name->Modello }}</td>
                        <td>{{ $versions->Versione }}</td>
                        <td>{{ $garage_details->car_size_status ?? "Not Mentioned" }}</td>
                        <td>{{ $garage_details->km_of_cars ?? "Not Mentioned" }}</td>
                        <td>{{ $garage_details->km_traveled_annually ?? "Not Mentioned" }}</td>
                        <td>{{ $garage_details->alloy_wheels ?? "Not Mentioned" }}</td>
                        <td>{{ $garage_details->number_plate ?? "Not Mentioned" }}</td>
                        <td>{{ (date("Y-m-d", strtotime($garage_details->created_at)))   ?? "Not Mentioned" }}</td>
                    </tr>
                    @empty
                    <tr>
                    <td colspan="11">Garage Details Not Available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
   
</div>
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="{{ url('/') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
            <a href="#" class="breadcrumb-item">Admin </a>
            <span class="breadcrumb-item active"> Users List </span>
            <span class="breadcrumb-item active"> Customer Profile</span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop
@push('scripts')
<script src="{{ asset('validateJS/admin.js') }}"></script>
<script src="{{ url('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ url('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>
@endpush


