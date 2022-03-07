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
        //
        
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {   
        //call command
        $schedule->command('db:getSalesloftCall')->everyTenMinutes();
        //calldata command
        $schedule->command('db:getSalesloftCallData')->everyTenMinutes();
        //cadence command
        $schedule->command('db:getSalesloftCadence')->everyTenMinutes();
        //user command
        $schedule->command('db:getSalesloftUser')->daily();
        //people command(working leads)
        $schedule->command('db:getSalesloftPeople')->daily();
        //report cache command 
        $schedule->command('db:getCacheReports')->hourly();
        
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
