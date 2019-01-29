<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Http\Controllers\Command\OrderCommand;
use App\Http\Controllers\Command\StockCommand;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        #Function untuk update current price
        $schedule->call(function(){
            OrderCommand::cancelExpiredOrder();
            OrderCommand::addPoint();
            OrderCommand::countExpiredPoint;
        })->everyTenMinutes();
        
        $schedule->call(function(){
            #Function untuk update stock booked dan sold
            StockCommand::countStockBookSold();
        })->daily();
    }
}
