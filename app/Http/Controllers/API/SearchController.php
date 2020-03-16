<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\SearchKeywords;
use Auth;
use sHelper;

class SearchController extends Controller {
	public $successStatus = 200;

	public function getSearchKyes($hint = "") {
		$user = Auth::user()->id;

		$data["self"] = SearchKeywords::select("keyword")->distinct()->where("user_id", $user)->get();
		$data['all'] = SearchKeywords::select("keyword")->distinct()->where("user_id", "!=", $user)->get();
		return sHelper::get_respFormat(1, '', $data, null);
	}

	public function saveSearchKey($key) {

		if ($key) {
			SearchKeywords::updateOrCreate(array(
				"user_id" => Auth::user()->id,
				"keyword" => $key));
			return sHelper::get_respFormat(1, 'Keyword saved.', null, null);
		} else {
			return sHelper::get_respFormat(1, 'Error.', null, null);
		}

	}

	public function clearSearchKey() {
		SearchKeywords::where("user_id", Auth::user()->id)->delete();
		return sHelper::get_respFormat(1, 'Keyword cleard.', null, null);

	}

	public function getSearchData($key) {

	}
}

?>