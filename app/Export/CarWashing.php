<?php
namespace App\Export;
use App\Products_group;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use sHelper;
use Auth;
use DB;
  
class CarWashing implements FromCollection , WithHeadings{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $car_size_arr = [1=>'Small' , 2=>'Average' , 3=> 'Big'];
    public function collection(){
		    $data_arr = [];
			/*Get category */
            //$car_service_details = DB::table('workshop_service_payments')->where([['workshop_id' , '=' , Auth::user()->id] , ['category_type' , '=' , 1]])->first();
            $car_washing_category = sHelper::get_subcategory(1);
            $car_size = $this->car_size_arr;
            if($car_washing_category->count() > 0){
                foreach($car_washing_category as $list){
                    foreach($car_size as $key => $size_value){
                        $service_average_time =  sHelper::get_car_wash_service_time($key , $list->id);
                        $service_price = sHelper::car_wash_price_max_appointment(Auth::user()->id , $list->id , $key);
                        $hourly_rate = $service_price['hourly_rate'];
                        $max_appointment = $service_price['max_appointment'];
                        $price =  sHelper::calculate_service_price($service_average_time ,$hourly_rate);
                        
                        $service_list = [];
                        $service_list['id'] = $list->id;
                        $service_list['category_name'] = $list->category_name;
                        $service_list['description'] = $list->description;
                        $service_list['size_value'] = $size_value;
                        $service_list['car_size'] = $key;

                        $service_list['hourly_rate'] = ''; 
						if(!empty($hourly_rate)){
						   $service_list['hourly_rate'] = $hourly_rate; 
						}
                        $service_list['price'] = ''; 
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
            }  
			return collect($data_arr);
   }
		 
   public function headings(): array
    {
        return [
            'id',
            'Service',
            'Description',
            'Car Size',
            'Size Status',
            'Hourly Rate',
            'Price',
			'Maximum Appointment',
        ];
    }
}