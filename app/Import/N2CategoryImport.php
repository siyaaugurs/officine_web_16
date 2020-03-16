<?php
namespace App\Import;
use App\User;
use App\Products_group;
use App\ProductsGroupsItem;
use App\CategoriesDetails;
use Maatwebsite\Excel\Concerns\ToModel;
  
class N2CategoryImport implements ToModel{

    public $row_count = 0;  

   	public function model(array $row){
        $current_number_of_row = ++$this->row_count;
        if($current_number_of_row > 1){
            $created_at = $updated_at = date('Y-m-d h:i:s');
            $insert_arr = [
                'group_name' => $row[3],
                'description' => $row[5],
                'priority' => $row[6],
                'status' => $row[8],
                'language' => $row[7],
            ];
            if(!empty($row[1])) {
                $group_details = Products_group::find($row[1]);
                if($group_details != NULL) {
                    $insert_arr['products_groups_group_id'] = $group_details->group_id;
                    if(!empty($row[0])) {
                        $check_n2_category = Products_group::where([['id', '=', $row[0]], ['parent_id', '!=', 0]])->first();
                        if($check_n2_category == NULL) {
                            $insert_arr['parent_id'] = $row[1];
                            $insert_arr['type'] = 2;
                            $insert_arr['group_unique_id'] = uniqid();
                        }
                    } else {
                        $insert_arr['parent_id'] = $row[1];
                        $insert_arr['type'] = 2;
                        $insert_arr['group_unique_id'] = uniqid();
                    }
                }
                $category_response = Products_group::updateOrCreate(['id' => $row[0]] , $insert_arr);
                if($category_response) {
                    $cat_details_arr = [
                        'description' => $row[5],
                        'priority' => $row[6],
                        'status' => $row[8],
                        'n2_group_id' => $category_response->group_id,
                        'n2_id' => $category_response->id,
                    ];
                    if($category_response->type == 1) {
                        $where_clause = [['n2_group_id' , '=' , $category_response->group_id]];
                    } else {
                        $where_clause = [['n2_id' , '=' , $category_response->id]];
                    }
                    $category_detail_response = \App\CategoriesDetails::updateOrCreate($where_clause , $cat_details_arr);
                    echo "<pre>";
                    echo "ok";
                }
            }
        }
    }
}