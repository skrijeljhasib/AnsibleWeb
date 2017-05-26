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

        while (true)
        {
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

                        $id = $machines['ansible_facts']['openstack_servers'][$i]['id'];
                        $name = $machines['ansible_facts']['openstack_servers'][$i]['name'];
                        $public_v4 = $machines['ansible_facts']['openstack_servers'][$i]['public_v4'];

                        $hosts_gateway = $app->getServicesFactory()->get('gateway.hosts');

                        $db_hosts = $hosts_gateway->fetch();

                        $db_ids = [];

                        foreach ($db_hosts as $db_host) {
                            $db_ids[] = $db_host->getId();
                        }

                        if(!in_array($id,$db_ids)) {
                            $host = new Host();
                            $host->setId($id);
                            $host->setName($name);
                            $host->setIp($public_v4);

                            $hosts_gateway->put($host);
                        }
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


    }
}