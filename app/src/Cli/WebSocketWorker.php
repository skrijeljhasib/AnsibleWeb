<?php

namespace Project\Cli;

use ObjectivePHP\Application\ApplicationInterface;
use ObjectivePHP\Cli\Action\AbstractCliAction;
use Project\Config\AnsibleApi;
use Hoa\Event\Bucket;
use Pheanstalk\Pheanstalk;

/**
 * Class WebSocketWorker
 * @package Project\Listener
 */
class WebSocketWorker extends AbstractCliAction
{
    public function __construct()
    {
        $this->setCommand('websocket-worker');
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

	$websocket->on('message', function (Bucket $bucket) use ($pheanstalk) {
    		$job = $pheanstalk->watch('ansible-get')
        		->ignore('default')
        		->reserve();

   	$bucket->getSource()->send($job->getData());

    	$pheanstalk->delete($job);

    	return;
	});

	$websocket->on('close', function () {
    		echo 'connection closed', "\n";
    		return;
	});

	$websocket->run();
    }
}
