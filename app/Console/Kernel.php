<?php

namespace App\Console;

use App\Console\Commands\AVET;
use App\Console\Commands\BuyingReminder;
use App\Console\Commands\CROSS;
use App\Console\Commands\FART;
use App\Console\Commands\FifthReminder;
use App\Console\Commands\FirstReminder;
use App\Console\Commands\ForthReminder;
use App\Console\Commands\GAT;
use App\Console\Commands\KDJ;
use App\Console\Commands\MACD;
use App\Console\Commands\MACDT;
use App\Console\Commands\PS;
use App\Console\Commands\SecondReminder;
use App\Console\Commands\SeventhReminder;
use App\Console\Commands\SixthReminder;
use App\Console\Commands\StockFlowSpider;
use App\Console\Commands\Spider;
use App\Console\Commands\StockAnalyzer;
use App\Console\Commands\TAPE;
use App\Console\Commands\TGT;
use App\Console\Commands\ThirdReminder;
use App\Console\Commands\TR;
use App\Console\Commands\VRT;
use App\Console\Commands\ZHIBIAO;
use App\Console\Commands\ZLZJ;
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
        $schedule->command(CROSS::class)->cron('40 19 * * *');
        $schedule->command(TGT::class)->cron('55 19 * * *');
        $schedule->command(TAPE::class)->cron('0 11 * * *');
        $schedule->command(ZHIBIAO::class)->cron('30 11 * * *');
        $schedule->command(GAT::class)->cron('10 19 * * *');
        $schedule->command(VRT::class)->cron('22 19 * * *');
//        $schedule->command(ZLZJ::class)->cron('*/3 * * * *');


        $schedule->command(ThirdReminder::class)->cron('10 10 * * *');
        $schedule->command(FirstReminder::class)->cron('10 20 * * *');
        $schedule->command(SecondReminder::class)->cron('20 20 * * *');
        $schedule->command(BuyingReminder::class)->cron('30 20 * * *');
        $schedule->command(ForthReminder::class)->cron('40 20 * * *');
        $schedule->command(FifthReminder::class)->cron('45 20 * * *');
        $schedule->command(SixthReminder::class)->cron('50 20 * * *');
        $schedule->command(SeventhReminder::class)->cron('55 20 * * *');
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
