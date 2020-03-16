<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model{
    
	protected  $table = "feedback";
    protected $fillable = [
        'id', 'users_id',  'products_id', 'workshop_id' , 'rating' , 'comments', 'type' , 'status' , 'is_deleted' ,'created_at' , 'updated_at'];

    public $type = [
      1 => "Sapre Parts",
      2 => "Tyre",
      3 => "Rim"
    ];
		
    public static function get_all_feedback(){
      return \DB::table('feedback as a')
               ->leftjoin('users as b' ,'b.id' , '=' ,'a.users_id' )
               ->select('a.*' , 'b.f_name' , 'b.l_name' , 'b.id as uid')
               ->orderBy('created_at' , 'DESC')
               ->paginate(20);
        //return Feedback::orderBy('created_at')->get();
    }
	
	public static function get_workshop_rating($workshop_user_id){
	   return Feedback::where([['workshop_id' , '=' ,$workshop_user_id] , ['is_deleted' , '=' , NULL]])->select(\DB::raw('count(*) as num_of_users , SUM(rating)/count(*) as rating'))->first();
	  
	}	
  public static function get_product_rating($request){
     return Feedback::where([['products_id' , '=' ,$request->product_id], ['type', '=', $request->type] , ['is_deleted' , '=' , NULL]])->select(\DB::raw('count(*) as num_of_users , SUM(rating)/count(*) as rating'))->first();
    
  }

  public static function parts_feedback($product_id , $tyre){
    return Feedback::where([['products_id' , '=' ,$product_id], ['type', '=', $tyre] , ['is_deleted' , '=' , NULL]])
    ->select(\DB::raw('count(*) as num_of_users , SUM(rating)/count(*) as rating'))->first();
}
   public static function get_product_rating_list($request){
     return Feedback::where([['products_id' , '=' ,$request->id], ['type', '=', $request->type] , ['is_deleted' , '=' , NULL]])->select(\DB::raw('count(*) as num_of_users , SUM(rating)/count(*) as rating'))->first();
    
  }
   public static function get_product_rating_list_for_tyre($product_id){
     return Feedback::where([['products_id' , '=' ,$product_id], ['type', '=', 2] , ['is_deleted' , '=' , NULL]])->select(\DB::raw('count(*) as num_of_users , SUM(rating)/count(*) as rating'))->first();
    
  }
	
	
    public static function get_seller_feedback($seller_id) {
        // return \DB::table('feedback')
        // ->leftjoin('users' ,'users.id' , '=' ,'feedback.users_id' )
        // ->select('feedback.*' , 'users.f_name' , 'users.l_name')->whereIn('products_id', array($product_id))
        // ->paginate(20);
        return \DB::table('feedback as a')
               ->leftjoin('users as b' ,'b.id' , '=' ,'a.users_id' )
               ->leftjoin('services as s' ,'s.id' , '=' ,'a.service_id' )
               ->select('a.*' , 'b.f_name' , 'b.l_name' , 'b.id as uid', 's.category_id')
               ->where('a.seller_id' ,'=', $seller_id)
               ->paginate(20);
    }
    
    public static function get_feedback_by_id($f_id) {
        return Feedback::leftjoin('users', 'users.id', '=', 'feedback.users_id')->leftjoin('galleries', 'galleries.feedback_id', '=', 'feedback.id')->select('feedback.*' , 'users.f_name', 'users.l_name', 'galleries.image_name')->where('feedback.id'  ,'=', $f_id)->get();
    }
    
    public static function get_workshop_feedback($workshop_id) {
        return \DB::table('feedback as a')
               ->leftjoin('users as b' ,'b.id' , '=' ,'a.users_id' )
               ->leftjoin('services as s' ,'s.id' , '=' ,'a.service_id' )
               ->select('a.*' , 'b.f_name' , 'b.l_name' , 'b.id as uid', 's.category_id')
               ->where('a.workshop_id' ,'=', $workshop_id)
               ->paginate(20);
    }
    public static function get_workshop_feedback_detail($workshop_id) {
        return \DB::table('feedback as a')
               ->leftjoin('users as b' ,'b.id' , '=' ,'a.users_id' )
               ->leftjoin('services as s' ,'s.id' , '=' ,'a.service_id' )
               ->select('a.*' , 'b.f_name' , 'b.l_name' , 'b.id as uid', 's.category_id')
               ->where('a.id' ,'=', $workshop_id)
               ->first();
    }

     public static function get_rating($product_id){
     return Feedback::where([['products_id' , '=' ,$product_id] , ['is_deleted' , '=' , NULL]])->select(\DB::raw('count(*) as num_of_product , SUM(rating)/count(*) as rating'))->first();
    
  }
    public static function add_feedback($request, $user_id) {
        return Feedback::create([
            'users_id' => $user_id,
            'products_id' => $request->products_id,
            'workshop_id' => $request->workshop_id,
            'seller_id' => $request->seller_id,
            'rating' => $request->ratings,
            'comments' => $request->comments,
            'type' => $request->type,
            'status' => 'A',
        ]);
    }
    public static function get_feedback_list($request) {
        if(!empty($request->workshop_id)) {
            $where_clause = ['workshop_id'=>$request->workshop_id, 'deleted_at' => NULL];
        } else if(!empty($request->product_id) && !empty($request->type)) {
            $where_clause = ['products_id'=>$request->product_id, 'type' => $request->type, 'deleted_at' => NULL];
        }
        return \DB::table('feedback as a')->leftjoin('users as b' ,'b.id' , '=' ,'a.users_id' )->select('a.*' , 'b.f_name' , 'b.l_name' , 'b.id as users_id')->where($where_clause)->get();
    }
	
}
