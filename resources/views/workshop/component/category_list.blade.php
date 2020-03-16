<table class="table">
         <thead>
          <tr>
            <th>SN.</th>
            <th>Services</th>
            <th>Description</th>
            <th>Car Size</th>
            <th>Time</th>
            <th>Hourly Cost</th>
            <th>Price</th>
            <th>Max. Appointment</th>
            <th>Action</th>
          </tr>
         </thead> 
         <tbody>
         @php  $i = 1; @endphp
          @foreach($car_washing_category as $services) 
           
             @foreach($car_size as $key=>$size_value)
               @php 
                 $enctype_id = $services->id."/".$key;
                 $enc_type_s_id = base64_encode($enctype_id); 
                 $service_price = sHelper::car_wash_price_max_appointment(Auth::user()->id , $services->id , $key);
               @endphp
               <tr>
                <td>{{ $i }}</td>
                <td>{{ $services->category_name }}</td>
                <td>{{ $services->description }}</td>
                <td>{{ $size_value }}</td>
                <td>
                @php
                 $service_average_time =  sHelper::get_car_wash_service_time($key , $services->id);
                 @endphp
                {{ !empty($service_average_time) ? $service_average_time : "N/A" }}</td>
                <td>&euro;&nbsp;{{ !empty($service_price['hourly_rate']) ? $service_price['hourly_rate'] : "N/A" }}</td>
                <td>
               @php $price =  sHelper::calculate_service_price($service_average_time ,$service_price['hourly_rate']) 
               @endphp 
               &euro;&nbsp;{{ $price }}
               </td>
                <td>{{ !empty($service_price['max_appointment']) ? $service_price['max_appointment'] : "N/A" }}</td>
                <td>
                    <a href="#" data-serviceid="<?php echo $services->id; ?>" data-size="<?php if(!empty($key))echo $key; ?>" data-toggle="tooltip" data-placement="top" title="Edit services details"  class="btn btn-primary btn-sm edit_service_details"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;
                   
                    <a data-toggle="tooltip" data-placement="top" title="view services details" href='{{ url("vendor/view_services/$enc_type_s_id") }}' class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-eye-open"></i></a>
                </td> 
           </tr>
             @php $i++ @endphp
             @endforeach
          @endforeach 
         </tbody> 
        </table> 