<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Library\kromedaHelper;
use App\Library\sHelper;
use App\CustomDatabase;
use Carbon\Carbon;
use DB;

class ProductsGroupsItem extends Model{
	
      protected  $table = "products_groups_items";
      protected $fillable = ['id', 'users_id', 'products_groups_id' , 'version_id', 'our_description', 'item_id', 'item' , 'front_rear' , 'left_right' ,  'our_description' , 'n1_kromeda_group_id'  , 'n2_kromeda_group_id' , 'language', 'deleted_at', 'created_at' 
      , 'updated_at' , 'codiceListino' , 'Tipo' , 'Listino' , 'CodiceOE' , 'Descrizione' , 'CS' , 'Prezzo' , 'N','V','unique_id', 'cron_executed_status' , 'type'];
      
	 
	/*Custom Query Script start*/
	public static function add_group_items_new($groups_item , $group_id , $language , $version , $group_group_id){
		$group_group_id = trim($group_group_id);
		    if (Auth::check()) { 
			  $admin_detail =  DB::table('users')->where([['roll_id' , '=' , 4]])->first();
			  if($admin_detail != NULL){	$uid = $admin_detail->id; }
			}
			else{  $uid = 3; }
		$created_at = $updated_at = date('Y-m-d h:i:s');
		$queries = ''; 
		foreach($groups_item as $item){
			$item_name = \DB::connection()->getPdo()->quote($item->Voce);
			$uniqueKey = $version.$group_group_id.$item->idVoce.$language;	
			$queries .=  "INSERT INTO `products_groups_items`(`id`, `users_id`, `version_id` , `products_groups_id`,`n2_kromeda_group_id` ,  `item_id`, `item`, `front_rear`, `left_right`, `language`, `created_at` , `updated_at` ,`unique_id`) VALUES (null ,'$uid', '$version', '$group_id', '$group_group_id' ,'$item->idVoce',$item_name,'$item->ap','$item->ds','$language','$created_at','$updated_at','$uniqueKey') ON DUPLICATE KEY UPDATE n2_kromeda_group_id='$group_group_id' , item_id='$item->idVoce' , item=$item_name, front_rear='$item->ap', left_right='$item->ds' , updated_at='$updated_at';\n";
			$queries .= "SELECT @id := id FROM `products_groups_items` WHERE `unique_id`='$uniqueKey';\n";
			 $get_item_number = kromedaHelper::get_part_number($version , $item->idVoce);
			 if(count($get_item_number) > 0){
				foreach($get_item_number as $item_number){
				  $listno = \DB::connection()->getPdo()->quote($item_number->Listino);
				  $description = \DB::connection()->getPdo()->quote($item_number->Descrizione);
				  $uniqueKey2 = $version.$item->idVoce.$item_number->CodiceListino.$item_number->CodiceOE;
				 	$price = sHelper::replace_comman_with_dot($item_number->Prezzo);
					$queries .=  "INSERT INTO `products_item_numbers`(`id`,`version_id`,`products_groups_items_item_id`, `products_groups_items_id`, `CodiceListino`, `Tipo`, `Listino`, `CodiceOE`, `Descrizione`, `CS`, `Prezzo`, `N`, `V`, `created_at` ,`updated_at`,`unique_id`) VALUES (null, '$version', '$item->idVoce',@id,'$item_number->CodiceListino','$item_number->Tipo',$listno, '$item_number->CodiceOE',$description,'$item_number->CS','$price', '$item_number->N' , '$item_number->V','$created_at','$updated_at','$uniqueKey2') ON DUPLICATE KEY UPDATE CodiceListino='$item_number->CodiceListino', Tipo='$item_number->Tipo', Listino=$listno, CodiceOE='$item_number->CodiceOE', Descrizione=$description, CS='$item_number->CS', Prezzo='$price', N='$item_number->N', V='$item_number->V' , updated_at='$updated_at';\n";
				 }
			   }
		   }
		
		return CustomDatabase::custom_insertOrUpdate($queries);
	 }
     
     
     public static function add_group_items_custom($item , $group_details , $get_item_number , $language){
	    if(!empty($get_item_number->Prezzo)){
		     if(strpos($get_item_number->Prezzo , ',') !== FALSE){
			   $price_admin = str_replace(',' , '.' , $get_item_number->Prezzo); 
			  }
		     else{ $price_admin = $get_item_number->Prezzo; }
		     }
		 else { $price_admin = 0; }  
	   
	    return ProductsGroupsItem::updateOrCreate(['products_groups_id'=>$group_details->id, 
		                                  'item_id'=>$item['item_description']['idVoce']
										  ] , 
										 ['users_id'=>Auth::user()->id ,
										 'products_groups_id'=>$group_details->id, 
										 'item_id'=>$item['item_description']['idVoce'],
										 'item'=>$item['item_description']['Voce'] , 
										 'front_rear'=>$item['item_description']['ap'] ,
										 'left_right'=>$item['item_description']['ds'] , 
										 'language'=>$language , 
										 'codiceListino'=>$get_item_number->CodiceListino , 
										 'Tipo'=>$get_item_number->Tipo , 
										 'Listino'=>$get_item_number->Listino , 
										 'CodiceOE'=>$get_item_number->CodiceOE , 
										 'Descrizione'=>$get_item_number->Descrizione , 
										 'CS'=>$get_item_number->CS , 
										 'Prezzo'=>$price_admin ,
										 'N'=>$get_item_number->N, 
										 'V'=>$get_item_number->V,
										] 
										);
	}
     
     public static function add_group_items($item , $group_id , $language){
		 if(!empty($item->item_number->Prezzo)){
		    $price = (int) $item->item_number->Prezzo;
	        $price_admin = number_format($price ,2);
		   }
		 else  $price_admin = 0;  
		
		
         return ProductsGroupsItem::updateOrCreate(['products_groups_id'=>$group_id, 
		                                  'item_id'=>$item->idVoce] , 
										 ['users_id'=>Auth::user()->id ,
										 'products_groups_id'=>$group_id, 
										 'item_id'=>$item->idVoce ,
										 'item'=>$item->Voce , 
										 'front_rear'=>$item->ap ,
										 'left_right'=>$item->ds , 
										 'language'=>$language , 
										 'codiceListino'=>$item->item_number->CodiceListino , 
										 'Tipo'=>$item->item_number->Tipo , 
										 'Listino'=>$item->item_number->Listino , 
										 'CodiceOE'=>$item->item_number->CodiceOE , 
										 'Descrizione'=>$item->item_number->Descrizione , 
										 'CS'=>$item->item_number->CS , 
										 'N'=>$item->item_number->N, 
										 'V'=>$item->item_number->V,
										] 
										);
     }
	 
	 public static function get_groups_items($group_id , $lang){
	     
	     return ProductsGroupsItem::where([['products_groups_id' , '=' , $group_id] , ['language' , '=' , $lang]])->get();
	 }
	 
	 public static function get_group_item($item_id){
		return ProductsGroupsItem::where('id' , '=' , $item_id)->first();
	  }
	  public static function get_all_groups_items($group_id ){
	     return ProductsGroupsItem::where([['products_groups_id' , '=' , $group_id], ['deleted_at' , '=' , NULL]])->get();
	 }
	 
	  public static function add_custom_n3_category($request , $n1_group_id , $n2_group_id , $lang){
	 	return ProductsGroupsItem::updateOrCreate(
			[ 'id' => $request->n3_category_id],
            [
                'users_id'=>Auth::user()->id ,
                'unique_id'=>uniqid(),
                'products_groups_id'=>$request->sub_category ,
                'item'=>$request->sub_category_n3,
                'front_rear'=>$request->front_rare,
                'left_right'=>$request->left_right,
				'our_description'=>$request->description,
				'n1_kromeda_group_id'=>$n1_group_id ,
				'n2_kromeda_group_id'=>$n2_group_id ,
                'type'=>2,
                'language'=>$lang 
            ]);
	 }

		public static function delete_n3_category($item_id) {
			return ProductsGroupsItem::where('id' , '=' , $item_id)
            ->update(['deleted_at'=>date('Y-m-d H:i:s')]);
		}

		public static function get_all_n3_category($id_arr) {
			return ProductsGroupsItem::whereIn('products_groups_id',$id_arr)->where('deleted_at' , '=' , NULL)->orderBy('item', 'asc')->get();
		}

		public static function get_all_n3() {
			return ProductsGroupsItem::where('deleted_at' , '=' , NULL)->get();
		}
		
		public static function check_today_execute($group_id){
	        return ProductsGroupsItem::whereDate('updated_at', Carbon::today())
		                          ->where([['products_groups_id' , '=' , $group_id]])
								  ->get();
	      }
	      public static function update_custom_n3_category($request){
		return ProductsGroupsItem::where('id' , '=' , $request->n3_category_id)->update(
			['our_description'=>$request->description ,
			'front_rear'=>$request->front_rare,
			'item'=>$request->group_name,
			'left_right'=>$request->left_right
			]);
	}
	/*public static function edit_kromeda_n3_category($request, $lang) {
			return ProductsGroupsItem::where([['id' , '=' , $request->k_groups_item_id], ['products_groups_id', '=', $request->k_category_group_id]])
									->update(['item'=>$request->group_name, 'front_rear'=>$request->front_rare, 'left_right'=>$request->left_right, 'our_description'=>$request->description, 'type'=>1,'language'=>$lang ]);
		}*/
		
		public static function edit_kromeda_n3_category($request, $n1_group_id, $n2_group_id, $lang) {
    		return ProductsGroupsItem::where([['id' , '=' , $request->edit_n3_category_id],['products_groups_id', '=', $request->sub_category]])
    									->update(['item'=>$request->sub_category_n3, 'front_rear'=>$request->front_rare, 'n1_kromeda_group_id' => $n1_group_id, 'n2_kromeda_group_id' => $n2_group_id,'left_right'=>$request->left_right, 'our_description'=>$request->description, 'type'=>1,'language'=>$lang ]);
    	}
    	
    	public static function get_product_n3_category($n3_category_id) {
			return ProductsGroupsItem::where([['id', '=', $n3_category_id], ['deleted_at', '=', NULL]])->get();
		}

		public static function get_all_item_group($lang) {
			return DB::table('products_groups_items')->where([['language' , '=' , $lang] , ['deleted_at' ,'=' , NULL]])->groupBy('item_id') ->get();
		  
		  /*$sql = "SELECT `*` FROM products_groups_items where language = '".$lang."' GROUP BY item_id";
		  $n3_category = CustomDatabase::get_record($sql);
		  return  collect($n3_category);*/
	  }
	public static function get_n3_category_details($item_id) {
		return ProductsGroupsItem::where([['id', '=', $item_id]])->first();
	}

	public static function get_all_unique_n3_category($lang){
    	$kromeda_n3 =  DB::table("products_groups_items")->where([['type' , '=' , 1] , ['language' , '=' , $lang]])->groupBy('item_id');
		return DB::table("products_groups_items")->where([['type' , '=' , 2]])->union($kromeda_n3)->orderBy('item', 'asc')->get(); 
  	}
	  
}
