<?php

namespace App;
use Auth;
use Illuminate\Database\Eloquent\Model;
use DB;

class Spare_category_item extends Model
{
    public  $table = "spare_category_items";
    protected $fillable = ['id', 'users_id', 'main_category_id', 'version_id', 'products_groups_id',  'products_groups_group_id', 'status','created_at', 'updated_at']; 
    
    public static function save_in_spare_groups($n2_category_id , $main_category_id){
       return Spare_category_item::create(['users_id'=>Auth::user()->id, 'products_groups_id'=>$n2_category_id , 'main_category_id'=>$main_category_id, 'status' => 'A']);
    }
    public static function update_in_spare_groups($n2_category_id , $main_category_id){
       return Spare_category_item::where([['products_groups_id' , '=' , $n2_category_id]])->update(['main_category_id'=>$main_category_id]);
    }
    public static function get_service_group($n2_category_id) {
        // return Spare_category_item::where([['products_groups_id', '=', $n2_category_id]])->get();
        return DB::table('spare_category_items as a')
                ->leftjoin('main_category as b' , 'b.id','=','a.main_category_id')
                ->where('a.products_groups_id', $n2_category_id)
                ->select('a.*', 'b.main_cat_name as main_category_name')
                ->first(); 
    }
    public static function get_added_services_group($main_cat_id) {
        if(!empty($main_cat_id)) {
            return Spare_category_item::where([['main_category_id', '=', $main_cat_id]])->get();
        }
    }

    public static function save_selected_service_group($request) {
       $flag = FALSE;
        foreach($request->records as $record){
            Spare_category_item::updateOrcreate(
                ['main_category_id'=>$request->main_category_id , 'products_groups_group_id'=>$record['group_id'],
                 'version_id'=>$record['version_id']],
                ['users_id'=>Auth::user()->id,
                'main_category_id'=>$request->main_category_id ,                  
                'version_id'=>$record['version_id'] ,                  
                'products_groups_group_id'=>$record['group_id'] , 
                'status'=>'A' , 
                ]
            );	 							  
        $flag = TRUE; 
        }
      return $flag;
    }
    
     public static function get_selected_groups($version_id = NULL) {
	  if(!empty($version_id)){
		   return  Spare_category_item::where([['version_id' , '=' ,$version_id]])->get();
		}	
        return Spare_category_item::where([['deleted_at' , '=' , NULL]])->get();
       // return DB::table('spare_category_items as sp')->leftjoin('products_groups as p' , 'sp.products_groups_id','=','p.id')->where([['status', '=', 'A']])->select('sp.*', 'p.group_name', 'p.language')->get();
    }
    
    public static function get_all_spare_items($groups_arr = NULL) {
        if(!empty($groups_arr) ){
           return DB::table('spare_category_items as sp')
                ->leftjoin('products_groups as p' , 'sp.products_groups_group_id','=','p.group_id')
                ->leftjoin('main_category as m' , 'm.id','=','sp.main_category_id')
                ->whereIn('main_category_id' , $groups_arr)
                ->select('sp.*', 'p.group_name', 'p.language', 'm.main_cat_name')
                ->get(); 
        }
        
      	return DB::table('spare_category_items as sp')
           ->join('main_category as m' , 'm.id','=','sp.main_category_id')
		   ->select('sp.*','m.main_cat_name')
		   ->get();
    }
    
    public static function get_spare_items() {
        return DB::table('spare_category_items as sp')
                    ->leftjoin('main_category as m' , 'm.id','=','sp.main_category_id')
                    ->select('sp.*', 'm.main_cat_name')->paginate(15);
    }
   
   public static function all_spare_category_item($main_id){
       return DB::table('spare_category_items as sp')
		   ->join('main_category as m' , 'm.id','=','sp.main_category_id')
		   ->select('sp.*','m.main_cat_name')
		   ->where([['sp.main_category_id','=', $main_id]])
		   ->get();
   }
   
   public static function get_serach_spare_items($main_cat_id = NULL) {
        if($main_cat_id != NULL){
           return DB::table('spare_category_items as sp')
		   ->select('sp.*','m.main_cat_name')
           ->join('main_category as m' , 'm.id','=','sp.main_category_id')
		   ->where([['sp.main_category_id','=', $main_cat_id]])
		   ->get();
        }
     
        return DB::table('spare_category_items as sp')
		   ->select('sp.*','m.main_cat_name')
           ->join('main_category as m' , 'm.id','=','sp.main_category_id')
		   ->get();
    }
    
    public static function get_assemble_service($groups_id){
	  return DB::table('spare_category_items')
	            ->where([['products_groups_group_id' , '=' , $groups_id]])->first();
	}
	
	 /*For App API*/
     public static function find_assemble_service($group_id){
	      return DB::table('spare_category_items as a')
		            ->join('main_category as m' , [['m.id','=','a.main_category_id']])
					->select('a.*','m.main_cat_name' , 'm.description')
					->where([['products_groups_group_id','=' ,$group_id],['a.deleted_at', '=' ,NULL]])
					//->where([['m.deleted_at' , '=' , NULL]])
					->first();
	 }
   /*End*/
	
	
}
