<?php

namespace App\Console\Commands;

use App\Service\TestingService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GAT extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'GAT {code?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Golden above sixty  testing';

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
        Log::info("GAT start");
        $code = $this->argument('code');
        $this->service->goldenAboveSixty($code);
        Log::info("GAT end");
    }
}
