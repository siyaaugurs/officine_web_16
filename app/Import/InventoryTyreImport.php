<?php
namespace App\Import;
use Maatwebsite\Excel\Concerns\ToModel;
use App\ProductsNew_details;
use App\Product_inventory;
use App\ProductsNew;
use sHelper;
use Auth;
  
class InventoryTyreImport implements ToModel{

    public $row_count = 0;

    public function model(array $row){

        $created_at = $updated_at = date('Y-m-d h:i:s');
        $current_number_of_row = ++$this->row_count;

        if($current_number_of_row > 1){
            if(!empty($row[3]) || !empty($row[2])) {
                if(!empty($row[3]) && !empty($row[4])) {
                    $where_clause = [['Tyre24_ean_number', '=', $row[3]], ['seller_price', '=', $row[4]], ['users_id', '=', Auth::user()->id]];
                } else if(!empty($row[2]) && !empty($row[4])) {
                    $where_clause = [['Tyre24_itemId', '=', $row[2]], ['seller_price', '=', $row[4]], ['users_id', '=', Auth::user()->id]];
                }
                $check_record = \App\TyreInventory::where($where_clause)->first();
                if(!empty($check_record)) {
                    $update_record = \App\TyreInventory::where('id' , '=' , $check_record->id)->update(['quantity' => $row[5], 'stock_warning' => $row[6]]);
                    $tyre24_detail = \App\Tyre24::find($check_record->Tyre24_id);
                    if($update_record) {
                        if($row[5] > $check_record->quantity) {
                            $tyre24_detail->quantity = ($row[5] - $check_record->quantity) + $tyre24_detail->quantity;
                            $tyre24_detail->save();
                        }
                        if($row[5] < $check_record->quantity) {
                            $tyre24_detail->quantity = $tyre24_detail->quantity - ($check_record->quantity - $row[5]);
                            $tyre24_detail->save();
                        }
                    }
                } else {
                    if(!empty($row[3])) {
                        $where_clause1 = [['tyre_response->ean_number', '=', $row[3]], ['deleted_at', '=', NULL]];
                    } else if(!empty($request->item_number)) {
                        $where_clause1 = [['itemId', '=', $row[2]], ['deleted_at', '=', NULL]];
                    }
                    $check_tyre_details = \App\Tyre24::where($where_clause1)->first();
                    $tyre_response = json_decode($check_tyre_details->tyre_response);
                    if(!empty($check_tyre_details)) {
                        $result = \App\TyreInventory::create([
                            'users_id' => Auth::user()->id,
                            'Tyre24_id' => $check_tyre_details->id,
                            'Tyre24_itemId' => $check_tyre_details->itemId ? $check_tyre_details->itemId : NULL,
                            'Tyre24_ean_number' => $tyre_response->ean_number,
                            'seller_price' => $row[4],
                            'quantity' => $row[5],
                            'stock_warning' => $row[6],
                            'status' => 'A',
                        ]);
                        if($result) {
                            $check_tyre_details->quantity = $check_tyre_details->quantity + $row[5];
                            $check_tyre_details->save();
                        }
                    }
                }
            }

        }
    }
}