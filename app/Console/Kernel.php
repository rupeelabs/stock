<?php

namespace App\Console;

use App\Console\Commands\AVET;
use App\Console\Commands\BuyingReminder;
use App\Console\Commands\FART;
use App\Console\Commands\FirstReminder;
use App\Console\Commands\KDJ;
use App\Console\Commands\MACD;
use App\Console\Commands\MACDT;
use App\Console\Commands\PS;
use App\Console\Commands\SecondReminder;
use App\Console\Commands\StockFlowSpider;
use App\Console\Commands\Spider;
use App\Console\Commands\StockAnalyzer;
use App\Console\Commands\ThirdReminder;
use App\Console\Commands\TR;
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

        $schedule->command(Spider::class)->cron('0 10 * * *');
        $schedule->command(PS::class)->cron('0 11 * * *');
        $schedule->command(StockFlowSpider::class)->cron('0 16 * * *');
        $schedule->command(StockAnalyzer::class)->cron('0 17 * * *');
        $schedule->command(AVET::class)->cron('30 17 * * *');
        $schedule->command(FART::class)->cron('40 17 * * *');
        $schedule->command(KDJ::class)->cron('0 18 * * *');
        $schedule->command(MACD::class)->cron('30 18 * * *');
        $schedule->command(TR::class)->cron('00 19 * * *');
        $schedule->command(MACDT::class)->cron('30 19 * * *');


        $schedule->command(ThirdReminder::class)->cron('10 9 * * *');
        $schedule->command(FirstReminder::class)->cron('10 20 * * *');
        $schedule->command(SecondReminder::class)->cron('20 20 * * *');
        $schedule->command(BuyingReminder::class)->cron('30 20 * * *');
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
