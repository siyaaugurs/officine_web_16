<?php
namespace App\Import;
use Maatwebsite\Excel\Concerns\ToModel;
use App\ProductsNew_details;
use App\Product_inventory;
use App\ProductsNew;
use sHelper;
use Auth;
  
class InventorySpareProduct implements ToModel {

    public $row_count = 0;

    public function model(array $row){

        $created_at = $updated_at = date('Y-m-d h:i:s');
        $current_number_of_row = ++$this->row_count;

        if($current_number_of_row > 1){ 

            if(!empty($row[1]) || !empty($row[2])) { 
                
                if(!empty($row[1])) {
                    $where_clause = [['product_new_product_name', '=', $row[1]], ['products_sale_price', '=', $row[3]], ['users_id', '=', Auth::user()->id]];
                } else {
                    $where_clause = [['product_new_details_bar_code', '=', $row[2]], ['products_sale_price', '=', $row[3]], ['users_id', '=', Auth::user()->id]];
                }
                $check_record = Product_inventory::where($where_clause)->first();
                if(!empty($check_record)) {
                    $result = Product_inventory::where('id' , '=' , $check_record->id)->update(['quantity' => $row[4], 'stock_warning' => $row[5]]);
                    if($result) {
                        $product_detail = ProductsNew::find($check_record->product_new_id);
                        if($product_detail->type == 1) {
                            $product_where = [['products_kromeda_id', '=', $product_detail->products_name]];
                        } else {
                            $product_where = [['product_id', '=', $product_detail->id]];
                        }
                        $product_new_detail = ProductsNew_details::where($product_where)->first();
                        if($row[4] > $check_record->quantity) {
                            $product_new_detail->products_quantiuty = ($row[4] - $check_record->quantity) + $product_new_detail->products_quantiuty;
                            $product_new_detail->save();
                        }
                        if($row[4] < $check_record->quantity) {
                            $product_new_detail->products_quantiuty = $product_new_detail->products_quantiuty - ($check_record->quantity - $row[4]);
                            $product_new_detail->save();
                        }
                    }
                } else {
                    $product_arr = [
                        'users_id' => Auth::user()->id,
                        'status' => 'A',
                        'products_sale_price' => $row[3],
                        'quantity' => $row[4],
                        'stock_warning' => $row[5],
                    ];
                    if(!empty($row[1])) {
                        $product_record = ProductsNew::where([['products_name', '=', $row[1]], ['deleted_at', '=', NULL]])->first();
                        if($product_record->type == 1) {
                            $product_where = [['products_kromeda_id', '=', $product_record->products_name]];
                        } else {
                            $product_where = [['product_id', '=', $product_record->id]];
                        }
                        $product_details_record = ProductsNew_details::where($product_where)->first();
                        $product_arr['product_new_product_name'] = $row[1];
                        $product_arr['product_new_id'] = $product_record->id;
                        $product_arr['product_new_details_bar_code'] = $product_details_record->bar_code ? $product_details_record->bar_code : NULL;
                    } else {
                        $product_details_record = ProductsNew_details::where([['bar_code', '=', $row[2]], ['deleted_at', '=', NULL]])->first();
                        if($product_details_record->type == 1) {
                            $product_where = [['products_name', '=', $product_details_record->products_kromeda_id]];
                        } else {
                            $product_where = [['id', '=', $product_details_record->product_id]];
                        }
                        $product_record = ProductsNew::where($product_where)->first();
                        $product_arr['product_new_product_name'] = $product_record->products_name;
                        $product_arr['product_new_id'] = $product_record->id;
                        $product_arr['product_new_details_bar_code'] = $row[2];
                    }
                    $res = Product_inventory::create($product_arr);
                    if($res) {
                        $product_details_record->products_quantiuty = $product_details_record->products_quantiuty + $row[4];
                        $product_details_record->save();
                    }
                }

            }
        }
    }

}