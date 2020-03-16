<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\CustomDatabase;
use Auth;
use App\Library\kromedaHelper;
use Carbon\Carbon;
  

class Products_group extends Model{
  
    protected  $table = "products_groups";
    protected $fillable = ['id', 'parent_id', 'car_makers', 'car_model' , 'car_version' , 'group_id' ,'products_groups_group_id', 'group_name', 'type' , 'status' , 'description','group_unique_id', 'language', 'priority', 'cron_executed_status','deleted_at','created_at', 'updated_at'];
    public $type_status = [1=>'Kromeda category ' , 2=>'Our custom category'];
   
  
     public static function add_kromeda_sub_groups($group_details , $get_sub_groups , $lang){
      $created_at =  $updated_at = date('Y-m-d h:i:s');
          $queries =  '';
		       foreach($get_sub_groups as $sub_group){
                $uniqueKey2 = $group_details->car_version.$group_details->group_id.$lang.$sub_group->idSottogruppo;
			          $queries .=  "INSERT INTO `products_groups`(`id`, `parent_id`, `car_makers`, `car_model`, `car_version`, `group_id`, `products_groups_group_id`,  `group_name`, `group_unique_id`, `language` , `created_at` , `updated_at`) VALUES (null , '$group_details->id', '$group_details->car_makers', '$group_details->car_model', '$group_details->car_version', '$sub_group->idSottogruppo', '$group_details->group_id',  '$sub_group->Sottogruppo', '$uniqueKey2','$lang' ,'$created_at' , '$updated_at'  ) ON DUPLICATE KEY UPDATE group_name='$sub_group->Sottogruppo', group_id='$sub_group->idSottogruppo' , products_groups_group_id='$group_details->group_id' , updated_at='$updated_at' ;\n";
            }
		return CustomDatabase::custom_insertOrUpdate($queries);
	}
	
    
     public static function add_kromeda_groups($groups , $maker , $model , $version , $lang){
	    $created_at = $updated_at = date('Y-m-d h:i:s');
      $queries =  '';
		    foreach($groups as $group){
          $uniqueKey = $version.$group->idGruppo.$lang;
          $queries .=  "INSERT INTO `products_groups`(`id`, `parent_id`, `car_makers`, `car_model`, `car_version`, `group_id`, `group_name`, `group_unique_id`, `language` , `created_at` , `updated_at`) VALUES (null , 0, '$maker', '$model', '$version', '$group->idGruppo', '$group->Gruppo', '$uniqueKey','$lang' , '$created_at' , '$updated_at' ) ON DUPLICATE KEY UPDATE group_name='$group->Gruppo', group_id='$group->idGruppo';\n";
        }
        return CustomDatabase::custom_insertOrUpdate($queries);
	}
	
    
    
    /*Custom Query script start*/
    public static function add_kromeda_group_2($groups , $maker , $model , $version , $lang){
      $created_at = $updated_at = date('Y-m-d h:i:s');
      $queries =  '';
      foreach($groups as $group){
        $group_name  = \DB::connection()->getPdo()->quote($group->Gruppo);    
        $uniqueKey = $version.$group->idGruppo.$lang;
        $queries .=  "INSERT INTO `products_groups`(`id`, `parent_id`, `car_makers`, `car_model`, `car_version`, `group_id`, `group_name`, `group_unique_id`, `language` , `created_at` , `updated_at`) VALUES 
        (null , 0, '$maker', '$model', '$version','$group->idGruppo', $group_name, '$uniqueKey','$lang' , '$created_at' , '$updated_at' )
        ON DUPLICATE KEY UPDATE group_name=$group_name, group_id='$group->idGruppo' , updated_at='$updated_at' ;\n";
    $queries .= "SELECT @id := id FROM `products_groups` WHERE `group_unique_id`='$uniqueKey';\n";
        $get_sub_groups = kromedaHelper::get_sub_group($version , $group->idGruppo  , $lang);
        if(is_array($get_sub_groups)){
          if(count($get_sub_groups) > 0){
            foreach($get_sub_groups as $sub_group){
              $uniqueKey2 = $uniqueKey.$sub_group->idSottogruppo;
              $sub_group_name = \DB::connection()->getPdo()->quote($sub_group->Sottogruppo); 
              $queries .=  "INSERT INTO `products_groups`(`id`, `parent_id`, `car_makers`, `car_model`, `car_version`, `group_id`, `products_groups_group_id`,  `group_name`, `group_unique_id`, `language` , `created_at` , `updated_at`) 
              VALUES (null , @id, '$maker', '$model', '$version', '$sub_group->idSottogruppo' , '$group->idGruppo' ,$sub_group_name, '$uniqueKey2','$lang' ,'$created_at' , '$updated_at'  ) 
              ON DUPLICATE KEY UPDATE group_name=$sub_group_name, group_id='$sub_group->idSottogruppo' , products_groups_group_id='$group->idGruppo' , updated_at='$updated_at' ;\n";
            }
          }
        }
       }
      return CustomDatabase::custom_insertOrUpdate($queries);
   }
   /*End*/
    
    
	public static function get_sub_category_via_version($version_id , $category_id){
	   /*Working*/
	    return Products_group::where([['car_version' , '!=' , $version_id] , [''] , ['deleted_at' , '=' ,NULL] , 
	                                 ['status' , '=' , 'A'] , ['type' , '=' , $type]])
								   ->get();
	}
  
  

	public static function get_sub_categories($type , $parent_id = 0){
	 /*Working*/	
	   return Products_group::where([['parent_id' , '!=' , $parent_id] , ['deleted_at' , '=' ,NULL] , 
	                                 ['status' , '=' , 'A'] , ['type' , '=' , $type]])
								   ->get();
	}
	
	public static function get_sub_category($parent_id , $group_id) {
		/*Working*/
        return Products_group::where([['parent_id' , '=' , $parent_id] , 
		                                  ['deleted_at' , '=' , NULL],
									                     ['status' , '=' , 'A']])
									 ->orWhere([['products_groups_group_id' , '=' ,$group_id]])	
									 ->get();
    }
	
	public static function get_products_groups($version , $lang){
	   return  Products_group::where([['language' , '=' , $lang] , ['car_version' , '=' , $version], ['deleted_at'  ,'=', NULL] , ['parent_id' , '=' ,0] , ['type' , '=' , 1]])
	                          ->get();
	}
	
    public static function get_parent_groups($version , $lang){
      return  Products_group::where([['language' , '=' , $lang] , ['car_version' , '=' , $version], ['deleted_at'  ,'=', NULL] , ['parent_id' , '=' ,0]])
	                          ->orWhere([['car_makers' , '=' , NULL] , ['car_model' , '=' , NULL] , ['parent_id' , '=' , 0] ,['deleted_at'  ,'=', NULL]])
							  ->get();
    }
    
  /*  public static function get_serached_parent_groups($request , $lang){
      return  Products_group::where([ ['language' , '=' , $lang] , ['car_version' , '=' , $request->car_version_id], ['deleted_at'  ,'=', NULL] , ['parent_id' , '=' ,0]])->get();
    }*/
   
    public static function get_all_category($lang = NULL){
        return  DB::table('products_groups')->where([['parent_id', '=', 0] , ['type' , '=' ,1] , ['language' , '=' , $lang]])->get();
    }

    public static function get_unique_category($lang){
      return DB::table('products_groups')->where([['type' , '=' , 1] , ['parent_id' , '=' , 0] ,  ['deleted_at','=',NULL] , 
      ['status' , '=' , 'A'] , ['language' , '=' , $lang]])
      ->groupBy('group_id')
			->get();
    }
   
 
   public static function get_n1_categories($type , $lang){
      /*Working*/
	   /*  $queries = 'select * from `products_groups` where (`parent_id` = 0 and `deleted_at` is null) group by `group_id`';
        return CustomDatabase::get_record($queries); */
       
  /*   return  DB::table('products_groups')->where(array('parent_id'=>0 , 'deleted_at'=>NULL)) 
               ->groupBy(array('group_id'))
               ->get(); */
     
       return  DB::table('products_groups')->where([['parent_id', '=', 0] , ['deleted_at' , '=' , NULL] /* , ['status' , '=' , 'A'] */ , ['type' , '=' , $type] , ['language' , '=' , $lang]])->get();
     
    }
   
   
   
   
    public static function get_all_n1_category($car_version){
      return  Products_group::where([['parent_id' , '=' ,0], ['car_version', '=', $car_version]])->get();
    }

     public static function add_kromeda_group($group , $maker , $model , $version ,  $lang){
      return Products_group::updateOrCreate(['car_makers'=>$maker , 'car_model'=>$model , 'car_version'=>$version, 'group_id'=>$group->idGruppo , 'language'=>$lang] ,
          [ 'parent_id'=>0 , 
           'car_makers'=>$maker, 
           'car_model'=>$model,
           'car_version'=>$version,
           'group_id'=>$group->idGruppo,
           'group_name'=>$group->Gruppo,
           'language'=>$lang ,
           'deleted_at'=>NULL]); 
    }
    
     public static function add_kromeda_sub_group($sub_group , $maker , $model , $version , $group_id ,  $lang){
    return Products_group::updateOrCreate(['car_version'=>$version, 'group_id'=>$sub_group->idSottogruppo , 'parent_id'=>$group_id , 'language'=>$lang] ,
	           [
			   'parent_id'=>$group_id , 
			   'car_makers'=>$maker, 
			   'car_model'=>$model,
			   'car_version'=>$version,
               'group_id'=>$sub_group->idSottogruppo,
			   'group_name'=>$sub_group->Sottogruppo,
			   'language'=>$lang ,
			   'deleted_at'=>NULL]); 
	}


     public static function add_group($request , $group_arr , $lang){
        return Products_group::updateOrCreate(['car_version'=>$request->car_version , 'group_id'=>$group_arr['group']['idGruppo']] ,
                      ['car_makers'=>$request->car_makers , 'car_model'=>$request->car_models , 'car_version'=>$request->car_version ,
                      'group_id'=>$group_arr['group']['idGruppo'] , 'group_name'=>$group_arr['group']['Gruppo'] ,  'language'=>$lang , 'deleted_at'=>NULL]);
    }


    public static function get_group_first($group_id){
      return Products_group::where([['id' , '=' , $group_id] , ['status', '=' ,'A'], 
	                                 ['deleted_at' , '=' , NULL]])->first();
	  }
    
    public static function get_group($car_version = NULL){
      if($car_version != NULL){
        return Products_group::where([['car_version' , '=' , $car_version], ['deleted_at'  ,'=', NULL]])->get();
      }
      return Products_group::where('deleted_at'  ,'=', NULL)->orderBy('group_name' ,'ASC')->get();
    }
    
    public static function get_group_list(){
	   //return Products_group::where('deleted_at'  ,'=', NULL)->orderBy('group_name' ,'ASC')->paginate(15);
	   return Products_group::where([['deleted_at'  ,'=', NULL] ,['parent_id' , '=' ,0]])->orderBy('created_at' ,'DESC')->paginate(15);
	}
    
    public static function create_new_group($request) {
        return Products_group::create([
            'car_makers' => $request->marker_id , 
            'car_model' => $request->models_id , 
            'car_version' => $request->version_id , 
            'group_name' => $request->group_name , 
        ]);
    }
    
    

    public static function get_all_products_group($selected_group) {
	    return DB::table('products_groups as a')
                   ->leftjoin('products_groups as b' , 'a.parent_id' , '=' , 'b.id')
                   ->select('a.*' , 'b.group_name as category')
                   //->whereNotIn('a.id' , $selected_group)
				   ->where([['a.parent_id', '!=', 0] , ['a.type' , '=' , 1]])->groupBy('a.group_id')
				   ->get('a.group_id');
     /* return Products_group::where([['parent_id', '!=', 0]])
             ->whereNotIn('id' , $selected_group)
             ->paginate(15);
*/    }
    
   /*  public static function get_all_products_group($lang) {
	    $kromeda_spare_response =  DB::table('products_groups as a')
                   ->leftjoin('products_groups as b' , 'a.parent_id' , '=' , 'b.id')
                   ->select('a.*' , 'b.group_name as category')
           ->where([['a.parent_id', '!=', 0] , ['a.type' , '=' , 1] , ['a.deleted_at' , '=' , NULL] ,  ['b.deleted_at' , '=' , NULL] , ['a.status' , '=' , 'A'] , ['b.status' , '=' , 'A'] , ['a.language' , '=' , $lang]])
           ->groupBy('a.group_id');
           //->get();

          
      $kromeda_groups =   DB::table('products_groups as a')
           ->leftjoin('products_groups as b' , 'a.parent_id' , '=' , 'b.id')
           ->select('a.*' , 'b.group_name as category')
           ->where([['a.parent_id', '!=', 0] , ['a.type' , '=' , 2] ,  ['a.deleted_at' , '=' , NULL] , ['b.deleted_at' , '=' , NULL] , ['a.status' , '=' , 'A'] , ['b.status' , '=' , 'A'] , ['a.language' , '=' , $lang]])
            ->union($kromeda_spare_response)
            ->get('a.group_id');
      return $kromeda_groups;      

    } */
    
    public static function get_search_spares_details($group_item_id, $language , $selected_group) {
        if(!empty($group_item_id) && !empty($language)) {
            return Products_group::where([['parent_id' , '!=' , 0], ['car_version', '=', $group_item_id] , ['language', '=', $language]])
                                   ->whereNotIn('id' , $selected_group)
                                   ->get();
        }
    }
    
    
    public static function get_n1_category_list(){
     return Products_group::where([['deleted_at'  ,'=', NULL] ,
     ['parent_id' , '=' ,0],
      ['car_version', '=', 95794]])
      ->orWhere([['car_makers' , '=' , NULL] , ['car_model' , '=' , NULL] , ['parent_id' , '=' , 0] , ['deleted_at' , '=' , NULL]])
			->orderBy('created_at' ,'DESC')->get();
    }

    
    public static function get_custom_n1_category_list(){
	   return Products_group::where([['deleted_at'  ,'=', NULL] ,['parent_id' , '=' ,0], ['type', '=', 2]])->orderBy('created_at' ,'DESC')->get();
	}
	
   public static function add_custom_n1_group($request, $lang){
        return Products_group::create(
            [
                'parent_id'=>0 ,
                'group_name'=>$request->group_name,
				'description'=>$request->description,
                'type'=>2,
                'group_id'=>0,
				'priority'=>$request->priority,
                'language'=>$lang 
            ]); 
    }
    
    public static function add_custom_n2_sub_group($request, $group_details,  $lang){
        return Products_group::create(
            [
                'parent_id'=>$request->group_name ,
                'group_name'=>$request->sub_group_name,
                'description'=>$request->description,
                'type'=>2,
                'group_id'=>0,
                'priority'=>$request->priority,
                'group_unique_id'=>uniqid(),
                'products_groups_group_id'=>$group_details->group_id,
                'language'=>$lang 
            ]); 
	}

  

  public static function edit_category_details($group_id , $request) {
        return Products_group::where([['id', '=' ,$group_id]])       
                   ->update(['group_name'=>$request->edit_group_name,
							 'description' => $request->description , 
							 'priority'=>$request->priority]);
  }

    /*public static function edit_custom_category_details($request) {
        return Products_group::where('id', $request->category_id)       
                            ->update(['group_name' => $request->edit_group_name, 'description' => $request->description]);
    }*/
	
    public static function get_all_parent_groups($version , $lang){
      return  Products_group::where([['car_version', '=', NULL], ['parent_id' , '=' ,0]])->orWhere([ ['language' , '=' , $lang] , ['car_version' , '=' , $version], ['deleted_at'  ,'=', NULL] , ['parent_id' , '=' ,0]])->get();
    }
	
    public static function get_all_groups($version_id) {
      return Products_group::where([['car_version' , '=' , $version_id]])->orWhere([['car_version' , '=' , NULL], ['deleted_at'  ,'=', NULL]])->get();
    }
    
	
	
    public static function get_spare_groups_detils($id) {
        return Products_group::where([['group_id' , '=' , $id]])->first();
    }
    
    
	/*For APP API*/
	   public static function find_product__group_details($product_id){
	     return Products_group::where([['deleted_at' , '=' , NULL] , ['id','=' ,$product_id]])->first();
	   }
	/*End*/
	
	   public static function get_n1_category($parent_id){
	     return Products_group::where([['id' , '=' , $parent_id] ])->first();
	   }
	
	public static function check_subgroups_today_execute($group_id){
	    return Products_group::whereDate('updated_at', Carbon::today())
		                          ->where([['parent_id' , '=' , $group_id]])
								  ->get();
	}
	
	
	
	public static function update_custom_n2_sub_group($request, $group_details){
	   	return Products_group::where([['id','=',$request->edit_n2_category_id]])->update(
		  ['parent_id'=>$request->groups,
		  'group_name'=>$request->edit_sub_group_name,
		  'description'=>$request->description,
		  'products_groups_group_id'=>$group_details->group_id,
		  'priority'=>$request->priority,
		  ]);
    }
    
    public static function update_kromeda_n2_sub_group($request){
	   return Products_group::where([['id','=',$request->edit_n2_category_id]])->update(
		  ['parent_id'=>$request->groups ,
		  'group_name'=>$request->edit_sub_group_name,
		  'description'=>$request->description,
		  'products_groups_group_id'=>0,
		  'priority'=>$request->priority,
		  ]);
  }
  
  public static function get_all_sub_category($lang){
	  return DB::table('products_groups')->where([['language' , '=' , $lang] , ['parent_id' , '!=' , 0] , ['deleted_at' , '=' , NULL]])->groupBy('group_id') ->get();
	  /*$sql = "SELECT `*` FROM products_groups where language = '".$lang."' AND parent_id != 0 GROUP BY group_id";
	  
	  $sub_category = CustomDatabase::get_record($sql);
	  return  collect($sub_category);*/
	  //$sub_category = DB::statement($sql);
	  //return $sub_category;
	  //return Products_group::where([['language' , '=' , $lang] , ['deleted_at' , '=' , NULL]])->get();
  }
  
/*Filtered n1 category*/
public static function versions_category($version , $lang){
  /* DB::enableQueryLog(); */
  $kromeda_n1 =  DB::table('products_groups')->where([['car_version','=',$version] , ['type','=',1] , ['parent_id','=',0],['deleted_at','=',NULL]]);
  return DB::table("products_groups")->where([['type','=',2], ['deleted_at','=',NULL], ['parent_id','=',0]])->union($kromeda_n1)->get(); 
} 
/*End*/

  /*Working*/
  public static function get_all_unique_category($lang){
    $kromeda_n1 =  DB::table("products_groups")->where([['type' , '=' , 1] , ['parent_id' , '=' , 0] , ['deleted_at' , '=' , NULL], ['language' , '=' , $lang]])->groupBy('group_id');
		return DB::table("products_groups")->where([['type' , '=' , 2] ,['deleted_at' , '=' , NULL] , ['parent_id' , '=' , 0] , ['language' , '=' , $lang]])->union($kromeda_n1)->get(); 
  }


  public static function get_all_unique_sub_category($lang){
    $kromeda_n2 =  DB::table("products_groups")->where([['type' , '=' , 1] , ['parent_id' , '!=' , 0] , ['language' , '=' , $lang]])->groupBy('group_id');
    return DB::table("products_groups")->where([['type' , '=' , 2] , ['parent_id' , '!=' , 0]])->union($kromeda_n2)->get(); 
  }
	
	
}

?>