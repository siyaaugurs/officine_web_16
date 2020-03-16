<?php
namespace App;
use Auth;
use Illuminate\Database\Eloquent\Model;
use App\Tyre24_details;
use App\Http\Controllers\API\Tyre24Controller;
use DB;
use apiHelper;
use sHelper;

class Tyre24 extends Model{

  protected  $table = "tyre24s";	  
  protected $fillable = [
    'id', 'user_id' , 'type_status', 'tyre_max_size' , 'max_width' , 'max_aspect_ratio' ,'max_diameter', 'pair' ,'type' , 'season_tyre_type', 'vehicle_tyre_type','speed_index', 'load_speed_index', 'peak_mountain_snowflake','itemId', 
    'our_description' , 'seller_price','quantity','tax' , 'tax_value' ,  'stock_warning','unit','discount' ,'tyre_response','meta_key_title' , 
    'meta_key_word' , 'status', 'substract_stock','delivery_days','PFU','runflat','reinforced','unique_id' , 'deleted_at' , 'created_at' , 'updated_at'
   ];

  public $vhicle_type = ['c'=>"Car" , 'm' ,  'o' , 'i'];
  public $season_type = ['s' , 'w' , 'g'];

  

  public static function save_tyre_response_2($max_tyre_size , $tyre_response , $string_arr){
	$str_arr = explode('/' , $string_arr);
	$max_width = $str_arr[0];  $max_aspect_ratio = $str_arr[1]; $max_diameter = $str_arr[2];
    $uid = Auth::user()->id; 
    $created_at = $updated_at = date('Y-m-d h:i:s');
    $queries = '';
    foreach($tyre_response->items as $tyre){
      if(!empty($tyre->type)){
        $type_tyre = $tyre->type;
        if(in_array($type_tyre , array('s','w', 'g'))){
          $season_type = $tyre->type;
          $vhicle_type = 'c';
        }
        else{
          $vhicle_type = $tyre->type;
          $season_type = NULL;
        }
      }  
      /*Find speed index from description*/
		$speed_index = sHelper::find_speed_index($tyre->description);
	  /*End*/
	  $tyre_response = json_encode($tyre);
      $uniqueKey = $max_tyre_size.$tyre->itemId;
      $queries .=  "INSERT INTO `tyre24s`(`id`, `user_id`, `tyre_max_size`,`max_width` ,`max_aspect_ratio`,`max_diameter`,`itemId` , 
        `tyre_response`, `type` ,`speed_index`,  `unique_id`, `created_at` , `updated_at`) VALUES 
        (null ,'$uid','$max_tyre_size','$max_width' ,'$max_aspect_ratio','$max_diameter','$tyre->itemId','$tyre_response','$tyre->type','$speed_index','$uniqueKey','$created_at','$updated_at') 
        ON DUPLICATE KEY UPDATE max_width='$max_width' ,max_aspect_ratio='$max_aspect_ratio',max_diameter='$max_diameter',tyre_response='$tyre_response' , type='$tyre->type' , speed_index='$speed_index';\n";
        $brand_unique_key = "2".sHelper::slug($tyre->manufacturer_description); 
        $queries .= "INSERT INTO `brand_logos`(`id` , `brand_type`, `brand_name`,`unique_id`, `created_at`) VALUES (null, 2,  '$tyre->manufacturer_description', '$brand_unique_key','$created_at')
		ON DUPLICATE KEY UPDATE brand_name='$tyre->manufacturer_description';";
		$get_tyre_details = apiHelper::get_tyre_details($tyre->itemId);
        if($get_tyre_details != FALSE){
          $tyre_details = json_decode($get_tyre_details);
          $uniqueKey2 = $uniqueKey.$tyre->itemId;
          $queries .= "INSERT INTO `tyre24_details`(`id`, `tyre24s_itemId`, `tyre_detail_response`, 
              `unique_id`, `created_at` , `updated_at`) VALUES 
              (null ,'$tyre->itemId','$get_tyre_details', '$uniqueKey2','$created_at','$updated_at') 
              ON DUPLICATE KEY UPDATE tyre_detail_response='$get_tyre_details';\n"; 
          }
  }
  


	return CustomDatabase::custom_insertOrUpdate($queries);
  } 
  
   /*Get All Tyres function */
  public static function get_tyres($tyre_id = NULL){
    if($tyre_id != NULL){
      return Tyre24::where([['id' , '=' , $tyre_id] , ['deleted_at']])->first();
    }
     return DB::table('tyre24s')->where([['deleted_at' , '=' , NULL]])->paginate(20); 
    // return DB::table('tyre24s')->where([['deleted_at' , '=' , NULL]])->select('tyre_response')->get();    
  }
  /*End*/
  
   public static function save_tyres_response($max_tyre_size , $tyre_response){
     $uid = Auth::user()->id;
     $created_at = $updated_at = date('Y-m-d h:i:s');
     $queries = '';
     foreach($tyre_response->items as $tyre){
       //echo "<pre>";
       //print_r($tyre);exit;
        $uniqueKey = str_replace(" " , "" ,  $max_tyre_size).$tyre->itemId;
        $text_de = json_encode($tyre->text_de);
        $date_de = json_encode($tyre->date_de);
        $source_de = json_encode($tyre->source_de);
        $feedback_de = json_encode($tyre->feedback_de);
        $itemText =  json_encode($tyre->itemText);
        $itemDate  = json_encode($tyre->itemDate);
        $itemSource  = json_encode($tyre->itemSource);
        $shortFeedback  = json_encode($tyre->shortFeedback);
        $manufacturer_item_number  = json_encode($tyre->manufacturer_item_number);
        $description  = \DB::connection()->getPdo()->quote($tyre->description);
        $description1  = \DB::connection()->getPdo()->quote($tyre->description1);
      
      $queries .=  "INSERT INTO `tyre24s`(`id`, `user_id`, `tyre_max_size`, `itemId` , `description` , `description1`, `price` , `org_price` , `stock` , `kbprice` , `discount` , `text_de` , `pic` , `pic_t24` , `date_de` , `source_de` , `feedback_de` , `itemText` , `itemDate` , `itemSource` , `shortFeedback` , `pr_description`, `manufacturer_description` , `type` , `matchcode` , `ean_number` , `manufacturer_item_number` , `imageUrl` ,`weight`, `ownStock` , `wholesalerArticleNo` , `is3PMSF`, `unique_id`, `created_at` , `updated_at`) VALUES (null ,'$uid','$max_tyre_size','$tyre->itemId', '$description', '$description1', '$tyre->price' , '$tyre->org_price' , 
      '$tyre->stock' , 
      '$tyre->kbprice' , '$tyre->discount' , 
      '$text_de' , '$tyre->pic' , '$tyre->pic_t24','$date_de' ,'$source_de' , '$feedback_de' , '$itemText', '$itemDate', '$itemSource' , '$shortFeedback', 
      '$tyre->pr_description',
       '$tyre->manufacturer_description' , '$tyre->type' , 
       '$tyre->matchcode' , '$tyre->ean_number' ,
        '$manufacturer_item_number' , '$tyre->imageUrl' , '$tyre->weight' , '$tyre->ownStock' , '$tyre->wholesalerArticleNo' , '$tyre->is3PMSF','$uniqueKey','$created_at','$updated_at') 
      ON DUPLICATE KEY UPDATE description='$tyre->description',
        price='$tyre->price',
        org_price='$tyre->org_price',  
        stock='$tyre->stock',
        kbprice='$tyre->kbprice',
        discount='$tyre->discount', 
        text_de='$text_de', 
        pic='$tyre->pic',
        pic_t24='$tyre->pic_t24' ,date_de='$date_de' ,source_de='$source_de',feedback_de='$feedback_de' ,itemText='$itemText' ,itemDate='$itemDate' ,itemSource='$itemSource' ,shortFeedback='$shortFeedback' ,pr_description='$tyre->pic' ,manufacturer_description='$tyre->pic' ,type='$tyre->pic' ,matchcode='$tyre->matchcode' ,ean_number='$tyre->ean_number',manufacturer_item_number='$manufacturer_item_number', imageUrl='$tyre->imageUrl' , weight='$tyre->weight',ownStock='$tyre->ownStock',wholesalerArticleNo='$tyre->wholesalerArticleNo',is3PMSF='$tyre->is3PMSF';\n"; 
    return $queries;
    }
       
      //return CustomDatabase::custom_insertOrUpdate($queries);  
  }


  /*Get All Tyres function */
  public static function get_tyre(){
    return Tyre24::where([['deleted_at' , '=' , NULL] , ['type_status' , '=' , 1]])->orderBy('created_at' , 'DESC')->paginate(20);    
  }
  /*End*/
  public static function get_tyre_info($input ,$tyre_type,$limit,$request){
    $total_limit=$limit+10;
  	//return Tyre::where('width' , $input['width'])->where('aspect_ratio',$input['aspect_ratio'])->where('rim_diameter',$input['rim_diameter'])->get();
     return Tyre24::select('*')->where([['tyre_max_size','=',$input['search_string']]])
                               ->whereIn('speed_index',[$request->speed_index])->whereIn('type',[$tyre_type])->offset($limit)->limit(10)->get();
	
  }
  
  public static function get_min_max_seller_price(){
    $price_arr = [];
    $price_arr[] = Tyre24::min('seller_price');
    $price_arr[] = Tyre24::max('seller_price');
    return $price_arr;
  }


  public static function get_min_max_price(){
    $price_arr = [];
    $tyres_list = Tyre24::all();
    foreach($tyres_list as $tyre){
       $response = json_decode($tyre->tyre_response);
       $tyre->price = $response->price;
    }
    $price_arr[] = $tyres_list->min('price');
    $price_arr[] = $tyres_list->max('price');
    return $price_arr;
  }


  
  public static function tyre_list($request , $con = 0){
    $skip = 0; 
    $take = 10;
    $vhicle_tyre_type_arr = $season_type_arr = [];
    if(!empty($request->limit)){   $skip = $request->limit;  }
    if(count($request->all()) > 0){
      $price_order_status = ($request->price_level == 1) ? 'ASC' : 'DESC';
      $speed_index_arr = $diameter_arr  = $width_arr =  $aspect_ratio_arr = $price_arr =  $season_type_arr = $brand_arr =  $vhicle_tyre_type_arr = []; 
      /*manage vhicle type*/
       if(!empty($request->vehicle_tyre_type)){ 
          $vehicle_tyre_type = explode(',' , $request->vehicle_tyre_type);
          $vhicle_tyre_type = DB::table('master_tyre_measurements')->wherein('id' , $vehicle_tyre_type)->get();
          if($vhicle_tyre_type->count() > 0){
              foreach($vhicle_tyre_type as $tyre_type){
                 $vhicle_type = json_decode($tyre_type->code);
                  if(count($vhicle_type)  > 0){
                    foreach($vhicle_type as $key=>$value){
                      $vhicle_tyre_type_arr[] = $value;
                    }
                  }
              }
            }
      }
      /*End*/
      /*Season type*/
      if(!empty($request->season_type)){ 
         $season_tyre_type = explode(',' , $request->season_type);
         $sped_index_response = DB::table('master_tyre_measurements')->wherein('id' , $season_tyre_type)->get();
          if($sped_index_response->count() > 0){
            $season_type_arr = $sped_index_response->pluck('code2')->all();
          }
         $vhicle_tyre_type_arr = array_merge($vhicle_tyre_type_arr ,$season_type_arr);
        }
      /*End*/
      /*Speed index manage*/
      if(!empty($request->speed_index)){ 
        $speed_index = explode(',' , $request->speed_index);
        $sped_index_response = DB::table('master_tyre_measurements')->wherein('id' , $speed_index)->get();
        if($sped_index_response->count() > 0){
          $speed_index_arr = $sped_index_response->pluck('name')->all();
        }
      }
      /*End*/
        DB::enableQueryLog();
       //DB::raw('AVG(feedback.rating) as ratings_average')
       //print_r($request->search_string);exit;
        $query =  Tyre24::select('tyre24s.*');
                   //$query->leftjoin('feedback','feedback.products_id' , '=', 'tyre24s.id')->where([['feedback.type' , '=' , 2]]);
                   //->groupBy('tyre24s.id'); 
                    if(!empty($request->favourite)){
                      $tyre_ids = DB::table('user_wish_lists')->where([['user_id' , '=' , $request->user_id] , ['wishlist_type' , '=' , 1] , ['product_type' , '=', 2] , ['deleted_at' , '=' , NULL]])->get();
                      if($tyre_ids->count() > 0){
                        $tyre_id_arr = $tyre_ids->pluck('product_id')->all();
                      }
                      $query->whereIn('tyre24s.id',$tyre_id_arr);
                    }
                    else{
                        $query->where([['tyre_max_size','=',(int) $request->search_string]]);
                    }
                  if(count($vhicle_tyre_type_arr) > 0){ $query->WhereIn('tyre24s.type' , $vhicle_tyre_type_arr);   }
                  if(count($speed_index_arr) > 0){ $query->WhereIn ('speed_index' , $speed_index_arr);   }
                  if(!empty($request->brand)){ 
                    $brand_arr = explode(',' , $request->brand);
                    $query->WhereIn('tyre_response->manufacturer_description' , $brand_arr);  
                  }
                  if(!empty($request->price_range)){ 
                    $price_arr = explode(',' , $request->price_range);
                    $query->WhereBetween('seller_price' , [(int) trim($price_arr[0] ) , (int)trim($price_arr[1])]); 
                  }
        $query->orderBy('seller_price' , $price_order_status);
        $query->offset($skip);
        $query->limit($take); 
        $tyres = $query->get();
        //print_r(DB::getQueryLog());exit; 
      /*   echo "<pre>";
        print_r($tyres);exit;   */
        if($tyres->count() > 0){
          return json_encode(['status'=>200 , 'response'=>$tyres]);                        
        }
        else{
          return json_encode(['status'=>100]); 
        }
    }
    $tyres =   Tyre24::select('*')->orderBy('seller_price' , 'DESC')
                              ->skip($skip)
                              ->take($take)
                              ->get();
   return json_encode(['status'=>200 , 'response'=>$tyres]);                 
                            
  }

  
  
  public static function get_Tyre_measurment($width ,  $aspect_ratio , $rim_diameter , $tyre_type_arr ,$limit,$request = NULL){
    $speed_index = explode(',', $request->speed_index);
    if(!empty($request->price_range)){
     $price_arr = explode(',', $request->price_range);
        return  Tyre24::select('*')
                   ->whereIn('type' , $tyre_type_arr) 
                   ->whereIn('speed_index',$speed_index)
                   ->WhereBetween('tyre_response->price',[(int)trim($price_arr[0]),(int)trim($price_arr[1])])
                   ->where(function ($query) use($width,$aspect_ratio,$rim_diameter) {
                                 $query->whereBetween('max_width', $width)
                                       ->orWhereBetween('max_aspect_ratio',$aspect_ratio)
                                       ->orWhereBetween('max_diameter' ,$rim_diameter);
                             })
                   ->offset($limit)->limit(10)->get();
      // print_r(DB::getQueryLog()); die;
    }else{
      return  Tyre24::select('*')
                   ->whereIn('type' , $tyre_type_arr )  
                   ->where(function ($query) use($width , $aspect_ratio , $rim_diameter) {
                               $query->whereBetween('max_width', $width)
                                     ->orWhereBetween('max_aspect_ratio' , $aspect_ratio)
                                     ->orWhereBetween('max_diameter' , $rim_diameter);
                           })
                 ->offset($limit)->limit(10)->get();
    }
   }  

	public static function get_Tyre24_detail($item_id){
		return DB::table('tyre24_details')->where([['tyre24s_itemId' , '=' , $item_id]])
					->first();
	}
   
   
   /*Save Tyre response custom script start*/
   /*public static function save_tyre_response($request){
    $for_pair = NULL;
    if(!empty($request->for_pair)){
       $for_pair = $request->for_pair;
      }
      return Tyre24::where([['id' , '=' , $request->tyres_id]])
         ->update([
                   'pair'=>$for_pair,
                   'our_description'=>$request->our_tyre_description , 
                   'seller_price'=>$request->seller_price ,
                   'quantity'=>$request->quantity, 
                   'tax'=>$request->tax, 
                   'tax_value'=>$request->tax_value, 
                   'stock_warning'=>$request->stock_warning, 
                   'unit'=>$request->unit ,
                   'meta_key_title'=>$request->meta_title,
                   'meta_key_word'=>$request->meta_keywords,
                   'runflat'=>$request->run_flat,
                   'reinforced'=>$request->reinforced,
                   'tyre_response->is3PMSF'=>$request->is3PMSF,
                   'tyre_response->weight'=>$request->weight,
                   'tyre_response->price'=>$request->tyre24_price,
                   'tyre_response->manufacturer_description'=>$request->manufacturer_description,
                   'tyre_response->wholesalerArticleNo'=>$request->whole_saller_article_id,
                   'tyre_response->pr_description'=>$request->pr_description,
                   'tyre_response->description1'=>$request->tyre_description_1,
                   'tyre_response->description'=>$request->tyre24_description,
                   'tyre_response->ean_number'=>$request->ean_number,
                   'tyre_response->matchcode'=>$request->matchcode,
          ]);
    }*/
    public static function save_tyre_response($request){
       $for_pair = NULL;
        if(!empty($request->for_pair)){ $for_pair = $request->for_pair; }
        $response =  DB::table('tyre24s')->where([['id','=',$request->tyres_id]])->update([
                'pair'=>$for_pair,
                'vehicle_tyre_type'=>$request->tyre_type,
                'season_tyre_type'=>$request->season_tyre_type,
                'load_speed_index'=>$request->load_index, 
                'speed_index'=>$request->speed_index,
                'our_description'=>$request->our_tyre_description , 
                'seller_price'=>$request->seller_price ,
                'quantity'=>$request->quantity, 
                'tax'=>$request->tax, 
                'tax_value'=>$request->tax_value, 
                'stock_warning'=>$request->stock_warning, 
                'substract_stock'=>$request->substract_stock,
                'unit'=>$request->unit ,
                'meta_key_title'=>$request->meta_title,
                'meta_key_word'=>$request->meta_keywords,
                'runflat'=>$request->run_flat,
                'peak_mountain_snowflake'=>$request->peak_mountain_snowflake,
                'reinforced'=>$request->reinforced,
                // 'tyre_response->is3PMSF'=>$request->is3PMSF,
                'tyre_response->is3PMSF'=>$request->peak_mountain_snowflake,
                'tyre_response->weight'=>$request->weight,
                'tyre_response->price'=>$request->tyre24_price,
                'tyre_response->manufacturer_description'=>$request->manufacturer_description,
                'tyre_response->wholesalerArticleNo'=>$request->whole_saller_article_id,
                'tyre_response->pr_description'=>$request->pr_description,
                'tyre_response->description1'=>$request->tyre_description_1,
                'tyre_response->description'=>$request->tyre24_description,
                'tyre_response->ean_number'=>$request->ean_number,
                'tyre_response->matchcode'=>$request->matchcode,
                'status'=>$request->products_status,
                'updated_at'=>date('Y-m-d h:i:s')
            ]);
	    if($response){
            /*Tyre detail*/
      $tyre = Tyre24::find($request->tyres_id);
      if($tyre->type_status == 1){ $where_clause = ['tyre24s_itemId'=>$tyre->itemId]; }
      else if($tyre->type_status == 2){ $where_clause = ['tyre24_id'=>$tyre->id]; }
      $tyre_detail = sHelper::get_tyre_detail($tyre);
      if($tyre_detail != NULL){
        if($tyre != NULL){
                    $update_arr_2 = ['tyre24_id'=>$request->tyres_id, 
                                      'tyre24s_itemId'=>$request->tyre_item_id , 
                                      'rolling_resistance'=>$request->rolling_resistance , 
                                      'noise_db'=>$request->noise_db , 
                                      'tyre_class'=>$request->tyre_class , 
                                      'tyre_detail_response->price'=>$request->tyre24_price, 
                                      'tyre_detail_response->weight'=>$request->weight, 
                                      'tyre_detail_response->is3PMSF'=>$request->peak_mountain_snowflake, 
                                      'tyre_detail_response->wetGrip'=>$request->wet_grip, 
                                      'tyre_detail_response->matchcode'=>$request->matchcode, 
                                      'tyre_detail_response->org_price'=>$request->tyre24_price, 
                                      'tyre_detail_response->ean_number'=>$request->ean_number, 
                                      'tyre_detail_response->description'=>$request->tyre24_description, 
                                      'tyre_detail_response->description1'=>$request->tyre_description_1, 
                                      'tyre_detail_response->pr_description'=>$request->pr_description, 
                                      'tyre_detail_response->extRollingNoiseDb'=>$request->noise_db,
                                      'tyre_detail_response->rollingResistance'=>$request->rolling_resistance,
                                      'tyre_detail_response->tireClass'=>$request->tyre_class,
                                      'tyre_detail_response->wholesalerArticleNo'=>$request->whole_saller_article_id, 
                                      'tyre_detail_response->manufacturer_description'=>$request->manufacturer_description, 
                                      'updated_at'=>date('Y-m-d h:i:s')]; 
                    return DB::table('tyre24_details')->where($where_clause)->update($update_arr_2);
            }
        }
      else{
          $tyre_detail_response = json_encode(['id'=>'' , 'type'=>$request->tyre_type, 'price'=>$request->tyre24_price, 'stock'=>1 , 
          'weight'=>$request->weight, 'date_de'=>'' , 'is3PMSF'=>$request->peak_mountain_snowflake , 'pic_t24'=>'' , 'text_de'=>'','wetGrip'=>$request->wet_grip, 'imageUrl'=>'' , 'itemDate'=>'' , 'itemText'=>'' , 'matchcode'=>$request->matchcode, 
          'org_price'=>$request->tyre24_price, 'source_de'=>'' ,'tireClass'=>$request->tyre_class ,'ean_number'=>$request->ean_number , 
          'itemSource'=>'' , 'description'=>$request->tyre24_description , 'description1'=>$request->tyre_description_1,
            'longFeedback'=>'' , 'wholesalerId'=>'' , 'shortFeedback'=>'' , 'pr_description'=>$request->pr_description , 
            'extRollingNoise'=>'--' , 'longFeedback_de'=>'' ,'shortFeedback_de'=>'' , 'extRollingNoiseDb'=>$request->noise_db , 'rollingResistance'=>$request->rolling_resistance , 'wholesalerArticleNo'=>$request->whole_saller_article_id, 'manufacturer_description'=>$request->manufacturer_description,'manufacturer_item_number'=>'']);
          $insert_arr_2 = ['tyre24_id'=>$request->tyres_id , 'rolling_resistance' => $request->rolling_resistance,'noise_db' => $request->noise_db, 'tyre_class' => $request->tyre_class ,'tyre_detail_response'=>$tyre_detail_response,'unique_id'=>$request->tyres_id.uniqid() , 'created_at'=>date('Y-m-d h:i:s') , 'updated_at'=>date('Y-m-d h:i:s')]; 
          return Tyre24_details::create($insert_arr_2);
        }  		
			/*End*/
        }
    }
    /*End*/
  
  
    public static function get_tyres_list($width , $aspect_ration , $diameter){
      $search_string = $width.$aspect_ration.$diameter;
      return Tyre24::where([['tyre_max_size' ,'=' , $search_string]])->get();
    }

    
	 public static function  get_all_min_max(){
		return DB::select(DB::raw("select MIN(max_width) as min_width ,MAX(max_width) as max_width, MIN(max_diameter) as min_diameter ,MAX(max_diameter) as max_diameter, MIN(max_aspect_ratio) as min_aspect_ratio, MAX(max_aspect_ratio) as max_aspect_ratio  from tyre24s WHERE max_diameter NOT LIKE '%[a-z]%' OR max_width NOT LIKE '%[a-z]%' OR max_aspect_ratio NOT LIKE '%[a-z]%'"));
	 }
	 
	  
	 public static function get_Tyre_id($tyre_id){
		return Tyre24::select('*')->where('id',$tyre_id)->first();
			 
	 }
   public static function get_tyre_details($tyre_id) {
        return Tyre24::select('tyre_response')->where('id',$tyre_id)->first();
    }

   
}
