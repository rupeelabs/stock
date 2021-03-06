<?php
/**
 * Created by PhpStorm.
 * Date: 2019/1/6
 * Time: 下午7:51
 */

namespace App\Service;

use App\Mail\AVE;
use App\Mail\CrossStock;
use App\Mail\FAR;
use App\Mail\GoldenAboveSixtyStock;
use App\Mail\KdjLessThanTwentyStock;
use App\Mail\MacdThiceGlodStock;
use App\Mail\NewStock;
use App\Mail\NiceStock;
use App\Mail\VolRiseStock;
use Illuminate\Support\Facades\Mail;


class MailReminderService
{
    /**
     * 五日均线上升提醒
     */
    public function fiveAveRiseRemind($date)
    {
        $date = $date ?: date('Y-m-d');
        $stocks = \DB::select(sprintf(
            "select a.code, a.name  from stock as a INNER JOIN five_ave_rise as b on a.code=b.code
where b.date='%s'",
            $date
        ));
        if ($stocks) {
            Mail::send(new FAR($stocks));
        }

    }

    /**
     * 金叉提醒
     */
    public function brandistockRemind($date)
    {
        $date = $date ?: date('Y-m-d');
        $stocks = \DB::select(sprintf(
            "select a.code, a.name  from stock as a 
INNER JOIN ave_testing as b on a.code=b.code
where b.date='%s'",
            $date
        ));
        if ($stocks) {
            Mail::send(new AVE($stocks));
        }
    }

    public function buyingSigRemind($date)
    {
        $date = $date ?: date('Y-m-d');
        $stocks = \DB::select(sprintf(
            "select a.code, a.name  from stock as a INNER JOIN macd_testing as b on a.code=b.code
where b.date='%s'",
            $date
        ));
        if ($stocks) {
            Mail::send(new NiceStock($stocks));
        }
    }

    public function crossRemind($date)
    {
        $date = $date ?: date('Y-m-d');
        $stocks = \DB::select(sprintf(
            "select a.code, a.name  from stock as a INNER JOIN `cross` as b on a.code=b.code
where b.date='%s'",
            $date
        ));
        if ($stocks) {
            Mail::send(new CrossStock($stocks));
        }
    }

    public function newStockRemind($date)
    {
        $date = $date ?: date('Y-m-d');
        $sql = "select *  from stock 
where DATE_FORMAT(created_at,'%Y-%m-%d') ='{$date}'";
        $stocks = \DB::select($sql);
        if ($stocks) {
            Mail::send(new NewStock($stocks));
        }
    }

    public function macdTwiceGlodenRemind($date)
    {
        $date = $date ?: date('Y-m-d');
        $stocks = \DB::select(sprintf(
            "select a.code, a.name  from stock as a INNER JOIN `macd_twice_gold` as b on a.code=b.code
where b.date='%s'",
            $date
        ));
        if ($stocks) {
            Mail::send(new MacdThiceGlodStock($stocks));
        }
    }

    public function goldenAboveSixtyRemind($date)
    {
        $date = $date ?: date('Y-m-d');
        $stocks = \DB::select(sprintf(
            "select a.code, a.name  from stock as a INNER JOIN `golden_above` as b on a.code=b.code
where b.date='%s'",
            $date
        ));
        if ($stocks) {
            Mail::send(new GoldenAboveSixtyStock($stocks));
        }
    }

    public function kdjLessThanTwentyRemind($date)
    {
        $date = $date ?: date('Y-m-d');
        $stocks = \DB::select(sprintf(
            "select a.code, a.name  from stock as a INNER JOIN `kdj_twenty` as b on a.code=b.code
where b.date='%s'",
            $date
        ));
        if ($stocks) {
            Mail::send(new KdjLessThanTwentyStock($stocks));
        }
    }

    public function volRiseRemind($date)
    {
        $date = $date ?: date('Y-m-d');
        $stocks = \DB::select(sprintf(
            "select a.code, a.name  from stock as a INNER JOIN `vol_rise` as b on a.code=b.code
where b.date='%s'",
            $date
        ));
        if ($stocks) {
            Mail::send(new VolRiseStock($stocks));
        }
    }
}