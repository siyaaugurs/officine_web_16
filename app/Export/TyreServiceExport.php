<?php
namespace App\Export;
use App\Products_group;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use sHelper;
use Auth;
use DB;
  
class TyreServiceExport implements FromCollection , WithHeadings{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(){
        $data_arr = [];
        /*Get category */
        $service_details = \App\Category::get_workshop_tyre24_category(23);
        if($service_details->count() > 0){
            foreach($service_details as $list) {
                $workshop_tyre24_details = sHelper::get_workshop_tyre24_service_detail(Auth::user()->id , $list->id);
                if($workshop_tyre24_details != NULL){
                    $hourly_rate = $workshop_tyre24_details->hourly_rate;
                    /* $pfu = $workshop_tyre24_details->PFU;
                    $delivery_days = $workshop_tyre24_details->delivery_days; */
                    $price =  sHelper::calculate_service_price($list->time, $hourly_rate);
                    $max_appointment = $workshop_tyre24_details->max_appointment;
                    $delivery_days=$workshop_tyre24_details->delivery_days;
                    $PFU=$workshop_tyre24_details->PFU;
                }

                $service_list = [];

                $service_list['id'] = $list->id;
                $service_list['category_name'] = $list->category_name;
                $service_list['description'] = $list->description;
                $service_list['range_from'] = $list->range_from;
                $service_list['range_to'] = $list->range_to;
                $service_list['time'] = $list->time;
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
                /* $service_list['pfu'] = ''; 
                if(!empty($pfu)){
                    $service_list['pfu'] = $pfu; 
                }
                $service_list['delivery_days'] = ''; 
                if(!empty($delivery_days)){
                    $service_list['delivery_days'] = $delivery_days; 
                } */

                $data_arr[] = $service_list;   
            }
        }
        return collect($data_arr);
   }
		 
   public function headings(): array
    {
        return [
            'Id',
            'Service',
            'Description',
            'Range From',
            'Range To',
            'Time',
            'Hourly Rate',
            'Price',
			'Maximum Appointment',
			/* 'PFU',
			'Delivery Days', */
        ];
    }
}