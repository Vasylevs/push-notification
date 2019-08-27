<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Minishlink\WebPush\MessageSentReport;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

class SendNoSocketNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nosocketnotifications:start {data?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Notification for subscribe users no socket';

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
     * @throws \ErrorException
     */
    public function handle()
    {
        $users = User::where('active','=',1)->get();
        $notifications = [];

        foreach ($users as $user){
            $notifications[] = [
                'subscription' => Subscription::create([
                    "endpoint" => $user->endpoint,
                    "keys" => [
                        'p256dh' => $user->key,
                        'auth' => $user->token
                    ],
                ]),
                'payload' => '{msg:"Hello World!"}'
            ];
        }

        if(!empty($notifications)){
            $auth = [
                'GCM' => 'MY_GCM_API_KEY', // deprecated and optional, it's here only for compatibility reasons
                'VAPID' => [
                    'subject' => 'mailto:me@website.com', // can be a mailto: or your website address
                    'publicKey' => config('app.public_web_push_key'), // (recommended) uncompressed public key P-256 encoded in Base64-URL
                    'privateKey' => config('app.private_web_push_key'), // (recommended) in fact the secret multiplier of the private key encoded in Base64-URL
                    'pemFile' => 'path/to/pem', // if you have a PEM file and can link to it on your filesystem
                    'pem' => 'pemFileContent', // if you have a PEM file and want to hardcode its content
                ],
            ];

            $webPush = new WebPush($auth);

            foreach ($notifications as $notification) {
                $webPush->sendNotification(
                    $notification['subscription'],
                    $notification['payload'] // optional (defaults null)
                );

            }

            /**
             * Check sent results
             * @var MessageSentReport $report
             */
            /*foreach ($webPush->flush() as $report) {
                $endpoint = $report->getRequest()->getUri()->__toString();

                if ($report->isSuccess()) {
                    echo "[v] Message sent successfully for subscription {$endpoint}.";
                } else {
                    echo "[x] Message failed to sent for subscription {$endpoint}: {$report->getReason()}";
                }
            }*/
        }
    }
}
