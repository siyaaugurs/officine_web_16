<?php
namespace App\Import;
use App\User;
use App\Products_group;
use Maatwebsite\Excel\Concerns\ToModel;
use App\ProductsNew_details;
use App\ProductsNew;
use sHelper;
use DB;
use Auth;
use Storage;
  
class CustomSpareImport implements ToModel{

    public $row_count = 0;
    public $directory  = 'storage/products_image/';

    public function model(array $row){ 

        $created_at = $updated_at = date('Y-m-d h:i:s');
        $current_number_of_row = ++$this->row_count;
        
        if($current_number_of_row > 1){
            if(($current_number_of_row < 3000)) {
                echo "<pre>";
                print_r($current_number_of_row);
                
                $products = $product_details = $product_id = $product_response = NULL;
                $product_arr = [];
                $products_details_arr = [];
                if(!empty($row[9])) {
                    $product_details = \App\ProductsNew_details::where([['bar_code', '=', $row[9]]])->first();
                    if(!empty($product_details)) {
                        $products = \App\ProductsNew::find($product_details->product_id);
                    }
                } else {
                    $products = \App\ProductsNew::where([['products_name', '=', $row[4]], ['type', '=', 2]])->first();
                    if(!empty($products)) {
                        $product_details = \App\ProductsNew_details::where([['product_id', '=', $products->id]])->first();
                    }
                }
                if(!empty($row[1])) {
                    $group_detail = DB::table('products_groups')->where([['id' , '=' , $row[1]], ['parent_id', '!=', NULL]])->first();
                    if($group_detail != NULL){
                        if($group_detail->type == 1){
                            $product_arr['products_groups_group_id']  = $group_detail->group_id; 
                        } else {
                            $product_arr['products_groups_group_id']  = NULL;
                        }
                    }
                }
                if(!empty($row[2])) {
                    $n3_category_detail = DB::table('products_groups_items')->where([['id' , '=' , $row[2]]])->first();
                    if($n3_category_detail != NULL){
                        if($n3_category_detail->type == 1){
                            $product_arr['products_groups_items_item_id']  = $n3_category_detail->item_id; 	
                        } else {
                            $product_arr['products_groups_items_item_id']  = NULL; 
                        }
                    }
                }
                
                if(!empty($products)) {
                    $product_id = $products->id;
                    if(!empty($row[1])) {
                        $product_arr['products_groups_id'] = $row[1];
                    }
                    if(!empty($row[2])) {
                        $product_arr['products_groups_items_id'] = $row[2];
                    }
                    if(!empty($row[4])) {
                        $product_arr['products_name'] = $row[4];
                    }
                    if(!empty($row[5])) {
                        $product_arr['price'] = $row[5];
                    }
                    if(!empty($row[12])) {
                        $product_arr['seller_price'] = $row[12];
                    }
                    if(!empty($row[3])) {
                        $product_arr['listino'] = $row[3];
                    }
                    if(!empty($row[3])) {
                        $product_arr['listino'] = $row[3];
                    }
                    if(!empty($row[21])) {
                        $product_arr['assemble_status'] = $row[21];
                    }
                    if(!empty($row[7])) {
                        $products_details_arr['our_products_description'] = $row[7];
                    }
                    if(!empty($row[9])) {
                        $products_details_arr['bar_code'] = $row[9];
                    }
                    if(!empty($row[8])) {
                        $products_details_arr['for_pair'] = $row[8];
                    }
                    if(!empty($row[10])) {
                        $products_details_arr['meta_key_title'] = $row[10];
                    }
                    if(!empty($row[11])) {
                        $products_details_arr['meta_key_words'] = $row[11];
                    }
                    if(!empty($row[12])) {
                        $products_details_arr['seller_price'] = $row[12];
                    }
                    if(!empty($row[13])) {
                        $products_details_arr['products_quantiuty'] = $row[13];
                    }
                    if(!empty($row[14])) {
                        $products_details_arr['minimum_quantity'] = $row[14];
                    }
                    if(!empty($row[15])) {
                        $products_details_arr['tax'] = $row[15];
                    }
                    if(!empty($row[16])) {
                        $products_details_arr['tax_value'] = $row[16];
                    }
                    if(!empty($row[17])) {
                        $products_details_arr['unit'] = $row[17];
                    }
                    if(!empty($row[19])) {
                        $products_details_arr['assemble_time'] = $row[19];
                    }
                    if(!empty($row[18])) {
                        $products_details_arr['products_status'] = $row[18];
                    }
                    if(!empty($row[20])) {
                        $products_details_arr['products_name1'] = $row[20];
                    }
                    $result = ProductsNew::where('id' , '=' , $product_id)->update($product_arr);
                } else {
                    if(!empty($row[1]) && !empty($row[2])) {
                        $product_arr = [
                            'products_groups_id' => $row[1], 
                            'users_id' => Auth::user()->id, 
                            'products_groups_items_id' => $row[2],
                            'products_name' => $row[4],
                            'price' => $row[5],
                            'seller_price' => $row[12],
                            'listino' => $row[3],
                            'type' => 2,
                            'assemble_status' => $row[21],
                            'unique_id' => uniqid().time(),
                            'created_at' => $created_at,
                            'updated_at' => $updated_at,
                        ];
    
                        $products_details_arr = [
                            'our_products_description'=>$row[7], 
                            'type'=>2, 
                            'bar_code'=>$row[9] , 
                            'for_pair'=>$row[8] , 
                            'meta_key_title'=>$row[10] , 
                            'meta_key_words'=>$row[11] ,
                            'seller_price'=>$row[12] ,
                            'products_quantiuty'=>$row[13] , 
                            'minimum_quantity'=>$row[14] , 
                            'tax'=>$row[15] , 
                            'tax_value'=>$row[16] ,
                            'unit'=>$row[17] , 
                            'assemble_time'=> !empty($row[19]) ? $row[19] : 0, 
                            'products_status' => !empty($row[18]) ? $row[18] : 'A', 
                            'products_name1' => $row[20]
                        ];
                        $product_id = DB::table('products_new')->insertGetId($product_arr);
                    }
                }
                if(!empty($product_id)) {
                    $product_response = ProductsNew::find($product_id);
                }
                if(!empty($product_response)) {
                    if($product_response->type == 2){
                        $products_details_arr['product_id'] = $product_response->id;
                        $where_clause_for_detail = [['product_id' , '=' , $product_response->id]];
                        $product_detail_response = ProductsNew_details::updateOrCreate($where_clause_for_detail , $products_details_arr);
                    }
                    if(!empty($row[23])) {
                        $image_arr = explode(',', $row[23]);
                        foreach($image_arr as $imgs) {
                            if(!empty($imgs)){
                                $get_extension = explode('.', $imgs);
                                $ext = end($get_extension);
                                $content = file_get_contents($imgs);
                                if(!empty($content)) {
                                    $image_name = md5(microtime().uniqid().rand(9 , 9999)).".".$ext;
                                    file_put_contents($this->directory. '/'.$image_name, $content);
                                    $image_url = url("storage/products_image/$image_name");
                                    $product_img =  \App\ProductsImage::create(['users_id'=>Auth::user()->id ,'products_id'=>$product_id , 'product_kromeda_id'=>(string)$product_response->products_name ,'type'=>2, 'image_name'=>$image_name, 'image_url'=>$image_url , 'status'=>1 , 'primary_status'=>0]); 
                                }
                            }
                        }
                    }
                }
            } else {
                echo "1";exit;
            }
        }

    }
}