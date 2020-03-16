<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Service_weekly_days;
use Auth;
use DB;

class AssembleProducts extends Controller{
   
    public function get_action (Request $request ,$action){ 
        if($action == "get_product") {
            // $data['products'] = \App\Products_assemble::get_assemble_products();
            if(!empty($request->language) || !empty($request->groupId)){
                if($request->language == "en") $lang = "ENG";
                else $lang = "ITA";
                $get_product = \App\Products_assemble::get_assemble_products($request->groupId);
                //  echo "<pre>";
                //  print_r($get_product);exit;
                if($get_product->count() > 0){
                    return json_encode(array("status"=>200 , "response"=>$get_product));
                } else {
                    return json_encode(array("status"=>100));
                }
                              
            } else {
                return json_encode(array("status"=>100 , "msg"=>'<div class="notice notice-danger notice-sm"><strong> Wrong </strong>something went wrong please try again  !!! </div>'));
            }
        }
    }
   
}
