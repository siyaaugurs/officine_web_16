<?php
/**
* Class AppHelper | app/Helpers/AppHelper.php
*
* @package     ShopifyApp\Helpers
*/

namespace App\Helpers;

//use DB;
//use Storage;
use App\Models\CsvData;
use App\Models\Product;
use App\Models\Setting;
//use OhMyBrew\ShopifyApp\Facades\ShopifyApp;

/**
* AppHelper
*
* This class contains common functions which can be used anywhere in the project scope.
*
* @author
* @version     v.1.0
* @copyright   Copyright © 2019 D-Law. All rights reserved.
*/
class AppHelper
{
    public function __construct() {

    }

    public static function instance()
    {
        return new AppHelper();
    }

    private static function csv_to_array($filename='', $hasHeader=FALSE, $delimiter=',') {
        if(!file_exists($filename) || !is_readable($filename)) {
            return FALSE;
        }
        $header = array();
        $data = array();
        if (($handle = fopen($filename, 'r')) !== FALSE)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
            {
                if($hasHeader && empty($header)) {
                    $header = self::mutate_csv_header_title($row);
                } elseif(!$hasHeader) {
                    $row = self::escape_cell_values($row);
                    $data[] = $row;
                } else {
                    $row = self::escape_cell_values($row);
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }
        return $data;
    }

    private static function mutate_csv_header_title($row) {
        array_walk($row, function(&$key) {
            $key = str_slug(str_ireplace('%', 'percentage', trim($key)), '_');
        });
        return $row;
    }

    private static function get_abbreviation_text($value) {
        $abbreviation = config('diamonds.abbreviation');

        return $abbreviation[strtoupper($value)] ?? $value;
    }

    private static function create_product_description($data) {
        $body_html = '';
        $body_html .= !empty($data->video_link) && filter_var($data->video_link, FILTER_VALIDATE_URL) ? '<iframe src="' . $data->video_link . '" style="top: 0; left: 0; bottom: 0; right: 0; width: 100%; height: 500px; border: none; margin: 0; padding: 0; overflow: hidden;"></iframe><br><br>' : '';
        $body_html .= "<strong>Shape: </strong>{$data->shape}<br>";
        $body_html .= "<strong>Carat Weight: </strong>{$data->carats}<br>";
        $body_html .= "<strong>Colour: </strong>{$data->color}<br>";
        $body_html .= "<strong>Clarity: </strong>{$data->clarity}<br>";
        $body_html .= "<strong>Cut: </strong>" . self::get_abbreviation_text($data->cut) . "<br>";
        $body_html .= "<strong>Polish: </strong>" . self::get_abbreviation_text($data->polish) . "<br>";
        $body_html .= "<strong>Symmetry: </strong>" . self::get_abbreviation_text($data->symmetry) . "<br>";
        $body_html .= "<strong>Fluorescence: </strong>" . self::get_abbreviation_text($data->fluorescence) . "<br>";
        $body_html .= "<strong>Measurements: </strong>{$data->measurements}<br>";
        $body_html .= "<strong>Table: </strong>{$data->table_percentage}%<br>";
        $body_html .= "<strong>Depth: </strong>{$data->depth_percentage}%<br>";
        if(!empty($data->ratio)) { $body_html .= "<strong>Ratio: </strong>{$data->ratio}<br>"; }
        $body_html .= "<strong>Lab Report: </strong>{$data->lab}<br>";
        $body_html .= "<strong>Report No: </strong>{$data->report_no}<br>";

        return $body_html;
    }

    public static function calculate_final_price($price) {
        $setting = Setting::find(1);

        if(!empty($setting)) {
            return $price * (1 + ($setting->price_addon_percentage)/100);
        }

        return $price;
    }

    public static function escape_cell_values($row) {
        array_walk($row, function(&$key) {
            $key = trim($key);
        });
        return $row;
    }

    public static function validateDiamondData($data, $abbreviations, $short_names, $shapes, $cuts, $colors, $clarities, $fluorescences, $polish, $symmetry) {
        if(in_array(strtoupper($data['shape']), ['BAGUETTE', 'OCTAGONAL', 'ROUND ROSE CUT'])) {
            return FALSE;
        }
        // Cushion Cut, Round Brilliant, Oval shape
        $shape_words = explode(' ', strtoupper($data['shape']));
        if(count($shape_words) > 1) {
            if(!find_array_has($shapes, $shape_words))
                return FALSE;
        } else {
            if(!in_array(strtoupper($data['shape']), $shapes) && !in_array(strtoupper($data['shape']), array_keys($abbreviations)))
                return FALSE;
        }

        if(!in_array(strtoupper($data['color']), $colors)) {
            return FALSE;
        }

        if(!in_array(strtoupper($data['clarity']), $clarities)) {
            return FALSE;
        }

        if(!empty(trim($data['cut'])) && !in_array(strtoupper($data['cut']), $cuts) && !in_array(strtoupper($data['cut']), $short_names)) {
            return FALSE;
        }

        if(!empty(trim($data['polish'])) && !in_array(strtoupper($data['polish']), $polish) && !in_array(strtoupper($data['polish']), $short_names)) {
            return FALSE;
        }

        if(!empty(trim($data['symmetry'])) && !in_array(strtoupper($data['symmetry']), $symmetry) && !in_array(strtoupper($data['symmetry']), $short_names)) {
            return FALSE;
        }

        if(!empty(trim($data['fluorescence'])) && !in_array(strtoupper($data['fluorescence']), $fluorescences) && !in_array(strtoupper($data['fluorescence']), $short_names)) {
            return FALSE;
        }

        return TRUE;
    }

    public static function parse_csv($filename, $path, $header=TRUE)
    {
        $abbreviations = config('diamonds.abbreviation');
        $short_names = array_map('strtoupper', array_keys(array_flip($abbreviations)));
        $shapes = config('diamonds.shapes');
        $cuts = config('diamonds.cut');
        $colors = config('diamonds.colors');
        $clarities = config('diamonds.clarities');
        $fluorescences = config('diamonds.fluorescences');
        $polish = config('diamonds.polish');
        $symmetry = config('diamonds.symmetry');

        $data = array_map('str_getcsv', file($path));
        if (count($data) > 0) {
            $csv_data = CsvData::create([
                'csv_filename' => $filename,
                'csv_header' => $header,
                'csv_data' => json_encode($data)
            ]);
            if($csv_data->id) {
                $rows = self::csv_to_array($path, $header);
//                echo '<pre>';
//                print_r($rows);
//                die;
                $recordUpdated = $recordInserted = 0;
                foreach ($rows as $row) {
                    $row['symmetry']    = $row['symm'] ?? $row['symmetry'];
                    $row['csv_data_id'] = $csv_data->id;
                    if(!self::validateDiamondData($row, $abbreviations, $short_names, $shapes, $cuts, $colors, $clarities, $fluorescences, $polish, $symmetry)) {
                        continue;
                    }
                    $csvData = Product::updateOrCreate(
                        [
                            'report_no' => $row['report_no'],
                        ],
                        $row
                    );
                    if ($csvData->wasRecentlyCreated) {
                        $recordInserted++;
                    } else {
                        $recordUpdated++;
                    }
                    self::create_product($csvData);
                    //break;
                }

                $datetime = date('d/m/Y h:i A');
                $result = "Uploading result for the uploaded file: <b>{$filename}</b><br><br>Time: {$datetime}<br>";
                $result .= "<br>";
                if ($recordInserted) {
                    $result .= "Added Records: {$recordInserted}<br>";
                }
                if ($recordUpdated) {
                    $result .= "Updated Records: {$recordUpdated}<br>";
                }
                return $result;
            }
        }
        return FALSE;
    }

    public static function import_from_api(array $rows = [], $api_id)
    {
        $abbreviations = config('diamonds.abbreviation');
        $short_names = array_map('strtoupper', array_keys(array_flip($abbreviations)));
        $shapes = config('diamonds.shapes');
        $cuts = config('diamonds.cut');
        $colors = config('diamonds.colors');
        $clarities = config('diamonds.clarities');
        $fluorescences = config('diamonds.fluorescences');
        $polish = config('diamonds.polish');
        $symmetry = config('diamonds.symmetry');

        foreach ($rows as $row) {
            if(!self::validateDiamondData($row, $abbreviations, $short_names, $shapes, $cuts, $colors, $clarities, $fluorescences, $polish, $symmetry)) {
                continue;
            }
            $row['api_id'] = $api_id;
            $csvData = Product::updateOrCreate(
                [
                    'report_no' => $row['report_no'],
                    'stock_no' => $row['stock_no']
                ],
                $row
            );
            self::create_product($csvData);
            break;
        }
    }

    public static function create_product($csvData) {
        $product_details    = self::create_product_description($csvData);
        $final_price        = self::calculate_final_price($csvData->total_amount);

        /*if(!$csvData->published && !in_array(strtoupper($csvData->shape), ['BAGUETTE', 'OCTAGONAL', 'ROUND ROSE CUT'])) {
            $apikey     = config('shopify-papp.apikey');
            $password   = config('shopify-papp.password');
            $hostname   = config('shopify-papp.hostname');
            $version    = config('shopify-papp.api_version');

            $client = new \GuzzleHttp\Client();
            $response = $client->request(
                    'POST',
                    "https://{$apikey}:{$password}@{$hostname}/admin/api/{$version}/products.json",
                    [
                        'json' => [
                            'product' => [
                                'title'             => $csvData->product_title,
                                'body_html'         => $product_details,
                                'vendor'            => 'Pure Carats',
                                'product_type'      => 'Diamond',
                                'published_scope'   => 'global',
                                'tags'              => [
                                    'Lab:' . $csvData->lab,
                                    'Shape:' . $csvData->shape,
                                    'Colour:' . $csvData->color,
                                    'Clarity:' . $csvData->clarity,
                                    'Cut:' . $csvData->cut,
                                    'Polish:' . $csvData->polish,
                                    'Symmetry:' . $csvData->symmetry,
                                    'Fluorescence:' . $csvData->fluorescence,
                                ],
                                'variants'      => [
                                    [
                                        'price'     => $final_price,
                                        'sku'       => $csvData->report_no,
                                        'inventory_management'  => 'shopify',
                                        'inventory_quantity'    => 1
                                    ]
                                ],
                                'images' => [
                                    [
                                        'src' => asset('assets/diamond-shape-images/' . str_slug($csvData->shape) . '.png')
                                    ]
                                ]
                            ]
                        ]
                    ]
            );
            $body = json_decode($response->getBody()->getContents());
//            $response = $shop->api()->rest(
//                    'POST',
//                    '/admin/api/2020-01/products.json',
//                    [
//                        'product' => [
//                            'title'         => $csvData->product_title,
//                            'body_html'     => '<iframe src="' . $csvData->video_link . '" style="top: 0; left: 0; bottom: 0; right: 0; width: 100%; height: 500px; border: none; margin: 0; padding: 0; overflow: hidden;"></iframe>',
//                            'vendor'        => 'Pure Carats',
//                            'product_type'  => 'Diamond',
//                            'published_scope' => 'global',
//                            'variants'      => [
//                                [
//                                    'price'     => $csvData->rap_price,
//                                    'sku'       => $csvData->report_no,
//                                    'inventory_management' => 'shopify',
//                                    'inventory_quantity' => 1
//                                ]
//                            ]
//                        ]
//                    ]
//            );
//            $body = $response->body;
            if(isset($body->product)) {
                Product::where('id', $csvData->id)->update([
                    'final_price'   => $final_price,
                    'published'     => 1,
                    'product_id'    => $body->product->id
                ]);

                return true;
            }
        }*/
        Product::where('id', $csvData->id)->whereNull('final_price')->update([
            'final_price'   => $final_price,
        ]);
        return false;
    }
}
