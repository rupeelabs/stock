<?php

namespace App\Console\Commands;

use App\Service\BuyingPointService;
use Illuminate\Console\Command;

class BP extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'BP {code?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $service;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(BuyingPointService $service)
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
        $this->service->run($code);
    }
}
