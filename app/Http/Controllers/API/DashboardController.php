<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User; 
use Illuminate\Support\Facades\Auth; 
use Validator;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\Model\Kromeda;
use App\Model\UserDetails;
use sHelper;
use App\Library\kromedaHelper;
use App\Http\Controllers\API\UserDetail;

class DashboardController extends Controller {

	public $successStatus = 200;
    public $fuel_type_arr = ['D'=>'Diesel' , 'B'=>'Petrol' , 'd'=>'Diesel' ,'b'=>'Petrol'];
    
	private function GetRequest($sessKey,$func,$auth,$str='')
    {
            $base_url='https://krws.autosoft.it/ws/krwsrest_v12.dll/datasnap/rest/tkrm/';
            $base_urllast='ws-officinetop/tphs82ja92/';
            $url=$base_url.$func.'/'.($auth==true?$base_urllast:'').($sessKey==false?'':$sessKey.'/').$str;
   
            $client = new \GuzzleHttp\Client();
            $request = $client->get($url);
            $response = $request->getBody()->getContents();
            $response=trim($response);
            $response=json_decode($response);
            return $response;
    }

    public function respFormat($stcode , $msg , $data,$data_set){
      $resp=array();
      $resp['status_code']=$stcode;
      $resp['message']=$msg;
      $resp['data']=$data;
      $resp['data_set']=$data_set;
      return response()->json($resp, $this-> successStatus); 
    }


    public function home(){
      $res=$this->GetRequest(false,'CreateSessionKey',true,'');
      $resp=$this->GetRequest($res->result[1],'CheckSessionKey',true,'');
      if($resp->result[1]){
           //$respo=$this->GetRequest($res->result[1],'GetProfileInfo',false,'');
           return $res->result[1];
      }
      else
      {
      	   return '';
      }
    }

    public function getMakers(){ 
	    set_time_limit(500);
        $respon = kromedaHelper::get_makers();
        if(count($respon) > 0){
            return sHelper::get_respformat(1, null, null, $respon);
        }else{
           return sHelper::get_respformat(0, "Something went wrong , please try again !!!", null, null);
        }
    }

    public function getModels($idMarca){
        $respon = kromedaHelper::get_models($idMarca);
        if(count($respon) > 0){
		    return sHelper::get_respformat(1, null, null, $respon);
		 }
        else{
		   return sHelper::get_respformat(0, "No model available in this maker", null, null);
		 } 
    }

      
    public function getVersion($idMarca , $idYear){
        $version_respon = kromedaHelper::get_versions($idMarca , $idYear);
          if(count($version_respon) > 0){
             $new_version_collection = collect($version_respon);
             if($new_version_collection->count() > 0){
                  $new_version_collection = $new_version_collection->map(function($version , $key){
                      $version_arr = (array) $version; 
                        if(!array_key_exists('fueltype' , $version_arr)){
                            if(array_key_exists($version_arr['Alimentazione'] , $this->fuel_type_arr)){
                                $fuel_type = $this->fuel_type_arr[$version_arr['Alimentazione']]; 
                              }
                             else{
                                 $fuel_type = "Others"; 
                              }
                              $version->fueltype = $fuel_type;     
                        }
                      $version->car_size = sHelper::get_car_size_via_body($version->Body);
                      return $version;
                  });
             }
             return sHelper::get_respformat(1, null, null, $new_version_collection);
          }
        else{
            return sHelper::get_respformat(0, "Version not available for this model !!!", null, null);
        }  
    }





    public function getParts($idVeh,$lan='ENG')
    {
        $url='getParts'.'/'.$idVeh.'/'.$lan;
        $respon=Kromeda::get_response($url);
        $respon=json_decode($respon);
        if($respon->success==false)
        {
            $sess_key=$this->home();
            $str=$idVeh.'/'.$lan;
            $respon=$this->GetRequest($sess_key,'OE_GetActiveGroups',false,$str);
            Kromeda::add_response($url,$respon);
        }else{
           $respon=$respon->data;
        }
        if(strlen($respon->result[0])>0)
        {
            return $this->respFormat(0,$respon->result[0],null,null);
        }
        return $this->respFormat(1,'',null,$respon->result[1]->dataset);
    }
    public function getCarSearch($opt,$val,$lan='ENG')
    {
    	$url='getCarSearch'.'/'.$opt.'/'.$val.'/'.$lan;
        $respon=Kromeda::get_response($url);
        $respon=json_decode($respon);
        if($respon->success==false)
        {
            $sess_key=$this->home();
            $str=$opt.'/'.$val.'/'.$lan;
            $respon=$this->GetRequest($sess_key,'CP_Search',false,$str);
            Kromeda::add_response($url,$respon);
        }else{
           $respon=$respon->data;
        }
        return $this->respFormat(1,'',null,$respon->result[1]->dataset);
    }

    public function getSearchPlate($plateNo,$lan='ENG'){
        $url='getSearchPlate'.'/'.$plateNo.'/'.$lan;
        $respon=Kromeda::get_response($url);
        $respon=json_decode($respon);
        if($respon->success==false)
        {
           $sess_key=$this->home();
           $str=$plateNo.'/'.$lan;
           $respon=$this->GetRequest($sess_key,'CP_SearchPlate',false,$str);
            Kromeda::add_response($url,$respon , "CP_SearchPlate");
        }else{
           $respon=$respon->data;
        }
        if(strlen($respon->result[0])>0)
        {
             $err=explode(':',$respon->result[0]);
            $err=trim($err[1]);
            return $this->respFormat(0,$err,null,null);
        }
        // return $this->respFormat(1,'',$respon->result[1]->vehicles[0],null);    
        $resp=$this->searchCarListInfo($respon->result[1]->vehicles[0]);
        //return $resp;
        return $this->respFormat(1,'',$resp,null);    
    }


    public function getSubParts($idVeh,$idsubpa,$lan='ENG')
    {
    	$url='getSubParts'.'/'.$idVeh.'/'.$idsubpa.'/'.$lan;
        $respon=Kromeda::get_response($url);
        $respon=json_decode($respon);
        if($respon->success==false)
        {
            $sess_key=$this->home();
            $str=$idVeh.'/'.$idsubpa.'/'.$lan;
            $respon=$this->GetRequest($sess_key,'OE_GetActiveSubGroups',false,$str);
            Kromeda::add_response($url,$respon , "OE_GetActiveSubGroups");
        }else{
           $respon=$respon->data;
        }
        return response()->json(['response' => $respon->result[1]], $this-> successStatus); 
    }

    
    public function getPartsItems($idVeh,$idpart,$lan='ENG')
    {
    	$url='getPartsItems'.'/'.$idVeh.'/'.$idpart.'/'.$lan;
        $respon=Kromeda::get_response($url);
        $respon=json_decode($respon);
        if($respon->success==false)
        {
            $sess_key=$this->home();
            $str=$idVeh.'/'.$idpart.'/'.$lan;
            $respon=$this->GetRequest($sess_key,'OE_GetActiveItemsByGroup',false,$str);
            Kromeda::add_response($url,$respon , "OE_GetActiveItemsByGroup");
        }else{
           $respon=$respon->data;
        }
        return response()->json(['response' => $respon->result[1]], $this-> successStatus); 
    }

    public function getSubPartsItems($idVeh,$idsubpa,$lan='ENG')
    {
    	$url='getSubPartsItems'.'/'.$idVeh.'/'.$idsubpa.'/'.$lan;
        $respon=Kromeda::get_response($url);
        $respon=json_decode($respon);
        if($respon->success==false)
        {
            $sess_key=$this->home();
            $str=$idVeh.'/'.$idsubpa.'/'.$lan;
            $respon=$this->GetRequest($sess_key,'OE_GetActiveItemsBySubgroup',false,$str);
            Kromeda::add_response($url,$respon , "OE_GetActiveItemsBySubgroup");
        }else{
           $respon=$respon->data;
        }
        return response()->json(['response' => $respon->result[1]], $this-> successStatus);
    }
    
    public function getItemNo($idVeh,$idItem)
    {
    	$url='getItemNo'.'/'.$idVeh.'/'.$idItem;
        $respon=Kromeda::get_response($url);
        $respon=json_decode($respon);
        if($respon->success==false){
            $sess_key=$this->home();
            $str=$idVeh.'/'.$idItem;
            $respon=$this->GetRequest($sess_key,'OE_GetPartNumber',false,$str);
            Kromeda::add_response($url,$respon , "OE_GetPartNumber");
        }else{
           $respon=$respon->data;
        }
        return response()->json(['response' => $respon->result[1]], $this-> successStatus);
    }

    public function getItemNoUniq($idVeh,$idItem,$lan='ENG')
    {
    	$url='getItemNoUniq'.'/'.$idVeh.'/'.$idItem.'/'.$lan;
        $respon=Kromeda::get_response($url);
        $respon=json_decode($respon);
        if($respon->success==false)
        {
            $sess_key=$this->home();
            $str=$idVeh.'/'.$idItem.'/'.$lan;
            $respon=$this->GetRequest($sess_key,'OE_GetCriterias',false,$str);
            Kromeda::add_response($url,$respon , "OE_GetCriterias");
        }else{
           $respon=$respon->data;
        }
        return response()->json(['response' => $respon->result[1]], $this-> successStatus);
    }

    public function addSearchCar($plateNo , $lang = 'en'){
        $lang = sHelper::get_set_language($lang);
        $search_plate_response = kromedaHelper::search_plate_number($plateNo ,$lang);
        if(!empty($search_plate_response)){
            $input = ['carMakeName'  => $search_plate_response->idMarca, 
                      'carModelName' => $search_plate_response->idModello.'/'.$search_plate_response->Anno, 
                      'carVersion'   => $search_plate_response->idVeicolo , 'carBody'=>$search_plate_response->Body , 'number_plate'=>$plateNo];
            $save_car_response = UserDetails::addCar($input , 2 , null);
            if($save_car_response){
                return $this->respFormat(1,'Car save successfully !!!',null, null);
            }
            else { 
                return $this->respFormat(0 , 'Your car details is already exists' , null , null);     
            }  
        }
        else{
            return sHelper::get_respformat(0 , ", please check your plate number . Kromeda has not provide response , related with plate number !!!", null, null);
        }
    }


    public function searchCarListInfo($inner)
    {
        $respArr=array();
        $respInner=json_decode(Kromeda::get_response('getMakers'));

        $innerArr=array();
        $i=0;

        if($respInner->success==true)
        {
            foreach($respInner->data->result[1]->dataset as $key=>$maker)
            {
                if($maker->idMarca==$inner->idMarca)
                {
                  $innerArr['carMake']= $maker;
                }

            }

        }

        
        $url='getVersion'.'/'.$inner->idModello.'/'.$inner->Anno;

        $respon=json_decode(Kromeda::get_response($url));
        if($respon->success==true)
        {
           foreach($respon->data->result[1]->dataset as $key=>$versions)
           {
                // return $versions;
                if($versions->idVeicolo==$inner->idVeicolo)
                {
                   $innerArr['carVers']=$versions;
                }
           }
        }

     
        $respon=json_decode(Kromeda::get_response('getModels'.'/'.$inner->idMarca));

        if($respon->success==true)
        {
           foreach($respon->data->result[1]->dataset  as $key=>$models)
           {

            if($models->idModello==$inner->idModello)
                {
                   $innerArr['carModel']=$models;
                }
           }
        }
        return $innerArr;
    }

}