<?php
namespace App\Import;
use App\User;
use App\Products_group;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Tyre24;
use App\Tyre24_details;
use DB;
  
class TyreImport1 implements ToModel{
   
   public $row_count = 0;
   public $directory  = 'storage/tyre_images/';
   
   public function model(array $row){
	   $current_number_of_row = ++$this->row_count;
	   $created_at = $updated_at = date('Y-m-d h:i:s');
	   if($current_number_of_row > 1){
	   if(!empty($row[1]) && !empty($row[2]) && !empty($row[3])){
		   $tyre_max_size = $row[1].$row[2].$row[3];
		   $tyre = Tyre24::where([['tyre_response->ean_number', '=', $row[7]]])->first();
		   if($tyre == NULL){
			  $tyre = Tyre24::where([['id', '=', $row[0]]])->first();
		   }  
			if($tyre != NULL){
				$insert_arr = [
					'user_id'=>3, 'tyre_max_size'=>$tyre_max_size, 
					'max_width'=>$row[1], 'max_aspect_ratio'=>$row[2],
					'max_diameter'=>$row[3], 'pair'=>$row[14], 'type'=>$row[5],
					'vehicle_tyre_type'=>$row[34], 'season_tyre_type'=>$row[35], 'load_speed_index'=>$row[27],
					'peak_mountain_snowflake'=>$row[33], 'speed_index'=>$row[4], 'our_description'=>$row[15],
					'seller_price'=>$row[16], 'quantity'=>$row[17], 'tax'=>$row[18], 'tax_value'=>$row[19],
					'stock_warning'=>$row[20], 'unit'=>$row[29], 'discount'=>$row[21],
					'tyre_response->matchcode'=>$row[6], 'tyre_response->ean_number'=>$row[7], 'tyre_response->description'=>$row[8], 'tyre_response->description1'=>$row[9], 'tyre_response->pr_description'=>$row[10],
					'tyre_response->wholesalerArticleNo'=>$row[11], 'tyre_response->manufacturer_description'=>$row[12], 'tyre_response->price'=>$row[13],
					'tyre_response->is3PMSF'=>$row[31], 'tyre_response->weight'=>$row[32], 'meta_key_title'=>$row[22],
					'meta_key_word'=>$row[23], 'status'=>$row[30], 'substract_stock'=>$row[28],
					'runflat'=>$row[24], 'reinforced'=>$row[25] ,'updated_at'=>$updated_at
				];
				$save_tyre_detail = DB::table('tyre24s')->where([['id' , '=' , $tyre->id]])->update($insert_arr);
					if($save_tyre_detail){
						/*Find tyre 24 details*/
						 $tyre_detail_response = Tyre24_details::where([['tyre24s_itemId' , '=' , $tyre->itemId]])->first();
						/*End*/
						 if($tyre_detail_response != NULL){
							$update_arr_2 = [
							'tyre24_id'=>$tyre->id,	
							'rolling_resistance'=>$row[37],	
							'noise_db'=>$row[38],	
							'tyre_class'=>$row[39],	
							'tyre_detail_response->price'=>$row[13], 
							'tyre_detail_response->weight'=>$row[32], 
							'tyre_detail_response->is3PMSF'=>$row[31], 
							'tyre_detail_response->wetGrip'=>$row[26], 
							'tyre_detail_response->matchcode'=>$row[6], 
							'tyre_detail_response->org_price'=>$row[13], 
							'tyre_detail_response->ean_number'=>$row[7], 
							'tyre_detail_response->description'=>$row[8], 
							'tyre_detail_response->description1'=>$row[9], 
							'tyre_detail_response->pr_description'=>$row[10], 
							'tyre_detail_response->wholesalerArticleNo'=>$row[11], 
							'tyre_detail_response->manufacturer_description'=>$row[12], 
							'tyre_detail_response->tireClass'=>$row[39], 
							'tyre_detail_response->extRollingNoiseDb'=>$row[38], 
							'tyre_detail_response->rollingResistance'=>$row[37], 
							'updated_at'=>date('Y-m-d h:i:s')]; 
							$response = DB::table('tyre24_details')->where([['tyre24_id' , '=' , $tyre->id]])->update($update_arr_2);
							  
						 }
						 else{
							$unique_id_2 = $tyre->unique_id.$tyre->itemId;
							$tyre_detail_response_json = json_encode(["id"=>null,  "type"=>$row[4], "price"=>$row[13],  "stock"=>1, "weight"=>$row[32],
							 "date_de"=>null, "is3PMSF"=>$row[31], "pic_t24"=>null, "text_de"=>null, "wetGrip"=>$row[26], "imageUrl"=>null, "itemDate"=>null, "itemText"=>null, "matchcode"=>$row[6], "org_price"=>$row[13], "source_de"=>null, "tireClass"=>$row[39], "ean_number"=>$row[7], "itemSource"=>null, "description"=>$row[8], "description1"=>$row[9], "longFeedback"=>null, "wholesalerId"=>null, "shortFeedback"=>null, "pr_description"=>$row[10], "extRollingNoise"=>null, "longFeedback_de"=>null, "shortFeedback_de"=>null, "extRollingNoiseDb"=>$row[38], "rollingResistance"=>$row[37], "wholesalerArticleNo"=>$row[11], "manufacturer_description"=>$row[12], "manufacturer_item_number"=>null]);
							/*End*/  
							$save_tyre_detail_response = Tyre24_details::create(['tyre24_id'=>$tyre->id , 'tyre24s_itemId'=>$tyre->itemId, 'rolling_resistance' => $row[37], 'noise_db' => $row[38], 'tyre_class' => $row[39],'tyre_detail_response'=>$tyre_detail_response_json , 'unique_id'=>$unique_id_2, 'created_at'=>$created_at, 'updated_at'=>$updated_at]);  
						 }

					}
					if(!empty($row[36])) {
						$image_arr = explode(',', $row[36]);
						foreach($image_arr as $imgs) {
							if(!empty($imgs)){
								$get_extension = explode('.', $imgs);
								$ext = end($get_extension);
								$content = file_get_contents($imgs);
								if(!empty($content)){
								   $image_name = md5(microtime().uniqid().rand(9 , 9999)).".".$ext;
								   file_put_contents($this->directory. '/'.$image_name, $content);
								   $image_url = url("storage/tyre_images/$image_name");
								   $product_img = \App\TyreImage::create(['tyre24_id'=> $tyre->id, 'tyre_item_id'=>$tyre->itemId, 'image'=> $image_name, 'image_url'=> $image_url]);  
								  }
								
							}
						}
					}  
			} 	
	}
  }
}
}


?>