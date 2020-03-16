<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Model\UserDetails;
use App\Library\kromedaHelper;
use App\Products_group;
use App\ExcutedQuery;
use App\CustomDatabase;
use DB;
use App\Maker;
use App\Models;
use App\Version;
use sHelper;
use kromedaSMRhelper;
use App\Usersreferal;
use App\User;


class KronJobs extends Controller{

  
    /*manage referal script start*/
    public function manage_referal_code(Request $request){
      if(!empty($request->user) && !empty($request->inviteto) && !empty($request->referalcode)){
          $check_exists = User::where([['mobile_number' , '=' , $request->inviteto]])->first();
          if($check_exists == NULL){
            $check_referal_code = User::where([['mobile_number','=',$request->user] , ['own_referal_code' , '=' , $request->referalcode]])->first();
            if($check_referal_code != NULL){
              $response = Usersreferal::updateOrcreate(['receiver_to'=>$request->inviteto] ,
                                                       ['sender_from'=>$request->user ,
                                                        'sender_referal_code'=>$request->referalcode, 
                                                        'receiver_to'=>$request->inviteto]);
              if($response){
                //return redirect('http://play.google.com/store/apps/details?id=com.officinetop.officine')
                return redirect('http://play.google.com/store/apps');
              } 
              else{  
                
              }                                        
            }
            else{
              echo "referal code is not valid!!!";exit;
            }
          }
          else{
             echo "you are already registered !!!";exit;
          }
      }
      else{
        echo "Something went wrong , please try again !!!";exit;
      }
      
  }
  /*End*/
    
  public function save_version_response(){
    //echo "working";exit;
    set_time_limit(100000000);
    $string = 'getVersion';
    $response = DB::table('kromedas')->select('id' , 'response')->where('url', 'like', '%'.$string.'%')->get();
    //echo "<pre>";
    //print_r($response);exit;
    if($response->count() > 0){
        foreach($response as $res){
          $decoded_version = json_decode($res->response);
          foreach($decoded_version->result[1]->dataset as $version){
            $encode_version = json_encode($version); 
            $version_response = \App\Version::updateOrCreate(['idVeicolo'=>$version->idVeicolo] , ['version_response'=>$encode_version]);   
          }
        }
    }
  } 
    
   
  public function cron_for_maintinance_service_times(){
    $lang = sHelper::get_set_language(app()->getLocale());
    $item_times_response = \App\ItemsRepairsTimeId::where([['cron_executed_status' , '=' , NULL]])->paginate(10);
    if($item_times_response->count() > 0){
      foreach($item_times_response as $item){
        $services_time_response = kromedaSMRhelper::kromeda_version_service_time($item->version_id , $item->repair_times_id , $lang);
            $services_time = json_decode($services_time_response);
						if($services_time->status == 200){
							if($lang == "ENG") {
                $time_response = \App\ItemsRepairsServicestime::save_item_repairs_times_eng($item->id , $services_time->response , $lang); 
              } else if($lang == "ITA") {
								$time_response = \App\ItemsRepairsServicestime::save_item_repairs_times_ita($item->id , $services_time->response , $lang); 
							}
							//if($time_response){ 
                //}
              }
        $item->cron_executed_status = 1;
        $item->save();
			}
    }
  }


  public function cron_for_car_maintinance(){
    set_time_limit(100000000);
    $language = sHelper::get_set_language(app()->getLocale());
    $versions_response = \App\Version::where([['car_maintinance_execution' , '=' , NULL]])->paginate(50);
    if($versions_response->count() > 0){
      foreach($versions_response as $version){
         $get_time_id_response = kromedaSMRhelper::kromeda_version_criteria($version->idVeicolo , $language);
         $time_id_arr = json_decode($get_time_id_response);
         if($time_id_arr->status == 200){
             if(count($time_id_arr->response) > 0){
                $item_times_response = \App\ItemsRepairsTimeId::save_item_repairs_time_id($version->idVeicolo , $time_id_arr->response , $language);
                if($item_times_response){
                   $version->car_maintinance_execution = 1;
                   $version->save();
                }
             }
         }
         else if($time_id_arr->status == 404){
            $version->car_maintinance_execution = 1;
            $version->save();
         }
         
      }  
     }
 }
  
  public function cron_for_spare_parts(){
    $n3_category_list = \App\ProductsGroupsItem::where([['type' , '=' , 1] , ['deleted_at' , '=' , NULL] , ['cron_executed_status' , '=',NULL]])->paginate(5);
    if($n3_category_list->count() > 0){
      foreach($n3_category_list as $category_list){
        $item_number = \App\ProductsItemNumber::get_part_item_number($category_list->id);
        if($item_number->count() > 0){
          foreach($item_number as $number){
            //$get_products = kromedaHelper::oe_products_item("107", "13404PT0004"); 
            $get_products = kromedaHelper::oe_products_item((string) $number->CodiceListino ,(string) $number->CodiceOE);
            if(is_array($get_products)){
               if(count($get_products) > 0){
                 $add_products_response = \App\ProductsNew::add_products_by_kromeda_new($category_list  , $number , $get_products);
               }   
            }
            //$get_products_other_cross = kromedaHelper::oe_getOtherproducts("023", "34116767269");
            $get_products_other_cross = kromedaHelper::oe_getOtherproducts((string) $number->CodiceListino , (string) $number->CodiceOE); 
            if(is_array($get_products_other_cross)){
                if(count($get_products_other_cross)){
                  $other_products_response = \App\ProductsNew::add_other_products_by_kromeda_new($category_list , $number , $get_products_other_cross);
                }
            }
           /*End*/         
          }
        }
        $category_list->cron_executed_status = 1;
        $category_list->save();
      }
    }
  }

 
  public function cron_for_n3_category(){
    $product_sub_group = \App\Products_group::where([['cron_executed_status' , '=' , NULL] , ['parent_id' , '!=' , 0] , ['type' , '=' , 1] , ['deleted_at' , '=' , NULL]])->paginate(25);
    if($product_sub_group->count() > 0){
      foreach($product_sub_group as $sub_group){
        $get_products_item = kromedaHelper::get_sub_products_by_sub_group($sub_group->car_version , $sub_group->group_id , $sub_group->language);
        if(is_array($get_products_item)){
          if(count($get_products_item) > 0){
            $productsItemResponse = \App\ProductsGroupsItem::add_group_items_new($get_products_item , $sub_group->id  , $sub_group->language ,$sub_group->car_version , $sub_group->group_id);
            if($productsItemResponse){
              $sub_group->cron_executed_status = 1;
              $sub_group->save();
            }
          } 
        } 
      }
    }     
  }

 
	
  /*Cron for groups*/
  public function cron_for_group(){
    set_time_limit(100000000);
    $language = sHelper::get_set_language(app()->getLocale());
    $versions = \App\Version::where([['execution_status' , '=' , NULL]])->paginate(40);
    if($versions->count() > 0){
      $lang = sHelper::get_set_language(app()->getLocale());
      foreach($versions as $version){
        /*Get Model detail*/
         $model_details = Models::get_model($version->model);
        /*End*/
        if($model_details != NULL){
            $groups =  kromedaHelper::get_products_group($version->idVeicolo , $lang); 
            if(is_array($groups)){
               if(count($groups) > 0){
                $response = \App\Products_group::add_kromeda_group_2($groups , $model_details->maker , $version->model, $version->idVeicolo , $language);
                if($response){
                  $version->execution_status = 1;
                  $version->save();
                }  
              }
            }
          }
		  }  		   
	  }
  } 
  /*End*/	

  /*Cron for version start*/
  public function cron_for_version(){
     set_time_limit(100000000);
     $models = Models::where([['cron_executed_status' , '=' , NULL]])->get();
     if($models->count() > 0){
       foreach($models as $model){
         $model_key =  $model->idModello."/".$model->ModelloAnno;
         try{
           $versions = kromedaHelper::get_versions($model->idModello , $model->ModelloAnno);
          }
          catch(RequestException  $e){ 
            continue;
          }
           if(count($versions) > 0){
               $save_version_response = Version::save_version($model_key , $versions);
               $model->cron_executed_status = 1;
               $model->save();
           }
      }
        
     }
  } 
  /*End*/
  
  public function cron_for_model(){
    set_time_limit(100000000);
    /*Get All makers*/
    $maker = Maker::where([['cron_executed_status' , '=' , NULL]])->get();
   
	/*End*/
        if($maker->count() <= 0){
      /*Save makers*/
          $makers =   kromedaHelper::get_makers();
          if(count($maker) > 0){
            $save_maker = \App\Maker::save_makers($makers);
          }
      /*End*/
        }
      $maker = Maker::where([['cron_executed_status' , '=' , NULL]])->get();
      foreach($maker as $m_maker){
        if($m_maker->cron_executed_status == NULL){
          try{
            /*Get Makers model script */
            $models = kromedaHelper::get_models($m_maker->idMarca);
            /*End*/
            if(count($models) > 0){
              /*Save model record script */
               $save_model_response = Models::save_models($m_maker , $models);
              /*End*/ 
              }
          }
          catch(RequestException  $e){ 
             continue;
          }               
          $m_maker->cron_executed_status = 1; 
          $m_maker->save();
        }
      }
  }

	
	
    public function users_cars($action){
      set_time_limit(100000000);
        $queries = '';
         /*Get Executed version*/
         $version_arr = [];
          $executed_version =   DB::table('versions')->where([['execution_status' , '=' , 1]])->get();
          if($executed_version->count() > 0){
             $version_arr = $executed_version->pluck('idVeicolo')->all(); 
          }
          /*End*/
          $users_cars_details = UserDetails::where([['deleted_at' , '=' , NULL]])->whereNotIn('carVersion' , $version_arr)->get();
          /*Get version unique value in */
          if($users_cars_details->count() > 0){
               $users_cars_details =  $users_cars_details->unique('carVersion');
            }
          /*End*/
          //echo "<pre>";
          //print_r($users_cars_details);exit;
         /*Get Car Group and version for all unique value */
         //   $sql = "SELECT `*` FROM user_details GROUP BY carVersion";
		      //	$user_detail = CustomDatabase::get_record($sql);
          //  $users_cars_details = collect($user_detail);
         /*End*/

      
        
          if($users_cars_details->count() > 0){
            $exists = NULL; 
            foreach($users_cars_details as $users_details){
               /*Check Execute Or Not */
                 $get_executed_query = DB::table('versions')->where([['idVeicolo' , '=' , $users_details->carVersion] , ['execution_status' , '=' , 1]])->first();
               /*End*/ 
                  if($get_executed_query == NULL){
                      //$url = "kron_getParts/".$users_details->carVersion."/".$users_details->language;    
               //$exists = ExcutedQuery::get_record($url);
                   if($exists == NULL){
                       $groups =  kromedaHelper::get_products_group($users_details->carVersion , $users_details->language);
                       if(is_array($groups)){
                          if(count($groups) > 0){
                             $response = \App\Products_group::add_kromeda_group_2($groups , $users_details->carMakeName , $users_details->carModelName , $users_details->carVersion ,  $users_details->language);
                             //$response = 1;
                             if($response){
                               $get_all_groups = \App\Products_group::where([['car_version' ,'=',$users_details->carVersion] , ['deleted_at' , '=' , NULL] , ['type' , '=' , 1]])->get();
                               if($get_all_groups->count() > 0){
                                 /*N3 script start*/  
                                 foreach($get_all_groups as $group){
                                   if($group->parent_id != 0){
                                     $get_products_item = kromedaHelper::get_sub_products_by_sub_group($group->car_version , $group->group_id , $group->language);
                                      if(is_array($get_products_item)){
                                        if(count($get_products_item) > 0){
                                          $productsItemResponse = \App\ProductsGroupsItem::add_group_items_new($get_products_item , $group->id  , $group->language ,$group->car_version);
                                        }
                                      }
                                    }
                                    else{
                                      /*Add  kromeda groups items script start*/
                                      $get_products_item = kromedaHelper::get_groups_items_by_group($group->car_version , $group->group_id , $group->language);
                                      if(is_array($get_products_item)){
                                        if(count($get_products_item) > 0){
                                          $productsItemResponse = \App\ProductsGroupsItem::add_group_items_new($get_products_item , $group->id  , $group->language ,$group->car_version);
                                        }
                                      }
                                      /*End*/
                                    }
                                  }
                                /*End*/  
                                /*Get N3 Script start*/
                                foreach($get_all_groups as $groups){
                                  $products_group_item = \App\ProductsGroupsItem::where([['products_groups_id' , '=' , $groups->id] , ['deleted_at' , '=' , NULL]])->get();
                                  if($products_group_item->count() > 0){
                                    foreach($products_group_item as $item){
                                      $item_number = \App\ProductsItemNumber::get_part_item_number($item->id);
                                      if($item_number->count() > 0){
                                        foreach($item_number as $number){
                                          /*Get Products from getCrossMethod*/
                                          //$get_products = kromedaHelper::oe_products_item("107", "13404PT0004");
                                          $get_products = kromedaHelper::oe_products_item((string) $number->CodiceListino , $number->CodiceOE);
                                          if(is_array($get_products)){
                                                  if(count($get_products)){
                                                    $add_products_response = \App\ProductsNew::add_products_by_kromeda_new($item  , $number , $get_products);
                                                  }
                                                }
                                              /*End*/
                                              /*Get Products From Get OtherCross Script Start*/
                                              //$get_products_other_cross = kromedaHelper::oe_getOtherproducts("023", "34116767269");
                                              $get_products_other_cross = kromedaHelper::oe_getOtherproducts((string) $number->CodiceListino , $number->CodiceOE); 
                                              if(is_array($get_products_other_cross)){
                                                 if(count($get_products_other_cross)){
                                                    $other_products_response = \App\ProductsNew::add_other_products_by_kromeda_new($item , $number , $get_products_other_cross);
                                                 }
                                              }
                                             /*End*/
                                          }
                                        }
                                      }
                                  }
                                }  
                                /*End*/
                              }
                             }
                          } 
                       }
                   } 
                   /*change user details status*/
                   //UserDetails::where([['id' , '=' , $users_details->id]])->update(['executed'=>1]);
                   //DB::enableQueryLog();
                   $change_execute_status = DB::table('versions')->where([['idVeicolo' , '=' , $users_details->carVersion]])->update(['execution_status'=>1]);
                   //dd(DB::getQueryLog());
                   /*End*/  
                  }
            }
          }
    }
}
