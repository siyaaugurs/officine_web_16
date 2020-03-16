<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    //
    protected $table = 'notifications';
    protected $fillable = ['id','notification_type' ,'target_user' ,'title','subject','content','url','file','file_url','deleted_at','created_at','updated_at'];
    


    public static function add_notification($request,$image){
		$image_url = url("storage/Notification/$image");
        return Notification::updateOrCreate(
        ['id'=>$request->id] ,[
                'notification_type' => $request->notification_type ,
                'target_user' => $request->target_user ,
				'title'=>$request->title,
                'subject' => $request->subject ,
                'content' => $request->content ,
                'url' => $request->url ,
                'file' => $image,
				'file_url' => $image_url,] 
        );
     }
	 
	 public static function get_all_notification_list(){
		 return Notification::where([['deleted_at','=',NULL]])->get()->all();	 
	 }
	 
	 public static function edit_notification_details($id){
		 return Notification::where([['id','=',$id]])->first(); 
	 }
	 
	  public static function delete_notification($id){
		 return Notification::where([['id','=',$id]])->delete();  
	  }
	 
	 
}
