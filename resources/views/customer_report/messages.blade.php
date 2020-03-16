@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<input type="hidden" name="ticket_id" id="ticket_id" value="<?php if(!empty($ticket_detail->id)) echo encrypt($ticket_detail->id); ?>" />

<style> .container{ padding:15px;} </style>
<div class="card" style="margin-bottom:10px;">
   <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="glyphicon glyphicon-list"></i>&nbsp;Ticket Details </h6>
    </div>
    <div class="content">
				<div class="card">
					<div class="card-header header-elements-inline">
					<div class="card-body" id="message_body">
						<div class="row">
                           <div class="col-sm-12">
							   <table class="table table-bordered">
								  <tbody>
									  <tr>
										  <th>Ticket ID</th>
										  <td>Ticket- {{ $ticket_detail->id }}</td>
										  <th>Created Date</th>
										  <td>{{ sHelper::date_format_for_database($ticket_detail->created_at , 1) }}</td>
									  </tr>
								  </tbody>
								  <tbody>
									  <tr>
										  <th colspan="2">Customer Name</th>
										  <td colspan="2">@if(!empty($ticket_detail->ticket_creator))  {{  $ticket_detail->ticket_creator->f_name." ".$ticket_detail->ticket_creator->l_name }} @endif
											  @if(!empty($ticket_detail->ticket_creator))
											    @php  $uid = base64_encode($ticket_detail->ticket_creator->id); @endphp
											    <a target="_blank" href="<?php echo url("admin/customers_profile/$uid") ?>">( OFFICINE- {{  $ticket_detail->ticket_creator->id }} )</a>
											  @endif
										  </td>
									  </tr>
									  <tr>
										  <th colspan="2">Compain Type</th>
										  <th colspan="2"> 
										  @if(array_key_exists($ticket_detail->ticket_type , $support_complain_type))
										   {{ $support_complain_type[$ticket_detail->ticket_type] }} 
										  @else
                                            No type defined !!!
										  @endif
										  </th>
										</tr>
									</tbody>
							   </table>
						   </div>
						</div>
					</div>
					</div>
				</div>
				<!-- /basic layout -->
			</div>
</div>
<div class="card" style="margin-bottom:10px;">
   <div class="card-header bg-light header-elements-inline">
        <h6 class="card-title" style="font-weight:600;"><i class="glyphicon glyphicon-comment"></i>&nbsp;Messages </h6>
    </div>
    <div class="content">
				<!-- Basic layout -->
				<div class="card">
					<div class="card-header header-elements-inline">
						<div class="header-elements">
						</div>
					</div>
					  @include('customer_report.component.messages') 
				</div>
				<!-- /basic layout -->
			</div>
</div>

@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>@lang('messages.Home')</a>
            <a href="#" class="breadcrumb-item">@lang('messages.Admin')</a>
            <span class="breadcrumb-item active">Support Ticket List</span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop
@push('scripts')
<script src="{{ asset('validateJS/customer_reports.js') }}"></script>
<script>
$(document).ready(function() {
/*Load page script Start*/
/* var ticket_id = $("#ticket_id").val();
$("#load_chat_messages").load(base_url+"/admin/report_load_page/messages/"+ticket_id, function(responseTxt, statusTxt, xhr){
    if(statusTxt == "success")
      alert("External content loaded successfully!");
    if(statusTxt == "error")
      alert("Error: " + xhr.status + ": " + xhr.statusText);
  }); */
/*End*/		
 var element = document.getElementById("chatlist");
 element.scrollTop = element.scrollHeight;
 /*Support Ticket Form submit script Start*/
 $(document).on('submit','#support_messages',function(e){
		//$('#response').html(" ");
		//$("err_response").html(" ");
		$('#send_support_btn').html(' Please wait <i class="icon-spinner2 spinner"></i>').attr('disabled' , true);
		e.preventDefault();
			$.ajax({
				url: base_url+"/customer_report/send_support_messages",
				type: "POST",        
				data: new FormData(this),
				contentType: false,
				cache: false,
				processData:false,  
				success: function(data){
					console.log(data);
				   $('#send_support_btn').html('<b><i class="icon-paperplane"></i></b> Send').attr('disabled' , false);
				   response = jQuery.parseJSON(data);
				   console.log(response);
				   if(response.status == 200){
						$("#msg_response_popup").modal('show'); 
						$("#msg_response").html(response.msg);
						setTimeout(function(){ location.reload() } , 1000);
				   }
				},
				error: function(xhr, error){
				   $('#send_support_btn').html('<b><i class="icon-paperplane"></i></b> Send').attr('disabled' , false);
				   $("#msg_response_popup").modal('show'); 
				   $("#msg_response").html('<div class="notice notice-danger"><strong> Wrong </strong> Something went wrong , please tr again !!! </div>');
				} 
			});

	   });

 /*End*/
} );
</script>
@endpush




