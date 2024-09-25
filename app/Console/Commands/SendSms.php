<?php

namespace App\Console\Commands;

use App\Services\MessageService;
use Illuminate\Console\Command;

class SendSms extends Command
{
    private MessageService $service;

    public function __construct(MessageService $messageService)
    {
        parent::__construct(); // Don't forget to call the parent constructor
        $this->service = $messageService;
    }

    protected $signature = 'user:sms:send';

    protected $description = 'Command description';

    public function handle()
    {
        $message = 'Это тест от Eskiz';
        $phone = 998930430959;
        $this->service->sendMessage($phone, $message); // Use the injected service

        return Command::SUCCESS;
    }
}
