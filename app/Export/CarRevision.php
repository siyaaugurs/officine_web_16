<?php
namespace App\Export;
use App\Products_group;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use sHelper;
use Auth;
use DB;
  
class CarRevision implements FromCollection , WithHeadings{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(){
		    $data_arr = [];
            /*Get category */
            $category_list = \App\Category::car_revision_service(Auth::user()->id);
            if($category_list->count() > 0){
                foreach($category_list as $list) {
                    $service_details = sHelper::get_car_revision_service_detail(Auth::user()->id , $list->id);
                    if($service_details != NULL){
                        $price = $service_details->price;
                        $max_appointment = $service_details->max_appointment;
                    }

                    $service_list = [];

                    $service_list['id'] = $list->id;
                    $service_list['category_name'] = ''; 
                    if(!empty($list->category_name)){
                        $service_list['category_name'] = $list->category_name; 
                    }
                    $service_list['price'] = '0'; 
                    if(!empty($price)){
                        $service_list['price'] = $price; 
                    }
                    $service_list['max_appointment'] = ''; 
                    if(!empty($max_appointment)){
                        $service_list['max_appointment'] = $max_appointment; 
                    }

                    $data_arr[] = $service_list;
                }
            }
            return collect($data_arr);
   }
		 
   public function headings(): array
    {
        return [
            'Id',
            'Service Name',
            'Service Price',
			'Maximum Appointment',
        ];
    }
}