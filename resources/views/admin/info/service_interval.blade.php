<table class="table">
                           <tr>
                             <th>Makers / Model </th>
                             <td> {{ !empty($model_details->makers_name) ? $model_details->makers_name : "N/A" }} / {{ !empty($model_details->Modello) ? $model_details->Modello." >> ".$model_details->ModelloAnno : "N/A" }}</td>
                           </tr>
                           <tr>
                             <th>Version </th>
                             <td> {{ !empty($version_details->Versione) ? $version_details->Versione : "N/A" }} {{ !empty($version_details->Body) ? $version_details->Body : "N/A" }}</td>
                           </tr>
                           <tr>
                             <th>Service Interval id</th>
                             <td> {{ !empty($interval_info->service_interval_id) ? $interval_info->service_interval_id : "N/A" }}</td>
                           </tr>
                           <tr>
                             <th>Additional</th>
                             <td> {{ !empty($interval_info->additional) ? $interval_info->additional : "N/A" }}</td>
                           </tr>
                           <tr>
                             <th>Sort Order</th>
                             <td>{{ !empty($interval_info->sort_order) ? $interval_info->sort_order : "N/A" }}</td>
                           </tr>
                           <tr>
                             <th>Service Km</th>
                             <td>{{ !empty($interval_info->service_kms) ? $interval_info->service_kms : "N/A" }}</td>
                           </tr>
                           <tr>
                             <th>Service Months</th>
                             <td>
							{{ !empty($interval_info->service_months) ? $interval_info->service_months : "N/A" }}
							 </td>
                           </tr>
                           <tr>
                             <th>Interval Description For Kms.</th>
                             <th>{{ !empty($interval_info->interval_description_for_kms) ? $interval_info->interval_description_for_kms : "N/A" }}</th>
                           </tr>
                           <tr>
                             <th>Service Advisory Message</th>
                             <td>{{ !empty($interval_info->service_advisory_message) ? $interval_info->service_advisory_message : "N/A" }}</td>
                           </tr>
                           <tr>
                             <th>Standard Service Time <span class="text-danger">(hrs)</span></th>
                             <td>{{ !empty($interval_info->standard_service_time_hrs) ? $interval_info->standard_service_time_hrs : "N/A" }}</td>
                           </tr>
                           <tr>
                             <th>Automatic Transmission Time <span class="text-danger">(hrs)</span></th>
                             <td>{{ !empty($interval_info->automatic_transmission_time_hrs) ? $interval_info->automatic_transmission_time_hrs : "N/A" }}</td>
                           </tr>
                           <tr>
                             <th>Extra Time <span class="text-danger">(hrs)</span></th>
                             <td>{{ !empty($interval_info->extra_time_hrs) ? $interval_info->extra_time_hrs : "N/A" }}</td>
                           </tr>
                           <tr>
                             <th>Extra Time Description</th>
                             <td>{{ !empty($interval_info->extra_time_description) ? $interval_info->extra_time_description : "N/A" }}</td>
                           </tr>
                        </table>