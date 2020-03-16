<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Products_group;
use sHelper;
use App\Library\kromedaHelper;
use App\ProductsGroupsItem;
use App\Library\kromedaDataHelper;

class ProductsGroups extends Controller{
    
    
    public function save_n3_category(Request $request){
    $group_details = Products_group::find($request->sub_group_id);
    if($group_details != NULL){
        $lang = sHelper::get_set_language($request->language);
        $response = kromedaDataHelper::get_and_save_products_item($request->version ,$request->sub_group_id ,$lang);
        /*Get Product Item */
        $n3_category = sHelper::get_group_items_for_users($group_details);
        $option = '';
        if($n3_category->count() > 0){
              foreach($n3_category as $category){
            $front = ''; $rear = '';
            if(!empty($category->front_rear)) $front = $category->front_rear;
            if(!empty($category->left_right)) $rear = $category->left_right;	 
            $option .=  '<option value="'.$category->id.'">'.$category->item." ".$front." ".$rear.'</option>';
          }
		       return json_encode(array("status"=>200 , 'response'=>$option));   
        }
        else{
          return json_encode(array("status"=>404));    
        }
        /*End*/
      }
	}
	
	public function save_sub_groups(Request $request){
		$response = kromedaDataHelper::save_sub_groups($request->group_id);
        /*Get Sub Groups script start*/
		  $group_details =  Products_group::find($request->group_id);
		  if($group_details != NULL){
			  $sub_groups = sHelper::get_sub_categories($group_details);
			  if($sub_groups->count() > 0){
				  return json_encode(array("status"=>200,'response'=>$sub_groups));   
				}
			  else{
				 return json_encode(array("status"=>404));   
				}	
			}
		   else{
			   return json_encode(array("status"=>100));   
			}	
		
		/*End*/
	}
	
	public function save_groups(Request $request){
	  if(!empty($request->versions)){
		 $lang = sHelper::get_set_language($request->language);
		 $response = kromedaDataHelper::get_groups_and_save($request->maker , $request->model_value , $request->versions , $lang);
		 $group_list = Products_group::get_parent_groups($request->versions , $lang);
		 if($group_list->count() > 0){
		    return json_encode(array("status"=>200 , 'response'=>$group_list));  
		   } 
		 else{
		     return json_encode(array("status"=>404));  
		   }  
		}
	   else{
		  return json_encode(array("status"=>100));  
		}	 
	}
    
    public function get_action(Request $request , $action){
        if($action == "check_priorities"){
		     if(!empty($request->priority_val)){
			    $response =  \DB::table($request->table)->where([['priority' , '=' , $request->priority_val]])->first(); 
				if($response != FALSE){
				    echo 1;exit;
				  }
				else{
				    echo 2;exit;
				  }  
				}
			 else{
			    return json_encode(array("status"=>100 , "response"=>'<div class="notice notice-danger"><strong> Wrong </strong> Something went wrong , please enter the all required fields  !!! </div>')); 
			  } 
		  }
		 /*Get n3 with all*/
		 if($action == "get_sub_category_n3"){
		    if(!empty($request->group_id) && !empty($request->language)){
          $language = sHelper::get_set_language($request->language);
          $group_details = Products_group::get_group_first($request->group_id);
          if($group_details != NULL){
             $group_items = ProductsGroupsItem::get_groups_items($request->group_id , $language);
             if($group_items->count() > 0)
             return json_encode(array("status"=>200 , "response"=>$group_items));  
             else return json_encode(array("status"=>404)); 
          }				
	 }
	 else{
	   return json_encode(array("status"=>100 , "response"=>'<div class="notice notice-danger"><strong> Wrong </strong> Something went wrong , please enter the all required fields  !!! </div>'));
	 }
		  }
		/*End*/
		
	 /*Get Sub category script start*/
		if($action == "get_sub_category_n2"){
      $language = sHelper::get_set_language($request->language);  
      $category_details = Products_group::get_group_first($request->category_id);
        if($category_details != NULL){
            $flag = 0; $option = ''; 
            if(!empty($request->version) && $request->version != "all"){
				      $group_details = Products_group::where([['car_version' , '=' ,$request->version],['group_id' , '=' , $category_details->group_id] , ['parent_id' , '=' ,0] , ['status' , '=' , 'A']])->first();   
              if(empty($group_details)){
                /*Add all n1*/
                $groups = kromedaHelper::get_groups($request->version , $language);
                if(count($groups) > 0){
                    $groups_result =   Products_group::add_kromeda_group_2($groups , NULL , NULL , $request->version , $language);
                  }
                /*End*/
                }
                if($category_details != NULL){
                  $groups_details =  Products_group::where([['car_version' , '=' , $request->version] , ['language' , '=' , $language] , 
                  ['parent_id' , '=',0] , ['status' , '=','A']])->first();
                 /* ['group_id' , '=' , $category_details->group_id] */ 
                  
                  if($groups_details != NULL){
                      $unique_sub_group = Products_group::where([['parent_id' , '=' , $groups_details->id] , ['status' , '=' , 'A']])
                                                        ->orWhere([['products_groups_group_id' , '=' ,$groups_details->group_id]])
                                                        ->orderBy('group_name' , 'ASC')	
                                                         ->get();
                      $flag = 1;
                  }                                          
                }
              }
              else{
                $unique_sub_group = Products_group::get_sub_category($category_details->id , $category_details->group_id); 
                if($unique_sub_group->count() > 0){
                   $flag = 1;
                }
              }
              if($unique_sub_group->count() > 0){
				        $option .= '<option value="0" hidden="hidden">Select Sub category</option>';
                $option .= '<option value="all">All Sub Category</option>'; 
                  foreach($unique_sub_group as $sub_groups){
                        $option .= '<option value="'.$sub_groups->id.'" data-type="'.$sub_groups->type.'">'.$sub_groups->group_name.'</option>';
                       }
              }

              if($flag == 1){
                return json_encode(array("status"=>200 , "response"=>$option));
              }
              else{
                return json_encode(array("status"=>400 ));
              }
            }
        else{
          return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong> Wrong </strong>Something went wrong please try again  !!! </div>'));
        } 
			}
		/*End*/
		
		
		/*End*/
		
         /*Search Products Groups script */ 
       if($action == "search_products_group"){
          $validator = \Validator::make($request->all(), [
            'makers_id' => 'required',
            'models' => 'required' , 'car_version_id'=>'required'
           ]);
           if($validator->fails()){
            return json_encode(array( "error"=> $validator->errors()->getMessages(), "status"=>400));
          }
          $language = sHelper::get_set_language($request->language);
         /*  $group_list = Products_group::get_products_groups($request->car_version_id , $language);
          $n1_custom_category_list = Products_group::get_custom_n1_category_list(); */
          $version_categories = Products_group::versions_category((string) $request->car_version_id , $language);
          if($version_categories->count() > 0){
            $categories = [];
              foreach($version_categories as $category){
                $categories[] = kromedaDataHelper::arrange_n1_category($category);
              }
            $categories = collect($categories);
            }
          return view('products.component.category_list_new')->with(['categories'=>$categories]);
        }
        /*End Script*/
        
        /*Get N3 category script start*/
           if($action == "get_n3_category"){
            if(!empty($request->group_id) && !empty($request->language)){
              $lang = sHelper::get_set_language($request->language);  
              $sub_groups_details = Products_group::where([['id' , '=' , $request->group_id] , ['deleted_at' , '=' , NULL] , ['language' , '=' , $lang]])->first();
              if($sub_groups_details != NULL){
                  $products_groups_items = sHelper::get_group_items($sub_groups_details);
                    if($products_groups_items->count() > 0){
                      foreach($products_groups_items as $item){
                        $item = kromedaDataHelper::arrange_n3_category($item);
                         
                      }
                      $products_groups_items = $products_groups_items->where('deleted_at' , NULL);
                      if($products_groups_items->count() > 0){
                        return json_encode(array("status"=>200 , 'response'=>$products_groups_items));
                      } 
                    }
                    else{
                      return json_encode(array("status"=>404));
                    }
              }
              else{
                return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong> Wrong </strong>Something went wrong please try again  !!! </div>'));
              }
            }
            else{
           return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong> Wrong </strong>Something went wrong please try again  !!! </div>'));
            }
          } 
        /*End*/
        /*Check priority script star5t */
          if($action == "check_priority"){
                    if(is_numeric($request->priority_val)){
                        if($request->type == "subgroups"){
                        $check_priority = Products_group::where([['priority' , '=' , $request->priority_val] , ['parent_id' , '!=' , 0]])->first();
                    }
                    else{
                        $check_priority = Products_group::where([['priority' , '=' , $request->priority_val] , ['parent_id' , '=' , 0]])->first();
                    }
                    if($check_priority != NULL){
                        echo 1;exit;
                    }
                    else{ echo 2;exit; }
            }
           else{
                 echo 1;exit; 
           }    
            
          }
        /*End */
       /*Get  Groups script start*/
        if($action == "get_sub_groups"){
            if(!empty($request->group_id)){
			      	$lang = sHelper::get_set_language($request->language);  
              $groups_details = Products_group::where([['id','=',$request->group_id]])->first();
              if($groups_details != NULL){
				          	$sub_groups = sHelper::get_sub_group($groups_details);
                        if($sub_groups->count() > 0){
                            foreach($sub_groups as $s_groups){
                              $s_groups =  kromedaDataHelper::arrange_n2_category($s_groups);
                            }		
                            $sub_groups = $sub_groups->where('deleted_at' , NULL);
                             if($sub_groups->count() > 0){
                                return json_encode(array("status"=>200 , "response"=>$sub_groups));
                              }
                              else{
                                  return json_encode(array("status"=>404));
                              }	
                        }
                        else{
                            return json_encode(array("status"=>404));
                        }
                    } 
                    else{
                    return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong> Wrong </strong>Something went wrong please try again  !!! </div>'));  
                    } 
            }
            else{
            return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger"><strong> Wrong </strong>Something went wrong please try again  !!! </div>'));    
            }	
        }
	  /*End*/	
    }
}
