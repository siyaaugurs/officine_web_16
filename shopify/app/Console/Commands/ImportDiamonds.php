<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Storage;
use App\Helpers\AppHelper;
use Illuminate\Support\Str;

class ImportDiamonds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import-diamonds';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import all csv files from public folder';

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
        $csvFiles = [];
        try {
            $allFiles = Storage::disk('public')->allFiles();
            foreach ($allFiles as $file) {
                $time = Storage::disk('public')->lastModified($file);
                $filename = basename($file);
                $result = Str::endsWith(strtolower($filename), 'csv');

                if($time >= strtotime(date('Y-m-d')) && $result) {
                    $csvFiles[] = $file;
                }
            }
            if(count($csvFiles) > 0) {
                $bar = $this->output->createProgressBar(count($csvFiles));
                $bar->start();

                foreach ($csvFiles as $file) {
                    $path = Storage::disk('public')->path($file);
                    $filename = basename($path);
                    AppHelper::parse_csv($filename, $path);
                    $bar->advance();
                }
                $bar->finish();
                $this->info('All files have been imported successfully.');
            } else {
                $this->info('No new csv file found.');
            }

        } catch (\Exception $e) {
            $this->info($e->getMessage());
        } catch (\Throwable $e) {
            $this->info($e->getMessage());
        }
    }
}
