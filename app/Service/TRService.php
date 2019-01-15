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


class TRService
{
    public function handle($code = '', $isAll = 'no')
    {
        if ($code) {
            $sql = sprintf("select code from stock where code='%s'", $code);
        } else {
            $sql = "select code from stock";
        }
        $publishRecords = \DB::select(sprintf(
            "select * from publish_record order by code,date"
        ));
        $stocks = \DB::select($sql);
        foreach ($stocks as $stock) {
            $flows = \DB::select(sprintf(
                "select * from stock_flow where code='%s' order by date asc",
                $stock->code
            ));
            foreach ($flows as $key => $flow) {
                if ($isAll == 'no' && $flow->date < date('Y-m-d')) {
                    continue;
                }
                $publishQuantity = $this->getCurPublishQuantity(
                    $publishRecords,
                    $flow->code,
                    $flow->date
                );
                $turnoverRate = round($flow->turnover/$publishQuantity, 4)*10;
                $sql = sprintf(
                    "update stock_flow set turnover_rate='%s' where id=%d",
                    $turnoverRate, $flow->id
                );
                \DB::update($sql);
            }
        }
    }

    public function getCurPublishQuantity(&$publishRecords, $code, $date)
    {
        $quantity = 1;
        foreach ($publishRecords as $key => $record) {
            if ($record->code == $code) {
                if ($record->date < $date) {
                    $quantity = $record->quantity;
                }
            }
        }
        return $quantity;
    }
}