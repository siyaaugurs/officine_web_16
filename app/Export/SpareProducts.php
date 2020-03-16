<?php
namespace App\Export;
use App\Products_group;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use sHelper;
use DB;
  
class SpareProducts implements FromCollection , WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(){
         $lang = sHelper::get_set_language(app()->getLocale());
         $data_arr = [];
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
            'Products Name',
            'Kromeda Price',
            'Kromeda Description',
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