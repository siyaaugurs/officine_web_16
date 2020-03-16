<?php
namespace App\Model;
use App\Model\Kromeda;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserDetails extends Model {

	protected $table = "user_details";
	protected $fillable = ['id', 'user_id', 'revesion_km', 'carMakeName', 'carModelName', 'carVersion', 'addedTo', 'image', 'car_size', 'car_size_status', '', 
	'original_image', 'km_of_cars', 'km_traveled_annually', 'revision_date_km', 'alloy_wheels', 'created_at', 'updated_at', 'number_plate' , 'executed'];
	
	public static $image_ext = array("jpeg", "png", "jpg", "JPEG", "PNG", "JPG");

	public static function upload_pic($request) {
		$car_pic_path = public_path('carlogo/');
		if (!is_dir($car_pic_path)) {mkdir($car_pic_path, 0755, true);}
		if (!empty($request->image)) {
			$fileName = md5(time() . uniqid()) . "." . $request->file('image')->getClientOriginalExtension();
			$extension = $request->file('image')->getClientOriginalExtension();
			if (in_array($extension, self::$image_ext)) {
				$request->file('image')->move($car_pic_path, $fileName);
				return $fileName;
			} else {return 111;}
		} else {return 'default.jpg';}
	}

	public static function addcar($input, $added, $request = null) {
		$user = Auth::user();
		if (!empty($input['carBody'])) {
			if (strpos('microcar,uitilitaria', strtolower(explode(",", $input['carBody'])[0])) !== false) {
				$car_size_status = "Small";
				$car_size = 1;
			}
			else if (strpos('berlina 2 volumi, berlina 3 volumi, station wagon, crossover, coupe, cabriolet', strtolower(explode(",", $input['carBody'])[0])) !== false) {
				$car_size_status = "Average";
				$car_size = 2;
			}

			else if (strpos('suv, fuoristrada, monovolume, auto di lusso, multispace', strtolower(explode(",", $input['carBody'])[0])) !== false) {
				$car_size_status = "Big";
				$car_size = 3;
			}
			else{
			  $car_size_status = "Average";
			  $car_size = 2;  
			}
		} else {
			$car_size_status = "Average";
			$car_size = 2;
		}
		$matches = array('user_id' => $user->id, 'carMakeName' => $input['carMakeName'], 'carModelName' => $input['carModelName'], 'carVersion' => $input['carVersion'] , 'deleted_at' => NULL);
		$exists = UserDetails::where($matches)->count();
	
		if ($exists > 0) {
			return false;
		}
		$image = 'default.jpg';
		/*Change Selected Car API*/
			$users_car_list = UserDetails::where('user_id' , '=' , Auth::user()->id)->get();
			if($users_car_list->count() > 0){
				$update_record = UserDetails::where('user_id' , '=' , Auth::user()->id)->update(['selected'=>0]);
			}
		/*End*/
		UserDetails::create([
			'user_id' => $user->id,
			'carMakeName' => $input['carMakeName'],
			'carModelName' => $input['carModelName'],
			'carVersion' => $input['carVersion'],
			'addedTo' => $added,
			'image' => $image,
			'car_size' => $car_size,
			'car_size_status' => $car_size_status,
			'number_plate'=>$input['number_plate'] ,
			'alloy_wheels' => 0,
			'selected'=>1
		]);
		return true;

	}

	public static function deleteCar($input) {
		$user = Auth::user();
		$matches = array('id' => $input['carId']);
		$exists = UserDetails::where($matches)->count();
		if ($exists == 0) {
			return false;
		}
		UserDetails::where($matches)->delete();
		return true;

	}

	public static function editCar($input, $request = null) {
		$user = Auth::user();
		$matches = array('id' => $input['carId']);
		$exists = UserDetails::where('id', '=', $input['carId'])->count();
		if ($exists == 0) {
			return 1;
		}

		if (!empty($input['car_size'])) {
			if ($input['car_size'] == 1) {
				$car_size_status = "Small";
				$car_size = 1;
			}
			else if ($input['car_size'] == 2) {
				$car_size_status = "Average";
				$car_size = 2;
			}

			else if ($input['car_size'] == 3) {
				$car_size_status = "Big";
				$car_size = 3;
			}
			else{
			  	$car_size_status = "Average";
			$car_size = 2;  
			}
			
		} else {
			$car_size_status = "Average";
			$car_size = 2;
		}
		
		if(!empty($input['number_plate'])){
		     $number_plate = $input['number_plate'];
		   }  
		else{
		    $number_plate = NULL;
		  }  

/*
$exists=UserDetails::where(array(
'id' =>$input['carId'],
'carMakeName' =>$input['carMakeName'],
'carModelName' => $input['carModelName'],
'carVersion'=>$input['carVersion']
))->count();
if($exists>0)
{
return 2;
}

$image='default.jpg';
if($request!==null){
$result = UserDetails::upload_pic($request);
if($result != 111){
$image=$result;
}
}
 */

		if (!empty($input['alloy_wheels'])) {
			$allow_wheel = 1;
		} else {
			$allow_wheel = 0;
		}

		$update = array(
			'carMakeName' => $input['carMakeName'],
			'carModelName' => $input['carModelName'],
			'carVersion' => $input['carVersion'],
			'km_of_cars' => $input['km_of_cars'],
			'km_traveled_annually' => $input['km_traveled_annually'],
			'revision_date_km' => $input['revision_date_km'],
			'alloy_wheels' => $allow_wheel,
			'revesion_km' => $input['revesion_km'],
			'car_size' => $car_size,
			'car_size_status' => $car_size_status,
		    'number_plate'=>$number_plate,
			'updated_at' => date('Y-m-d H:i:s'),
		);

		//return $update;

		$result = \DB::table('user_details')->where('id', '=', $input['carId'])->update($update);
		if ($result) {
			return 3;
		} else {
			FALSE;
		}

	}

	public static function carList() {
		return  UserDetails::where('user_id', Auth::user()->id)->get();
	}

	public static function carListInfo() {
		$user = Auth::user();
		$resp = UserDetails::where('user_id', $user->id)->get();
		//echo "<pre>";
		//print_r($resp);exit;
		$respArr = array();
		$respInner = json_decode(Kromeda::get_response_to_database('getMakers'));
		//$respInner = json_decode(\App\Maker::all());
		//echo "<pre>";
		//print_r($respInner);exit;
		$i = 0;
		foreach ($resp as $key => $innerArr) {
			++$i;
			$result_images_arr = \App\Gallery::users_details_image($innerArr->id);
			$innerArr['images'] = $result_images_arr;

			$respArrInner = array();
			$slug_maker = '';
			if ($respInner->success == true) {
				foreach ($respInner->data->result[1]->dataset as $key => $maker) {
					if ($maker->idMarca == $innerArr->carMakeName) {
						$innerArr['carMake'] = $maker;
						$slug_maker = $innerArr['carMake']->Marca;

					}

				}

			}

			$respInnerVersion = json_decode(Kromeda::get_response_to_database('getVersion/' . $innerArr->carModelName));
			if ($respInnerVersion->success == true) {
				foreach ($respInnerVersion->data->result[1]->dataset as $key => $versions) {
					if ($versions->idVeicolo == $innerArr->carVersion) {
						$innerArr['carVers'] = $versions;
					}
				}
			}

			$get_model_details = Kromeda::get_response_to_database('getModels/' . $innerArr->carMakeName);
			$respInnerModel = json_decode($get_model_details);
			$car_modello = '';

			if ($respInnerModel->success == true) {
				foreach ($respInnerModel->data->result[1]->dataset as $key => $models) {
					if ($models->idModello . '/' . $models->ModelloAnno == $innerArr->carModelName) {
						$innerArr['carModel'] = $models;
						$car_modello = $innerArr['carModel']->Modello;
					}
				}
			}

			/*Get model default script start*/

			$js_response = \sHelper::car_model_details($slug_maker, $car_modello);
			$innerArr['original_image'] = $js_response;

			/*End*/

			$respArr[] = $innerArr;
		}

		return $respArr;
		//Kromeda::get_response($url);
	}

	/*Jitendra sahu  change*/
	public static function set_default_image($user_details_id, $image_name) {
		return UserDetails::where('id', '=', $user_details_id)->update(['image' => $image_name]);
	}
	/*End*/
	
	public static function get_customers_car_record($p1) {
        return UserDetails::where('user_id' , $p1)->get();
    }

}
