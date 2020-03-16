<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Userwallet extends Model{
 
    protected  $table = "userwallets";
    protected $fillable = ['id', 'user_id', 'amount' , 'deleted_at','created_at' , 'updated_at'];
    
}
