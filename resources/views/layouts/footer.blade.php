<!-- Footer -->
<div class="navbar navbar-expand-lg navbar-light">
	<div class="text-center d-lg-none w-100">
		<button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse" data-target="#navbar-footer">
			<i class="icon-unfold mr-2"></i>
			Footer
		</button>
	</div>

	<div class="navbar-collapse collapse" id="navbar-footer">
		<span class="navbar-text">
			&copy; {{ date('Y') }} <a href="#">Officine Top</a> by <a href="#" target="_blank">OfficineTop</a>
		</span>
		<ul class="navbar-nav ml-lg-auto">
		<li class="nav-item"><a href="{{url("policy_pages/1")}}" class="navbar-nav-link font-weight-semibold"  target="_blank" ><span class="text-pink-400"><i class="icon-cart2 mr-2"></i> privacy disclaimer</span></a></li>
			<li class="nav-item"><a href="{{url("policy_pages/2")}}" class="navbar-nav-link" target="_blank"><i class="icon-lifebuoy mr-2"></i>  Term & Conditions</a></li>
			<li class="nav-item"><a href="{{url("policy_pages/3")}}" class="navbar-nav-link  target="_blank" font-weight-semibold"><span class="text-pink-400"><i class="icon-cart2 mr-2"></i> cookies information</span></a></li>
			<li class="nav-item"><a href="{{url("policy_pages/4")}}" class="navbar-nav-link" target="_blank"><i class="icon-file-text2 mr-2"></i> How it works</a></li>
		</ul>
	</div>
</div>
<!--Message popup script start-->
<div class="modal" id="msg_response_popup">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                    <h4 class="modal-title" id="myModalLabel"><i class="text-white icon-megaphone mr-3 icon-2x"></i> Message  </h4>
                    <hr />
                  </div>
                  <!-- Modal body -->
                        <div class="modal-body">
                          <div class="row ">
							 <div class="col-md-12">
                                  <div id="msg_response"></div>  
                              </div>
                          </div>
                       </div>
                  </div>
                  <div class="modal-footer">       
                </div>
              </div>
            </div>
<!--End-->
<!-- /footer -->
<script src="{{ asset('validateJS/formValidation.js') }}"></script>
<script src="{{ asset('validateJS/common.js') }}"></script>
<script src="{{ asset('validateJS/vendors.js') }}"></script>
<script>
	$('#start_time, #end_time ').datetimepicker({
		format: 'HH:mm'
	});
	 $('[data-toggle="tooltip"]').tooltip(); 
</script>