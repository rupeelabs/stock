<?php
/**
 * Created by PhpStorm.
 * Date: 2019/1/6
 * Time: 下午7:51
 */

namespace App\Service;

use App\Mail\Stock;
use Illuminate\Support\Facades\Mail;


class KDJService
{
    public function getKDJ($code = '')
    {
        if ($code) {
            $sql = sprintf("select code from stock where code='%s'", $code);
        } else {
            $sql = "select code from stock";
        }
        $stocks = \DB::select($sql);
        foreach ($stocks as $stock) {
            $flows = \DB::select(sprintf(
                "select * from stock_flow where code='%s' order by id asc",
                $stock->code
            ));
            $yestodayK = $yestodayD = $yestodayJ = 0;
            foreach ($flows as $key => $flow) {
                $index = $key;
                $lowest = $flow->lowest;
                $highest = $flow->highest;
                while ($index >= 0 && ($key - $index) < 9) {
                    if ($flows[$index]->lowest < $lowest) {
                        $lowest = $flows[$index]->lowest;
                    }
                    if ($flows[$index]->highest > $highest) {
                        $highest = $flows[$index]->highest;
                    }
                    $index--;
                }
                if ($highest == $lowest) {
                    $rsv = 0;
                } else {
                    $rsv = round((($flow->close - $lowest) / ($highest - $lowest)) * 100, 4);
                }
                if ($key == 0) {//上市第一天K=RSV
                    $k = $d = $j = round($rsv, 2);
                    $yestodayK = $k;
                    $yestodayD = $d;
                    $yestodayJ = $j;
                } else {
                    $todayRange = $flow->close - $flows[$key - 1]->close;
                    if ($todayRange == 0) {//与昨日对比涨跌幅为0
                        $k = $yestodayK;
                        $d = $yestodayD;
                        $j = $yestodayJ;
                    } else {
                        $k = round(($yestodayK * 2) / 3 + $rsv / 3, 2);
                        $d = round(($yestodayD * 2) / 3 + $k / 3, 2);
                        $j = round(3 * $k - 2 * $d, 2);
                    }
                    $yestodayK = $k;
                    $yestodayD = $d;
                    $yestodayJ = $j;
                }
                \DB::update(
                    "update stock_flow set kdj_k=?, kdj_d=?, kdj_j=? where id=?",
                    [$k, $d, $j, $flow->id]
                );
            }
        }
    }
}