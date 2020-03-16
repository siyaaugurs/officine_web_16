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
								   {{ $business_details->owner_name }}
								</div>
							</li>
                            <li class="media">
								<div class="mr-3">
                                  2.
                                </div>
								<div class="media-body">
									<div class="media-title d-flex flex-nowrap">
										<a class="font-weight-semibold mr-3">Business name</a>
										<span class="font-size-sm text-muted text-nowrap ml-auto"> 
                                      </span>
									</div>
									{{ $business_details->business_name ?? "Not mentioned" }}
								</div>
							</li>
                            <li class="media">
								<div class="mr-3">
                                  3.
                                </div>
								<div class="media-body">
									<div class="media-title d-flex flex-nowrap">
										<a class="font-weight-semibold mr-3">Registration proof</a>
										<span class="font-size-sm text-muted text-nowrap ml-auto"> 
                                      </span>
									</div>
									 @if(!empty($business_details->registration_proof) || !empty($business_details->address_proof))
                                        @php 
                                          $reg_proof = $business_details->registration_proof;
                                          $adrs_proof = $business_details->address_proof;
                                        @endphp  
                                     @else 
                                      @php 
                                          $reg_proof = "";
                                           $adrs_proof = "";
                                        @endphp 
                                     @endif
                                    <a target="_blank" href='{{ asset("storage/business_details/$reg_proof") }}'>{{ $business_details->registration_proof ?? "Not mentioned" }}</a>
								</div>
							</li>
                            <li class="media">
								<div class="mr-3">
                                  4.
                                </div>
								<div class="media-body">
									<div class="media-title d-flex flex-nowrap">
										<a class="font-weight-semibold mr-3">Address proof</a>
										<span class="font-size-sm text-muted text-nowrap ml-auto"> 
                                      </span>
									</div>
									 <a target="_blank" href='{{ asset("storage/business_details/$adrs_proof") }}'>{{ $business_details->address_proof ?? "Not mentioned" }}</a> 
								</div>
							</li>
                            <li class="media">
								<div class="mr-3">
                                  5.
                                </div>
								<div class="media-body">
									<div class="media-title d-flex flex-nowrap">
										<a class="font-weight-semibold mr-3">Address</a>
										<span class="font-size-sm text-muted text-nowrap ml-auto"> 
                                      </span>
									</div>
									<!-- {{ $business_details->address_1 ?? "N/A  ," }}
                                    {{ $business_details->address_2 ?? "N/A  ," }}
                                    {{ $business_details->address_3 ?? "N/A  ," }}
                                    {{ $business_details->landmark ?? "N/A " }} -->
									{{ $business_details->registered_office ?? "Not mentioned" }}
								</div>
							</li>
                            <li class="media">
								<div class="mr-3">
                                  6.
                                </div>
								<div class="media-body">
									<div class="media-title d-flex flex-nowrap">
										<a class="font-weight-semibold mr-3">About business</a>
										<span class="font-size-sm text-muted text-nowrap ml-auto"> 
                                      </span>
									</div>
									{{ $business_details->about_business ?? "N/A " }}
								</div>
							</li>
                            <li class="media">
								<div class="mr-3">
                                  6.
                                </div>
								<div class="media-body">
									<div class="media-title d-flex flex-nowrap">
										<a class="font-weight-semibold mr-3">Term and condition  </a>
										<span class="font-size-sm text-muted text-nowrap ml-auto"> 
                                      </span>
									</div>
									@if(!empty($business_details->term_and_condition))
                                    {{ "Accepted "}}
                                    @else
                                      {{ "Not Accepted "}}
                                    @endif
								</div>
							</li>
                        </ul>