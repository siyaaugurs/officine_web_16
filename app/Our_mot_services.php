<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use DB;

class Our_mot_services extends Model{
    
	protected  $table = "our_mot_services";
    protected $fillable = [
        'id', 'service_name', 'service_description' , 'service_km' , 'month', 'car_makers', 'car_models', 'car_version' , 'created_at' , 'updated_at', 'deleted_at'];  
	
	
	public static function save_mot_services($request){
	   // return $request->all();
        $result =  Our_mot_services::updateOrCreate(
                                    ['id' => $request->mot_service_id ],
                                    [   'service_name'=>$request->service_name ,
                                        'service_description'=>$request->service_description,
                                        'service_km'=>$request->service_km,
                                        'month'=>$request->month,
                                        'car_makers'=>$request->car_makers,
                                        'car_models'=>$request->car_models,
                                        'car_version'=>$request->car_version
                                    ]);
        if($result){
            if(!empty($request->n3_category)){
                if(count($request->n3_category) > 0){
                    foreach($request->n3_category as $key=>$value){
                        DB::table('mot_n3_category')->insert(['our_mot_services_id'=>$result->id , 'n3_category_id'=>$value]); 
                    }
                    return TRUE;  
                }
                else{
                    return TRUE;
                }
            }
            else return TRUE;
        }									 
    }   

    public static function delete_mot_services($id) {
        return Our_mot_services::where('id' , '=' , $id)
            ->update(['deleted_at'=>date('Y-m-d H:i:s')]);
    }
    public static function get_mot_details($id) {
        return Our_mot_services::where('id', '=', $id)->first();
    } 
    public static function get_workshop_mot_services() {
        return DB::table("our_mot_services")->where([['deleted_at' , '=' , NULL]])->get();
    }
}
