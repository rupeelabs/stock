<?php

namespace App\Console\Commands;

use App\Mail\OrderShipped;
use App\Service\MailReminderService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;


class FirstReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'FirstReminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Five Rise Reminder';

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
        $this->service->fiveAveRiseRemind();
//        Mail::raw('你好，我是PHP程序！', function ($message) {
//            $to = '396444855@qq.com';
//            $message ->to($to)->subject('纯文本信息邮件测试');
//        });
    }
}
