<?php
/**
 * Created by PhpStorm.
 * Date: 2019/1/6
 * Time: 下午7:51
 */

namespace App\Service;

use App\Mail\Stock;
use Illuminate\Support\Facades\Mail;


class BuyingPointService
{
    public function run($code = '')
    {
        $flows = \DB::select(sprintf(
            "select * from stock_flow where code='%s' order by id asc",
            $code
        ));
        $yestodayK = $yestodayD = $yestodayJ = 0;
        foreach ($flows as $key => $flow) {
            if ($flow->date < '2010-01-01')
                continue;
            if ($flow->kdj_k < 20 && $flow->kdj_d < 20 && $flow->kdj_j < 20) {
                $index = $key + 1;
                $highest = $flow->close;
                while (($index - $key) < 40) {
                    if (!isset($flows[$index])) {
                        break;
                    }
                    if ($flows[$index]->close > $highest) {
                        $highest = $flows[$index]->close;
                    }
                    $index ++;
                }
                $growthRate = round(($highest-$flow->close)/$flow->close)*100;
                \DB::insert(
                    "insert into buying_point (code, date, is_kdj_lowest, highest,growth_rate,close) 
value(?,?,1,?,?,?)",
                    [
                        $flow->code,
                        $flow->date,
                        $highest,
                        $growthRate,
                        $flow->close
                    ]
                );
            }
        }
    }
}