<?php
namespace App\Import;
use App\User;
use App\Products_group;
use Maatwebsite\Excel\Concerns\ToModel;
use DB;
use sHelper;
use App\Rim;
use App\RimDetails;
  
class RimImport implements ToModel{
   
   public $row_count = 0;
   public function model(array $row){
	 	$current_number_of_row = ++$this->row_count;
	    if($current_number_of_row > 1){
            $created_at = $updated_at = date('Y-m-d h:i:s');
            $unique_id =  $row[0].uniqid();
		    /*Convert in json for rim response*/
			$rim_response = json_encode(['ET'=>$row[3],
			                              'size'=>$row[4], 
										  'type'=>$row[5], 
			                              'alcar'=>$row[1], 
										  'ezFrom'=>$row[6], 
										  'connection'=>$row[7], 
										  'workmanship'=>$row[8] , 
										  'manufacturer'=>$row[9], 
		                                  'typeDescription'=>$row[10], 
										]);
		   /*End*/
		   /*Convert in json for rim details response*/
		   $rim_detail_response = json_encode(['items'=>['pic'=>'' , 'type'=>$row[11], 'price'=>$row[13], 
		                           'stock'=>'' , 'itemId'=>'' ,'weight'=>$row[12], 
								   'date_de'=>'', 'is3PMSF'=>'' , 'kbprice'=>'' , 'pic_t24'=>'', 'text_de'=>'' ,
								   'discount'=>'' , 'imageUrl'=>'' , 'itemDate'=>'' , 
								   'itemText'=>'' , 'ownStock'=>'','matchcode'=>$row[14] ,'org_price'=>$row[13] , 
								   'source_de'=>'' ,'ean_number'=>$row[15],
								   'itemSource'=>'', 'description'=>$row[16],
								   'feedback_de'=>'' , 'description1'=>$row[18],  
								   'shortFeedback'=>'' , 'pr_description'=>$row[19] ,'wholesalerArticleNo'=>$row[20], 
								   'manufacturer_description'=>$row[21], 'manufacturer_item_number'=>$row[22]
								   ]]);
								   /*End*/								
			$insret_arr = ['maker_name'=>$row[9], 'maker_slug'=>sHelper::slug($row[9]),
						           'alcar'=>$row[1] , 
								   'dia_meter'=>$row[2],
								   'rim_response'=>$rim_response,
								   'updated_at'=>$updated_at,
								];
								/**/				  
								
			$insert_rim_response = ['rim_id_id'=>0 ,'rim_alcar'=>$row[1], 
								'number_of_holes'=>$row[23],
								'our_product_name'=>$row[24], 
								'our_description'=>$row[25], 
								'bar_code'=>$row[26], 
								'for_pair'=>$row[27], 
								'color'=>$row[28], 
								'meta_key_title'=>$row[34], 
								'meta_key_words'=>$row[35],
								'seller_price'=>$row[29], 
								'products_quantity'=>$row[29], 
								'minimum_quantity'=>$row[30], 
								'tax'=>$row[31], 
								'tax_value'=>$row[32], 
								'our_assemble_time'=>$row[36]
							];	
		if(empty($row[0])){
			$insret_arr['type'] = 2;
		    $insret_arr['created_at'] = $created_at;
		    $insret_arr['unique_id'] = uniqid();
		}
		echo "<pre>";
		$rim_response = \App\Rim::updateOrCreate(['id'=>$row[0]] , $insret_arr);
		print_r($rim_response);
		if($rim_response){
			if($rim_response->type == 1){
				$where_clause = ['rim_alcar'=>$rim_response->alcar];
				
			}
			if($rim_response->type == 2){
				$insert_rim_response['rim_alcar'] = $rim_response->alcar;
				$insert_rim_response['rim_details_response'] = $rim_detail_response;
				$insret_arr['created_at'] = $created_at;
 				$insret_arr['updated_at'] = $updated_at;
			    if(empty($row[0])){
					$insret_arr['unique_id'] = $rim_response->id.uniqid();
				}
				$where_clause = ['rim_id'=>$rim_response->id];
			}
			$rim_detail_response = RimDetails::updateOrCreate($where_clause ,$insert_rim_response);
			print_r($rim_detail_response);
		}												  
	   }
	}
  }


?>