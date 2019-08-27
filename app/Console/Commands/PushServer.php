<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

use App\Classes\Socket\Pusher;
use React\EventLoop\Factory as ReactLoop;
use React\ZMQ\Context as ReactContext;
use React\Socket\Server as ReactServer;
use Ratchet\Wamp\WampServer;
use ZMQ;

class PushServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'socketpush:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start socketpush server';

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
        $loop = ReactLoop::create();


        /** @var Pusher $pusher class work with data (get,formatting,send)*/
        $pusher = new Pusher;

        //Listen for the web server to make a ZeroMQ push after on ajax request
        $context = new ReactContext($loop);

        $pull = $context->getSocket(ZMQ::SOCKET_PULL);
        $pull->bind('tcp://127.0.0.1:4444');
        $pull->on('message',[$pusher,'broadcast']);

        $webSock = new ReactServer('0.0.0.0:8080',$loop);

        $webServer = new IoServer(
            new HttpServer(
                new WsServer(
                    new WampServer($pusher)
                )
            ),
            $webSock
        );

        $this->info('Run handle');

        $loop->run();
    }
}
