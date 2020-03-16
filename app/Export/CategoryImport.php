<?php
namespace App\Export;
use App\User;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Products_group;
  
class CategoryImport implements ToModel{
    
	/**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
     public function model(array $row){
		
		 $data = [];
		foreach($row as $response){
		      $data[] = $response;
		   }
		  echo "<pre>";
		 print_r($data);exit;  
		 /*return new Products_group([
            'group_name'=>$row[2],
            'type'=>2, 
            'description' =>\sHelper::slug($row[2]),
			'language'=>$row[6]
		]);	*/
     }
	
}
?>