<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Usersreferal extends Model{
    
    protected $table = "usersreferals"; 
    protected $fillable = ['id', 'sender_from', 'sender_referal_code', 'receiver_to' , 'deleted_at' , 'created_at' , 'updated_at'];
     


}
