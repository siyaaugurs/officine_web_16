<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use DB;

class MotN3Category extends Model{


    protected  $table = "mot_n3_category";
    protected $fillable = [ 'id', 'our_mot_services_id', 'n3_category_id' , 'deleted_at'];  

    public static function get_mot_n3_category($mot_id) {
        $result = DB::table('mot_n3_category as m')
                        ->leftjoin('products_groups_items as p' , 'p.id' , '=' , 'm.n3_category_id')->select('m.n3_category_id' , 'p.item', 'p.front_rear', 'p.left_right')
                        ->where([['m.our_mot_services_id' , $mot_id]])->get();
        return $result;
    }

    public static function get_n3_category($mot_id) {
        return MotN3Category::where('our_mot_services_id', '=', $mot_id)->get();
    }
}