<?php
namespace App\Import;
use App\User;
use App\Products_group;
use App\ProductsGroupsItem;
use App\CategoriesDetails;
use Maatwebsite\Excel\Concerns\ToModel;
use Auth;
  
class N3CategoryImport implements ToModel{

    public $row_count = 0;
   
    public function model(array $row){

        $current_number_of_row = ++$this->row_count;
        if($current_number_of_row > 1){
            $created_at = $updated_at = date('Y-m-d h:i:s');
            
            $n2_group_id = NULL;
            $n1_group_id = NULL;

            if(!empty($row[1])) {
                $n2_category_details = Products_group::where([['id', '=', $row[1]], ['parent_id', '!=', 0]])->first();
                if($n2_category_details != NULL) {

                    $n2_group_id = $n2_category_details->group_id;
                    
                    $n1_category_details = Products_group::where([['id', '=', $n2_category_details->parent_id], ['parent_id', '=', 0]])->first();
                    if($n1_category_details != NULL) {
                        $n1_group_id = $n1_category_details->group_id;
                    }
                    $insert_arr = [
                        'users_id' => Auth::user()->id,
                        'products_groups_id' => $row[1],
                        'n1_kromeda_group_id' => $n1_group_id,
                        'n2_kromeda_group_id' => $n1_group_id,
                        'item' => $row[3],
                        'front_rear' => $row[4],
                        'left_right' => $row[5],
                        'our_description' => $row[8],
                        'our_description' => $row[8],
                        'language' => $row[7] 
                    ];
                    if(!empty($row[0])){
                        $check_n3_category = ProductsGroupsItem::find($row[0]);
                        if($check_n3_category == NULL) {
                            $insert_arr['unique_id'] = uniqid().$row[7];
                            $insert_arr['type'] = 2;
                            $insert_arr['status'] = 'A';
                        }
                    } else {
                        $insert_arr['unique_id'] = uniqid().$row[7];
                        $insert_arr['type'] = 2;
                        $insert_arr['status'] = 'A';
                    }
                    $category_response = ProductsGroupsItem::updateOrCreate(['id' => $row[0] ] , $insert_arr);
                    if($category_response) {
                        $cat_details_arr = [
                            'description' => $row[8],
                            'priority' => $row[9],
                            'status' => $row[10],
                            'n3_item_id' => $category_response->item_id,
                            'n3_id' => $category_response->id,
                        ];
                        if($category_response->type == 1) {
                            $where_clause = [['n3_item_id' , '=' , $category_response->item_id]];
                        } else {
                            $where_clause = [['n3_id' , '=' , $category_response->id]];
                        }
                        $category_detail_response = \App\CategoriesDetails::updateOrCreate($where_clause , $cat_details_arr);
                    }
                }
            }

        }

    }
}