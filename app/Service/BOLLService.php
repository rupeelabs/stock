<?php
/**
 * Created by PhpStorm.
 * Date: 2019/1/6
 * Time: 下午7:51
 */

namespace App\Service;

use App\Mail\Stock;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


class BOLLService
{
    const N = 20;

    const K = 2;

    public function handle($code = '', $isAll = 'no')
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
            foreach ($flows as $key => $flow) {
                if ($isAll == 'no' && $flow->date < date('Y-m-d')) {
                    continue;
                }
                if ($key < (self::N - 1)) {
                    continue;
                }
                $MA = $this->getMA($flows, $key, self::N-1);
//                $temp = 0;
//                foreach ($slice as $slouse) {
//                    $temp += pow(($slouse->close - $MA), 2);
//                }
//                $MD = sqrt($temp/self::N);

                echo $MA;exit;
                $sql = sprintf(
                    "update stock_flow set diff='%s', dea='%s', macd='%s',ema12='%s',ema26='%s' where id=%d",
                    $diff, $dea, $macd, $ema12, $ema26, $flow->id
                );
                \DB::update($sql);
            }
        }
    }

    private function getSumOfClose(&$flows)
    {
        $sum = 0;
        foreach ($flows as $flow) {
            $sum += $flow->close;
        }
        echo $sum;exit;
        return $sum;
    }

    public function getMA(&$flows, $key, $length)
    {
        $slice = array_slice($flows, $key - $length + 1, $length);
        $sum = $this->getSumOfClose($slice);
        return round($sum/$length, 4);
    }
}