<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class Servicequotes extends Model{
	  
	protected $table = "servicequotes";
	protected $fillable = ['id','users_id','workshop_id', 'category_id','text', 'type', 'service_booking_date',  'status','deleted_at' , 'created_at' , 'updated_at'];
	public $type_status = [1=>"For Admin" , 2=>'For Workshop']; 
	public $status_type = ['D'=>'Dispatch' , 'p'=>'Pending'];
    
	/*Status 
	D = 'Dispatch'
	P = 'Pending'
	*/
	
	
	public static function service_quotes_detail($service_quote_id){
		return DB::table('servicequotes as a')
		   ->join('users as u' , [['u.id' , '=' , 'a.users_id']])
		   ->leftJoin('users as w' , [['w.id' , '=' , 'a.workshop_id']])
		   ->select('a.*' , 'u.f_name as customer_fname' , 'u.l_name as customer_lname' , 'u.mobile_number as customer_mobile' , 'u.email as customer_email' , 'u.user_name as customer_username', 'w.f_name as workshop_f_name' , 'w.l_name as workshop_l_name' , 'w.company_name as workshop_company_name' , 'w.mobile_number as workshop_mobile_number' , 'w.email as workshop_email') 
		   ->where([['a.id' , '=' , $service_quote_id]])
		   ->first();

	}
	
	public static function 	get_service_quotes($request , $columns){
		$column = $request->input('order.0.column');
		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		if($request->input('order.0.column') == 0 || $request->input('order.0.column') == 1 || $request->input('order.0.column') == 3 || $request->input('order.0.column') == 4){
			$order = 'a.'.$order;
		}
		if($request->input('order.0.column') == 2){
			$order = 'u.'.$order;
		}
		//echo $order;exit;
		$dir = $request->input('order.0.dir');	
	   return DB::table('servicequotes as a')
	          ->join('users as u' , [['u.id' , '=' , 'a.users_id']])
			  ->leftJoin('users as w' , [['w.id' , '=' , 'a.workshop_id']])
			  ->select('a.*' , 'u.f_name as customer_fname' , 'u.l_name as customer_lname' , 'u.mobile_number as customer_mobile' , 'u.email as customer_email' , 'u.user_name as customer_username', 'w.f_name as workshop_f_name' , 'w.l_name as workshop_l_name' , 'w.company_name as workshop_company_name' , 'w.mobile_number as workshop_mobile_number' , 'w.email as workshop_email') 
			  ->offset($start)->limit($limit)->orderBy($order , $dir)
			 ->where([['a.type' , '=' , $request->for_type]]) 
			  ->get();
	}


	public static function add_service_quotes($request){	
		return Servicequotes::create(['category_id'=>$request->category_type , 'users_id'=>Auth::user()->id,
		                              'text'=>$request->text, 'type'=>$request->button_type,  'status' =>'A']);
	} 
	public static function get_service_quotes_name($service_quote_id){
		return DB::table('servicequotes')->where('id',$service_quote_id)->first();
	}

}
