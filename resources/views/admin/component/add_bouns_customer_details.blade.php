<form id="" action="{{ url('admin_ajax/add_customer') }}" method="POST">
    @csrf
    <div class="form-group">
         <input type="hidden" name="customer_id" id="customer_id" class="form-control"  value="<?php if(!empty($customer_id))echo $customer_id; ?>" required  />
    </div>
    <div class="row">
        <div class="col-md-12 form-group">
            <label>Add Bouns Amount &nbsp;<span class="text-danger">*</span></label>
            <input type="text" class="form-control" placeholder="Add Bouns Amount" name="user_bouns" id="user_bouns" value="" required="required"  />
        </div>
		 <div class="col-md-12 form-group">
            <label>Add Bouns Description &nbsp;<span class="text-danger">*</span></label>
            <textarea  class="form-control" placeholder="Add Bouns description" name="user_bouns_detail" id="user_bouns_detail" value="" required="required"></textarea>
        </div>
        
    </div>

    <div id="response"></div>
    <div class="d-flex justify-content-between align-items-center">
        <button type="submit" id="customer_details_sbmt" class="btn bg-blue ml-3">@lang('messages.Submit') <i class="icon-paperplane ml-2"></i></button>
    </div>
</form>