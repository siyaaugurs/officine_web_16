<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class ProductsNew_details extends Model{
   
    protected  $table = "products_new_details";
    protected $fillable = [
      'id', 'product_id',  'products_kromeda_id', 'products_name1',  'our_products_description' , 'type' , 'bar_code' , 'for_pair','meta_key_title','meta_key_words','seller_price','products_quantiuty','minimum_quantity', 'out_of_stock_status','tax','tax_value','substract_stock','unit' , 'products_status','assemble_status','assemble_time','created_at','deleted_at' , 'updated_at'];


    public static function save_custom_products_details($product_details , $request , $for_pair){
	  return ProductsNew_details::updateOrcreate(['product_id'=>$product_details->id] , 
            ['product_id'=>$product_details->id,
            'products_name1'=>$request->product_name1,
             'our_products_description'=>$request->products_description, 
             'bar_code'=>$request->bar_code, 
             'for_pair'=>$for_pair, 
			        'type'=>2,
             'meta_key_title'=>$request->meta_title, 
             'meta_key_words'=>$request->meta_keywords,
             'seller_price'=>$request->seller_price, 
             'products_quantiuty'=>$request->quantity, 
             'minimum_quantity'=>$request->stock_warning, 
             'tax'=>$request->tax, 
             'tax_value'=>$request->tax_value, 
             'substract_stock'=>$request->substract_stock, 
             'unit'=>$request->unit, 
             'products_status'=>$request->products_status, 
             'assemble_status'=>$request->products_assemble_status, 
             'assemble_time'=>$request->assemble_time, 
            ]
        );
    } 

    public static function save_products_details($kromeda_product_id , $request , $for_pair){
      return ProductsNew_details::updateOrcreate(['products_kromeda_id'=>$kromeda_product_id] , 
            ['product_id'=>$request->products_id,
            'products_kromeda_id'=>$kromeda_product_id, 
            'products_name1'=>$request->our_product_name,
             'our_products_description'=>$request->products_description, 
             'bar_code'=>$request->bar_code, 
             'for_pair'=>$for_pair, 
			 'type'=>1,
             'meta_key_title'=>$request->meta_title, 
             'meta_key_words'=>$request->meta_keywords,
             'seller_price'=>$request->seller_price, 
             'products_quantiuty'=>$request->quantity, 
             'minimum_quantity'=>$request->stock_warning, 
             'tax'=>$request->tax, 
             'tax_value'=>$request->tax_value, 
             'substract_stock'=>$request->substract_stock, 
             'unit'=>$request->unit, 
             'products_status'=>$request->products_status, 
             'assemble_status'=>$request->products_assemble_status, 
             'assemble_time'=>$request->assemble_kromeda_time, 
            ]
        );
    }

   
}
