<?php
namespace App\Export;
use App\Products_group;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use sHelper;
use DB;
use Auth;
  
class InventorySparePart implements FromCollection , WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(){
        $lang = sHelper::get_set_language(app()->getLocale());
        $data_arr = [];

        $spare_product =   \App\Product_inventory::where([['deleted_at', '=', NULL], ['users_id', '=', Auth::user()->id]])->get();
        if($spare_product->count() > 0){
            foreach($spare_product as $product){
                $new_product_details = [];
                $new_product_details['id'] = $product->id;
                $new_product_details['product_new_product_name'] = $product->product_new_product_name;
                $new_product_details['product_new_details_bar_code'] = $product->product_new_details_bar_code;
                $new_product_details['products_sale_price'] = $product->products_sale_price;
                $new_product_details['quantity'] = $product->quantity;
                $new_product_details['stock_warning'] = $product->stock_warning;
                $new_product_details['status'] = $product->status;

                $data_arr[] =  $new_product_details; 
            }
        }

       return collect($data_arr);
    }
    public function headings(): array {
        return [
            'ID',
            'Product Item',
            'EAN Number',
            'Price',
            'Quantity',
            'Stock Warning',
            'Status',
		];
    }
}
