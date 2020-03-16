<form id="bank_details_form_admin">
				@csrf
				<div class="form-group">
					<label>@lang('messages.OwnerName') <span class="text-danger">*</span></label>
                    <input type="hidden" name="workshop_id" id="workshop_id" class="form-control"  value="<?php if(!empty($bank_details->users_id)){ echo $bank_details->users_id; }else { echo $workshop_id; } ?>" required readonly  />
					<input type="text" name="owner_name" id="owner_name" class="form-control" placeholder="@lang('messages.OwnerName')" value="{{ $bank_details->account_holder_name ?? '' }}" required="required"  />
					<span id="account_holder_name_err"></span>
				</div>
				<div class="row">
					<div class="col-md-6 form-group">
						<label>@lang('messages.IBANCode')&nbsp;<span class="text-danger">*</span></label>
						<input type="text" class="form-control" placeholder="@lang('messages.IBANCode')" name="iban_code" id="iban_code" value="{{ $bank_details->iban_code ?? '' }}" required="required"  />
					</div>
					<div class="col-md-6 form-group">
						<label>@lang('messages.SwiftCode')&nbsp;<span class="text-danger">*</span></label>
						<input type="text" class="form-control" placeholder="@lang('messages.SwiftCode')" name="swift_code" id="swift_code" value="{{ $bank_details->swift_code ?? '' }}" required="required"  />
					</div>
				</div>
				<div class="row" style="margin-top:15px; margin-bottom:15px;">
					<div class="col-sm-12">
						<input type="hidden" id="country_edit_id" value="@if(!empty($bank_details->country_id)){{ $bank_details->country_id }} @endif">
						<input type="hidden" id="country_edit_name" value="@if(!empty($bank_details->country_name)){{ $bank_details->country_name }} @endif">
						<label>@lang('messages.BankCountry') &nbsp;<span class="text-danger">*</span>&nbsp;</label>
						<select class="form-control country" name="country" id="country_11">
							@if(!empty($bank_details->country_id))
								<option value="<?php echo $bank_details->country_id."@".$bank_details->country_name; ?>">{{ $bank_details->country_name }}</option>
							@endif
							<option value="0">@lang('messages.SelectCountryName')</option>
						</select>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-12 form-group">
						<label>@lang('messages.BankAddress') &nbsp;<span class="text-danger">*</span></label>
						<textarea type="text"  row="5" class="form-control" placeholder="@lang('messages.BankAddress')" name="bank_address" id="bank_address" required="required"  />{{ $bank_details->bank_address ?? '' }}</textarea>
					</div>
				</div>
				
				<div class="d-flex justify-content-between align-items-center">
					<button type="submit" id="bank_details_sbmt" class="btn bg-blue ml-3">@lang('messages.Submit') <i class="icon-paperplane ml-2"></i></button>
				</div>
			</form>