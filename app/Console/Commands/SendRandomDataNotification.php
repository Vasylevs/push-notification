<?php

namespace App\Console\Commands;

use App\Classes\Socket\Pusher;
use Illuminate\Console\Command;

class SendRandomDataNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendnotification:start {data?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Notification for subscribe users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \ZMQSocketException
     */
    public function handle()
    {
        $data = [
            'topic_id' => 'randomNotification',
            'data' => !empty($this->argument('data'))?$this->argument('data'): 'Num:' . rand(0,999)
        ];

        Pusher::sendDataToServer($data);
        var_dump($data);
    }
}
