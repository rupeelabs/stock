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

    /**
     * 5日与10日金叉
     * @param string $code
     */
    public function average($code = '')
    {
        if ($code) {
            $sql = sprintf("select code from stock where market_type=1 and code='%s'", $code);
        } else {
            $sql = "select code from stock where market_type=1";
        }
        $stocks = \DB::select($sql);
        foreach ($stocks as $stock) {
            $code = $stock->code;
            $flows = \DB::select(sprintf(
                "select * from stock_flow where code='%s' order by id asc",
                $code
            ));
            foreach ($flows as $key => $flow) {
                if ($key < 1) continue;
                if (
                    $flow->five_ave > $flow->ten_ave &&
                    $flows[$key - 1]->five_ave < $flows[$key - 1]->ten_ave &&
                    $flow->five_ave < $flow->twenty_ave
                ) {
                    if (\DB::select(sprintf(
                        "select id from ave_testing where code='%s' and date='%s'",
                        $flow->code,
                        $flow->date
                    ))) {
                        continue;
                    }
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
    }


    /**
     * 十字架 K线图
     * @param string $code
     */
    public function cross($code = '')
    {
        if ($code) {
            $sql = sprintf("select code from stock where market_type=1 and code='%s'", $code);
        } else {
            $sql = "select code from stock where market_type=1";
        }
        $stocks = \DB::select($sql);
        foreach ($stocks as $stock) {
            $code = $stock->code;
            $flows = \DB::select(sprintf(
                "select * from stock_flow where code='%s' order by id asc",
                $code
            ));
            foreach ($flows as $key => $flow) {
                if ($key < 1) continue;
                if (
                    $flow->lowest > 0 &&
                    $flow->open > 0 &&
                    $flow->close > $flow->open &&
//                    (($flow->highest/$flow->lowest - 1)*100 >8) &&
//                    (($flow->close/$flow->open - 1)*100 <0.3) &&
                    (($flow->open/$flow->lowest - 1)*100 >10)
                ) {
                    if (\DB::select(sprintf(
                        "select id from `cross` where code='%s' and date='%s'",
                        $flow->code,
                        $flow->date
                    ))) {
                        continue;
                    }
                    \DB::insert(
                        "insert into `cross` (code, date) 
value(?,?)",
                        [
                            $flow->code,
                            $flow->date
                        ]
                    );
                }
            }
        }
    }

    /**
     * 5日与60日金叉
     * @param string $code
     */
    public function fiveAndSixtyGolden($code = '')
    {
        if ($code) {
            $sql = sprintf("select code from stock where market_type=1 and code='%s'", $code);
        } else {
            $sql = "select code from stock where market_type=1";
        }
        $stocks = \DB::select($sql);
        foreach ($stocks as $stock) {
            $code = $stock->code;
            $flows = \DB::select(sprintf(
                "select * from stock_flow where code='%s' order by id asc",
                $code
            ));
            foreach ($flows as $key => $flow) {
                if ($key < 1) continue;
                if (
                    $flow->five_ave > $flow->sixty_ave &&
                    $flows[$key - 1]->five_ave < $flows[$key - 1]->sixty_ave
                ) {
                    if (\DB::select(sprintf(
                        "select id from five_sixty where code='%s' and date='%s'",
                        $flow->code,
                        $flow->date
                    ))) {
                        continue;
                    }
                    \DB::insert(
                        "insert into five_sixty (code, date) 
value(?,?)",
                        [
                            $flow->code,
                            $flow->date
                        ]
                    );
                }
            }
        }
    }

    public function macd($code = '')
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
                if ($key < 1) continue;
                if (
                    $flow->diff >= $flow->dea &&
                    $flows[$key - 1]->diff < $flows[$key - 1]->dea &&
                    $flow->diff < 0 &&
                    $flow->dea < 0
//                    $flow->macd > -0.05 &&
//                    $flow->diff < -0.07 &&
//                    $flow->dea < -0.07
                ) {
                    if (!$this->hasAveGolden($flows, $flow->date, 5)) {
                        continue;
                    }
                    if (!$this->hasKDJGolden($flows, $flow->date, 13)) {
                        continue;
                    }
                    if ($this->hasAveDeadSixty($flows, $flow->date, 13)) {
                        continue;
                    }
                    if ($this->hasAveDeadTwenty($flows, $flow->date)) {
                        continue;
                    }
//                    if (!$this->isTurnoverRateBThan($flows, $flow->date, 1, 3)) {
//                        continue;
//                    }
                    if (\DB::select(sprintf(
                        "select id from macd_testing where code='%s' and date='%s'",
                        $flow->code,
                        $flow->date
                    ))) {
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
                }
            }
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

    /**
     * 过去$past日的平均换手率是否大于$a
     * @param $a
     * @param $past
     */
    public function isTurnoverRateBThan(&$flows, $curDate, $a, $past)
    {
        foreach ($flows as $key => $flow) {
            if ($flow->date == $curDate) {
                break;
            }
        }
        $index = $key;
        $turnoverRate = $turnover = 0;
        while (($key - $index) < $past && ($index - 1) > 0) {
            $turnover += $flow->turnover_rate;
            $index --;
        }
        $turnoverRate = round($turnover/($key-$index), 2);
        return $turnoverRate >= $a;
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
        $limit = 30;
        foreach ($data as $item) {
            $flows = \DB::select(sprintf(
                "select * from stock_flow where code='%s' and date>='%s' order by date asc limit %d",
                $item->code,
                $item->date,
                $limit
            ));
//            var_dump($flows);exit;
            $close = $highest = $flows[0]->close;
            foreach ($flows as $key => $flow) {
                if ($flow->close > $highest) {
                    $highest = $flow->close;
                }
            }
            if ($close != 0) {
                $growthRate = round(($highest - $close) / $close, 4) * 100;
            } else {
                $growthRate = $highest * 100;
            }

            \DB::update(sprintf(
                "update macd_testing set growth_rate='%s',highest='%s',
close='%s' where id=%d",
                $growthRate,
                $highest,
                $close,
                $item->id
            ));
        }

    }

    public function isSidewayInPastDays(&$flows, $code, $date)
    {

    }


    public function fiveAveRise($code = '')
    {
        if ($code) {
            $sql = sprintf("select code from stock where market_type=1 and code='%s'", $code);
        } else {
            $sql = "select code from stock where market_type=1";
        }
        $stocks = \DB::select($sql);
        foreach ($stocks as $stock) {
            $code = $stock->code;
            $flows = \DB::select(sprintf(
                "select * from stock_flow where code='%s' order by id asc",
                $code
            ));
            foreach ($flows as $key => $flow) {
                if ($key < 10) continue;
                if (
                    $flows[$key - 1]->five_ave < $flow->five_ave &&
                    $flows[$key - 1]->five_ave < $flows[$key - 2]->five_ave &&
                    $flow->five_ave < $flow->ten_ave &&
                    $flows[$key - 1]->five_ave > 0 &&
                    (($flows[$key - 1]->ten_ave-$flows[$key - 1]->five_ave)/$flows[$key - 1]->five_ave*100)>10
                ) {
                    if (\DB::select(sprintf(
                        "select id from five_ave_rise where code='%s' and date='%s'",
                        $flow->code,
                        $flow->date
                    ))) {
                        continue;
                    }
                    \DB::insert(
                        "insert into five_ave_rise (code, date) 
value(?,?)",
                        [
                            $flow->code,
                            $flow->date
                        ]
                    );
                }
            }
        }
    }


}