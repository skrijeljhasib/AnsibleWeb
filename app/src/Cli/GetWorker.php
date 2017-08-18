<?php

namespace Project\Cli;

use ObjectivePHP\Application\ApplicationInterface;
use ObjectivePHP\Cli\Action\AbstractCliAction;
use Project\Config\Url;
use Project\Entity\Host;
use Pheanstalk\Pheanstalk;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

/**
 * Class GetWorker
 * @package Project\Cli
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
        $url = $app->getConfig()->get(Url::class);

        $pheanstalk = new Pheanstalk($url['beanstalk']);

        while (true) {
            $job = $pheanstalk
                ->watch('ansible-get-getallmachine')
                ->watch('ansible-get-deletemachine')
                ->watch('ansible-get-installmachine')
                ->watch('ansible-get-ansible-post')
                ->ignore('default')
                ->reserve();

            if ($job !== false) {

                $websocket_client = new \Hoa\Websocket\Client(
                    new \Hoa\Socket\Client($url['websocket_client'])
                );
                $websocket_client->setHost(gethostname());
                $websocket_client->connect();

		$json = json_decode($job->getData(), true);
                $callback['callback'] = $json['name'];
                if (!is_null($callback['callback'])) {
                    $websocket_client->send(json_encode($callback));
                }
                $websocket_client->close();

                switch ($pheanstalk->statsJob($job)['tube']) {
                    case 'ansible-get-getallmachine' :
                        $machines = json_decode($job->getData(), true);
                        $inventory = uniqid("gen",true);
			$hosts_gateway = $app->getServicesFactory()->get('gateway.hosts');
                        for ($i = 0; $i < count($machines['ansible_facts']['openstack_servers']); $i++) {
                            $name = $machines['ansible_facts']['openstack_servers'][$i]['name'];
                            $hostid = $machines['ansible_facts']['openstack_servers'][$i]['id'];
                            $status = $machines['ansible_facts']['openstack_servers'][$i]['status'];
                            $location = $machines['ansible_facts']['openstack_servers'][$i]['region'];
                            $public_v4 = $machines['ansible_facts']['openstack_servers'][$i]['networks']['Ext-Net'][0];
                            $hosts_gateway = $app->getServicesFactory()->get('gateway.hosts');
			    $host = $hosts_gateway->fetchByHostId($hostid);
			    if (!$host) {
				$host = new Host();
                            	$host->setName($name);
                            	$host->setIp($public_v4);
                            	$host->setHostID($hostid);
                            	$host->setLocation($location);
                            	$host->setStatus($status);
			    }
			    $host->setInventory($inventory);
                            $hosts_gateway->put($host);
                        }
			$hosts_gateway->deleteAllFromInventory($inventory);
                        $pheanstalk->bury($job);
                        break;

                    case 'ansible-get-deletemachine' :
                        $machine = json_decode($job->getData(), true);
                        $name = $machine['invocation']['module_args']['name'];
                        if ($machine['result'] == "deleted") {
                            $hosts_gateway = $app->getServicesFactory()->get('gateway.hosts');
                            $host = $hosts_gateway->fetchByName($name);

			    $orders_gateway = $app->getServicesFactory()->get('gateway.orders');
        		    $order = $orders_gateway->fetchByName($host->getName());
        		    if (!empty($order)) { 
				if (!is_null($order->getDns()) && $order->getDns()) {
                                	$dns = json_decode($order->getDns(), true);
					$guzzle_client = new Client(
                            [
                                'base_uri' => $url["ansible_playbook"],
                                'headers' => [
                                    'Content-Type' => 'application/json'
                                ]
                            ]
                        		);
                        		try {
                                   	$response = $guzzle_client->request('GET', '/PlayBook',
                                        [
                                            'query' => [
                                                'playbook' => 'delDnsEntryToOvh',
                                                'host_name' => $name,
                                                'type' => $dns['dns_type'],
                                                'domain_name' => $dns['dns_domain_name'],
                                                'ip' => $host->getIp()
                                            		]
                                        ]	
                                    	);
                                    	if ($response->getStatusCode() != 200) {
                                        	echo 'Error playbook delDnsEntryToOvh';
                                        	break;
                                    	}
                                        $response = $guzzle_client->request('GET', '/PlayBook',
                                        [
                                            'query' => [
                                                'playbook' => 'deletetemplate',
                                                'ip' => $host->getIp()
                                                        ]
                                        ]
                                        );
                                        if ($response->getStatusCode() != 200) {
                                                echo 'Error playbook delete Template';
                                                break;
                                        }
					} catch (RequestException $e) {
                            			echo Psr7\str($e->getRequest());
                            			if ($e->hasResponse()) {
                                			echo Psr7\str($e->getResponse());
                            			}
                        		}
                                }
				$orders_gateway->delete($order); 
			    }
                            $hosts_gateway->delete($host);
                        }
                        $pheanstalk->bury($job);
                        break;

                    case 'ansible-get-installmachine' :
                        $machine = json_decode($job->getData(), true);
                        $name = $machine['server']['name'];
                        $ip = $machine['server']['public_v4'];
                        $hostid = $machine['server']['id'];
                        $status = $machine['server']['status'];
                        $hosts_gateway = $app->getServicesFactory()->get('gateway.hosts');
                        $host = $hosts_gateway->fetchByName($name);
                        $host->setIp($ip);
                        $host->setHostID($hostid);
                        $host->setStatus($status);
                        $hosts_gateway->put($host);

                        $pheanstalk->bury($job);

                        $guzzle_client = new Client(
                            [
                                'base_uri' => $url["ansible_playbook"],
                                'headers' => [
                                    'Content-Type' => 'application/json'
                                ]
                            ]
                        );
                        try {
                            $response = $guzzle_client->request('GET', '/PlayBook',
                                [
                                    'query' => [
                                        'playbook' => 'waitssh',
                                        'ip' => $ip
                                    ]
                                ]
                            );
                            if ($response->getStatusCode() != 200) {
                                echo 'Error playbook waitssh';
                                break;
                            }

			    for ($i = 1; $i <= 3; $i++) {
                            	$response = $guzzle_client->request('GET', '/PlayBook',
                                [
                                    'query' => [
                                        'playbook' => 'installdependencies',
					'step' => $i,
                                        'ip' => $ip
                                    ]
                                ]
                            	);
                            	if ($response->getStatusCode() != 200) {
                                	echo 'Error playbook installdependencies step ' . $step;
                                	break;
                            	}
			    }

                            $orders_gateway = $app->getServicesFactory()->get('gateway.orders');
                            $order = $orders_gateway->fetchByName($name);

			    $dns_order = false;
                            if (!empty($order)) {
                                if (!is_null($order->getDns()) && $order->getDns()) {
                                    $dns = json_decode($order->getDns(), true);
				    $dns_order = true;
				}
			    }
			    if (!$dns_order) {
					$dns['dns_type'] = 'A';
					$dns['dns_domain_name'] = 'vehbo.ovh';
			    }
                                    $response = $guzzle_client->request('GET', '/PlayBook',
                                        [
                                            'query' => [
                                                'playbook' => 'addDnsEntryToOvh',
                                                'host_name' => $name,
                                                'type' => $dns['dns_type'],
                                                'domain_name' => $dns['dns_domain_name'],
                                                'ip' => $ip
                                            ]
                                        ]
                                    );
                                    if ($response->getStatusCode() != 200) {
                                        echo 'Error playbook addDnsEntryToOvh';
                                        break;
                                    }
				    $host->setDomain($dns['dns_domain_name']);
				    $hosts_gateway->put($host);

                            if (!empty($order)) {
                                if (!is_null($order->getLanguage()) && $order->getLanguage()) {
                                    $language = json_decode($order->getLanguage(), true);
                                    $response = $guzzle_client->request('GET', '/PlayBook',
                                        [
                                            'query' => [
                                                'playbook' => 'php',
                                                'language' => $language['language'],
                                                'php_version' => $language['php_version'],
                                                'ip' => $ip
                                            ]
                                        ]
                                    );
                                    if ($response->getStatusCode() != 200) {
                                        echo 'Error playbook php';
                                        break;
                                    }
                                }

                                if (!is_null($order->getPackages()) && $order->getPackages()) {
                                    $response = $guzzle_client->request('GET', '/PlayBook',
                                        [
                                            'query' => [
                                                'playbook' => 'installpackage',
                                                'packages' => implode(',', json_decode($order->getPackages(), true)),
                                                'ip' => $ip
                                            ]
                                        ]
                                    );
                                    if ($response->getStatusCode() != 200) {
                                        echo 'Error playbook installpackage';
                                        break;
                                    }
                                }

                                if (!is_null($order->getWebserver()) && $order->getWebserver()) {
                                    $webserver = json_decode($order->getWebserver(), true);
                                    if ($webserver['webserver'] == 'apache') {
                                        $response = $guzzle_client->request('GET', '/PlayBook',
                                            [
                                                'query' => [
                                                    'playbook' => $webserver['webserver'],
                                                    'document_root' => $webserver['document_root'],
						    'owner_directory' => $webserver['owner_directory'],
                                                    'ip' => $ip
                                                ]
                                            ]
                                        );
                                    }
                                    if ($webserver['webserver'] == 'nginx') {
                                        $response = $guzzle_client->request('GET', '/PlayBook',
                                            [
                                                'query' => [
                                                    'playbook' => $webserver['webserver'],
                                                    'ip' => $ip
                                                ]
                                            ]
                                        );
                                    }
                                    if ($response->getStatusCode() != 200) {
                                        echo 'Error playbook webserver';
                                        break;
                                    }
                                }

                                if (!is_null($order->getDatabase()) && $order->getDatabase()) {
                                    $database = json_decode($order->getDatabase(), true);
                                    if ($database['database'] == 'mysql') {
                                        $response = $guzzle_client->request('GET', '/PlayBook',
                                            [
                                                'query' => [
                                                    'playbook' => 'mysql',
                                                    'mysql_root_password' => $database['mysql_root_password'],
                                                    'mysql_new_user' => $database['mysql_new_user'],
                                                    'mysql_new_user_password' => $database['mysql_new_user_password'],
                                                    'mysql_database' => $database['mysql_database'],
                                                    'ip' => $ip
                                                ]
                                            ]
                                        );
                                    }
                                    if ($database['database'] == 'mongodb') {
                                        $response = $guzzle_client->request('GET', '/PlayBook',
                                            [
                                                'query' => [
                                                    'playbook' => 'mongodb',
                                                    'ip' => $ip
                                                ]
                                            ]
                                        );
                                    }
                                    if ($response->getStatusCode() != 200) {
                                        echo 'Error playbook database';
                                        break;
                                    }
				 }
				 if (!is_null($order->getTemplateJson()) && $order->getTemplateJson() == '"Template:Yes"') {
			            $response = $guzzle_client->request('GET', '/PlayBook',
                                [
                                    'query' => [
                                        'playbook' => 'installtemplate',
                                        'ip' => $ip
                                    ]
                                ]
                                    );
                            		if ($response->getStatusCode() != 200) {
                                		echo 'Error playbook installtemplate';
                                		break;
                            		}
                                }
                                 if (!is_null($order->getDeploy()) && $order->getDeploy() && (!empty($order->getDeploy()))) {
                                    $response = $guzzle_client->request('GET', '/PlayBook',
                                [
                                    'query' => [
                                        'playbook' => 'deployproject',
                                        'ip' => $ip,
					'project' => json_decode($order->getDeploy(), true),
                                        'name' => $name,
					'mysql_root_password' => $database['mysql_root_password']
                                    ]
                                ]
                                    );
                                        if ($response->getStatusCode() != 200) {
                                                echo 'Error playbook deployproject';
                                                break;
                                        }
                                }

                            }

                            $response = $guzzle_client->request('GET', '/PlayBook',
                                [
                                    'query' => [
                                        'playbook' => 'notify',
                                        'ip' => $ip,
					'name' => $name
                                    ]
                                ]
                            );
                            if ($response->getStatusCode() != 200) {
                                echo 'Error playbook notification';
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
                        /*
                        $machine = json_decode($job->getData(), true);
                        if ($machine['unreachable'] == "true") {
                            $pheanstalk->bury($job);
                            break;
                        }
                        if (($machine['state'] == "started") && ($machine['port'] == "22")) {
                            $pheanstalk->delete($job);
                            break;
                        }
                        if ($machine['msg'] == "line added") {
                            $pheanstalk->delete($job);
                            break;
                        }
                        if (strpos($machine['stdout'], 'Setting up python-simplejson') !== false) {
                            $pheanstalk->delete($job);
                            break;
                        }
                        if ($machine['msg'] == "All items completed") {
                            $pheanstalk->delete($job);
                            break;
                        }*/
                        $pheanstalk->bury($job);
                        break;
                }
            } else {
                echo 'waiting...';
                sleep(3);
            }
        }
    }
}
