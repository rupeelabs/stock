<?php

namespace App\Console\Commands;

use App\Service\MailReminderService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;


class SecondReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SecondReminder {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'brandistock Reminder';

    private $service;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(MailReminderService $service)
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
        Log::info('Second remind start');
        $date = $this->argument('date');
        $this->service->fiveAveRiseRemind($date);
        Log::info('Second remind end');
    }
}
