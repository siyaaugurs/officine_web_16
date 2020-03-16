<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\DiamondApis;
use App\Helpers\AppHelper;
use App\Http\Resources\DiamondULGDResource;

class FetchDiamonds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch-diamonds';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch diamonds from the different apis';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $abbreviations = config('diamonds.abbreviation');
            $short_names = array_map('strtoupper', array_keys(array_flip($abbreviations)));
            $shapes = config('diamonds.shapes');
            $cuts = config('diamonds.cut');
            $colors = config('diamonds.colors');
            $clarities = config('diamonds.clarities');
            $fluorescences = config('diamonds.fluorescences');
            $polish = config('diamonds.polish');
            $symmetry = config('diamonds.symmetry');

            $diamondApis = DiamondApis::all();
            if(count($diamondApis) > 0) {
                foreach ($diamondApis as $diamondApi) {
                    $this->info($diamondApi->name);
                    $client = new \GuzzleHttp\Client();
                    $response = $client->request(
                            'GET',
                            trim($diamondApi->url),
                            [
                                'form_params' => $diamondApi->input_params
                            ]
                    );
                    $responseBody   = $response->getBody()->getContents();
                    $responseData   = json_decode($responseBody, true);
                    $diamondData    = (new DiamondULGDResource($responseData))->toArray(null);

                    $bar = $this->output->createProgressBar(count($diamondData));
                    $bar->start();

                    if(count($diamondData) > 0) {
                        Product::where('api_id', $diamondApi->id)->delete();

                        foreach ($diamondData as $row) {
                            if(!AppHelper::validateDiamondData($row, $abbreviations, $short_names, $shapes, $cuts, $colors, $clarities, $fluorescences, $polish, $symmetry)) {
                                continue;
                            }
                            $row['api_id'] = $diamondApi->id;
                            $data = Product::updateOrCreate(
                                [
                                    'report_no' => $row['report_no'],
                                    'stock_no' => $row['stock_no']
                                ],
                                $row
                            );
                            AppHelper::create_product($data);
                            // break;
                            $bar->advance();
                        }
                    } else {
                        $bar->advance();
                    }

                    $bar->finish();

                    $this->info('All data have been imported successfully.');
                }
            } else {
                $this->info('No api found!');
            }
        } catch (\Exception $e) {
            $this->info($e->getMessage());
        } catch (\Throwable $e) {
            $this->info($e->getMessage());
        }
    }
}
