<?php

namespace App\Console\Commands;

use App\Service\MACDService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MACD extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'MACD {code?} {all=no}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'MACD';

    private $service;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(MACDService $service)
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
        Log::info("MACD start");
        $isAll = $this->argument('all');
        $code = $this->argument('code');
        $this->service->handle($code, $isAll);
        Log::info("MACD end");
    }
}
