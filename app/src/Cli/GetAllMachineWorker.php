<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 26.05.17
 * Time: 13:54
 */

namespace Project\Cli;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use ObjectivePHP\Application\ApplicationInterface;
use ObjectivePHP\Cli\Action\AbstractCliAction;
use Project\Config\AnsibleApi;
use Project\Config\MachineTemplate;
use Project\Config\OpenStackAuth;
use Project\Entity\DB\Host;
use Project\Service\GetAllMachineService;
use GuzzleHttp\Psr7;

class GetAllMachineWorker extends AbstractCliAction
{

    public function __construct()
    {
        $this->setCommand('get-hosts-worker');
        $this->setDescription('Get all hosts and update hosts table');
    }

    /**
     * @param ApplicationInterface $app
     * @return void
     */
    public function run(ApplicationInterface $app)
    {
        $ansible_api = $app->getConfig()->get(AnsibleApi::class);
        $openstack_auth = $app->getConfig()->get(OpenStackAuth::class);
        $machine_template = $app->getConfig()->get(MachineTemplate::class);

        $getAllMachineService = new GetAllMachineService();
        $json = $getAllMachineService->load(
            $machine_template,
            $openstack_auth
        );

//        while (true)
//        {
            try {
                $client = new Client(
                    [
                        'base_uri' => $ansible_api["address"],
                        'headers' => ['Content-Type' => 'application/json']
                    ]
                );

                $response = $client->request('POST', '/post_data',
                    [
                        'json' => json_decode($json)
                    ]
                );

                if ($response->getStatusCode() == 200) {
                    $machines = json_decode($response->getBody(), true);

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

                } else {
                    echo 'Request failed: HTTP status code: ' . $response->getStatusCode();
                }
            } catch (RequestException $e) {
                echo Psr7\str($e->getRequest());
                if ($e->hasResponse()) {
                    echo Psr7\str($e->getResponse());
                }
            }
        }


  //  }
}
