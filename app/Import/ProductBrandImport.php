<?php
namespace App\Import;
use Maatwebsite\Excel\Concerns\ToModel;
use sHelper;
use DB;
use Auth;
use App\BrandLogo;

class ProductBrandImport implements ToModel{

    public $row_count = 0;
    public $directory  = 'storage/brand_logo_image/';

    public function model(array $row){
        $created_at = $updated_at = date('Y-m-d h:i:s');
        $current_number_of_row = ++$this->row_count;
		if($current_number_of_row > 1){
            if(!empty($row[1]) && !empty($row[2])) {
                $brand_slug = $row[1].sHelper::slug($row[2]);
                $brand_response = BrandLogo::where([['unique_id', '=', $brand_slug]])->first();
                if($brand_response == NULL){
                    $brand_response = BrandLogo::create(['brand_type' => $row[1], 'brand_name'=>$row[2] , 'unique_id'=>$brand_slug]); 
                }
                if(!empty($row[4])) {
                    $get_extension = explode('.', $row[4]);
                    $ext = end($get_extension);
                    $content = file_get_contents($row[4]);
                    if(!empty($content)){
                        $image_name = md5(microtime().uniqid().rand(9 , 9999)).".".$ext;
                        file_put_contents($this->directory. '/'.$image_name, $content);
                        $image_url = url("storage/brand_logo_image/$image_name");
                        $brand_details = \App\BrandLogo::where([['id','=',$brand_response->id]])->update(['image'=>$image_name , 'image_url'=>$image_url]);
                    }                   
                }
            }

        }
    }
}
