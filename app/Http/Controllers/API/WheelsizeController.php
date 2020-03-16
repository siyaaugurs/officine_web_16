<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
class WheelsizeController extends Controller 
{
	public $successStatus = 200;

    private function GetRequest($func,$str='')
    {
            $base_url='https://api.wheel-size.com/v1/';
            $user_key='b8f5788d768c1823dd920c2576b644f9';
            $url=$base_url.$func.'/?user_key='.$user_key.$str;
   
            $client = new \GuzzleHttp\Client();
            $request = $client->get($url);
            $response = $request->getBody()->getContents();
            $response=json_decode($response);
            return $response;
    }

    public function validateOpt($key,$req)
    {
    	$response=array('responseStatus'=>true,'responseMessage'=>'Success','dataset'=>array());
        $opt_arr=array(
           'getMakers'=>array('countries','countries_exclude'),
           'getModels'=>array('year'),

           'getListYear'=>array('model'),
           'getModelsModiFitWheel'=>array('trim'),
           'getListBoltPattern'=>array('stud','stud_min','stud_max','pcd','pcd_min','pcd_max','countries','countries_exclude'),
           'getListCarModelsByBoltPattern'=>array('rim_diameter','rim_width','offset','offset_min','offset_max','cb','cb_min','cb_max','countries','countries_exclude'),

           'getTyre'=>array('width','width_min','width_max','aspect_ratio','aspect_ratio_min','aspect_ratio_max','rim_diameter','rim_diameter_min','rim_diameter_max','countries','countries_exclude'),

           'getListCarModelsByTire'=>array('width','width_min','width_max','aspect_ratio','aspect_ratio_min','aspect_ratio_max','rim_diameter','rim_diameter_min','rim_diameter_max','countries','countries_exclude'),
           'getGenerationOfModel'=>array('year'),

           'getAllModelInfo'=>array('trim'),
           'getModelByRim_Bolt'=>array('offset_min','offset_max'),
           'getModelBytyre'=>array('width','width_min','width_max','aspect_ratio','aspect_ratio_min','aspect_ratio_max','rim_diameter','rim_diameter_min','rim_diameter_max')

        );
        $reqArr=array_keys($req->all());

        $optArq=count($opt_arr[$key]);
        $reqArg=count($reqArr);

        if((int)$optArq>=(int)$reqArg)
        {
            $diffArr=array_diff($reqArr,$opt_arr[$key]);
            if(count($diffArr)>0)
            {
             	$response['responseStatus']=false;
                $response['responseMessage']='Following keys name are mismatch';
                $response['dataset']=$diffArr;
            }
            else
            {
            	//$response['dataset']=$reqArr;
             	$response['dataset']=($reqArg>0?'&':'').http_build_query($req->all());
            }
                
        }  
        else
        {
	        $response['responseStatus']=false;
	        $response['responseMessage']='Number of request parameter key is greater than API optional keys';
        }

        return json_encode($response);

    }
    public function getMakers(Request $req)
    {  
        $validate=json_decode($this->validateOpt('getMakers',$req));
        if($validate->responseStatus)
        {
        	$str=$validate->dataset;
        	$func='makes';
            $validate->dataset=$this->GetRequest($func,$str);
        }
        
        return response()->json(['response' =>$validate], $this-> successStatus); 
    }
    
    public function getModels($make,Request $req)
    {
        $validate=json_decode($this->validateOpt('getModels',$req));
        if($validate->responseStatus)
        {
        	$str='&make='.$make.$validate->dataset;
        	$func='models';
            $validate->dataset=$this->GetRequest($func,$str);
        }
        
        return response()->json(['response' =>$validate], $this-> successStatus); 
    }
    
    public function getModelsInfo($make,$modelslug,Request $req)
    {
        $validate=json_decode($this->validateOpt('getModelsInfo',$req));
        if($validate->responseStatus)
        {
        	$str='&make='.$make.'&slug='.$modelslug.$validate->dataset;
        	$func='models';
            $validate->dataset=$this->GetRequest($func,$str);
        }
        
        return response()->json(['response' =>$validate], $this-> successStatus); 
    }

    public function getModelsInfoInYear($make,$modelslug,$year,Request $req)
    {
        $validate=json_decode($this->validateOpt('getModelsInfoInYear',$req));
        if($validate->responseStatus)
        {
        	$str='&make='.$make.'&slug='.$modelslug.$validate->dataset;
    	    $func='models/'.$make.'/'.$modelslug.'/'.$year;
            $validate->dataset=$this->GetRequest($func,$str);
        }
        
        return response()->json(['response' =>$validate], $this-> successStatus);

    }

    public function getListYear($make,Request $req)
    {
        $validate=json_decode($this->validateOpt('getListYear',$req));
        if($validate->responseStatus)
        {
        	$str='&make='.$make.$validate->dataset;
    	    $func='years';
            $validate->dataset=$this->GetRequest($func,$str);
        }
        
        return response()->json(['response' =>$validate], $this-> successStatus);
    }

    public function getModelsModi($make,$modelslug,$year,Request $req)
    {
    
        $validate=json_decode($this->validateOpt('getModelsModi',$req));
        if($validate->responseStatus)
        {
        	$str='&make='.$make.'&model='.$modelslug.'&year='.$year.$validate->dataset;
    	    $func='trims';
            $validate->dataset=$this->GetRequest($func,$str);
        }
        
        return response()->json(['response' =>$validate], $this-> successStatus);
    }

    public function getModelsModiFitWheel($make,$modelslug,$year,$trim='',Request $req)
    {
        $validate=json_decode($this->validateOpt('getModelsModiFitWheel',$req));
        if($validate->responseStatus)
        {
        	$trim=$trim==''?'':'&trim='.$trim;
			$str='&make='.$make.'&model='.$modelslug.'&year='.$year.$validate->dataset;
			$func='vehicles';
            $validate->dataset=$this->GetRequest($func,$str);
        }
        
        return response()->json(['response' =>$validate], $this-> successStatus);
    }

    public function getListBoltPattern(Request $req)
    {
    	
        $validate=json_decode($this->validateOpt('getListBoltPattern',$req));
        if($validate->responseStatus)
        {
        	$str=$validate->dataset;
    	    $func='bolt-patterns';
            $validate->dataset=$this->GetRequest($func,$str);
        }
        
        return response()->json(['response' =>$validate], $this-> successStatus);
    }
    
    public function getListCarModelsByBoltPattern($boltPat,Request $req)
    {

        $validate=json_decode($this->validateOpt('getListCarModelsByBoltPattern',$req));
        if($validate->responseStatus)
        {
        	$str=$validate->dataset;
    	    $func='bolt-patterns/'.$boltPat;
            $validate->dataset=$this->GetRequest($func,$str);
        }
        
        return response()->json(['response' =>$validate], $this-> successStatus);
    }

    public function getTyre(Request $req)
    {
        $validate=json_decode($this->validateOpt('getTyre',$req));
        if($validate->responseStatus)
        {
        	$str=$validate->dataset;
    	    $func='tires';
            $validate->dataset=$this->GetRequest($func,$str);
            return $this->respFormat(1,'',null,$validate->dataset);
        }
        return $this->respFormat(0,$validate->responseMessage,null,$validate->dataset);
        
    }

    public function getListCarModelsByTire($tirePat1,$tirePat2,Request $req)
    {

        $validate=json_decode($this->validateOpt('getListCarModelsByTire',$req));
        if($validate->responseStatus)
        {
        	$str=$validate->dataset;
    	    $func='tires/'.$tirePat1.'/'.$tirePat2;
            $validate->dataset=$this->GetRequest($func,$str);
        }
        
        return response()->json(['response' =>$validate], $this-> successStatus);
    }

    public function getGenerationOfModel($make,$model,Request $req)
    {

        $validate=json_decode($this->validateOpt('getGenerationOfModel',$req));
        if($validate->responseStatus)
        {
        	$str='&make='.$make.'&model='.$model.$validate->dataset;
    	    $func='generations';
            $validate->dataset=$this->GetRequest($func,$str);
        }
        
        return response()->json(['response' =>$validate], $this-> successStatus);
    }

    public function getAllModelInfo($make,$model,$year,Request $req)
    {
    	
        $validate=json_decode($this->validateOpt('getAllModelInfo',$req));
        if($validate->responseStatus)
        {
        	$str='&make='.$make.'&model='.$model.'&year='.$year.$validate->dataset;
    	    $func='search/by_model';
            $validate->dataset=$this->GetRequest($func,$str);
        }
        
        return response()->json(['response' =>$validate], $this-> successStatus);
    }
    public function getModelByRim_Bolt($bolt,$rimDia,$rimWid,Request $req)
    {

        $validate=json_decode($this->validateOpt('getModelByRim_Bolt',$req));
        if($validate->responseStatus)
        {
        	$str='&bolt_pattern='.$bolt.'&rim_diameter='.$rimDia.'&rim_width='.$rimWid.$validate->dataset;
    	    $func='search/by_rim';
            $validate->dataset=$this->GetRequest($func,$str);
        }
        
        return response()->json(['response' =>$validate], $this-> successStatus);
    }
    public function getModelBytyre($tireWid,$asp_ratio,$rimDia,Request $req)
    {
        $validate=json_decode($this->validateOpt('getModelBytyre',$req));
        if($validate->responseStatus)
        {
        	$str='&tire_width='.$tireWid.'&aspect_ratio='.$asp_ratio.'&rim_diameter='.$rimDia.$validate->dataset;
    		$func='search/by_tire';
            $validate->dataset=$this->GetRequest($func,$str);
        }
        
        return response()->json(['response' =>$validate], $this-> successStatus);
    }

    public function respFormat($stcode,$msg,$data,$data_set)
    {
      $resp=array();
      $resp['status_code']=$stcode;
      $resp['message']=$msg;
      $resp['data']=$data;
      $resp['data_set']=$data_set;
      return response()->json($resp, $this-> successStatus); 
    }
    

}