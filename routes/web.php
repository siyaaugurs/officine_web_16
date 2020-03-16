<?php

/* Language convertor routes defines */
Route::get("language/{language_code?}" , function ($locale = NULL){
   Session::put('locale' , $locale); 
   return redirect()->back();
});

/*magage referal routes*/
Route::get('manage_referal_code' , 'KronJobs@manage_referal_code');
/*End*/


Route::get('save_version_response' ,'KronJobs@save_version_response');

Route::get('cron_for_model' , 'KronJobs@cron_for_model');
Route::get('cron_for_version' , 'KronJobs@cron_for_version');
Route::get('cron_for_group' , 'KronJobs@cron_for_group');
Route::get('cron_for_n3_category' , 'KronJobs@cron_for_n3_category');
Route::get('cron_for_spare_parts' , 'KronJobs@cron_for_spare_parts');
Route::get('cron_for_car_maintinance' , 'KronJobs@cron_for_car_maintinance');
Route::get('cron_for_maintinance_service_times' , 'KronJobs@cron_for_maintinance_service_times');

Route::get('kron_route/{action}' , 'KronJobs@users_cars');
Route::get('add_products_image' , 'KromedaDataController@add_products_image');
Route::post('car_wash/edit_service_details' , 'CarWashController@car_wash_edit_service_details');
Route::get('user_policies/{page}/{p1?}' , 'UserPolicyController@all_user_policy');
/* End */
Route::group(['middleware'=>['auth' , 'is_admin']] , function(){
  /*oredr routes manages*/
Route::get('order_agax/{action}' , 'Order@get_action');
/*End*/	
/*Kromeda Monitoring*/	
Route::get('admin/kromeda_monitoring/{p1?}' , 'KromedaDataController@index');
Route::get('kromeda_monitoring_ajax/{action}' , 'KromedaDataController@get_action');
/*End*/
/*manage notification routes for admin*/
Route::get('notification/{action}' , 'Notification@get_action');
/*End*/

/*Product rpute define*/
Route::get('porducts' , 'Products@index');
Route::get('products/{page}/{param?}' , 'Products@page');
/*End*/	 
/*user policy management */
 Route::get('user_policy/{pages}/{p1?}' , "UserPolicyController@pages");
 Route::post('user_policy/{action}' , 'UserPolicyController@post_action');
 /*End*/ 
 /*Rim management */
 Route::get('rim/{page}/{p1?}' , "RimController@page");
 Route::get('rim_ajax/{action}' , 'RimAjaxController@get_action');
 /*End*/  
/*Tyre24 APP manage */
Route::get('tyre24/{pages}/{p1?}' , 'Tyre24Controller@pages');
/*End*/	  
/*Servive Quotess route start*/
 Route::get('service_quotes' , 'ServiceQuotes@pages');
 Route::get('service_quotes_agax/{action}' , 'ServiceQuotes@get_action');
/*End*/	
/*Kromeda Products Data*/
  Route::get('model','ProductsController@get_model');
  Route::get('version','ProductsController@version');
  Route::get('save_groups_subgroups','ProductsController@save_groups_subgroups');
  Route::get('get_and_save_products_item','ProductsController@get_and_save_products_item');
  Route::get('save_cross_and_otherCrossProducts','ProductsController@save_cross_and_otherCrossProducts');
  Route::get('search_products','ProductsController@search_products');
   Route::get('get_category_n1','ProductsController@get_category_n1');
   Route::get('get_all_n1_category','ProductsController@get_all_n1_category');
  Route::get('get_category_n2','ProductsController@get_category_n2');
/*End*/    
/*Kromeda Products Data 05_08_2019*/
  Route::get('save_groups_subgroups_05_08','ProductsController_5_08@save_groups_subgroups');
  Route::get('get_groups_05_08','ProductsController_5_08@get_groups');
  Route::get('get_all_groups_05_08','ProductsController_5_08@get_all_groups');
  Route::get('save_products_item_05_08','ProductsController_5_08@get_and_save_products_item');
  Route::get('get_products_item_05_08','ProductsController_5_08@get_products_item');
  Route::get('save_cross_and_otherCrossProducts05_08','ProductsController_5_08@save_cross_and_otherCrossProducts');
  Route::get('search_products_05_08','ProductsController_5_08@search_products');
  Route::get('search_n3_category_05_08','ProductsController_5_08@search_n3_category');
  Route::get('search_products_by_item_number_05_08/{item_id}','ProductsController_5_08@search_products_by_item_number');
/*End*/
  Route::get('products_group/save_n3_category' , 'ProductsGroups@save_n3_category');
  Route::get('products_group/save_sub_groups' , 'ProductsGroups@save_sub_groups');
  Route::get('products_group/save_groups' , 'ProductsGroups@save_groups');
  Route::get('products_group/{action}' , 'ProductsGroups@get_action');
  Route::get('get_groups_04_09' , 'ProductsController_5_08@get_groups_04_09');
  Route::get('save_groups_04_09' , 'ProductsController_5_08@save_groups_04_09');
  Route::get('save_sub_groups_04_09' , 'ProductsController_5_08@save_sub_groups_04_09');
  Route::get('get_sub_groups_04_09' , 'ProductsController_5_08@get_sub_groups_04_09');
   Route::get('admin/car_maintinance/{page}/{p1?}', 'Admin@car_maintinance');
   /*Route Users list*/
   Route::get('admin/customer_report/{page?}/{p1?}/{p2?}', 'CustomerReport@index');

 /*   Route::get('customer_report/{action}', 'CustomerReport@get_action');
   Route::post("customer_report/{action?}" , "CustomerReport@post_action"); */
   /*End*/
   Route::get('admin/{page?}/{para?}/{p2?}', 'Admin@index');
   Route::get('admin_ajax/{action}', 'Admin@get_action');
   Route::post('admin_ajax/{action}', 'Admin@post_action');
   Route::get('admin/delete_user_list/{p2?}', 'Admin@get_action');
   Route::get('coupon/{action}/{p1?}' , "Coupon@get_action");
   Route::get('coupon_ajax/{ajax_get_action}','Coupon@ajax_get_action');
   Route::post('coupon/{action}' , "Coupon@post_action");

  // Route::get('spare_products/{pages}/{para?}','SpareProductsController@pages');
   Route::post('profile/{action}','ProfileController@post_action');
   Route::post('spare_products/save_custom_products','SpareProductsController@save_custom_products');
   Route::post('spare_products/edit_custom_products','SpareProductsController@edit_custom_products');
   Route::post('spare_products/{action}/','SpareProductsController@post_action');
   Route::get('spare_products/{action}/','SpareProductsController@get_action');
   
});
 
Route::group(['middleware'=>['auth']] , function(){
  Route::get('vendor/request_for_quotes/{p1?}','RequestQuotes@index');
  Route::post('vendor/request_for_quotes_ajax/{action}','RequestQuotes@get_action');
  /*Upload Excel form data script Strat*/
Route::get('export/{action}/{p1?}','ImportExport@export');
Route::post('import/{action}','ImportExport@import');
/*End*/
/*Tyre Ajax routes start */
Route::get('tyre_ajax/{action}' , 'Tyre24Controller@ajax_get_action');
Route::post('tyre_ajax/{action}' , 'Tyre24Controller@ajax_post_action');
Route::post("tyre24_ajax/{action}" , "Tyre24Ajax@post_action");
Route::get('tyre24_ajax/{action}' , "Tyre24Ajax@get_action");
/*End*/	
  /*Customer Report routes start*/	
  Route::get('customer_report/{page?}/{p1?}/{p2?}', 'CustomerReport@index');
  Route::get('customer_report_ajax/{action}', 'CustomerReport@get_action');
  Route::post("customer_report/{action?}" , "CustomerReport@post_action");
  /*End*/	



  Route::get('change_rolls/{rolls_id}' , 'HomeController@change_rolls');	
  Route::post('workshop_time_slot' , 'HomeController@workshop_time_slot');
  Route::get('spacial_condition/{pages?}/{p1?}','SpecialCondition@pages');
  Route::get('mot_spare_parts/{action}' , 'MotController@mot_spare_parts');
  Route::get('mot_services/{action}' , 'MotController@get_action');
  Route::post('mot_services/{action}' , 'MotController@post_action');
  Route::get('car_wash/{action}' , 'CarWashController@get_action');
  Route::get('car_maintinance/{action}' , 'CarMaintenanceController@get_action');
  Route::post('car_maintinance/{action}' , 'CarMaintenanceController@post_action');
   Route::get('master/{page?}/{para?}', 'Master@index');
   //Route::get('master_get/{action}/{para?}' , 'Master@get_action');
   Route::post('master/{action?}' , 'Master@post_action');
   Route::get('master_agax/{action}' , 'Master@get_action');
    Route::post('car_revision/{action}' , "CarRevision@post_action");
    Route::get('car_revision/{action}' , "CarRevision@get_action");
    Route::post('spacial_condition_ajax/{action}' , "SpecialCondition@post_action");
    Route::get('spacial_condition_ajax/{action}' , "SpecialCondition@get_action");

/*Seller Route start*/
Route::get('seller' , 'Seller@index');
Route::post("seller/add_pfu_detail" , 'Seller@add_pfu_detail');
Route::get('seller/{page}/{para?}' , "Seller@page");
Route::post("seller_ajax/{action}" , 'Seller@post_action');
Route::get("seller_ajax/{action}","Seller@get_action");
/*End*/
});

Route::post('register', "Auth\RegisterController@sign_in")->name('register');
Route::get("commonAjax/{action}" , "CommonAjax@get_action");
Route::post("commonAjax/{action}" , "CommonAjax@post_action");
 Route::post('sos_ajax/{action}/','SosController@post_action');
 Route::get('sos_ajax/{action}/','SosController@get_action');
 Route::post('wrecker_ajax/{action}/','WreckerServices@post_action');
 Route::get('wrecker_ajax/{action}/','WreckerServices@get_action');

//Route::get("admin/{page?}" , "Admin@index");
//Route::get("commonAjax/{action}" , "CommonAjax@get_action");

Route::group(['middleware' => ['auth']], function () {
   /*Car wash related url*/
   Route::post('car_wash/time_slot' , 'CarWashController@car_wash_time_slot');
   Route::get('vendor/home' , 'Vendor@index');
   Route::get('vendor/{page}/{para?}/{para2?}' , "Vendor@page");
   Route::post("vendor/{action}" , "Vendor@postAction");
   Route::post('services/edit_services' , 'ServicesController@edit_services');
   Route::post('services/{action}' , 'ServicesController@post_action'); 
   Route::get("services/{action}" , "ServicesController@get_action");
    Route::post("service_ajax/{action}" , "ServicesController@post_ajax_action");
   
   Route::post("vendor_ajax/{action}" , "VendorAjax@postAction");
   Route::get("vendor_ajax/{action}" , "VendorAjax@getAction");
   Route::get("workshop_ajax/{action}" , "Vendor@get_action");
});
/*Seller route defined */
/*Products Management routes*/
Route::get('product/{action}',"Products@get_action");
Route::get("products_ajax/{action}","ProductsAjax@get_action");
Route::get('products_category/{action}' , 'ProductsCategory@get_action');
Route::get('products_categories/{action}' , 'ProductsCategory@products_categories');
Route::post('products_category/{action}' , 'ProductsCategory@post_action');
Route::post("products_ajax/{action}" , 'ProductsAjax@post_action');
/*End*/

/* Workshop Route script start */
Route::get('workshop/{page}/{para?}' , 'Workshop@page');
Route::post('workshop/{action}' , 'Workshop@post_action');
Route::post('assemble_services/add_services' , 'AssembleController@add_services');
Route::post('assemble_services/{action}' , 'AssembleController@post_action');
Route::get('assemble_services/{action}' , 'AssembleController@get_action');
/*End*/
/*AssmebleProducts Controller Routes */
Route::get("assemble_products/{action}","AssembleProducts@get_action");
/*End */
//Route::get("forget_password" , "Auth\LoginController@forget_password");
Route::get("logout" , "Logout@logout");
Route::post("sign_in" , "Auth\LoginController@sign_in");

Route::get('login',"Auth\LoginController@login")->name('login');
Route::get('policy_pages/{p1?}',"HomeController@policy_pages")->name('policy_pages');
Route::get('registration',"Auth\LoginController@registration")->name('registration');
Route::get('signup_verification/verification/{token}', "Auth\RegisterController@signup_verification");


/*Password reset routes defined*/
Route::get('/password/send_reset_notification' , "Auth\ResetPasswordController@send_reset_notification");
Route::get('/password/{page}/{p1?}' , "Auth\ResetPasswordController@page");
Route::post('/password/{action}' , "Auth\ResetPasswordController@action");

/*End*/
Route::get('/' , "HomeController@index");
Route::get('/{page}/{p1?}' , "HomeController@page");
Route::post('home/gallery', "HomeController@gallery");

/*
Route::get("add_workshop" , "HomeController@add_workshop");
Route::get("edit_workshop/{}" , "HomeController@add_workshop");
*/