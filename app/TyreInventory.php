<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class TyreInventory extends Model
{
    protected  $table = "tyre_inventories";
    protected $fillable = [
      'id', 'users_id', 'Tyre24_id', 'Tyre24_itemId', 'Tyre24_ean_number', 'seller_price', 'quantity', 'stock_warning', 'status', 'deleted_at' , 'created_at' , 'updated_at'];

    public static function add_tyre_inventory($request, $check_ean, $tyre_response) {
        return TyreInventory::updateOrCreate([
            'id' => $request->seller_tyre_invent_id,
        ], [
            'users_id' => Auth::user()->id,
            'Tyre24_id' => $check_ean->id,
            'Tyre24_itemId' => $check_ean->itemId ? $check_ean->itemId : NULL,
            'Tyre24_ean_number' => $tyre_response->ean_number,
            'seller_price' => $request->price,
            'quantity' => $request->quantity,
            'stock_warning' => $request->stock_warning,
            'status' => $request->status
        ]);
    }
}
