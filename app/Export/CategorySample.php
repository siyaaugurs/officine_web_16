<?php
namespace App\Export;
use App\Products_group;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use sHelper;
  
class CategorySample implements FromCollection , WithHeadings
{
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
            'ID',
            'Kromeda Group ID',
            'Category Name',
            'Type',
            'Description',
			'Priority',
			'Language',
			'Status',
        ];
    }
}