<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\CustomDatabase;
use Auth;
use App\Library\kromedaHelper;
 

class Version extends Model{

    protected  $table = "versions";
    protected $fillable = ['id', 'model', 'idVeicolo', 'Versione' , 'ModelloCodice' , 'Dal' , 'Al','Kw','Cv', 'Body', 'Alimentazione', 'Valvole' , 'PorteComm'  , 'Cilindri', 'Status', 'Motore', 'execution_status', 'car_maintinance_execution','version_response','unnique_key', 'created_at' , 'updated_at'];
    
    
    public static function add_version($model , $versions){
		foreach($versions as $version){
			$unique_id = $model.$version->idVeicolo;
			Version::updateOrCreate(
					['model'=>$model, 'idVeicolo'=>$version->idVeicolo], 
					['model'=>$model,
					'idVeicolo'=>$version->idVeicolo, 
					'Versione'=>$version->Versione,
					'ModelloCodice'=>$version->ModelloCodice, 
					'Dal'=>$version->Dal,
					'Al'=>$version->Al, 
					'Kw'=>$version->Kw, 
					'Cv'=>$version->Cv, 
					'Body'=>$version->Body, 
					'Alimentazione'=>$version->Alimentazione, 
					'Valvole'=>$version->Valvole, 
					'PorteComm'=>$version->PorteComm, 
					'Cilindri'=>$version->Cilindri, 
					'Status'=>$version->Status,
					'Motore'=>$version->Motore, 
					'unnique_key'=>$unique_id
					]  
			);
	  }
	}
    
    
    public static function save_version($model , $response){
		if(count($response) > 0){
		    $queries =  '';
			foreach($response as $version){
				//return $version->ModelloCodice;
				$created_at = date('Y-m-d h:i:s');
				$updated_at = date('Y-m-d h:i:s');
				$uniqueKey = $model.$version->idVeicolo;
			$queries .=  "\nINSERT INTO `versions`(`id`,`model`, `idVeicolo`, `Versione`, `ModelloCodice`, `Dal`, `Al`,`Kw`,`Cv`,`Body`,`Alimentazione`,`Valvole` , `PorteComm`,  `Cilindri`, `Status` , `Motore` ,`unnique_key`,`created_at` , `updated_at`) VALUES (null , '$model' , '$version->idVeicolo', '$version->Versione', '$version->ModelloCodice','$version->Dal', '$version->Al', '$version->Kw','$version->Cv', '$version->Body', '$version->Alimentazione' , '$version->Valvole', '$version->PorteComm','$version->Cilindri' ,'$version->Status','$version->Motore' , '$uniqueKey','$created_at' , '$updated_at' ) ON DUPLICATE KEY UPDATE idVeicolo='$version->idVeicolo', Versione='$version->Versione' , ModelloCodice='$version->ModelloCodice' ,  Dal='' , Al='$version->Al' , Kw='$version->Kw' , Cv='$version->Cv', Body='$version->Body', Alimentazione='$version->Alimentazione' ,Valvole='$version->Valvole' , PorteComm='$version->PorteComm'  , Cilindri='$version->Cilindri', Status='$version->Status', Motore='$version->Motore', updated_at='$updated_at';\n";
			   }
			 //return $queries;
			 return CustomDatabase::custom_insertOrUpdate($queries); 
		  }
    }
	
	public static function get_version($version_id){
	  return Version::where('idVeicolo' , $version_id)->first();  
	}
	
	
	
	public static function get_versions($model){
      return Version::where([['model' , '=' , $model]])->get();
	}

}


