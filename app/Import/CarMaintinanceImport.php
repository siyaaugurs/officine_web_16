<?php
namespace App\Import;
use App\User;
use App\Products_group;
use Maatwebsite\Excel\Concerns\ToModel;
use App\WorkshopServicesPayments;
use Auth;
  
class CarMaintinanceImport implements ToModel{
   
   public $row_count = 0;
   
   public function model(array $row){
    $current_number_of_row = ++$this->row_count;
	   $created_at = $updated_at = date('Y-m-d h:i:s');
	   if($current_number_of_row > 1){
            /* if(!empty($row[0])){
                $insert_arr = [
                            'workshop_id'=>Auth::user()->id, 
                            'category_type'=>12,
                            'hourly_rate'=>$row[1],
                            'maximum_appointment'=>$row[2],
                            'created_at'=>$created_at,
                            'updated_at'=>$updated_at
                            ];
                $where_clause = ['workshop_id'=>Auth::user()->id , 'type'=>12];         
                $response = WorkshopServicesPayments::updateOrCreate($where_clause ,  $insert_arr);     
            } */
            if(!empty($row[0]) && !empty($row[5]) && !empty($row[6]) ){
                $service_details = \App\ItemsRepairsServicestime::find($row[0]);
                if($service_details != NULL) {
                    $insert_arr = [
                        'workshop_id'=>Auth::user()->id, 
                        'items_repairs_servicestimes_id'=>$row[0], 
                        'hourly_cost'=>$row[5],
                        'max_appointment'=>$row[6],
                        'deleted_at'=>NULL,
                        'created_at'=>$created_at,
                        'updated_at'=>$updated_at
                    ];
                    $where_clause = ['workshop_id'=>Auth::user()->id, 'items_repairs_servicestimes_id'=>$row[0] ];  
                    $response = \App\WorkshopCarMaintinanceServiceDetails::updateOrCreate($where_clause ,  $insert_arr);
                } 
            }
        }
	}
 
}
?>