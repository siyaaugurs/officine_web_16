<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;
use sHelper;
use kromedaHelper;
use App\CustomDatabase;
use Carbon\Carbon;
class MotItemParts extends Model{
    
	protected  $table = "mot_item_parts";
    protected $fillable = [
      'id', 'users_id',  'kr_part_lists_id','mot_part_numbers_id','CodiceListino','Tipo','Listino','CodiceArticolo', 'S', 'Descrizione',  'F' , 'CS' , 'Prezzo',  'Foto' , 'N' , 'V', 'unique_id', 'created_at' ,'updated_at'];


    public static function add_products_by_kromeda_new($part_number_details ,$products){
	     $uid = Auth::user()->id;
         $created_at = date('Y-m-d h:i:s');
         $updated_at = date('Y-m-d h:i:s');
         $queries = ''; 
           foreach($products  as $product){
               $uniqueKey = $part_number_details->kr_part_lists_id.$part_number_details->id.$product->CodiceListino.$product->CodiceArticolo;
               $price = sHelper::replace_comman_with_dot($product->Prezzo);
               $product_description  = \DB::connection()->getPdo()->quote($product->Descrizione);
               $queries .=  "INSERT INTO `mot_item_parts`(`id`, `users_id`, `kr_part_lists_id`, `mot_part_numbers_id`,`CodiceListino`, `Tipo`, `Listino`, `CodiceArticolo`, `S`,`Descrizione` ,`F` ,`CS`, `Prezzo`,`Foto`,`N` , `V`, `created_at` , `updated_at` ,`unique_id`) VALUES (null ,'$uid','$part_number_details->kr_part_lists_id','$part_number_details->id', '$product->CodiceListino', '$product->Tipo', '$product->Listino','$product->CodiceArticolo', '$product->S' , $product_description ,'$product->F', '$product->CS' , '$price' , '$product->Foto','$product->N','$product->V', '$created_at' , '$updated_at','$uniqueKey') ON DUPLICATE KEY UPDATE    CodiceListino='$product->CodiceListino', Tipo='$product->Tipo', Listino='$product->Listino', CodiceArticolo='$product->CodiceArticolo', Descrizione=$product_description, 
 CodiceArticolo='$product->CodiceArticolo' , S='$product->S',  Descrizione=$product_description ,CS='$product->CS' , Foto='$product->Foto', N='$product->N',V='$product->V',  
 Prezzo='$price' , updated_at='$updated_at';";
               $queries .= "SELECT @id := id FROM `mot_item_parts` WHERE `unique_id`='$uniqueKey';";
               /*Get Picture url script start*/
                $get_picture_url = kromedaHelper::get_picture_url($product->CodiceListino , $product->CodiceArticolo);
                 if(!empty($get_picture_url) || $get_picture_url != NULL){
                  $queries .= "INSERT INTO `mot_parts_image`(`id`,`users_id`, `mot_item_parts_id`,`CodiceArticolo`,`ls_CodiceListino`,`image_url`, `status`, `primary_status` , `unique_id`) VALUES (null ,'$uid', @id, '$product->CodiceArticolo', '$product->CodiceListino','$get_picture_url',1,1,'$uniqueKey') ON DUPLICATE KEY UPDATE  image_url='$get_picture_url' , CodiceArticolo='$product->CodiceArticolo' , ls_CodiceListino='$product->CodiceListino';";	  
                 }
               /*End*/	
             }
             return CustomDatabase::custom_insertOrUpdate($queries);
        }

     
	 public static function add_products_by_kromeda($part_number_details ,$products){
         $uid = Auth::user()->id;
         $created_at = date('Y-m-d h:i:s');
         $updated_at = date('Y-m-d h:i:s');
         $queries = ''; 
           foreach($products  as $product){
              $uniqueKey = $part_number_details->kr_part_lists_id.$part_number_details->id.$product->CodiceListino.$product->CodiceArticolo;
               $price = sHelper::replace_comman_with_dot($product->Prezzo);
               $product_description  = \DB::connection()->getPdo()->quote($product->Descrizione);
               $queries .=  "INSERT INTO `mot_item_parts`(`id`, `users_id`, `kr_part_lists_id`, `mot_part_numbers_id`, `CodiceListino`, `Listino`, `CodiceArticolo`,`Descrizione`,`Prezzo`,`Foto`, `created_at` , `updated_at` ,`unique_id`) VALUES (null ,'$uid','$part_number_details->kr_part_lists_id','$part_number_details->id', '$product->CodiceListino', '$product->Listino', '$product->CodiceArticolo',$product_description, '$price','$product->Foto', '$created_at' , '$updated_at','$uniqueKey') ON DUPLICATE KEY UPDATE    CodiceListino='$product->CodiceListino', Listino='$product->Listino', CodiceArticolo='$product->CodiceArticolo', Descrizione=$product_description, 
 Prezzo='$price' , Foto='$product->Foto' , updated_at='$updated_at';";
			   $queries .= "SELECT @id := id FROM `mot_item_parts` WHERE `unique_id`='$uniqueKey';";
               /*Get Picture url script start*/
                $get_picture_url = kromedaHelper::get_picture_url($product->CodiceListino , $product->CodiceArticolo);
                 if(!empty($get_picture_url) || $get_picture_url != NULL){
                  $queries .= "INSERT INTO `mot_parts_image`(`id`,`users_id`, `mot_item_parts_id`,`CodiceArticolo`,`ls_CodiceListino`,`image_url`, `status`, `primary_status` , `unique_id`) VALUES (null ,'$uid', @id, '$product->CodiceArticolo', '$product->CodiceListino','$get_picture_url',1,1,'$uniqueKey') ON DUPLICATE KEY UPDATE  image_url='$get_picture_url' , CodiceArticolo='$product->CodiceArticolo' , ls_CodiceListino='$product->CodiceListino';";		  
                 }
               /*End*/	
             }
             return CustomDatabase::custom_insertOrUpdate($queries);
        }
	 
	
        public static function  get_parts($item_number_id){
          return  MotItemParts::whereDate('updated_at', Carbon::today())
		                      ->where('mot_part_numbers_id' , $item_number_id)
							  ->get();
       }   
	
}
