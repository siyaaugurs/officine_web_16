<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Products_order;

class Order extends Controller{

    public function get_action(Request $request , $action){
        if($action == "get_products_order_lists"){
            $product_order_status = new Products_order;
            $columns = array(0 =>'id', 1 =>'order_date', 2=>'payment_mode', 3=>'seller_id' , 4=> 'id');
            $orders = Products_order::orders($request , $columns); 
            $totalData = Products_order::count();
            $totalFiltered = $totalData; 
            $data = [];
            foreach ($orders as $order){
                $nestedData = [];
                    $show =  ' <a class="btn btn-warning" target="_blank" href="'.url("admin/order_details/$order->id").'" data-quotesid="'.$order->id.'"><i class="fa fa-info-circle"></i></a>';
                    
                    if(array_key_exists($order->status , $product_order_status->order_status)){
                        $status = '<span class="badge badge-success">'. $product_order_status->order_status[$order->status] .'</span>';
                    }
                    else{
                        $status = '<span class="badge badge-warning">N defined </span>';
                    }
                    $nestedData['sNo'] = $order->id;
                    $nestedData['order_date'] = \sHelper::convert_italian_time($order->order_date);
                    $nestedData['customer_name'] = $order->customer_fname." ".$order->customer_lname;
                    $nestedData['workshop_seller'] = $order->workshop_company_name;
                    $nestedData['total_price'] = "&euro; ".$order->total_price;
                    $nestedData['order_status'] = $status;
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
     
    }
    
}
