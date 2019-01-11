<?php

namespace App\Console\Commands;

use App\Service\StockAnalyzerService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class StockAnalyzer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'StockAnalyzer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Stock Analyzer';

    private $service;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(StockAnalyzerService $service)
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
        echo '['.date('Y-m-d H:i:s').'] Stock Analyzer Start';
        $this->service->analyze();
        echo '['.date('Y-m-d H:i:s').'] Stock Analyzer End';
    }
}
