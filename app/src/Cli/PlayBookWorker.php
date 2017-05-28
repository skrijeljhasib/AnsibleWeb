<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 18.05.17
 * Time: 19:23
 */

namespace Project\Cli;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use ObjectivePHP\Application\ApplicationInterface;
use ObjectivePHP\Cli\Action\AbstractCliAction;
use Pheanstalk\Pheanstalk;
use Project\Config\AnsibleApi;

/**
 * Class PlayBookWorker
 * @package Project\Listener
 */
class PlayBookWorker extends AbstractCliAction
{
    public function __construct()
    {
        $this->setCommand('ansible-worker');
        $this->setDescription('Send JSON String as a POST Request to AnsibleApi');
    }

    public function run(ApplicationInterface $app)
    {
        $ansible_api = $app->getConfig()->get(AnsibleApi::class);
        $pheanstalk = new Pheanstalk($ansible_api['beanstalk']);

        while (true) {
            $job =	 $pheanstalk->watch('getallmachine')
				->watch('deletemachine')
				->watch('ansible-post')
				->watch('installmachine')
                		->ignore('default')
                		->reserve();
            if ($job !== false) {

                    $client = new Client(
                        [
                            'base_uri' => $ansible_api["address"],
                            'headers' => ['Content-Type' => 'application/json']
                        ]
                    );
		try {
                    $response = $client->request('POST', '/post_data',
                        [
                            'json' => json_decode($job->getData())
                        ]
                    );

                    if ($response->getStatusCode() == 200) {
			$pheanstalk->useTube('ansible-get-'.$pheanstalk->statsJob($job)['tube'])->put($response->getBody());
                        //$pheanstalk->useTube('ansible-get')->put($response->getBody());
			echo 'tube : ' . $pheanstalk->statsJob($job)['tube'] . '\n';
			echo 'job  : ' . $job->getData();
                        $pheanstalk->delete($job);
			
                    } else {
                        echo 'Request failed: HTTP status code: ' . $response->getStatusCode();
			$pheanstalk->bury($job);
                    }
                } catch (RequestException $e) {
                    echo Psr7\str($e->getRequest());
                    if ($e->hasResponse()) {
                        echo Psr7\str($e->getResponse());
                    }
                }
            } else {
		echo 'waiting...';
                sleep(3);
            }
        }
    }
}
