<?php
namespace App;
use App\CustomDatabase;
use Illuminate\Database\Eloquent\Model;

class Maker extends Model{
    
    protected  $table = "makers";
    protected $fillable = [
        'id', 'idMarca', 'slug_name',  'Marca' , 'CodiceListino' , 'cron_executed_status' , 'created_at' , 'updated_at'];    
 
    
    public static function save_makers($makers){
        $queries = '';
        $created_at = date('Y-m-d h:i:s');
        $updated_at = date('Y-m-d h:i:s');
        foreach($makers as $maker){
            $queries .=  "INSERT INTO `makers`(`id`, `idMarca`, `Marca`, `CodiceListino`, `created_at` , `updated_at`) VALUES (null, '$maker->idMarca', '$maker->Marca','$maker->CodiceListino','$created_at','$updated_at' ) ON DUPLICATE KEY UPDATE Marca='$maker->Marca', CodiceListino='$maker->CodiceListino' ;\n";
           }
        return CustomDatabase::custom_insertOrUpdate($queries); 
    }
    
    public static function get_makers($maker_id){  
       return Maker::where('idMarca' , '=' , $maker_id)->first();
    }
	
	public static function get_makers_slug($maker_details){
	   
	}
}
