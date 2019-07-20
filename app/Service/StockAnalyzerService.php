<?php
/**
 * Created by PhpStorm.
 * Date: 2019/1/6
 * Time: 下午7:37
 */

namespace App\Service;


class StockAnalyzerService
{
    public function analyze($code = '', $isAll = 'no')
    {
        $today = date('Y-m-d');
        if ($code) {
            $sql = sprintf("select code from stock where code='%s'", $code);
        } else {
            $sql = "select code from stock";
        }
        $stocks = \DB::select($sql);
        foreach ($stocks as $stock) {
            $flows = \DB::select(sprintf(
                "SELECT * from stock_flow where code = '%s' order by id asc",
                $stock->code
            ));
            if (empty($flows))
                continue;
            $temp = $flows;
            if ($isAll == 'no') {
                $temp = [];
                $temp[] = last($flows);
            }
            foreach ($temp as $flow) {
                $fiveAve = $this->getAve($flows, 5, $flow->date);
                $tenAve = $this->getAve($flows, 10, $flow->date);
                $twentyAve = $this->getAve($flows, 20, $flow->date);
                $sixtyAve = $this->getAve($flows, 60, $flow->date);
//                if (!\DB::select("select id from stock_flow where code=? and date=?", [$stock->code, $today]))
//                    continue;
                \DB::update(
                    "update stock_flow set five_ave=?, ten_ave=?, twenty_ave=?, sixty_ave=? where id=?",
                    [$fiveAve, $tenAve, $twentyAve, $sixtyAve, $flow->id]
                );
            }
        }

    }

    public function getAve(&$flows, $limit, $date)
    {
        foreach ($flows as $key => $flow) {
            if ($flow->date == $date) {
                break;
            }
        }
        $index = $key;
        $total = 0;
        while ($index >= 0 && ($key - $index) < $limit) {
            $total += $flows[$index]->close;
            $index --;
        }
        return round($total/$limit, 4);
    }

    public function shangzhang()
    {
        $sql = "select code from stock where market_type=1";
        $stocks = \DB::select($sql);

        $shangzhangStocks = [];
        foreach ($stocks as $stock) {
            $flows = \DB::select(sprintf(
                "SELECT * from stock_flow where code = '%s' order by id desc limit 0,1",
                $stock->code
            ));
            if (empty($flows)) continue;
            if ($flows[0]->close > $flows[0]->twenty_ave) {
                $shangzhangStocks[] = $stock->code;
            }
        }

        var_dump($shangzhangStocks);
    }

}