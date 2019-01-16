<?php

namespace App\Console\Commands;

use App\Service\KDJService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class KDJ extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'KDJ {all=no} {code?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'KDJ';

    private $service;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(KDJService $service)
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
        Log::info("KDJ start");
        $isAll = $this->argument('all');
        $code = $this->argument('code');
        $this->service->getKDJ($code, $isAll);
        Log::info("KDJ end");
    }
}
