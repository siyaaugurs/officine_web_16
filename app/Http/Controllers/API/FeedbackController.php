<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use sHelper;
use DB;

class FeedbackController extends Controller {

    public function add_feedback(Request $request) {

        $validator = \Validator::make($request->all(), [
            'ratings'=>'required',
        ]);
        if($validator->fails()){
            return sHelper::get_respFormat(0 ,$validator->errors()->first() , NULL, NULL); 
        }
        /*if(!empty($request->images)){
            echo "<pre>";
            print_r($request->all());exit;
            if(count($request->images) > 5) {
                return sHelper::get_respFormat(0 , "Please Select Maximum Five Image ." , null , null); 
            }
        }*/
        $response = \App\Feedback::add_feedback($request, Auth::user()->id);
        if(!empty($response)) {
            if(!empty($request->images)){
                $feedback_images = $this->upload_images($request);
                if(count($feedback_images) > 0){
                    foreach($feedback_images as $key => $image){
                        $upload_image_response = \App\Gallery::save_feedback_images($image , $response->id); 
                    }
                }
            }
            return sHelper::get_respFormat(1 , "Feedback Added Successfully !!! " , $response ,null );
        } else {
            return sHelper::get_respFormat(0 , "Something Went wrong please try again ." , null , null); 
        }
    }

    public function get_workshop_ratings(Request $request) {
        if(!empty($request)) {
            $respone = \App\Feedback::get_feedback_list($request);
            if($respone->count() > 0){
                foreach($respone as $feedback_response){
                    $feedback_response->images = NULL;
                    $feedback_response->no_of_people = $respone->count();
                    $images = sHelper::get_feedback_images($feedback_response->id);
                    if($images->count() > 0){
                        $feedback_response->images = $images;
                    }
                    $all_feed_back =  $feedback_response->avg_ratings = null;
                    if($request->workshop_id != NULL) {
                        $all_feed_back = \App\Feedback::get_workshop_rating($request->workshop_id);
                        $feedback_response->avg_ratings = (string) $all_feed_back->rating;
                    } 
                    if($request->product_id != NULL) {
                        $all_feed_back = \App\Feedback::get_product_rating($request);
                        $feedback_response->avg_ratings = (string) $all_feed_back->rating;
                    }
                }
                return sHelper::get_respFormat(1 , null , null ,$respone );
            } else {
                return sHelper::get_respFormat(0 , "No Feedback Avilable ." , null , null); 
            }
        } else {
            return sHelper::get_respFormat(0 , "Something Went wrong please try again ." , null , null); 
        }
    }
   
}