<?php
namespace App\Export;
use App\Products_group;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use sHelper;
use Auth;
use DB;
  
class WrackerService implements FromCollection , WithHeadings{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(){
		    $data_arr = [];
			/*Get category */
			 $wrecker_services = \App\WrackerServices::get_wracker_services();
			 if($wrecker_services->count() > 0){
			     foreach($wrecker_services as $service){
					 $service_details =  \App\WorkshopWreckerServices::where([['users_id','=', Auth::user()->id] , ['wracker_services_id' , '=' , $service->id]])->first();
				    // if($service_details != NULL){
					    for($i = 1; $i <=2; $i++){
							 if($i == 1) $service_type = "Service by Appointment";
							 if($i == 2) $service_type = "Emergency Service";
							 $new_cat_fields = [];
							 $new_cat_fields['id'] = $service->id;
							 $new_cat_fields['service_name'] = $service->services_name;
							 $new_cat_fields['time_per_km'] = $service->time_per_km;
							 $new_cat_fields['loading_unloading_time'] = $service->loading_unloading_time;
							 $new_cat_fields['type_of_weight_1_2000'] = $service->type_of_weight_1_2000;
							 $new_cat_fields['type_of_weight_2000_3000'] = $service->type_of_weight_1_2000;
							 $new_cat_fields['description'] = $service->description;
							 $new_cat_fields['status'] = $service->status;
							 $new_cat_fields['service_type'] = $service_type;
							 /*Find Workshop Service details*/
							 $new_cat_fields['service_type_status'] = $i;
							 $new_cat_fields['total_time_arrives'] = '';
							 $new_cat_fields['hourly_cost'] = '';
							 $new_cat_fields['cost_per_km'] = '';
							 $new_cat_fields['call_cost'] = 0;
							 $new_cat_fields['max_appointment'] = '';
							 if($service_details != NULL){
								 $service_details_response = \App\WorkshopWreckerServiceDetails::where([['wrecker_service_type' , '=' , $i] , ['workshop_wrecker_services_id' , '=' , $service_details->id]])->first();
								 if($service_details_response != NULL){
									$new_cat_fields['total_time_arrives'] = $service_details_response->total_time_arrives;
									$new_cat_fields['hourly_cost'] = $service_details_response->hourly_cost;
									$new_cat_fields['cost_per_km'] = $service_details_response->cost_per_km;
									$new_cat_fields['call_cost'] = $service_details_response->call_cost;
									$new_cat_fields['max_appointment'] = $service_details_response->max_appointment;
								   }
							 }
							 /*End*/
						$data_arr[] = $new_cat_fields;	 
						}
					//   }
					}
			   }
			return collect($data_arr);
   }
		 
   public function headings(): array
    {
        return [
		    'ID',
            'Services Name',
			'Times / Km. (in Min .)',
            'Loading Uploading Time',
			'Type of Weight 1 - 2000',
			'Type of Weight 2000 - 3000',
			'Description',
			'status',
			'Service Type',
			'Service Type Status',
			'Total time arrives',
			'Hourly Cost',
			'Cost Per km.',
			'Call cost',
			'Maximum Appointment',
        ];
    }
}