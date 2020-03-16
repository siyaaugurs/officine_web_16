<ul class="media-list media-chat-scrollable mb-3">
                    <li class="media">
                        <div class="mr-3">
                            1.
                        </div>
                        <div class="media-body">
                            <div class="media-title d-flex flex-nowrap">
                                <a class="font-weight-semibold mr-3">Name</a>
                                <span class="font-size-sm text-muted text-nowrap ml-auto"> 
                                </span>
                            </div>
                                {{ $customer_detail->f_name." ".$customer_detail->l_name }}
                        </div>
                    </li>
                    <li class="media">
                        <div class="mr-3">
                            2.
                        </div>
                        <div class="media-body">
                            <div class="media-title d-flex flex-nowrap">
                                <a class="font-weight-semibold mr-3">Email</a>
                                <span class="font-size-sm text-muted text-nowrap ml-auto"> 
                                </span>
                            </div>
                            {{ $customer_detail->email }}
                        </div>
                    </li>
                    <li class="media">
                        <div class="mr-3">
                            3.
                        </div>
                        <div class="media-body">
                            <div class="media-title d-flex flex-nowrap">
                                <a class="font-weight-semibold mr-3">Mobile Number</a>
                                <span class="font-size-sm text-muted text-nowrap ml-auto"> 
                                </span>
                            </div>
                                {{ $customer_detail->mobile_number }}
                        </div>
                    </li>
                    <li class="media">
                        <div class="mr-3">
                            4.
                        </div>
                        <div class="media-body">
                            <div class="media-title d-flex flex-nowrap">
                                <a class="font-weight-semibold mr-3">Registration Date</a>
                                <span class="font-size-sm text-muted text-nowrap ml-auto"> 
                                </span>
                            </div>
                                {{ (date("Y-m-d", strtotime($customer_detail->created_at)))  }}
                        </div>
                    </li>
                    <li class="media">
                        <div class="mr-3">
                            5.
                        </div>
                        <div class="media-body">
                            <div class="media-title d-flex flex-nowrap">
                                <a class="font-weight-semibold mr-3">User Name</a>
                                <span class="font-size-sm text-muted text-nowrap ml-auto"> 
                                </span>
                            </div>
                                {{ $customer_detail->user_name }}
                        </div>
                    </li>
                    <li class="media">
                        <div class="mr-3">
                            6.
                        </div>
                        <div class="media-body">
                            <div class="media-title d-flex flex-nowrap">
                                <a class="font-weight-semibold mr-3">Profile</a>
                                <span class="font-size-sm text-muted text-nowrap ml-auto"> 
                                </span>
                            </div>
                            @if($customer_detail->profile_image != NULL)         
                                <img class="img-thumbnail" src='{{ url("storage/profile_image/$customer_detail->profile_image") }}' height="120" width="200">
                            @else
                                <img src="{{ URL::to('storage/user.png') }}" style="height:100px;width:100px">       
                            @endif
                        </div>
                    </li>
                </ul>