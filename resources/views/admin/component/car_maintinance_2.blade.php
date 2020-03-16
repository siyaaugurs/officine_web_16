<table class="table">
            <thead>
                <tr>
                    <th>@lang('messages.SN')</th>
                    <th>@lang('messages.Item')</th>
                    <th>@lang('messages.ap')</th>
                    <th>@lang('messages.ds')</th>
                    <th>@lang('messages.Services')</th>
                   <th>@lang('messages.Time')</th>
                   <!-- <th>@lang('messages.Info')</th> -->
                    <th>@lang('messages.language')</th>
                    <th>@lang('messages.Status')</th>
                    <th>Edit</th>
                    <th>Part list</th>
                </tr>
            </thead>
            <tbody>
            @php   $sn = 0; @endphp
             @forelse ($car_maintinance_service_list as $details)
               @php   $sn++; @endphp
                <tr>
                    <td>{{ $sn }}</td>
                    <td>{{ !empty($details->item) ? $details->item : "N/A" }}</td>
                    <td>{{ !empty($details->front_rear) ? $details->front_rear : "N/A" }}</td>
                    <td>{{ !empty($details->left_right) ? $details->left_right : "N/A" }}</td>
                    <td>{{ !empty($details->action_description) ? $details->action_description : "N/A" }}</td>
                   <td>
                   {{ !empty($details->time_hrs) ? $details->time_hrs : "N/A" }}
                   </td> 
                    <!--<td>{{ !empty($details->id_info) ? $details->id_info : "N/A" }}</td> -->
                    <th>{{ !empty($details->language) ? $details->language : "N/A" }}</th>
                    <td>
                      <?php
						if(!empty($details->status)){
						   if($details->status == "P"){
							   ?>
							   <a href="#" data-serviceitemid="<?php if(!empty($details->id))echo $details->id; ?>" class="change_item_service_status"  data-status="A"><i class="fa fa-toggle-off"></i></a>
							   <?php
							 }
						   else if($details->status == "A"){
							   ?>
							   <a href="#"  class="change_item_service_status" data-serviceitemid="<?php if(!empty($details->id))echo $details->id; ?>" data-status="P"><i class="fa fa-toggle-on"></i></a>
							   <?php
							 }	 
						 }
						?>
                    </td>
                    <td>
                        <a href="#"  class="btn btn-primary edit_maintenance_service" data-serviceitemid="<?php if(!empty($details->id))echo $details->id; ?>" ><i class="fa fa-edit"></i></a>
                    </td>
                    <td>
                        <?php 
						if($details->type == 2){
						    ?>
							<a href='{{ url("admin/car_maintinance/kpart_list/$details->id?type=0") }}'   class="btn btn-primary kpartlist_car_maintinance" data-serviceitemid="" >
                            <i class="fa fa-list"></i></a>
							<?php
						  }
						 else{
						     ?>
							 <a href='{{ url("admin/car_maintinance/kpart_list/$details->id?type=0") }}' target="_blank"  class="btn btn-primary kpartlist_car_maintinance" data-serviceitemid="<?php if(!empty($details->id))echo $details->id; ?>" >
                            <i class="fa fa-list"></i></a>
							 <?php 
						  }  
						?>
                        
                    </td>
                </tr>
            @empty
                <tr>
                <td colspan="5">Records Not Founds !!!</td>
                </tr>
            @endforelse
            </tbody>
        </table>