<?php

namespace Project\Cli;

use ObjectivePHP\Application\ApplicationInterface;
use ObjectivePHP\Cli\Action\AbstractCliAction;
use Project\Config\AnsibleApi;
use Project\Config\MachineTemplate;
use Project\Config\OpenStackAuth;
use Project\Entity\DB\Host;
use Hoa\Event\Bucket;
use Pheanstalk\Pheanstalk;

/**
 * Class SocketWorker
 * @package Project\Listener
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
	$ansible_api = $app->getConfig()->get(AnsibleApi::class);

        $pheanstalk = new Pheanstalk($ansible_api['beanstalk']);

	$websocket = new \Hoa\Websocket\Server(
    		new \Hoa\Socket\Server('ws://0.0.0.0:9000')
	);

	$websocket->on('open', function () {
   		echo 'new connection', "\n";
    		return;
	});

	$websocket->on('message', function (Bucket $bucket) {
    		return;
	});
	
	$websocket->on('close', function () {
    		echo 'connection closed', "\n";
    		return;
	});

	$websocket->run();

    }
}
