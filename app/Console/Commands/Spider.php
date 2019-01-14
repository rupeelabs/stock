<?php

namespace App\Console\Commands;

use App\Service\SpiderService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class Spider extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spider';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'spider stock list';

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
        Log::info("Spider start");
        $this->service->getStockList();
        echo '['.date('Y-m-d H:i:s').'] Spider End';
        Log::info("Spider end");
    }
}
