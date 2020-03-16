<form id="customer_details_form_admin" >
    @csrf
    <div class="form-group">
        <input type="hidden" name="customer_id" id="customer_id" class="form-control"  value="<?php if(!empty($customer_id))echo $customer_id; ?>" required  />
    </div>
    <div class="row">
        <div class="col-md-6 form-group">
            <label>@lang('messages.Name')&nbsp;<span class="text-danger">*</span></label>
            <input type="text" class="form-control" placeholder="@lang('messages.UserName')" name="user_name" id="user_name" value="{{ $customers_detail->f_name }}" required="required"  />
        </div>
        <div class="col-md-6 form-group">
            <label>@lang('messages.MobileNumber')&nbsp;<span class="text-danger">*</span></label>
            <input type="number" class="form-control" placeholder="@lang('messages.MobileNumber')" name="mobile_number" id="mobile_number" value="{{ $customers_detail->mobile_number  }}" required="required" />
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 form-group">
            <label>@lang('messages.Email')&nbsp;<span class="text-danger">*</span></label>
            <input type="text" class="form-control" placeholder="@lang('messages.Email')" name="email" id="email" value="{{ $customers_detail->email  }}" required />
        </div>
        <div class="col-md-6 form-group">
            <label>@lang('messages.Profile')</label>
            <input type="hidden" name="profile_pic" id="profile_pic" value="{{ $customers_detail->profile_image  }}">
            <input type="file" class="form-control" placeholder="@lang('messages.ProfileImage')" name="customer_profile" id="customer_profile" />
        </div>
    </div>
    
    <div id="response"></div>
    <div class="d-flex justify-content-between align-items-center">
        <button type="submit" id="customer_details_sbmt" class="btn bg-blue ml-3">@lang('messages.Submit') <i class="icon-paperplane ml-2"></i></button>
    </div>
</form>