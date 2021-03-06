<?php

namespace App\Console\Commands;

use App\Service\TestingService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AVET extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'AVET {code?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'AVET Loopback testing';

    private $service;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(TestingService $service)
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
        Log::info("AVET start");
        $code = $this->argument('code');
        $this->service->average($code);
        Log::info("AVET end");
    }
}
