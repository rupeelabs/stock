<?php
/**
 * Created by PhpStorm.
 * Date: 2019/1/6
 * Time: 下午7:51
 */

namespace App\Service;

use App\Mail\AVE;
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
            "select a.code, a.name  from stock as a INNER JOIN ave_testing as b on a.code=b.code
where b.date='%s'",
            $date
        ));
        if ($stocks) {
            Mail::send(new AVE($stocks));
        }

    }

    /**
     * 金叉提醒
     */
    public function brandistockRemind()
    {
        $today = date('Y-m-d');
        $data = [];
        $stocks = \DB::select("select * from stock");
        foreach ($stocks as $stock) {
            $code = $stock->code;
            $flows = \DB::select("select * from stock_flow where code=? ORDER by date desc limit 2", [$code]);
            if ($flows[0]->date != $today)
                continue;
            if ($flows[0]->five_ave > $flows[0]->sixty_ave && $flows[1]->five_ave < $flows[1]->sixty_ave) {
                $data[] = $stock;
            }
        }

        if (empty($data)) {
            return;
        }
        Mail::send(new NiceStock($data));
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