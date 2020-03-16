<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use App\CustomDatabase;
use Carbon\Carbon;
use DB;
use sHelper;

class MotPartsNumber extends Model{
   
    protected  $table = "mot_part_numbers";
    protected $fillable = ['id', 'kr_part_lists_id', 'CodiceListino', 'Tipo' , 'Listino' , 'CodiceOE' , 'Descrizione','CS','Prezzo', 'N', 'V' , 'deleted_at' , 'created_at' , 'updated_at'];
   
    
	public static function save_item_number($item_numbers , $kr_part_list_data){
	  // return $kr_part_list_data->id;	
	   $created_at = date('Y-m-d h:i:s');
       $updated_at = date('Y-m-d h:i:s');
	   $queries = ''; 
	    foreach($item_numbers as $item_number){
				$uniqueKey = $kr_part_list_data->id.$item_number->CodiceListino.$item_number->CodiceOE;
				  $price = sHelper::replace_comman_with_dot($item_number->Prezzo);
			      $queries .=  "INSERT INTO `mot_part_numbers`(`id`, `kr_part_lists_id`, `CodiceListino`, `Tipo`, `Listino`, `CodiceOE`, `Descrizione`, `CS`, `Prezzo`, `N`, `V`, `created_at` ,`updated_at`,`unique_id`) VALUES (null ,'$kr_part_list_data->id','$item_number->CodiceListino','$item_number->Tipo', '$item_number->Listino', '$item_number->CodiceOE', '$item_number->Descrizione','$item_number->CS','$price', '$item_number->N' , '$item_number->V','$created_at','$updated_at','$uniqueKey') ON DUPLICATE KEY UPDATE  CodiceListino='$item_number->CodiceListino', Tipo='$item_number->Tipo', Listino='$item_number->Listino', CodiceOE='$item_number->CodiceOE', Descrizione='$item_number->Descrizione', CS='$item_number->CS', Prezzo='$price', N='$item_number->N', V='$item_number->V' , created_at='$created_at' , updated_at='$updated_at';\n";
			   }
		return CustomDatabase::custom_insertOrUpdate($queries);	   
	}
	
	public static function get_item_number($kr_part_list_data_id){
	  return MotPartsNumber::whereDate('created_at', Carbon::today())
	                       ->where([['kr_part_lists_id', '=' , $kr_part_list_data_id]])->get(); 
	} 
	
	
}
