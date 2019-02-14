<?php

namespace App\Console\Commands;

use App\Service\TestingService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CROSS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CROSS {code?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'CROSS testing';

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
        Log::info("CROSS start");
        $code = $this->argument('code');
        $this->service->cross($code);
        Log::info("CROSS end");
    }
}
