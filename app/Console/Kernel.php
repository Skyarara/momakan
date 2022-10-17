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
        Commands\BuatOrder::class,
        Commands\DefaultChange::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        
        $schedule->command('buat:order')
            ->dailyAt('23:30')
            ->timezone('Asia/Makassar')
            ->appendOutputTo(storage_path('logs/command-schedule.log'));
        $schedule->command('change:default')
            ->dailyAt('23:45')
            ->timezone('Asia/Makassar')
            ->appendOutputTo(storage_path('logs/command-schedule.log'));

    }




    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
