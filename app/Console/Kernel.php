<?php

namespace App\Console;

use App\Console\Commands\RefreshWechatTicketCommand;
use App\Console\Commands\RefreshWechatTokenCommand;
use App\Console\Commands\TestCommand;
use App\Models\Activity;
use App\Models\WechatApp;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        TestCommand::class,
        RefreshWechatTokenCommand::class,
        RefreshWechatTicketCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//        $schedule->command(TestCommand::class)->everyTwoHours();
//        $schedule->command(RefreshWechatTicketCommand::class)->everyTwoHours();
//        $schedule->command(RefreshWechatTokenCommand::class)->everyTwoHours();
    }
}
