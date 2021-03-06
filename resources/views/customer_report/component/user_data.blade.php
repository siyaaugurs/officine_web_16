<table class="table">
            <thead>
                <tr> 
                    <td colspan="7">
                    </td> 
                </tr>
                <tr>
                     <th>@lang('messages.SN')</th>
                    <th>@lang('messages.Name')</th>
                    <th>@lang('messages.ClientId')</th>
                    <th>@lang('messages.Email')</th>
                    <th>@lang('messages.Mobile')</th>
                    <th>@lang('messages.Roletype')</th>
                    <th>@lang('messages.RegDate')</th>
                    <th class="text-center">@lang('messages.Actions')</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($all_customers as $users)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $users->f_name." ".$users->l_name }}</td>
                    <td>(OFFICINE{{ $users->id }})</td>
                    <td>{{ $users->email }}</td>
                    <td>{{ $users->mobile_number }}</td>
                    <td>
                        @if($users->roll_id == 1)
                          <span class="badge badge-success">@lang('messages.Seller')</span>  
                        @elseif($users->roll_id == 2)
                          <span class="badge badge-primary">@lang('messages.Workshop')</span>
                        @elseif($users->roll_id == 3)
                         <span class="badge badge-danger">@lang('messages.Customers')</span>
                        @endif
                    </td>
                    <td>{{ date('d-m-Y H:i:s' , strtotime($users->created_at ) )}}</td>
                    <td class="text-center">
                       @if($users->roll_id != 3)
                        <div class="list-icons">
                            <div class="dropdown">
                                <a href="#" class="list-icons-item" data-toggle="dropdown">
                                    <i class="icon-menu9"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                   @if($users->roll_id == 2 || $users->roll_id == 1) 
                                    @php 
                                      $encrypt_id=  base64_encode($users->id)
                                    @endphp
                                    <a target="_blank" href='{{ url("admin/company_profiles/$encrypt_id") }}' class="dropdown-item"><i class="icon-eye mr-3"></i>@lang('messages.ViewCompanyDetails')</a>
                                   @endif 
                                     <!-- <a href='#' class="dropdown-item"><i class="icon-eye mr-3"></i>@lang('messages.ViewDetails')</a> -->
                                </div>
                            </div>
                        </div>
                        @else
                        @php 
                            $enc_id=  base64_encode($users->id)
                        @endphp
                        <div class="list-icons">
                            <div class="dropdown">
                                <a href="#" class="list-icons-item" data-toggle="dropdown">
                                    <i class="icon-menu9"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href='{{ url("admin/customers_profile/$enc_id") }}' class="dropdown-item"><i class="icon-eye mr-3"></i>View Details</a>
                                </div>
                            </div>
                        </div>
                       @endif 
                    </td>
                       
                </tr>
            @empty
                <tr>
                <td colspan="5">Customers Not Available</td>
                </tr>
            @endforelse
            </tbody>
        </table>