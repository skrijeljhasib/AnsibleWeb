<?php

namespace Project\Api;

use Pheanstalk\Pheanstalk;
use Project\Application;
use Project\Config\Url;
use Project\Service\DeployService;
use stdClass;

/**
 * Class Redeploy
 * @package Project\Action
 */
class Redeploy
{

    /**
     * @var array $ansible_api Should contain the server address of the api
     * @var string $tube Beanstalk
     */
    private $ansible_api,$tube;

    /**
     * Check the playbook get parameter and return a json string of the playbook to the client
     * @param Application $app
     */
    function __invoke(Application $app)
    {

        $this->ansible_api = $app->getConfig()->get(Url::class);
        $pheanstalk = new Pheanstalk($this->ansible_api["beanstalk"]);
	
	if  ($app->getRequest()->getParameters()->get('key') != 'as;jgliughuivawlvgbawyuvgwrkuwygvbqwkuqvgkgnvqdglmuhwlsxi,;sqpj,') { die('wrong key!'); }

        switch ($app->getRequest()->getParameters()->get('playbook')) {
            case 'redeployprojectagain':
                $this->tube = 'ansible-post';
                    $deployService = new DeployService();
                    $json = $deployService->loadagain(
                        $app,
                        $this->ansible_api["ansible_playbook"],
                        $app->getRequest()->getParameters()->get('project')
                    );
                    $pheanstalk->useTube($this->tube)->put($json);
                $this->tube = null;
                break;
            default:
                $json = json_encode(new stdClass);
        }
        if (!empty($this->tube)) {
            $pheanstalk->useTube($this->tube)->put($json);
	}
    }
}
