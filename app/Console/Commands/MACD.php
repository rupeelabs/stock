<?php

namespace App\Console\Commands;

use App\Service\MACDService;
use Illuminate\Console\Command;

class MACD extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'MACD {code?}';

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
        $code = $this->argument('code');
        $this->service->handle($code);
    }
}
