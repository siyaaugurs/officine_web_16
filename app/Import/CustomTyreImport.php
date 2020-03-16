<?php
namespace App\Import;
use App\User;
use App\Products_group;
use Maatwebsite\Excel\Concerns\ToModel;
use App\WorkshopServicesPayments;
use Auth;
use App\Tyre24;
use App\Tyre24_details;
use DB;

  
class CustomTyreImport implements ToModel{

    public $row_count = 0;
    public $directory  = 'storage/tyre_images/';

    public function model(array $row){

        $current_number_of_row = ++$this->row_count;
        $created_at = $updated_at = date('Y-m-d h:i:s');
        
        if($current_number_of_row > 1){
            $tyre_detail = Tyre24::where([['tyre_response->ean_number' , '=' , $row[6]] , ['type_status' , '=' , 2]])->first();
            $tyre_response_json = ['tyre_detail_response->stock' => 1, 'updated_at' => $updated_at ];
            if(!empty($tyre_detail)) {
                $tyre_id = $tyre_detail->id;
                $insert_data = ['user_id' => 3, 'updated_at' => $updated_at, 'type_status' => 2, 'tyre_response->stock' => 1, 'tyre_response->ownStock' => 0];
                $insert_data['max_width'] = $tyre_detail->max_width;
                $insert_data['max_aspect_ratio'] = $tyre_detail->max_aspect_ratio;
                $insert_data['max_diameter'] = $tyre_detail->max_diameter;
                if(!empty($row[0])) {
                    $insert_data['max_width'] = $row[0];
                }
                if(!empty($row[1])) {
                    $insert_data['max_aspect_ratio'] = $row[1];
                }
                if(!empty($row[2])) {
                    $insert_data['max_diameter'] = $row[2];
                }
                $insert_data['tyre_max_size'] = $insert_data['max_width'].$insert_data['max_aspect_ratio'].$insert_data['max_diameter'];
                if(!empty($row[13])) {
                    $insert_data['pair'] = $row[13];
                    $tyre_response_json['tyre_detail_response->price'] = $row[13];
                }
                if(!empty($row[4])) {
                    $insert_data['type'] = $row[4];
                    $tyre_response_json['tyre_detail_response->type'] = $row[4];
                }
                if(!empty($row[33])) {
                    $insert_data['vehicle_tyre_type'] = $row[33];
                }
                if(!empty($row[34])) {
                    $insert_data['season_tyre_type'] = $row[34];
                }
                if(!empty($row[26])) {
                    $insert_data['load_speed_index'] = $row[26];
                }
                if(!empty($row[32])) {
                    $insert_data['peak_mountain_snowflake'] = $row[32];
                    $tyre_response_json['tyre_detail_response->weight'] = $row[32];
                }
                if(!empty($row[3])) {
                    $insert_data['speed_index'] = $row[3];
                }
                if(!empty($row[14])) {
                    $insert_data['our_description'] = $row[14];
                }
                if(!empty($row[15])) {
                    $insert_data['seller_price'] = $row[15];
                }
                if(!empty($row[16])) {
                    $insert_data['quantity'] = $row[16];
                }
                if(!empty($row[17])) {
                    $insert_data['tax'] = $row[17];
                }
                if(!empty($row[18])) {
                    $insert_data['tax_value'] = $row[18];
                }
                if(!empty($row[19])) {
                    $insert_data['stock_warning'] = $row[19];
                }
                if(!empty($row[28])) {
                    $insert_data['unit'] = $row[28];
                }
                if(!empty($row[20])) {
                    $insert_data['discount'] = $row[20];
                }
                if(!empty($row[21])) {
                    $insert_data['meta_key_title'] = $row[21];
                }
                if(!empty($row[22])) {
                    $insert_data['meta_key_word'] = $row[22];
                }
                if(!empty($row[29])) {
                    $insert_data['status'] = $row[29];
                }
                if(($row[27] == 0) || ($row[27] == 1)) {
                    $insert_data['substract_stock'] = $row[27];
                }
                if(($row[23] == 0) || ($row[23] == 1) ) {
                    $insert_data['runflat'] = $row[23];
                }
                if(($row[24] == 0) || ($row[24] == 1)) {
                    $insert_data['reinforced'] = $row[24];
                }
                if(!empty($row[4])) {
                    $insert_data['tyre_response->type'] = $row[4];
                }
                if(!empty($row[12])) {
                    $insert_data['tyre_response->price'] = $row[12];
                    $insert_data['tyre_response->kbprice'] = $row[12];
                    $insert_data['tyre_response->org_price'] = $row[12];
                    $tyre_response_json['tyre_detail_response->org_price'] = $row[12];
                }
                if(!empty($row[31])) {
                    $insert_data['tyre_response->weight'] = $row[31];
                    $tyre_response_json['tyre_detail_response->is3PMSF'] = $row[31];
                }
                if(!empty($row[30])) {
                    $insert_data['tyre_response->is3PMSF'] = $row[30];
                }
                if(!empty($row[5])) {
                    $insert_data['tyre_response->matchcode'] = $row[5];
                    $tyre_response_json['tyre_detail_response->matchcode'] = $row[5];
                }
                if(!empty($row[6])) {
                    $insert_data['tyre_response->ean_number'] = $row[6];
                    $tyre_response_json['tyre_detail_response->ean_number'] = $row[6];
                }
                if(!empty($row[7])) {
                    $insert_data['tyre_response->description'] = $row[7];
                    $tyre_response_json['tyre_detail_response->description'] = $row[7];
                }
                if(!empty($row[8])) {
                    $insert_data['tyre_response->description1'] = $row[8];
                    $tyre_response_json['tyre_detail_response->description1'] = $row[8];
                }
                if(!empty($row[9])) {
                    $insert_data['tyre_response->pr_description'] = $row[9];
                    $tyre_response_json['tyre_detail_response->pr_description'] = $row[9];
                }
                if(!empty($row[10])) {
                    $insert_data['tyre_response->wholesalerArticleNo'] = $row[10];
                    $tyre_response_json['tyre_detail_response->wholesalerArticleNo'] = $row[10];
                }
                if(!empty($row[11])) {
                    $insert_data['tyre_response->manufacturer_description'] = $row[11];
                    $tyre_response_json['tyre_detail_response->manufacturer_description'] = $row[11];
                }
                $response = DB::table('tyre24s')->where([['id' , '=' , $tyre_detail->id]])->update($insert_data);
            } else {
                if(!empty($row[0]) && !empty($row[1]) && !empty($row[2])) {
                    $tyre_max_size = $row[0].$row[1].$row[2];
                    $unique_id = $row[0].$row[1].$row[2].time().uniqid();
                    
                    $tyre_detail_json_response = json_encode(["pic"=>null, "type"=>$row[4], "price"=>$row[12], "stock"=>1, "itemId"=>null, "weight"=>$row[31], "date_de"=>null, "is3PMSF"=>$row[30], "kbprice"=>$row[12], "pic_t24"=>null, "text_de"=>null, "discount"=>null, "imageUrl"=>null, "itemDate"=>null, "itemText"=>null, "ownStock"=>0, "matchcode"=>$row[5], "org_price"=>$row[12], "source_de"=>null, "ean_number"=>$row[6], "itemSource"=>null, "description"=>$row[7], "feedback_de"=>null, "description1"=>$row[8], "shortFeedback"=>null, "pr_description"=>$row[9], "wholesalerArticleNo"=>$row[10], "manufacturer_description"=>$row[11], "manufacturer_item_number"=>null]);
    
                    $insert_data = ['user_id'=>3, 'tyre_max_size'=>$tyre_max_size, 'max_width'=>$row[0], 'max_aspect_ratio'=>$row[1],'max_diameter'=>$row[2] , 'pair'=>$row[13] , 'type'=>$row[4], 'vehicle_tyre_type'=>$row[33], 'season_tyre_type'=>$row[34], 'load_speed_index'=>$row[26] , 'peak_mountain_snowflake'=>$row[32], 'speed_index'=>$row[3],'our_description'=>$row[14] , 'seller_price'=>$row[15] , 'quantity'=>$row[16], 'tax'=>$row[17] , 'tax_value'=>$row[18], 'stock_warning'=>$row[19] , 'unit'=>$row[28], 'discount'=>$row[20], 'tyre_response'=>$tyre_detail_json_response, 'meta_key_title'=>$row[21], 'meta_key_word'=>$row[22],'status'=>$row[29], 'substract_stock'=>$row[27], 'runflat'=>$row[23], 'reinforced'=>$row[24], 'created_at'=>$created_at,'updated_at'=>$updated_at, 'unique_id'=> $unique_id, 'type_status'=>2];
                    $response = DB::table('tyre24s')->insertGetId($insert_data);
                    $tyre_id = $response;
                }
            }
            $tyre24_details = Tyre24_details::where([['tyre24_id', '=', $tyre_id]])->first();
            if(!empty($tyre24_details)) {
                if(!empty($row[25])) {
                    $tyre_response_json['tyre_detail_response->wetGrip'] = $row[25];
                }
                $json_response = $tyre_response_json;
            } else {
                $tyre_detail_response_json = json_encode(["id"=>null,  "type"=>$row[4], "price"=>$row[13],  "stock"=>1, "weight"=>$row[32],"date_de"=>null, "is3PMSF"=>$row[31], "pic_t24"=>null, "text_de"=>null, "wetGrip"=>$row[25], "imageUrl"=>null, "itemDate"=>null, "itemText"=>null, "matchcode"=>$row[5], "org_price"=>$row[12], "source_de"=>null, "tireClass"=>null, "ean_number"=>$row[6], "itemSource"=>null, "description"=>$row[7], "description1"=>$row[8], "longFeedback"=>null, "wholesalerId"=>null, "shortFeedback"=>null, "pr_description"=>$row[9], "extRollingNoise"=>null, "longFeedback_de"=>null, "shortFeedback_de"=>null, "extRollingNoiseDb"=>null, "rollingResistance"=>null, "wholesalerArticleNo"=>$row[10], "manufacturer_description"=>$row[11], "manufacturer_item_number"=>null]);

                $unique_id = $row[0].$row[1].$row[2].time().uniqid();
                $unique_id_2 = $unique_id.$tyre_id;
                $json_response = ['unique_id' => $unique_id_2, 'created_at' => $created_at, 'updated_at' => $updated_at, 'tyre_detail_response' => $tyre_detail_response_json];

            }
            $detail_response = Tyre24_details::updateOrCreate(['tyre24_id'=>$tyre_id] , $json_response);
            if(!empty($tyre_id)) {
                if(!empty($row[36])) {
					$image_arr = explode(',', $row[36]);
					foreach($image_arr as $imgs) {
                        if(!empty($imgs)){
                            $get_extension = explode('.', $imgs);
                            $ext = end($get_extension);
                            if (@GetImageSize($imgs)) {
                                $content = file_get_contents($imgs);
                                $image_name = md5(microtime().uniqid().rand(9 , 9999)).".".$ext;
                                file_put_contents($this->directory. '/'.$image_name, $content);
                                $image_url = url("storage/tyre_images/$image_name");
                                $product_img = \App\TyreImage::create(['tyre24_id'=> $tyre_id, 'image'=> $image_name, 'image_url'=> $image_url]); 
                            }
						}
					}
				}
            }
        }
    }

}