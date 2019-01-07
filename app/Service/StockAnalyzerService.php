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
}