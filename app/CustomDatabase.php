<?php
namespace App;
use config\pdodatabase;
use \PDO;


class CustomDatabase {
   
   private static $pdo_obj;
   private static $sql_obj;
   
   public function  __construct(){
    
   }
   
   public static function custom_insertOrUpdate($queries){
	  self::$pdo_obj = new pdodatabase;
	  self::$sql_obj = self::$pdo_obj->connect();
      $q = self::$sql_obj->prepare($queries);
	  $insert = $q->execute();
	  if(!empty($insert)) { return TRUE;  }
	  else{ return FALSE;  }        
   }
   
    public static function get_record($queries){
      self::$pdo_obj = new pdodatabase;
      self::$sql_obj = self::$pdo_obj->connect();
       $q = self::$sql_obj->prepare($queries);
       $stmt = $q->execute();
       $result = $q->fetchAll(PDO::FETCH_OBJ);
       if(count((array) $result) > 0){
          return $result;
       }
       else{
          return $result;
       }
    }
}


?>