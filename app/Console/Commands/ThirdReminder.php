<?php

namespace App\Console\Commands;

use App\Mail\OrderShipped;
use App\Service\MailReminderService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


class ThirdReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ThirdReminder {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'New stock Reminder';

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
        Log::info('Third Remind start');
        $date = $this->argument('date');
        $this->service->newStockRemind($date);
        Log::info('Third Remind end');
    }
}
