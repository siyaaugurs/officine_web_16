<?php
namespace App\Export;
use App\Products_group;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use sHelper;
use DB;
use kromedaDataHelper;
  
class CustomSpareProducts implements FromCollection , WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(){
        $lang = sHelper::get_set_language(app()->getLocale());
		$data_arr = [];
        $custom_spare_products = DB::table('products_new')->where([['type' , '=' , 2] , ['deleted_at' ,'=' , NULL]])->get();
	    if($custom_spare_products->count() > 0){
            foreach($custom_spare_products as $product){
                $product = kromedaDataHelper::arrange_spare_product($product);
                $product_image = \App\ProductsImage::where([['products_id', '=', $product->id]])->get();
                $new_product_details = [];
                if($product->deleted_at == NULL){
                    $new_product_details['id'] = $product->id;
                    $new_product_details['category_id'] = $product->products_groups_id;
                    $new_product_details['products_groups_items_id'] = $product->products_groups_items_id;
                    $new_product_details['brand'] = $product->listino;
                    $new_product_details['products_name'] = $product->products_name;
                    $new_product_details['price'] = $product->price;
                    $new_product_details['kromeda_description'] = $product->kromeda_description;
                    $new_product_details['our_products_description'] = $product->our_products_description;
                    $new_product_details['for_pair'] = $product->for_pair;
                    $new_product_details['bar_code'] = $product->bar_code;
                    $new_product_details['meta_key_title'] = $product->meta_key_title;
                    $new_product_details['meta_key_words'] = $product->meta_key_words;
                    $new_product_details['seller_price'] = $product->seller_price;
                    $new_product_details['products_quantiuty'] = $product->products_quantiuty;
                    $new_product_details['minimum_quantity'] = $product->minimum_quantity;
                    $new_product_details['tax'] = $product->tax;
                    $new_product_details['tax_value'] = $product->tax_value;
                    $new_product_details['unit'] = $product->unit;
                    $new_product_details['products_status'] = $product->products_status;
                    $new_product_details['assemble_time'] = $product->assemble_time;
                    $new_product_details['products_name1'] = $product->products_name1;
                    $new_product_details['assemble_status'] = $product->assemble_status;
                    $new_product_details['product_status'] = "0";
                    if($product_image->count() > 0) {
                        $new_product_details['product_status'] = "1";
                    }
                    $new_product_details['product_images'] = "";
                    $data_arr[] =  $new_product_details; 
                }
            }
        }
        return collect($data_arr);
    }

    public function headings(): array {
        return [
            'ID',
            'Category ID (N2)',
            'Category Item ID (N3)',
            'Brand',
            'Item',
            'Kromeda Price',
            'Kromeda Product Name',
            'Our Products Description',
            'Pair Status',
            'Bar code',
            'meta_key_title',
            'meta_key_words',
            'seller_price',
            'Quantity ',
            'Stock Warning',
            'tax',
            'tax_value',
            'unit',
            'Status',
			'assemble_time',
            'Product Name',
            'Assemble Status',
			'Image Avilability',
			'Product Images',
		];
    }
}