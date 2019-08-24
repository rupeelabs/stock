<?php

namespace App\Console\Commands;

use App\Mail\OrderShipped;
use App\Service\MailReminderService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


class EighthReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'EighthReminder {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'kdj less than thenty Reminder';

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
        Log::info('Eighth Remind start');
        $date = $this->argument('date');
        $this->service->kdjLessThanTwentyRemind($date);
        Log::info('Eighth Remind end');
    }
}
