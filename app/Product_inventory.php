<?php

namespace App;
use Auth;

use Illuminate\Database\Eloquent\Model;

class Product_inventory extends Model
{
    protected  $table = "product_inventories";
    protected $fillable = ['id', 'users_id', 'car_maker_id' , 'car_model_id','car_version_id', 'group_id','products_id','product_new_id', 'product_new_product_name', 'product_new_details_bar_code', 'products_sale_price' , 'quantity' , 'stock_warning' , 'tax','tax_value' , 'assemble_service' , 'status' , 'deleted_at' , 'created_at' , 'update_at'];

    public static function add_inventory_product($request, $bar_code, $item_name, $product_id){
        return product_inventory::updateOrCreate(
            ['id'=>$request->inventory_id] ,
            [
                'users_id' => Auth::user()->id , 
                'product_new_product_name' => $item_name , 
                'product_new_details_bar_code' => $bar_code , 
                'products_sale_price' => $request->product_price , 
                'quantity' => $request->product_quantity , 
                'stock_warning' => $request->stock_warning , 
                'status' => $request->product_status , 
                'product_new_id' => $product_id, 
            ]
        );
    }
    public static function get_products() {
        return Product_inventory::join('products', 'products.id', '=', 'product_inventories.products_id')
        ->select('product_inventories.*' , 'products.products_name')
        ->where('product_inventories.deleted_at'  ,'=', NULL)->orderBy('products_id' ,'ASC')->paginate(10, 'product_inventories.*');
    }

    public static function get_product_id($uid) {
        return product_inventory::select('id')->where('users_id'  ,'=', $uid)->get();
    }

    public static function get_details($p1) {
        return product_inventory::join('products', 'products.id', '=', 'product_inventories.products_id')->select('product_inventories.*' , 'products.products_name')->where('product_inventories.id', '=', $p1)->first();
    }
    
    public static function edit_inventory_product($request, $id) {
        return  Product_inventory::where('id' ,$id)->update([
                'users_id' => Auth::user()->id , 
                'car_maker_id' => $request->car_makers , 
                'car_model_id' => $request->car_models , 
                'car_version_id' => $request->car_version , 
                'group_id' => $request->group_item_inventory , 
                'products_id' => $request->inventory_product , 
                'products_sale_price' => $request->product_price , 
                'quantity' => $request->product_quantity , 
                'stock_warning' => $request->stock_warning , 
                'tax' => $request->tax, 
                'tax_value' => $request->tax_value, 
                'assemble_service' => $request->assemble_sp, 
                'status' => $request->product_status
        ]); 
    }

    public static function get_products_by_group_item($product_id){
 		return Product_inventory::join('products', 'products.id', '=', 'product_inventories.products_id')->select('product_inventories.*' , 'products.products_name')->where([['product_inventories.group_id' , '=' , $product_id]])->get();
	}
}
