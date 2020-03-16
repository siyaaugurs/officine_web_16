<?php

Route::get('version_repair_time/{version_id}/{time_id}/{lang}', 'API\CategoryController@version_repair_time');
/* Route::get('model_details' , function(){
    
}); */
Route::get('generate_referal_code' , 'API\UserController@generate_referal_code');
Route::get('get_car_wash_category/{category_id}/{car_size}', 'API\CarwashController@get_services');
Route::get('get_workshop', "API\CarwashController@get_workshop");
Route::get("get_cost/{service_id}/{cost_1?}/{cost_2?}", "API\CategoryController@service_cost");
//Route::get('get_workshop_service_package/{workshop_users_id}/{category_id}' , "API\CategoryController@get_workshop_service_package");
Route::get('get_workshop_service_package/{workshop_users_id}/{category_id}/{select_date}/{car_size}/{car_id}/{user_id}', "API\CategoryController@get_workshop_service_package");
Route::get('get_assemble_workshop_package/{workshop_id}/{selected_date}/{product_id}/{car_id}/{user_id}', "API\CategoryController@get_assemble_workshop_package");
Route::get('get_calendar_with_price', 'API\CategoryController@get_next_seven_days_min_price');
Route::get('assemble_workshop_calendar_with_price', 'API\CategoryController@assemble_workshop_with_amount');
Route::post("check_service_booking", "API\UserDetail@check_service_booking");
/* Spare part API*/

Route::get('get_spare_group/{car_version}/{lang}', 'API\Sparepart@get_spare_parts');
Route::get('get_spare_sub_group/{group_id}', 'API\Sparepart@get_spare_sub_group');
Route::get('get_spare_n3_groups/{group_id}', 'API\Sparepart@get_spare_n3_category');
Route::get('get_products', 'API\Sparepart@get_products');
Route::get('get_productsnew', 'API\Sparepart@get_productsnew');
Route::get('get_products_details/{product_id}/{selected_date?}', 'API\Sparepart@get_products_details');
Route::get('get_product_brand_list' , 'API\Sparepart@get_product_brands');
/*get spspecial_condition*/
Route::get('car_washing_special_condition' , 'API\SpecialCondition@car_washing_special_condition');
Route::get('car_revision_special_condition' , 'API\SpecialCondition@car_revision_special_condition');
Route::get('car_maintenance_special_condition' , 'API\SpecialCondition@car_maintenance_special_condition');



Route::get('get_special_condition',"API\SpecialCondition@get_special_condition");	
Route::get("getCarMakers", "API\DashboardController@getMakers");
Route::get("getCarModels/{makeId}", "API\DashboardController@getModels");
Route::get("getCarVersion/{makeId}/{year}", "API\DashboardController@getVersion");
Route::get("getParts/{idVeh}/{lang}", "API\DashboardController@getParts");

Route::get('get_service_quotes_list' , 'API\ServiceQuotesController@get_service_quotes_list');
Route::get('get_next_seven_days_min_price_for_service' , 'API\ServiceQuotesController@get_next_seven_days_min_price_for_service');
Route::get('get_main_category' , 'API\ServiceQuotesController@get_main_category');
/*coupon */
Route::get('get_coupon', "API\CouponController@get_coupon");
Route::get('get_all_coupon', "API\CouponController@get_all_coupon");

/*Get parts number API*/
Route::get("getPartsNumber/{idVeh}/{idParts}", "API\CategoryController@getPartsNumber");
/* CodiceOE */
Route::get("getPartsImage/{idVeh}/{idParts}", "API\CategoryController@getPartsImage");
Route::get("getOtherPartsImage/{parts_number_id}/{CodiceOE}", "API\CategoryController@getOthersPartsImage");
/*End*/
//Mot service 
Route::get('get_mot_service' , 'API\MotController@get_mot_service');
Route::get("get_mot_service_operation","API\MotController@get_mot_service_operation");
Route::get("get_workshop_for_mot_service","API\MotController@get_workshop_for_mot_service");
Route::get("get_next_seven_days_min_price_for_mot_service","API\MotController@get_next_seven_days_min_price_for_mot_service");
Route::get("mot_services_package","API\MotController@mot_services_package");

Route::get('verifyEmail/{token}', 'API\UserController@verifyEmail');

Route::get("getTyre", "API\WheelsizeController@getTyre");
Route::get("getTyreSearch", "API\WheelsizeController@getTyre");

Route::get('get_all_advertising', "API\AdvertisingController@get_all_advertising");

/*Tyre 24 API Routes Start*/
Route::get('tyre24/get_tyres' , "API\Tyre24@get_tyres");
Route::get('tyre24/get_details' , "API\Tyre24@get_details");

/*Steel wheel search script start*/
Route::get('tyre24/get_rim_manufacturer' , "API\Tyre24@get_rim_manufacturer");
Route::get('tyre24/get_rim_type_for_manufacturer' , "API\Tyre24@get_rim_type_for_manufacturer");
Route::get('tyre24/get_rim_workmanship_for_rim_type' , "API\Tyre24@get_rim_workmanship_for_rim_type");
Route::get('tyre24/get_rim' , "API\Tyre24@get_rim");
Route::get('tyre24/search_rims' , "API\Tyre24@search_rims");
 /*End*/
/*Alloy wheel search (new)*/
Route::get('tyre24/get_comfort_alloy_rim_car_brands',  'API\Tyre24@get_comfort_alloy_rim_car_brands');
Route::get('tyre24/get_comfort_alloy_rim_car_models',  'API\Tyre24@get_comfort_alloy_rim_car_models');
Route::get('tyre24/get_comfort_alloyrim_car_types',  'API\Tyre24@get_comfort_alloyrim_car_types');
Route::get('tyre24/get_comfort_alloy_rim_car_info',  'API\Tyre24@get_comfort_alloy_rim_car_info');
/*End*/

Route::get('tyre24/get_tyre_list',  'API\Tyre24Controller@get_tyre_list');
Route::post('insert_product_order','API\UserDetail@insert_product_order');
Route::get('best_seller_product','API\ProductController@best_seller_product');
Route::get('get_tyre','API\Tyre24Controller@get_tyre');
Route::get('get_tyre_specification','API\Tyre24Controller@get_tyre_specification');
Route::get('get_user_tyre_details','API\Tyre24Controller@get_user_tyre_details');
Route::post('get_workshop_address_info','API\Tyre24Controller@get_workshop_address_info');



Route::get('get_all_wracker_workshop_services' , 'API\WrackerService@get_all_wracker_workshop_services');
Route::get('sos_workshop_packages' , 'API\WrackerService@sos_workshop_packages');
Route::get('get_wrackerservices' , 'API\WrackerService@get_wrackerservices');
Route::get('next_thirty_days_for_sos' , 'API\WrackerService@next_thirty_days_for_sos');
Route::get('sos_workshop_list_for_appontment' , 'API\WrackerService@sos_workshop_list_for_appontment');
Route::get('sos_workshop_list_for_emergency' , 'API\WrackerService@sos_workshop_list_for_emergency');
Route::get('sos_workshop_packages_for_emergency' , 'API\WrackerService@sos_workshop_packages_for_emergency');


Route::get('get_tyre_workshop' , 'API\Tyre24Controller@get_tyre_workshop');
Route::get('get_next_seven_days_min_price_for_tyre' , 'API\Tyre24Controller@get_next_seven_days_min_price_for_tyre');
Route::get('get_tyre_workshop_package' , 'API\Tyre24Controller@get_tyre_workshop_package');
Route::get('get_tyre_details' , 'API\Tyre24Controller@get_tyre_details');
/*Stert car_maintenance_services*/
Route::get('car_maintenance_services' , 'API\CarMaintenanceController@car_maintenance_services');
Route::get('car_maintenance_workshop' , 'API\CarMaintenanceController@car_maintenance_workshop');
Route::get('get_brand_car_maintenance' , 'API\CarMaintenanceController@get_brand_car_maintenance');
Route::get('get_next_seven_days_min_price_for_car_maintenance' , 'API\CarMaintenanceController@get_next_seven_days_min_price_for_car_maintenance');
Route::get('car_maintenance_services_package/{workshop_user_id}/{category_service_id}/{selected_date}/{car_id}/{user_id}' , 'API\CarMaintenanceController@car_maintenance_services_package');

/* End car_maintenance_services*/

/*End*/

/*Request For Quotes api*/
Route::get('request_quotes_workshop' , 'API\ServiceQuotes@request_quotes_workshop');
Route::get('next_seven_days_request_quotes' , 'API\ServiceQuotes@next_seven_days_request_quotes');
Route::get('workshop_package_for_service_quotes' , 'API\ServiceQuotes@workshop_package_for_service_quotes');

/*End*/

/* Car Revision API */
Route::get('get_revision_services',  'API\CarRevisionController@get_revision_services');
Route::get('get_workshop_revision_facility',  'API\CarRevisionController@car_revision_workshop_list');
Route::get('get_next_thirty_days_min_price',  'API\CarRevisionController@get_next_thirty_days_min_price');
Route::get('get_car_revision_service_package/{users_id}/{service_id}/{select_date}/{selected_car_id}/{user_id}' , "API\CarRevisionController@get_car_revision_service_package");
/* Car Revision API */

Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');
Route::post('resetPassword', 'API\UserController@resetPassword');
Route::get('resetLink/{token}/{error?}', 'API\UserController@resetLink');
Route::post('resetPasswordByLink', 'API\UserController@resetPasswordByLink');
Route::get("getSearchPlate/{plate}/{lang}", "API\DashboardController@getSearchPlate");
Route::post('save_user_tyre_details','API\Tyre24Controller@save_user_tyre_details');
Route::get('get_sos_workshop' , 'API\Tyre24Controller@get_sos_workshop');
Route::get('get_workshop_sos_calender' , 'API\Tyre24Controller@get_workshop_sos_calender');

Route::get('get_workshop_ratings', "API\FeedbackController@get_workshop_ratings");
Route::get('support_type', "API\SupportController@support_type");

Route::group(['middleware' => 'auth:api'], function () {
  
/*Feedback API */
Route::post('add_feedback', "API\FeedbackController@add_feedback");
/*End */
Route::post('service_booking_request_quotes' , 'API\ServiceQuotes@service_booking_request_quotes');
Route::post('service_quotes' , 'API\ServiceQuotes@service_quotes');
Route::post('emergency_sos_service_booking' , 'API\WrackerService@emergency_sos_service_booking');
Route::post('sos_service_booking' , 'API\WrackerService@sos_service_booking');

Route::post('add_notification_detail','API\NotificationController@add_notification_detail');
Route::get('get_notification_detail','API\NotificationController@get_notification_detail');
Route::get('delete_account_for_customer', "API\NotificationController@delete_account_for_customer");

/*Generate support ticket routes*/
Route::post('generate_support_ticket' , 'API\SupportController@generate_support_ticket');

Route::get('support_ticket' , 'API\SupportController@support_ticket');
/*End */
Route::get('remove_tyre_detail/{id}' , 'API\Tyre24Controller@remove_tyre_detail');
Route::get('car_revision_next_schedule' , 'API\CarRevisionController@car_revision_next_schedule');
Route::post('save_users_address' , 'API\UserDetail@save_users_address');
Route::post("service_booking", "API\UserDetail@service_booking");
/* booking car_maintenance_services*/
Route::post("service_booking_for_car_maintenance", "API\CarMaintenanceController@service_booking_for_car_maintenance");
/* booking end car_maintenance_services*/
Route::get("apply_special_condition","API\SpecialCondition@apply_special_condition");

Route::post('assemble_service_booking', "API\ServiceBooking@assemble_service_booking");
//car revision service booking API
Route::post('car_revision_service_booking',"API\ServiceBooking@car_revision_service_booking");

//mot service booking API
Route::post("mot_service_booking","API\MotController@mot_service_booking");


// shopping cart Api
Route::get("get_user_profile","API\UsercartController@get_user_profile");
Route::post("update_coustmer_profile","API\UsercartController@update_coustmer_profile");
Route::post("update_coustmer_address","API\UsercartController@update_coustmer_address");
Route::post("update_coustmer_contact","API\UsercartController@update_coustmer_contact");
Route::post("update_coustmer_change_password" , "API\UsercartController@update_coustmer_change_password");
Route::get("get_user_order_details" , "API\UsercartController@get_user_order_details");
route::post("add_user_contact_list","API\UsercartController@add_user_contact_list");
route::post("add_user_address_list","API\UsercartController@add_user_address_list");
route::post("delete_user_add_item","API\UsercartController@delete_user_add_item");
route::get("get_cart_list","API\UsercartController@get_cart_list");
route::post("check_user_cart_items","API\UsercartController@check_user_cart_items");
route::post("add_to_cart","API\UsercartController@add_to_cart");
route::get("remove_cart_items","API\UsercartController@remove_cart_items");
route::post("update_product_quantity","API\UsercartController@update_product_quantity");
route::post("delete_user_contact_list","API\UsercartController@delete_user_contact_list");
route::post("update_payment_status","API\UsercartController@update_payment_status");
route::post("delete_user_address","API\UsercartController@delete_user_address");
route::post("add_user_wish_list","API\UsercartController@add_user_wish_list");
route::get("get_user_wish_list","API\UsercartController@get_user_wish_list");
route::post("delete_user_wish_list","API\UsercartController@delete_user_wish_list");


Route::get("show_notification","API\NotificationController@show_notification");
Route::post("service_booking", "API\UserDetail@service_booking");

Route::post('details', 'API\UserController@details');
Route::post('logout', 'API\UserController@logout');
Route::post('changePassword', 'API\UserController@changePassword');

Route::get("getCarSearch/{opt}/{value}/{lang}", "API\DashboardController@getCarSearch");
Route::get('selected_car/{user_details_id}', 'API\CategoryController@get_selected_car');

Route::get("getSubParts/{idVeh}/{idParts}/{lang}", "API\DashboardController@getSubParts");
Route::get("getPartsItems/{idVeh}/{idParts}/{lang}", "API\DashboardController@getPartsItems");
Route::get("getSubPartsItems/{idVeh}/{idSubParts}/{lang}", "API\DashboardController@getSubPartsItems");
Route::get("getItemNo/{idVeh}/{idItem}", "API\DashboardController@getItemNo");
Route::get("getItemNoUniq/{idVeh}/{idItem}/{lang}", "API\DashboardController@getItemNoUniq");

Route::get("getCarSearch/{opt}/{value}/{lang}", "API\DashboardController@getCarSearch");
Route::get("getMakers", "API\WheelsizeController@getMakers");
Route::get("getModels/{make}", "API\WheelsizeController@getModels")->where(array('make' => '[a-zA-z]+'));
Route::get("getModelsInfo/{make}/{modalSlug}", "API\WheelsizeController@getModelsInfo")->where(array('make' => '[a-zA-z]+', 'modalSlug' => '[a-zA-z]+'));
Route::get("getModelsInfoInYear/{make}/{modalSlug}/{year}", "API\WheelsizeController@getModelsInfoInYear")->where(array('make' => '[a-zA-z]+', 'modalSlug' => '[a-zA-z]+', 'year' => '[0-9]+'));
Route::get("getListYear/{make}", "API\WheelsizeController@getListYear")->where(array('make' => '[a-zA-z]+'));
Route::get("getModelsModi/{make}/{modalSlug}/{year}", "API\WheelsizeController@getModelsModi")->where(array('make' => '[a-zA-z]+', 'modalSlug' => '[a-zA-z]+', 'year' => '[0-9]{4}'));

Route::get("getModelsModiFitWheel/{make}/{modalSlug}/{year}", "API\WheelsizeController@getModelsModiFitWheel")->where(array('make' => '[a-zA-z]+', 'modalSlug' => '[a-zA-z]+', 'year' => '[0-9]{4}'));
Route::get("getListBoltPattern", "API\WheelsizeController@getListBoltPattern");

Route::get("getListCarModelsByBoltPattern/{boltpat}", "API\WheelsizeController@getListCarModelsByBoltPattern");
// Route::get("getTyre/" , "API\WheelsizeController@getTyre");
Route::get("getListCarModelsByTire/{tirepat1}/{tirepat2}", "API\WheelsizeController@getListCarModelsByTire")->where(array('tirepat1' => '[0-9]+', 'tirepat2' => '[a-zA-Z0-9]+'));
Route::get("getGenerationOfModel/{make}/{model}", "API\WheelsizeController@getGenerationOfModel")->where(array('make' => '[a-zA-z]+', 'model' => '[a-zA-z]+'));
Route::get("getAllModelInfo/{make}/{model}/{year}", "API\WheelsizeController@getAllModelInfo")->where(array('make' => '[a-zA-z]+', 'model' => '[a-zA-z]+', 'year' => '[0-9]{4}'));

Route::get("getModelByRim_Bolt/{boltPat}/{rimdia}/{rimWid}", "API\WheelsizeController@getModelByRim_Bolt")->where(array('rimdia' => '[0-9 .]+', 'rimWid' => '[0-9 .]+'));
Route::get("getModelBytyre/{tireWids}/{aspect_ratio}/{rimDia}", "API\WheelsizeController@getModelBytyre")->where(array('tireWids' => '[0-9 .]+', 'aspect_ratio' => '[0-9]+', 'rimWid' => '[0-9 .]+'));

Route::post('addCar', 'API\UserDetail@addCar');
Route::get("addSearchCar/{plate}/{lang}", "API\DashboardController@addSearchCar");
Route::post('deleteCar', 'API\UserDetail@deleteCar');
Route::post('editCar', 'API\UserDetail@editCar');
Route::get('carList', 'API\UserDetail@carListInfo');

/*Js routes Api */
Route::get('car_model_details', 'API\Carcontroller@car_model_details');
Route::get('get_user_details_pic', 'API\Carcontroller@get_user_details_pic');
Route::post('upload_car_pic', 'API\Carcontroller@upload_car_pic');
Route::get('set_default_car_pic', 'API\Carcontroller@set_default_car_pic');
Route::get('garage/{action}', "API\Carcontroller@get_action");

/*End*/

Route::get('search_key', 'API\SearchController@getSearchKyes');
Route::post('search_key/{key}', 'API\SearchController@saveSearchKey');
Route::delete('clear_key', 'API\SearchController@clearSearchKey');
Route::get('search_data/{key}', 'API\SearchController@getSearchData');

Route::post('tyre_service_booking' , 'API\Tyre24Controller@tyre_service_booking');

});
