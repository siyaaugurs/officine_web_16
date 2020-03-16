<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Products extends Model{
    
	  protected  $table = "products";
	  protected $fillable = [
        'id', 'users_id',  'car_makers_name' , 'models_name' , 'car_version_id' , 'type' , 'type_status' , 'category_id'  , 'category_type', 'kromeda_products_id' , 'products_name' ,'kromeda_description', 'products_description','front_rear' ,'left_right' , 'CodiceOE' ,'meta_key_title', 'meta_key_words' , 'price' , 'seller_price' , 'products_quantiuty' , 'minimum_quantity' , 'out_of_stock_status' ,  'tax' , 'tax_value' , 'products_json' , 'products_status' , 'assemble_status' ,'created_at' ,'deleted_at' , 'updated_at' , 'language'];
	
	public static function products_edit($request){
	    return Products::updateOrCreate([['id' , '=' , $request->products_id ]] , 
		                                ['products_name'=>$request->products_name , 
										 'category_id'=>$request->category ,
										 'kromeda_description'=>$request->kromeda_description , 
										 'products_description'=>$request->products_description , 
										 'meta_key_title'=>$request->meta_title , 
										 'meta_key_words'=>$request->meta_keywords , 
										 'price'=>$request->kromeda_price , 
										 'seller_price'=>$request->seller_price , 
										 'tax'=>$request->tax , 
										 'tax_value'=>$request->tax_value , 
										 'minimum_quantity'=>$request->stock_warning , 
										 'products_status'=>$request->products_status , 
										 'substract_stock'=>$request->substract_stock ,
										 'unit'=>$request->unit ,
										 'assemble_status'=>$request->products_assemble_status
										] 
										);
	}
	
	public static function add_products_by_kromeda_on_real_time($group_details , $products_details , $lang){
		$price = (int) $products_details->item_description->Prezzo;
		$price_admin = number_format($price ,2);
		
	   return Products::updateOrCreate([
	                            'car_makers_name'=>$group_details->car_makers ,
	                            'models_name'=>$group_details->car_model , 
								'car_version_id'=>$group_details->car_version , 
								'category_id'=>$group_details->id ,  
								'kromeda_products_id'=>$products_details->idVoce ,
								'language'=>$lang
							   ] , 
	                            ['car_makers_name'=>$group_details->car_makers ,
	                            'models_name'=>$group_details->car_model, 
								'car_version_id'=>$group_details->car_version , 
								'type'=>1 , 'type_status'=>'kromeda' , 
								'category_id'=>$group_details->id ,  
	                            'products_name'=>$products_details->Voce,
								'kromeda_description'=>$products_details->item_description->Descrizione, 
								'front_rear'=> $products_details->ap,
	                            'left_right'=> $products_details->ds,
	                            'CodiceListino'=>$products_details->item_description->CodiceListino,
								'CodiceOE'=>$products_details->item_description->CodiceOE,
								'tipo'=>$products_details->item_description->Tipo,
								'listino'=>$products_details->item_description->Listino,
								'descrizione'=>$products_details->item_description->Descrizione,
								'cs'=>$products_details->item_description->CS, 
								'n'=>$products_details->item_description->N, 
								'v'=>$products_details->item_description->V ,
	                            'price'=>$price_admin,
	                            'kromeda_products_id'=>$products_details->idVoce,
	                            'products_json'=>json_encode($products_details) , 
								'products_status'=>'P' , 
								'language'=>$lang
								]);
								
	}
	
	
	public static function add_products_by_kromeda($request , $description , $lang ){
	   $price = (int) $description['item_description']['products_description']->Prezzo;
	   $price_admin = number_format($price ,2);
	   
	   return Products::updateOrCreate(['car_makers_name'=>$request->car_makers ,
	                                    'models_name'=>$request->car_models , 
										'car_version_id'=>$request->car_version ,
										'category_id'=>$request->group_item ,
										'kromeda_products_id'=>$description['item_description']['idVoce'] 
										] , 
	                                   ['car_makers_name'=>$request->car_makers , 'models_name'=>$request->car_models , 'car_version_id'=>$request->car_version , 'type'=>1 , 'type_status'=>'kromeda' ,  'category_id'=>$request->group_item ,  
	   'products_name'=>$description['item_description']['Voce'] ,
	   'kromeda_description'=>$description['item_description']['products_description']->Descrizione, 'front_rear'=> $description['item_description']['ap'],
	    'left_right'=> $description['item_description']['ds'],
	    'CodiceListino'=>$description['item_description']['products_description']->CodiceListino,
		'CodiceOE'=>$description['item_description']['products_description']->CodiceOE,
	    'price'=>$price_admin,
	   'kromeda_products_id'=>$description['item_description']['idVoce'] ,
	   'products_json'=>json_encode($description) , 'products_status'=>'P' , 
	   'language'=>$lang  
	   ]);
	}
	
	public static function get_products_by_group($category_id){
	   return Products::where([['category_id' , '=' , $category_id]])->get();
	}
	
   public static function get_products_by_group_item($maker , $model , $version , $category_id){
	   return Products::where([['category_id' , '=' , $category_id]])->get();
	   //return Products::where([['car_makers_name' , '=' , $maker] , ['models_name' , '=' , $model] , ['car_version_id' , '=' , $version] , ['category_id' , '=' , $category_id]])->get();
	}

	public static function get_version_products($car_version , $group_id){
		return Products::where([['type' , '=' ,1] , ['car_version_id' , '=' , $car_version] , ['category_id' , '=' , $group_id] , ['products_status' , '=' , 'A'] ])->get();
	}

  public static function get_admin_products($request){
		return Products::where([['car_makers_name' , '=' , $request->maker],
		                        ['models_name' , '=' , $request->model] ,
								['type' , '=' ,1] ,
								['car_version_id' , '=' , $request->version_id] ,
								['category_id' , '=' , $request->group_id] ,
							   ])->get();
	} 
    
	public static function get_assemble_products(){
	  return Products::where('assemble_status' , '=' , 'Y')->get();
	}

	public static function get_products($products_id = NULL){
	  if(!empty($products_id)){
		  return Products::where('id' , '=' , $products_id)->first();
	  }
	  return Products::all();
	}
	
	public static function get_products_list($products_id = NULL){
	  if(!empty($products_id)){
		  return Products::where('id' , '=' , $products_id)->first();
	  }
	  return Products::orderBy('products_name' , 'ASC')->paginate(15);
	}
	
	public static function get_inventory_products($product_id = NULL , $lang = NULL){
		if($product_id != NULL){
		  return Products::where([['category_id' , '=' , $product_id] , ['language' , '=' , $lang]])->get();
		}
		return Products::where('deleted_at'  ,'=', NULL)->orderBy('products_name' ,'ASC')->get();
	}
	
	public static function get_product_quantity($id) {
		if($id != NULL){
			return Products::select('products_quantiuty')->where('id' , '=' , $id)->get();
		}
		return Products::where('deleted_at'  ,'=', NULL)->get();
	}
	
	public static function update_product_quantity($product_quantity, $p_id, $p_quantity) {
		return  Products::where('id' ,$p_id)->update([ 
			'products_quantiuty' => $product_quantity + $p_quantity, 
		]); 
	}
	
	public static function update_increase_product($quantity,$p_quantity,$p_id) {
		return  Products::where('id' ,$p_id)->update([ 
			'products_quantiuty' => $quantity + $p_quantity, 
		]);
	}
	
	public static function update_decrease_product($quantity,$p_quantity,$p_id) {
		return  Products::where('id' ,$p_id)->update([ 
			'products_quantiuty' => $p_quantity - $quantity, 
		]);
	}
	
	/*Get products CodiceOE id script start*/
	public static function get_products_coe_id($CodiceOE_id){
	   return Products::where([['CodiceOE' , '=' , $CodiceOE_id]])->get();
	}
	/*End*/
}
