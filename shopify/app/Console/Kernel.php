<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\ImportDiamonds::class,
        Commands\FetchDiamonds::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('import-diamonds')
                ->timezone('Europe/Amsterdam')
                ->at('05:00')
                ->withoutOverlapping()
                ->emailOutputTo('shashank@augurs.in', true);

        $schedule->command('fetch-diamonds')
                ->timezone('Europe/Amsterdam')
                ->at('05:00')
                ->withoutOverlapping()
                ->emailOutputTo('shashank@augurs.in', true);
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
