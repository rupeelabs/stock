<?php

namespace App\Console\Commands;

use App\Service\KDJService;
use App\Service\SpiderService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class LIRUNYIYE extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'LIRUNQIYE';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '盈利企业';

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
        Log::info("YINGLIQIYE start");
        $this->service->getYingliQiye();
        Log::info("YINGLIQIYE end");
    }
}
