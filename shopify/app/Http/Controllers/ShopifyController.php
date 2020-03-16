<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Storage;
use App\Helpers\AppHelper;
use App\Models\Product;
use Illuminate\Support\Str;
use App\Http\Resources\DiamondULGDResource;

class ShopifyController extends Controller
{
    public function index(Request $request) {
//        echo date('c');
//        $shop = ShopifyApp::shop();
//        $response = $shop->api()->rest(
//                'GET',
//                '/admin/api/2019-10/products.json',
//                [
//                    'fields' => 'id,image,title,vendor,created_at',
//                    'created_at_min' => date('Y-m-d')
//                ]
//        );
//        echo '<pre>';
//        print_r($response);
//        die;
//        $products = [];
//        if(!empty($response->body) && isset($response->body->products)) {
//            $products = $response->body->products;
//        }

        $keyword = $request->get('search');
        if (!empty($keyword)) {
            $productsModal = Product::where('id', '=', $keyword)
                    ->orWhere('lab', '=', $keyword)
                    ->orWhere('report_no', 'LIKE', "%$keyword%")
                    ->orWhere('stock_no', 'LIKE', "%$keyword%")
                    ->orWhere('shape', 'LIKE', "%$keyword%")
                    ->orWhere('carats', '=', $keyword)
                    ->orWhere('color', '=', $keyword)
                    ->orWhere('clarity', '=', $keyword)
                    ->orWhere('cut', '=', $keyword)
                    ->orWhere('polish', '=', $keyword)
                    ->orWhere('symmetry', '=', $keyword)
                    ->orWhere('fluorescence', '=', $keyword);
        } else {
            $productsModal = Product::latest();
        }
        $total_products = $productsModal->count();
        $products = $productsModal->paginate();

        return view("welcome", compact('total_products', 'products'));
    }

    public function import_csv(Request $request) {
        if($request->method() == 'POST') {
            if ($request->hasFile('csv_file') && $request->file('csv_file')->isValid()) {
                try {
                    if ($request->file('csv_file')->getClientMimeType() == 'application/vnd.ms-excel' || $request->file('csv_file')->extension() == 'txt') {
                        $filename = $request->file('csv_file')->getClientOriginalName();
                        $path = $request->file('csv_file')->getRealPath();

                        $result = AppHelper::parse_csv($filename, $path, $request->has('header'));
                        if (!$result) {
                            return redirect()->back();
                        } else {
                            Session::flash('flash_success', $result);
                        }
                    } else {
                        Session::flash('error', 'Invalid file uploaded!');
                    }

                } catch (\Exception $e) {
                    Session::flash('error', $e->getMessage());
                } catch (\Throwable $e) {
                    Session::flash('error', 'Something went wrong!');
                }
            } else {
                Session::flash('error', 'Invalid file uploaded!');
            }
        }

        return view("import_csv");
    }

    public function ajax_ftp_import() {
        try {
            $files = Storage::disk('public')->allFiles();
            $counter = 0;
            $imported_file_names = [];
            foreach ($files as $file) {
                $filename   = basename($file);
                $time       = Storage::disk('public')->lastModified($file);
                $result     = Str::endsWith(strtolower($filename), 'csv');

                if($result && $time >= strtotime(date('Y-m-d'))) {
                    $counter++;
                    $imported_file_names[] = $filename;
                    $path = Storage::disk('public')->path($file);
                    AppHelper::parse_csv($filename, $path);
                }
            }

            if($counter) {
                $response = [
                    'success' => true,
                    'message' => $counter . ' file(s) are imported successfully.<br>' . implode('<br>', $imported_file_names)
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'No new file found.'
                ];
            }
        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'message' => 'Error: '. $e->getMessage()
            ];
        } catch (\Throwable $e) {
            $response = [
                'success' => false,
                'message' => 'Error: '. $e->getMessage()
            ];
        }

        return response()->json($response);
    }

    public function api_data() {
        return view("products.list");
    }

    public function ajaxGetApiData(Request $request) {
        $client = new \GuzzleHttp\Client();
        $response = $client->request(
                'GET',
                'http://diamonderp.in/ulgd/PortalAPI/GetStock',
                [
                    'form_params' => [
                        'PortalKey' => '8b68eaab-1cc7-4009-a324-386b92daf2f6',
                        'FromCarat' => 0.2,
                        'ToCarat'   => 25,
                        'Shape'     => 'Round,Cushion,Oval,Princess,Emerald,Pear,Marquise,Asscher,Radiant,Heart',
                        'Color'     => 'D,E,F,G,H',
                        'Clarity'   => 'FL,IF,VVS1,VVS2,VS1,VS2,SI1',
                        'Cut'       => 'ID,EX,VG',
                        'Polish'    => 'EX,VG',
                        'Symmetry'  => 'EX,VG',
                        'Fluo'      => 'NONE,FAINT',
                    ]
                ]
        );

        $responseBody = $response->getBody()->getContents();
        $responseData = json_decode($responseBody, true);

        // $diamondData = (new DiamondULGDResource($responseData))->toArray($request);
        // echo '<pre>';
        // print_r($diamondData);
        // die;
        // AppHelper::import_from_api($diamondData, '');

        return  DiamondULGDResource::make($responseData);
    }
}
