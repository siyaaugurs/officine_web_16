<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use App\Servicequotes as Service_quotes;
use DB;
use App\Users_category;

class ServiceQuotes extends Controller{
    
	public function pages($page = 'home'){
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
		if($page == "home"){
		   // $data['all_service_quotes'] = Service_quotes::get_service_quotes(); 
			//echo "<pre>";
			//print_r($data['all_service_quotes']);exit; 
		 }
		
		if(!view()->exists('service_quotes.'.$page))
			return view("404")->with($data);
		else  
		return view("service_quotes.".$page)->with($data);  
	}
	
	public function get_action(Request $request , $action){
		/*Service Quotes list script start*/
		if($action == "service_for_quotes_list"){
			$columns = array(0 =>'id', 1 =>'service_booking_date', 2=>'f_name', 3=>'status' , 4=> 'id');
			$service_quotes_list = Service_quotes::get_service_quotes($request , $columns); 		
			$totalData = Service_quotes::where([['type' , '=' , $request->for_type]])->count();
			$totalFiltered = $totalData; 
			$data = [];
			foreach ($service_quotes_list as $quotes_list){
				if($quotes_list->status == 'D'){
					$status = '<span class="badge badge-success">Dispatch</span>';
				}
				if($quotes_list->status == 'P'){
					$status = '<span class="badge badge-warning">Pendign</span>';
				}
				$show =  ' <a class="btn btn-warning service_quotes_details" href="javascript::void();" data-quotesid="'.$quotes_list->id.'"><i class="fa fa-info-circle"></i></a>';
				$nestedData['sNo'] = $quotes_list->id;
                $nestedData['requested_date'] = \sHelper::convert_italian_time($quotes_list->created_at);
                $nestedData['customer_name'] = $quotes_list->customer_fname." ".$quotes_list->customer_lname;
                $nestedData['status'] = $status;
                $nestedData['action'] = "{$show}";
                $data[] = $nestedData;
			}
			$json_data = array(
				"draw"            => intval($request->input('draw')),  
				"recordsTotal"    => intval($totalData),  
				"recordsFiltered" => intval($totalFiltered), 
				"data"            => $data   
				);
			echo json_encode($json_data); 	
		}
		/*End*/

        if($action == "change_status"){
			if(!empty($request->service_quote_id)){
				$result = Service_quotes::where([['id' , '=' , $request->service_quote_id]])->update(['status' => $request->status ]);
				if($result){
					echo 1;exit;
				} else {
					echo 2;exit;
				}	 
			} else {
				echo '<div class="notice notice-danger"><strong>Wrong , </strong> Something went wrong please try again  .</div>';exit; 
			}  
		}
		/*Get Quote details script start*/
		if($action == "get_quote_details"){
		    if(!empty($request->service_quote_id)){
				$service_quotes_details = Service_quotes::service_quotes_detail($request->service_quote_id); 
				if($service_quotes_details != NULL){
					$service_quotes_details->image = NULL;
					$service_quotes_details->service_booking_date = \sHelper::convert_italian_time($service_quotes_details->created_at);
					$image = DB::table('site_images')->where([['servicequotes_id' , '=' , $service_quotes_details->id]])->get();
					if($image->count() > 0){
						$service_quotes_details->image = $image;
					}
				}
				return view("service_quotes.component.quotes_details")->with(['quotes_details'=>$service_quotes_details]);    
			  }
			 else{
			    echo '<div class="notice notice-danger"><strong>Wrong , </strong> Something went wrong please try again  .</div>';exit;
			  } 
		
		  }
		  /*end*/
	}
}
