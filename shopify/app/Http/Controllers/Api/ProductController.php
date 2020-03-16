<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ProductRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Helpers\AppHelper;
use DB;

class ProductController extends Controller
{
    public function store(ProductRequest $request) {        
        $input = [
            'lab'               => request('lab'),
            'report_no'         => request('certificate_no'),
            'shape'             => request('shape'),
            'carats'            => request('carats'),
            'color'             => request('color'),
            'clarity'           => request('clarity'),
            'cut'               => request('cut'),
            'polish'            => request('polish'),
            'symmetry'              => request('symmetry'),
            'fluorescence'      => request('fluorescence'),
            'measurements'      => request('measurements'),
            'table_percentage'  => request('table_percentage'),
            'depth_percentage'  => request('depth_percentage'),
            'ratio'             => request('ratio'),
            'rap_price'         => request('rap_price'),
            'video_link'        => request('video_link'),
            'discount'          => request('discount'),
            'per_carat_amount'  => request('per_carat_amount'),
            'total_amount'      => request('total_amount'),
            'user_id'           => $request->user()->id
        ];
        $input['final_price'] = AppHelper::calculate_final_price($input['total_amount']);
        $data = AppHelper::escape_cell_values($input);
        
        DB::beginTransaction();
        try {
            $csvData = Product::updateOrCreate(['report_no' => $data['report_no']], $data);
            if ($csvData->wasRecentlyCreated) {                        
                AppHelper::create_product($csvData);
                $message = 'Product has been created successfully.';
            } else {
                $message = 'Product has been updated successfully.';
            }
            DB::commit();
            return response()->json(['success' => $message], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (\Throwable $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
