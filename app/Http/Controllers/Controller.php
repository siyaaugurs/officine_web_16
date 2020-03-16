<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Session;
use App\VendorDetails;
use App\KnpPincodeModel;
use App\Shop_categoryModel;
use App\CategoryModel;
use App\Products;
use App\User;
use App\ProductsImageModel;
use App\VservicesModel;
use App\VendorShopImage;
use App\vendorbusinesDetails;
use App\VendorPostAds;
use App\Order;
use App\OrderDetails;
use App\ShopSetting;
use App\ProductAttribueTable;
use App\Advertisement;
use App\Ngo_model;
use App\Matrimonial_model;
use Image; 
use App\AdvertisementRecord;
use App\VendorCatAuthority;
use Intervention\Image\Exception\NotReadableException;
use Auth;
use App\KnpShopImage;
use Mail;
class Controller extends BaseController{
	
    use AuthorizesRequests, ValidatesRequests;
	
	
	public $imageArr = array("jpeg" , "png" , "jpg" , "JPEG" , "PNG" ,"JPG" ,'pdf' , 'PDF' );
	public $image_ext = array("jpeg" , "png" , "jpg" , "JPEG" , "PNG" ,"JPG" );
	public $category_type2 = ["A" => "Motorcycle","B" => "Car", "C" => "Truck"];
	public $category_type = [["name" => "Motorcycle", "code" => "A"], ["name" => "Car", "code" => "B"], ["name" => "Truck", "code" => "C"]];
	public $tyre_type_arr = ["s"=>"Summer tyre" , "w"=>"Winter tyre" , "m"=>"2-Wheel/Quad tyre", "g"=>"All-season tyre" , "o"=>"Off-road tyre", "i" => "Truck tyre"]; 
	public $tyre_type = [["name" => "Summer tyre", "code" => "s"], ["name" => "Winter tyre", "code" => "w"], ["name" => "2-Wheel/Quad tyre", "code" => "m"], ["name" => "All-season tyre", "code" => "g"], ["name" => "Off-road tyre", "code" => "o"], ["name" => "Truck tyre", "code" => "i"]];
	public $support_complain_type = ['1' =>'Products Complain' , '2' =>'Service Complain'];
	public $support_complain_type_app = [['code'=>'1' , 'name'=>'Products Complain'] , ['code'=>'2' , 'name'=>'Service Complain']];
	
	
    public function return_ticket_type($ticket_type){
		if(array_key_exists($ticket_type , $this->support_complain_type))
		  return $this->support_complain_type[$ticket_type];
		else return NULL;  
	}
	
	public function upload_profile_image($request){
		$image_path = public_path('storage/profile_image/');
		if(!is_dir($image_path)){ mkdir($image_path, 0755 , true); }
		 if(!empty($request->customer_profile)){
			  $fileName = md5(time().uniqid()).".".$request->file('customer_profile')->getClientOriginalExtension();
		      $extension = $request->file('customer_profile')->getClientOriginalExtension();
		      if(in_array($extension , $this->image_ext)){
				   $request->file('customer_profile')->move($image_path , $fileName);
			     return $fileName;
				}
			  else{ return 111; }	
		   }
		 else{ return $request->customer_profile_image;  }  
	 }
	 
	public function upload_rim_image($request){
		$group_img_path = public_path('storage/rim_images/');
		if(!is_dir($group_img_path)){ mkdir($group_img_path, 0755 , true); }
		if(!empty($request->rim_image)){
			$imageArr = [];
			foreach($request->file("rim_image") as $image) {
				$ext = $image->getClientOriginalExtension();
				 if(in_array($ext , $this->image_ext)) {
					$file_name = md5(microtime().uniqid().rand(9 , 9999)).".".$image->getClientOriginalExtension();
					$image->move($group_img_path  , $file_name);
					$imageArr[] = $file_name;	
				  }
				 else continue;	
			  }
			return $imageArr;	
		 } 
	 } 
 
	public function upload_tyre_image($request){
		$group_img_path = public_path('storage/tyre_images/');
		if(!is_dir($group_img_path)){ mkdir($group_img_path, 0755 , true); }
		if(!empty($request->tyre_image)){
			$imageArr = [];
			foreach($request->file("tyre_image") as $image) {
				$ext = $image->getClientOriginalExtension();
				 if(in_array($ext , $this->image_ext)) {
					$file_name = md5(microtime().uniqid().rand(9 , 9999)).".".$image->getClientOriginalExtension();
					$image->move($group_img_path  , $file_name);
					$imageArr[] = $file_name;	
				  }
				 else continue;	
			  }
			return $imageArr;	
		 } else { return $request->cat_file_name_copy;  }  
	 }  
	 
	public function upload_tyre_label_image($request){
		$group_img_path = public_path('storage/tyre_images/');
		if(!is_dir($group_img_path)){ mkdir($group_img_path, 0755 , true); }
		if(!empty($request->tyre_label_image)){
			$imageArr = [];
			foreach($request->file("tyre_label_image") as $image) {
				$ext = $image->getClientOriginalExtension();
				 if(in_array($ext , $this->image_ext)) {
					$file_name = md5(microtime().uniqid().rand(9 , 9999)).".".$image->getClientOriginalExtension();
					$image->move($group_img_path  , $file_name);
					$imageArr[] = $file_name;	
				  }
				 else continue;	
			  }
			return $imageArr;	
		 } else { return $request->cat_file_name_copy;  }  
	}
	 
	 public function upload_multiple_image($request){
		$group_img_path = public_path('storage/group_image/');
	    if(!is_dir($group_img_path)){ mkdir($group_img_path, 0755 , true); }
		 if(!empty($request->images)){
			  $imageArr = [];
			  foreach($request->file("images") as $image) {
				  $ext = $image->getClientOriginalExtension();
				   if(in_array($ext , $this->image_ext)) {
					  $file_name = md5(microtime().uniqid().rand(9 , 9999)).".".$image->getClientOriginalExtension();
					  $image->move($group_img_path  , $file_name);
				      $imageArr[] = $file_name;	
					}
				   else continue;	
				}
			  return $imageArr;	
		   } else { return $request->cat_file_name_copy;  }  
     }
	public function upload_brand_logo_image($request){
		$brand_logo_image_path = public_path('storage/brand_logo_image/');
	    if(!is_dir($brand_logo_image_path)){ mkdir($brand_logo_image_path, 0755 , true); }
		 if(!empty($request->images)){
			  $fileName = md5(time().uniqid()).".".$request->file('images')->getClientOriginalExtension();
		      $extension = $request->file('images')->getClientOriginalExtension();
		      if(in_array($extension , $this->image_ext)){
				   $request->file('images')->move($brand_logo_image_path , $fileName);
			     return $fileName;
				}
			  else{ return 111; }
		   } else { return $request->cat_file_name_copy;  }  
     }
	public function upload_pic($request){
	    $car_pic_path = public_path('carlogo/');
		if(!is_dir($car_pic_path)){ mkdir($car_pic_path, 0755 , true); }
		 if(!empty($request->image)){
			  $fileName = md5(time().uniqid()).".".$request->file('image')->getClientOriginalExtension();
		      $extension = $request->file('image')->getClientOriginalExtension();
		      if(in_array($extension , $this->image_ext)){
				     $request->file('image')->move($car_pic_path , $fileName);
			       return $fileName;
				}
			  else{ return 111; }	
		   }
		 else{ return $request->image;  }  
	 }
	 
	 public function upload_products_image($request){
		$image_path = public_path('storage/products_image/');;
	    if(!is_dir($image_path)){ mkdir($image_path, 0755 , true); }
		 if(!empty($request->products_gallery_image)){
			  $imageArr = [];
			  foreach($request->file("products_gallery_image") as $image){
				  $ext = $image->getClientOriginalExtension();
				   if(in_array($ext , $this->image_ext)){
					  $file_name = md5(microtime().uniqid().rand(9 , 9999)).".".$image->getClientOriginalExtension();
					  $image->move($image_path  , $file_name);
				      $imageArr[] = $file_name;	
					}
				   else continue;	
				}
			  return $imageArr;	
		   }
		 else{ return 404;  }  
	 }
 

    public function upload_address_proof($request){
		$adrs_path = public_path('storage/business_details/');
		if(!is_dir($adrs_path)){ mkdir($adrs_path, 0755 , true); }
		 if(!empty($request->address_proof)){
			  $fileName = md5(time().uniqid()).".".$request->file('address_proof')->getClientOriginalExtension();
		      $extension = $request->file('address_proof')->getClientOriginalExtension();
		      if(in_array($extension , $this->imageArr)){
				   $request->file('address_proof')->move($adrs_path , $fileName);
			       return $fileName;
				}
			  else{ return 111; }	
		   }
		 else{ return $request->address_proof_copy;  }  
	 }
	 
	 public function upload_reg_proof($request){
		$reg_path = public_path('storage/business_details/');
		if(!is_dir($reg_path)){ mkdir($reg_path, 0755 , true); }
		 if(!empty($request->registration_proof)){
			  $fileName = md5(time().uniqid()).".".$request->file('registration_proof')->getClientOriginalExtension();
		      $extension = $request->file('registration_proof')->getClientOriginalExtension();
		      if(in_array($extension , $this->imageArr)){
				   $request->file('registration_proof')->move($reg_path , $fileName);
			       return $fileName;
				}
			  else{ return 111; }	
		   }
		 else{ return $request->registration_proof_copy;  }  
	 }
	

    public function upload_category_image($request){
		$image_path = public_path('storage/category/');;
	    if(!is_dir($image_path)){ mkdir($image_path, 0755 , true); }
		 if(!empty($request->cat_file_name)){
			  $imageArr = [];
			  foreach($request->file("cat_file_name") as $image){
				  $ext = $image->getClientOriginalExtension();
				   if(in_array($ext , $this->image_ext)){
					  $file_name = md5(microtime().uniqid().rand(9 , 9999)).".".$image->getClientOriginalExtension();
					  $image->move($image_path  , $file_name);
				      $imageArr[] = $file_name;	
					}
				   else continue;	
				}
			  return $imageArr;	
		   }
		 else{ return $request->cat_file_name_copy;  }  
	 }
	 public function upload_advertising_image($request){
		$image_path = public_path('storage/advertising/');;
	    if(!is_dir($image_path)){ mkdir($image_path, 0755 , true); }
		 if(!empty($request->cat_file_name)){
			  $imageArr = [];
			  foreach($request->file("cat_file_name") as $image){
				  $ext = $image->getClientOriginalExtension();
				   if(in_array($ext , $this->image_ext)){
					  $file_name = md5(microtime().uniqid().rand(9 , 9999)).".".$image->getClientOriginalExtension();
					  $image->move($image_path  , $file_name);
				      $imageArr[] = $file_name;	
					}
				   else continue;	
				}
			  return $imageArr;	
		   }
		 else{ return $request->cat_file_name_copy;  }  
	}
	 
	public function get_image_url(){
	 if(!empty(Auth::user()->profile_image)){
	     $image = Auth::user()->profile_image;
	     if(file_exists("storage/$image")){
	        return url('storage/'.Auth::user()->profile_image); 
	     }
	     else{
	        if(Auth::user()->sex == "M")
				 return url('storage/male.png');
			else
			    return url('storage/female.png');
	     }
		  
	 }	
	 else{
			if(Auth::user()->sex == "M")
				 return url('storage/male.png');
			else
			    return url('storage/female.png');	 
	 }
	}
	
	
      public function upload_coupon_image($request){
		$copon_path = public_path('storage/coupon_image/');
		if(!is_dir($copon_path)){ mkdir($copon_path, 0755 , true); }
		 if(!empty($request->coupon_image)){
			  $fileName = md5(time().uniqid()).".".$request->file('coupon_image')->getClientOriginalExtension();
		      $extension = $request->file('coupon_image')->getClientOriginalExtension();
		      if(in_array($extension , $this->image_ext)){
				   $request->file('coupon_image')->move($copon_path , $fileName);
			     return $fileName;
				}
			  else{ return 111; }	
		   }
		 else{ return $request->coupon_image_copy;  }  
	 }
	
	public function upload_business_file($request){
		$image_path = public_path('uploadFiles/vendorbusiness_file/');;
	    if(!is_dir($image_path)){ mkdir($image_path, 0755 , true); }
		 if($request->hasFile($request->input('business_file'))){
			  $fileName = md5(time()).".".$request->file('business_file')->getClientOriginalExtension();
		      $extension = $request->file('business_file')->getClientOriginalExtension();
		      if($extension =="pdf" || $extension=="jpg"){
				   $request->file('business_file')->move($image_path  , $fileName);
			       return $fileName;
				} 	
			  else{ return 111; }	
		   }
		 else{ return 112;  }  
	 }
	 
	 
	 public function workshop_image($request){
	    $image_path = public_path('storage/workshop/');
	   if(!is_dir($image_path)){ mkdir($image_path, 0755 , true); }
		 if(!empty($request->file("images"))){
			  $imageArr = array();
			  foreach($request->file("images") as $image){
				  $file_name = md5(microtime().uniqid().rand(9 , 9999)).".".$image->getClientOriginalExtension();
				  $image->move($image_path  , $file_name);
				  Session::push('image_grid', $file_name);
				  //Session::push('image_grid', $file_name);
				  //$imageArr[] = $file_name;	
				}
			  return $imageArr;
			  // return implode("@" , $imageArr);	
		   }
		 else{ return 0;  }  
	 }
	 
	 /*
	 public function social_post_image($request){
	    $image_path = public_path('storage/');
	   if(!is_dir($image_path)){ mkdir($image_path, 0755 , true); }
		 if(!empty($request->file("images"))){
			  $imageArr = array();
			  foreach($request->file("images") as $image){
				  $file_name = md5(microtime().uniqid().rand(9 , 9999)).".".$image->getClientOriginalExtension();
				  $image->move($image_path  , $file_name);
				  $imageArr[] = $file_name;	
				}
			   return implode("@" , $imageArr);	
		   }
		 else{ return 0;  }  
	 }
	 */
	 
	 
	public function ngos_image($formData){
	  $thumbImage_path = public_path('uploadFiles/ngo/cover');
	  $original_image_path = public_path('uploadFiles/ngo/cover/original');
	  if(!is_dir($thumbImage_path)){ mkdir($thumbImage_path, 0755, true); }
	  if(!is_dir($original_image_path)){ mkdir($original_image_path, 0755, true); }
	  if(!empty($formData['image'])){
		  if(!empty($formData['imageCopy'])){
			 $filethumbPath = $thumbImage_path."/".$formData['imageCopy'];
			 $fileoriginal = $original_image_path."/".$formData['imageCopy'];			 
			 if(file_exists($fileoriginal )){ unlink($fileoriginal ); }
			 if(file_exists($filethumbPath)){ unlink($filethumbPath); }
			}
		  $fileName = md5(time()).".".$formData['image']->getClientOriginalExtension();
		  $extension = $formData['image']->getClientOriginalExtension();
		   if($extension =="jpeg" || $extension=="png" || $extension=="jpg"){ 	
		     $eventthumbImage = Image::make($formData['image']->getRealPath())->resize(361 , 220);
			 $eventthumbImage->save($thumbImage_path.'/'.$fileName , 80);
			 //$fullImage = Image::make($formData['image']->getRealPath())->resize(350,200);
			 $formData['image']->move($original_image_path  , $fileName);
			 return $fileName;
			}
		   else{
			  return 100;
			}	
		}
	   else{
		  return $formData['imageCopy'];
		}	 
	}
	
	
	public function ngo_cover_image($request){
	  $ngo_imagePath = public_path('uploadFiles/ngo');
	  if(!is_dir($ngo_imagePath)){ mkdir($ngo_imagePath, 0755, true); }
	  	$filename = md5(microtime()).'.'.$request->file('ngo_cover_image')->getClientOriginalExtension();
		if(in_array( $request->file('ngo_cover_image')->getClientOriginalExtension() , $this->imageArr )){
		 $image = Image::make($request->file('ngo_cover_image')->getRealPath())->resize(2000,600);
			 if($image->save($ngo_imagePath.'/'.$filename , 80)){
				 return $filename;
			   }
		 return $filename;
		}
		else{ return 100; }
	}
	
	public function ngo_image($request){
	  $ngo_imagePath = public_path('uploadFiles/ngo');
	  $ngothumb_imagePath = public_path('uploadFiles/ngo/thumb');
	  if(!is_dir($ngo_imagePath)){ mkdir($ngo_imagePath, 0755, true); }
	  if(!is_dir($ngothumb_imagePath)){ mkdir($ngothumb_imagePath, 0755, true); }
	  	$filename = md5(microtime()).'.'.$request->file('ngo_profile')->getClientOriginalExtension();
		if(in_array( $request->file('ngo_profile')->getClientOriginalExtension() , $this->imageArr )){
		   	 $image = Image::make($request->file('ngo_profile')->getRealPath())->resize(500,400);
			 if($image->save($ngothumb_imagePath.'/'.$filename , 80)){
			     $request->file('ngo_profile')->move($ngo_imagePath  , $filename);
				 return $filename;
			   }
		}
		else{ return 100; }
	}
	
	
	public function talentBannerImage($formData){
	    $talent_imagePath = public_path('uploadFiles/talent');
		$talent_imageoriginPath = public_path('uploadFiles/talent/original');
	  if(!is_dir($talent_imagePath)){ mkdir($talent_imagePath, 0755, true); }
	  if(!is_dir($talent_imageoriginPath)){ mkdir($talent_imageoriginPath, 0755, true); }
	  if(!empty($formData['talent_profileImage'])){
		  if(!empty($formData['talent_profileImageCopy'])){
			 $filethumbPath = $talent_imagePath."/".$formData['talent_profileImageCopy'];
			 $fileoriginal = $talent_imageoriginPath."/".$formData['talent_profileImageCopy'];			 
			 if(file_exists($fileoriginal )){ unlink($fileoriginal ); }
			 if(file_exists($filethumbPath)){ unlink($filethumbPath); }
			}
		  $fileName = md5(time()).".".$formData['talent_profileImage']->getClientOriginalExtension();
		  if(in_array($formData['talent_profileImage']->getClientOriginalExtension() , $this->imageArr )){
		     $talentthumbImage = Image::make($formData['talent_profileImage']->getRealPath())->resize(361 , 220);
			 $talentthumbImage->save($talent_imagePath.'/'.$fileName,80);
			 $formData['talent_profileImage']->move($talent_imageoriginPath  , $fileName);
			 return $fileName;
			}
		   else{  return 100; }	
		}
	   else{
		  return $formData['talent_profileImageCopy'];
		}	
	}
	
	 /*
	 public function matrimonial_image ($request){
		$mat_profile_imagePath = public_path('uploadFiles/matrimonial_image');
		$mat_profile_imageoriginPath = public_path('uploadFiles/matrimonial_image/original');
		if(!is_dir($mat_profile_imagePath)){ mkdir($mat_profile_imagePath, 0755, true);  } 
		if(!is_dir($mat_profile_imagePath)){ mkdir($mat_profile_imageoriginPath, 0755, true);  } 
		$filename = md5(microtime()).'.'.$request->file('mat_profileImage')->getClientOriginalExtension();
		if(in_array( $request->file('mat_profileImage')->getClientOriginalExtension() , $this->imageArr )){
		   	 $image = Image::make($request->file('mat_profileImage')->getRealPath())->resize(361,220);
			 if($image->save($mat_profile_imagePath.'/'.$filename , 80)){
			     $request->file('mat_profileImage')->move($mat_profile_imageoriginPath  , $filename);
				 return $filename;
			   }
		}
		else{ return 100; }
	 } */
	
	
	public function send_notifiation($notification_text , $notification_url , $image_url , $receiver_id , $admin_receiver){
           $dataArr = array("id"=>md5(uniqid().time()) , "receiver_id"=>$receiver_id , "post_user_by"=>Auth::user()->id , "admin_receiver"=>$admin_receiver , "image_url"=>$image_url , "notification_text"=>$notification_text , "notification_url"=>$notification_url , "created_at"=>time());
	  return VendorDetails::send_notification($dataArr);
        }
	 
	 public function send_notification_email($dataemailArr , $html_page ){
		   Mail::send('emails.'.$html_page, $dataemailArr, function ($message){
			 $message->from('info@kanpurize.com', 'Kanpurize vendor Registeration');
			 $message->to('vivek.gupta@kanpurize.com')->subject('New Shop Registeration !');
		   }); 
		return TRUE;
	 }
	
	
	public function check_device(){
                $useragent = $_SERVER['HTTP_USER_AGENT'];
		if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge
		|maemo|midp|mmp|netfront|opera m(ob|in)i|palm(
		os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows
		(ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a
		wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r
		|s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1
		u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp(
		i|ip)|hs\-c|ht(c(\-|
		|_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac(
		|\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt(
		|\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg(
		g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-|
		|o|v)|zz)|mt(50|p1|v
		)|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v
		)|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-|
		)|webc|whit|wi(g
		|nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
		 {
		    return "M";
		 }
		else{
	            return "D";
	         } 
                   // header('Location: http://detectmobilebrowser.com/mobile');
         }
         
         public function shopcategoryIcon($request){
	  if(!empty($request->file('categoryIcon'))){
		 $fileName = time().$request->file('categoryIcon')->getClientOriginalName();
		 $imageName = str_replace(" ","-",$fileName);
	     $shopCategoryIconPath = public_path('uploadFiles/shopCategoryIcon');
		 if(!is_dir($shopCategoryIconPath)){ mkdir($shopCategoryIconPath, 0755, true);  }
	     $iconImage = Image::make($request->file('categoryIcon')->getRealPath())->resize(50, 50);
		 $iconImage->save($shopCategoryIconPath.'/'.$imageName,80);
		 return $imageName;
	   }
	  else{
	   	 $imageName = $request->categoryIconcopy; 
	   } 
	}
	
	
	public function upload_gallery_image($request){
	    $fileName = time().$request->file('image')->getClientOriginalName();
		$image = str_replace(" ","-",$fileName);
		$originalImgPath = public_path('uploadFiles/gallery');
		if(!is_dir($originalImgPath)){ mkdir($originalImgPath, 0755, true);  }
		$galleryImage = Image::make($request->file('image')->getRealPath())->resize(365,350);
		$galleryImage->save($originalImgPath.'/'.$image,80);
		return $image;
	}
	
	
	
	public function uploadComplainImage($request){
	  if($request->hasFile($request->input('image'))){
			   $complainImgPath = public_path('uploadFiles/complainFiles');
			   if(!is_dir($complainImgPath)){ mkdir($complainImgPath, 0755, true); }
				$fileName = time().$request->file('image')->getClientOriginalName();
				$image = str_replace(" ","-",$fileName);
				$request->file('image')->move($complainImgPath,$image);
				return $image;
			 }
		   else{ return ""; }	
	}
	
	
	
	public function uploadproductsImage($request){
	   $originalImgPath = public_path('uploadFiles/productsImg');
	   $thumbImgpath = public_path('uploadFiles/thumbsImg');
	   $frontImgPath = public_path('uploadFiles/productsImg/FrontImg');
	    if(!is_dir($originalImgPath)){ mkdir($originalImgPath, 0755, true); }
		if(!is_dir($thumbImgpath)){ mkdir($thumbImgpath, 0755, true); }
		if(!is_dir($frontImgPath)){ mkdir($frontImgPath, 0755, true); }
		 if(!empty($request->file("pImage"))){
		      if(!empty($request->fileCopy)){
				  $firstFile = $originalImgPath."/".$request->fileCopy;
				  $secondFile = $thumbImgpath."/".$request->fileCopy;
				  $thirdFile = $frontImgPath."/".$request->fileCopy;
				   if(file_exists($firstFile)){ unlink($firstFile); }
				   if(file_exists($secondFile)){ unlink($secondFile); }
				   if(file_exists($thirdFile)){ unlink($thirdFile); }
				}
			   $prImage = time().$request->file("pImage")->getClientOriginalName();	
			   $pImage = str_replace(" ","-",$prImage);
			   $thumb_img = Image::make($request->file("pImage")->getRealPath())->resize(80, 68);
			   $thumb_img->save($thumbImgpath.'/'.$pImage,80);
			   //front Image
			   $frontImg = Image::make($request->file("pImage")->getRealPath())->resize(300, 250);
			   $frontImg->save($frontImgPath.'/'.$pImage,80);
			   //originalImage
			   $originalImg = Image::make($request->file("pImage")->getRealPath())->resize(1225, 1000);
			   $originalImg->save($originalImgPath.'/'.$pImage,80);
			  return $pImage;
		   }
		 else{
		     return $request->fileCopy;
		   }  
	 }
	
	
	public function uploadOffersnews($request){
	  $offersNewsPath = public_path('uploadFiles/offersNews'); 
	  if(!is_dir($offersNewsPath)){ mkdir($offersNewsPath, 0755, true); }
	   	 if(!empty($request->file('newsofferImage'))){
			$fileName = md5(time()).".".$request->file('newsofferImage')->getClientOriginalExtension();
	       //$fileName = time().$request->file('newsofferImage')->getClientOriginalName();
		   	$offersNewsImage = Image::make($request->file('newsofferImage')->getRealPath())->resize(400, 200);
		    $offersNewsImage->save($offersNewsPath.'/'.$fileName,80);
		    if(!empty($request->input("imageCopy"))){
			    $previousFile = $offersNewsPath."/".$request->input("imageCopy");
	            if(file_exists($previousFile)){ unlink($previousFile); }
			  } 
			 return $fileName; 
		 }
		else{ 
		  return  $request->input("imageCopy"); 
		 }
	 }
	
	
	public function shopBannerImage($formData){
	    if(!empty($formData['bannerImage']->getClientOriginalName())){
             $fileName = rand(1,9999).$formData['bannerImage']->getClientOriginalName();
			  $ext = $formData['bannerImage']->getClientOriginalExtension();
			   if($ext=="jpg" || $ext=="jpeg" || $ext=="png"){
			       $imageName = str_replace(" ","-",$fileName);
				   $bannerImagePath = public_path('uploadFiles/shopBannerImage');
				  if(!is_dir($bannerImagePath)){ mkdir($bannerImagePath, 0755, true); }
					 $bannerImage = Image::make($formData['bannerImage']->getRealPath())->resize(2000, 600);
					   if($bannerImage->save($bannerImagePath.'/'.$imageName,80)){
						  $upShopprofileImage = $this->editVendorData(array("bannerImage"=>$imageName) , array("id"=>$formData['shopID']));
							if($upShopprofileImage != FALSE){
								  return json_encode(
										array(
										"success"=>"<span style='color:green;'>Banner Image Added Successfully...</span>",
										"vStatus"=>700
										));
								}
							 else{
								return json_encode(
								   array(
										"error"=>"Unexpected Try again..",
										"vStatus"=>500
									  ));
							   }    
						}
			   }
			   else{
			      return json_encode(
								   array(
										"error"=>"Unexpected Try again..",
										"vStatus"=>500
									  ));
			   }
          }
    } 
	
	
	public function saveShopLogoImage($formData){
	   if(!empty($formData['imageName'])){
		     $ext = $formData['frontImage']->getClientOriginalExtension();
			  if($ext=="jpg" || $ext=="jpeg" || $ext=="png"){
				   $fileName = rand(1,9999).$formData['frontImage']->getClientOriginalName();
				   $shopImage = str_replace(" ","-",$fileName);
				   $shopLogopath = public_path('uploadFiles/shopProfileImg');
				    $thumbImgpath = public_path('uploadFiles/shopProfileImg/thumbImg');
					 if(!is_dir($shopLogopath)){ mkdir($shopLogopath, 0755, true); }
		             if(!is_dir($thumbImgpath)){ mkdir($thumbImgpath, 0755, true); }
					  $shopLogo = Image::make($formData['frontImage']->getRealPath())->resize(750, 500);
					  $thumbImage = Image::make($formData['frontImage']->getRealPath())->resize(80,80);
			        if($shopLogo->save($shopLogopath.'/'.$shopImage,80)){
					     if($thumbImage->save($thumbImgpath.'/'.$shopImage,80)){
							$upShopprofileImage = $this->editVendorData(array("imageLogo"=>$shopImage) , array("id"=>$formData['shopID']));
							   if($upShopprofileImage != FALSE){
								   echo"<span style='color:#00cc66;'><strong>image Uploaded ....</strong></span>";
								 }
								else{
								echo"<span style='color:red;'><strong>Unexpected Try again....</strong></span>";	
								} 
							}
						 else{
							   echo"<span style='color:red;'><strong>Unexpected try again....</strong></span>";
							}	
					  }
					else{
					   echo"<span style='color:red;'><strong>Unexpected try again....</strong></span>";
					  }  
					
				}
			  else{
				  echo"<span style='color:red;'>invalid file , file type allowed only  JPG,JPEG,PNG</span>";
				}	
		  }
	   else{
		   echo"<span style='color:red;'>Please Select Your Shop Profile Image...</span>";
		  }	   
	}
	
	
public function saveBusinessDetails($formData){
	  if(KnpPincodeModel::matchPincode(array("pincode"=>$formData['pincode'])) != FALSE){
	   //return $formData;
	   /*--gst file upload start--*/
		$gstFilepath = public_path('uploadFiles/businessDetails/gstFile');
		$signaturePath = public_path('uploadFiles/businessDetails/signature');
		$panCardPath = public_path('uploadFiles/businessDetails/panCard');
		if(!is_dir($gstFilepath)){ mkdir($gstFilepath, 0755, true); }
		if(!is_dir($signaturePath)){ mkdir($signaturePath, 0755, true); }
		if(!is_dir($panCardPath)){ mkdir($panCardPath, 0755, true); }
		/*--gst file upload Start--*/
		if(empty($formData['dontGst'])){
			  if(!empty($formData['gstFile'])){
				   $gstfileName = md5(time()).".".$formData['gstFile']->getClientOriginalExtension(); 
				   $newgstFilepath = $formData['gstFile']->move($gstFilepath , $gstfileName);  
				   $dontGst = 'Y'; 
			   }
			  else{
				  $gstfileName =  $formData['gstfileCopy'];
				  $dontGst = 'Y';
			   }   
		    }
		      else{
		         $gstfileName = $formData['gstfileCopy'];
		         $dontGst = $formData['dontGst']; 
		   }
		 /*--gst file upload End--*/
		 /*--Upload pancard photo start */
		   if(!empty($formData['pancard_photo'])){
			  if(in_array( $formData['pancard_photo']->getClientOriginalExtension() , $this->imageArr )){
			    $paNimageName =  md5(time()).".".$formData['pancard_photo']->getClientOriginalExtension();
				$formData['pancard_photo']->move($panCardPath , $paNimageName); 
			  }
			  else{
			    return json_encode(array("msg"=>'<div class="notice notice-danger"><strong>Wrong , </strong>Invalid Pancard Photo format type . Only support JPEG , PNg , JPG . </div>',
							"vStatus"=>100));
			  }
			 }
		   else{
			   $paNimageName = $formData['pancard_photo_copy'];
			 } 	 
		 /*End*/
		 
			$whereClause = array("id"=>$formData['business_id']);
			$insertData = array("shop_username"=>$formData['shop_username'],"ownerName"=>$formData['bownerName'],"aboutBusiness"=>$formData['aboutBusiness'],"dontgst"=>$dontGst,"gstNumber"=>$formData['gstNumber'],"gstFile"=>$gstfileName,"gstProvisionalID"=>$formData['gstProvisionalID'],"panCardNumber"=>$formData['panNumber'] , "pan_photo"=>$paNimageName ,"address1"=>$formData['address1'],"address2"=>$formData['address2'],"address3"=>$formData['address3'],"pinCode"=>$formData['pincode'],"city"=>$formData['city'],"state"=>$formData['state'],"editDate"=>time(),"cbusinesStatus"=>"Y"); 
			 $updateDataResult = vendorbusinesDetails::editBusinessDetails($insertData,$whereClause); 
			  if($updateDataResult != FALSE){
				  return json_encode(array(
							"msg"=>'<div class="notice notice-success"><strong>Success , </strong> Records Saved Successfully ... </div>',
							"vStatus"=>100
							));
				}
				else{  
				   return json_encode(array("msg"=>"<div class='notice notice-danger'><strong>Wrong , </strong>  Un-expected try again ...</div>",
							"vStatus"=>100
							));
				}	  
		 }
	   else{
		   return json_encode(
							array(
							"msg"=>"<div class='notice notice-danger'><strong>Wrong , </strong> We don't provide service in this Area . </div>",
							"vStatus"=>100));
	     }	  
   }
	
	/*
	public function eventImageUpload($formData){
	use less 
	  $originalImgPath = public_path('uploadFiles/EventImage');
	  $eventThumbImage = public_path('uploadFiles/EventImage/eventhumbImage');
	  if(!empty($formData['image'])){
		  if(!empty($formData['imageCopy'])){
			 $filePath = $originalImgPath."/".$formData['imageCopy'];
			 $filethumbPath = $eventThumbImage."/".$formData['imageCopy'];
			 if(file_exists($filePath)){ unlink($filePath); }
			 if(file_exists($filethumbPath)){ unlink($filethumbPath); }
			}
		  $fileName = time().$formData['image']->getClientOriginalName();
		  $image = str_replace(" ","-",$fileName);
		  $extension = $formData['image']->getClientOriginalExtension();
		   //print_r($extension);exit;
		   if($extension =="jpeg" || $extension=="png" || $extension=="jpg"){ 	
		     $eventthumbImage = Image::make($formData['image']->getRealPath())->resize(361,220);
			 $eventthumbImage->save($eventThumbImage.'/'.$image,80);
			 $fullImage = Image::make($formData['image']->getRealPath())->resize(1024,512);
			 if($fullImage->save($originalImgPath.'/'.$image,80)){
			    return $image;
			  }
			}
		   else{ return 100;
			}	
		}
	   else{
		  return $formData['imageCopy'];
		}	
	} */
	
	
	public function eventImageUpload($formData){
	  $eventThumbImage = public_path('uploadFiles/eventImage/thumb');
	  $original_image_path = public_path('uploadFiles/eventImage');
	  if(!is_dir($eventThumbImage)){ mkdir($eventThumbImage, 0755, true); }
	  if(!is_dir($original_image_path)){ mkdir($original_image_path, 0755, true); }
	  if(!empty($formData['image'])){
		  if(!empty($formData['imageCopy'])){
			 //$filePath = $originalImgPath."/".$formData['imageCopy'];
			 $filethumbPath = $eventThumbImage."/".$formData['imageCopy'];
			 $fileoriginal = $original_image_path."/".$formData['imageCopy'];			 
			 //if(file_exists($filePath)){ unlink($filePath); }
			 if(file_exists($fileoriginal )){ unlink($fileoriginal ); }
			 if(file_exists($filethumbPath)){ unlink($filethumbPath); }
			}
		  $fileName = md5(time()).".".$formData['image']->getClientOriginalExtension();
		  $extension = $formData['image']->getClientOriginalExtension();
		   //print_r($extension);exit;
		   if($extension =="jpeg" || $extension=="png" || $extension=="jpg"){ 	
		     $eventthumbImage = Image::make($formData['image']->getRealPath())->resize(361 , 220);
			 $eventthumbImage->save($eventThumbImage.'/'.$fileName , 80);
			 //$fullImage = Image::make($formData['image']->getRealPath())->resize(350,200);
			 $formData['image']->move($original_image_path  , $fileName);
			 return $fileName;
			}
		   else{
			  return 100;
			}	
		}
	   else{
		  return $formData['imageCopy'];
		}	 
	}
	
	public function postAdvertisementPhoto($formData){
	   $vAdspostPath = public_path('uploadFiles/vendorAdspost');
	   $vthumbsAdspostPath = public_path('uploadFiles/vthumbsAdspost');
		if(!is_dir($vAdspostPath)){ mkdir($vAdspostPath, 0755, true); }
		if(!is_dir($vthumbsAdspostPath)){ mkdir($vthumbsAdspostPath, 0755, true); }
	   if(!empty($formData['postImage'])){
		   if(!empty($formData['imageNameCopy'])){
		     $filePath = $vAdspostPath."/".$formData['imageNameCopy'];
			 $filethumbPath = $vthumbsAdspostPath."/".$formData['imageNameCopy'];
			 if(file_exists($filePath)){ unlink($filePath); }
			 if(file_exists($filethumbPath)){ unlink($filethumbPath); }
		    }
		   $fileName = rand(1,9999).$formData['postImage']->getClientOriginalName();
	       $imageName = str_replace(" ","-",$fileName);
	       $adsRealSize = Image::make($formData['postImage']->getRealPath())->resize(2000, 600);
	       $thumbsAdsRealSize = Image::make($formData['postImage']->getRealPath())->resize(400, 200);
	       $thumbsAdsRealSize->save($vthumbsAdspostPath.'/'.$imageName,80);
		   if($adsRealSize->save($vAdspostPath.'/'.$imageName,80)){
			  return $imageName;
		   }	
		 }
	   else{
		   return $formData['imageNameCopy']; 
		 }	 
	}
	
	public function BlogImageUpload($formData){
	  //$originalImgPath = public_path('uploadFiles/blogImage');
	  $eventThumbImage = public_path('uploadFiles/blogImage/blogImagethumb');
	  $original_image_path = public_path('uploadFiles/blogImage/original_image');
	  //if(!is_dir($originalImgPath)){ mkdir($originalImgPath, 0755, true); }
	  if(!is_dir($eventThumbImage)){ mkdir($eventThumbImage, 0755, true); }
	  if(!is_dir($original_image_path)){ mkdir($original_image_path, 0755, true); }
	  
	  if(!empty($formData['image'])){
		  if(!empty($formData['imageCopy'])){
			 //$filePath = $originalImgPath."/".$formData['imageCopy'];
			 $filethumbPath = $eventThumbImage."/".$formData['imageCopy'];
			 $fileoriginal = $original_image_path."/".$formData['imageCopy'];			 
			 //if(file_exists($filePath)){ unlink($filePath); }
			 if(file_exists($fileoriginal )){ unlink($fileoriginal ); }
			 if(file_exists($filethumbPath)){ unlink($filethumbPath); }
			}
		  $fileName = time().$formData['image']->getClientOriginalName();
		  $image = str_replace(" ","-",$fileName);
		  $extension = $formData['image']->getClientOriginalExtension();
		   //print_r($extension);exit;
		   if($extension =="jpeg" || $extension=="png" || $extension=="jpg"){ 	
		     $eventthumbImage = Image::make($formData['image']->getRealPath())->resize(361 , 220);
			 $eventthumbImage->save($eventThumbImage.'/'.$image,80);
			 //$fullImage = Image::make($formData['image']->getRealPath())->resize(350,200);
			 $formData['image']->move($original_image_path  , $image);
			 return $image;
			}
		   else{
			  return 100;
			}	
		}
	   else{
		  return $formData['imageCopy'];
		}	
	}	
	
	
	public function encrypt($string, $key=5) {
		$result = '';
		for($i=0, $k= strlen($string); $i<$k; $i++) {
			$char = substr($string, $i, 1);
			$keychar = substr($key, ($i % strlen($key))-1, 1);
			$char = chr(ord($char)+ord($keychar));
			$result .= $char;
		}
	    return base64_encode($result);
     }
     
	public function decrypt($string, $key=5) {
	   $result = '';
	   $string = base64_decode($string);
		for($i=0,$k=strlen($string); $i< $k ; $i++) {
			$char = substr($string, $i, 1);
			$keychar = substr($key, ($i % strlen($key))-1, 1);
			$char = chr(ord($char)-ord($keychar));
			$result.=$char;
		}
		return $result;
      }
      
      
      public function share_webshop_url($businessCategory , $username , $shop_name = NULL){
	      $username_share  = base64_encode($username);
		 switch($businessCategory){
		    case 1:
			return url("goods_Shop/$username_share/$shop_name");
			break;
			
			case 2:
			return url("services_shop/$username_share/$shop_name");
			break;
			
			case 3;
			return url("goods_services/$username_share/$shop_name");
			break;
			
			default:
			return FALSE;
		  }
	  }
	  public function upload_notification_pic($request){
	    $Notification = public_path('storage/Notification/');
		if(!is_dir($Notification)){ mkdir($Notification, 0755 , true); }
		 if(!empty($request->file)){
			  $fileName = md5(time().uniqid()).".".$request->file('file')->getClientOriginalExtension();
		      $extension = $request->file('file')->getClientOriginalExtension();
		      if(in_array($extension , $this->image_ext)){
				     $request->file('file')->move($Notification , $fileName);
			       return $fileName;
				}
			  else{ return 111; }	
		   }
		 else{ return $request->file;  }  
	 }
	 public function upload_customer_report_pic($request){
	    $officine_pic = public_path('storage/Officine_Pic/');
		if(!is_dir($officine_pic)){ mkdir($officine_pic, 0755 , true); }
		 if(!empty($request->browse_image)){
			  $fileName = md5(time().uniqid()).".".$request->file('browse_image')->getClientOriginalExtension();
		      $extension = $request->file('browse_image')->getClientOriginalExtension();
		      if(in_array($extension , $this->image_ext)){
				     $request->file('browse_image')->move($officine_pic , $fileName);
			       return $fileName;
				}
			  else{ return 111; }	
		   }
		 else{ return $request->browse_image;  }  
	 }
	 public function upload_images($request){
		$img_path = public_path('storage/');
	    if(!is_dir($img_path)){ mkdir($img_path, 0755 , true); }
		 if(!empty($request->images)){
			  $imageArr = [];
			  foreach($request->file("images") as $image) {
				      $ext = $image->getClientOriginalExtension();
					  $file_name = md5(microtime().uniqid().rand(9 , 9999)).".".$image->getClientOriginalExtension();
					  $image->move($img_path  , $file_name);
				      $imageArr[] = $file_name;	
				}
			  return $imageArr;	
		   } 
     }

     public function upload_feedback_images($request){
		$img_path = public_path('storage/');
	    if(!is_dir($img_path)){ mkdir($img_path, 0755 , true); }
		 if(!empty($request->images)){
			  $imageArr = [];
			  $img = (array)$request->file("images");
		  	foreach($img as $image) {
			      $ext = $image->getClientOriginalExtension();
				  $file_name = md5(microtime().uniqid().rand(9 , 9999)).".".$image->getClientOriginalExtension();
				  $image->move($img_path  , $file_name);
			      $imageArr[] = $file_name;	
			}
			  return $imageArr;	
		   } 
     }
    
	
}