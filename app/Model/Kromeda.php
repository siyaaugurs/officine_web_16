<?php

namespace App\Model;
use App\KromedaLog;
use Illuminate\Database\Eloquent\Model;


class Kromeda extends Model{
    protected $fillable = ['id','url','response','response_date', 'type' , 'type_status', 'created_at','updated_at'];

   public static function add_response($url , $resp , $method = NULL){
	   $match = array('url'=>$url);
	   $resp=json_encode($resp);
	   if($method != NULL){
		   $save_monitoring = KromedaLog::save_kromeda_api_log($method);
		 }
		return  Kromeda::updateOrCreate($match , ['response'=>$resp,'response_date'=>date('Y-m-d')]); 
	}

    public static function get_response_api($url){
      if(!empty($url)){
		 $result = Kromeda::where([['url' , '=' , $url] , ['response_date' , '=' ,date('Y-m-d')]])->get('response');
        if($result->count() > 0){
        	return json_decode($result[0]->response);
        }else{
           return FALSE;
        }
	  }
	}
	
	public static function js_get_response_api($url){
	  if(!empty($url)){
		 $result = Kromeda::where([['url','=',$url]])->first('response');
		if($result != null){
			$new_result = json_decode($result->response);
			return $new_result;
			//return $new_result->result[1]->dataset;
        }else{
           return FALSE;
        }
	  }
	}
	
	public static function get_response_to_database($url){
		$resp = array('success'=>true,'data'=>array());
        $match=array('url'=>$url,'response_date'=>date('Y-m-d'));
    	$match=array('url'=>$url);
		$result = Kromeda::where('url','=',$url)->first('response');
        if($result != null){
        	 $resp['data'] = json_decode($result->response); 
        }else{
            $resp['success'] = false; 
        }
		return  json_encode($resp); 
	}

	public static function get_response($url){
		$resp = array('success'=>true,'data'=>array());
        $match=array('url'=>$url,'response_date'=>date('Y-m-d'));
    	$match=array('url'=>$url);
		$result = Kromeda::where([['url' , '=' , $url] , ['response_date' , '=' ,date('Y-m-d')]])->get('response');
        if($result->count() > 0){
        	 $resp['data']=json_decode($result[0]->response); 
        }else{
            $resp['success']=false; 
        }
		return  json_encode($resp); 
	}

	public static function save_wheel_size_response($url , $response){
		  return Kromeda::updateOrcreate(['url'=>$url , 'type_status'=>2] , 
				                         ['url'=>$url, 
										  'response'=>$response,
										  'type'=>2,
										  'type_status'=>'Wheelsize response', 
										  'response_date'=>date('Y-m-d')
										  ] 						 
		         );
	}

	public static function get_wheelsize_response($url){
		 return Kromeda::where([['url' , '=' , $url] , ['type' , '=' , 2]])->first();  
	}
	
	public static function get_tyre24_response($url){
		return Kromeda::where([['url' , '=' , $url] , ['type' , '=' , 3]])->first();  
	}
	
	
	public static function save_tyre24_response($url , $response){
		return Kromeda::updateOrcreate(['url'=>$url , 'type_status'=>3] , 
									   ['url'=>$url, 
										'response'=>$response,
										'type'=>3,
										'type_status'=>'tyre24 response', 
										'response_date'=>date('Y-m-d')
										] 						 
			   );
  }
}	
