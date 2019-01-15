<?php

namespace App\Console\Commands;

use App\Service\MailReminderService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;


class BuyingReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'BuyingReminder {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Buying Reminder';

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
        Log::info("Buying remind start");
        $date = $this->argument('date');
        $this->service->buyingSigRemind($date);
        Log::info("Buying remind end");
    }
}
