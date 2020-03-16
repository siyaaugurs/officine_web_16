@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<div class="content">
<!-- Fallback language -->
 <input type="hidden" name="page" id="page" value="car_maintenance">
<style> .container{ padding:15px;} </style>
<div class="card" style="margin-bottom:10px;">
    <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="fa fa-filter"></i>&nbsp;Filter</h6>
    </div>
    <div class="content">
	    <div id="filter-panel">
            <div class="panel panel-default">
                <div class="panel-body">
                   <form id="customer_search">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <select class="form-control" name="car_makers" id="car_makers">                                  <option value="0">--Select--Makers--Name--</option>
                                    @forelse($cars__makers_category as $category)
                                        <option value="{{ $category->idMarca }}">{{ $category->Marca }}</option>
                                    @empty
                                        <option value="0">No Maker Available </option>
                                    @endforelse
								   
                                </select>                                
                            </div> 
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <select class="form-control" name="car_models" id="car_models">                                    <option value="0">--First--Select--Makers--Name--</option>
                                </select>                                
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <select class="form-control car_version_group" id="version_id" name="car_version" data-action="get_and_save_services_time">
                                    <option value="0">--First--Select--Model--Name--</option>
                                </select>                                
                            </div> 
                        </div> 
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                            <button type="submit" id="search_users_on_hold" class="btn btn-warning" style="color:white;">Search &nbsp;<span class="glyphicon glyphicon-search"></span></button>         
                            </div>
                        </div>
                    </div>
                   </form> 
                </div>
            </div>
        </div>  
    </div>
</div>
</div>
<div class="content">
<div class="card" style="margin-bottom:10px;">
    <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="glyphicon glyphicon-comment"></i>&nbsp;Send Mail</h6>
    </div>
    <div class="content">
	    <div id="filter-panel">
            <div class="panel panel-default">
                <div class="panel-body">
                   <div class="card collapse show" id="add_wrecker_special_condition" style="margin-top:20px;">
    <div class="card-body">
        <form id="mail_messages_form" autocomplete="off">
            <div class="row">
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Subject &nbsp;<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="subject" placeholder="Subject" id="subject" required="required">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Messages &nbsp;<span class="text-danger">*</span></label>
                        <textarea type="text" class="form-control" rows="5" name="messages" placeholder="Messages" id="messages" required="required"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Browse File &nbsp;</label>
                        <input type="file" class="form-control" name="browse_image" id="browse_image" />
                    </div>
                </div>
            </div>
                <div class="row">
                    <div class="col-md-12">
                      <button type="submit" id="send_mail_messages" class="btn bg-teal-400 btn-labeled btn-labeled-right ml-auto"><b><i class="icon-paperplane"></i></b> Send</button>
                    </div>
                </div>
        </form>
	</div>
</div>
                </div>
            </div>
        </div>  
    </div>
</div>
</div>

<div class="content">
    <div class="card" id="user_data_body" style="overflow:auto">
      @include('customer_report.component.user_data' )
    </div>
    <div class="row" style="margin-top:10px;" id="pagination_row">
          <div class="col-sm-12">
             {{ $all_customers->links() }}
          </div>
        </div>
</div>
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>@lang('messages.Home')</a>
            <a href="#" class="breadcrumb-item">@lang('messages.Admin')</a>
            <span class="breadcrumb-item active">@lang('messages.UsersList')</span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop
@push('scripts')
<script>
$(document).ready(function(e) {
   $(document).on('submit','#mail_messages_form',function(e){
	$('#send_mail_messages').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
		var customer_search_form = $("#customer_search");
		var message_form = new FormData(this);
		var maker = $("#customer_search #car_makers").val();	
		var model = $("#customer_search #car_models").val();
		var version = $("#customer_search #version_id").val();
		message_form.append('maker' , maker);
		message_form.append('model' , model);
		message_form.append('version' , version);
		e.preventDefault();
			$.ajax({
				url: base_url+"/customer_report/send_mail_messages",
				type: "POST",        
				data: message_form,
				contentType: false,
				cache: false,
				processData:false,  
				success: function(data){
                    $('#send_mail_messages').html('Send &nbsp;<span class="icon-paperplane"></span>').attr('disabled' , false);
                    $("#msg_response").html(data);
                    $("#msg_response_popup").modal({
                        backdrop:'static',
                        keyboard:false
                    });
				} 
			});

	   }); 
});
</script>

<script src="{{ asset('validateJS/customer_reports.js') }}"></script>
<script src="{{ asset('validateJS/products.js') }}"></script>
<script src="{{ asset('validateJS/admin.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/datatables_advanced.js') }}"></script>
@endpush


