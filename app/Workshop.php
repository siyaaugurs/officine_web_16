<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

class Workshop extends Model{
      
	  protected  $table = "workshops";
	  protected $fillable = [
        'id', 'enctype_id',  'users_id', 'title' , 'workshop_start_date' , 'workshop_end_date' , 'workshop_start_time' , 'workshop_end_time' , 'paid_status' , 'address_status' , 'address' ,'landmark','country_id', 'city_id','description','amount','gallery_status' , 'status' , 'created_at' , 'updated_at' ,
    ];
	
	
	public static function get_all_workshop(){
       return DB::table('workshops as a')
	             ->join('users as b', 'a.users_id', '=', 'b.id')
				 ->select('a.*' ,'b.f_name' , 'b.l_name')
				 ->get();
	}
	
	public static function add_workshop($request){
		return  Workshop::create(['enctype_id'=>md5(rand().time()) , 'users_id'=>Auth::user()->id , 'title'=>$request->title , 'workshop_start_date'=>date("Y-m-d" , strtotime($request->start_date)) , 'workshop_end_date'=>date("Y-m-d" , strtotime($request->end_date)) , 'workshop_start_time'=>$request->start_time  , 'workshop_end_time'=> $request->end_time , 'paid_status'=> $request->work_shop_paid , 'address_status'=>$request->address_status  , 'address'=>$request->address  ,'landmark'=>$request->landmark  , 'country_id'=> $request->country , 'city_id'=>$request->city  , 'description'=>nl2br($request->description), 'amount'=>$request->amount  , 'gallery_status'=>$request->image_gallery, 'status'=>0]); 		 
	}
	
	 

	public static function get_workshop($users_id = NULL , $workshop_id = NULL){
		if($workshop_id != NULL){
			 return Workshop::where('enctype_id' , $workshop_id)->first();
		}
		return Workshop::where('users_id' , $users_id)->orderBy('created_at' , 'DESC')->get();
	}
	
	public static function get_workshop_details($id){
		return Workshop::where('enctype_id' , $id)->first();
	}
	
	
	
	
	public static function edit_workshop($request){
		return  Workshop::where('id' ,$request->edit_id)
		                ->update(['title'=>$request->title , 'workshop_start_date'=>date("Y-m-d" , strtotime($request->start_date)) , 'workshop_end_date'=>date("Y-m-d" , strtotime($request->end_date)) , 'workshop_start_time'=>$request->start_time  , 'workshop_end_time'=> $request->end_time , 'paid_status'=> $request->work_shop_paid , 'address_status'=>$request->address_status  , 'address'=>$request->address  ,'landmark'=>$request->landmark  , 'country_id'=> $request->country , 'city_id'=>$request->city ,'description'=>$request->description  , 'amount'=>$request->amount]); 
		 
	}
	
	
}
