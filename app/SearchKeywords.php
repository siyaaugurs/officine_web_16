<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SearchKeywords extends Model {
	protected $table = "search_keyword";
	protected $fillable = ['id', 'user_id', 'keyword'];
}
