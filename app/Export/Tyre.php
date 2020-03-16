<?php
namespace App\Export;
use App\Products_group;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use sHelper;
use kromedaDataHelper;
  
class Tyre implements FromCollection , WithHeadings{
	
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(){
		$lang = sHelper::get_set_language(app()->getLocale());
		    /*Get category */
			 $category_list = [];
			 $tyre_list = \DB::table('tyre24s')->where([['deleted_at' , '=' , NULL] , ['type_status' , '=' , 1]])->get();
			 $data_arr = [];
				 foreach($tyre_list as $tyre){
						$tyre =  kromedaDataHelper::arrange_tyre_detail($tyre);
					   	$new_cat_fields = [];
						$new_cat_fields['id'] = $tyre->id;
						$new_cat_fields['max_width'] = $tyre->max_width;
						$new_cat_fields['max_aspect_ratio'] = $tyre->max_aspect_ratio;
						$new_cat_fields['max_diameter'] = $tyre->max_diameter;
						$new_cat_fields['speed_index'] = $tyre->speed_index;
						$new_cat_fields['tyre_type'] = $tyre->type;
						$new_cat_fields['matchcode'] = $tyre->matchcode;
						$new_cat_fields['ean_number'] = $tyre->ean_number;
						$new_cat_fields['description'] =  $tyre->description;
						$new_cat_fields['description1'] = $tyre->description1;
						$new_cat_fields['pr_description'] = $tyre->pr_description;
						$new_cat_fields['wholesalerArticleNo'] = $tyre->wholesalerArticleNo;
						$new_cat_fields['manufacturer_description']  = $tyre->manufacturer_description;
						$new_cat_fields['price'] = $tyre->price;
						$new_cat_fields['pair'] = $tyre->pair;
						$new_cat_fields['our_description'] = $tyre->our_description;
						$new_cat_fields['seller_price'] = $tyre->seller_price;
						$new_cat_fields['quantity'] = $tyre->quantity;
						$new_cat_fields['tax'] = $tyre->tax;
						$new_cat_fields['tax_value'] = $tyre->tax_value;
						$new_cat_fields['stock_warning'] = $tyre->stock_warning;
						$new_cat_fields['discount'] = $tyre->discount;
						$new_cat_fields['meta_key_title'] = $tyre->meta_key_title;
						$new_cat_fields['meta_key_word'] = $tyre->meta_key_word;
						$new_cat_fields['runflat'] = $tyre->runflat;
						$new_cat_fields['reinforced'] = $tyre->reinforced;
						$new_cat_fields['wetGrip'] = $tyre->wetGrip;
					  	$new_cat_fields['load_speed_index'] = $tyre->load_speed_index;
					  	$new_cat_fields['substract_stock'] = $tyre->substract_stock;
					  	$new_cat_fields['unit'] = $tyre->unit;
					  	$new_cat_fields['status'] = $tyre->status;
					  	// $new_cat_fields['is3PMSF'] = $tyre->is3PMSF;
					 	$new_cat_fields['weight'] = $tyre->weight;
					 	$new_cat_fields['peak_mountain_snowflake'] = $tyre->is3PMSF;
					 	$new_cat_fields['vehicle_tyre_type'] = $tyre->vehicle_tyre_type;
						$new_cat_fields['season_tyre_type'] = $tyre->season_tyre_type;
						$new_cat_fields['tyre_images'] = '';
						$new_cat_fields['rollingResistance'] = $tyre->rollingResistance;
						$new_cat_fields['noiseDb'] = $tyre->noiseDb;
						$new_cat_fields['tireClass'] = $tyre->tireClass;
					    $data_arr[] = $new_cat_fields;
				  } 
			return collect($data_arr);
   }
		 
   public function headings(): array
    {
        return [
			'ID',
            'Max Width',
            'Max Aspect Ratio',
            'Max diameter',
            'Speed Index',
			'Tyre Type',
            'Match Code',
			'Ean number',
			'Description',
			'Description 1',
			'Pr Description',
			'Whole Saler Article No.',
			'Manufacturer Description',
		    'Price', 	
			'Pair',
			'Our Description',
			'Seller Price',
			'Quantity',
			'Tax',
			'Tax value',
			'Stock Warning',
			'Discount',
			'Meta Key Title',
			'Meta key Word',
			'Runflat',
			'Rein Forced',
			'Wet Grip',
			'Speed Load Index',
			'Substract Stock',
			'Unit',
			'Status',
			// 'is3PMSF',
			'Weight',
			'3 Peak Mountain Snowflake',
			'Vehicle Type',
			'Season Type',
			'Tyre Images',
			'Rolling Resistance',
			'NoiseDb',
			'Tyre Class',
        ];
    }
}