<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'price_addon_percentage', 
        'storage_driver', 
        'ftp_host', 
        'ftp_port', 
        'ftp_username', 
        'ftp_password'
    ];
}
