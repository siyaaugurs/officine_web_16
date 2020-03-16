<?php
namespace App\Export;
use App\Products_group;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use sHelper;
use kromedaDataHelper;
  
class Category implements FromCollection , WithHeadings{
	
	
    public function collection(){
		$lang = sHelper::get_set_language(app()->getLocale());
			$category_list = [];
			$third_category = Products_group::get_all_unique_category($lang);
		   	if(count($third_category) > 0){
		   		$third_category_list = $third_category->where('deleted_at',NULL);
				 foreach($third_category_list as $category){
					$category = kromedaDataHelper::arrange_n1_category($category);
				   	$new_cat_fields = [];
					$new_cat_fields['id'] = $category->id;
					$new_cat_fields['group_id'] = $category->group_id;
					$new_cat_fields['group_name'] = $category->group_name;
					$new_cat_fields['type'] = $category->type;
					$new_cat_fields['description'] = $category->description;
					$new_cat_fields['priority'] = $category->priority;
					$new_cat_fields['status'] = $category->status;
					$new_cat_fields['language'] = $category->language;
					$data_arr[] = $new_cat_fields;
				  } 
			return collect($data_arr);
		}
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
			'Status',
			'Language',
        ];
    }
}