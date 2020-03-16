<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Models_details extends Model{
    
    protected  $table = "model_details";
    protected $fillable = [
        'id', 'maker_slug', 'maker_response' , 'model_slug' ,'model', 'model_response' , 'year' , 'year_response','generations','tires','rims','status' , 'language' ,'created_at' 
        , 'updated_at']; 
        
     
    public static function save_model_details($maker_slug , $model_slug  , $year, $language,  $response , $model_detail){
        $model = $model_detail->idModello."/".$model_detail->ModelloAnno;
        $response_decode = json_decode($response);
        return Models_details::updateOrCreate(['maker_slug'=>$maker_slug, 'model_slug'=>$model_slug , 'language'=>$language],
                                                ['maker_slug'=>$maker_slug,
                                                'maker_response'=>json_encode($response_decode->make),
                                                'model'=>$model,
                                                'model_slug'=>$model_slug,
                                                'model_response'=>json_encode($response_decode->model),
                                                'year'=>$year,
                                                'year_response'=>json_encode($response_decode->years),
                                                'generations'=>json_encode($response_decode->generations),
                                                'tires'=>json_encode($response_decode->tires),
                                                'rims'=>json_encode($response_decode->rims),
                                                'language'=>$language,
                                                ]);
    }    
    
    /* public static function save_model_details($maker_slug , $model_slug  , $year, $language,  $response){
        $response_decode = json_decode($response);
        return Models_details::updateOrCreate(['maker_slug'=>$maker_slug , 'model_slug'=>$model_slug , 'year'=>$year , 'language'=>$language], 
                                              ['maker_slug'=>$maker_slug, 
                                              'maker_response'=>json_encode($response_decode->make),
                                              'model_slug'=>$model_slug,
                                              'model_response'=>json_encode($response_decode->model),
                                              'year'=>$year,
                                              'year_response'=>json_encode($response_decode->years),
                                              'generations'=>json_encode($response_decode->generations),
                                              'tires'=>json_encode($response_decode->tires),
                                              'rims'=>json_encode($response_decode->rims),
                                              'language'=>$language,                                       
                                              ]); 
    }     */


    public static function get_model_details($maker_slug , $model_slug  , $year, $language){
        return Models_details::where([['maker_slug','=',$maker_slug] , ['model_slug','=',$model_slug] , ['language' , '=',$language]])->first();
    }
    
    
    public static function get_model_details_new($maker_slug, $model_collection, $language){
		$model = $model_collection->idModello."/".$model_collection->ModelloAnno;
        return Models_details::where([['maker_slug','=',$maker_slug],['model' ,'=',$model] , ['language','=',$language]])->first();
    }

    public static function get_tyre($maker_slug,$model_slug,$year){
       	return Models_details::where([['maker_slug', '=', $maker_slug], ['model_slug' ,'=',$model_slug],['year','=',$year]])->first();
    	
    	 }
 
}
