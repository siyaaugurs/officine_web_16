<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $shapes = ['ROUND','CUSHION','OVAL','PRINCESS','EMERALD','PEAR','MARQUISE','ASSCHER','RADIANT','HEART'];
        $shapes_array = array_merge($shapes, array_map("strtolower", $shapes));
        
        return [
            'lab'               => 'required',
            'certificate_no'    => 'required',
            'shape'             =>  [
                                        'required',
                                        Rule::in($shapes_array),
                                    ],
            'carats'            => 'required',
            'color'             => 'required',
            'clarity'           => 'required',
            'cut'               => 'required',
            'polish'            => 'required',
            'symmetry'          => 'required',
            'fluorescence'      => 'required',
            'measurements'      => 'required',
            'table_percentage'  => 'required|numeric|between:0,100',
            'depth_percentage'  => 'required|numeric|between:0,100',
            'total_amount'      => 'required|numeric',
        ];
    }
}
