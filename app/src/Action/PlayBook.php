<?php

namespace Project\Action;

use Project\Application;
use Project\Config\AnsibleApi;
use Project\Config\Host;
use Project\Config\MachineTemplate;
use Project\Config\MachineAccess;
use Project\Config\OpenStackAuth;
use Project\Service\DatabaseService;
use Project\Service\InstallDependenciesService;
use Project\Service\WebServerService;
use Project\Service\CleanService;
use Project\Service\GetAllMachineService;
use Project\Service\WaitSSHService;
use Project\Service\InstallMachineService;
use Project\Service\InstallPackageService;
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
            case 'installmachine':
                $machineService = new InstallMachineService();
                $json = $machineService->load(
                    $this->openstack_auth,
                    $this->machine_template,
                    $this->host,
                    $app->getEnv(),
                    $app->getRequest()->getParameters()
                );
                break;

            case 'installpackage':
                $packageService = new InstallPackageService();
                $json = $packageService->load(
                    $this->machine_access,
                    $app->getRequest()->getParameters()
                );
                break;

            case 'installdependencies':
                $installDependencies = new InstallDependenciesService();
                $json = $installDependencies->load(
                    $this->machine_access,
                    $app->getRequest()->getParameters()
                );
                break;

            case 'waitssh':
                $waitService = new WaitSSHService();
                $json = $waitService->load(
                    $app->getRequest()->getParameters()
                );
                break;

            case 'clean':
                $cleanService = new CleanService();
                $json = $cleanService->load(
                    $app->getRequest()->getParameters()
                );
                break;

            case 'getAllMachine':
                $getAllMachineService = new GetAllMachineService();
                $json = $getAllMachineService->load(
                    $this->machine_template,
                    $this->openstack_auth
                );
                break;

            case 'apache':
                $webserverService = new WebServerService();
                $webserverService->load(
                    $this->machine_access,
                    $app->getRequest()->getParameters()
                );
                $json = $webserverService->apache(
                    $app->getRequest()->getParameters()
                );
                break;
            case 'mysql':
                $databaseService = new DatabaseService();
                $databaseService->load(
                    $this->machine_access,
                    $app->getRequest()->getParameters()
                );
                $json = $databaseService->mysql(
                    $app->getRequest()->getParameters()
                );
                break;

            case 'mongodb':
                $databaseService = new DatabaseService();
                $databaseService->load(
                    $this->machine_access,
                    $app->getRequest()->getParameters()
                );
                $json = $databaseService->mongodb(
                    $app->getRequest()->getParameters()
                );
                break;

            default:
                $json = json_encode(new stdClass);
        }

        echo $json;
    }
}