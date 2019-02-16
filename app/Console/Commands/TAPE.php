<?php

namespace App\Console\Commands;

use App\Service\KDJService;
use App\Service\SpiderService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TAPE extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'TAPE';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'TAPE';

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
        Log::info("TAPE start");
        $this->service->getTape();
        Log::info("TAPE end");
    }
}
