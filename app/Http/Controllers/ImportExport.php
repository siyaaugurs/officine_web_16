<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Products_group;
use App\Library\sHelper;
use Excel;
use App\User;
use App\Export\Category;
use App\Export\SubCategory;
use App\Export\CategorySample;
use App\Export\SampleSubCategorySample;
use App\Export\CategoryImport;
use App\Export\Tyre;
use App\Export\TyreSample;
use App\Export\Rim;
use App\Export\SpareProductsList;
use App\Export\CarWashing;
use App\Export\CarRevision;
use App\Export\AssembleService;
use App\Export\TyreServiceExport;
use App\Export\WrackerService;
use App\Export\CarMaintinanceExport;
use App\Export\InventorySparePart;
use App\Export\ExportTyreInventory;

use App\Export\RimSample;
use App\Import\TyreImport;
use App\Export\SpareProducts;
use App\Export\KromedaSpareProducts;
use App\Export\CustomSpareProducts;
use App\Export\ProductBrandList;
use App\Import\KromedaSpareImport; 
use App\Import\CustomSpareImport; 
use App\Import\ProductBrandImport; 
use App\Export\N3Category;
use App\Import\N3CategoryImport;
use App\Import\SpareImport; 
use App\Import\N1CategoryImport;
use App\Import\CarwashingImport;
use App\Import\N2CategoryImport;
use App\Import\CarRevisionImport;
use App\Import\AssembleServiceImport;
use App\Import\TyreServiceImport;
use App\Import\WrackerServiceImport;
use App\Import\CarMaintinanceImport;
use App\Import\CustomTyreImport;
use App\Import\InventorySpareProduct;
use App\Import\InventoryTyreImport;



use Auth;

class ImportExport extends Controller{
   
   
   public function export($action , $p1 = NULL){
	if($action == "sapre_part_inventory") {
		return Excel::download(new InventorySparePart, 'inventory_spare.xlsx');  
	}
	  /*Export common services script start*/
	  if($action == "services"){
		  if(empty($p1)) return redirect()->back();
		   if($p1 == 13){
			  /*Import Wracker Service scirpt start*/
			     return Excel::download(new WrackerService , 'wracker_service.xlsx'); 
			  /*End*/
			}
		   else if($p1 == 12){
			return Excel::download(new CarMaintinanceExport , 'car_maintinance_service.xlsx'); 
		   }	

		}
	  /*End*/ 
	  /*Export Car revision script Start*/
	  if($action == "car_reviosion"){
		    return Excel::download(new CarRevision , 'car_revision.xlsx');
		}
	  /*End*/ 
	  if($action == "car_washing"){
		 if(Auth::check()){
		   return Excel::download(new CarWashing , 'car_washing.xlsx');
		  } 
		 else{
		     return redirect()->back(); 
		  } 
		} 
	  if($action == "spare_products_list"){
		  /*Export Spare Products list */
		  return Excel::download(new SpareProductsList , 'spare_product.xlsx');	
		  /*End*/  
		  
	   } 
	/*Export Spare products */
	 if($action == "spare_product_list_sample"){
		return Excel::download(new SpareProducts , 'spare_product_sample.xlsx');	  
	 }  
	 /*End*/
	 /*Export Kromeda Spare products */
		if($action == "kromeda_spare_product_list"){
			return Excel::download(new KromedaSpareProducts , 'kromeda_spare_product.xlsx');	  
		}  
	 /*End*/
	/*Export Custom Spare products */
	if($action == "custom_spare_product_list"){
		return Excel::download(new CustomSpareProducts , 'Custom_spare_product.xlsx');	  
	}  
	if($action == "product_brand_list_export"){
		return Excel::download(new ProductBrandList , 'Product_brand_list.xlsx');	  
	}
	 /*End*/
	   if($action == "rim_list_sample"){
		return Excel::download(new RimSample , 'rim_list.xlsx');	  
	   } 
	/*Export Rim list start*/
	   if($action == "rim_list"){
		     return Excel::download(new Rim , 'rim_list.xlsx');	  
		 }
	   /*End*/
	   /*Export Tyre list sample*/
	   if($action == "list_of_custom_tire"){
		    return Excel::download(new TyreSample , 'tyre_list.xlsx');	 
		  }
	   /*End*/
	   /*Export tyre script start*/
	     if($action == "tire_list"){
			  return Excel::download(new Tyre , 'tyre_list.xlsx');	 
		   }
	   /*End*/
       if($action == "category"){
		 return Excel::download(new Category, 'category.xlsx');	  
		 //return Excel::download($data_arr, 'users.xlsx');
  
		/*	return Excel::download('laravelcode', function($excel) use ($new_cat_fields) {
					   $excel->sheet('mySheet', function($sheet) use ($new_cat_fields){
						  $sheet->fromArray($data);
						});
                   })->download($action);  */
		  } 
		if($action == "sub_category"){
		   return Excel::download(new SubCategory, 'sub_category.xlsx');	  
		} 
		/*Category Sample */
		if($action == "sample_category"){
		    return Excel::download(new CategorySample, 'category_sample.xlsx');	  
		 }
		/*End*/
		/*Sub Category sample start*/
		 if($action == "sample_sub_category"){
		      return Excel::download(new SampleSubCategorySample, 'sub_category_sample.xlsx');	 
		   }
		/*End*/ 
		/*n3 Category  sample*/
		if($action == 'n3_category'){
			return Excel::download(new N3Category, 'N3Category.xlsx');	 	
		}
		/*Import assemble_services details start*/
		if($action == "assemble_services"){
		   return Excel::download(new AssembleService, 'assemble_services.xlsx');	 	
		 }
		/*End*/
		/*Import Tyre Service details script start*/
		if($action == "tyre_service_detail"){
		    return Excel::download(new TyreServiceExport, 'tyre_services.xlsx');  
		  }
		/*End*/

		if($action == "tyre_inventory") {
			return Excel::download(new ExportTyreInventory, 'tyre_inventory.xlsx');
		}
		
   }
  
  public function import(Request $request , $action){
	  /*Service import script start*/
	   if($action == "service_import"){
		   if($request->service_id == 13){
			    /*Import Wracker Service script Start*/
				  Excel::import(new WrackerServiceImport , request()->file('import_file'));
				/*End*/
		   }
		   else if($request->service_id == 12){
			    /*Import Wracker Service script Start*/
				Excel::import(new CarMaintinanceImport , request()->file('import_file'));
				/*End*/
		   }  	
		 }
	  /*End*/
	  /*Import Tyre service script start*/
	    if($action == "import_tyre_service"){
		  Excel::import(new TyreServiceImport , request()->file('tyre_service_file'));

		}
	  /*End*/
  	if($action == "brand_product_import"){
	    Excel::import(new ProductBrandImport , request()->file('brand_product_file'));
	}
	  /*import assemble service start*/
	  if($action == "import_assemble_service"){
		    Excel::import(new AssembleServiceImport , request()->file('car_assemble_file'));
		}
	  /*End*/
	 /*Import car revision sript start*/
	 if($action == "import_car_revision_service_details"){
	      Excel::import(new CarRevisionImport , request()->file('car_revision_file'));
	   }
	 /*End*/ 
	  
	 /*Import Car wash details*/
	 if($action == "import_car_wash_service_details"){
	     Excel::import(new CarwashingImport , request()->file('car_washing_file'));
	   }
	 /*End*/ 
	/*Import Spare parts script start */
	if($action == "import_spare_parts"){
	    Excel::import(new SpareImport , request()->file('spare_products_file'));
	  }
	if($action == "custom_import_spare_parts"){
		set_time_limit(500);
	    Excel::import(new CustomSpareImport , request()->file('custom_spare_products_file'));
	}
  	if($action == "import_kromeda_spare_parts"){
	    Excel::import(new KromedaSpareImport , request()->file('spare_products_file'));
	}
	/*End*/
	if($action == "import_rim"){
		Excel::import(new \App\Import\RimImport , request()->file('tire_file'));
	}  
	if($action == "import_custom_tire"){
		Excel::import(new CustomTyreImport , request()->file('custom_tire_file'));
	}
	if($action == "import_tire"){
		set_time_limit(0);
		// ini_set('memory_limit','1G');
		Excel::import(new TyreImport , request()->file('tire_file'));
		/*//$response = TyreImport::tyre_import($request);
		if(is_uploaded_file($_FILES['tire_file']['tmp_name'])){
		    // Open uploaded CSV file with read-only mode
            $csvFile = fopen($_FILES['tire_file']['tmp_name'], 'r');
			// Skip the first line
            fgetcsv($csvFile);
			while(($line = fgetcsv($csvFile)) !== FALSE){
                // Get row data
                $name   = $line[0];
               echo $name."<br />";
            }
			fclose($csvFile);
		  }
		else{
		    echo "Not working";exit;
		  }  */
	  }  
	if($action == "import_category"){
		  Excel::import(new CategoryImport , request()->file('category_file'));
	  }
	if($action == "import_n3_category"){
		 if($request->type == '3'){		 
		 Excel::import(new N3CategoryImport , request()->file('category_file'));
		 }
		 if($request->type == '1'){	 
		 Excel::import(new N1CategoryImport , request()->file('category_file'));
		 } 
		 if($request->type == '2'){
		 Excel::import(new N2CategoryImport , request()->file('category_file'));
		 } 
	 } 
	if($action == "import_spare_invent") {
		Excel::import(new InventorySpareProduct , request()->file('spare_invent_files'));
	}
	if($action == "import_tyre_invent_tire") {
		Excel::import(new InventoryTyreImport , request()->file('tyre_invent_files'));
	}
	
  } 
}
