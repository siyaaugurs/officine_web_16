<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Resources\DiamondResource;

class SearchController extends Controller
{
    private $cuts = ['', 'ID', 'EX', 'VG'];
    private $clarities = ['', 'FL', 'IF', 'VVS1', 'VVS2', 'VS1', 'VS2', 'SI1'];
    private $colors = ['', 'D', 'E', 'F', 'G', 'H'];
    private $polishes = ['', 'EX', 'VG', 'GD'];
    private $symmetries = ['', 'EX', 'VG', 'GD'];
    
    private function getFilterValues($data, $input) {
        $options = [];
        $choices = explode(',', $input);
        if(count($choices) == 2) {
            $first = $choices[0];
            $last = $choices[1];
            $gap = $last - $first;
            if($gap < 2) {
                $options[] = $data[$last];
            } else {
                for($i=$first+1; $i<=$last; $i++) {
                    if($i == 0)
                        continue;
                    $options[] = $data[$i]; 
                }
            }
            if(in_array('GD', $options)) {
                $options[] = 'G';
            }
        }
        return $options;
    }
    
    public function index(Request $request) {
        $requestData = $request->except(['page', 'size']);
        
        $query = Product::query();
        // Price Filter
        $query->whereBetween('final_price', [request('converted_pricerange_min'), request('converted_pricerange_max')]);
        // Carats Filter
        $query->whereBetween('carats', [request('min_carat'), request('max_carat')]);
        // Shape Filter
        $query->when(!empty(request('shapes')), function ($q) {
            $shapes = explode(',', request('shapes'));
            if(in_array('ROUND', $shapes)) {
                $shapes[] = 'ROUND BRILLIANT';
            }
            return $q->whereIn('shape', $shapes);
        });
        // Cut Filter
        $cut = $this->getFilterValues($this->cuts, request('cut'));
        $cut[] = 'N/A';
        $cut[] = 'NA';
        $query->whereIn('cut', $cut);
        
        // Clarity Filter
        $clarities = $this->getFilterValues($this->clarities, request('clarity'));
        $query->whereIn('clarity', $clarities);
        
        // Color Filter
        $colors = $this->getFilterValues($this->colors, request('color'));
        $query->whereIn('color', $colors);
        
        // Polish Filter
        $polishes = $this->getFilterValues($this->polishes, request('polish'));
        $query->whereIn('polish', $polishes);
        
        // Symmetry Filter
        $symmetries = $this->getFilterValues($this->symmetries, request('symmetry'));
        $query->whereIn('symmetry', $symmetries);
        
        // Depth Filter
        $query->whereBetween('depth_percentage', [request('min_depth'), request('max_depth')]);
        
        // Table Filter
        $query->whereBetween('table_percentage', [request('min_table'), request('max_table')]);
        
        if(request()->has('sorters') && count(request('sorters')) > 0) {
            $field = request('sorters')[0]['field'];
            $dir = request('sorters')[0]['dir'];
            
            if($field == 'shape_html') {
                $column = 'shape';
            } elseif($field == 'carat') {
                $column = 'carats';
            } elseif($field == 'price') {
                $column = 'final_price';
            } else {
                $column = $field;
            }
            $query = $query->orderBy($column, $dir);
        } else {
            $query = $query->orderBy('updated_at', 'desc');
        }
        
        $productsFound = $query->count();
        $products = $query->paginate();
        
        return DiamondResource::collection($products)->additional(['last_page' => ceil($productsFound / 50)]);        
    }
}
