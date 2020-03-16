<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Models extends Model{
    protected  $table = "models";
    protected $fillable = [
        'id', 'maker', 'makers_name' , 'idModello' , 'Modello', 'ModelloAnno', 'unique_id', 'cron_executed_status' ,  'created_at' , 'updated_at'];  


        public static function save_models($makers_detail , $models){
            $queries = '';
            $created_at = date('Y-m-d h:i:s');
            $updated_at = date('Y-m-d h:i:s');
            foreach($models as $model){
                $unique_id = $makers_detail->idMarca.$model->idModello.$model->ModelloAnno;
                $queries .=  "INSERT INTO `models`(`id`, `maker`, `makers_name`, `idModello`, `Modello` , `ModelloAnno` , `unique_id`,  `created_at` , `updated_at`) VALUES (null, '$makers_detail->idMarca', '$makers_detail->Marca','$model->idModello' , '$model->Modello' , '$model->ModelloAnno' , '$unique_id','$created_at','$updated_at' ) ON DUPLICATE KEY UPDATE makers_name='$makers_detail->Marca' , idModello='$model->idModello' , Modello='$model->Modello' , ModelloAnno='$model->ModelloAnno';\n";
               }
            //return $queries;
            return CustomDatabase::custom_insertOrUpdate($queries); 
        } 
	  
	    public static function get_model($model){
            $model_arr = explode("/" , $model);
            return Models::where([['idModello','=',$model_arr[0] ],['ModelloAnno','=',$model_arr[1]] ])->first();
		}	
		
		public static function save_all_model($models, $maker_details){
            $created_at = date('Y-m-d h:i:s');
            $updated_at = date('Y-m-d h:i:s');
            $queries =  '';
            foreach($models as $model){
                $uniqueKey =$maker_details->idMarca.$model->idModello.$model->ModelloAnno;
                $queries .=  "INSERT INTO `models`(`id`, `maker`, `makers_name`, `idModello`, `Modello`, `ModelloAnno`, `unique_id`,  `created_at` , `updated_at`) VALUES (null , '$maker_details->idMarca', '$maker_details->Marca', '$model->idModello', '$model->Modello', '$model->ModelloAnno', '$uniqueKey', '$created_at' , '$updated_at' ) ON DUPLICATE KEY UPDATE makers_name='$maker_details->Marca', idModello='$model->idModello' , Modello='$model->Modello' , ModelloAnno='$model->ModelloAnno' ;\n";
            }
            return CustomDatabase::custom_insertOrUpdate($queries);
        }
}
