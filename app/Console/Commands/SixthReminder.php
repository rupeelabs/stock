<?php

namespace App\Console\Commands;

use App\Mail\OrderShipped;
use App\Service\MailReminderService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


class SixthReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SixthReminder {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'golden above sixty Reminder';

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
        Log::info('Sixth Remind start');
        $date = $this->argument('date');
        $this->service->goldenAboveSixtyRemind($date);
        Log::info('Sixth Remind end');
    }
}
