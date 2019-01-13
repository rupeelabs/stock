<?php
/**
 * Created by PhpStorm.
 * Date: 2019/1/6
 * Time: 下午7:51
 */

namespace App\Service;

use App\Mail\Stock;
use Illuminate\Support\Facades\Mail;


class MACDService
{
    public function handle($code = '')
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
            $yestodayEMA12 = $yestodayEMA26 = $yestodayDEA = 0;
            foreach ($flows as $key => $flow) {
                if ($key == 0) {
                    $diff = $dea = $macd = 0;
                    $yestodayEMA12 = $yestodayEMA26 = $flow->close;
                    $yestodayDEA = 0;
                } else {
                    $yestodayEMA12 = $ema12 = round($yestodayEMA12 * 11 / 13 + $flow->close * 2 / 13, 5);
                    $yestodayEMA26 = $ema26 = round($yestodayEMA26 * 25 / 27 + $flow->close * 2 / 27, 5);
                    $diff = round($ema12 - $ema26, 3);
                    $yestodayDEA = $dea = round($yestodayDEA * 8 / 10 + $diff * 2 / 10, 3);
                    $macd = round(2 * ($diff - $dea), 3);
                }
                \DB::update(
                    "update stock_flow set diff=?, dea=?, macd=? where id=?",
                    [$diff, $dea, $macd, $flow->id]
                );
            }
        }
    }
}