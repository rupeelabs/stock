<?php

namespace App\Console\Commands;

use App\Service\KDJService;
use Illuminate\Console\Command;

class KDJ extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'KDJ {code?} {all=no}';

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
        $isAll = $this->argument('all');
        $code = $this->argument('code');
        $this->service->getKDJ($code, $isAll);
    }
}
