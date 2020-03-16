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
  
class CustomSpareImport1 implements ToModel{
    public $row_count = 0;
    public $directory  = 'storage/products_image/';

    public function model(array $row){
        $created_at = $updated_at = date('Y-m-d h:i:s');
		$current_number_of_row = ++$this->row_count;
		if($current_number_of_row > 1){
            $products_details_arr = [
                'our_products_description'=>$row[7], 'type'=>2, 'bar_code'=>$row[9] , 'for_pair'=>$row[8] , 'meta_key_title'=>$row[10] , 'meta_key_words'=>$row[11] ,'seller_price'=>$row[12] ,'products_quantiuty'=>$row[13] , 'minimum_quantity'=>$row[14] , 'tax'=>$row[15] , 'tax_value'=>$row[16] ,'unit'=>$row[17] , 'assemble_time'=>$row[19], 'products_status' => $row[18], 'products_name1' => $row[21]
            ];
            $product_arr = [
                'products_groups_id' => $row[1], 
                'products_groups_id' => Auth::user()->id, 
                'products_groups_items_id' => $row[2],
                'products_name' => $row[4],
                'price' => $row[5],
                'seller_price' => $row[12],
                'listino' => $row[3],
                'type' => 2,
                'assemble_status' => $row[22],
            ];
            if(!empty($row[9]) && !empty($row[1]) && !empty($row[2])) {
                $group_detail = DB::table('products_groups')->where([['id' , '=' , $row[1]]])->first();
                if($group_detail != NULL){
                    if($group_detail->type == 1){
                        $product_arr['products_groups_group_id']  = $group_detail->group_id; 
                    }
                }
                $n3_category_detail = \DB::table('products_groups_items')->where([['id' , '=' , $row[2]]])->first();
                if($n3_category_detail != NULL){
                    if($n3_category_detail->type == 1){
                        $product_arr['products_groups_items_item_id']  = $n3_category_detail->item_id; 	
                    }
                }
                $product_id = NULL;
                if(!empty($row[9])) {
                    $check_ean_number = \App\ProductsNew_details::where([['bar_code', '=', $row[9] ]])->first();    
                    if($check_ean_number != NULL) {
                        $product_id = $check_ean_number->product_id;
                    }
                } else {
                    $check_ean_number = \App\ProductsNew::where([['id', '=', $row[0]] ])->first();
                    if($check_ean_number != NULL) {
                        $product_id = $check_ean_number->id;
                    } 
                }
                if($check_ean_number == NULL) {
                    $check_ean_number = \App\ProductsNew::where([['id', '=', $row[0]] ])->first();
                    if($check_ean_number != NULL) {
                        $product_id = $check_ean_number->id;
                    } else {
                        $product_arr['unique_id'] = uniqid().time();
                    }
                }
                $product_response = ProductsNew::updateOrCreate(['id' => $product_id ] , $product_arr);
                if($product_response){
                    if($product_response->type == 2){
                        $products_details_arr['product_id'] = $product_response->id;
                        $where_clause_for_detail = [['product_id' , '=' , $product_response->id]];
                        $product_detail_response = ProductsNew_details::updateOrCreate($where_clause_for_detail , $products_details_arr);
                    }
                    if(!empty($row[20])) {
                        $image_arr = explode(',', $row[20]);
                        foreach($image_arr as $imgs) {
                            if(!empty($imgs)){
                                $get_extension = explode('.', $imgs);
                                $ext = end($get_extension);
                                $content = file_get_contents($imgs);
                                $image_name = md5(microtime().uniqid().rand(9 , 9999)).".".$ext;
                                file_put_contents($this->directory. '/'.$image_name, $content);
                                $image_url = url("storage/products_image/$image_name");
                                $product_img =  \App\ProductsImage::create(['users_id'=>Auth::user()->id ,'products_id'=>$product_response->id , 'product_kromeda_id'=>(string)$product_response->products_name ,'type'=>2, 'image_name'=>$image_name, 'image_url'=>$image_url , 'status'=>1 , 'primary_status'=>0]); 
                            }
                        }
                    }
                }
            }
            
        }
    }
}