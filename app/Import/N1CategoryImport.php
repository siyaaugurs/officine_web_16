<?php
namespace App\Import;
use App\User;
use App\Products_group;
use App\CategoriesDetails;
use Maatwebsite\Excel\Concerns\ToModel;
use DB;
  
class N1CategoryImport implements ToModel{
    public $row_count = 0;
   
    public function model(array $row){
        $current_number_of_row = ++$this->row_count;
        if($current_number_of_row > 1){
            $created_at = $updated_at = date('Y-m-d h:i:s');
            $insert_arr = [
                'group_name' => $row[2],
                'description' => $row[4],
                'priority' => $row[5],
                'status' => $row[6],
                'language' => $row[7],
            ];
            if(!empty($row[0])) {
                $check_n1_category = Products_group::find($row[0]);
                if($check_n1_category == NULL) {
                    $insert_arr['parent_id'] = 0;
                    $insert_arr['type'] = 2;
                }
            } else {
                $insert_arr['parent_id'] = 0;
                $insert_arr['type'] = 2;
            }
            $category_response = Products_group::updateOrCreate(['id' => $row[0] ] , $insert_arr);
            if($category_response) {
                $cat_details_arr = [
                    'description' => $row[4],
                    'priority' => $row[5],
                    'status' => $row[6],
                    'n1_n2_group_id' => $category_response->group_id,
                    'n1_n2_id' => $category_response->id,
                ];
                if($category_response->type == 1) {
                    $where_clause = [['n1_n2_group_id' , '=' , $category_response->group_id]];
                } else {
                    $where_clause = [['n1_n2_id' , '=' , $category_response->id]];
                }
                $category_detail_response = \App\CategoriesDetails::updateOrCreate($where_clause , $cat_details_arr);
            }
        }
    }
}