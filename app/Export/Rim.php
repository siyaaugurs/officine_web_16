<?php
namespace App\Export;
use App\Products_group;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use sHelper;
use DB;
  
class Rim implements FromCollection , WithHeadings{
	
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(){
		    /*Get category */
			 $category_list = [];
			 $rim_list = DB::table('rims')->where([['deleted_at' , '=' , NULL]])->get();
			 $data_arr = [];
				 foreach($rim_list as $rim){
					 $new_cat_fields = [];
					 /*Rim response script start*/
					 $rim_response = json_decode($rim->rim_response);
					 $new_field['id'] = $rim->id;
					 $new_field['rim_alcar'] = $rim->alcar;
					 $new_field['diameter'] = $rim->dia_meter;
					 $new_field['ET'] = $rim_response->ET;
					 $new_field['size'] = $rim_response->size;
					 $new_field['type'] = $rim_response->type;
					 $new_field['ezFrom'] = $rim_response->ezFrom;
					 $new_field['connection'] = $rim_response->connection;
					 $new_field['workmanship'] = $rim_response->workmanship;
					 $new_field['manufacturer'] = $rim_response->manufacturer;
					 $new_field['typeDescription'] = $rim_response->typeDescription;
					 /*End*/ 
					 /*Get rim details script start*/
					 //$rim->alcar
					 //8215
					 //$rim_details = DB::table('rim_details')->where([['rim_alcar' , '=' , $rim->alcar]])->first();
					
                    $rim_details = DB::table('rim_details')->where([['rim_alcar' , '=' , 8215]])->first();
					 if($rim_details != NULL){
						$decode_rim_details = json_decode($rim_details->rim_details_response);
						$new_field['tyre_detail_type'] = '';
						$new_field['weight'] = '';
						$new_field['price'] = '';
						$new_field['matchcode'] = '';
						$new_field['ean_number'] = '';
						$new_field['description'] = '';
						$new_field['itemSource'] = '';
						$new_field['description1'] = '';
						$new_field['pr_description'] = '';
						$new_field['wholesalerArticleNo'] = '';
						$new_field['manufacturer_description'] = '';
						$new_field['manufacturer_item_number'] = '';
						 if($rim_details->rim_details_response != "[]"){
							 /*Rim details tyre24  response*/
							 $decode_rim_details = $decode_rim_details->items;
							 if(!empty($decode_rim_details->type)){
								if(is_string($decode_rim_details->type)){
								   $new_field['tyre_detail_type'] = $decode_rim_details->type;
								} 
							}	
							    if(!empty($decode_rim_details->weight)){
									$new_field['weight'] = $decode_rim_details->weight;
								}
								if(!empty($decode_rim_details->price)){
									$new_field['price'] = $decode_rim_details->price;
								}

								if(!empty($decode_rim_details->matchcode)){
									if(is_string($decode_rim_details->matchcode)){
									   $new_field['matchcode'] = $decode_rim_details->matchcode;
									} 
								}	
								if(!empty($decode_rim_details->ean_number)){ if(is_string($decode_rim_details->ean_number)){ $new_field['ean_number'] = $decode_rim_details->ean_number; } }
								if(!empty($decode_rim_details->description)){
									if(is_string($decode_rim_details->description)){
										$new_field['description'] = $decode_rim_details->description;
									}
								}
								if(!empty($decode_rim_details->itemSource)){
									if(is_string($decode_rim_details->itemSource)){
										$new_field['itemSource'] = $decode_rim_details->itemSource;
									}							   
								}
								if(!empty($decode_rim_details->description1)){
									if(is_string($decode_rim_details->description1)){
										$new_field['description1'] =$decode_rim_details->description1;
									}
								}
                                if(!empty($decode_rim_details->pr_description)){
									if(is_string($decode_rim_details->pr_description)){
										$new_field['pr_description'] =$decode_rim_details->pr_description;
									} 
								} 
                                if(!empty($decode_rim_details->wholesalerArticleNo)){
									if(is_string($decode_rim_details->wholesalerArticleNo)){
										$new_field['wholesalerArticleNo'] =$decode_rim_details->wholesalerArticleNo;
									}
								}
								if(!empty($decode_rim_details->manufacturer_description)){
									if(is_string($decode_rim_details->manufacturer_description)){
										$new_field['manufacturer_description'] =$decode_rim_details->manufacturer_description;
									}
								}
								if(!empty($decode_rim_details->manufacturer_item_number)){
									if(is_string($decode_rim_details->manufacturer_item_number)){
										$new_field['manufacturer_item_number'] = $decode_rim_details->manufacturer_item_number;
									}
								}
							 /*End*/
						 }
						 /*Rim custom fields script start*/
						 $new_field['number_of_holes'] = $rim_details->number_of_holes;
						 $new_field['our_product_name'] = $rim_details->our_product_name;
						 $new_field['our_description'] = $rim_details->our_description;
						 $new_field['bar_code'] = $rim_details->bar_code;
						 $new_field['for_pair'] = $rim_details->for_pair;
						 $new_field['color'] = $rim_details->color;
						 $new_field['seller_price'] = $rim_details->seller_price;
						 $new_field['products_quantity'] = $rim_details->products_quantity;
						 $new_field['minimum_quantity'] = $rim_details->minimum_quantity;
						 $new_field['tax'] = $rim_details->tax;
						 $new_field['tax_value'] = $rim_details->tax_value;
						 $new_field['meta_key_title'] = $rim_details->meta_key_title;
						 $new_field['meta_key_words'] = $rim_details->meta_key_words;
						 $new_field['our_assemble_time'] = $rim_details->our_assemble_time;
						   /*End*/
						}
				/*End*/
				$data_arr[] = $new_field;
		 } 
		//echo "<pre>";
		//print_r($data_arr);exit;
		return collect($data_arr);
   }
		 
  
   public function headings(): array
    {
        return [
		   /*Rim REsponse start*/
		    'ID',
			'Alcar',
			'Dia meter',
            'ET',
            'size',
			'type',
			'Ez From',
			'Connection',
			'Workmanship',
			'Manufacturer',
			'Type Description',
			/*Rim response end*/
			/*Rim details response start*/
			'Tyre Detail Type',
			'Weight',
			'Price',
			'Matchcode',
			'Ean Number',
			'Description',
			'Item Source',
			'Description 1',
			'Pr Description',
			'wholesalerArticleNo',
			'Manufacturer Description',
			'Manufacturer Item Number',
           /*End*/
			/*Rim Details column response*/
			'Number of Holes',
			'Our Product Name',
			'Our Description',
			'bar_code',
			'Pair',
			'Color',
			'Seller Price',
			'Products Quantity',
			'Minimum Quantity',
			'Tax',
			'Tax value',
			'Meta Key Title',
			'Meta key Word',
			'Our Assemble Time',
			/*End*/
		];
    }
}