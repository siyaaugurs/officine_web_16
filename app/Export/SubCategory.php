<?php
namespace App\Export;
use App\Products_group;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use sHelper;
use DB;
  
class SubCategory implements FromCollection , WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(){
		$lang = sHelper::get_set_language(app()->getLocale());
		/*Get category */
		$category_list = [];
		$all_n2 = Products_group::get_all_unique_sub_category($lang);
		if($all_n2->count() > 0){
			$category_list = $all_n2->where('deleted_at' , NULL)->where('status' , 'A');
		}
		$data_arr = [];
		if(count($category_list) > 0){
			foreach($category_list as $category){
				$new_cat_fields = [];
				$new_cat_fields['id'] = $category->id;
				$new_cat_fields['parent_id'] = $category->parent_id;  
				$new_cat_fields['group_id'] = $category->group_id;
				$new_cat_fields['group_name'] = $category->group_name;
				$new_cat_fields['type'] = $category->type;
				/* $new_cat_fields['description'] = $category->description;
				$new_cat_fields['priority'] = $category->priority; */
				$new_cat_fields['description'] = '';
				$new_cat_fields['priority'] = '';
				$new_cat_fields['language'] = $category->language;
				// $new_cat_fields['status'] = $category->status;
				$new_cat_fields['status'] = '';
				$get_category_details = sHelper::get_n2_categories_details($category);
				if($get_category_details != NULL) {
					$new_cat_fields['description'] = $get_category_details->description;
					$new_cat_fields['priority'] = $get_category_details->priority;
					if($get_category_details->status != NULL) {
						$new_cat_fields['status'] = $get_category_details->status;
					} else {
						$new_cat_fields['status'] = $category->status;
					}
				}
				$data_arr[] = $new_cat_fields;
			} 
			return collect($data_arr);
		}
   	}
		 
   	public function headings(): array
    {
        return [
            'ID',
			'Parent Category id',
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