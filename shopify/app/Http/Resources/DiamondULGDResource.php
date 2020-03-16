<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class DiamondULGDResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $result = [];
        foreach($this->resource as $resource) {
            array_push($result, [
                'stock_no'      => $resource['Stock #'],
                'lab'           => $resource['Lab'],
                'report_no'     => $resource['Certificate #'],
                'report_link'   => $resource['Certificate Url'],
                'shape'         => $resource['Shape'],
                'carats'        => $resource['Weight'],
                'color'         => $resource['Color'],
                'clarity'       => $resource['Clarity'],
                'cut'           => $resource['Cut Grade'],
                'polish'        => $resource['Polish'],
                'symmetry'      => $resource['Symmetry'],
                'fluorescence'  => $resource['Fluorescence Color'],
                'measurements'  => $resource['Measurements'],
                'depth_percentage'  => $resource['Depth Percent'],
                'table_percentage'  => $resource['Table Percent'],
                'girdle'        => $resource['Girdle Percent'],
                'video_link'    => $resource['Video Link'],
                'image_link'    => $resource['Image Link'],
                'origin'        => $resource['Country'],
                'total_amount'  => mt_rand(200, 20000),
            ]);
        }
        return $result;
    }
}
