<?php

namespace Project\Cli;

use ObjectivePHP\Application\ApplicationInterface;
use ObjectivePHP\Cli\Action\AbstractCliAction;
use Hoa\Event\Bucket;

/**
 * Class SocketWorker
 * @package Project\Cli
 */
class SocketWorker extends AbstractCliAction
{
    public function __construct()
    {
        $this->setCommand('socket-worker');
        $this->setDescription('WebSocket Server');
    }
    
    public function run(ApplicationInterface $app)
    {
        $websocket = new \Hoa\Websocket\Server(
                new \Hoa\Socket\Server('ws://0.0.0.0:9000')
        );

        $websocket->on('open', function () {
            echo 'new connection', "\n";
                return;
        });

        $websocket->on('message', function (Bucket $bucket) {
            $data = $bucket->getData();

            echo 'message: ', $data['message'], "\n";
            $bucket->getSource()->broadcast($data['message']);

            return;
        });

        $websocket->on('close', function () {
                echo 'connection closed', "\n";
                return;
        });

        $websocket->run();

    }
}
