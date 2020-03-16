<?php
namespace App\Export;
use App\Products_group;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use sHelper;
use Auth;
use DB;
  
class AssembleService implements FromCollection , WithHeadings{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(){
            $data_arr = [];
            $service_details = \App\Users_category::spare_services_categories(Auth::user()->id);
            if($service_details->count() > 0){
                foreach($service_details as $list) {
                    $service_list = [];
                    $max_appointment = $list->max_appointment;
                    $hourly_rate = $list->hourly_rate;
                    $assemble_service_details = \App\WorkshopServicesPayments::get_assemble_service_details(Auth::user()->id);
                    if(empty($max_appointment)) {
                        $max_appointment = $assemble_service_details->maximum_appointment;
                    }
                    if(empty($hourly_rate)) {
                        $hourly_rate = $assemble_service_details->hourly_rate;
                    }

                    $service_list['categories_id'] = $list->categories_id;
                    $service_list['main_cat_name'] = $list->main_cat_name;
                    $service_list['description'] = $list->description;
                    $service_list['hourly_rate'] = $hourly_rate;
                    $service_list['max_appointment'] = $max_appointment;

                    $data_arr[] = $service_list;
                }
            }
			return collect($data_arr);
   }
		 
   public function headings(): array
    {
        return [
            'Id',
            'Service Group',
            'Description',
            'Hourly Rate',
			'Maximum Appointment',
        ];
    }
}