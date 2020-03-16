<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use App\CustomDatabase;
use Auth;
use sHelper;
use kromedaHelper;
use Carbon\Carbon;

class ItemRepairsParts extends Model{

    protected  $table = "item_repairs_parts";
    protected $fillable = [
      'id', 'users_id',  'items_repairs_servicestimes_id','item_repairs_part_numbers_id','CodiceListino','Tipo','Listino','CodiceArticolo', 'S', 'Descrizione',  'F' , 'CS' , 'Prezzo',  'Foto' , 'N' , 'V', 'unique_id', 'created_at' ,'updated_at'];


    public static function add_products_by_kromeda_new($item_details_id  , $part_details, $products){
         $uid = Auth::user()->id;
         $created_at = date('Y-m-d h:i:s');
         $updated_at = date('Y-m-d h:i:s');
         $queries = ''; 
           foreach($products  as $product){
               //return $product;
               $uniqueKey = $item_details_id.$part_details.$product->CodiceListino.$product->CodiceArticolo;
               $price = sHelper::replace_comman_with_dot($product->Prezzo);
               $description = \DB::connection()->getPdo()->quote($product->Descrizione);
               $queries .=  "INSERT INTO `item_repairs_parts`(`id`, `users_id`, `items_repairs_servicestimes_id`, `item_repairs_part_numbers_id`, `CodiceListino`, `Tipo`, `Listino`, `CodiceArticolo`, `S`,`Descrizione` ,`F` ,`CS`, `Prezzo`,`Foto`,`N` , `V`, `created_at` , `updated_at` ,`unique_id`) VALUES (null ,'$uid','$item_details_id','$part_details', '$product->CodiceListino', '$product->Tipo', '$product->Listino','$product->CodiceArticolo', '$product->S' , '$product->Descrizione' ,'$product->F', '$product->CS' , '$price' , '$product->Foto','$product->N','$product->V', '$created_at' , '$updated_at','$uniqueKey') ON DUPLICATE KEY UPDATE    CodiceListino='$product->CodiceListino', Tipo='$product->Tipo', Listino='$product->Listino', CodiceArticolo='$product->CodiceArticolo', Descrizione='$product->Descrizione', 
 CodiceArticolo='$product->CodiceArticolo' , S='$product->S',  Descrizione=$description ,CS='$product->CS' , Foto='$product->Foto', N='$product->N',V='$product->V',  
 Prezzo='$price';\n";
               $queries .= "SELECT @id := id FROM `item_repairs_parts` WHERE `unique_id`='$uniqueKey';\n";
               /*Get Picture url script start*/
                $get_picture_url = kromedaHelper::get_picture_url($product->CodiceListino , $product->CodiceArticolo);
                 if(!empty($get_picture_url) || $get_picture_url != NULL){
                  $queries .= "INSERT INTO `item_repairs_parts_image`(`id`,`users_id`, `item_repairs_parts_id`,`CodiceArticolo`,`ls_CodiceListino`,`image_url`, `status`, `primary_status` , `unique_id`) VALUES (null ,'$uid', @id , '$product->CodiceArticolo', '$product->CodiceListino','$get_picture_url',1,1,'$uniqueKey') ON DUPLICATE KEY UPDATE  image_url='$get_picture_url' , CodiceArticolo='$product->CodiceArticolo' , ls_CodiceListino='$product->CodiceListino';\n";	  
                 }
               /*End*/	
             }
      return CustomDatabase::custom_insertOrUpdate($queries);
        }

     
	 public static function add_products_by_kromeda($item_details_id  , $part_details, $products){
         $uid = Auth::user()->id;
         $created_at = date('Y-m-d h:i:s');
         $updated_at = date('Y-m-d h:i:s');
         $queries = ''; 
           foreach($products  as $product){
               //return $product;
               $uniqueKey = $item_details_id.$part_details.$product->CodiceListino.$product->CodiceArticolo;
               $price = sHelper::replace_comman_with_dot($product->Prezzo);
               $queries .=  "INSERT INTO `item_repairs_parts`(`id`, `users_id`, `items_repairs_servicestimes_id`, `item_repairs_part_numbers_id`, `CodiceListino`, `Listino`, `CodiceArticolo`,`Descrizione`,`Prezzo`,`Foto`, `created_at` , `updated_at` ,`unique_id`) VALUES (null ,'$uid','$item_details_id','$part_details', '$product->CodiceListino', '$product->Listino', '$product->CodiceArticolo','$product->Descrizione', '$price','$product->Foto', '$created_at' , '$updated_at','$uniqueKey') ON DUPLICATE KEY UPDATE    CodiceListino='$product->CodiceListino', Listino='$product->Listino', CodiceArticolo='$product->CodiceArticolo', Descrizione='$product->Descrizione', 
 Prezzo='$price' , Foto='$product->Foto', created_at='$created_at' , updated_at='$updated_at'/n;";
			   $queries .= "SELECT @id := id FROM `item_repairs_parts` WHERE `unique_id`='$uniqueKey';\n";
               /*Get Picture url script start*/
                $get_picture_url = kromedaHelper::get_picture_url($product->CodiceListino , $product->CodiceArticolo);
                 if(!empty($get_picture_url) || $get_picture_url != NULL){
                  $queries .= "INSERT INTO `item_repairs_parts_image`(`id`,`users_id`, `item_repairs_parts_id`,`CodiceArticolo`,`ls_CodiceListino`,`image_url`, `status`, `primary_status` , `unique_id`) VALUES (null ,'$uid', @id , '$product->CodiceArticolo', '$product->CodiceListino','$get_picture_url',1,1,'$uniqueKey') ON DUPLICATE KEY UPDATE  image_url='$get_picture_url' , CodiceArticolo='$product->CodiceArticolo' , ls_CodiceListino='$product->CodiceListino';\n";	  
                 }
               /*End*/	
             }
 			//return $queries;
             return CustomDatabase::custom_insertOrUpdate($queries);
        }
	 
	
        public static function  get_parts($item_repairs_details_id){
         return  ItemRepairsParts::whereDate('created_at', Carbon::today())
		                          ->where('items_repairs_servicestimes_id' , $item_repairs_details_id)
								  ->get();
       }   
       
       public static function  get_kpart_list($item_repairs_details_id){
        return  ItemRepairsParts::where('items_repairs_servicestimes_id' , $item_repairs_details_id)->get();
      }
      
       public static function  get_item_repairs_details($item_repairs_details_id){
           return  ItemRepairsParts::where('id', '=', $item_repairs_details_id)->first();
       }
}
