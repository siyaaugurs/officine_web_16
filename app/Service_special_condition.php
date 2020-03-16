<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;
use sHelper;

class Service_special_condition extends Model{
    
    public  $table = "service_special_conditions";
  /*   protected $fillable = ['id', 'users_id', 'workshop_id', 'makers', 'models', 'versions', 'main_category_id', 'category_id', 'all_services', 'cars_id', 'cars_name', 'car_size', 'weight_type','operation_type', 'start_hour', 'end_hour', 'discount_type', 'amount_percentage', 'max_appointement', 'start_date', 'select_type', 'expiry_date','status','created_at', 'updated_at', 'deleted_at']; */
    
  protected $fillable = ['id', 'users_id', 'workshop_id', 'makers', 'models','versions','vehicle_type','season_type','main_category_id', 'category_id','all_services', 'wracker_service_type',  'cars_id', 'cars_name', 'car_size', 'weight_type','operation_type', 'start_hour', 'end_hour', 'discount_type', 'amount_percentage', 'max_appointement', 'start_date', 'select_type', 'expiry_date','status','created_at', 'updated_at', 'deleted_at'];
    

/*Get Tyre special condition script Start*/
public static function get_tyre_special_condition($user_id){
    $result = DB::table('service_special_conditions as sc')
                    ->leftjoin('categories as c' , 'sc.category_id' , '=' , 'c.id')
                    ->select('sc.*' , 'c.category_name' )
                    ->where([['sc.workshop_id' ,'=',$user_id], ['sc.main_category_id', '=', 23], ['sc.deleted_at', '=', NULL]])->get(); 
    return $result;						  
}
/*End*/
   /*Add 	Tyre special Condition script Start*/
	public static function save_tyre_special_conditions($request, $all_services, $category_id) {
        return Service_special_condition::updateOrCreate(['id'=>$request->edit_id] , [
            'users_id' => Auth::user()->id , 
            'workshop_id' => Auth::user()->id  , 
            'main_category_id' =>23, 
            'category_id' => $category_id , 
            'all_services' => $all_services,
            'makers' => $request->car_makers,
            'models' => $request->car_models,
            'versions' => $request->car_version,
            'vehicle_type' => $request->vehicle_type , 
            'season_type'=>$request->season_type,
            'operation_type' => $request->operation_type , 
            'start_hour' =>$request->start_time, 
            'end_hour' =>$request->end_time, 
            'discount_type'=>$request->discount_type , 
            'amount_percentage'=>$request->amount , 
            'max_appointement'=>$request->maximum_appointment , 
            'start_date'=>sHelper::date_format_for_database($request->start_date), 
            'select_type' =>$request->repeat_type, 
            'expiry_date' =>sHelper::date_format_for_database($request->expiry_date),                'status' => 'A'
        ]);
}
/*End*/ 

    public static function add_special_conditions($request, $start_date, $expiry_date, $all_services, $category_id) {
        if(!empty($request)) {
            return Service_special_condition::updateOrCreate(['id'=>$request->edit_id] , [
                'users_id' => Auth::user()->id , 
                'workshop_id' => Auth::user()->id  , 
                'main_category_id' => 1, 
                'category_id' => $category_id , 
                'all_services' => $all_services,
                'makers' => $request->car_makers,
                'models' => $request->car_models,
                'versions' => $request->car_version,
                'car_size' => $request->car_size , 
                'operation_type' => $request->operation_type , 
                'start_hour' => $request->start_time , 
                'end_hour' => $request->end_time , 
                'discount_type' => $request->discount_type , 
                'amount_percentage' => $request->amount , 
                'max_appointement' => $request->maximum_appointment , 
                'start_date' => $start_date , 
                'select_type' => $request->repeat_type , 
                'expiry_date' => $expiry_date , 
                'status' => 'A'
            ]);
        }
    }

    public static function get_special_service_condition($user_id , $con = NULL) {
        if($con != NULL){
        $date = date('Y-m-d');
        return DB::table('service_special_conditions as sc')
                        ->leftjoin('categories as c' , 'sc.category_id' , '=' , 'c.id')->select('sc.*' , 'c.category_name' )
                        ->where([['sc.workshop_id' , $user_id],['sc.main_category_id', '=', 1],['sc.expiry_date', ">=", $date], ['sc.deleted_at', '=', NULL]])->get(); 
        }
        $result = DB::table('service_special_conditions as sc')
        ->leftjoin('categories as c' , 'sc.category_id' , '=' , 'c.id')->select('sc.*' , 'c.category_name' )
        ->where([['sc.workshop_id' , $user_id], ['sc.main_category_id', '=', 1], ['sc.deleted_at', '=', NULL]])->get();
        return $result;
}
public static function get_revision_special_service_condition($user_id , $con = NULL) {
    if($con != NULL){
        $date = date('Y-m-d');
        return DB::table('service_special_conditions as sc')
                        ->leftjoin('categories as c' , 'sc.category_id' , '=' , 'c.id')->select('sc.*' , 'c.category_name' )
                        ->where([['sc.workshop_id' , $user_id] ,['sc.expiry_date', '>=' ,$date], ['sc.main_category_id', '=', 2], ['sc.deleted_at', '=', NULL]])->get();
    }
    $result = DB::table('service_special_conditions as sc')
    ->leftjoin('categories as c' , 'sc.category_id' , '=' , 'c.id')->select('sc.*' , 'c.category_name' )
    ->where([['sc.workshop_id' , $user_id], ['sc.main_category_id', '=', 2], ['sc.deleted_at', '=', NULL]])->get();
    return $result;
}
    public static function add_revision_special_conditions($request, $start_date, $expiry_date, $all_services, $category_id) {
        if(!empty($request)) {
            return Service_special_condition::updateOrCreate(['id'=>$request->edit_id] , [
                'users_id' => Auth::user()->id , 
                'workshop_id' => Auth::user()->id  , 
                'main_category_id' => 2, 
                'category_id' => $category_id , 
                'all_services' => $all_services, 
                'makers' => $request->car_makers,
                'models' => $request->car_models,
                'versions' => $request->car_version,
                'operation_type' => $request->operation_type , 
                'start_hour' => $request->start_time , 
                'end_hour' => $request->end_time , 
                'discount_type' => $request->discount_type , 
                'amount_percentage' => $request->amount , 
                'max_appointement' => $request->maximum_appointment , 
                'start_date' => $start_date , 
                'select_type' => $request->repeat_type , 
                'expiry_date' => $expiry_date , 
                'status' => 'A'
            ]);
        }
    }
    public static function get_maintenance_special_condition($user_id , $con = NULL) {
        if(!empty($con)) {
			$date = date('Y-m-d');
            $result = DB::table('service_special_conditions as sc')
                            ->leftjoin('items_repairs_servicestimes as i' , 'sc.category_id' , '=' , 'i.id')->select('sc.*' , 'i.item' )
                            ->where([['sc.workshop_id' , $user_id], ['sc.expiry_date' , '>=' , $date] , ['sc.main_category_id', '=', 12], ['sc.deleted_at', '=', NULL]])->get();
            return $result;
        }
		return DB::table('service_special_conditions as sc')
                            ->leftjoin('items_repairs_servicestimes as i' , 'sc.category_id' , '=' , 'i.id')->select('sc.*' , 'i.item' )
                            ->where([['sc.workshop_id' , $user_id], ['sc.main_category_id', '=', 12], ['sc.deleted_at', '=', NULL]])->get();	
    }

    public static function get_special_condition($car_id , $user_id) {
        $date = date('Y-m-d');
        $result = DB::table('service_special_conditions as sc')
                        ->leftjoin('items_repairs_servicestimes as i' , 'sc.category_id' , '=' , 'i.id')->select('sc.*' , 'i.item' )
                        ->where([['sc.workshop_id' , $user_id],['sc.expiry_date' , '>=' , $date] ,['sc.main_category_id', '=', $car_id], ['sc.deleted_at', '=', NULL]])->get();
        return $result;
}

    public static function add_maintenance_special_conditions($request, $start_date, $expiry_date, $all_services, $category_id) {
        if(!empty($request)) {
            return Service_special_condition::updateOrCreate(['id'=>$request->edit_id] , [
                'users_id' => Auth::user()->id , 
                'workshop_id' => Auth::user()->id  , 
                'main_category_id' => 12, 
                'category_id' => $category_id , 
                'all_services' => $all_services,
                'makers' => $request->car_makers,
                'models' => $request->car_models,
                'versions' => $request->car_version,
                'operation_type' => $request->operation_type , 
                'start_hour' => $request->start_time , 
                'end_hour' => $request->end_time , 
                'discount_type' => $request->discount_type , 
                'amount_percentage' => $request->amount , 
                'max_appointement' => $request->maximum_appointment , 
                'start_date' => $start_date , 
                'select_type' => $request->repeat_type , 
                'expiry_date' => $expiry_date , 
                'status' => 'A'
            ]);
        }
    }

    public static function get_wrecker_special_condition($user_id) {
        return DB::table('service_special_conditions as sc')
                    ->leftjoin('wracker_services as w' , 'sc.category_id' , '=' , 'w.id')->select('sc.*' , 'w.services_name' )
                    ->where([['sc.workshop_id' , $user_id], ['sc.main_category_id', '=', 13], ['sc.deleted_at', '=', NULL]])->get();
    }

    public static function add_wrecker_special_conditions($request, $start_date, $expiry_date, $all_services, $category_id) {
        if(!empty($request)) {
            return Service_special_condition::updateOrCreate(['id'=>$request->edit_id] , [
                'users_id' => Auth::user()->id , 
                'workshop_id' => Auth::user()->id  , 
                'main_category_id' => 13, 
                'category_id' => $category_id , 
                'all_services' => $all_services,
                'wracker_service_type'=>$request->service_type,
                'makers' => $request->car_makers,
                'models' => $request->car_models,
                'versions' => $request->car_version, 
                'weight_type' => $request->weight_type , 
                'operation_type' => $request->operation_type , 
                'start_hour' => $request->start_time , 
                'end_hour' => $request->end_time , 
                'discount_type' => $request->discount_type , 
                'amount_percentage' => $request->amount , 
                'max_appointement' => $request->maximum_appointment , 
                'start_date' => $start_date , 
                'select_type' => $request->repeat_type , 
                'expiry_date' => $expiry_date , 
                'status' => 'A'
            ]);
        }
    }
    public static function get_special_condition_details($id) {
       return  DB::table('service_special_conditions as sc')
                   ->leftjoin('categories as c' , 'sc.category_id' , '=' , 'c.id')
                   ->select('sc.*' , 'c.category_name' )
                   ->where([['sc.id', '=', $id]])->first();    
    }
  

    public static function get_revision_special_condition_details($id) {
        if(!empty($id)) {
            $result = DB::table('service_special_conditions as sc')
                            ->leftjoin('categories as c' , 'sc.category_id' , '=' , 'c.id')->select('sc.*' , 'c.category_name' )
                            ->where([['sc.main_category_id', '=', 2], ['sc.id', '=', $id]])->first();
            return $result; 
        }
    }
    public static function get_maintenance_special_condition_details($id) {
        if(!empty($id)) {
            $result = DB::table('service_special_conditions as sc')
                            ->leftjoin('items_repairs_servicestimes as i' , 'sc.category_id' , '=' , 'i.id')->select('sc.*' , 'i.item' )
                            ->where([['sc.main_category_id', '=', 12], ['sc.id', '=', $id]])->first();
            return $result; 
        }
    }
    public static function get_wrecker_special_condition_details($id) {
        if(!empty($id)) {
            $result = DB::table('service_special_conditions as sc')
                            ->leftjoin('wracker_services as w' , 'sc.category_id' , '=' , 'w.id')->select('sc.*' , 'w.services_name' )
                            ->where([['sc.main_category_id', '=', 13], ['sc.id', '=', $id]])->first();
            return $result; 
        }
    }
    public static function get_assemble_special_condition($user_id) {
        if(!empty($user_id)) {
            $result = DB::table('service_special_conditions as sc')
                        ->leftjoin('main_category as m' , 'sc.category_id' , '=' , 'm.id')->select('sc.*' , 'm.main_cat_name' )
                        ->where([['sc.workshop_id' , $user_id], ['sc.main_category_id', '=', 0], ['sc.deleted_at', '=', NULL]])->get();
            return $result;
        }
    }
    public static function add_assemble_special_conditions($request, $start_date, $expiry_date, $all_services, $category_id) {
        if(!empty($request)) {
            return Service_special_condition::updateOrCreate(['id'=>$request->edit_id] , [
                'users_id' => Auth::user()->id , 
                'workshop_id' => Auth::user()->id  , 
                'main_category_id' => 0, 
                'category_id' => $category_id , 
                'all_services' => $all_services,
                'makers' => $request->car_makers,
                'models' => $request->car_models,
                'versions' => $request->car_version, 
                'operation_type' => $request->operation_type , 
                'start_hour' => $request->start_time , 
                'end_hour' => $request->end_time , 
                'discount_type' => $request->discount_type , 
                'amount_percentage' => $request->amount , 
                'max_appointement' => $request->maximum_appointment , 
                'start_date' => $start_date , 
                'select_type' => $request->repeat_type , 
                'expiry_date' => $expiry_date , 
                'status' => 'A'
            ]);
        }
    }
    public static function get_assemble_special_condition_details($id) {
        if(!empty($id)) {
            $result = DB::table('service_special_conditions as sc')
                            ->leftjoin('main_category as m' , 'sc.category_id' , '=' , 'm.id')->select('sc.*' , 'm.main_cat_name' )
                            ->where([['sc.main_category_id', '=', 0], ['sc.id', '=', $id]])->first();
            return $result; 
        }
    }
     public static function add_mot_special_conditions($request, $start_date, $expiry_date,$all_services, $category_id) {
        if(!empty($request)) {
            return Service_special_condition::updateOrCreate(['id'=>$request->special_condition_id] , [
                'users_id' => Auth::user()->id , 
                'workshop_id' => Auth::user()->id  , 
                'main_category_id' => 3, 
                'category_id' => $category_id , 
                'all_services' => $all_services,
                'makers' => $request->car_makers,
                'models' => $request->car_models,
                'versions' => $request->car_version,
                'operation_type' => $request->operation_type , 
                'start_hour' => $request->start_time , 
                'end_hour' => $request->end_time , 
                'discount_type' => $request->discount_type , 
                'amount_percentage' => $request->amount , 
                'max_appointement' => $request->maximum_appointment , 
                'start_date' => $start_date , 
                'select_type' => $request->repeat_type , 
                'expiry_date' => $expiry_date , 
                'status' => 'A'
            ]);
        }
    }

    public static function get_mot_special_condition($user_id, $con = NULL) {
        if(!empty($con)) {
            $date = date('Y-m-d');
            $result = DB::table('service_special_conditions as sc')
                            ->leftjoin('version_services_schedules_intervals as i' , 'sc.category_id' , '=' , 'i.id')
                            ->where([['sc.workshop_id' , $user_id], ['sc.expiry_date' , '>=' , $date] , ['sc.main_category_id', '=', 3], ['sc.deleted_at', '=', NULL]])->get();
            return $result;
        }
        return DB::table('service_special_conditions as sc')
                            ->leftjoin('version_services_schedules_intervals as i' , 'sc.category_id' , '=' , 'i.id')->select('sc.*' , 'i.interval_description_for_kms' )
                            ->where([['sc.workshop_id' , $user_id], ['sc.main_category_id', '=', 3], ['sc.deleted_at', '=', NULL]])->get();
    }
    public static function get_mot_special_condition_details($id) {
        if(!empty($id)) {
            $result = DB::table('service_special_conditions as sc')
                            ->leftjoin('version_services_schedules_intervals as i' , 'sc.category_id' , '=' , 'i.id')
                            ->where([['sc.main_category_id', '=', 3], ['sc.id', '=', $id]])->first();
            return $result; 
        }
    }
    public static function add_request_quotes_special_conditions($request, $start_date, $expiry_date, $all_services, $category_id) {
        if(!empty($request)) {
            return Service_special_condition::updateOrCreate(['id'=>$request->special_condition_id] , [
                'users_id' => Auth::user()->id , 
                'workshop_id' => Auth::user()->id  , 
                'main_category_id' => 25, 
                'category_id' => $category_id , 
                'all_services' => $all_services, 
                'makers' => $request->car_makers,
                'models' => $request->car_models,
                'versions' => $request->car_version,
                'operation_type' => $request->operation_type , 
                'start_hour' => $request->start_time , 
                'end_hour' => $request->end_time , 
                'discount_type' => $request->discount_type , 
                'amount_percentage' => $request->amount , 
                'max_appointement' => $request->maximum_appointment , 
                'start_date' => $start_date , 
                'select_type' => $request->repeat_type , 
                'expiry_date' => $expiry_date , 
                'status' => 'A'
            ]);
        }
    }

    public static function get_request_quotes_special_condition($user_id) {
        if(!empty($user_id)) {
            $result = DB::table('service_special_conditions as sc')
                        ->leftjoin('main_category as c' , 'sc.category_id' , '=' , 'c.id')->select('sc.*' , 'c.main_cat_name' )
                        ->where([['sc.workshop_id' , $user_id], ['sc.main_category_id', '=', 25], ['sc.deleted_at', '=', NULL]])->get();
            return $result;
        }
    }

    public static function get_request_quots_special_condition_details($user_id, $id) {
        if(!empty($id)) {
            $result = DB::table('service_special_conditions as sc')
                        ->leftjoin('main_category as c' , 'sc.category_id' , '=' , 'c.id')->select('sc.*' , 'c.main_cat_name' )
                        ->where([['sc.id' , $id], ['sc.workshop_id' , $user_id], ['sc.main_category_id', '=', 25], ['sc.deleted_at', '=', NULL]])->first();
            return $result;
        }
    }
}
