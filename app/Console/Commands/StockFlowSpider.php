<?php

namespace App\Console\Commands;

use App\Service\SpiderService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class StockFlowSpider extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'StockFlowSpider {all=no} {code?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Stock Flow Spider';

    private $service;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(SpiderService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::info("Stock flow spider start");
        $code = $this->argument('code');
        $isAll = $this->argument('all');
        $this->service->getStockFlow($code, $isAll);
        Log::info("Stock flow spider end");
    }
}
