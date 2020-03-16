<?php
namespace App\Import;
use App\User;
use App\Products_group;
use Maatwebsite\Excel\Concerns\ToModel;
use App\WorkshopServicesPayments;
use Auth;

  
class AssembleServiceImport implements ToModel{
   
   	public $row_count = 0;
   
   	public function model(array $row){
		$current_number_of_row = ++$this->row_count;
		$created_at = $updated_at = date('Y-m-d h:i:s');
		if($current_number_of_row > 1){
			$service_details = \App\Users_category::check_spare_services_categories(Auth::user()->id, $row[0]);
			if($service_details != NULL) {
				if(!empty($row[0]) && !empty($row[3]) && !empty($row[4]) ){
					$insert_arr = [
						'workshop_id' => Auth::user()->id , 
						'categories_id' => $row[0] , 
						'description'=>$row[2]  , 
						'max_appointment'=>$row[4], 
						'hourly_rate'=>$row[3] , 
						'status'=>'A'
					];
					$where_clause = ['workshop_id' => Auth::user()->id , 'categories_id' => $row[0]];	
					$response = \App\WorkshopAssembleServices::updateOrCreate($where_clause ,  $insert_arr);
				}
			}
		}
	}
}


?>