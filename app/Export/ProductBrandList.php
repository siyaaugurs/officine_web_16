<?php
namespace App\Export;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use sHelper;
use DB;
  
class ProductBrandList implements FromCollection , WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(){
        $data_arr = [];
        $get_brand_details = \App\BrandLogo::where([['deleted_at', '=', NULL]])->orderBy('brand_type' , 'ASC')->get();
        if($get_brand_details->count() > 0) {
            foreach($get_brand_details as $brand){
                $new_brand_details = [];
                $new_brand_details['id'] = $brand->id;
                $new_brand_details['brand_type'] = $brand->brand_type;
                $new_brand_details['brand_name'] = $brand->brand_name;
                $new_brand_details['images_avilability'] = '0';
                if(!empty($brand->image)) {
                    $new_brand_details['images_avilability'] = '1';
                }
                $new_brand_details['brand_images'] = '';
                $data_arr[] =  $new_brand_details; 
            }
        }
        return collect($data_arr);
    }

    public function headings(): array {
        return [
            'ID',
            'Brand Type',
            'Brand Name',
            'Image Avilability',
            'Brand Images',
		];
    }
}