<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Library\kromedaHelper;
use App\Library\kromedaDataHelper;
use App\ProductsNew;
use App\ProductsImage;
use Auth;
use DB;
use App\Users_category;

class KromedaDataController extends Controller{

	public function index($page = "kromeda_monitoring" , $p1 = "OE_GetPartNumber"){
        $data['cars__makers_category'] = \App\Maker::all();
		$data['title'] = "Officine Top  - ".$page;
        $data['page'] = $page;
        if (Auth::check()) {
          $data['users_profile'] = \App\User::find(Auth::user()->id);
          $data['alloted_roles'] = DB::table('roles')->where([['users_id' , '=' , Auth::user()->id]])->get();
          $data['selected_category'] = Users_category::get_users_category(Auth::user()->id);
           $data['spare_group_selected_services'] = Users_category::spare_group_services(Auth::user()->id);
        }else{
		  return redirect('login')->with(['msg'=>'<div class="notice notice-danger"><strong>Note  ,</strong> login first  !!! </div>']);
	    }
		if($page == "kromeda_monitoring"){
		   $data['api_monitoring'] =  \App\KromedaLog::select(DB::raw('count(*) as total_hits , method_name'))->groupBy('method_name')->get();
		  }
		/*Load  view script start*/
		if(!view()->exists('admin.k_monitoring.'.$page))
			return view("404")->with($data);
		else  
		return view("admin.k_monitoring.".$page)->with($data);
		/*End*/	
	}

	public function get_action(Request $request , $action){
		if($action == "get_monitoring_by_dates"){
			$data = [];
			$start_date = \sHelper::date_format_for_database($request->start_date , 2);
			$end_date = \sHelper::date_format_for_database($request->end_date , 2);
			if(empty($start_date) || empty($end_date)){
				 echo '<div class="notice notice-success"><strong>Success , </strong> Special Condition Save Successfully !!!.</div>';exit;
			  }
			else{
			   $data['api_monitoring'] =  \App\KromedaLog::select(DB::raw('count(*) as total_hits , method_name'))->whereBetween('created_at', [$start_date , $end_date])
			   ->groupBy('method_name')->get();
			  /*load view*/
			   return view("admin.component.k_monitoring")->with($data);
			  /*End*/	
			  }	 
		  }	
		if($action == "get_monitoring_status"){
			$data = [];
			if($request->status == 1 || $request->status == 2){
				if($request->status == 1)
				   $number_of_days = 7;
				if($request->status == 2)
				   $number_of_days = 1;	
				$date = \Carbon\Carbon::today()->subDays($number_of_days);  
			   }
			if($request->status == 3){
				 $date = date('Y-m-d H:i:s', strtotime('-1 hour'));
			  }
			$data['api_monitoring'] =  \App\KromedaLog::select(DB::raw('count(*) as total_hits , method_name'))->where('created_at', '>=', $date)->groupBy('method_name')->get();
			/*load view*/
			  return view("admin.component.k_monitoring")->with($data);
			/*End*/		 
		  }	  
	 }
    
	public static function add_products_image(){
	   set_time_limit(500);	
       $products = ProductsNew::all();
	   if($products->count() > 0){
		  foreach($products as $product){
		    $get_picture_url = kromedaHelper::get_picture_url($product->CodiceListino , $product->CodiceArticolo);
		   	if(!empty($get_picture_url) || $get_picture_url != NULL){
			  $image_url_response =  ProductsImage::add_products_kromeda_image_url_2($product  , $get_picture_url); 
			}
		   } 
		 }
	}
	
	public static function add_group(Request $request){
	    $makers_name = kromedaHelper::get_makers();
		if(count($makers_name) > 0){
		     foreach($makers_name as $makers){
			    $get_models = kromedaHelper::get_models($makers->idMarca);
				if(count($get_models) > 0){
					foreach($get_models as $model){
					  $get_versions = kromedaHelper::get_versions($model->idModello , $model->ModelloAnno);
					  if(count($get_versions) > 0){
						  foreach($get_versions as $version){
							 $get_groups = kromedaHelper::get_groups($version->idVeicolo); 
							 $model_number = $model->idModello."/".$model->ModelloAnno;
							 $add_group_response = kromedaDataHelper::add_groups($get_groups , $makers->idMarca , $model_number, $version->idVeicolo ,  "ENG"); 
							 }
						}
					  else{
						 echo "Version is not Available";exit;
						}	
					 }
				  }
				else{
			       echo "Models is not Available";exit;
				  }    
			   } 
		  }
		else{
		    echo "Makers is not Available";exit;
		  }  
	}
	
}
