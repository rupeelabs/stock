<?php
/**
 * Created by PhpStorm.
 * Date: 2019/1/5
 * Time: 下午10:36
 */

namespace App\Service;

use App\Util\JsonPResolver;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Log;

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
            if (empty($flows))
                continue;
            if ($isAll == 'no') {
                $temp = [];
                $temp[] = last($flows);
                $flows = $temp;
            }
            foreach ($flows as $flow) {
                list($date, $open, $close, $highest, $lowest, $vol, $turnover, $amplitude) = explode(',', $flow);
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

    public function getPublishRecord()
    {
        $sql = "select * from stock";
        $stocks = \DB::select($sql);
        foreach ($stocks as $stock) {
            $code =  $stock->code;
            $url = "http://pdfm.eastmoney.com/EM_UBG_PDTI_Fast/api/js?token=4f1862fc3b5e77c150a2b985b12db0fd&rtntype=6&id={$code}{$stock->market_type}&type=k&authorityType=fa&cb=jsonp1546755196396";
            $stock = json_decode($this->grab($url), true);
            $flows = $stock['flow'];

            foreach ($flows as $flow) {
                $date = date('Y-m-d', strtotime($flow['time']));
                $quantity = $flow['ltg'];
                $result = \DB::select(sprintf(
                    "select id from publish_record where code='%s' and date='%s'",
                    $code, $date
                ));
                if ($result)
                    continue;
                \DB::insert(
                    "INSERT INTO publish_record(code,date,quantity) VALUE(?,?,?)",
                    [
                        $code,
                        $date,
                        $quantity
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

    /**
     * 盘口数据
     */
    public function getTape()
    {
        $sql = "select * from stock where market_type=1";
        $stocks = \DB::select($sql);
        foreach ($stocks as $stock) {
            $code =  $stock->code;
            $url = "https://emdcuserdata.eastmoney.com/UserData/GetTapeData?code=sh{$code}";
            $response = json_decode($this->grab($url), true);
            $tape = $response['Data'];

            $result = \DB::select(sprintf(
                "select id from tape where code='%s' and date='%s'",
                $code, $tape['Date']
            ));
//            var_dump($result);exit;
            if ($result) {
                \DB::update(
                    "update tape set tape_z=?, tape_d=? where id=?",
                    [$tape['TapeZ'], $tape['TapeD'], $result[0]->id]
                );
            } else {
                \DB::insert(
                    "INSERT INTO tape(code,date,tape_z,tape_d) VALUE(?,?,?,?)",
                    [
                        $code,
                        $tape['Date'],
                        $tape['TapeZ'],
                        $tape['TapeD']
                    ]
                );
            }
        }
    }


    public function getZhuYaoZhiBiao()
    {
        $sql = "select * from stock where market_type=1";
        $stocks = \DB::select($sql);
        foreach ($stocks as $stock) {
            $code =  $stock->code;
            $url = "http://emh5.securities.eastmoney.com/api/CaiWuFenXi/GetZhuYaoZhiBiaoList";


            $param['fc'] = $code.'01';
            $param['platform'] = 'ios';
            $param['fn'] = '%E5%8D%8E%E4%B8%BD%E5%AE%B6%E6%97%8F';
            $param['stockMarketID'] = '1';
            $param['stockTypeID'] = '2';
            $param['color'] = 'w';
            $param['Sys'] = 'ios';
            $param['ProductType'] = 'cft';
            $param['Version'] = '7.9';
            $param['DeviceType'] = 'iOS 11.4.1';
            $param['UniqueID'] = 'A85e71CBF794-7BDF-4BFE-BF4E-F2D437DA2EFe7455';
            $param['Version'] = '7.9';
            $param['corpType'] = '4';
            $param['reportDateType'] = 0;
            $param['latestCount'] = 4;
            try {
                $response = (string)$this->httpClient->request(
                    'POST',
                    $url,
                    [
                        'headers' => ['Content-Type' => 'application/json;charset=UTF-8'],
                        RequestOptions::JSON => $param
                    ]
                )->getBody();
            } catch (\Exception $e) {
                Log::error("zhibiao spider error:{$code}");
                throw $e;
            }
            $response = json_decode($response, true);
            $netInterest = trim($response['Result']['ZhuYaoZhiBiaoList_QiYe'][0]['Netinterest'], '%');
            $netInterest = $netInterest == '--' ? 0 : $netInterest;
            \DB::update(
                "update stock set net_interest=? where code=?",
                [$netInterest, $code]
            );
        }
    }
}