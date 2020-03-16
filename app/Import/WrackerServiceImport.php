<?php
namespace App\Import;
use App\User;
use App\Products_group;
use Maatwebsite\Excel\Concerns\ToModel;
use App\WorkshopServicesPayments;
use Auth;

  
class WrackerServiceImport implements ToModel{
   
   public $row_count = 0;
   public function model(array $row){
	   $current_number_of_row = ++$this->row_count;
	   $created_at = $updated_at = date('Y-m-d h:i:s');
	   if($current_number_of_row > 1){
	   if(!empty($row[0])){
		   /*Save in workshop wrecker services*/
		    $response =  \App\WorkshopWreckerServices::updateOrCreate(['users_id'=>Auth::user()->id , 'wracker_services_id'=>$row[0] ], 
			['users_id'=>Auth::user()->id , 'wracker_services_id'=>$row[0] , 'status'=>'A']
			);
			if($response != NULL){
			    $save_detail = \App\WorkshopWreckerServiceDetails::updateOrCreate(['workshop_wrecker_services_id'=>$response->id , 'wrecker_service_type'=>$row[9]] , 
				['workshop_wrecker_services_id'=>$response->id , 'wrecker_service_type'=>$row[9] , 'total_time_arrives'=>$row[10] , 
				'hourly_cost'=>$row[11] ,'cost_per_km'=>$row[12] , 'call_cost'=>$row['13'] , 'max_appointment'=>$row['14']]
				);  
			  }
		   /*End*/
		}
		 }
	}
  }


?>