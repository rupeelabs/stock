<?php

namespace App\Console\Commands;

use App\Service\SpiderService;
use App\Service\TestingService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ZLZJ extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ZLZJ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '主力资金';

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
        Log::info("ZLZJ start");
        $this->service->getZhuLiZiJin();
        Log::info("ZLZJ end");
    }
}
