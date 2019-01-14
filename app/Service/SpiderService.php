<?php
/**
 * Created by PhpStorm.
 * Date: 2019/1/5
 * Time: 下午10:36
 */

namespace App\Service;

use App\Util\JsonPResolver;
use GuzzleHttp\Client;

class SpiderService
{
    private $httpClient;
    public function __construct(Client $client)
    {
        $this->httpClient = $client;
    }

    public function grab($url)
    {
        $body = (string)$this->httpClient->get($url)->getBody();
        $result = JsonPResolver::resolve($body);
        return $result;
    }

    public function getStockList()
    {
        for ($i = 1; $i < 200; $i ++) {
            $url = "http://api.so.eastmoney.com/bussiness/web/QuotationLabelSearch?cb=jQuery1124032282312573125416_1546687772099&token=32A8A21716361A5A387B0D85259A0037&keyword=0&type=1&pi={$i}&ps=100&_=1546687772120";
            $stocks = json_decode($this->grab($url), true);
            if (empty($stocks['Data'][0]['Datas']))
                break;
            foreach ($stocks['Data'][0]['Datas'] as $stock) {
                $result = \DB::select(sprintf("SELECT id From stock where code='%s'", $stock['Code']));
                if (!$result) {
                    \DB::insert(
                        "INSERT INTO stock(code,name,outer_code,jys,market_type,mkt_num,security_type,created_at) VALUE(?,?,?,?,?,?,?,?)",
                        [
                            $stock['Code'],
                            $stock['Name'],
                            isset($stock['OuterCode']) ? $stock['OuterCode'] : 0,
                            $stock['JYS'],
                            $stock['MarketType'],
                            $stock['MktNum'],
                            $stock['SecurityType'],
                            date('Y-m-d H:i:s')
                        ]
                    );
                }
            }
        }
    }

    public function getStockFlow($code = '', $isAll = 'no')
    {
        if ($code) {
            $sql = sprintf("select * from stock where code='%s'", $code);
        } else {
            $sql = "select * from stock";
        }
        $stocks = \DB::select($sql);
        foreach ($stocks as $stock) {
            $code =  $stock->code;
            $url = "http://pdfm.eastmoney.com/EM_UBG_PDTI_Fast/api/js?token=4f1862fc3b5e77c150a2b985b12db0fd&rtntype=6&id={$code}{$stock->market_type}&type=k&authorityType=fa&cb=jsonp1546755196396";
            $stock = json_decode($this->grab($url), true);
            $flows = $stock['data'];
            if ($isAll == 'no') {
                $temp = [];
                $temp[] = last($flows);
                $flows = $temp;
            }
            foreach ($flows as $flow) {
                try {
                    list($date, $open, $close, $highest, $lowest, $vol, $turnover, $amplitude) = explode(',', $flow);
                } catch (\Exception $e) {
                    echo $e->getMessage();
                    echo $code;
                    echo $flow;exit;
                }
                $result = \DB::select(sprintf(
                    "select id from stock_flow where code='%s' and date='%s'",
                    $code, $date
                ));
                if ($result)
                    continue;
                $amplitude = rtrim($amplitude, '%');
                \DB::insert(
                    "INSERT INTO stock_flow(code,open,close,highest,lowest,vol,date,turnover,amplitude,created_at) VALUE(?,?,?,?,?,?,?,?,?,?)",
                    [
                        $code,
                        $open,
                        $close,
                        $highest,
                        $lowest,
                        $vol,
                        $date,
                        $turnover,
                        $amplitude > 0 ? $amplitude : 0,
                        date('Y-m-d H:i:s')
                    ]
                );
            }
        }
    }

    public function getTodayStockFlow()
    {
        $stocks = \DB::select("select code from stock");
        foreach ($stocks as $stock) {
            $code = $stock->code;
            $url = "http://pdfm.eastmoney.com/EM_UBG_PDTI_Fast/api/js?token=4f1862fc3b5e77c150a2b985b12db0fd&rtntype=6&id={$code}1&type=k&authorityType=fa&cb=jsonp1546755196396";
            $stock = json_decode($this->grab($url), true);
            $flows = $stock['data'];
            $today = $flows[count($flows) - 1];
            list($date, $open, $close, $highest, $lowest, $vol, $turnover, $amplitude) = explode(',', $today);
            if ($date != date('Y-m-d')) {
                continue;
            }
            if (\DB::select("select id from stock_flow where code=? and date=?", [$code, $date]))
                continue;
            $amplitude = rtrim($amplitude, '%');
            \DB::insert(
                "INSERT INTO stock_flow(code,open,close,highest,lowest,vol,date,turnover,amplitude,created_at) VALUE(?,?,?,?,?,?,?,?,?,?)",
                [
                    $code,
                    $open,
                    $close,
                    $highest,
                    $lowest,
                    $vol,
                    $date,
                    $turnover,
                    $amplitude > 0 ? $amplitude : 0,
                    date('Y-m-d H:i:s')
                ]
            );
        }
    }
}