<?php

namespace App\Console\Commands;

use App\Service\SpiderService;
use App\Service\TestingService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DROP extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'DROP';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '今日跌幅超过7%';

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
        Log::info("DROP start");
        $this->service->getTodayDrop();
        Log::info("DROP end");
    }
}
