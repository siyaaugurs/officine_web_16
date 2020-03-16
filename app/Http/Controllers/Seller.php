<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use App\Model\Kromeda;
use kRomedaHelper;
use sHelper;
use App\Products;
use App\Tyre_pfu;
use App\Tyre_workshop_pfu;
use DB;
use Session;
use App\Users_category;
use App\Http\Controllers\API\Tyre24Controller as Api_tyre24;

class Seller extends Controller
{
	
	public function __construct(){
       $this->middleware('is_seller');
    }
    
	
    public function index($page = "home"){
       $data['users_profile'] = \App\User::find(Auth::user()->id);
	   $data['title'] = "Officine Top Seller - ".$page;
	    if(Auth::check()) {
		   $data['users_profile'] = \App\User::find(Auth::user()->id);
		   $data['alloted_roles'] = DB::table('roles')->where([['users_id' , '=' , Auth::user()->id]])->get(); 
		   $data['selected_category'] = Users_category::get_users_category(Auth::user()->id);
		   $data['spare_group_selected_services'] = Users_category::spare_group_services(Auth::user()->id);
		  }
       return view("seller.".$page)->with($data);
    }
	
	
    public function  page(Request $request, $page , $p1 = NULL){
		$data['page'] = $page;
        $data['users_profile'] = \App\User::find(Auth::user()->id);
        $data['title'] = "Officine Top Seller - ".$page;
        $data['cars__makers_category'] = \App\Maker::all();
		 if(Auth::check()) {
		  $data['users_profile'] = \App\User::find(Auth::user()->id);
		  $data['alloted_roles'] = DB::table('roles')->where([['users_id' , '=' , Auth::user()->id]])->get(); 
		  $data['selected_category'] = Users_category::get_users_category(Auth::user()->id);
			$data['spare_group_selected_services'] = Users_category::spare_group_services(Auth::user()->id);
		  }
        if($page == "products"){
            //$data['cars__makers_category'] = kRomedaHelper::get_makers();
        }
        if($page == "dashboard"){
            $seller_id = Auth::user()->id;
			$data['product_order_list'] = \App\Products_order::get_seller_orders($seller_id);
        }
        if($page == "product_list") {
            $data['products'] = \App\Product_inventory::where([['deleted_at', '=', NULL], ['users_id', '=', Auth::user()->id]])->paginate(10);
        }
        /*if($page == "product_description") {
            if(!empty($p1)) {
                $data['products'] = \App\Products_order_description::get_product_description($p1);
            }
        }*/
        if($page == "order_details") {
            if(!empty($p1)) {
                $seller_id = Auth::user()->id;
                $data['order_deatil'] = \App\Products_order::get_order_detail(decrypt($p1));
                $data['order'] = \App\Products_order_description::get_product_description(decrypt($p1));
                $data['company_name'] = sHelper::get_seller_owner($seller_id);
                $data['shipping_address_details'] = \App\Address::get_address_details($data['order_deatil']->shipping_address_id);  
            }
        }
        if($page == "seller_order_list") {
           
			
        }
        if($page == "remove_inventry_product") {
            if(!empty($p1)){ 
                $products_details = \App\Product_inventory::find($p1);
                $product_details = \App\Product_inventory::where('id', '=',  $p1)->update(['deleted_at' => date('Y-m-d H:i:s')]);
                if($product_details){
                   return redirect()->back()->with(["msg"=>'<div class="notice notice-success notice"><strong> Success </strong> Record deleted successfull !!! </div>']);
                } else {
                   return redirect()->back()->with(["msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> something went wrong please try again !!! </div>']);
                } 
            } else {
                return redirect()->back()->with(["msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> something went wrong please try again !!! </div>']);   
            }
              
        }
        
        if($page == "seller_feedback") {
            $uid = Auth::user()->id;
            $data['all_feedback'] = \App\Feedback::get_seller_feedback($uid);
            // echo "<pre>";
            // print_r($data['all_feedback']);exit;
        }
       	if($page == "add_pfu") {
            $tyre_obj = new Api_tyre24;
            $data['category_type'] = $this->category_type;
            $data['category_type2'] =$this->category_type2;
            // $data['pfu_category'] = Tyre_pfu::get_tyre_pfu();
            $data['seller_pfu'] = Tyre_workshop_pfu::get_tyre_user_pfu();
        }

        if($page == "edit_inventory_product") {
            if(!empty($p1)) {
                $data['product_deatils'] = \App\Product_inventory::where([['id', '=', decrypt($p1)]])->first();
            } else {
                return redirect()->back()->with(["msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> something went wrong please try again !!! </div>']);
            }
        }
        if($page == "manage_tyre_inventory") {
            $data['tyre_inventory'] = \App\TyreInventory::where([['users_id', '=', Auth::user()->id], ['deleted_at', '=', NULL]])->paginate(20);
        }
        if($page == "remove_inventry_tyre") {
            if(!empty($p1)) {
                $tyre_details = \App\TyreInventory::where('id', '=',  $p1)->update(['deleted_at' => date('Y-m-d H:i:s')]);
                if($tyre_details){
                   return redirect()->back()->with(["msg"=>'<div class="notice notice-success notice"><strong> Success </strong> Record deleted successfull !!! </div>']);
                } else {
                   return redirect()->back()->with(["msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> something went wrong please try again !!! </div>']);
                } 
            } else {
                return redirect()->back()->with(["msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> something went wrong please try again !!! </div>']); 
            }
        }
		
        return view("seller.".$page)->with($data);
    }
    public function add_pfu_detail(Request $request){
        // echo "<pre>";
        // print_r($request->all());exit;
        /* $day_data = \App\Tyre_workshop_pfu::updateOrCreate(
                    ['workshop_id' => Auth::user()->id , 
                     'tyre_pfu_id'=>$request->category, 
                    ],
                    ['workshop_id'=>$uid,
                     'add_money'=>$request->add_money , 'tyre_pfu_id'=>$request->category , 'deleted_at'=>NULL]);
        return redirect()->back()->with(["msg"=>'PFU Added Successfully.']); */
        $result = \App\Tyre_workshop_pfu::add_pfu_detail($request);
        if($result){
            return redirect()->back()->with(["msg"=>'Record saved Successfully .']);
        }
        else{   
            return redirect()->back()->with(["wrong_msg"=>'Wrong , please try again ..']);
        } 
    }

    public function post_action(Request $request , $action) {
        if($action == "add_seller_tyre_inventory") {
            $validator = \Validator::make($request->all(), [
                'price' => 'required',
                'quantity' => 'required'
            ]);
            $bar_code = $item_number = $tyre24_id = $quantity = NULL;
            if($validator->fails()){
                return json_encode(array("error"=> $validator->errors()->getMessages(), "status"=>400));
            } else {
                if(!empty($request->ean_number) || !empty($request->item_number)) {
                    if(!empty($request->ean_number)) {
                        $where_clause = [['tyre_response->ean_number', '=', $request->ean_number], ['deleted_at', '=', NULL]];
                    } else if(!empty($request->item_number)) {
                        $where_clause = [['itemId', '=', $request->item_number], ['deleted_at', '=', NULL]];
                    }
                    $check_ean = \App\Tyre24::where($where_clause)->first();
                    $tyre_response = json_decode($check_ean->tyre_response);
                    if(!empty($check_ean)) {
                        $result = \App\TyreInventory::add_tyre_inventory($request, $check_ean, $tyre_response);
                        if($result) {
                            if($request->seller_tyre_invent_type == 1) {
                                $check_ean->quantity = $check_ean->quantity + $request->quantity;
                                $check_ean->save();
                            }
                            if($request->seller_tyre_invent_type ==2) {
                                if($request->quantity > $request->invent_quantity) {
                                    $check_ean->quantity = ($request->quantity - $request->invent_quantity) + $check_ean->quantity;
                                    $check_ean->save();
                                }
                                if($request->quantity < $request->invent_quantity) {
                                    $check_ean->quantity = $check_ean->quantity - ($request->invent_quantity - $request->quantity);
                                    $check_ean->save();
                                }
                            }
                            return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Record Saved successfully .</div>'));
                        } else {
                            return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went wrong please try again  .</div>'));
                        }
                    } else {
                        return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Note , </strong>This EAN Number is not Valid .</div>'));
                    }
                } else {
                    return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-primary"><strong>Note , </strong>Please Enter EAN Number or Item Number .</div>'));
                }
            }
        }
        if($action == "add_seller_tyre_pfu") {
            $validator = \Validator::make($request->all(), [
                'seller_price' => 'required',
                'tyre_class' => 'required',
                'description' => 'required'
            ]);
            if($validator->fails()){
                return json_encode(array("error"=> $validator->errors()->getMessages(), "status"=>400));
            }
            $result = \App\Tyre_workshop_pfu::add_pfu_detail($request);
            if($result){
                return json_encode(array('msg'=>'<div class="notice notice-success"><strong>Success , </strong> Record saved Successfully.</div>' , "status"=>200));
            } else {   
                return json_encode(array('msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> please try again .</div>' , "status"=>100));
            } 
        }
        if($action == "edit_seller_pfu_details") {
            $validator = \Validator::make($request->all(), [
                'category' => 'required',
                'price' => 'required',
                'weights_of_tyres_from' => 'required',
                'weights_of_tyres_to' => 'required'
            ]);
            if($validator->fails()){
                return json_encode(array("error"=> $validator->errors()->getMessages(), "status"=>400));
            }
            $result = \App\Tyre_workshop_pfu::add_pfu_detail($request);
            if($result){
                return json_encode(array('msg'=>'<div class="notice notice-success"><strong>Success , </strong> Record saved Successfully.</div>' , "status"=>200));
            }
            else{   
                return json_encode(array('msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> please try again .</div>' , "status"=>100));
            } 
        }
        /*if($action == "add_invent_products") {
            $validator = \Validator::make($request->all(), [
                'inventory_product' => 'required',
                'product_price' => 'required',
                'product_quantity' => 'required',
                'stock_warning' => 'required',
                'product_status' => 'required',
            ]);
            if($validator->fails()){
                return json_encode(array("error"=> $validator->errors()->getMessages(), "status"=>400));
            } else {
                $result = \App\Product_inventory::add_inventory_product($request);
                if($result) {
                    return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Product Added successfully .</div>'));
                } else {
                    return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went wrong please try again  .</div>')); 
                }
            }
        }*/
        
        if($action == "add_invent_products") {
            $validator = \Validator::make($request->all(), [
                'product_price' => 'required',
                'product_quantity' => 'required',
            ]);
            $bar_code = $item_name = $product_id = NULL;
            if($validator->fails()){
                return json_encode(array("error"=> $validator->errors()->getMessages(), "status"=>400));
            } else {
                if(!empty($request->item_name || !empty($request->ean_number))) {
                    if(!empty($request->ean_number) && empty($request->item_name)) {
                        $check_ean = \App\ProductsNew_details::where([['bar_code', '=', $request->ean_number], ['deleted_at', '=', NULL]])->first();
                        if(!empty($check_ean)) {
                            if($check_ean->type == 1) {
                                $where_clause = [['products_name', '=', $check_ean->products_kromeda_id]];
                            } else {
                                $where_clause = [['id', '=', $check_ean->product_id]];
                            }
                            $get_item_number = \App\ProductsNew::where($where_clause)->first();
                            if(!empty($get_item_number)) {
                                $item_name = $get_item_number->products_name;
                                $product_id = $get_item_number->id;
                            }
                            $bar_code = $check_ean_number->bar_code;
                            $check_ean->products_quantiuty = $check_ean->products_quantiuty + $request->product_quantity;
                            $check_ean->save();
                        } else {
                            return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Note , </strong>This EAN Number is not Valid .</div>')); 
                        }
                    } else {
                        $check_by_item_number = \App\ProductsNew::where([['products_name', '=', $request->item_name], ['deleted_at', '=', NULL]])->first();
                        if(!empty($check_by_item_number)) {
                            $check_ean_number = sHelper::get_products_details($check_by_item_number);
                            if(!empty($request->item_name) && empty($request->ean_number)) {
                                $bar_code = $check_ean_number->bar_code;
                                $item_name = $request->item_name;
                                $product_id = $check_by_item_number->id;
                            }
                            if(!empty($request->item_name) && !empty($request->ean_number)) {
                                if($request->ean_number == $check_ean_number->bar_code) {
                                    $bar_code = $request->bar_code;
                                    $item_name = $request->item_name;
                                    $product_id = $check_by_item_number->id;
                                } else {
                                    return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Note , </strong>This EAN Number is not Valid .</div>'));
                                }
                            }
                            $check_ean_number->products_quantiuty = $check_ean_number->products_quantiuty + $request->product_quantity;
                            $check_ean_number->save();
                        } else {
                            return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Note , </strong>This Item Number is not Valid .</div>'));
                        }
                    }
                    $result = \App\Product_inventory::add_inventory_product($request, $bar_code, $item_name, $product_id);
                    if($result){
                        return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Record Saved successfully .</div>'));
                    } else {
                        return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went wrong please try again  .</div>'));
                    } 
                }
            }
        }
        
        if($action == "edit_invent_product") {
            $validator = \Validator::make($request->all(), [
                'product_price' => 'required',
                'product_quantity' => 'required',
            ]);
            $bar_code = $item_name = $product_id = $quantity = $product_details_id = NULL;
            if($validator->fails()){
                return json_encode(array("error"=> $validator->errors()->getMessages(), "status"=>400));
            } else {
                if(!empty($request->item_name || !empty($request->ean_number))) {
                    if(!empty($request->ean_number) && empty($request->item_name)) {
                        $check_ean = \App\ProductsNew_details::where([['bar_code', '=', $request->ean_number], ['deleted_at', '=', NULL]])->first();
                        $quantity = $check_ean->products_quantiuty;
                        $product_details_id = $check_ean->id;
                        if(!empty($check_ean)) {
                            if($check_ean->type == 1) {
                                $where_clause = [['products_name', '=', $check_ean->products_kromeda_id]];
                            } else {
                                $where_clause = [['id', '=', $check_ean->product_id]];
                            }
                            $get_item_number = \App\ProductsNew::where($where_clause)->first();
                            if(!empty($get_item_number)) {
                                $item_name = $get_item_number->products_name;
                                $product_id = $get_item_number->id;
                            }
                            $bar_code = $check_ean_number->bar_code;
                        } else {
                            return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Note , </strong>This EAN Number is not Valid .</div>')); 
                        }
                    } else {
                        $check_by_item_number = \App\ProductsNew::where([['products_name', '=', $request->item_name], ['deleted_at', '=', NULL]])->first();
                        if(!empty($check_by_item_number)) {
                            $check_ean_number = sHelper::get_products_details($check_by_item_number);
                            $quantity = $check_ean_number->products_quantiuty;
                            $product_details_id = $check_ean_number->id;
                            if(!empty($request->item_name) && empty($request->ean_number)) {
                                $bar_code = $check_ean_number->bar_code;
                                $item_name = $request->item_name;
                                $product_id = $check_by_item_number->id;
                            }
                            if(!empty($request->item_name) && !empty($request->ean_number)) {
                                if($request->ean_number == $check_ean_number->bar_code) {
                                    $bar_code = $request->bar_code;
                                    $item_name = $request->item_name;
                                    $product_id = $check_by_item_number->id;
                                } else {
                                    return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Note , </strong>This EAN Number is not Valid .</div>'));
                                }
                            }
                        } else {
                            return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Note , </strong>This Item Number is not Valid .</div>'));
                        }
                    }
                    if($request->product_quantity > $request->inventory_quentity) {
                        $total_quantity = ($request->product_quantity - $request->inventory_quentity) + $quantity;
                    }
                    if($request->product_quantity < $request->inventory_quentity) {
                        $total_quantity = $quantity -  ($request->inventory_quentity  - $request->product_quantity) ;
                    }
                    $res = \App\ProductsNew_details::where('id' , '=' , $product_details_id)->update(['products_quantiuty'=>$total_quantity]);
                    $result = \App\Product_inventory::add_inventory_product($request, $bar_code, $item_name, $product_id);
                    if($result){
                        return json_encode(array('status'=>200 , 'msg'=>'<div class="notice notice-success"><strong>Success , </strong> Record Saved successfully .</div>'));
                    } else {
                        return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Something Went wrong please try again  .</div>'));
                    } 
                } else {
                    return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Wrong , </strong> Please Add Item Number or EAN Number  .</div>'));
                }
            }
        }
    }

    public function get_action(Request $request , $action) {
        if($action == "get_tyre_invent_search") {
            if(!empty($request->item_number) && !empty($request->ean_number)) {
                $where_clause = [['Tyre24_itemId', '=', $request->item_number], ['Tyre24_ean_number', '=', $request->ean_number], ['users_id', '=', Auth::user()->id], ['deleted_at', '=', NULL]];
            } else if (!empty($request->item_number)) {
                $where_clause = [['Tyre24_itemId', '=', $request->item_number], ['users_id', '=', Auth::user()->id], ['deleted_at', '=', NULL]];
            } else if(!empty($request->ean_number)) {
                $where_clause = [['Tyre24_itemId', '=', $request->item_number], ['users_id', '=', Auth::user()->id], ['deleted_at', '=', NULL]];
            }
            $response = \App\TyreInventory::where($where_clause)->get();
            if($response->count() > 0) {
                return view('seller.component.tyre_inventory_list')->with(['tyre_inventory'=>$response]);
            } else {
                echo '<div class="notice notice-danger"><strong>Wrong </strong> No record found !!! </div>';exit; 
            }
            
        }
        if($action == "get_tyre_inventory_details") {
            if(!empty($request->tyre_id)) {
                $response = \App\TyreInventory::where([['id', '=', $request->tyre_id]])->first();
                if($response) {
                    return json_encode(['status'=>200 , "response"=>$response]);
                } else {
                    return json_encode(['status'=>100 , "msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> Something Went Wrong please try again   !!! </div>']);
                }
            }
        }
        if($action == "change_saller_tyre_status") {
            if(!empty($request->tyre_id)) {
                $result = \App\TyreInventory::find($request->tyre_id);
                if($result != NULL){
                    $result->status = $request->status;
                    if($result->save()){
                        echo '<div class="notice notice-success"><strong> Success </strong> Change successfully   !!! </div>';exit; 
                    } else{
                        echo '<div class="notice notice-danger"><strong> Success </strong> Change successfully   !!! </div>';exit;   
                    } 
                }
                else{
                    echo '<div class="notice notice-danger"><strong> Wrong </strong> Something Went wrong please try again  !!! </div>';exit; 
                }
            }
        }
        if($action == "get_product_invent_search") {
            if(!empty($request->item_number) && empty($request->ean_number)) {
                $where_clause = [['product_new_product_name', '=', $request->item_number], ['users_id', '=', Auth::user()->id], ['deleted_at', '=', NULL]];
            } 
            if(!empty($request->ean_number) && empty($request->item_number)) {
                $where_clause = [['product_new_details_bar_code', '=', $request->ean_number], ['users_id', '=', Auth::user()->id], ['deleted_at', '=', NULL]];
            }
            if(!empty($request->item_number) && !empty($request->ean_number)) {
                $where_clause = [['product_new_details_bar_code', '=', $request->ean_number], ['product_new_product_name',
                 '=', $request->item_number], ['users_id', '=', Auth::user()->id], ['deleted_at', '=', NULL]];
            }
            $response = \App\Product_inventory::where($where_clause)->get();
            if($response->count() > 0) {
                return view('seller.component.product_list')->with(['products'=>$response]);
            } else {
                echo '<div class="notice notice-danger"><strong>Wrong </strong> No record found !!! </div>';exit; 
            }
        }
        if($action == "get_pfu_details") {
            if(!empty($request->pfu_id)) {
                $result = \App\Tyre_workshop_pfu::get_seller_pfu_details($request->pfu_id);
                if($result){
                    return json_encode(['status'=>200 , "response"=>$result]);
                } else {
                    return json_encode(['status'=>100 , "msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> Something Went Wrong please try again   !!! </div>']);
                }
            }
        }
        if($action == "delete_pfu") {
            if(!empty($request->pfu_id)) {
                $result = \App\Tyre_workshop_pfu::where('id', '=',  $request->pfu_id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
                if($result){
                    return json_encode(['status'=>200 , "msg"=>'<div class="notice notice-success notice"><strong> Success </strong> Record Deleted Successfully.  !!! </div>']);
                } else {
                    return json_encode(['status'=>100 , "msg"=>'<div class="notice notice-danger notice"><strong> Wrong </strong> Something Went Wrong please try again   !!! </div>']);
                }
            }
        }
        if($action == "change_saller_product_status"){
            if(!empty($request->productId)) {
                $result = \App\Product_inventory::find($request->productId);
                if($result != NULL){
                    $result->status = $request->status;
                    if($result->save()){
                        echo '<div class="notice notice-success"><strong> Success </strong> Change successfully   !!! </div>';exit; 
                    }else{
                        echo '<div class="notice notice-danger"><strong> Success </strong> Change successfully   !!! </div>';exit;   
                    } 
                }
                else{
                    echo '<div class="notice notice-danger"><strong> Wrong </strong> Something Went wrong please try again  !!! </div>';exit; 
                }
            }
        }
        /*if($action == "get_seller_order_lists") {
            if(request()->ajax()) {
                $seller_id = Auth::user()->id;
                $orders = \App\Products_order::get_seller_orders($seller_id);
                if($orders->count() > 0){
					foreach($orders as $key => $order){
						$order->sNo = $key+1;
					}
				}
				$start_date = (!empty($_GET["start_date"])) ? ($_GET["start_date"]) : ('');
				$end_date = (!empty($_GET["end_date"])) ? ($_GET["end_date"]) : ('');
				$status = (!empty($_GET["status"])) ? ($_GET["status"]) : ('');
				
				if($start_date && $end_date){
					$start_date = date('Y-m-d', strtotime($start_date));
         			$end_date = date('Y-m-d', strtotime($end_date));
					$orders = \App\Products_order::leftjoin('users' , 'products_orders.users_id' , '=' , 'users.id')->where([["products_orders.created_at",">=" , $start_date],["products_orders.created_at", "<=", $end_date], ["products_orders.seller_id", "=", $seller_id]])->select('products_orders.*','users.f_name')->get();
					if($orders->count() > 0){
						foreach($orders as $key => $order){
							$order->sNo = $key+1;
						}
					}
				}
				else if($status){
					$orders = \App\Products_order::leftjoin('users' , 'products_orders.users_id' , '=' , 'users.id')->where([["products_orders.status","=" , $status], ["products_orders.seller_id", "=", $seller_id]])->select('products_orders.*','users.f_name')->get();
					if($orders->count() > 0){
						foreach($orders as $key => $order){
							$order->sNo = $key+1;
						}
					}
				}
				return  datatables()->of($orders)
				->addColumn('action', function($orders){
					$button = '<a href="#" data-toggle="tooltip" data-placement="top" title="View Order" data-orderid="'.$orders->id.'" class="btn btn-info get_seller_order_details"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;&nbsp;';
					$button .= '<a href="'.url("seller/product_description/$orders->id").'" data-toggle="tooltip" data-placement="top" title="View Order Description" class="btn btn-warning"><i class="fa fa-eye"></i></a>';
					return $button;
				})
				->rawColumns(['action'])
				->make(true);
			}
        }*/
        if($action == "get_seller_order_lists") {
            if(request()->ajax()) {
                $seller_id = Auth::user()->id;
                $orders = \App\Products_order::get_seller_orders($seller_id);
                if($orders->count() > 0){
					foreach($orders as $key => $order){
						$order->sNo = $key+1;
					}
                }
                
				$start_date = (!empty($_GET["start_date"])) ? ($_GET["start_date"]) : ('');
				$end_date = (!empty($_GET["end_date"])) ? ($_GET["end_date"]) : ('');
				$status = (!empty($_GET["status"])) ? ($_GET["status"]) : ('');
				
				if($start_date && $end_date){
					$start_date = date('Y-m-d', strtotime($start_date));
         			$end_date = date('Y-m-d', strtotime($end_date));
					$orders = \App\Products_order::leftjoin('users' , 'products_orders.users_id' , '=' , 'users.id')->where([["products_orders.order_date",">=" , $start_date],["products_orders.order_date", "<=", $end_date], ["products_orders.seller_id", "=", $seller_id]])->select('products_orders.*','users.f_name')->get();
					if($orders->count() > 0){
						foreach($orders as $key => $order){
							$order->sNo = $key+1;
						}
					}
				}
				else if($status){
					$orders = \App\Products_order::leftjoin('users' , 'products_orders.users_id' , '=' , 'users.id')->where([["products_orders.status","=" , $status], ["products_orders.seller_id", "=", $seller_id]])->select('products_orders.*','users.f_name')->get();
					if($orders->count() > 0){
						foreach($orders as $key => $order){
							$order->sNo = $key+1;
						}
					}
				}
				return  datatables()->of($orders)
				->addColumn('action', function($orders){
                    $encrypt_id =  encrypt($orders->id);
                    $button = '
                        <div style="min-width: 120px;float:right">
                            <div class="btn-group"><a href="#" data-toggle="tooltip" title="View Order" data-orderid="'.$orders->id.'" class="btn btn-primary get_seller_order_details"><i class="fa fa-eye"></i></a>
                                <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle"><span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li style="display:block;padding:5px 20px;clear:both;line-height:1.42857;">
                                        <a href="'.url("seller/order_details/$encrypt_id").'" style="color:#333;" target="_blank"><i class="fa fa-eye"></i> Order Deatils</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    ';
					/* $button .= '<a href="'.url("seller/product_description/$orders->id").'" data-toggle="tooltip" data-placement="top" title="View Order Description"  class="btn btn-warning"><i class="fa fa-eye"></i></a>'; */
					return $button;
				})
				->make(true);
			}
        }
         if($action == "view_seller_order") {
            if(!empty($request->orderId)) {
                $order = \App\Products_order::get_order_detail($request->orderId);
                if($order != NULL) {
                    $shipping_address_details = \App\Address::get_address_details($order->shipping_address_id);
                    ?>
                        <table class="table">
                            <tr>
                                <th>Customer Name</th>
                                <td><?php echo $order->f_name; ?></td>
                            </tr>
                            <tr>
                                <th>Order At</th>
                                <td><?php echo $order->order_date; ?></td>
                            </tr>
                            <tr>
                                <th>Transaction Id</th>
                                <td><?php echo $order->transaction_id; ?></td>
                            </tr>
                            <tr>
                                <th>Number of Products</th>
                                <td><?php echo $order->no_of_products; ?></td>
                            </tr>
                            <tr>
                                <th>Total Price</th>
                                <td>&euro;&nbsp;<?php echo $order->total_price; ?></td>
                            </tr>
                            <tr>
                                <th>Total Discount</th>
                                <td>&euro;&nbsp;<?php echo $order->total_discount; ?></td>
                            </tr>
                            
                            <tr>
                                <th>Sipping Address</th>
                                <td>
                                    <?php 
                                        if(!empty($shipping_address_details->address_1)) {
                                            echo $shipping_address_details->address_1 ;
                                            ?>,&nbsp;<?php
                                        }
                                        if(!empty($shipping_address_details->address_2)) {
                                            echo $shipping_address_details->address_2 ;
                                            ?>,&nbsp;<?php
                                        } 
                                        if(!empty($shipping_address_details->address_3)) {
                                            echo $shipping_address_details->address_3 ;
                                            ?>,&nbsp;<?php
                                        } 
                                        if(!empty($shipping_address_details->address_3)) {
                                            echo $shipping_address_details->address_3 ;
                                            ?>,&nbsp;<?php
                                        } 
                                        if(!empty($shipping_address_details->landmark)) {
                                            echo $shipping_address_details->landmark ;
                                            ?>,&nbsp;<?php
                                        } 
                                        if(!empty($shipping_address_details->zip_code)) {
                                            echo $shipping_address_details->zip_code ;
                                            ?>&nbsp;.<?php
                                        } 
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Address Type</th>
                                <td><?php echo $order->address_type; ?></td>
                            </tr>
                            <tr>
                                <th>Tracking Id</th>
                                <td><?php echo $order->tracking_id; ?></td>
                            </tr>
                            <tr>
                                <th>Courier Id</th>
                                <td><?php echo $order->courier_id; ?></td>
                            </tr>
                            <tr>
                                <th>Payment Status</th>
                                <td>
                                    <?php
                                        if(!empty($order->payment_status)){
                                            if($order->payment_status == "P"){
                                                ?>
                                                    <span class="badge badge-danger">Pending</span>
                                                <?php
                                            } else if($order->payment_status == "C") {
                                                ?>
                                                    <span class="badge badge-success">Confirm</span>
                                                <?php
                                            }
                                        }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Order Status</th>
                                <td>	
                                    <div style="min-width: 120px">
                                        <div class="btn-group">
                                            <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle"><span class="caret"></span>
                                            <?php
                                                if($order->status == "I"){
                                                    ?>
                                                        <span id="order_status">In Process</span>
                                                    <?php
                                                } else if($order->status == "D"){
                                                    ?>
                                                        <span id="order_status">Dispatched</span>
                                                    <?php
                                                } else if($order->status == "IN") {
                                                    ?>
                                                        <span id="order_status">Intransit</span>
                                                    <?php
                                                } else if($order->status == "DE") {
                                                    ?>
                                                        <span id="order_status">Delivered</span>
                                                    <?php
                                                }
                                            ?>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                <li style="display:block;padding:5px 20px;clear:both;line-height:1.42857;">
                                                    <a href="#" style="color:#333;" class="change_order_status_seller" data-orderid="<?php echo $order->id?>" data-status="I">In Process</a>
                                                </li>
                                                <li style="display:block;padding:5px 20px;clear:both;line-height:1.42857;">
                                                    <a href="#" class="change_order_status_seller" data-orderid="<?php echo $order->id?>" style="color:#333;" data-status="D"> Dispatched</a>
                                                </li>
                                                <li style="display:block;padding:5px 20px;clear:both;line-height:1.42857;">
                                                    <a href="#" class="change_order_status_seller" data-orderid="<?php echo $order->id?>" style="color:#333;" data-status="IN"> Intransit</a>
                                                </li>
                                                <li style="display:block;padding:5px 20px;clear:both;line-height:1.42857;">
                                                    <a href="#" class="change_order_status_seller" data-orderid="<?php echo $order->id?>" data-status="DE"style="color:#333;"> Delivered</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>	
                                </td>
                            </tr>
                        </table>
                    <?php
                } else {
                    return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Something Went Wrong, </strong>Please try again .</div>'));
                }
            } else {
                return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Something Went Wrong, </strong>Please try again .</div>'));
            }
        }
        if($action == "change_order_status") {
            $order_id = $request->orderId_id;
            $status = $request->status;
            if(!empty($order_id && $status)) {
                $arr = ['status' => $status];
                \App\Products_order::where('id', $order_id)->update($arr);
                return \App\Products_order::where('id', $order_id)->first();
            }
        }
        if($action == "change_seller_order_status") {
            $order_id = $request->orderId_id;
			$status = $request->status;
			if( $request->status == 'P') {
				$arr = ['status' => 'I'];
				return \App\Products_order::where('id', $order_id)->update($arr);
			}
        }
         if($action == "view_seller_product_description") {
            if(!empty($request->orderId)) {
                $product_desc = \App\Products_order_description::get_product_description($request->orderId);
                $i=0;
                if($product_desc != NULL) {
                    ?>
                    <div class="card" id="user_data_body" style="overflow:auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>S No.</th>
                                    <th>Customer Name</th>
                                    <th>Product Order Id</th>
                                    <th>Product Name</th>
                                    <th>Product Descriptions</th>
                                    <th>Coupan Id</th>
                                    <th>Price</th>
                                    <th>Discount</th>
                                    <th>Total Price</th>
                                    <th>Created At</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if(!empty($product_desc)) {
                                    foreach($product_desc as $product_desc){
                                        $i++;
                                           ?>
                                        <tr>
                                           <td><?php echo $i;?></td>
                                           <td><?php echo $product_desc->f_name; ?></td>
                                           <td><?php echo $product_desc->products_orders_id; ?></td>
                                           <td><?php echo $product_desc->product_name; ?></td>
                                           <td><?php echo $product_desc->product_description; ?></td>
                                           <td><?php echo $product_desc->coupons_id; ?></td>
                                           <td><?php echo $product_desc->price; ?></td>
                                           <td><?php echo $product_desc->discount; ?></td>
                                           <td><?php echo $product_desc->total_price; ?></td>
                                           <td><?php echo $product_desc->created_at; ?></td>
                                           <td>
                                               <?php 
                                                   if($product_desc->status == "P") {
                                                       ?>
                                                           <span style="background:red;color:white">Pending</span>
                                                       <?php
                                                   } else if($product_desc->status == "A") {
                                                       ?>
                                                           <span style="background:green;color:white">Approved</span>
                                                       <?php
                                                   }
                                               ?>
                                           </td>
                                       </tr>
                                    <?php
                                    }
                                } else {
                                    ?>
                                        <tr><td colspan="7">No Product Avilable..</td></tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
                } else {
                    return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Something Went Wrong, </strong>Please try again .</div>'));
                }
            } else {
                return json_encode(array('status'=>100 , 'msg'=>'<div class="notice notice-danger"><strong>Something Went Wrong, </strong>Please try again .</div>'));
            }
        }
        if($action == "view_feedback") {
            $f_id = $request->feedbackId;
            return $feedback_details = \App\Feedback::get_feedback_by_id($f_id);
            
        }
        
        
        if($action == "search_invent_productsBy_group") {
            if(!empty($request->groupid)){
                $products = \App\Product_inventory::get_products_by_group_item($request->groupid);
                return view('seller.component.product_list')->with(['products'=>$products]);
                
            } else {
                echo '<div class="notice notice-danger"><strong> Wrong </strong>something went wrong please try again  !!! </div>';exit;
            }
        }
    }
	
}
