<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DiamondResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $abbreviation = [
            'G'     => 'Good',
            'GD'    => 'Good',
            'VG'    => 'Very Good',
            'EX'    => 'Excellent',
            'ID'    => 'Ideal',
            'NONE'  => 'None',
            'FAINT' => 'Faint'
        ];

        $shape = $this->shape != 'ROUND BRILLIANT' ? ucfirst(strtolower($this->shape)) : 'Round';

        $diamond_title_line1 = "{$this->carats} Carat {$shape} Diamond";
        $diamond_title_line2 = "{$this->color}-Color {$this->clarity}-Clarity";

        $cut_text = $abbreviation[strtoupper($this->cut)] ?? $this->cut;
        if(!empty($this->cut) && !in_array($this->cut, ['NA', 'N/A'])) {
            $diamond_title_line2 .= " {$cut_text}-Cut";
        }

        $shape_image = asset('assets/diamond-shape-images/' . str_slug($this->shape) . '.png');
        $shape_icon = asset('assets/diamond-shape-images/icons/' . str_slug($this->shape) . '.png');

        return [
            'id'                => $this->id,
            'product_id'        => $this->product_id,
            'stock_no'          => $this->stock_no,
            'title'             => $this->product_title,
            'title_line1'       => $diamond_title_line1,
            'title_line2'       => $diamond_title_line2,
            'report_no'         => $this->report_no,
            'report_link'       => $this->report_link,
            'shape'             => $shape,
            'carat'             => $this->carats,
            'carat_weight'      => "{$this->carats} Ct.",
            'color'             => $this->color,
            'clarity'           => $this->clarity,
            'cut'               => $cut_text,
            'lab'               => $this->lab,
            'price'             => $this->final_price,
            'polish'            => $abbreviation[strtoupper($this->polish)] ?? $this->polish,
            'symmetry'          => $abbreviation[strtoupper($this->symmetry)] ?? $this->symmetry,
            'fluorescence'      => $abbreviation[strtoupper($this->fluorescence)] ?? $this->fluorescence,
            'measurements'      => "{$this->measurements} mm",
            'depth_percentage'  => "{$this->depth_percentage}%",
            'table_percentage'  => "{$this->table_percentage}%",
            'image_link'        => !empty($this->image_link) && filter_var($this->image_link, FILTER_VALIDATE_URL) ? $this->image_link : '',
            'video_link'        => !empty($this->video_link) && filter_var($this->video_link, FILTER_VALIDATE_URL) ? $this->video_link : '',
            'generic_image'     => $shape_image,
            'shape_html'        => '<img src="' . $shape_icon . '" width="25" height="25" style="vertical-align: middle;">&nbsp;&nbsp;<span class="hidden-xs">' . $shape . '</span>',
        ];
    }
}
