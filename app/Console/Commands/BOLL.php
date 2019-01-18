<?php

namespace App\Console\Commands;

use App\Service\BOLLService;
use App\Service\MACDService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class BOLL extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'BOLL {all=no} {code?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'BOLL';

    private $service;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(BOLLService $service)
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
        Log::info("BOLL start");
        $isAll = $this->argument('all');
        $code = $this->argument('code');
        $this->service->handle($code, $isAll);
        Log::info("BOLL end");
    }
}
