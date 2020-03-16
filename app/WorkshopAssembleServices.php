<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

class WorkshopAssembleServices extends Model
{
    protected  $table = "workshop_assemble_services";
    protected $fillable = ['id', 'workshop_id', 'categories_id', 'description', 'max_appointment' , 'hourly_rate' , 'status' , 'deleted_at' , 'created_at' , 'updated_at' ];

    public static function get_assemble_service_details($user_category_details) {
         return DB::table('users_categories as uc')
                            ->leftjoin('workshop_assemble_services as was' , [['was.categories_id' , '=' , 'uc.categories_id'] , ['was.workshop_id' , '=' , 'uc.users_id']])     
                            ->select('uc.categories_id' , 'uc.id' , 'was.description' , 'was.max_appointment', 'was.hourly_rate')  
                            ->where([['uc.id','=',$user_category_details->id] , ['uc.deleted_at' , '=' , NULL] , ['was.deleted_at' , '=' , NULL]])
							->where([['was.deleted_at' , '=' , NULL]])
                            ->first();
    }

    public static function update_assemble_services($request) {
        return WorkshopAssembleServices::updateOrCreate(
            ['workshop_id'=>Auth::user()->id,'categories_id'=> $request->category_id],
            [
                'workshop_id'=>Auth::user()->id , 
                'categories_id'=>$request->category_id , 
                // 'description'=>$request->description  , 
                'max_appointment'=>$request->max_appointment, 
                'hourly_rate'=>$request->hourly_cost , 
                'status'=>'A'
            ]);
    }
    
    /*For APP API */
   public static function find_workshop_price($workshop_id , $main_category_id){
	   return DB::table('users_categories as a')
	                   ->join('workshop_assemble_services as b' , [['b.workshop_id' , '=' , 'a.users_id'] , ['a.categories_id' , '=' , 'b.categories_id']])
					   ->where([['a.users_id','=',$workshop_id],['a.categories_id' , '=' , $main_category_id],['a.deleted_at','=',NULL]])
					   ->where([['b.deleted_at' , '=' , NULL]])
					   ->first();
   } 
   public static function update_all_assemble_services($request) {
        return WorkshopAssembleServices::where('workshop_id' , '=' , Auth::user()->id)
            ->update(['hourly_rate'=>$request->hourly_cost, 'max_appointment'=>$request->max_appointment]);
    }
  /*End*/
}
