<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

class Users_category extends Model{
     
    protected  $table = "users_categories";
	protected $fillable = [
       'id', 'users_id','categories_id'  , 'for_quotes','deleted_at' ,'created_at' , 'updated_at'
    ];

    public static function add_user_category($cat_id){
	   return Users_category::updateOrcreate(['users_id'=>Auth::user()->id ,
	                                          'categories_id'=>$cat_id],
											 ['users_id'=>Auth::user()->id ,
	                                          'categories_id'=>$cat_id,
											  'deleted_at'=>NULL
											 ]); 
    }


    
    public static function spare_group_services($users_id){
        $result =   DB::table('users_categories as a')
	            ->join('main_category as b' , 'a.categories_id' , '=' , 'b.id')
				->where('users_id' , '=' , $users_id)
				->where('b.type' , '=' , 1)
                ->orderBy('b.main_cat_name' , 'DESC')
                ->select('b.main_cat_name' , 'b.id as id')
                ->get();
        if($result->count() > 0) return $result; else return FALSE;        
    }
    
    
   public static function get_users_category($users_id){
        $result =   DB::table('users_categories as a')
	                   ->join('main_category as b' , 'a.categories_id' , '=' , 'b.id')
				       ->where([['users_id' , '=' , $users_id] , ['a.deleted_at' , '=' , NULL]])
					   ->where([['b.deleted_at' , '=' , NULL]/*, ['b.private', '!=', 1]*/])
                       ->orderBy('b.main_cat_name' , 'DESC')
                        ->select('b.main_cat_name' , 'b.id as id' , 'a.for_quotes')
                       ->get();
        if($result->count() > 0) return $result; else return FALSE;        
    }

    public static function delete_users_cat($users_id){
        return DB::table('users_categories')->where([['users_id','=',$users_id]])
		                                     ->update(['deleted_at'=>now() , 'for_quotes'=>NULL]);
    }
    
     public static function spare_services_categories($users_id){
        $result =   DB::table('users_categories as a')
	            ->join('main_category as b' , 'a.categories_id' , '=' , 'b.id')
	            ->leftjoin('workshop_assemble_services as w' , [['a.categories_id' , '=' , 'w.categories_id'], ['a.users_id' , '=' , 'w.workshop_id']])
				->where([['a.users_id' , '=' , $users_id] , ['a.deleted_at' , '=' , NULL] , ['b.status' , '=' , 'A'] , ['b.type' , '=' , 1] , ['b.deleted_at' , '=' , NULL]])
			    ->orderBy('b.main_cat_name' , 'DESC')
                ->select('b.main_cat_name' , 'a.id as id', 'b.description', 'w.max_appointment', 'w.hourly_rate', 'a.categories_id')
                ->get();
        if($result->count() > 0) return $result; else return FALSE;        
    }
    public static function check_spare_services_categories($users_id, $category_id){
        $result =   DB::table('users_categories as a')
                ->join('main_category as b' , 'a.categories_id' , '=' , 'b.id')
                ->leftjoin('workshop_assemble_services as w' , [['a.categories_id' , '=' , 'w.categories_id'], ['a.users_id' , '=' , 'w.workshop_id']])
                ->where([['a.users_id' , '=' , $users_id] , ['a.deleted_at' , '=' , NULL], ['a.categories_id', '=', $category_id]])
                ->where([['b.type' , '=' , 1] , ['b.deleted_at' , '=' , NULL]])
                ->orderBy('b.main_cat_name' , 'DESC')
                ->select('b.main_cat_name' , 'a.id as id', 'w.description', 'w.max_appointment', 'w.hourly_rate', 'a.categories_id')
                ->get();
        if($result->count() > 0) return $result; else return FALSE;        
    }
    public static function get_assemble_service_details($users_id){
        return $result =   DB::table('users_categories as a')
	            ->join('main_category as b' , 'a.categories_id' , '=' , 'b.id')
	            ->leftjoin('workshop_assemble_services as w' , [['a.categories_id' , '=' , 'w.categories_id'], ['a.users_id' , '=' , 'w.workshop_id']])
				->where([['a.users_id' , '=' , $users_id] , ['a.deleted_at' , '=' , NULL]])
				->where([['b.type' , '=' , 1] , ['b.deleted_at' , '=' , NULL]])
                ->orderBy('b.main_cat_name' , 'DESC')
                ->select('b.main_cat_name' , 'a.id as id', 'w.description', 'w.max_appointment', 'w.hourly_rate', 'a.categories_id')
                ->first();    
    }
    //Get Users
    public static function get_users(){
        return Users_category::where([['categories_id' , '=' , 2], ['deleted_at', '=', NULL]])->get();          
    }
    //End


    /*For car revision users*/
    public static function get_car_revision_services($off_days_workshop_users){
        return DB::table('users_categories as uc')
            ->join('users as u', 'u.id', '=', 'uc.users_id')
            ->join('business_details as bd', 'bd.users_id', '=', 'u.id')
            ->select('u.created_at', 'uc.users_id','u.updated_at', 'u.f_name', 'u.l_name','u.profile_image', 'u.mobile_number', 'u.company_name', 'bd.owner_name',
             'bd.business_name', 'bd.registered_office', 'bd.about_business')
            ->where([['categories_id' , '=' , 2] , ['uc.deleted_at' , '=' , NULL]])
            ->whereNotIn('u.id', $off_days_workshop_users)
            ->get();
    }
    public static function get_car_revision_details($users_id){
        return DB::table('users_categories as uc')
            ->join('users as u', 'u.id', '=', 'uc.users_id')
            ->join('business_details as bd', 'bd.users_id', '=', 'u.id')
            ->select('u.created_at', 'uc.users_id','u.updated_at', 'u.f_name', 'u.l_name','u.profile_image', 'u.mobile_number', 'u.company_name', 'bd.owner_name',
                'bd.business_name', 'bd.registered_office', 'bd.about_business')
            ->where([['categories_id' , '=' , 1] , ['uc.deleted_at' , '=' , NULL] , ['uc.users_id' , '=' , $users_id]])
            ->first();
    }
    
    	public static function get_workshop_list($off_days_workshop_users, $main_cat_id){
		return DB::table('users_categories as uc')
			->join('users as u', 'u.id', '=', 'uc.users_id')
			->join('business_details as bd', 'bd.users_id', '=', 'u.id')
			->select('u.created_at', 'uc.users_id','u.updated_at', 'u.f_name', 'u.l_name','u.profile_image', 'u.mobile_number', 'u.company_name', 'bd.owner_name',
			 'bd.business_name', 'bd.registered_office', 'bd.about_business')
			->where([['categories_id' , '=' , $main_cat_id] , ['uc.deleted_at' , '=' , NULL]])
			->whereNotIn('u.id', $off_days_workshop_users)
			->get();
			
       }
       
   	public static function get_workshop_user_list($main_cat_id){
		return DB::table('users_categories as uc')
			->join('users as u', 'u.id', '=', 'uc.users_id')
			->join('business_details as bd', 'bd.users_id', '=', 'u.id')
			->select('u.created_at', 'uc.users_id','u.updated_at', 'u.f_name', 'u.l_name','u.profile_image', 'u.mobile_number', 'u.company_name', 'bd.owner_name',
			 'bd.business_name', 'bd.registered_office', 'bd.about_business')
			->where([['categories_id' , '=' , $main_cat_id] , ['uc.deleted_at' , '=' , NULL] , ['u.deleted_at' , '=' ,NULL]])->get();
			}
    /*End*/
    
}
