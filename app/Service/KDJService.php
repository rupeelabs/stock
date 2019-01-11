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
    public function getKDJ()
    {
        $code = '002230';
        $flows = \DB::select(sprintf(
            "select * from stock_flow where code='%s' order by id asc",
            $code
        ));
        foreach ($flows as $key => $flow) {
            var_dump($flow);exit;
            $index = $key;
            $lowest = $flow->lowest;
            $highest = $flow->highest;
            while ($index >= 0 && ($key - $index + 1) <= 9) {
                if ($flow->lowest < $lowest) {
                    $lowest = $flow->lowest;
                }
                if ($flow->highest > $highest) {
                    $highest = $flow->highest;
                }
                $index --;
            }
            $rsv = (($flow->close - $lowest)/($highest - $lowest)) * 100;
            if ($key == 0) {//上市第一天K=RSV
                $k = $d = $j = $rsv;
            } else {
                $k = ($flow[$key - 1]['kdj_k'] * 2) / 3 + $rsv / 3;
                $d = ($flow[$key - 1]['kdj_d'] * 2) / 3 + $k / 3;
                $j = 3 * $k - 2 * $d;
            }
            \DB::update(
                "update stock_flow set kdj_k=?, kdj_d=?, kdj_j=? where id=?",
                [$k, $d, $j, $flow['id']]
            );
        }
    }
}