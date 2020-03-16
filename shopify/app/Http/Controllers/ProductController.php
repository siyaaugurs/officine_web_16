<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $shapes = config('diamonds.shapes');
        $labs = config('diamonds.labs');
        $colors = config('diamonds.colors');
        $clarities = config('diamonds.clarities');
        $fluorescences = config('diamonds.fluorescences');

        return view("products.edit", compact('shapes', 'labs', 'colors', 'clarities', 'fluorescences', 'product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
//        echo '<pre>';
//        print_r($request->all());
//        exit;
        $request->validate([
            'shape'             =>  [
                                        'required',
                                        Rule::in(['ROUND','CUSHION','OVAL','PRINCESS','EMERALD','PEAR','MARQUISE','ASSCHER','RADIANT','HEART']),
                                    ],
            'carats'            => 'required',
            'color'             => 'required',
            'clarity'           => 'required',
            // 'cut'               => 'required',
            'polish'            => 'required',
            'symmetry'          => 'required',
            // 'fluorescence'      => 'required',
            'measurements'      => 'required',
            'table_percentage'  => 'required|numeric|between:0,100',
            'depth_percentage'  => 'required|numeric|between:0,100',
            'total_amount'      => 'required|numeric',
            'final_price'       => 'required|numeric',
        ]);

        $product->update($request->all());

        return redirect()->back()->with('flash_success','Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('home')->with('success','Product deleted successfully');
    }
}
