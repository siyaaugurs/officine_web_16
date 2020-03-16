<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class MainCategory extends Model
{
    public  $table = "main_category";
    protected $fillable = ['id', 'main_cat_name', 'type', 'priority', 'description', 'type_status', 'status' , 'deleted_at','created_at', 'updated_at']; 

    public static function add_spare_group($request) {
        return MainCategory::updateOrcreate(
            ['id'=>$request->spare_group_id],
            [   'main_cat_name'=>$request->spare_group_name ,
                'description'=>$request->description ,                  
                'type_status'=>'spare_groups',
                'type' => 1,
                'priority'=>0,
                'status' =>'A' 
            ]
        );
    }

    public static function get_spare_group_record($category_name) {
            return MainCategory::where([['main_cat_name' , '=' , $category_name], ['type', '=', 1]])->first();
    }

   
    public static function get_all_spares_group() {
        return MainCategory::where([ ['type', '=', 1] /*,['private' , '=' , 0] */, ['deleted_at' , '=' , NULL]])->paginate(10);
    }
    
    
    public static function get_all_valid_spares_group($private_key = NULL) {
        if($private_key != NULL){
            return MainCategory::where([ ['type', '=', 1],/* ['status','=','A'] , */ ['private' , '=' , 0] , ['deleted_at' , '=' , NULL]])->get();
        }
        return MainCategory::where([ ['type', '=', 1],/* ['status','=','A'] , */ ['deleted_at' , '=' , NULL]])->get();
    }
    
    
    public static function get_spares_details($spare_id) {
        if(!empty($spare_id)) {
            return MainCategory::where([['id' , '=' , $spare_id], ['type', '=', 1]])->first();
        }
    }
    public static function get_assemble_details($cat_id) {
        return DB::table('main_category as a') 
                        ->where([['a.id' , '=' , $cat_id], ['type', '=', 1]])
                        ->select('a.main_cat_name')
                        ->first(); 
    }

}
