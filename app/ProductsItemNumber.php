<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use App\CustomDatabase;
use App\Library\sHelper;

class ProductsItemNumber extends Model{
    
	 protected  $table = "products_item_numbers";
	 protected $fillable = [
        'id', 'version_id',  'products_groups_items_item_id',  'products_groups_items_id',  'CodiceListino' ,'Tipo' ,  'Listino' , 'CodiceOE' , 'Descrizione' , 'CS' , 'Prezzo' ,'N','V','unique_id','created_at' ,'updated_at'];
		
	 	
	public static function get_part_item_number($products_groups_items_id){
	    return ProductsItemNumber::where([['products_groups_items_id' , '=' , $products_groups_items_id]])->get();
	} 	

	public static function save_product_item_number($get_item_number , $part_item){
		$created_at = $updated_at = date('Y-m-d h:i:s');
		$queries = ''; 
		if(count($get_item_number) > 0){
			foreach($get_item_number as $item_number){
				$listno = \DB::connection()->getPdo()->quote($item_number->Listino);
				$description = \DB::connection()->getPdo()->quote($item_number->Descrizione);
				$uniqueKey2 = $part_item->version_id.$part_item->idVoce.$item_number->CodiceListino.$item_number->CodiceOE;
				   $price = sHelper::replace_comman_with_dot($item_number->Prezzo);
				  $queries .=  "INSERT INTO `products_item_numbers`(`id`,`version_id`,`products_groups_items_item_id`, `products_groups_items_id`, `CodiceListino`, `Tipo`, `Listino`, `CodiceOE`, `Descrizione`, `CS`, `Prezzo`, `N`, `V`, `created_at` ,`updated_at`,`unique_id`) VALUES (null, '$part_item->version_id', '$part_item->idVoce',@id,'$item_number->CodiceListino','$item_number->Tipo',$listno, '$item_number->CodiceOE',$description,'$item_number->CS','$price', '$item_number->N' , '$item_number->V','$created_at','$updated_at','$uniqueKey2') ON DUPLICATE KEY UPDATE CodiceListino='$item_number->CodiceListino', Tipo='$item_number->Tipo', Listino=$listno, CodiceOE='$item_number->CodiceOE', Descrizione=$description, CS='$item_number->CS', Prezzo='$price', N='$item_number->N', V='$item_number->V' , updated_at='$updated_at';\n";
			   }
			return CustomDatabase::custom_insertOrUpdate($queries);   
		}
	}

	public static function get_parts($item_repairs_details) {
		return ProductsItemNumber::where([['version_id', '=', $item_repairs_details->version_id], 
		['products_groups_items_item_id', '=', $item_repairs_details->item_id]])->get();
	}

	public static function save_item_number($get_item_number , $item_repairs_details) {
		$created_at = $updated_at = date('Y-m-d h:i:s');
		$queries = ''; 
		if(count($get_item_number) > 0){
			foreach($get_item_number as $item_number){
				$listno = \DB::connection()->getPdo()->quote($item_number->Listino);
				$description = \DB::connection()->getPdo()->quote($item_number->Descrizione);
				$uniqueKey2 = $item_repairs_details->version_id.$item_repairs_details->item_id.$item_number->CodiceListino.$item_number->CodiceOE;
				$price = sHelper::replace_comman_with_dot($item_number->Prezzo);
				$queries .=  "INSERT INTO `products_item_numbers`(`id`,`version_id`,`products_groups_items_item_id`, `products_groups_items_id`, `CodiceListino`, `Tipo`, `Listino`, `CodiceOE`, `Descrizione`, `CS`, `Prezzo`, `N`, `V`, `created_at` ,`updated_at`,`unique_id`) VALUES (null, '$item_repairs_details->version_id', '$item_repairs_details->item_id',NULL,'$item_number->CodiceListino','$item_number->Tipo',$listno, '$item_number->CodiceOE',$description,'$item_number->CS','$price', '$item_number->N' , '$item_number->V','$created_at','$updated_at','$uniqueKey2') ON DUPLICATE KEY UPDATE CodiceListino='$item_number->CodiceListino', Tipo='$item_number->Tipo', Listino=$listno, CodiceOE='$item_number->CodiceOE', Descrizione=$description, CS='$item_number->CS', Prezzo='$price', N='$item_number->N', V='$item_number->V' , updated_at='$updated_at';\n";
			}
			return CustomDatabase::custom_insertOrUpdate($queries);   
		}
	}
		
}
