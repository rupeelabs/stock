<?php
/**
 * Created by PhpStorm.
 * Date: 2019/1/6
 * Time: 下午7:37
 */

namespace App\Service;


class StockAnalyzerService
{
    public function analyze()
    {
        return $this->getKDJ();
        $today = date('Y-m-d');
        $stocks = \DB::select("select code from stock");
        foreach ($stocks as $stock) {
            $fiveAve = $this->getAve($stock->code, 5);
            $tenAve = $this->getAve($stock->code, 10);
            $twentyAve = $this->getAve($stock->code, 20);
            $sixtyAve = $this->getAve($stock->code, 60);
            if (!\DB::select("select id from stock_flow where code=? and date=?", [$stock->code, $today]))
                continue;
            \DB::update(
                "update stock_flow set five_ave=?, ten_ave=?, twenty_ave=?, sixty_ave=? where code=? and date=?",
                [$fiveAve, $tenAve, $twentyAve, $sixtyAve, $stock->code, $today]
            );
        }

    }

    public function getAve($code, $limit)
    {
        $ave = \DB::table('stock_flow')->where('code', $code)
            ->where('date', '<=', date('Y-m-d'))
            ->orderBy('date', 'desc')
            ->limit($limit)->avg('close');
        return $ave;
    }

    public function getKDJ()
    {
        $code = 002230;
        $flows = \DB::select("select * from stock_flow where code=? order by id asc", [$code]);
        foreach ($flows as $key => $flow) {
            var_dump($flow);exit;
            $index = $key;
            $lowest = $flow['lowest'];
            $highest = $flow['highest'];
            while ($index >= 0 && ($key - $index + 1) <= 9) {
                if ($flow['lowest'] < $lowest) {
                    $lowest = $flow['lowest'];
                }
                if ($flow['highest'] > $highest) {
                    $highest = $flow['highest'];
                }
                $index --;
            }
            $rsv = (($flow['close'] - $lowest)/($highest - $lowest)) * 100;
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