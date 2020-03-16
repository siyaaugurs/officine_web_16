<?php
namespace App\Import;
use App\User;
use App\Products_group;
use Maatwebsite\Excel\Concerns\ToModel;
use App\WorkshopServicesPayments;
use Auth;

  
class CarwashingImport implements ToModel{
   
   public $row_count = 0;
   
   public function model(array $row){
	   $current_number_of_row = ++$this->row_count;
	   $created_at = $updated_at = date('Y-m-d h:i:s');
	   if($current_number_of_row > 1){
	   		if(!empty($row[0]) && !empty($row[4]) && !empty($row[5]) && !empty($row[7])){
				$insert_arr = [
					'users_id'=>Auth::user()->id,
					'category_id'=>$row[0],
					'car_size' =>$row[4],
					'hourly_rate'=>$row[5], 
					'max_appointment'=>$row[7],
					'is_deleted_at'=>NULL,
					'type' =>1
				];
				$where_clause = ['users_id' => Auth::user()->id , 'category_id' => $row[0], 'car_size' =>  $row[4]];
				$response = \App\Services::updateOrCreate($where_clause ,  $insert_arr);	 
	       	}
		}
	}
  }


?>