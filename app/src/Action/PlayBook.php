<?php

namespace Project\Action;

use Pheanstalk\Pheanstalk;
use Project\Application;
use Project\Config\Host;
use Project\Config\MachineAccess;
use Project\Config\MachineTemplate;
use Project\Config\OpenStackAuth;
use Project\Config\OvhDnsAuth;
use Project\Config\TemplateJson;
use Project\Config\Url;
use Project\Service\AddDnsEntryToOvh;
use Project\Service\AddToHostFile;
use Project\Service\CleanService;
use Project\Service\DatabaseService;
use Project\Service\DelDnsEntryToOvh;
use Project\Service\DeleteMachineService;
use Project\Service\GetAllMachineService;
use Project\Service\InstallDependenciesService;
use Project\Service\InstallMachineService;
use Project\Service\InstallPackageService;
use Project\Service\LanguageService;
use Project\Service\NotifyService;
use Project\Service\TemplateService;
use Project\Service\WaitSSHService;
use Project\Service\WebServerService;
use stdClass;

/**
 * Class PlayBook
 * @package Project\Action
 */
class PlayBook
{

    /**
     * @var array $ansible_api Should contain the server address of the api
     * @var array $openstack_auth Should contain the openstack authentication config
     * @var array $machine_template Contains the type of machine which will be installed
     * @var array $machine_access Contains the username of the machine to connect remotely
     * @var array $host FIXED, CUSTOM, RANDOM
     * @var string $tube Beanstalk tube
     */
    private $ansible_api, $openstack_auth, $machine_template,
        $machine_access, $host, $tube, $ovh_dns_auth, $templateJson;

    /**
     * Check the playbook get parameter and return a json string of the playbook to the client
     * @param Application $app
     */
    function __invoke(Application $app)
    {

        $this->ansible_api = $app->getConfig()->get(Url::class);
        $this->openstack_auth = $app->getConfig()->get(OpenStackAuth::class);
        $this->machine_template = $app->getConfig()->get(MachineTemplate::class);
        $this->machine_access = $app->getConfig()->get(MachineAccess::class);
        $this->host = $app->getConfig()->get(Host::class);
        $this->ovh_dns_auth = $app->getConfig()->get(OvhDnsAuth::class);
        $this->templateJson = $app->getConfig()->get(TemplateJson::class);
        $pheanstalk = new Pheanstalk($this->ansible_api["beanstalk"]);

        switch ($app->getRequest()->getParameters()->get('playbook')) {
            case 'getallmachine':
                $this->tube = 'getallmachine';
                $machineService = new GetAllMachineService();
                $json = $machineService->load(
                    $this->machine_template,
                    $this->openstack_auth
                );
                break;

            case 'deletemachine':
                $this->tube = 'deletemachine';
                $machineService = new DeleteMachineService();
                $json = $machineService->load(
                    $this->openstack_auth,
                    $app
                );
                break;

            case 'installmachine':
		$key = "name";
		if (preg_match("/^(?=.{1,255}$)[0-9A-Za-z](?:(?:[0-9A-Za-z]|-){0,61}[0-9A-Za-z])?(?:\.[0-9A-Za-z](?:(?:[0-9A-Za-z]|-){0,61}[0-9A-Za-z])?)*\.?$/", json_decode($app->getRequest()->getParameters()->get('host'))->$key, $output_array)) {
                	$this->tube = 'installmachine';
                	$machineService = new InstallMachineService();
                	$json = $machineService->load(
                    		$this->openstack_auth,
                    		$this->machine_template,
                    		$this->host,
                    		$app
                	);
		}
                break;

            case 'addtohostfile':
                $this->tube = 'ansible-post';
                $addtohostfile = new AddToHostFile();
                $json = $addtohostfile->load($app);
                break;

            case 'installpackage':
                $this->tube = 'ansible-post';
                $packageService = new InstallPackageService();
                $json = $packageService->load(
                    $this->machine_access,
                    $app
                );
                break;

            case 'installdependencies':
                $this->tube = 'ansible-post';
                $installDependencies = new InstallDependenciesService();
                $json = $installDependencies->load(
                    $this->machine_access,
                    $app->getRequest()->getParameters()
                );
                break;

            case 'waitssh':
                $this->tube = 'ansible-post';
                $waitService = new WaitSSHService();
                $json = $waitService->load(
                    $app->getRequest()->getParameters()
                );
                break;

            case 'installtemplate':
                $this->tube = 'ansible-post';
                foreach ($this->templateJson as $templatename) {
                    $templateService = new TemplateService();
                    $json = $templateService->load(
                        $app->getRequest()->getParameters(),
                        $this->ansible_api["ansible_playbook"],
                        $templatename
                    );
                    $pheanstalk->useTube($this->tube)->put($json);
                }
                $this->tube = '';
                break;

            case 'notify':
                $this->tube = 'ansible-post';
                $notifyService = new NotifyService();
                $json = $notifyService->load(
                    $app->getRequest()->getParameters()
                );
                break;

            case 'apache':
                $this->tube = 'ansible-post';
                $webserverService = new WebServerService();
                $webserverService->load(
                    $this->machine_access,
                    $app
                );
                $json = $webserverService->apache(
                    $app->getRequest()->getParameters()
                );
                break;

            case 'nginx':
                $this->tube = 'ansible-post';
                $webserverService = new WebServerService();
                $webserverService->load(
                    $this->machine_access,
                    $app
                );
                $json = $webserverService->nginx();
                break;

            case 'mysql':
                $this->tube = 'ansible-post';
                $databaseService = new DatabaseService();
                $databaseService->load(
                    $this->machine_access,
                    $app
                );
                $json = $databaseService->mysql(
                    $app->getRequest()->getParameters()
                );
                break;

            case 'mongodb':
                $this->tube = 'ansible-post';
                $databaseService = new DatabaseService();
                $databaseService->load(
                    $this->machine_access,
                    $app
                );
                $json = $databaseService->mongodb();
                break;

            case 'php':
                $this->tube = 'ansible-post';
                $languageService = new LanguageService();
                $languageService->load(
                    $this->machine_access,
                    $app
                );
                $json = $languageService->php($app);
                break;

            case 'addDnsEntryToOvh':
                $this->tube = 'ansible-post';
                $addDnsEntryToOvh = new AddDnsEntryToOvh();
                $json = $addDnsEntryToOvh->load(
                    $app,
                    $this->ovh_dns_auth
                );
                break;

            case 'delDnsEntryToOvh':
                $this->tube = 'ansible-post';
                $addDnsEntryToOvh = new DelDnsEntryToOvh();
                $json = $addDnsEntryToOvh->load(
                    $app,
                    $this->ovh_dns_auth
                );
                break;

            default:
                $json = json_encode(new stdClass);
        }
        if (!empty($this->tube)) {
            $pheanstalk->useTube($this->tube)->put($json);
        }

    }
}
