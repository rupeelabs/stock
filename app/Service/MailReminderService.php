<?php
/**
 * Created by PhpStorm.
 * Date: 2019/1/6
 * Time: 下午7:51
 */

namespace App\Service;

use App\Mail\Stock;
use Illuminate\Support\Facades\Mail;


class MailReminderService
{
    /**
     * 五日均线上升提醒
     */
    public function fiveAveRiseRemind()
    {
        $today = date('Y-m-d');
        $data = [];
        $stocks = \DB::select("select code from stock");
        foreach ($stocks as $stock) {
            $code = $stock->code;
            $flows = \DB::select("select * from stock_flow where code=? ORDER by date desc limit 2", [$code]);
            if (empty($flows))
                continue;
            if ($flows[0]->date != $today)
                continue;
            if ($flows[0]->five_ave > $flows[1]->five_ave) {
                $stock['today_close'];
                $stock['yesterday_close'];
                $data[] = $stock;
            }
        }
        if (empty($data)) {
            return;
        }
        $content = '';
        foreach ($data as $item) {
            $content .= "代码：{$item['code']} 名称：{$item['name']} 昨收：{$item['yesterday_close']} 今收：{$item['today_close']}\r\n";
        }
        Mail::raw($content, function ($message) {
            $to = '396444855@qq.com';
            $message ->to($to)->subject('五日均线上升股票');
        });


    }

    /**
     * 金叉提醒
     */
    public function brandistockRemind()
    {
        $today = date('Y-m-d');
        $data = [];
        $stocks = \DB::select("select code from stock");
        foreach ($stocks as $stock) {
            $code = $stock->code;
            $flows = \DB::select("select * from stock_flow where code=? ORDER by date desc limit 2", [$code]);
            if ($flows[0]->date != $today)
                continue;
            if ($flows[0]->five_ave > $flows[0]->sixty_ave && $flows[1]->five_ave < $flows[1]->sixty_ave) {
                $data[] = $stock;
            }
        }

        if (empty($data)) {
            return;
        }
        $content = '';
        foreach ($data as $item) {
            $content .= "代码：{$item['code']} 名称：{$item['name']} 昨收：{$item['yesterday_close']} 今收：{$item['today_close']}\r\n";
        }
        Mail::raw($content, function ($message) {
            $to = '396444855@qq.com';
            $message ->to($to)->subject('金叉股票');
        });
    }

    public function buyingSigRemind()
    {
        $stocks = \DB::select(sprintf(
            "select a.code, a.name  from stock as a INNER JOIN macd_testing as b on a.code=b.code
where b.date='%s'",
            date('Y-m-d')
        ));
        if ($stocks) {
            Mail::send(new NiceStock($stocks));
        }
    }
}