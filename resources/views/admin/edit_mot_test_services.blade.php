@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
@if(session::has('msg'))
  {!! Session::get('msg') !!}
@endif
 <style> 
    .container{ padding:15px;} 
    .cardWrap1{ 
        overflow: auto;
    }
 </style>
<div class="card" id="add_special_condition" >
    <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-plus"></i>&nbsp;Edit Our Services</h6>
    </div>
	<div class="card-body">
        <form id="edit_our_mot_services" autocomplete="off">
            @csrf
            <input type="hidden" name="mot_service_id" id="mot_service_id" value="{{ $mot_service_details->id }}">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Service Name&nbsp;<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="service_name" name="service_name" placeholder="Service Name" value="{{ $mot_service_details->service_name }}" />
                    </div>   
                </div>
            </div>	
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Service Description&nbsp;<span class="text-danger">*</span></label>
                        <textarea cols="5" name="service_description" class="form-control" placeholder="Service Description">{{ $mot_service_details->service_description }}</textarea>
                    </div>   
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <label>Service Km.&nbsp;<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="service_km" name="service_km" placeholder="Service Km." value="{{ $mot_service_details->service_km }}" />
                </div>
                <div class="col-sm-6">
                    <label>Month.&nbsp;<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="month" name="month" placeholder="Month" value="{{ $mot_service_details->month }}" />
                </div>
            </div>
            <div class="row rowPadding">
                <div class="col-sm-12">
                    <label>Car compatible&nbsp;<span class="text-danger">*</span></label>          
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <select class="form-control makers" name="car_makers" id="makers">
                            <option value="0" hidden="hidden">@lang('messages.selectMaker')</option>
                            <option value="1" <?php if($mot_service_details->maker_value == 1)echo "Selected"; ?>>All Cars</option>
                             @foreach($cars__makers_category as $makers)
                               <option value="@if(!empty($makers->idMarca)){{ $makers->idMarca }} @endif"  <?php if($mot_service_details->maker_value == $makers->idMarca)echo "Selected"; ?>>@if(!empty($makers->Marca)){{ $makers->Marca }} @endif</option>
                             @endforeach 
                        </select>                                
                    </div> 
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <input type="hidden" id="model_value" value="{{ $mot_service_details->car_models }}">
                        <select class="form-control models" name="car_models"></select>                                
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <input type="hidden" id="version_value" value="{{ $mot_service_details->car_version }}">
                        <select class="form-control versions" id="version_id" name="car_version" data-action="get_n3_category">
                         
                        </select>                                
                    </div> 
                </div>
            </div>
            <div class="row rowPadding">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Select Category </label>
                        <select class="form-control multiselect" name="n3_category[]" id="n3_category" multiple>
                            
                        </select>
                        <span id="title_err"></span>
                    </div>   
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div id="err_response"></div>      
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" id="edit_our_mot_services_btn" class="btn bg-blue ml-3">Submit <i class="icon-paperplane ml-2"></i></button>
                </div>
            </div>
        </form>
	</div>
</div>
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="{{ url('/') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>@lang('messages.Home')</a>
            <a href="{{ url('/') }}" class="breadcrumb-item">@lang('messages.Admin')</a>
            <span class="breadcrumb-item active">Mot Services List  </span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop
@push('scripts')
<link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" />
 <script src="{{ url('validateJS/mot_service.js') }}"></script>
 <script src="{{ url('validateJS/products.js') }}"></script>
 <script src="{{ asset('validateJS/special_conditions_cars.js') }}"></script>
 <script src="{{ url('global_assets/js/demo_pages/form_multiselect.js') }}"></script>
<script src="{{ url('global_assets/js/plugins/forms/selects/bootstrap_multiselect.js') }}">
</script>
<script>
function get_all_n3_category(version_id, type, mot_id) {
    if(version_id != 0) {
        $("#preloader").show();
        $.ajax({
            url:base_url+"/mot_services/get_all_group",
            method:"GET",
            data:{version_id:version_id, mot_id:mot_id},
            complete:function(e, xhr, settings){
                var parseJson = jQuery.parseJSON(e.responseText);
                var html_content = '';
                if(parseJson.status == 200){
                    $("#n3_category").html(parseJson.response).multiselect('rebuild');
                    $("#preloader").hide();
                }
                if(parseJson.status == 100){
                    $("#n3_category").html(parseJson.response).multiselect('rebuild');
                    $("#preloader").hide(); 
                }
            },
            error: function(xhr, error){
                $("#preloader").hide();
            }
        }); 
    }
}
$(document).ready(function(e) {
    var car_maker = $('#makers').val();
    var model_id = $('#model_value').val();
    var version_id = $('#version_value').val();
    var mot_id = $('#mot_service_id').val();
    var type = 2;
    get_modals(car_maker, model_id, type);
    get_versions_details(model_id, version_id,type);
    get_all_n3_category(version_id, type, mot_id)
    $(document).on('change', '#version_id', function(e){
        e.preventDefault();
        var version_id = $('#version_id').val();
        var cat_type = 1;
        var mot_id = "";
        get_all_n3_category(version_id, cat_type, mot_id)
    });
    $(document).on('click','.our_mot_service_category',function(e){
        id = $(this).data('id');
        e.preventDefault();
        $.ajax({
                url:base_url+"/mot_services/our_mot_service_category",
                method:"GET",
                data:{id:id},
                success:function(e, xhr, settings){
                $("#interval_popup").modal('show');
                $("#interval_response").html(e);
                },
                error: function(xhr, error){
                $("#preloader").hide();
                }
            }); 
    });	
    $(document).on('click','.interval_info',function(e){
        id = $(this).data('id');
        e.preventDefault();
        $.ajax({
                url:base_url+"/mot_services/interval_info",
                method:"GET",
                data:{id:id},
                success:function(e, xhr, settings){
                $("#interval_response").html(e);
                },
                error: function(xhr, error){
                $("#interval_response").html(e);
                }
                ,complete: function(e , xhr , setting){
                $("#interval_popup").modal('show'); 
                }
            }); 
    });	
    
});
</script>
@endpush

