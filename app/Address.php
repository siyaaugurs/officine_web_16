<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

class Address extends Model{
    protected  $table = "addresses";
    protected $fillable = [
		'id', 'users_id', 'workshops_id' , 'business_details_id' ,  'address_1' , 'address_2' , 'address_3' , 'landmark','zip_code','country_id','country_name','state_id','state_name' , 'city_id' , 'city_name'  , 'latitude', 'longitude','address_type' , 'primary_status','distance', 'status' , 'is_deleted' ,'created_at' , 'updated_at'];
		    
	public $address_type = ['H' => 'Home','O' => 'Office'];
    public static function add_workshop_address($request , $edit_id = NULL){
		$country_id = 0;
		$country_name = '';
		$city_name = '';
		$city_id = 0; 
		$state_name = '';
		$state_id = 0;
		if(!empty($request->country)){
		     $country_arr = explode('@' , $request->country);
			 $country_id = $country_arr[0];
			 $country_name = $country_arr[1];
		   }
		if(!empty($request->state)){
		   $state_arr =  explode('@' , $request->state);
		   $state_name =$state_arr[1];
		   $state_id =$state_arr[0];		    
		   }
		   
		if(!empty($request->city)){
			$city_arr =  explode('@' , $request->city); 
			$city_name = $city_arr[1];
			$city_id = $city_arr[0]; 
		} 
		
		return  Address::updateOrCreate(['id'=>$edit_id] , 
		                               array(  'users_id'=>Auth::user()->id , 
										'workshops_id'=>$request->workshop_id ,
									    'address_1'=>$request->address_1 ,
										'address_2'=>$request->address_2 ,
										'zip_code'=>$request->zip_code , 
										'country_id'=>$country_id,
										'country_name'=>$country_name,
										'state_id'=>$state_id, 
										'state_name'=>$state_name,
										'city_id'=>$city_id, 
										'city_name'=>$city_name, 'latitude'=>$request->latitude , 'longitude'=>$request->longitude , 'status'=>1 , 'is_deleted'=>0)); 
	}
	
	
	public static function save_profile_address($request){
        $result = Address::where('id' ,$request->address_id)->update(
        ['address_1'=>$request->address,
         'zip_code'=>$request->zip_code, 
		 'latitude'=>$request->lat,
		 'longitude' =>$request->log,
		 'address_type' =>$request->address_type,
        ]);
        return $result;
	}
	public static function add_profile_address($request){
        $result = Address::create(
        ['address_1'=>$request->address,
         'zip_code'=>$request->zip_code, 
		 'latitude'=>$request->lat,
		 'longitude' =>$request->log,
		 'address_type' =>$request->address_type,
		 'users_id' =>Auth::user()->id,
		 'status' => 1,
		 'is_deleted' =>0
        ]);
        return $result;
	}
	
	public static function get_address($workshop_id){
		if(!empty($workshop_id)){
		      return Address::where([['users_id' , '=' , $workshop_id] , ['is_deleted' ,  '!=' , 1]])->get();
		  }
	}

	
	public static function get_address_details($address_id){
	     return Address::where([['id' , '=' , $address_id] ])->first();
	}
	public static function get_address_shipping_details($user_id){
		return Address::where([['workshops_id' , '=' , $user_id] ,['primary_status' ,'=' , 1]])->first();
	}
	
public static function find_workshop_users_location($users_id){	
		//DB::enableQueryLog();
	    return DB::table('addresses as a')
						->leftjoin('users as u' , 'a.users_id' , '=' , 'u.id')->leftjoin('users_categories as uc' ,'a.users_id', '=', 'uc.users_id')
						->where([['a.users_id' , '=' , $users_id] ,['u.users_status', '=','A'],['u.Roll_id', '=',2],['uc.deleted_at' , '=', null],['uc.categories_id', '=', 13] ])->select('a.*' , 'u.id')->get();  
		//print_r(DB::getQueryLog()); die;
	}
	
	public static function save_address_details($response){
	   return  Address::updateOrCreate(['business_details_id'=>$response->id] , 
		                               ['users_id'=>Auth::user()->id , 
										'workshops_id'=>$response->users_id ,
									    'business_details_id'=>$response->id,
										'address_1'=>$response->registered_office,
										'address_2'=>$response->address_2,
										'landmark'=>$response->landmark,
										'zip_code'=>$response->fiscal_code , 
										'primary_status'=>1,
										// 'country_id'=>$response->country_id,
										// 'country_name'=>$response->country_name,
										// 'state_id'=>$response->state_id, 
										// 'state_name'=>$response->state_name,
										// 'city_id'=>$response->city_id, 
										// 'city_name'=>$response->city_name, 
										'latitude'=>$response->latitude ,
										'longitude'=>$response->langitude ,
										'status'=>1 ,
										'is_deleted'=>0]
									 ); 
	}

	public static function user_location_with_distance($user_id , $latitude , $longitude){
		$circle_radius = 3959;
		return Address::select(DB::raw('id,users_id,latitude, longitude,address_1,address_2,address_3,zip_code, ( 6367 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) AS distance'))
		->where([['users_id' , '=' , $user_id] , ['status','=',1] ,  ['is_deleted','!=',1]])
		//->having('distance', '<', 10)
		->orderBY('distance' , 'ASC')
		->get();
}



		public static function get_primary_address($workshop_user_id){
			return Address::where([['primary_status' , '=' ,1] ,['users_id' , '=' ,$workshop_user_id]])->first();	
		}

	
 }
