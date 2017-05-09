<?php

namespace Project\Action;

use Project\Application;
use Project\Config\AnsibleApi;
use Project\Config\Host;
use Project\Config\MachineTemplate;
use Project\Config\MachineAccess;
use Project\Config\OpenStackAuth;
use Project\Service\CleanService;
use Project\Service\WaitService;
use Project\Service\MachineService;
use Project\Service\PackageService;
use stdClass;

/**
 * Class PlayBook
 * @package Project\Action
 */
class PlayBook
{

    /**
     * @var array $ansible_api          Should contain the server address of the api
     * @var array $openstack_auth       Should contain the openstack authentication config
     * @var array $machine_template     Contains the type of machine which will be installed
     * @var array $machine_access       Contains the username of the machine to connect remotely
     * @var array $host                 Should contain the meta data of the new created host like hostname
     */
    private $ansible_api, $openstack_auth, $machine_template, $machine_access, $host;

    /**
     * Check the playbook get parameter and return a json string of the playbook to the client
     * @param Application $app
     */
    function __invoke(Application $app)
    {
        $this->ansible_api = $app->getConfig()->get(AnsibleApi::class);
        $this->openstack_auth = $app->getConfig()->get(OpenStackAuth::class);
        $this->machine_template = $app->getConfig()->get(MachineTemplate::class);
        $this->machine_access = $app->getConfig()->get(MachineAccess::class);
        $this->host = $app->getConfig()->get(Host::class);

        switch ($app->getRequest()->getParameters()->get('playbook'))
        {
            case 'machine':
                $machineService = new MachineService();
                $json = $machineService->load(
                    $this->ansible_api,
                    $this->openstack_auth,
                    $this->machine_template,
                    $this->host,
                    $app->getEnv(),
                    $app->getRequest()->getParameters()->get('host')
                );
                break;

            case 'package':
                $packageService = new PackageService();
                $json = $packageService->load(
                    $this->ansible_api,
                    $this->machine_access,
                    $app->getRequest()->getParameters()->get('packages')
                );
                break;

            case 'wait':
                $waitService = new WaitService();
                $json = $waitService->load(
                    $this->ansible_api
                );
                break;

            case 'clean':
                $cleanService = new CleanService();
                $json = $cleanService->load(
                    $this->ansible_api
                );
                break;

            default:
                $json = json_encode(new stdClass);
        }

        echo $json;
    }
}