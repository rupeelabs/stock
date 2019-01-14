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
    protected $signature = 'SecondReminder';

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
        $this->service->brandistockRemind();
        Log::info('Second remind end');
    }
}
