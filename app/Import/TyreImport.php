<?php
namespace App\Import;
use App\User;
use App\Products_group;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Tyre24;
use App\Tyre24_details;
use DB;
use Auth;
  
class TyreImport implements ToModel{

    public $row_count = 0;
    public $directory  = 'storage/tyre_images/';

    public function model(array $row){

        $current_number_of_row = ++$this->row_count;
        $created_at = $updated_at = date('Y-m-d h:i:s');
        
        if(($current_number_of_row > 1) ) {
            if(($current_number_of_row < 3000)) {
                echo "<pre>";
                print_r($current_number_of_row);
                $tyre = Tyre24::where([['tyre_response->ean_number', '=', $row[7]]])->first();
                
                if(!empty($tyre)) {
                    $insert_arr = ['user_id' => Auth::user()->id,'updated_at'=>$updated_at];
    
                    $update_arr_2 = ['tyre24_id'=>$tyre->id,'updated_at'=>date('Y-m-d h:i:s')];
    
                    if(!empty($row[1]) && !empty($row[2]) && !empty($row[3])) {
                        $tyre_max_size = $row[1].$row[2].$row[3];
                        $insert_arr['tyre_max_size'] = $tyre_max_size;
                        $insert_arr['max_width'] = $row[1];
                        $insert_arr['max_aspect_ratio'] = $row[2];
                        $insert_arr['max_diameter'] = $row[3];
                    }
                    if(!empty($row[14])) {
                        $insert_arr['pair'] = $row[14];
                    }
                    if(!empty($row[5])) {
                        $insert_arr['type'] = $row[5];
                    }
                    if(!empty($row[33])) {
                        $insert_arr['vehicle_tyre_type'] = $row[33];
                    }
                    if(!empty($row[34])) {
                        $insert_arr['season_tyre_type'] = $row[34];
                    }
                    if(!empty($row[27])) {
                        $insert_arr['load_speed_index'] = $row[27];
                    }
                    if(!empty($row[4])) {
                        $insert_arr['speed_index'] = $row[4];
                    }
                    if(!empty($row[15])) {
                        $insert_arr['our_description'] = $row[15];
                    }
                    if(!empty($row[16])) {
                        $insert_arr['seller_price'] = $row[16];
                    }
                    if(!empty($row[17])) {
                        $insert_arr['quantity'] = $row[17];
                    }
                    if(!empty($row[18])) {
                        $insert_arr['tax'] = $row[18];
                    }
                    if(!empty($row[19])) {
                        $insert_arr['tax_value'] = $row[19];
                    }
                    if(!empty($row[20])) {
                        $insert_arr['stock_warning'] = $row[20];
                    }
                    if(!empty($row[29])) {
                        $insert_arr['unit'] = $row[29];
                    }
                    if(!empty($row[21])) {
                        $insert_arr['discount'] = $row[21];
                    }
                    if(!empty($row[6])) {
                        $insert_arr['tyre_response->matchcode'] = $row[6];
                        $update_arr_2['tyre_detail_response->matchcode'] = $row[6];
                    }
                    if(!empty($row[7])) {
                        $insert_arr['tyre_response->ean_number'] = $row[7];
                        $update_arr_2['tyre_detail_response->ean_number'] = $row[7];
                    }
                    if(!empty($row[8])) {
                        $insert_arr['tyre_response->description'] = $row[8];
                        $update_arr_2['tyre_detail_response->description'] = $row[8];
                    }
                    if(!empty($row[9])) {
                        $insert_arr['tyre_response->description1'] = $row[9];
                        $update_arr_2['tyre_detail_response->description1'] = $row[9];
                    }
                    if(!empty($row[10])) {
                        $insert_arr['tyre_response->pr_description'] = $row[10];
                        $update_arr_2['tyre_detail_response->pr_description'] = $row[10];
                    }
                    if(!empty($row[11])) {
                        $insert_arr['tyre_response->wholesalerArticleNo'] = $row[11];
                        $update_arr_2['tyre_detail_response->wholesalerArticleNo'] = $row[11];
                    }
                    if(!empty($row[12])) {
                        $insert_arr['tyre_response->manufacturer_description'] = $row[12];
                        $update_arr_2['tyre_detail_response->manufacturer_description'] = $row[12];
                    }
                    if(!empty($row[13])) {
                        $insert_arr['tyre_response->price'] = $row[13];
                        $update_arr_2['tyre_detail_response->price'] = $row[13];
                        $update_arr_2['tyre_detail_response->org_price'] = $row[13];
                    }
                    if(($row[32] == 1) || ($row[32] == 1)) {
                        $insert_arr['tyre_response->is3PMSF'] = $row[32];
                        $update_arr_2['tyre_detail_response->is3PMSF'] = $row[32];
                        $insert_arr['peak_mountain_snowflake'] = $row[32];
                    }
                    if(!empty($row[31])) {
                        $insert_arr['tyre_response->weight'] = $row[31];
                        $update_arr_2['tyre_detail_response->weight'] = $row[31];
                    }
                    if(!empty($row[22])) {
                        $insert_arr['meta_key_title'] = $row[22];
                    }
                    if(!empty($row[23])) {
                        $insert_arr['meta_key_word'] = $row[23];
                    }
                    if(!empty($row[30])) {
                        $insert_arr['status'] = $row[30];
                    }
                    if(!empty($row[28])) {
                        $insert_arr['substract_stock'] = $row[28];
                    }
                    if(!empty($row[24])) {
                        $insert_arr['runflat'] = $row[24];
                    }
                    if(($row[25] == 1) || ($row[25] == 0)) {
                        $insert_arr['reinforced'] = $row[25];
                    }
                    $save_tyre_detail = DB::table('tyre24s')->where([['id' , '=' , $tyre->id]])->update($insert_arr);
                    if($save_tyre_detail){
                        $tyre_detail_response = Tyre24_details::where([['tyre24s_itemId' , '=' , $tyre->itemId]])->first();
                        if($tyre_detail_response != NULL){
                            if(!empty($row[36])) {
                                $update_arr_2['rolling_resistance'] = $row[36];
                                $update_arr_2['tyre_detail_response->rollingResistance'] = $row[36];
                            }
                            if(!empty($row[37])) {
                                $update_arr_2['noise_db'] = $row[37];
                                $update_arr_2['tyre_detail_response->extRollingNoiseDb'] = $row[37];
                            }
                            if(!empty($row[38])) {
                                $update_arr_2['tyre_class'] = $row[38];
                                $update_arr_2['tyre_detail_response->tireClass'] = $row[38];
                            }
                            if(!empty($row[26])) {
                                $update_arr_2['tyre_detail_response->wetGrip'] = $row[26];
                            }
                            $response = DB::table('tyre24_details')->where([['tyre24_id' , '=' , $tyre->id]])->update($update_arr_2);
                        } else {
                            $unique_id_2 = $tyre->unique_id.$tyre->itemId;
                            $tyre_detail_response_json = json_encode(["id"=>null,  "type"=>$row[4], "price"=>$row[13],  "stock"=>1, "weight"=>$row[31],"date_de"=>null, "is3PMSF"=>$row[32], "pic_t24"=>null, "text_de"=>null, "wetGrip"=>$row[26], "imageUrl"=>null, "itemDate"=>null, "itemText"=>null, "matchcode"=>$row[6], "org_price"=>$row[13], "source_de"=>null, "tireClass"=>$row[38], "ean_number"=>$row[7], "itemSource"=>null, "description"=>$row[8], "description1"=>$row[9], "longFeedback"=>null, "wholesalerId"=>null, "shortFeedback"=>null, "pr_description"=>$row[10], "extRollingNoise"=>null, "longFeedback_de"=>null, "shortFeedback_de"=>null, "extRollingNoiseDb"=>$row[37], "rollingResistance"=>$row[36], "wholesalerArticleNo"=>$row[11], "manufacturer_description"=>$row[12], "manufacturer_item_number"=>null]);
    
                            $save_tyre_detail_response = Tyre24_details::create(['tyre24_id'=>$tyre->id , 'tyre24s_itemId'=>$tyre->itemId, 'rolling_resistance' => $row[36], 'noise_db' => $row[37], 'tyre_class' => $row[38],'tyre_detail_response'=>$tyre_detail_response_json , 'unique_id'=>$unique_id_2, 'created_at'=>$created_at, 'updated_at'=>$updated_at]);
                        }
                    }
                    if(!empty($row[35])) {
                        $image_arr = explode(',', $row[35]);
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
            } else {
                echo "1"; exit;
            }
        }
    }

}