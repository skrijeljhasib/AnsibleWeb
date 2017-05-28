<?php

namespace Project\Cli;

use ObjectivePHP\Application\ApplicationInterface;
use ObjectivePHP\Cli\Action\AbstractCliAction;
use Project\Config\AnsibleApi;
use Project\Config\MachineTemplate;
use Project\Config\OpenStackAuth;
use Project\Entity\DB\Host;
use Project\Service\GetAllMachineService;
use Pheanstalk\Pheanstalk;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

/**
 * Class GetWorker
 * @package Project\Listener
 */
class GetWorker extends AbstractCliAction
{
    public function __construct()
    {
        $this->setCommand('get-worker');
        $this->setDescription('Get Worker');
    }
    
    public function run(ApplicationInterface $app)
    {
	$ansible_api = $app->getConfig()->get(AnsibleApi::class);

        $pheanstalk = new Pheanstalk($ansible_api['beanstalk']);

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
    					$pheanstalk->delete($job);
					break;
				case 'ansible-get-deletemachine' :
					$machine = json_decode($job->getData(), true);
					$name = $machine['invocation']['module_args']['name'];	
					if ($machine['result'] == "deleted") {
				        	$hosts_gateway = $app->getServicesFactory()->get('gateway.hosts');
        					$host = $hosts_gateway->fetchByName($name);
        					$hosts_gateway->delete($host);
					}
    					$pheanstalk->delete($job);
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
    					$pheanstalk->delete($job);

			                $client = new Client(
                        			[   
                            				'base_uri' => 'http://stackstorm.test.flash-global.net:8888',
                        			]
                    			);
                			try {
                    				$response = $client->request('GET', '/PlayBook?playbook=addtohostfile&ip='.$ip);
                    				if ($response->getStatusCode() != 200) {
							echo 'Error';
							break;
						}
						$response = $client->request('GET', '/PlayBook?playbook=waitssh&ip='.$ip);
                                                if ($response->getStatusCode() != 200) {
                                                        echo 'Error';
                                                        break;
                                                }
						$response = $client->request('GET', '/PlayBook?playbook=installdependencies&ip='.$ip);
                                                if ($response->getStatusCode() != 200) {
                                                        echo 'Error';
                                                        break;
                                                }
					} catch (RequestException $e) {
                    				echo Psr7\str($e->getRequest());
                    				if ($e->hasResponse()) {
                        				echo Psr7\str($e->getResponse());
                    				}
                			}
					break;
				default: 
					$machine = json_decode($job->getData(), true);
					if ($machine['unreachable'] ==  "true") { $pheanstalk->bury($job); break; };
					if (($machine['state'] == "started") && ($machine['port'] == "22")) { $pheanstalk->delete($job); break; } 
					if ($machine['msg'] == "line added") { $pheanstalk->delete($job); break; }
					if (strpos($machine['sdtout'], 'Setting up python-simplejson') !== false) { $pheanstalk->delete($job); break; }
					break;
			}
		}


	}
    }
}
