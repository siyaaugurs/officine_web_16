<?php
namespace App\Import;
use App\User;
use App\Products_group;
use Maatwebsite\Excel\Concerns\ToModel;
use App\WorkshopServicesPayments;
use Auth;

  
class TyreServiceImport implements ToModel{
   
   public $row_count = 0;
   
   	public function model(array $row){
		$current_number_of_row = ++$this->row_count;
		$created_at = $updated_at = date('Y-m-d h:i:s');
		if($current_number_of_row > 1){
			$service_details = \App\Category::where([['id', '=', $row[0]], ['category_type', '=', 23]])->first();
			if($service_details != NULL) {
				if(!empty($row[0]) && !empty($row[6]) && !empty($row[8])){
					$insert_arr = [
								'workshop_user_id' => Auth::user()->id, 
								'category_id' => $row[0], 
								'hourly_rate' => $row[6],
								'max_appointment' => $row[8],
								'created_at' => $created_at,
								'updated_at' => $updated_at
							];
					$where_clause = ['workshop_user_id'=>Auth::user()->id , 'category_id'=>$row[0]];			
					$response = \App\WorkshopTyre24Details::updateOrCreate($where_clause ,  $insert_arr);	       
				}
			}
		}
	}
}


?>