<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class RimDetails extends Model{
    
	protected  $table = "rim_details";
    protected $fillable = ['id','rim_id','rim_id_id','rim_alcar','type', 'rim_details_response',
	 'our_product_name','our_description', 'bar_code' , 'for_pair','color','number_of_holes' ,'meta_key_title', 'meta_key_words', 'seller_price', 'products_quantity'
	 ,'minimum_quantity','out_of_stock_status' ,'tax', 'tax_value', 'substract_stock','unit' , 'products_status', 'assemble_status','our_assemble_time', 'deleted_at', 'created_at', 'updated_at', 'unique_id'
	];
	
	/*
	Type = 1 for tyre24 rim
	Type = 2 for custom rim
	*/
	
		
    public static function save_rim_response($request , $rim_detail){
      $for_pair = NULL;
	  if(!empty($request->for_pair)){
		   $for_pair = 1;
		}
	 return RimDetails::updateOrcreate(['rim_alcar'=>$rim_detail->alcar] , 
	                                    ['our_product_name'=>$request->our_product_name, 
										 'our_description'=>$request->our_product_name, 
										 'bar_code'=>$request->bar_code,
										 'for_pair'=>$for_pair,
										 'color'=>$request->color,
										 'number_of_holes'=>$request->number_of_holes,  
										 'meta_key_title'=>$request->meta_title, 
										 'meta_key_words'=>$request->meta_keywords,
										 'seller_price'=>$request->seller_price,
										 'products_quantity'=>$request->quantity,
										 'minimum_quantity'=>$request->stock_warning,
										 'tax'=>$request->tax,
										 'tax_value'=>$request->tax_value,
										 'substract_stock'=>$request->substract_stock,
										 'products_status'=>$request->products_status,
										 'bar_code'=>$request->bar_code,
										 'our_assemble_time'=>$request->our_assemble_time,
										] 
									   );
	  
	}
	
	
	public static function get_rim_detail($rim_alcar_id){
	   return RimDetails::where([['rim_alcar' , '=' , $rim_alcar_id]])
	                    ->whereDate('updated_at', Carbon::today())
						->first(); 
	}
	
	public static function save_rim_detail($rim_alcar_id , $response){
	    return RimDetails::updateOrCreate(['rim_alcar'=>$rim_alcar_id] , 
                                                     ['rim_id_id'=>$rim_alcar_id, 
                                                      'rim_details_response'=>$response
                                                     ]);
	}		
		
	
}
