<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Service_for_quotes extends Model{
   
   protected  $table = "service_quotes_details";
   protected $fillable = ['id', 'user_id', 'main_category_id', 'max_appointment', 'hourly_cost' , 'status' , 'deleted_at','created_at','updated_at'];
   
   
   
}
