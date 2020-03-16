<?php
namespace App\Export;
use App\Products_group;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use sHelper;
use DB;
  
class SpareProductsList implements FromCollection , WithHeadings{

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(){
          $lang = sHelper::get_set_language(app()->getLocale());
          $data_arr = [];
         /*Get All Spare Products*/
          $spare_product =   \App\ProductsNew::get_unique_products(1);
          /*Get Custom Products Script Start*/
          $custom_spare_products = DB::table('products_new')->where([['type' , '=' , 2]])->get();
          /*End*/
          $all_spare_parts =  $spare_product->merge($custom_spare_products);
        /*End*/ 
          if($all_spare_parts->count() > 0){
              foreach($all_spare_parts as $product){
                  $new_product_details = [];
                  $new_product_details['id'] = $product->id;
                  $new_product_details['category_id'] = $product->products_groups_id;
                  $new_product_details['products_groups_items_id'] = $product->products_groups_items_id;
                  $new_product_details['brand'] = $product->listino;
                  $new_product_details['products_name'] = $product->products_name;
                  $new_product_details['price'] = $product->price;
                  $new_product_details['kromeda_description'] = $product->kromeda_description;
                  $new_product_details['type'] = $product->type;
                  /*Define Blank key */
                  $new_product_details['our_products_description'] = '';
                  $new_product_details['for_pair'] = '';
                  $new_product_details['bar_code'] = '';
                  $new_product_details['meta_key_title'] = '';
                  $new_product_details['meta_key_words'] = '';
                  $new_product_details['seller_price'] = '';
                  $new_product_details['products_quantiuty'] = '';
                  $new_product_details['minimum_quantity'] = '';
                  $new_product_details['tax'] = '';
                  $new_product_details['tax_value'] = '';
                  $new_product_details['unit'] = '';
                  $new_product_details['products_status'] = '';
                  $new_product_details['assemble_time'] = ''; 
                  /*End*/
                  /*Get Product details script start*/
                  $get_product_detail = sHelper::get_products_details($product);
                  /*End*/
                  if($get_product_detail != NULL){
                            $new_product_details['our_products_description'] = $get_product_detail->our_products_description;
                            $new_product_details['for_pair'] = $get_product_detail->for_pair;
                            $new_product_details['bar_code'] = $get_product_detail->bar_code;
                            $new_product_details['meta_key_title'] = $get_product_detail->meta_key_title;
                            $new_product_details['meta_key_words'] = $get_product_detail->meta_key_words;
                            $new_product_details['seller_price'] = $get_product_detail->seller_price;
                            $new_product_details['products_quantiuty'] = $get_product_detail->products_quantiuty;
                            $new_product_details['minimum_quantity'] = $get_product_detail->minimum_quantity;
                            $new_product_details['tax'] = $get_product_detail->tax;
                            $new_product_details['tax_value'] = $get_product_detail->tax_value;
                            $new_product_details['unit'] = $get_product_detail->unit;
                            $new_product_details['products_status'] = $get_product_detail->products_status;
                            $new_product_details['assemble_time'] = $get_product_detail->assemble_time;
                        }   
                $data_arr[] =  $new_product_details;         
            }   
          }
        /*Get category */
	   return collect($data_arr);
		 
   }
		 
   public function headings(): array
    {
        return [
            'ID',
            'Category ID',
            'Category Item ID',
            'Brand',
            'Item',
            'Kromeda Price',
            'Kromeda Product Name',
            'Type',
            'Our Products Description',
            'Pair Status',
            'Bar code',
            'meta_key_title',
            'meta_key_words',
            'seller_price',
            'products_quantiuty',
            'minimum_quantity',
            'tax',
            'tax_value',
            'unit',
            'Status',
		     	  'assemble_time',
		];
    }
}