@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="content">
 <div class="card" >
        <div class="card-header bg-light header-elements-inline">
            <h6 class="card-title">Customer Bonus Details &nbsp; 
            </h6>
            <div class="header-elements">
                <div class="list-icons">
                   <a class="list-icons-item" data-action="collapse"></a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body" id="days_hour_section">
                @include('admin.component.add_bouns_customer_details' , ['customers_detail'=>$customer_detail])  
            </div>
        </div>  
      
        <div class="card" style="overflow: auto">
            <table class="table " >
                <thead style="font-weight:bold">
                    <tr>
                        <td>S No.</td>
                        <td>Amount</td>
						  <td>Amount</td>
                        <td>Creted On</td>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customer_bonus_detail as $customer_bonus)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{$customer_bonus->amount}}    </td>
						<td>{{$customer_bonus->description}}    </td>
                        <td>{{$customer_bonus->created_at}} </td>
                    </tr>
                    @empty
                    <tr>
                    <td colspan="11">bonus Details Not Available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
  		
    </div>

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
                    <a href='<?php echo url("admin/customers_profile/$p2/edit_customer") ?>' class="btn btn-primary" style="color:white; float:right;">Edit Customer Details&nbsp;<span class="glyphicon glyphicon-edit"></span></a>
                    <a class="list-icons-item" data-action="collapse"></a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body" id="days_hour_section">
            @if($edit_status != NULL && $edit_status == "edit_customer") 
                @include('admin.component.add_customer_details', ['customers_detail'=>$customer_detail] )
            @else
                @include('admin.component.customer_profile')
            @endif 
                
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
                        <td>@if(!empty($maker_name->Marca)){{ $maker_name->Marca }} @endif</td>
                        <td>@if(!empty($model_name->Modello)){{ $model_name->Modello }} @endif</td>
                        <td>@if(!empty($model_name->Versione)){{ $versions->Versione }} @endif</td>
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
            <a href="{{url('admin/dashboard')}}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
            <a href="{{url('admin')}}" class="breadcrumb-item">Admin </a>
            <a  href="{{url('admin/users_list')}}" class="breadcrumb-item"> Users List </a>
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


