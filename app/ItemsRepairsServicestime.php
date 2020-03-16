<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;
use sHelper;
use App\CustomDatabase;
use DB;
use App\User;

class ItemsRepairsServicestime extends Model{
   
   
    protected  $table = "items_repairs_servicestimes";
    protected $fillable = [
      'id', 'users_id', 'version_id', 'repair_times_id','items_repairs_time_ids_id','item_id','item','front_rear','left_right', 'action_description', 
      'our_description', 'time_hrs','our_time','id_info','type','status', 'language','created_at','updated_at', 'unique_key'];
	
	  

	  public static function get_items_services($times_id = NULL , $lang = NULL){
		if(!empty($times_id)){
			return ItemsRepairsServicestime::where([['items_repairs_time_ids_id','=', $times_id] , ['language' , '=',$lang]])->get();
		}
	    //$sql = "SELECT * FROM items_repairs_servicestimes WHERE type = 1 GROUP BY item_id UNION SELECT * FROM items_repairs_servicestimes WHERE type = 2;";
		//return DB::select( DB::raw($sql) );	  
	  	$car_maintinance_type1 =  DB::table("items_repairs_servicestimes")->where([['type' , '=' , 1]])->groupBy('item_id');
		return DB::table("items_repairs_servicestimes")->where([['type' , '=' , 2]])->union($car_maintinance_type1)->paginate(20);
	}

	public static function items_services_by_version($version_id , $lang){
	    $times_id_arr = \DB::table('items_repairs_time_ids')->where([['version_id' ,'=',  $version_id] , ['language', '=' , $lang]])->get();
		if($times_id_arr->count() > 0){
		   $times_id_arr = $times_id_arr->pluck('id')->all();
		  return ItemsRepairsServicestime::whereIn('items_repairs_time_ids_id', $times_id_arr)
		  ->orwhere([['type' , '=' , 2]])
		  ->orderBy('type' , 'DESC')
		  ->get();
		 }
	}
	/* s*/
	
	public static function get_items_services_version($version_id , $lang){
		$times_id_arr = \DB::table('items_repairs_time_ids')->where([['version_id' ,'=',  $version_id] , ['language', '=' , $lang]])->get();
		if($times_id_arr->count() > 0){
		$times_id_arr = $times_id_arr->pluck('id')->all();
		 return DB::table('items_repairs_servicestimes as a')
				->select('a.id','a.version_id' , 'a.users_id','a.item' ,'a.items_repairs_time_ids_id','a.item_id','a.front_rear','a.left_right','a.action_description','a.time_hrs','a.id_info','a.type','b.priority','b.our_description','b.k_time',
				'b.our_time','b.language','a.status')
				->leftjoin('items_repairs_servicestimes_details as b', 'a.id', '=', 'b.items_repairs_servicestimes_id')
				->whereIn('a.items_repairs_time_ids_id', $times_id_arr)->where([['a.status' ,'=','A']])->orWhere([['a.type' , '=' , 2]])->get();
		}			
	}

    public static function save_item_repairs_times_eng($version_id, $times_id , $times_responses, $lang){
		$queries = '';
		$uid = User::return_admin_id();
		 foreach($times_responses as $times_response){
			$timeHr = sHelper::replace_comman_with_dot($times_response->time_hrs);
			$uniqueKey = $times_id->id.$times_response->idVoce.$lang;
			$queries .= "INSERT INTO `items_repairs_servicestimes`(`id`, `users_id`, `version_id`, `repair_times_id`, `items_repairs_time_ids_id`, `item_id`, `item`, `front_rear`, `left_right`, `action_description`, `time_hrs`, `id_info`, `status`, `language`, `created_at`, `updated_at`, `unique_key`) VALUES (null, $uid, $version_id,  $times_id->repair_times_id, $times_id->id, $times_response->idVoce, '$times_response->Voce_ENG', '$times_response->ap_ENG', '$times_response->ds_ENG', '$times_response->action_description', $timeHr, '$times_response->id_info', 'P', '$lang', now(), now(), '$uniqueKey') ON DUPLICATE KEY UPDATE items_repairs_time_ids_id=$times_id->id, users_id=$uid, item_id=$times_response->idVoce, item='$times_response->Voce_ENG', front_rear='$times_response->ap_ENG', left_right='$times_response->ds_ENG', action_description='$times_response->action_description', time_hrs=$timeHr, id_info='$times_response->id_info';\n";
		   }
		return CustomDatabase::custom_insertOrUpdate($queries);
	}
	
    public static function save_item_repairs_times_ita($version_id, $times_id,$times_responses ,$lang){
		$queries = '';
		if(Auth::check()){ $uid = Auth::user()->id; }
		else{ $uid = 3;  }
		foreach($times_responses as $times_response){
		 	$timeHr = sHelper::replace_comman_with_dot($times_response->time_hrs);
			$uniqueKey = $times_id->id.$times_response->idVoce.$lang;
			$queries .= "INSERT INTO `items_repairs_servicestimes`(`id`, `users_id`, `version_id`, `repair_times_id`, `items_repairs_time_ids_id`, `item_id`, `item`, `front_rear`, `left_right`, `action_description`, `time_hrs`, `id_info`, `status`, `language`, `created_at`, `updated_at`, `unique_key`) VALUES (null, $uid, $version_id, $times_id->repair_times_id, $times_id->id, $times_response->idVoce, '$times_response->Voce', '$times_response->ap', '$times_response->ds', '$times_response->action_description', $timeHr, '$times_response->id_info', 'P', '$lang', now(), now(), '$uniqueKey') ON DUPLICATE KEY UPDATE items_repairs_time_ids_id=$times_id->id, users_id=$uid, item_id=$times_response->idVoce, item='$times_response->Voce', front_rear='$times_response->ap', left_right='$times_response->ds', action_description='$times_response->action_description', time_hrs=$timeHr, id_info='$times_response->id_info';\n";
		}
		 return CustomDatabase::custom_insertOrUpdate($queries);
	}
	
	public static function get_active_items_services($times_id = NULL , $lang = NULL){
		/*if(!empty($times_id)){
			return ItemsRepairsServicestime::where([['items_repairs_time_ids_id','=', $times_id]])->get();
		}
	  	return ItemsRepairsServicestime::where([['status', '=', 'A']])->orderBy('created_at' , 'DESC')->get();*/
	  	if(!empty($times_id)){
			
			return DB::table('items_repairs_servicestimes as a')
				  ->join('workshop_car_maintinance_service_details as b', 'a.id', '=', 'b.items_repairs_servicestimes_id')
				  ->select("a.*", 'b.hourly_cost')
				  ->where([['a.status', '=', 'A'],['a.items_repairs_time_ids_id','=', $times_id]])
				  ->orderBy('a.created_at' , 'DESC')
				  ->get();
		}
		return DB::table('items_repairs_servicestimes as a')
				  ->join('workshop_car_maintinance_service_details as b', 'a.id', '=', 'b.items_repairs_servicestimes_id')
				  ->select("a.*", 'b.hourly_cost')
				  ->where([['a.status', '=', 'A']])
				  ->orderBy('a.created_at' , 'DESC')
				  ->get();
	}
	
	public static function get_workshop_active_items_services($times_id = NULL , $lang = NULL){
	  	if(!empty($times_id)){
			
			return DB::table('items_repairs_servicestimes as a')
				  ->select("a.*")
				  ->where([['a.status', '=', 'A'],['a.items_repairs_time_ids_id','=', $times_id]])
				  ->orderBy('a.created_at' , 'DESC')
				  ->get();
		}
		return DB::table('items_repairs_servicestimes as a')
				  ->select("a.*")
				  ->where([['a.status', '=', 'A']])
				  ->orderBy('a.created_at' , 'DESC')
				  ->get();
	}
	public static function get_all_maintenance_items() {
		return ItemsRepairsServicestime::where([['status','=', 'A']])->orderBy('id', 'ASC')->get();
	}
	
	public static function add_new_maintenance_service($request) {
			return ItemsRepairsServicestime::create(
				[   'users_id' => Auth::user()->id,
					'item' => $request->item_name ,
					'front_rear' => $request->front_rear ,
					'left_right' => $request->left_right,
					'action_description' => $request->kromeda_description,
					'our_description'=>$request->our_description,
					'time_hrs' =>$request->kromeda_time,
					'our_time'=>$request->our_time,
					'id_info' => $request->info,
					'type'=>2,
					'language' => $request->language,
					'status' =>'A',
					'unique_key' =>uniqid(),
				]);
	}
	
	public static function get_maintenance_services_details($service_id) {
		if(!empty($service_id)) {
			return DB::table('items_repairs_servicestimes as a')
				->select('a.id','a.users_id','a.item' ,'a.items_repairs_time_ids_id','a.item_id','a.front_rear','a.left_right','a.action_description','a.time_hrs','a.id_info','a.type','b.priority','b.our_description','b.k_time','b.our_time','b.language','b.status')
				->leftjoin('items_repairs_servicestimes_details as b', 'a.id', '=', 'b.items_repairs_servicestimes_id')
				->where([['a.id','=', $service_id]])
				->first();
		}
	}

    public static function get_item_repair_details($id){
	   return ItemsRepairsServicestime::where('id' , $id)->first(); 
	}
	public static function search_by_item($item_name, $lang) {
		$car_maintinance_type1 = DB::table("items_repairs_servicestimes")->where([['type' , '=' , 1], ['item','=', $item_name] , ['language' , '=',$lang]])->groupBy('item_id');
		return DB::table("items_repairs_servicestimes")->where([['type' , '=' , 2], ['item','=', $item_name] , ['language' , '=',$lang]])->union($car_maintinance_type1)->get();
	}
	public static function get_service_category($category_id){
	    if(!empty($category_id)) {
			return DB::table('items_repairs_servicestimes')->select('item')->where([['id' , '=' , $category_id]])->first(); 
		}
	}
	
	
}
