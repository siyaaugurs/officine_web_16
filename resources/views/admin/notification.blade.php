@extends('layouts.master_layouts')
@section('content')
<input type="hidden" name="page" id="page" value="{{ $page }}" />
<style>
.form-pfu {
    margin-bottom: 1.25rem;
}
</style>
<div class="content">
    <!-- Page length options -->
    @if(Session::has('msg'))
      {!! session::get('msg') !!}
    @endif
	<div id="success_message" class="ajax_response" style="float:top"></div>
    <div class="card"  style="overflow:auto">
         <div class="card-header bg-light header-elements-inline">
            <h6 class="card-title" style="font-weight:600;"><i class="fa fa-list"></i>&nbsp;NOTIFICATION</h6>
            <a href='#' class="btn btn-primary" id="add_pfu" style="color:white; float:right;" >Add Notification &nbsp;<span class="glyphicon glyphicon-plus"></span></a>
        </div>

        <table class="table datatable-show-all dataTable no-footer" >
            <thead>
                <tr>
                    <th>SN.</th>
					<th>Notification  Type</th>
					<th>Target User</th>
					<th>Subject</th>
					<th>Title</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
			 @forelse($notification_list as $notification)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $notification->notification_type }}</td>
						<td>{{ $notification->target_user }}</td>
                        <td>{{ $notification->title }}</td>
						<td>{{ $notification->subject }}</td>
                        <td>
                            <a href="#" class="btn btn-primary edit_pfu" data-id="{{ $notification->id }}"> <i class="fa fa-edit"></i> </a>
							 <a href="#" class="btn btn-danger delete_notification" data-id="{{ $notification->id }}"> <i class="fa fa-remove"></i> </a>&nbsp;
                              <a href="javascript::void()" class="btn btn-primary send_notification" data-id="{{ $notification->id }}"> <i class="fa fa-send"></i> Shoot your message </a>
                        </td>
                    </tr>
                @empty
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
    <!-- /page length options -->
</div>
<!--Add Notification popup modal-->
<div class="modal" id="add_new_pfu">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Notification</h4>
                <hr />
            </div>
            <!-- Modal body -->
            <form id="add_pfu_form" >
                <input type="hidden" value="" name="id" id="id" />
                <div class="modal-body">
                    @csrf
                    <span id="add_response"></span>
                    <span id="err_response"></span>
                    <div class="row">
                        <div class="col-md-12 form-pfu">
                            <label>Notififcation Type&nbsp;<span class="text-danger">*</span></label>
							
							<select id="notification_type" name="notification_type" class="form-control" required="required">
                                <option value="SMS">SMS</option>
                                <option value="Email">Email</option>
                                <option value="WhatsApp">WhatsApp</option>
                                <option value="Push">Push</option>
							</select>
                            <span id="start_date_err"></span>
                        </div>
                    </div>
					<div class="row">
                        <div class="col-md-12 form-pfu">
                            <label>Target User&nbsp;<span class="text-danger">*</span></label>
							<select id="target_user" name="target_user" class="form-control" required="required">
                                <option value="All">All</option>
                                <option value="Customer">Customer</option>
                                <option value="Workshop">Workshop</option>
                                <option value="Seller">Seller</option>
							</select><span id="start_date_err"></span>
                        </div>
                    </div>
					<div class="row">
                        <div class="col-md-12 form-pfu">
                            <label>Title&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="" name="title" id="title" required="required"  />
                            <span id="start_date_err"></span>
                        </div>
                    </div>
					<div class="row">
                        <div class="col-md-12 form-pfu">
                            <label>Subject&nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="" name="subject" id="subject" required="required"  />
                            <span id="start_date_err"></span>
                        </div>
                    </div>
					<div class="row">
                        <div class="col-md-12 form-pfu">
                            <label>Content&nbsp;<span class="text-danger">*</span></label>
                            <textarea class="textarea1" rows="5" id="content" name="content"></textarea>
                             <span id="start_date_err"></span>
                        </div>
                    </div>
					<div class="row">
                        <div class="col-md-12 form-pfu">
                            <label>URL</label>
                            <input type="text" class="form-control" placeholder="" name="url" id="url" />
                            <span id="start_date_err"></span>
                        </div>
                    </div>
					<div class="row">
                        <div class="col-md-12 form-pfu">
                            <label>File Upload</label>
							<div class="col-md-12 form-pfu">
							<img class="card-img img-fluid" id="edit_image" src="" alt="" style="max-width: 13%;height: 49%"/>
							 </div>
                            <input type="file" class="form-control" accept="image/jpeg,image/gif,image/png,application/pdf,image/x-eps"  placeholder="" name="file" id="file"/>
                            <span id="start_date_err"></span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center" style="margin: 10px;">
                        <div class="form-check form-check-inline">
                            <button type="submit" id="pfu_btn" class="btn bg-blue ml-3" >@lang('messages.Submit') <i class="icon-paperplane ml-2"></i></button>
                        </div>
                    </div>
				</div>
			</form>
        </div>
        <div class="modal-footer">       
        </div>
    </div>
    <script src="{{ url('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
    <script src="{{ url('/vendor/unisharp/laravel-ckeditor/adapters/jquery.js') }}"></script>
    <script>
        $('textarea1').ckeditor();
         $('.textarea1').ckeditor(); // if class is prefered.
		  
    </script>
</div>
<!--End-->
@endsection
@section('breadcrum')
<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="{{ url('/') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>@lang('messages.Home')</a>
            <a href="{{ url('/') }}" class="breadcrumb-item">@lang('messages.Admin')</a>
            <span class="breadcrumb-item active"> Notification </span>
        </div>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>
</div>
@stop
@push('scripts')
<script src="{{ url('validateJS/admin.js') }}"></script>
<script src="{{ url('validateJS/add_notification.js') }}"></script>
<script>
  $(document).ready(function(e) {
     $(document).on('click','.send_notification',function(){
		  var  send_btn = $(this); 
	      $.ajax({
			url: base_url+"/notification/send_notification",
			method: "GET",
			data: {notification_id:send_btn.data('id')},
			success: function(data){
			 $("#msg_response").html(data);
			},
			error: function(xhr, error){
				$("#msg_response").html(data);
			},
			complete: function(e , xhr , setting){
			  	$("#msg_response_popup").modal({
				   backdrop:'static',
				   keyboard:false
				});
			}
	    });
	 });
  });
</script>
@endpush


