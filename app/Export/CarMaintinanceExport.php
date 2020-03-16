<?php
namespace App\Export;
use App\Products_group;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use sHelper;
use Auth;
use DB;
  
class CarMaintinanceExport implements FromCollection , WithHeadings{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(){
			$data_arr = [];
			/*Get Car maintinance services for*/
			$car_maintinance_service_list = \serviceHelper::car_maintinance_for_workshop();
			/*End*/
			if($car_maintinance_service_list->count() > 0){
				foreach($car_maintinance_service_list as $list){
					 $service_list = [];
					 $service_list['id'] = $list->id;
					 $service_list['item'] = $list->item;
					 $service_list['front_rear'] = $list->front_rear;
					 $service_list['left_right'] = $list->left_right;
					 $service_list['action_description'] = $list->description;
					 $service_list['hourly_cost'] = $list->hourly_cost;
					 $service_list['max_appointment'] = $list->max_appointment;
					 $service_list['id_info'] = $list->id_info;
					 $service_list['language'] = $list->language;
					 $data_arr[] = $service_list;
				  }
			 }
			return collect($data_arr); 
   }
		 
   public function headings(): array
    {
        return [
		    'id',
            'Item',
            'Front Rear',
			'Left right',
			'Service',
			'Hourly Cost',
			'Maximum Appointment',
			'Info',
			'Language',
        ];
    }
}