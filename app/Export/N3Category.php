<?php
namespace App\Export;
use App\Products_group;
use App\ProductsGroupsItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use sHelper;
  
class N3Category implements FromCollection , WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
	public function collection(){
		$lang = sHelper::get_set_language(app()->getLocale());
	    /*Get category */
	 	$category_list = [];
	 	$all_n3 = ProductsGroupsItem::get_all_unique_n3_category($lang);
		$data_arr = [];		 
	   	if(count($all_n3) > 0){
	   		$n3_cat = $all_n3->where('deleted_at' , NULL)->where('status' , 'A');
			foreach($n3_cat as $all_n3_category){
			   	$new_cat_fields = [];
				$new_cat_fields['id'] = $all_n3_category->id;
				$new_cat_fields['products_groups_id'] = $all_n3_category->products_groups_id;
				// $new_cat_fields['n1_kromeda_group_id'] = $all_n3_category->n1_kromeda_group_id;
				// $new_cat_fields['n2_kromeda_group_id'] = $all_n3_category->n2_kromeda_group_id;
				$new_cat_fields['item_id'] = $all_n3_category->item_id;
				$new_cat_fields['item'] = $all_n3_category->item;
				$new_cat_fields['front_rear'] = $all_n3_category->front_rear;
				$new_cat_fields['left_right'] = $all_n3_category->left_right;
				$new_cat_fields['type'] = $all_n3_category->type;
				$new_cat_fields['language'] = $all_n3_category->language;
				$new_cat_fields['description'] = '';	 
				$new_cat_fields['priority'] = '';
				$new_cat_fields['status'] =''; 
				$category_details = sHelper::get_n3_categories_details($all_n3_category); 
			 	if($category_details != NULL){
					$new_cat_fields['description'] = $category_details->description;	 
					$new_cat_fields['priority'] = $category_details->priority;
					if($category_details->status != NULL) {
						$new_cat_fields['status'] = $category_details->status; 
					} else {
			 			$new_cat_fields['status'] = $all_n3_category->status; 
					}
			 	}
			  	$data_arr[] = $new_cat_fields;
			}
			return collect($data_arr);
		}
	//	die;
			/*End*/ 
   	}
		 
   public function headings(): array
    {
        return [
			'ID',
            'N2 Sub Category',
   //          'N1 kromeda group_id',
			// 'N2 kromeda group_id',
			'Item ID',
			'item',
			'Front rear',
			'Left right',
			'Type',
			'Language',
			'Description',
			'Priority',
			'Status',
        ];
    }
}