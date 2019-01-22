<?php
/**
 * Created by PhpStorm.
 * Date: 2019/1/6
 * Time: 下午7:51
 */

namespace App\Service;

use App\Mail\AVE;
use App\Mail\FAR;
use App\Mail\NiceStock;
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
            "select a.code, a.name  from stock as a INNER JOIN ave_testing as b on a.code=b.code
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
}