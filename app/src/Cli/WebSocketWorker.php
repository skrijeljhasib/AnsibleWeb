<?php

namespace Project\Cli;

use ObjectivePHP\Application\ApplicationInterface;
use ObjectivePHP\Cli\Action\AbstractCliAction;
use Project\Config\AnsibleApi;
use Project\Config\MachineTemplate;
use Project\Config\OpenStackAuth;
use Project\Entity\DB\Host;
use Project\Service\GetAllMachineService;
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
	
	//$websocket->on('message', function (Bucket $bucket) use ($pheanstalk) {
	while (true) {
    		$job = $pheanstalk->watch('ansible-get-getallmachine')
                        ->watch('ansible-get-deletemachine')
                        ->watch('ansible-get-ansible-post')
                        ->watch('ansible-get-installmachine')
        		->ignore('default')
        		->reserve();
	        if ($job !== false) {
			echo 'tube : ' . $pheanstalk->statsJob($job)['tube'] . '\n';
                        echo 'job  : ' . $job->getData();
			switch ($pheanstalk->statsJob($job)['tube']) {
				case 'ansible-get-getallmachine' :
					$machines = json_decode($job->getData(), true);
					$hosts_gateway = $app->getServicesFactory()->get('gateway.hosts');
					$hosts_gateway->deleteAll();

                    for($i = 0; $i < count($machines['ansible_facts']['openstack_servers']); $i++) {

                        $name = $machines['ansible_facts']['openstack_servers'][$i]['name'];
                        $hostid = $machines['ansible_facts']['openstack_servers'][$i]['id'];
                        $status = $machines['ansible_facts']['openstack_servers'][$i]['status'];
                        $location = $machines['ansible_facts']['openstack_servers'][$i]['region'];
                        $public_v4= $machines['ansible_facts']['openstack_servers'][$i]['networks']['Ext-Net'][0];
			$hosts_gateway = $app->getServicesFactory()->get('gateway.hosts');
                        $host = new Host();
                        $host->setName($name);
                        $host->setIp($public_v4);
                        $host->setHostID($hostid);
                        $host->setLocation($location);
                        $host->setStatus($status);
                        $hosts_gateway->put($host);
                    }
					
					break;
				case 'ansible-get-deletemachine' :
					$machine = json_decode($job->getData(), true);
					$name = $machine['invocation']['module_args']['name'];	
					if ($machine['result'] == "deleted") {
				        	$hosts_gateway = $app->getServicesFactory()->get('gateway.hosts');
        					$host = $hosts_gateway->fetchByName($name);
        					$hosts_gateway->delete($host);
					}
                                        break;
				case 'ansible-get-installmachine' :
					$machine = json_decode($job->getData(), true);
					$name = $machine['server']['name'];
					$ip = $machine['server']['public_v4'];
					$hostid = $machine['server']['hostId'];
					$status = $machine['server']['status'];
				        $hosts_gateway = $app->getServicesFactory()->get('gateway.hosts');
				        $host = $hosts_gateway->fetchByName($name);
					$host->setIp($ip);
                        		$host->setHostID($hostid);
					$host->setStatus($status);
        				$hosts_gateway->put($host);
					break;
				default: 
					break;
			}
	/*		$websocket->on('message', function (Bucket $bucket) {
				$bucket->getSource()->send($job->getData());
				return;
			});*/
		}

   	//$bucket->getSource()->send($job->getData());
    	$pheanstalk->delete($job);

    	//return;
	//});
	}
	$websocket->on('close', function () {
    		echo 'connection closed', "\n";
    		return;
	});

	echo 'caca';
	$websocket->run();

    }
}
