<?php
/**
 * Created by PhpStorm.
 * Date: 2019/1/6
 * Time: 下午7:51
 */

namespace App\Service;

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
        $goodStock = [];
        $today = date('Y-m-d');
        $flows = \DB::select(sprintf(
            "select * from stock_flow where code='%s' order by id asc",
            $code
        ));
        foreach ($flows as $key => $flow) {
            if ($key < 1) continue;
            if (
                $flow->diff > $flow->dea &&
                $flows[$key-1]->diff < $flows[$key-1]->dea &&
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
                \DB::insert(
                    "insert into macd_testing (code, date) 
value(?,?)",
                    [
                        $flow->code,
                        $flow->date
                    ]
                );
                if ($flow >= $today) {
                    $goodStock[] = $flow->code;
                }
            }
        }
        $goodStock = [234, 46456];
        $content = '';
        foreach ($goodStock as $item) {
            $content .= "{$item}\r\n";
        }
        Mail::raw($content, function ($message) {
            $to = '396444855@qq.com';
            $message ->to($to)->subject('买入');
        });
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

}