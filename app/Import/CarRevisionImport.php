<?php
namespace App\Import;
use App\User;
use App\Products_group;
use Maatwebsite\Excel\Concerns\ToModel;
use App\WorkshopServicesPayments;
use Auth;

  
class CarRevisionImport implements ToModel{
   
   	public $row_count = 0;
   
   	public function model(array $row){
		$current_number_of_row = ++$this->row_count;
		$created_at = $updated_at = date('Y-m-d h:i:s');
	   	if($current_number_of_row > 1){
			if(!empty($row[0])  && !empty($row[2]) && !empty($row[3])){
				$insert_arr = [
					'workshop_id' => Auth::user()->id,
					'category_id' => $row[0],
					'price' => $row[2],
					'max_appointment' => $row[3]
				];
				$where_clause = ['workshop_id' => Auth::user()->id , 'category_id' => $row[0]];
				$response = \App\WorkshopCarRevisionServices::updateOrCreate($where_clause ,  $insert_arr);
			}
		}
	}
}


?>