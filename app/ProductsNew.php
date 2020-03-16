<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Library\sHelper;
use App\Library\kromedaHelper;
use App\CustomDatabase;
use DB;



class ProductsNew extends Model{

      protected  $table = "products_new";

	  protected $fillable = [
        'id', 'users_id', 'products_item_numbers_CodiceListino' , 'products_item_numbers_CodiceOE', 'products_groups_id' , 'products_groups_group_id' , 'products_groups_items_item_id' , 'products_groups_items_id' , 'kromeda_products_id' , 'products_name', 'kromeda_description', 'our_products_description','type', 'bar_code', 'for_pair','CodiceListino', 
        'CodiceArticolo' , 'S' , 'tipo' , 'listino' , 'descrizione' ,'F' , 'cs', 'Foto','n' , 'v' , 'meta_key_title' ,'meta_key_words' , 
        'price' , 'seller_price' , 'products_quantiuty' , 'minimum_quantity' , 'out_of_stock_status' ,  'tax' , 'tax_value' , 
        'substract_stock' , 'unit' , 'products_json' , 'products_status' , 'assemble_status', 'bar_code','assemble_time','assemble_kromeda_time','created_at' ,'deleted_at' , 'updated_at' , 'unique_id'];

     
     
      public static function products_edit($request, $for_pair){
		$response =  ProductsNew::where([['id', '=', $request->products_id]])
								->update(['products_name'=>$request->products_name, 
								//  'products_groups_id'=>$request->group_item,
								//  'products_groups_items_id'=>$request->item_name,
									'kromeda_description'=>$request->kromeda_description, 
									'our_products_description'=>$request->products_description, 
									'meta_key_title'=>$request->meta_title, 
									'meta_key_words'=>$request->meta_keywords, 
									'price'=>$request->kromeda_price, 
									'bar_code'=>$request->bar_code,
									'for_pair'=>$for_pair, 
									'seller_price'=>$request->seller_price, 
									'tax'=>$request->tax, 
									'tax_value'=>$request->tax_value, 
									'minimum_quantity'=>$request->stock_warning, 
									'products_status'=>$request->products_status, 
									'substract_stock'=>$request->substract_stock,
									'unit'=>$request->unit,
									'assemble_status'=>$request->products_assemble_status,
									'assemble_time'=>$request->assemble_time,
									'assemble_kromeda_time'=>$request->assemble_kromeda_time
								] );
								
		//echo "<pre>";
		//print_r($response);exit;
				return $response;			
	}
	
	public static function products_assemble_edit($request){
	    $result = ProductsNew::where(['products_groups_items_id'=>$request->item_name])
		                      ->update(['assemble_kromeda_time'=>$request->assemble_kromeda_time , 'assemble_time'=>$request->assemble_time]); 
        return $result;						  
	}


	 public static function get_products_coe_id($CodiceOE_id){
	   return ProductsNew::where([['products_name' , '=' , $CodiceOE_id]])->first();

	}

	 

		public static function get_products_list($item_id = NULL){
			if(!empty($item_id)){
				//return ProductsNew::where('products_groups_items_id' , '=' ,  $item_id)->get(); 
				return ProductsNew::where([['products_groups_items_id' , '=' ,  $item_id], ['type', '=', 1]])->get();
			}
			return ProductsNew::where('type' , 1)->orderBy('created_at' , 'DESC')->paginate(15);
		}
		
		public static function get_unique_products($type = NULL){
			if($type != NULL){
			    return DB::table('products_new')->where([['type' , '=' , 1]])->groupBy('CodiceArticolo')->get();
			  }
			return DB::table('products_new')->where([['type' , '=' , 1]])->groupBy('CodiceArticolo')->paginate(10);
			//$sql = "SELECT `*` FROM products_new where type = 1 GROUP BY CodiceArticolo";			
			//$products = CustomDatabase::get_record($sql);
			//return  collect($products); 
			//return DB::table('products_new')->where([['deleted_at' , '=' , NULL]])->get();
			//return DB::statement($sql);
		}
	 
	 public static function get_child_category($car_version , $group_id){
		 $result = \DB::table('products_groups')                

							->where('products_groups.parent_id' , '=' , $group_id)
							->where('products_groups.car_version' , '=' , $car_version)
                            ->select('products_groups.id')
                            ->get();
		$group_ids=array();
		foreach($result as $rslt){
			$group_ids[]=$rslt->id;
			$temparray=ProductsNew::get_child_category($car_version , $rslt->id);
			$group_ids=array_merge($temparray,$group_ids);
		}
		return $group_ids;
	 }

	 public static function get_version_products($item_id){
		/*$group_ids = ProductsNew::get_child_category($car_version , $group_id);
		array_push($group_ids,$group_id);
		return ProductsNew::join('products_groups_items as pg_i' , 'products_new.products_groups_items_id' , '=' , 'pg_i.id')->join('products_groups as pg' , 'products_new.products_groups_id' , '=' , 'pg.id')->where([['products_status' , '=' , 'A'] ])->whereIn('products_new.products_groups_id',$group_ids)->select('products_new.id' , 'pg.car_makers as car_makers_name' , 'products_new.kromeda_products_id as products_name' , 'pg_i.front_rear' , 'pg_i.left_right' , 'pg_i.CodiceOE' , 'products_new.kromeda_description as products_description','products_new.price','products_new.seller_price','products_new.products_groups_id as category_id','products_new.products_status','products_new.listino','products_new.out_of_stock_status')->get();
*/
          return ProductsNew::where([['products_groups_items_id' , '=' , $item_id] , ['products_status' , '=' , 'A'] ])->get();
	     
	 }

     
     
     
	 public static function get_products_details($products_id){
		return \DB::table('products_new as pr_new')
							->join('products_groups_items as pg_i' , 'pr_new.products_groups_items_id' , '=' , 'pg_i.id')
							->join('products_groups as pg' , 'pg_i.products_groups_id' , '=' , 'pg.id') 
							->where('pr_new.id' , '=' , $products_id)
							->select('pr_new.*' , 'pg_i.item', 'pg_i.id as products_item_id', 'pg_i.item_id',  'pg_i.front_rear' , 'pg_i.left_right' , 'pg.car_makers' , 'pg.parent_id' , 'pg.car_model' , 'pg.car_version' , 'pg.group_name' , 'pg_i.language as lang')
							->first();
								
	}

	public static function get_custom_products_details($products_id){
		return 	ProductsNew::where('id', '=', $products_id)->first();					
	}
	 
	/*save products by mot services*/

	/*save other products by mot */
	public static function add_other_products_by_mot_service($part_details , $products){
		$uid = User::return_admin_id();
		$created_at = $updated_at = date('Y-m-d h:i:s');
		$queries = ''; 
			 foreach($products  as $product){
				 $product_description  = \DB::connection()->getPdo()->quote($product->Descrizione);
				 $uniqueKey = $part_details->CodiceListino.$part_details->CodiceOE.$product->CodiceListino.$product->CodiceArticolo;
				 $price = sHelper::replace_comman_with_dot($product->Prezzo);
				 $queries .=  "INSERT INTO `products_new`(`id`, `users_id`,`products_item_numbers_CodiceListino` , `products_item_numbers_CodiceOE` , `products_groups_items_item_id` ,  `products_item_numbers_id`, `kromeda_products_id`, `products_name`, `kromeda_description`, `type`,`CodiceListino` ,`CodiceArticolo`,`S` ,`tipo` ,`listino`,`descrizione`,`cs`, `Foto` , `n` , `v`,`price`, `created_at` , `updated_at` ,`unique_id`) 
				 VALUES (null ,'$uid','$part_details->CodiceListino', '$part_details->CodiceOE',
				 '$part_details->products_groups_items_item_id',  '$part_details->id', '$product->CodiceArticolo', '$product->CodiceArticolo',$product_description, '1', '$product->CodiceListino' , '$product->CodiceArticolo' , '$product->S' , '$product->Tipo' ,'$product->Listino', $product_description , '$product->CS' , '$product->Foto','$product->N','$product->V','$price',
	 			'$created_at','$updated_at','$uniqueKey') ON DUPLICATE KEY UPDATE  products_groups_items_item_id='$part_details->products_groups_items_item_id', CodiceListino='$product->CodiceListino', kromeda_products_id='$product->CodiceArticolo',
	 			products_name='$product->CodiceArticolo', kromeda_description=$product_description, 
				CodiceArticolo='$product->CodiceArticolo' , S='$product->S' ,  tipo='$product->Tipo', listino='$product->Listino' ,
				descrizione=$product_description ,cs='$product->CS' , Foto='$product->Foto', n='$product->N',v='$product->V',  
				price='$price';";
				$brand_unique_key = "1".sHelper::slug($product->Listino); 
				 $queries .= "INSERT INTO `brand_logos`(`id` , `brand_type`, `brand_name`, `unique_id`,  `created_at`) VALUES (null, 1,  '$product->Listino',  '$brand_unique_key', '$created_at') ON DUPLICATE KEY UPDATE  brand_name='$product->Listino' , unique_id='$brand_unique_key';";
				 $queries .= "SELECT @id := id FROM `products_new` WHERE `unique_id`='$uniqueKey';\n";
				 /*Get Picture url script start*/
				 $get_picture_url = kromedaHelper::get_picture_url($product->CodiceListino , $product->CodiceArticolo);
				 if(!empty($get_picture_url) || $get_picture_url != NULL){
					$product_image_uniqkey = (string) $product->CodiceListino.$product->CodiceArticolo;
				  $queries .= "INSERT INTO `products_images`(`id`,`users_id`,`products_id` , `product_kromeda_id` ,`CodiceArticolo`,`ls_CodiceListino`,`image_url`, `status`, `primary_status` , `unique_id`) VALUES (null ,'$uid',@id, '$product->CodiceArticolo', '$product->CodiceArticolo', '$product->CodiceListino','$get_picture_url',1,1 , '$product_image_uniqkey') ON DUPLICATE KEY UPDATE  image_url='$get_picture_url', CodiceArticolo='$product->CodiceArticolo' , ls_CodiceListino='$product->CodiceListino';\n";				}
				 /*End*/	
			 }
			return CustomDatabase::custom_insertOrUpdate($queries);
	}
	/*End*/
	
	public static function add_products_by_mot_service($part_details , $products){
		$uid = User::return_admin_id();
		$created_at = $updated_at = date('Y-m-d h:i:s');
		$queries = ''; 
			 foreach($products  as $product){
				 $product_description  = \DB::connection()->getPdo()->quote($product->Descrizione);
				 $uniqueKey = $part_details->CodiceListino.$part_details->CodiceOE.$product->CodiceListino.$product->CodiceArticolo;
				 $price = sHelper::replace_comman_with_dot($product->Prezzo);
				 $queries .=  "INSERT INTO `products_new`(`id`, `users_id`,`products_item_numbers_CodiceListino` , `products_item_numbers_CodiceOE` , `products_groups_items_item_id` ,  `products_item_numbers_id`, `kromeda_products_id`, `products_name`, `kromeda_description`, `type`,`CodiceListino` ,`CodiceArticolo`,`S` ,`tipo` ,`listino`,`descrizione`,`cs`, `Foto` , `n` , `v`,`price`, `created_at` , `updated_at` ,`unique_id`) 
				 VALUES (null ,'$uid','$part_details->CodiceListino', '$part_details->CodiceOE',
				 '$part_details->products_groups_items_item_id',  '$part_details->id', '$product->CodiceArticolo', '$product->CodiceArticolo',$product_description, '1', '$product->CodiceListino' , '$product->CodiceArticolo' , '$product->S' , '$product->Tipo' ,'$product->Listino', $product_description , '$product->CS' , '$product->Foto','$product->N','$product->V','$price',
	 '$created_at','$updated_at','$uniqueKey') ON DUPLICATE KEY UPDATE  products_groups_items_item_id='$part_details->products_groups_items_item_id', CodiceListino='$product->CodiceListino', kromeda_products_id='$product->CodiceArticolo',
	 products_name='$product->CodiceArticolo', kromeda_description=$product_description, 
	 CodiceArticolo='$product->CodiceArticolo' , S='$product->S' ,  tipo='$product->Tipo', listino='$product->Listino' ,
	 descrizione=$product_description ,cs='$product->CS' , Foto='$product->Foto', n='$product->N',v='$product->V',  
	 price='$price';";
				$brand_unique_key = "1".sHelper::slug($product->Listino); 
				 $queries .= "INSERT INTO `brand_logos`(`id` , `brand_type`, `brand_name`, `unique_id`,  `created_at`) VALUES (null, 1,  '$product->Listino',  '$brand_unique_key', '$created_at') ON DUPLICATE KEY UPDATE  brand_name='$product->Listino' , unique_id='$brand_unique_key';";
				 $queries .= "SELECT @id := id FROM `products_new` WHERE `unique_id`='$uniqueKey';\n";
				 /*Get Picture url script start*/
				 $get_picture_url = kromedaHelper::get_picture_url($product->CodiceListino , $product->CodiceArticolo);
				 if(!empty($get_picture_url) || $get_picture_url != NULL){
					$product_image_uniqkey = (string) $product->CodiceListino.$product->CodiceArticolo;
				  $queries .= "INSERT INTO `products_images`(`id`,`users_id`,`products_id` , `product_kromeda_id` ,`CodiceArticolo`,`ls_CodiceListino`,`image_url`, `status`, `primary_status` , `unique_id`) VALUES (null ,'$uid',@id, '$product->CodiceArticolo', '$product->CodiceArticolo', '$product->CodiceListino','$get_picture_url',1,1 , '$product_image_uniqkey') ON DUPLICATE KEY UPDATE  image_url='$get_picture_url', CodiceArticolo='$product->CodiceArticolo' , ls_CodiceListino='$product->CodiceListino';\n";				}
				 /*End*/	
			 }
			return CustomDatabase::custom_insertOrUpdate($queries);
	}
	/*End*/ 

	/*Save Car maintainance Services*/
	public static function add_product_by_car_maintainance($part_number, $get_products) {
		$uid = User::return_admin_id();
		$created_at = $updated_at = date('Y-m-d h:i:s');
		$queries = ''; 
		foreach($get_products  as $product){
			$product_description  = \DB::connection()->getPdo()->quote($product->Descrizione);
			$uniqueKey = $part_number->CodiceListino.$part_number->CodiceOE.$product->CodiceListino.$product->CodiceArticolo;
			$price = sHelper::replace_comman_with_dot($product->Prezzo);
			$queries .=  "INSERT INTO `products_new`(`id`, `users_id`,`products_item_numbers_CodiceListino` , `products_item_numbers_CodiceOE` , `products_groups_items_item_id` ,  `products_item_numbers_id`, `kromeda_products_id`, `products_name`, `kromeda_description`, `type`,`CodiceListino` ,`CodiceArticolo`,`S` ,`tipo` ,`listino`,`descrizione`,`cs`, `Foto` , `n` , `v`,`price`, `created_at` , `updated_at` ,`unique_id`)VALUES (null ,'$uid','$part_number->CodiceListino', '$part_number->CodiceOE','$part_number->products_groups_items_item_id',  '$part_number->id', '$product->CodiceArticolo', '$product->CodiceArticolo',$product_description, '1', '$product->CodiceListino' , '$product->CodiceArticolo' , '$product->S' , '$product->Tipo' ,'$product->Listino', $product_description , '$product->CS' , '$product->Foto','$product->N','$product->V','$price','$created_at','$updated_at','$uniqueKey') ON DUPLICATE KEY UPDATE  products_groups_items_item_id='$part_number->products_groups_items_item_id', CodiceListino='$product->CodiceListino', kromeda_products_id='$product->CodiceArticolo',products_name='$product->CodiceArticolo', kromeda_description=$product_description,CodiceArticolo='$product->CodiceArticolo' , S='$product->S' ,  tipo='$product->Tipo', listino='$product->Listino' ,descrizione=$product_description ,cs='$product->CS' , Foto='$product->Foto', n='$product->N',v='$product->V',price='$price';";
			$brand_unique_key = "1".sHelper::slug($product->Listino); 
			$queries .= "INSERT INTO `brand_logos`(`id` , `brand_type`, `brand_name`, `unique_id`,  `created_at`) VALUES (null, 1,  '$product->Listino',  '$brand_unique_key', '$created_at') ON DUPLICATE KEY UPDATE  brand_name='$product->Listino' , unique_id='$brand_unique_key';";
			$queries .= "SELECT @id := id FROM `products_new` WHERE `unique_id`='$uniqueKey';\n";

			$get_picture_url = kromedaHelper::get_picture_url($product->CodiceListino , $product->CodiceArticolo);
			if(!empty($get_picture_url) || $get_picture_url != NULL){
				$product_image_uniqkey = (string) $product->CodiceListino.$product->CodiceArticolo;
				$queries .= "INSERT INTO `products_images`(`id`,`users_id`,`products_id` , `product_kromeda_id` ,`CodiceArticolo`,`ls_CodiceListino`,`image_url`, `status`, `primary_status` , `unique_id`) VALUES (null ,'$uid',@id, '$product->CodiceArticolo', '$product->CodiceArticolo', '$product->CodiceListino','$get_picture_url',1,1 , '$product_image_uniqkey') ON DUPLICATE KEY UPDATE  image_url='$get_picture_url', CodiceArticolo='$product->CodiceArticolo' , ls_CodiceListino='$product->CodiceListino';\n";
			}
		}
		return CustomDatabase::custom_insertOrUpdate($queries);
	}
	/*End */

	/*Add other product by car maintainance */ 
	public static function add_other_product_by_car_maintainance($part_number, $get_other_products) {
		$uid = User::return_admin_id();
		$created_at = $updated_at = date('Y-m-d h:i:s');
		$queries = ''; 
		foreach($get_other_products  as $product){
			$product_description  = \DB::connection()->getPdo()->quote($product->Descrizione);
			$uniqueKey = $part_number->CodiceListino.$part_number->CodiceOE.$product->CodiceListino.$product->CodiceArticolo;
			$price = sHelper::replace_comman_with_dot($product->Prezzo);
			$queries .=  "INSERT INTO `products_new`(`id`, `users_id`,`products_item_numbers_CodiceListino` , `products_item_numbers_CodiceOE` , `products_groups_items_item_id` ,  `products_item_numbers_id`, `kromeda_products_id`, `products_name`, `kromeda_description`, `type`,`CodiceListino` ,`CodiceArticolo`,`S` ,`tipo` ,`listino`,`descrizione`,`cs`, `Foto` , `n` , `v`,`price`, `created_at` , `updated_at` ,`unique_id`)	VALUES (null ,'$uid','$part_number->CodiceListino', '$part_number->CodiceOE','$part_number->products_groups_items_item_id',  '$part_number->id', '$product->CodiceArticolo', '$product->CodiceArticolo',$product_description, '1', '$product->CodiceListino' , '$product->CodiceArticolo' , '$product->S' , '$product->Tipo' ,'$product->Listino', $product_description , '$product->CS' , '$product->Foto','$product->N','$product->V','$price','$created_at','$updated_at','$uniqueKey') ON DUPLICATE KEY UPDATE  products_groups_items_item_id='$part_number->products_groups_items_item_id', CodiceListino='$product->CodiceListino', kromeda_products_id='$product->CodiceArticolo',products_name='$product->CodiceArticolo', kromeda_description=$product_description, CodiceArticolo='$product->CodiceArticolo' , S='$product->S' ,  tipo='$product->Tipo', listino='$product->Listino' ,descrizione=$product_description ,cs='$product->CS' , Foto='$product->Foto', n='$product->N',v='$product->V',price='$price';";
			$brand_unique_key = "1".sHelper::slug($product->Listino); 
			$queries .= "INSERT INTO `brand_logos`(`id` , `brand_type`, `brand_name`, `unique_id`,  `created_at`) VALUES (null, 1,  '$product->Listino',  '$brand_unique_key', '$created_at') ON DUPLICATE KEY UPDATE  brand_name='$product->Listino' , unique_id='$brand_unique_key';";

			$queries .= "SELECT @id := id FROM `products_new` WHERE `unique_id`='$uniqueKey';\n";

			$get_picture_url = kromedaHelper::get_picture_url($product->CodiceListino , $product->CodiceArticolo);

			if(!empty($get_picture_url) || $get_picture_url != NULL){
				$product_image_uniqkey = (string) $product->CodiceListino.$product->CodiceArticolo;
				$queries .= "INSERT INTO `products_images`(`id`,`users_id`,`products_id` , `product_kromeda_id` ,`CodiceArticolo`,`ls_CodiceListino`,`image_url`, `status`, `primary_status` , `unique_id`) VALUES (null ,'$uid',@id, '$product->CodiceArticolo', '$product->CodiceArticolo', '$product->CodiceListino','$get_picture_url',1,1 , '$product_image_uniqkey') ON DUPLICATE KEY UPDATE  image_url='$get_picture_url', CodiceArticolo='$product->CodiceArticolo' , ls_CodiceListino='$product->CodiceListino';\n";
			}
		}
		return CustomDatabase::custom_insertOrUpdate($queries);
	}
	/*End */
   
	 
	
	
			/*Add Custom others products new */
			public static function add_other_products_by_kromeda_new($item_details  , $part_details, $products){
				if (Auth::check()) { 
					$admin_detail =  DB::table('users')->where([['roll_id' , '=' , 4]])->first();
					if($admin_detail != NULL){	$uid = $admin_detail->id; }   else{  $uid = 3; }
				  }
				  else{  $uid = 3; }

					$created_at  = $updated_at = date('Y-m-d h:i:s');
					$queries = ''; 
					foreach($products  as $product){
						$uniqueKey = $part_details->CodiceListino.$part_details->CodiceOE.$product->CodiceListino.$product->CodiceArticolo;
						$product_description  = \DB::connection()->getPdo()->quote($product->Descrizione);
						$price = sHelper::replace_comman_with_dot($product->Prezzo);
						$queries .=  "INSERT INTO `products_new`(`id`, `users_id` , `products_item_numbers_CodiceListino` , `products_item_numbers_CodiceOE`, `products_groups_id`, `products_groups_items_id`, `products_groups_items_item_id` , `products_item_numbers_id`, `kromeda_products_id`, `products_name`, `kromeda_description`, `type`, `CodiceListino` ,`CodiceArticolo` ,`listino`,`descrizione`,`cs`, `Foto` ,`price`,
					`created_at` , `updated_at` ,`unique_id`) VALUES (null ,'$uid' ,'$part_details->CodiceListino' , '$part_details->CodiceOE' ,'$item_details->products_groups_id','$item_details->id',
					'$item_details->item_id' ,'$part_details->id', '$product->CodiceArticolo', '$product->CodiceArticolo',$product_description, '1', '$product->CodiceListino', '$product->CodiceArticolo' ,'$product->Listino',$product_description, '$product->CS' , '$product->Foto' , '$price',
					'$created_at','$updated_at','$uniqueKey') ON DUPLICATE KEY UPDATE products_groups_id= '$item_details->products_groups_id', products_groups_items_id='$item_details->id' , products_groups_items_item_id='$item_details->item_id', CodiceListino='$product->CodiceListino', kromeda_products_id='$product->CodiceArticolo',
					products_name='$product->CodiceArticolo', kromeda_description=$product_description, 
					CodiceArticolo='$product->CodiceArticolo' , listino='$product->Listino' ,
					descrizione=$product_description ,cs='$product->CS' , Foto='$product->Foto' ,  
					price='$price';";
					/*Brand Logo update*/
						$brand_unique_key = "1".sHelper::slug($product->Listino); 
						$queries .= "INSERT INTO `brand_logos`(`id` , `brand_type`, `brand_name`, `created_at`) VALUES (null, 1,  '$product->Listino', '$created_at') ON DUPLICATE KEY UPDATE  brand_name='$product->Listino' , unique_id='$brand_unique_key';";
					/*End*/						
						$queries .= "SELECT @id := id FROM `products_new` WHERE `unique_id`='$uniqueKey';\n";
						/*Get Picture url script start*/
						$get_picture_url = kromedaHelper::get_picture_url($product->CodiceListino , $product->CodiceArticolo);
							if(!empty($get_picture_url) || $get_picture_url != NULL){
								$product_image_uniqkey = (string) $product->CodiceListino.$product->CodiceArticolo;
							$queries .= "INSERT INTO `products_images`(`id`,`users_id`,`products_id` , `product_kromeda_id`,`CodiceArticolo`,`ls_CodiceListino`,`image_url`, `status`, `primary_status` , `unique_id`) VALUES (null ,'$uid',@id, '$product->CodiceArticolo', '$product->CodiceArticolo', '$product->CodiceListino','$get_picture_url',1,1,'$product_image_uniqkey') ON DUPLICATE KEY UPDATE  image_url='$get_picture_url' , CodiceArticolo='$product->CodiceArticolo' , ls_CodiceListino='$product->CodiceListino';\n";	}
						/*End*/	
				}
				//return $queries;
					return CustomDatabase::custom_insertOrUpdate($queries);
			}
			/*End*/

			/*Add Custom products new */
			public static function add_products_by_kromeda_new($item_details , $part_details ,  $products){
				if (Auth::check()) { 
					$admin_detail =  DB::table('users')->where([['roll_id' , '=' , 4]])->first();
					if($admin_detail != NULL){	$uid = $admin_detail->id; }   else{  $uid = 3; }
				  }
				  else{  $uid = 3; }
				$created_at = $updated_at = date('Y-m-d h:i:s');
				$queries = ''; 
					 foreach($products  as $product){
						 $product_description  = \DB::connection()->getPdo()->quote($product->Descrizione);
						 $uniqueKey = $part_details->CodiceListino.$part_details->CodiceOE.$product->CodiceListino.$product->CodiceArticolo;
						 $price = sHelper::replace_comman_with_dot($product->Prezzo);
						 $queries .=  "INSERT INTO `products_new`(`id`, `users_id`, `products_groups_id`, `products_groups_items_id`,`products_item_numbers_CodiceListino` , `products_item_numbers_CodiceOE` , `products_groups_items_item_id` ,  `products_item_numbers_id`, `kromeda_products_id`, `products_name`, `kromeda_description`, `type`,`CodiceListino` ,`CodiceArticolo`,`S` ,`tipo` ,`listino`,`descrizione`,`cs`, `Foto` , `n` , `v`,`price`, `created_at` , `updated_at` ,`unique_id`) 
						 VALUES (null ,'$uid','$item_details->products_groups_id','$item_details->id','$part_details->CodiceListino', '$part_details->CodiceOE',
						 '$item_details->item_id',  '$part_details->id', '$product->CodiceArticolo', '$product->CodiceArticolo',$product_description, '1', '$product->CodiceListino' , '$product->CodiceArticolo' , '$product->S' , '$product->Tipo' ,'$product->Listino', $product_description , '$product->CS' , '$product->Foto','$product->N','$product->V','$price',
			 '$created_at','$updated_at','$uniqueKey') ON DUPLICATE KEY UPDATE products_groups_id= '$item_details->products_groups_id', products_groups_items_id='$item_details->id' , products_groups_items_item_id='$item_details->item_id', CodiceListino='$product->CodiceListino', kromeda_products_id='$product->CodiceArticolo',
			 products_name='$product->CodiceArticolo', kromeda_description=$product_description, 
			 CodiceArticolo='$product->CodiceArticolo' , S='$product->S' ,  tipo='$product->Tipo', listino='$product->Listino' ,
			 descrizione=$product_description ,cs='$product->CS' , Foto='$product->Foto', n='$product->N',v='$product->V',  
			 price='$price';";
						$brand_unique_key = "1".sHelper::slug($product->Listino); 
						 $queries .= "INSERT INTO `brand_logos`(`id` , `brand_type`, `brand_name`, `unique_id`,  `created_at`) VALUES (null, 1,  '$product->Listino',  '$brand_unique_key', '$created_at') ON DUPLICATE KEY UPDATE  brand_name='$product->Listino' , unique_id='$brand_unique_key';";
						 $queries .= "SELECT @id := id FROM `products_new` WHERE `unique_id`='$uniqueKey';\n";
						 /*Get Picture url script start*/
						 $get_picture_url = kromedaHelper::get_picture_url($product->CodiceListino , $product->CodiceArticolo);
						 if(!empty($get_picture_url) || $get_picture_url != NULL){
							$product_image_uniqkey = (string) $product->CodiceListino.$product->CodiceArticolo;
                          $queries .= "INSERT INTO `products_images`(`id`,`users_id`,`products_id` , `product_kromeda_id` ,`CodiceArticolo`,`ls_CodiceListino`,`image_url`, `status`, `primary_status` , `unique_id`) VALUES (null ,'$uid',@id, '$product->CodiceArticolo', '$product->CodiceArticolo', '$product->CodiceListino','$get_picture_url',1,1 , '$product_image_uniqkey') ON DUPLICATE KEY UPDATE  image_url='$get_picture_url', CodiceArticolo='$product->CodiceArticolo' , ls_CodiceListino='$product->CodiceListino';\n";				}
						 /*End*/	
					 }
				/* echo "<pre>";
				print_r($queries);exit; */
					return CustomDatabase::custom_insertOrUpdate($queries);
			   }
			 /*End*/

	 public static function add_products_by_kromeda($item_details , $product){

	   	  if(strpos($product->Prezzo , ',') !== FALSE){

			   $price = str_replace(',' , '.' , $product->Prezzo); 

			}

		  else{ $price = $product->Prezzo; }	

		return  ProductsNew::updateOrCreate([

	                                    'products_groups_id'=>$item_details->products_groups_id ,

										'products_groups_items_id'=>$item_details->id ,

										'CodiceListino'=>$product->CodiceListino , 

										'kromeda_products_id'=>$product->CodiceArticolo 

										] , 

						   [

						 'users_id'=>Auth::user()->id ,  
						 'products_groups_id'=>$item_details->products_groups_id ,
						 'products_groups_items_id'=>$item_details->id ,
						 'kromeda_products_id'=>$product->CodiceArticolo ,
						 'products_name'=>$product->CodiceArticolo ,  
						 'kromeda_description'=>$product->Descrizione , 
						 'type'=>1 , 
						 'CodiceListino'=>$product->CodiceListino ,  

						 'CodiceArticolo'=>$product->CodiceArticolo ,  

						 'S'=>$product->S, 

						 'tipo'=>$product->Tipo ,

						 'listino'=> $product->Listino ,

						 'descrizione'=>$product->Descrizione ,

						 'F'=>$product->F ,  

						 'cs'=> $product->CS , 

						 'Foto'=>$product->Foto ,  

						 'n'=> $product->N ,

						 'v'=> $product->V , 

						 'price'=>$price , 

						 'products_json'=>json_encode($product) , 

					/*	 'products_status'=>'A',
						 'assemble_status'=>'N',*/

                           ]);

		

	}

	 /*public static function add_other_products_by_kromeda_new_for_cron($item_details  , $part_details, $products){
        $uid = Auth::user()->id;
		$created_at = date('Y-m-d h:i:s');
        $updated_at = date('Y-m-d h:i:s');
		$queries = ''; 
		  foreach($products  as $product){
			  $uniqueKey = $item_details->id.$part_details->id.$product->CodiceListino.$product->CodiceArticolo;
			  $price = sHelper::replace_comman_with_dot($product->Prezzo);
			  $queries .=  "INSERT INTO `products_new`(`id`, `users_id`, `products_groups_id`, `products_groups_items_id`, `products_item_numbers_id`, `kromeda_products_id`, `products_name`, `kromeda_description`, `type`, `CodiceListino` ,`CodiceArticolo` ,`listino`,`descrizione`,`cs`, `Foto` ,`price`,
	`created_at` , `updated_at` ,`unique_id`) VALUES (null ,'$uid','$item_details->products_groups_id','$item_details->id', '$part_details->id', '$product->CodiceArticolo', '$product->CodiceArticolo','$product->Descrizione', '1', '$product->CodiceListino', '$product->CodiceArticolo' ,'$product->Listino', '$product->Descrizione' , '$product->CS' , '$product->Foto' , '$price',
 '$created_at','$updated_at','$uniqueKey') ON DUPLICATE KEY UPDATE products_groups_id= '$item_details->products_groups_id', products_groups_items_id='$item_details->id', CodiceListino='$product->CodiceListino', kromeda_products_id='$product->CodiceArticolo',
products_name='$product->CodiceArticolo', kromeda_description='$product->Descrizione', 
CodiceArticolo='$product->CodiceArticolo' , listino='$product->Listino' ,
descrizione='$product->Descrizione' ,cs='$product->CS' , Foto='$product->Foto' ,  
price='$price';";
			  $queries .= "SELECT @id := id FROM `products_new` WHERE `unique_id`='$uniqueKey';\n";
			   $get_picture_url = kromedaHelper::get_picture_url($product->CodiceListino , $product->CodiceArticolo);
			    if(!empty($get_picture_url) || $get_picture_url != NULL){
				 $queries .= "INSERT INTO `products_item_numbers`(`users_id` , `products_id` 
				`CodiceArticolo`, `ls_CodiceListino`,`image_url`, `status`, `primary_status`) VALUES (null ,@id,'$product->CodiceArticolo', '$product->CodiceListino') ON DUPLICATE KEY UPDATE  image_url='$get_picture_url' , CodiceArticolo='$product->CodiceArticolo' , ls_CodiceListino='$product->CodiceListino';\n";	  
				}
			 	
			}
		
	  return CustomDatabase::custom_insertOrUpdate($queries);
	   }*/
	
	
	
	

	public static function add_other_products_by_kromeda($item_details , $product){

       	  if(strpos($product->Prezzo , ',') !== FALSE){

			   $price = str_replace(',' , '.' , $product->Prezzo); 

			}

		  else{ $price = $product->Prezzo; }	

		  return ProductsNew::updateOrCreate([

	                                    'products_groups_id'=>$item_details->products_groups_id ,

										'products_groups_items_id'=>$item_details->id ,

										'CodiceListino'=>$product->CodiceListino , 

										'kromeda_products_id'=>$product->CodiceArticolo 

										] , 

						   [

						 'users_id'=>Auth::user()->id ,  

						 'products_groups_id'=>$item_details->products_groups_id ,

						 'products_groups_items_id'=>$item_details->id ,

						 'CodiceListino'=>$product->CodiceListino , 

						 'kromeda_products_id'=>$product->CodiceArticolo ,

						  'products_name'=>$product->CodiceArticolo , 

						 'kromeda_description'=>$product->Descrizione , 

						 'type'=>1 , 

						 'CodiceArticolo'=>$product->CodiceArticolo ,  

						 'listino'=> $product->Listino ,

						 'descrizione'=>$product->Descrizione ,

						 'cs'=> $product->CS , 

						 'Foto'=>$product->Foto ,  

						 'price'=>$price , 
						 'products_json'=>json_encode($product),
						/* 'products_status'=>'A',
						 'assemble_status'=>'N',*/

                           ]);

	}
	
	public static function get_assemble_products(){
	  return ProductsNew::where('assemble_status' , '=' , 'Y')->get();
	}
	
	public static function get_user_assemble_products($group_item_id){
	 return ProductsNew::where([['products_groups_items_id' , '=' , $group_item_id ] , ['assemble_status' , '=' , 'Y'] , ['deleted_at' , '=' , NULL]])->get();
	 }
	 
	 public static function get_inventory_products($product_id = NULL){
		if($product_id != NULL){
		  return ProductsNew::where('products_groups_items_id' , '=' , $product_id)->get();
		}
		return ProductsNew::where('deleted_at'  ,'=', NULL)->orderBy('products_name' ,'ASC')->get();
	}
	public static function get_product($product_id) {
		if($product_id != NULL) {
			return ProductsNew::where('id' , '=' , $product_id)->first();
		}
	}
	public static function get_feedback_product($feedback_id) {
		if(!empty($feedback_id)) {
			return ProductsNew::where('id' , '=' , $feedback_id)->first();
		}
	}
    
    
    public static function update_product_new($request,$for_pair, $group_id, $group_item_item_id){
	  return  ProductsNew::updateOrCreate(
	  						['id' => $request->custom_product_id],
							  ['users_id'=>3 , 
							  'products_groups_id'=>$request->custom_sub_group , 
	  						'products_groups_group_id'=>$group_id , 
	  						'products_groups_items_id'=>$request->custom_items , 
	  						'products_groups_items_item_id'=>$group_item_item_id ,
							 'products_name'=>$request->products_name, 
                             'type'=>2, 
							 'for_pair'=>$for_pair,
							 // 'price'=>$request->kromeda_price, 
							 'products_status'=>$request->products_status, 
							 'listino'=>$request->brand,
							 'unique_id'=>uniqid()
							  ]);
	}
    
	public static function add_new_custom_products($request , $group_id , $group_item_id, $for_pair){
		$for_pair = 0;
		if(!empty($request->for_pair)){ $for_pair = $request->for_pair; }
		return  ProductsNew::updateOrCreate(
	  						['id' => $request->custom_product_id],
	  						['users_id'=>3 , 
							 'for_pair'=>$for_pair,
							 'products_groups_id'=>$request->sub_groups,
							 'products_groups_group_id'=>$group_id,
							 'products_groups_items_id'=>$request->items,
							 'products_groups_items_item_id'=> $group_item_id,		  
							 'products_name'=>$request->products_name, 
							 'our_products_description'=>$request->products_description,
							 'meta_key_title'=>$request->meta_title, 
							 'meta_key_words'=>$request->meta_keywords,
							 'products_quantiuty'=>$request->quantity, 
							 'minimum_quantity'=>$request->stock_warning, 
							 'tax'=>$request->tax, 
							 'tax_value'=>$request->tax_value, 
							 'substract_stock'=>$request->substract_stock,
							 'unit'=>$request->unit, 
							 'products_status'=>$request->products_status,
                             'type'=>2, 
							 'for_pair'=>$for_pair,
							 'seller_price'=>$request->seller_price,
							 'products_status'=>$request->products_status, 
							 'assemble_kromeda_time'=>$request->kromeda_assemble_time,
							 'products_assemble_status'=>$request->assemble_time,
							 'listino'=>$request->brand,
							 'unique_id'=>uniqid()
							  ]);  
	}
	
	
	/*For App API*/
	  public static function find_product_details($product_id){
	     return \App\ProductsNew::where([['id','=',$product_id] , ['deleted_at' , '=' , NULL] , ['products_status' , '=' , 'A']])->first();
	   }
	   
	   public static function get_product_item_details($product_item_id) {
			return DB::table('products_new as a')
                   ->leftjoin('products_groups as b' , 'b.id' , '=' , 'a.products_groups_id')
                   ->select('a.*', 'b.car_makers as maker', 'b.car_model as model', 'b.car_version as version', 'b.parent_id as n1_category', 'b.id as n2_category', 'a.products_groups_items_id as n3_category')
				   ->where([['a.kromeda_products_id', '=', $product_item_id]])
				   ->first();
		}
	/*End*/
	
	public static function get_product_item($product_item) {
		return DB::table('products_new as a')
                   ->leftjoin('products_groups_items as b' , 'b.id' , '=' , 'a.products_groups_items_id')
                   ->select('a.*', 'b.item as item_name', 'b.front_rear as front_rear', 'b.left_right as left_right', 'b.item_id as n3_item_number', 'b.id as product_group_item_id')
				   ->where([['a.products_name', '=', $product_item], ['a.deleted_at' , '=' , NULL]])
				   ->get();
	}
	
	public static function get_kromeda_compatible($product_item_number) {
		return DB::table('products_new as a')
					->join('products_groups_items as b' , 'b.id' , '=' , 'a.products_groups_items_id')
					->leftjoin('products_groups as c' , 'c.id' , '=' , 'a.products_groups_id')
					//->leftjoin('items_repairs_servicestimes as c' , 'b.item_id' , '=' , 'c.item_id')
					->where([['products_name', '=', $product_item_number] , ['a.deleted_at' , '=' , NULL]])
					->select('b.language as lang' , 'a.*' , 'b.item_id' , 'b.item' , 'b.front_rear' , 'b.left_right'  , 'c.car_makers' , 'c.car_model' , 'c.car_version', 'c.group_name' , 'c.parent_id' , 'c.type as group_type')
					->get(); 
		//return ProductsNew::where([['products_name', '=', $product_item_number] , ['deleted_at' , '=' , NULL]])->get();
	}
	
	public static function get_products($search_string , $car_version_id = NULL , $cat_arr = []){
	    if(count($cat_arr) > 0){
			if($cat_arr[1] == 3){
				return ProductsNew::where([['products_groups_items_id' , '=' , $cat_arr[0]] , ['products_status' , '=' , 'A'] , ['deleted_at' , '=' , NULL]])->get();
			}
			if($cat_arr[1] == 2 || $cat_arr[1] == 1){
				return ProductsNew::where([['products_status' , '=' , 'A'] , ['deleted_at' , '=' , NULL]])
				->whereIn('products_groups_id' ,  $cat_arr[0])					
				->get(); 
			}
		}
		
	}
	
	public static function get_products_with_keyword($q , $car_version_id = NULL , $cat_arr = []){
		if(count($cat_arr) > 0){
			if($cat_arr[1] == 3){
				return  ProductsNew::where([['products_groups_items_id' , '=' , $cat_arr[0]] , ['products_status' , '=' , 'A'] , ['deleted_at' , '=' , NULL]])
				            ->where(function($query) use ($q) {
                                $query->where('kromeda_description', 'LIKE', '%'.$q.'%')
                                    ->orWhere('our_products_description', 'LIKE', '%'.$q.'%')
                                    ->orWhere('products_name', 'LIKE', '%'.$q.'%')
                                    ->orWhere('listino', 'LIKE', '%'.$q.'%');
                            })->get();
			}
			if($cat_arr[1] == 2 || $cat_arr[1] == 1){
					return  ProductsNew::where([['products_status' , '=' , 'A'] , ['deleted_at' , '=' , NULL]])->whereIn('products_groups_id' ,  $cat_arr[0])	
				            ->where(function($query) use ($q) {
                                $query->where('kromeda_description', 'LIKE', '%'.$q.'%')
                                    ->orWhere('our_products_description', 'LIKE', '%'.$q.'%')
                                    ->orWhere('products_name', 'LIKE', '%'.$q.'%')
                                    ->orWhere('listino', 'LIKE', '%'.$q.'%');
                            })->get();
			}
		}
		
	}
	
	
	

	public static function get_products_with_version($q , $n2_cat_id_arr){
	    //print_r($n2_cat_id_arr);exit;
		return ProductsNew::where([['products_status' , '=' , 'A'] , ['deleted_at' , '=' , NULL]])
		                    ->WhereIn('products_groups_id' , $n2_cat_id_arr)
							->where(function($query) use ($q) {
                                $query->where('kromeda_description', 'LIKE', '%'.$q.'%')
                                    ->orWhere('our_products_description', 'LIKE', '%'.$q.'%')
                                    ->orWhere('products_name', 'LIKE', '%'.$q.'%')
                                    ->orWhere('listino', 'LIKE', '%'.$q.'%');
                            })
                            ->get();
	}
	 //...............s........................//
	 

	 public static function get_product_details($product_id){
		 return ProductsNew::whereIn('id',$product_id)->get();
	 }
	  public static function get_product_detail($product_id){
		 return ProductsNew::where('id',$product_id)->first();
	 }
	 

	public static function get_category_details($product_id) {
		return ProductsNew::where([['id', '=', $product_id]])->first();
	}

	
}


