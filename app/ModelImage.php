<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelImage extends Model{

    protected $table = "model_images";
	protected $fillable = ['id', 'model_slug'  , 'image_url','created_at' , 'updated_at' , 'deleted_at'];
    
    
}
