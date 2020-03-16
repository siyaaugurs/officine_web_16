<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use apiHelper;
use Spatie\ArrayToXml\ArrayToXml;

class Tyre24 extends Controller{


    public function get_tyres(Request $request){
        $arr = ['ns1:searchString'=>'winter tyres 195/65 R15 H W1956515H' , 'ns1:minAvailability'=>2];
        $response = apiHelper::get_soap_response($request->all() , "getTyres");
		if($response != FALSE){
			        $xml = simplexml_load_string($response);
					$body = $xml->children('SOAP-ENV', true)->Body->children('ns1', true)->getTyresResponse;
					$detail_response = json_decode(json_encode((array)$body), TRUE); 
				  try{
					  if(count($detail_response) > 0){
						return $detail_response; 
					   }
					 }
				   catch(RequestException  $e){ 
                      return FALSE;
                   } 	 
        }
    }

    public  function get_details(Request $request){
           //$arr = ['ns1:itemId'=>357469 , 'ns1:minAvailability'=>'2'];
           $response = apiHelper::get_soap_response($request->all() , "getDetails");
           if($response != FALSE){
                $xml = simplexml_load_string($response);
                $body = $xml->children('SOAP-ENV', true)->Body->children('ns1', true)->detailPage;
                $detail_response = json_decode(json_encode((array)$body), TRUE); 
                try{
                    if(count($detail_response) > 0){
                        return $detail_response;
                   }
                } 
                catch(RequestException  $e){ 
                    return FALSE;
                 } 
           }
           else return FALSE;
    }

    public function get_rim_manufacturer(Request $request){
        $arr = [];
        $response = apiHelper::get_soap_response($arr , "getRimManufacturers");
        print_r($response);exit;
    }
     
    public function get_rim_type_for_manufacturer(Request $request){
        $arr = ['ns1:rimManufacturer'=>'Ford'];
        $response = apiHelper::get_soap_response($arr , "getRimTypesForRimManufacturer");
        print_r($response);exit;
    }
    
    public  function  get_rim_workmanship_for_rim_type(Request $request){
        $arr = ['ns1:rimType'=>'B-Max'];
        $response = apiHelper::get_soap_response($arr , "getRimWorkmanshipForRimType");
        echo "<pre>";
        print_r($response);exit;
    }

    public  function  get_rim(Request $request){
        $arr = ['ns1:rimManufacturer'=>$request->maker];
        $response = apiHelper::get_soap_response($arr , "getRims");
        print_r($response);exit;
    }
    

     public  function  search_rims(Request $request){
        /*Working */
		/*8217 for this multipole value*/
        $arr = ['ns1:alcar'=>$request->alcar, 'ns1:minAvailability'=>1];
        $response = apiHelper::get_soap_response($request->all() , "searchRims");
        if($response != FALSE){
                $xml = simplexml_load_string($response);
                $body = $xml->children('SOAP-ENV', true)->Body->children('ns1', true)->searchRimsResponse;
                $detail_response = json_decode(json_encode((array)$body), TRUE); 
                try{
                    if(count($detail_response) > 0){
                        return $detail_response;
                   }
                } 
                catch(RequestException  $e){ 
                    return FALSE;
                 } 
           }
           else return FALSE;
    }
    
    public  function  get_comfort_alloy_rim_car_brands(Request $request){
        /*Working */
        $arr = [];
        $response = apiHelper::get_soap_response($arr , "getComfortAlloyRimCarBrands" , 2);
        print_r($response);exit;
    }
    
    public  function  get_comfort_alloy_rim_car_models(Request $request){
        /*Working */
        //$arr = ["ns:brandID"=>18];
        //echo "<pre>";
        //print_r($request->all());exit;
        $response = apiHelper::get_soap_response($request->all() , "getComfortAlloyRimCarModels" , 2);
        print_r($response);exit;
    }

    public  function  get_comfort_alloyrim_car_types(Request $request){
        /*Working */
        //$arr = ["ns:brandID"=>18];
        // /echo "<pre>";
        //print_r($request->all());exit;    
        $response = apiHelper::get_soap_response($request->all() , "getComfortAlloyRimCarTypes" , 2);
        print_r($response);exit;
    }

    public function get_comfort_alloy_rim_car_info(Request $request){
        $response = apiHelper::get_soap_response($request->all() , "getComfortAlloyRimCarInfo" , 2);
        print_r($response);exit;
    }
    
    

}
