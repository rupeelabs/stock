<?php

namespace App\Console\Commands;

use App\Service\TestingService;
use Illuminate\Console\Command;

class MACDT extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'MACDT {code?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'MACD Loopback testing';

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
        $code = $this->argument('code');
        $this->service->macd($code);
    }
}
