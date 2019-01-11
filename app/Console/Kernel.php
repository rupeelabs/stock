<?php

namespace App\Console;

use App\Console\Commands\FirstReminder;
use App\Console\Commands\SecondReminder;
use App\Console\Commands\Spider;
use App\Console\Commands\StockAnalyzer;
use App\Console\Commands\StockFlowSpider;
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
        Commands\Spider::class
    ];

    /**
     * 买点率统计：
     * 1、KDJ小于20
     * 2、KDJ金叉
     * 3、5日与10日均线金叉
     * 4、5日与20日金叉
     * 5、换手率>1
     *
     * 卖点概率统计：
     * 1、KDJ大于80
     * 2、5日均线下降
     * 3、
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(FirstReminder::class)->cron('0 21 * * *');
        $schedule->command(SecondReminder::class)->cron('30 21 * * *');
        $schedule->command(Spider::class)->cron('10 18 * * *');
        $schedule->command(StockFlowSpider::class)->cron('0 17 * * *');
        $schedule->command(StockAnalyzer::class)->cron('0 18 * * *');
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
