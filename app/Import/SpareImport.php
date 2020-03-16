<?php
namespace App\Import;
use App\User;
use App\Products_group;
use Maatwebsite\Excel\Concerns\ToModel;
use App\ProductsNew_details;
use App\ProductsNew;
use sHelper;
  
class SpareImport implements ToModel{
   
   public $row_count = 0;
   
   public function model(array $row){
	  /*  echo "<pre>";
	  print_r($row);exit; */
	   $created_at = $updated_at = date('Y-m-d h:i:s');
	   $current_number_of_row = ++$this->row_count;
	   if($current_number_of_row > 1){
		    $products_details_arr = [ 'our_products_description'=>$row[8], 
									'type'=>$row[7], 'bar_code'=>$row[10] , 'for_pair'=>$row[9] , 'meta_key_title'=>$row[11] , 'meta_key_words'=>$row[12] , 
									'seller_price'=>$row[13] ,'products_quantiuty'=>$row[14] , 'minimum_quantity'=>$row[15] , 'tax'=>$row[16] , 'tax_value'=>$row[17] ,
									'unit'=>$row[18] , 'assemble_time'=>$row[20]						   
								];   
								
			$product_arr = ['products_groups_id'=>$row[1], 
							'products_groups_items_id'=>$row[2],
							'products_name'=>$row[4],
							'price'=>$row[5],
							'listino'=>$row[3],
							'kromeda_description'=>$row[6],
							'type'=>$row[7]
						   ]; 
						   
			$group_detail = \DB::table('products_groups')->where([['id' , '=' , $row[1]]])->first();
			if($group_detail != NULL){
				if($group_detail->type == 1){
					$product_arr['products_groups_group_id']  = $group_detail->group_id; 
				}
			}
			/*Get N3 details*/
			$n3_category_detail = \DB::table('products_groups_items')->where([['id' , '=' , $row[2]]])->first();
			if($n3_category_detail != NULL){
				if($n3_category_detail->type == 1){
					$product_arr['products_groups_items_item_id']  = $n3_category_detail->item_id; 	
				}
			}
			
			if(empty($row[0])){
				$product_arr['unique_id'] = sHelper::slug($row[4]).uniqid();
			}
			$product_response = ProductsNew::updateOrCreate(['id'=>$row[0]] , $product_arr);
			//echo "<pre>";
			//print_r($products_details_arr);
			if($product_response){
				if($product_response->type == 1){
					$where_clause_for_detail = [['products_kromeda_id' , '=' , $product_response->products_name]];
					$products_details_arr['product_id'] = $product_response->id;
					$products_details_arr['products_kromeda_id'] = (string) $product_response->products_name;
										
				}
				if($product_response->type == 2){
					$products_details_arr['product_id'] = $product_response->id;
					$where_clause_for_detail = [['id' , '=' , $product_response->id]];
				}

				$product_detail_response = ProductsNew_details::updateOrCreate($where_clause_for_detail , $products_details_arr);
				
				echo "<pre>";
				
				print_r($product_response);
				print_r($product_detail_response);
			}
            

			/*Get Prodduct detail*/
			/* $product_detail = \DB::table('products_new')->where([['id' , '=' , $row[0]]])->first();
			if($product_detail != NULL){
				if($product_detail->type == 1){
					$products_details_arr['products_kromeda_id'] = (string) $product_detail->products_name;
					/*Where clause according to product kromeda id for products_new_details table*/
					//	$where_clause_1 = ['products_kromeda_id' , '=' , $product_detail->products_name];
					/*End*/
			//	}
			//	else{
			//		$where_clause_1 = ['product_id' , '=' , $product_detail->id];
			//	}
		//	}
			/*End*/
			//$products_details_arr['product_id'] = $row[0];
		 }
	}
  }


?>