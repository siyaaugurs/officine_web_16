<ul class="media-list media-chat-scrollable mb-3">
					     	<li class="media">
								<div class="mr-3">
                                  1.
                                </div>
								<div class="media-body">
									<div class="media-title d-flex flex-nowrap">
										<a class="font-weight-semibold mr-3">Owner Name</a>
										<span class="font-size-sm text-muted text-nowrap ml-auto"> 
                                      </span>
									</div>
								   {{ $bank_details->account_holder_name ?? "Not Mentioned" }}
								</div>
							</li>
                            <li class="media">
								<div class="mr-3">
                                  2.
                                </div>
								<div class="media-body">
									<div class="media-title d-flex flex-nowrap">
										<a class="font-weight-semibold mr-3">IBAN Code</a>
										<span class="font-size-sm text-muted text-nowrap ml-auto"> 
                                      </span>
									</div>
									{{ $bank_details->iban_code ?? "Not Mentioned" }}
								</div>
							</li>
                            <li class="media">
								<div class="mr-3">
                                  6.
                                </div>
								<div class="media-body">
									<div class="media-title d-flex flex-nowrap">
										<a class="font-weight-semibold mr-3">Country Name</a>
										<span class="font-size-sm text-muted text-nowrap ml-auto"> 
                                      </span>
									</div>
								{{ $bank_details->country_name ?? "Not Mentioned" }} 
								</div>
							</li>
                            <li class="media">
								<div class="mr-3">
                                  2.
                                </div>
								<div class="media-body">
									<div class="media-title d-flex flex-nowrap">
										<a class="font-weight-semibold mr-3">Bank Address</a>
										<span class="font-size-sm text-muted text-nowrap ml-auto"> 
                                      </span>
									</div>
									{{ $bank_details->bank_address ?? "Not Mentioned" }}
								</div>
							</li>
                        </ul>