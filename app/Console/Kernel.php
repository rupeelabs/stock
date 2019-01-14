<?php

namespace App\Console;

use App\Console\Commands\BuyingReminder;
use App\Console\Commands\FirstReminder;
use App\Console\Commands\KDJ;
use App\Console\Commands\MACD;
use App\Console\Commands\SecondReminder;
use App\Console\Commands\StockFlowSpider;
use App\Console\Commands\Spider;
use App\Console\Commands\StockAnalyzer;
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
        Commands\Spider::class,
        SecondReminder::class,
        StockFlowSpider::class,
        StockAnalyzer::class,
        FirstReminder::class
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

        $schedule->command(Spider::class)->timezone('Asia/Chongqing')->cron('0 10 * * *');
        $schedule->command(StockFlowSpider::class)->timezone('Asia/Chongqing')->cron('0 16 * * *');
        $schedule->command(StockAnalyzer::class)->timezone('Asia/Chongqing')->cron('0 18 * * *');
        $schedule->command(KDJ::class)->timezone('Asia/Chongqing')->cron('0 19 * * *');
        $schedule->command(MACD::class)->timezone('Asia/Chongqing')->cron('0 20 * * *');

        $schedule->command(FirstReminder::class)->timezone('Asia/Chongqing')->cron('10 22 * * *');
        $schedule->command(SecondReminder::class)->timezone('Asia/Chongqing')->cron('20 22 * * *');
        $schedule->command(BuyingReminder::class)->timezone('Asia/Chongqing')->cron('30 22 * * *');
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
