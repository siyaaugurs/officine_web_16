<?php
namespace App\Export;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use sHelper;
use DB;
use Auth;
  
class ExportTyreInventory implements FromCollection , WithHeadings
{
     /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(){

        $lang = sHelper::get_set_language(app()->getLocale());
        $data_arr = [];

        $tyre_inventory =   \App\TyreInventory::where([['deleted_at', '=', NULL], ['users_id', '=', Auth::user()->id]])->get();
        if($tyre_inventory->count() > 0){
            foreach($tyre_inventory as $tyre){
                $tyre_details = [];
                $tyre_details['id'] = $tyre->id;
                $tyre_details['users_id'] = $tyre->users_id;
                $tyre_details['Tyre24_itemId'] = $tyre->Tyre24_itemId;
                $tyre_details['Tyre24_ean_number'] = $tyre->Tyre24_ean_number;
                $tyre_details['seller_price'] = $tyre->seller_price;
                $tyre_details['quantity'] = $tyre->quantity;
                $tyre_details['stock_warning'] = $tyre->stock_warning;
                $tyre_details['status'] = $tyre->status;

                $data_arr[] =  $tyre_details; 
            }
        }
        return collect($data_arr);
    }

    public function headings(): array {
        return [
            'ID',
            'Seller ID',
            'Product Item',
            'EAN Number',
            'Price',
            'Quantity',
            'Stock Warning',
            'Status',
		];
    }
}