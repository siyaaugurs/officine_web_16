<?php
namespace App\Http\Controllers\API;
use sHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Products_order;
use App\ProductsNew;
use App\ProductsImage;
use App\Feedback;
use Illuminate\Support\Facades\Auth; 
use Validator;


class ProductController extends Controller{

    public function best_seller_product(Request $request){
	$product_detail = collect();
	$best_seller_products = Products_order::best_seller_products_id();
	if($best_seller_products){
	$ranks = [];

	// Count the cards for each rank
	foreach ($best_seller_products as $card) {
	if (!isset($ranks[$card->products_id])) {
		$ranks[$card->products_id] = 0;
	}
	$ranks[$card->products_id]++;
	}
	// Sort the cards array
	usort($best_seller_products, function ($a, $b) use ($ranks) {
	// If the cards count is the same for the rank, compare rank
	
	if ($ranks[$a->products_id] == $ranks[$b->products_id]) {
		return $b->products_id - $a->products_id;
	}
	
    // Compare the card count for the rank
    return $ranks[$b->products_id] - $ranks[$a->products_id];
	});
	$collection=collect($best_seller_products);
	
	$plucked = $collection->pluck('products_id')->all();
	$product_detail = ProductsNew::get_product_details($plucked);
	$min_price = $product_detail->min('price');
	$max_price = $product_detail->max('price');
	if(!empty($request->brand)){
			$brand_name_arr = explode(',' , $request->brand);
			$product_detail = $product_detail->whereIn('listino' , $brand_name_arr);
	}
	foreach($product_detail as $product){
		$product->type = (string) $product->type;
		$product->best_selling = Products_order::best_seller_products_count($product->id);
			$product->min_price = $min_price;
			$product->max_price = $max_price;
			$product->brand_image = null;
			$product->images = null;
			$image_arr = null;
			$image_arr = ProductsImage::get_products_image($product->id);
			if($image_arr != FALSE){
				$product->images = $image_arr;
			}
			/*Get Brand image logo */
			$brand_image = \App\BrandLogo::brand_logo($product->listino);
			if($brand_image != NULL){
				$product->brand_image = $brand_image->image_url; 
			}
			$all_feed_back = null;
			$all_feed_back['rating'] = null;
			$all_feed_back['num_of_product'] = null;
			$all_feed_back = \App\Feedback::get_rating($product->id);
			if($all_feed_back != NULL) {
				$product->rating = $all_feed_back;
				$product->rating_star = (string)$all_feed_back['rating'];
				$product->rating_count = $all_feed_back['num_of_product'];
			} /*End*/  
	}
		if(!empty($request->rating)){
			$rating = explode(',' , $request->rating);
			$rating_filtered = $product_detail->whereBetween('rating_star' , $rating);
			$product_detail = (string)$rating_filtered;
		}
		if(!empty($request->price_range)){
			$price_arr = explode(',' , $request->price_range);
			$price_filtered = $product_detail->whereBetween('price' , $price_arr);
			$product_detail = $price_filtered;
		}
			$sorted = $product_detail->sortByDesc('best_selling');
			$product_detail = $sorted->values();
		if(!empty($request->price_level)){
			if($request->price_level == 1){
				$sorted = $product_detail->sortBy('price');
				$product_detail = $sorted->values();
			}
			if($request->price_level == 2){
				$sorted = $product_detail->sortByDesc('price');
				$product_detail = $sorted->values();
			}
		}
		return sHelper::get_respFormat(1 , null , null , $product_detail); 
	} else {
		return sHelper::get_respFormat(0 , "No data." , null , null); 
	}
    }
}
