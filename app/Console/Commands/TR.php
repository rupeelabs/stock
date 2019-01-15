<?php

namespace App\Console\Commands;

use App\Service\TRService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TR extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'TR {all=no} {code?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'TR';

    private $service;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(TRService $service)
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
        Log::info("TR start");
        $isAll = $this->argument('all');
        $code = $this->argument('code');
        $this->service->handle($code, $isAll);
        Log::info("TR end");
    }
}
