<?php

namespace App\Console\Commands;

use App\Service\SpiderService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'PS';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish record spider';

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
        Log::info("Publish record spider start");
        $this->service->getPublishRecord();
        Log::info("Publish record spider End");
    }
}
