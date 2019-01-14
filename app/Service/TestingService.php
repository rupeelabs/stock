<?php
/**
 * Created by PhpStorm.
 * Date: 2019/1/6
 * Time: 下午7:51
 */

namespace App\Service;

use App\Mail\NiceStock;
use App\Mail\Stock;
use Illuminate\Support\Facades\Mail;


class TestingService
{
    public function average($code = '')
    {
        $flows = \DB::select(sprintf(
            "select * from stock_flow where code='%s' order by id asc",
            $code
        ));
        foreach ($flows as $key => $flow) {
            if ($key < 1) continue;
            if ($flow->five_ave > $flow->ten_ave && $flows[$key-1]->five_ave < $flows[$key-1]->ten_ave) {
                \DB::insert(
                    "insert into ave_testing (code, date) 
value(?,?)",
                    [
                        $flow->code,
                        $flow->date
                    ]
                );
            }
        }
    }

    public function macd($code = '')
    {
        $goodStockCode = [];
        $today = date('Y-m-d');
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
                if ($key < 1) continue;
                if (
                    $flow->diff > $flow->dea &&
                    $flows[$key - 1]->diff < $flows[$key - 1]->dea &&
                    $flow->diff < 0 &&
                    $flow->dea < 0 &&
                    $flow->macd > -0.05 &&
                    $flow->diff < -0.07 &&
                    $flow->dea < -0.07
                ) {
                    if (!$this->hasAveGolden($flows, $flow->date, 13)) {
                        continue;
                    }
                    if (!$this->hasKDJGolden($flows, $flow->date, 13)) {
                        continue;
                    }
                    if ($this->hasAveDeadSixty($flows, $flow->date, 20)) {
                        continue;
                    }
                    if ($this->hasAveDeadTwenty($flows, $flow->date)) {
                        continue;
                    }
                    \DB::insert(
                        "insert into macd_testing (code, date) 
value(?,?)",
                        [
                            $flow->code,
                            $flow->date
                        ]
                    );
                    if ($flow->date >= $today) {
                        $goodStockCode[] = $flow->code;
                    }
                }
            }
        }
        if ($goodStockCode) {
            $goodStocks = \DB::select(sprintf(
                "select * from stock where code in(%s)",
                implode(',', $goodStockCode)
            ));
            Mail::send(new NiceStock($goodStocks));
        }
    }

    /**
     * 计算过去的交易日有无日均线的金叉
     * @param $flows
     * @param $curDate 当前日期
     * @param $past 过去交易日数
     */
    public function hasAveGolden(&$flows, $curDate, $past)
    {
        foreach ($flows as $key => $flow) {
            if ($flow->date == $curDate) {
                break;
            }
        }
        $index = $key;
        while (($key - $index) < $past && ($index - 1) > 0) {
            if (
                $flows[$index]->five_ave >= $flows[$index]->ten_ave &&
                $flows[$index - 1]->five_ave < $flows[$index - 1]->ten_ave
            ) {
                return true;
            }
            $index --;
        }
        return false;
    }

    public function hasKDJLowest(&$flows, $curDate, $past)
    {
        foreach ($flows as $key => $flow) {
            if ($flow->date == $curDate) {
                break;
            }
        }
        $index = $key;
        while (($key - $index) < $past && ($index - 1) > 0) {
            if (
                $flows[$index]->kdj_k < 20 &&
                $flows[$index]->kdj_d < 20
            ) {
                return true;
            }
            $index --;
        }
        return false;
    }

    public function hasAveDeadSixty(&$flows, $curDate, $past = 13)
    {
        foreach ($flows as $key => $flow) {
            if ($flow->date == $curDate) {
                break;
            }
        }
        $index = $key;
        while (($key - $index) < $past && ($index - 1) > 0) {
            if (
                $flows[$index]->five_ave <= $flows[$index]->sixty_ave &&
                $flows[$index - 1]->five_ave > $flows[$index - 1]->sixty_ave
            ) {
                return true;
            }
            $index --;
        }
        return false;
    }

    public function hasAveDeadTwenty(&$flows, $curDate, $past = 8)
    {
        foreach ($flows as $key => $flow) {
            if ($flow->date == $curDate) {
                break;
            }
        }
        $index = $key;
        while (($key - $index) < $past && ($index - 1) > 0) {
            if (
                $flows[$index]->five_ave <= $flows[$index]->twenty_ave &&
                $flows[$index - 1]->five_ave > $flows[$index - 1]->twenty_ave
            ) {
                return true;
            }
            $index --;
        }
        return false;
    }

    /**
     * 计算过去的交易日有无日KDJ的金叉
     * @param $flows
     * @param $curDate 当前日期
     * @param $past 过去交易日数
     */
    public function hasKDJGolden(&$flows, $curDate, $past)
    {
        foreach ($flows as $key => $flow) {
            if ($flow->date == $curDate) {
                break;
            }
        }
        $index = $key;
        while (($key - $index) < $past && ($index - 1) > 0) {
            if (
                $flows[$index]->kdj_j >= $flows[$index]->kdj_k &&
                $flows[$index - 1]->kdj_j < $flows[$index - 1]->kdj_k &&
                $flows[$index]->kdj_j < 30 &&
                $flows[$index]->kdj_k < 30
            ) {
                return true;
            }
            $index --;
        }
        return false;
    }


    public function assert()
    {
        $data = \DB::select("select * from macd_testing");
        foreach ($data as $item) {

        }

    }

}