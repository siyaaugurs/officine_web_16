<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class User_notification_detail extends Model {
    //
    public $table = 'user_notification_details';
    protected $fillable = ['id', 'user_id', 'lang', 'notification_setting', 'notification_for_offer', 'notification_for_revision'];
}
