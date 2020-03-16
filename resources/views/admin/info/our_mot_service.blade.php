<table class="table">
                           <tr>
                             <th>Makers / Model </th>
                                
                                @if($model_details == "All Models")
                                    @php
                                        $modal_name = "All Models";
                                    @endphp
                                @elseif($model_details == "N/A")
                                    @php
                                        $modal_name = "N/A";
                                    @endphp
                                @else
                                    @php
                                        $modal_name =  $model_details->Modello." >> ".$model_details->ModelloAnno;
                                    @endphp
                                @endif
                                
                                @if($maker_details == "All Makers")
                                    @php
                                        $makers_name = "All Makers";
                                    @endphp
                                @else
                                    @php
                                        $makers_name =  $maker_details->Marca;
                                    @endphp
                                @endif
                             <td> 
                                 {{ $makers_name }}/{{ $modal_name }}
                             </td>
                           </tr>
                           <tr>
                             <th>Version </th>
                             <td> 
                                @if($version_details == "All Versions")
                                    {{ "All Versions" }}
                                @elseif($version_details == "N/A")
                                    {{ "N/A" }}
                                @else 
                                    {{ $version_details->Versione  }} {{ $version_details->Body  }}
                                @endif
                            </td>
                           </tr>
                           <tr>
                             <th>Service Name</th>
                             <td> {{ !empty($services_details->service_name) ? $services_details->service_name : "N/A" }}</td>
                           </tr>
                           <tr>
                             <th>Description</th>
                             <td>{{ !empty($services_details->service_description) ? $services_details->service_description : "N/A" }}</td>
                           </tr>
                           <tr>
                             <th>Service Km.</th>
                             <td>{{ !empty($services_details->service_km) ? $services_details->service_km : "N/A" }}</td>
                           </tr>
                           <tr>
                             <th>Month</th>
                             <td>{{ !empty($services_details->month) ? $services_details->month : "N/A" }}</td>
                           </tr>
                        </table>