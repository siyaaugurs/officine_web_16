<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Tyre24_details extends Model{

  protected  $table = "tyre24_details";
  protected $fillable = [
      'id', 'tyre24_id', 'tyre24s_itemId', 'seller_price', 'tyre_detail_response', 'rolling_resistance', 'noise_db', 'tyre_class', 'unique_id', 'deleted_at' , 'created_at' , 'updated_at'];


  public static function get_tyre_details($tyre_item_id){
      return Tyre24_details::where([['tyre24s_itemId' , '=' ,$tyre_item_id]])->first();
  }

}
