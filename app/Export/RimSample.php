<?php
namespace App\Export;
use App\Products_group;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use sHelper;
use DB;
  
class RimSample implements FromCollection , WithHeadings{
	
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(){
		$data_arr = [];
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